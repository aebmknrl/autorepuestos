<?php

namespace AppBundle\Controller;

use FOS\RestBundle\Controller\Annotations\QueryParam;
use Symfony\Component\HttpKernel\Exception\HttpException;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\Tools\Pagination\Paginator;
use AppBundle\Entity\Grupo;

class GruposController extends FOSRestController
{

     /**
     * @Rest\Post("/grupo/add")
     */
    public function postAddGrupoAction(Request $request)
    {
        try {       
            // Obtaining vars from request
            $grupoNombre    = $request->get('nombre');
            $descripcion    = $request->get('descripcion');
            $grupoPadre     = $request->get('grupoPadre');

            // Check for mandatory fields
            if($grupoNombre == ""){
                throw new HttpException (400,"El campo nombre no puede estar vacío");
            }

            // Find the relationship with Grupo Padre
            if($grupoPadre != ""){
            $GrupoPadre = $this->getDoctrine()->getRepository('AppBundle:Grupo')->find($grupoPadre);        
            if($GrupoPadre == ""){
                throw new HttpException (400,"El grupo especificado no existe");   
            }
            }else{
                $GrupoPadre = null;
            }

            // Create the "Grupo"
            $grupo     = new Grupo();
            $grupo     -> setGrupoNombre($grupoNombre);
            $grupo     -> setDescripcion($descripcion);
            $grupo     -> setGrupoPadre($GrupoPadre);
            $em         = $this->getDoctrine()->getManager();
            
            // tells Doctrine you want to (eventually) save (no queries yet)
            $em->persist($grupo);         
            // actually executes the queries (i.e. the INSERT query)
            $em->flush();
            
            $response = array("grupos" => array(
                array(
                    "Nuevo grupo creado"    => $grupoNombre,
                    "Descripción: "         => $descripcion,
                    "Grupo Padre:"          => $GrupoPadre->getGrupoNombre(),
                    "id"                    => $modelo->getModId()
                    )
                )  
            ); 
            return $response;
        }
        catch (\Doctrine\DBAL\Exception\UniqueConstraintViolationException $e){
            throw new HttpException (409,"Error: El nombre del grupo ya esxiste."); 
            } 
        catch (Exception $e) {
            return $e->getMessage();
            } 
    }

    
    /**
     * @Rest\Get("/grupo")
     */
    public function getAllGrupoAction()
    {
        // Initialize the 'Grupo' data repository
        $repository = $this->getDoctrine()->getRepository('AppBundle:Grupo');
        $query      = $repository->createQueryBuilder('g')->getQuery();
        $grupos    = $query->getResult();
        return $grupos;
    }


    /**
     * @Rest\Get("/modelo/{modeloid}")
     */
    public function getModeloAction(Request $request)
    {
        $modeloid       = $request->get('modeloid');
        $repository     = $this->getDoctrine()->getRepository('AppBundle:Modelo');
        $modelos        = $repository->findOneBymodId($modeloid);
        return $modelos;
    }


     /**
     * @Rest\Get("/modelo/{limit}/{page}")
     */
    public function getAllModeloPaginatedAction(Request $request)
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
        $repository     = $this->getDoctrine()->getRepository('AppBundle:Modelo');
        // The dsql syntax query
        $query          = $repository->createQueryBuilder('m')->getQuery()->setFirstResult($limit * ($page - 1))->setMaxResults($limit);
        // Build the paginator
        $paginator      = new Paginator($query, $fetchJoinCollection = true);
        // Construct the response
        $response = array(
            'modelos'                   => $paginator->getIterator(),
            'total modelos en página'   => $paginator->getIterator()->count(),
            'total modelos'             => $paginator->count()
        );
        // Send the response
        return $response;
    }


    /**
     * @Rest\Get("/modelo/{limit}/{page}/{searchtext}")
     */
    public function getAllModeloPaginatedSearchAction(Request $request)
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
        $repository = $this->getDoctrine()->getRepository('AppBundle:Modelo');
    
        // The dsql syntax query
        $query = $repository->createQueryBuilder('modelo')->join('modelo.marcaMar','m')
            ->where('m.marNombre LIKE :searchtext')
            ->orwhere('modelo.modObservacion LIKE :searchtext')
            ->orWhere('modelo.modNombre LIKE :searchtext')
            ->orWhere('modelo.modId LIKE :searchtext')
            ->setParameter('searchtext',"%" .$searchtext ."%")
            ->getQuery()
            ->setFirstResult($limit * ($page - 1))
            ->setMaxResults($limit);
        // Build the paginator
        $paginator = new Paginator($query, $fetchJoinCollection = true);
        // Construct the response
        $response = array(
            'modelos'                   => $paginator->getIterator(),
            'total modelos en pagina'   => $paginator->getIterator()->count(),
            'total modelos encontrados' => $paginator->count(),
            'busqueda por'              => $searchtext
        );
        // Send the response
        return $response;
    }


    /**
     * @Rest\Post("/modelo/edit/{modeloid}")
     */
     public function postUpdateModeloAction(Request $request)
     {
        try
        {
        $modeloid = $request->get('modeloid');
        $nombre = $request->get('nombre');
        $observacion = $request->get('observacion');
        $marcaid = $request->get('marcaid');
        $marca = $this->getDoctrine()->getRepository('AppBundle:Marca')->find($marcaid);


        if($modeloid == "" || !$modeloid)
        {
             throw new HttpException (400,"Debe proveer un id para modificar el registro.");  
        }

        if($nombre == "")
        {
                throw new HttpException (400,"El campo nombre no puede estar vacío");   
        }
        if($marcaid == "")
        {
               throw new HttpException (400,"El campo marca no puede estar vacío");   
        }
        if(!$marcaid)
        {
               throw new HttpException (400,"Debe especificar un ID de Marca válido");   
        }
         
        $em = $this->getDoctrine()->getManager();
        $modelo = $em->getRepository('AppBundle:Modelo')->find($modeloid);

        if (!$modelo) 
        {
            throw new HttpException (400,"No se ha encontrado el modelo especificado: " .$modeloid);
        }

        $modelo->setModNombre($nombre);
        $modelo->setModObservacion($observacion);
        $modelo->setMarcaMar($marca);
        $em->flush();

        $response = array(
            'message'      => 'El modelo '.$modelo->getModNombre().' ha sido actualizado',
            'modeloid'     => $modeloid,
            'observacion'  => $observacion,
            'marca'        => $marca->getMarNombre()
         ); 
         return $response;
        }
        catch (\Doctrine\DBAL\Exception\UniqueConstraintViolationException $e){
            throw new HttpException (409,"Error: El nombre del modelo ya esxiste."); 
            } 
        catch (Exception $e) {
            return $e->getMessage();
            } 
     }


    /**
     * @Rest\Delete("/modelo/delete/{modeloid}")
     */
    public function deleteRemoveModeloAction(Request $request)
    {
        $modeloid = $request->get('modeloid');
        // get EntityManager
        $em             = $this->getDoctrine()->getManager();
        $modelotoremove = $em->getRepository('AppBundle:Modelo')->find($modeloid);

        if ($modelotoremove != "") {      
            // Remove it and flush
            $em->remove($modelotoremove);
            $em->flush();
            $response = array(
                'message'   => 'El modelo '.$modelotoremove->getModNombre().' ha sido eliminado',
                'modeloid'  => $modeloid
            );
             return $response;
        } else{
            throw new HttpException (400,"No se ha encontrado el modelo especificado ID: " .$modeloid);
        }
        
    }

}   