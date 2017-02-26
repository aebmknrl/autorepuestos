<?php

namespace AppBundle\Controller;

use FOS\RestBundle\Controller\Annotations\QueryParam;
use Symfony\Component\HttpKernel\Exception\HttpException;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\Tools\Pagination\Paginator;
use AppBundle\Entity\NombreParte;

class NombrePartesController extends FOSRestController
{

     /**
     * @Rest\Post("/nombre_parte/add")
     */
    public function postAddNombreParteAction(Request $request)
    {
        try {       
            // Obtaining vars from request
            $parNombre          = $request->get('parNombre');
            $parNombreIngles    = $request->get('parNombreIngles');
            $parNombreOtros     = $request->get('parNombreOtros');     
            $parGrupo           = $request->get('parGrupo');

            // Check for mandatory fields
            if($parNombre == ""){
                throw new HttpException (400,"El campo nombre no puede estar vacío");
            }


            // Find the relationship with Grupo
            $grupo = $this->getDoctrine()->getRepository('AppBundle:Grupo')->find($parGrupo);        
            if($grupo == ""){
                throw new HttpException (400,"El grupo especificado no existe");   
            }

            // Create the "NombreParte"
            $nombreParte     = new NombreParte();
            $nombreParte     -> setParNombre($parNombre);
            $nombreParte     -> setParNombreIngles($parNombreIngles);
            $nombreParte     -> setParNombreOtros($parNombreOtros);
            $nombreParte     -> setParGrupoId($parGrupo);
            $em         = $this->getDoctrine()->getManager();
            
            // tells Doctrine you want to (eventually) save (no queries yet)
            $em->persist($nombreParte);         
            // actually executes the queries (i.e. the INSERT query)
            $em->flush();
            
            $response = array("nombrePartes" => array(
                array(
                    'message' => 'Nuevo nombre creado',
                    "nombre"   => $nombre
                    )
                )  
            ); 
            return $response;
        }
        catch (\Doctrine\DBAL\Exception\UniqueConstraintViolationException $e){
            throw new HttpException (409,"Error: El nombre de la parte ya esxiste."); 
            } 
        catch (Exception $e) {
            return $e->getMessage();
            } 
    }

    
    /**
     * @Rest\Get("/nombre_parte")
     */
    public function getAllNombreParteAction()
    {
        // Initialize the 'Nombre Parte' data repository
        $repository     = $this->getDoctrine()->getRepository('AppBundle:NombreParte');
        $query          = $repository->createQueryBuilder('n')->getQuery();
        $nombrePartes   = $query->getResult();
        return $nombrePartes;
    }


    /**
     * @Rest\Get("/nombre_parte/{parnombreid}")
     */
    public function getNombreParteAction(Request $request)
    {
        $parNombreId    = $request->get('parnombreid');
        $repository     = $this->getDoctrine()->getRepository('AppBundle:NombreParte');
        $nombrePartes   = $repository->findOneByparNombreId($parNombreId);
        return $nombrePartes;
    }


     /**
     * @Rest\Get("/nombre_parte/{limit}/{page}")
     */
    public function getAllNombrePartePaginatedAction(Request $request)
    {
        // Set up the limit and page vars from request
        $limit  = $request->get('limit');
        $page   = $request->get('page');

        // Check if the params are numbers before continue
        if(!is_numeric($page)) {
            throw new HttpException (400,"Por favor use solo números para la página");  
        }
        // Check if the limit asked has all or not.
        if (!is_numeric($limit)) {
            if ($limit != 'todos') {
                if ($limit != 'Todos') {
                    throw new HttpException (400,"Por favor use solo números para el límite o indique si son 'todos'");  
                } else {
                    $limit = 10000;
                }
            } else {
                $limit = 10000;
            }
        }

        // Connect with the autoparts db repository
        $repository     = $this->getDoctrine()->getRepository('AppBundle:NombreParte');
        // The dsql syntax query
        $query          = $repository->createQueryBuilder('n')->getQuery()->setFirstResult($limit * ($page - 1))->setMaxResults($limit);
        // Build the paginator
        $paginator      = new Paginator($query, $fetchJoinCollection = true);
        // Construct the response
        $response = array(
            'nombrePartes' => $paginator->getIterator(),
            'totalNombrePartesReturned' => $paginator->getIterator()->count(),
            'totalNombrePartes' => $paginator->count()
        );
        // Send the response
        return $response;
    }



    /**
     * @Rest\Get("/nombre_parte/{limit}/{page}/{searchtext}")
     */
    public function getAllNombrePartePaginatedSearchAction(Request $request)
    {
        // Set up the limit and page vars from request
        $limit = $request->get('limit');
        $page = $request->get('page');
        $searchtext = $request->get('searchtext');

        // Check if the params are numbers before continue
        if(!is_numeric($page)) {
            throw new HttpException (400,"Por favor use solo números para la página");  
        }
        // Check if the limit asked has all or not.
        if (!is_numeric($limit)) {
            if ($limit != 'todos') {
                if ($limit != 'Todos') {
                    throw new HttpException (400,"Por favor use solo números para el límite o indique si son 'todos'");  
                } else {
                    $limit = 10000;
                }
            } else {
                $limit = 10000;
            }
        }

        if($searchtext == ""){
            throw new HttpException (400,"Escriba un texto para la búsqueda"); 
        }

        // Connect with the autoparts db repository
        $repository = $this->getDoctrine()->getRepository('AppBundle:NombreParte');
    
        // The dsql syntax query
        $query = $repository->createQueryBuilder('nombreParte')->join('nombreParte.parGrupo','g')
            ->where('g.grupoNombre LIKE :searchtext')
            ->orwhere('nombreParte.parNombre LIKE :searchtext')
            ->orWhere('nombreParte.parNombreIngles LIKE :searchtext')
            ->orWhere('nombreParte.parNombreOtros LIKE :searchtext')
            ->setParameter('searchtext',"%" .$searchtext ."%")
            ->getQuery()
            ->setFirstResult($limit * ($page - 1))
            ->setMaxResults($limit);
        // Build the paginator
        $paginator = new Paginator($query, $fetchJoinCollection = true);
        // Construct the response
        $response = array(
        'nombrePartes' => $paginator->getIterator(),
        'totalNombrePartesReturned' => $paginator->getIterator()->count(),
        'totalNombrePartes' => $paginator->count(),
        'searchedText' => $searchtext
        );
        // Send the response
        return $response;
    }


    /**
     * @Rest\Post("/nombre_parte/edit/{parnombreid}")
     */
     public function postUpdateNombreParteAction(Request $request)
     {
        try
        {
            $parNombreId        = $request->get('parnombreid');
            $parNombre          = $request->get('parNombre');
            $parNombreIngles    = $request->get('parNombreIngles');
            $parNombreOtros     = $request->get('parNombreOtros');     
            $parGrupo           = $request->get('parGrupo');

        $grupo = $this->getDoctrine()->getRepository('AppBundle:Grupo')->find($parGrupo);


        if($parNombreId == "" || !$parNombreId)
        {
             throw new HttpException (400,"Debe proveer un id para modificar el registro.");  
        }

        if($parNombre == "")
        {
                throw new HttpException (400,"El campo nombre no puede estar vacío");   
        }

        $em = $this->getDoctrine()->getManager();
        $nombreParte = $em->getRepository('AppBundle:NombreParte')->find($parNombreId);

        if (!$nombreParte) 
        {
            throw new HttpException (400,"No se ha encontrado el grupo especificado: " .$parNombreId);
        }

            $nombreParte     -> setParNombre($parNombre);
            $nombreParte     -> setParNombreIngles($parNombreIngles);
            $nombreParte     -> setParNombreOtros($parNombreOtros);
            $nombreParte     -> setParGrupoId($parGrupo);

        $em->flush();

        $response = array(
            'message'      => 'El nombre de parte '.$nombreParte->getParNombre().' ha sido actualizado',
         ); 
         return $response;
        }
        catch (\Doctrine\DBAL\Exception\UniqueConstraintViolationException $e){
            throw new HttpException (409,"Error: El nombre de la parte ya esxiste."); 
            } 
        catch (Exception $e) {
            return $e->getMessage();
            } 
     }


    /**
     * @Rest\Delete("/nombre_parte/delete/{parnombreid}")
     */
    public function deleteRemoveNombreParteAction(Request $request)
    {
        $parNombreId = $request->get('parnombreid');
        // get EntityManager
        $em             = $this->getDoctrine()->getManager();
        $parnombretoremove = $em->getRepository('AppBundle:NombreParte')->find($parNombreId);

        if ($parnombretoremove != "") {      
            // Remove it and flush
            $em->remove($parnombretoremove);
            $em->flush();
            $response = array(
                'message'   => 'El nombre Parte '.$parnombretoremove->getParNombre().' ha sido eliminado',
                'parnombreid'  => $parNombreId
            );
             return $response;
        } else{
            throw new HttpException (400,"No se ha encontrado el nombre especificado ID: " .$parNombreId);
        }
        
    }

}   