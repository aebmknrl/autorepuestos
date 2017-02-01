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
                    "id"                    => $grupo->getId()
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
        $grupos		= $query->getResult();
        return $grupos;
    }


    /**
     * @Rest\Get("/grupo/{id}")
     */
    public function getGrupoAction(Request $request)
    {
        $id             = $request->get('id');
        $repository     = $this->getDoctrine()->getRepository('AppBundle:Grupo');
        $grupo          = $repository->findOneById($id);
        return $grupo;
    }


     /**
     * @Rest\Get("/grupo/{limit}/{page}")
     */
    public function getAllGrupoPaginatedAction(Request $request)
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
        $repository     = $this->getDoctrine()->getRepository('AppBundle:Grupo');
        // The dsql syntax query
        $query          = $repository->createQueryBuilder('g')->getQuery()->setFirstResult($limit * ($page - 1))->setMaxResults($limit);
        // Build the paginator
        $paginator      = new Paginator($query, $fetchJoinCollection = true);
        // Construct the response
        $response = array(
            'grupo'                     => $paginator->getIterator(),
            'total en página'           => $paginator->getIterator()->count(),
            'total'                     => $paginator->count()
        );
        // Send the response
        return $response;
    }


    /**
     * @Rest\Get("/grupo/{limit}/{page}/{searchtext}")
     */
    public function getAllGrupoPaginatedSearchAction(Request $request)
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
        $repository = $this->getDoctrine()->getRepository('AppBundle:Grupo');
    
        // The dsql syntax query
        $query = $repository->createQueryBuilder('grupo')->join('grupo.grupoPadre','g')
            ->where('g.grupoNombre LIKE :searchtext')
            ->orwhere('grupo.grupoNombre LIKE :searchtext')
            ->orWhere('grupo.descripcion LIKE :searchtext')
            ->setParameter('searchtext',"%" .$searchtext ."%")
            ->getQuery()
            ->setFirstResult($limit * ($page - 1))
            ->setMaxResults($limit);
        // Build the paginator
        $paginator = new Paginator($query, $fetchJoinCollection = true);
        // Construct the response
        $response = array(
            'grupos'                => $paginator->getIterator(),
            'total  en pagina'		=> $paginator->getIterator()->count(),
            'total  encontrados'	=> $paginator->count(),
            'busqueda por'          => $searchtext
        );
        // Send the response
        return $response;
    }


    /**
     * @Rest\Post("/grupo/edit/{id}")
     */
     public function postUpdateGrupoAction(Request $request)
     {
        try
        {
        $id = $request->get('id');
        $grupoNombre = $request->get('nombre');
        $descripcion = $request->get('descripcion');
        $grupoPadre = $request->get('grupoPadre');
        $grupoP = $this->getDoctrine()->getRepository('AppBundle:Grupo')->find($grupoPadre);


        if($id == "" || !$id)
        {
             throw new HttpException (400,"Debe proveer un id para modificar el registro.");  
        }

        if($grupoNombre == "")
        {
                throw new HttpException (400,"El campo nombre no puede estar vacío");   
        }

        if ($grupoPadre != ""){
        if(!$grupoP)
        {
               throw new HttpException (400,"Debe especificar un ID de Grupo válido");   
        }
        }
         
        $em = $this->getDoctrine()->getManager();
        $grupo = $em->getRepository('AppBundle:Grupo')->find($id);

        if (!$grupo) 
        {
            throw new HttpException (400,"No se ha encontrado el grupo especificado: " .$id);
        }

        $grupo     -> setGrupoNombre($grupoNombre);
        $grupo     -> setDescripcion($descripcion);
        $grupo     -> setGrupoPadre($grupoP);
        $em->flush();

        $response = array(
            'message'      => 'El grupo '.$grupo->getGrupoNombre().' ha sido actualizado',
            'grupoid'     => $id,
            'descripcion'  => $descripcion,
         ); 
         return $response;
        }

        catch (\Doctrine\DBAL\Exception\UniqueConstraintViolationException $e){
            throw new HttpException (409,"Error: El nombre del grupo ya existe."); 
            } 
        catch (Exception $e) {
            return $e->getMessage();
            } 
     }


    /**
     * @Rest\Delete("/grupo/delete/{id}")
     */
    public function deleteRemoveGrupoAction(Request $request)
    {
        $id = $request->get('id');
        // get EntityManager
        $em             = $this->getDoctrine()->getManager();
        $grupotoremove = $em->getRepository('AppBundle:Grupo')->find($id);

        if ($grupotoremove != "") {      
            // Remove it and flush
            $em->remove($grupotoremove);
            $em->flush();
            $response = array(
                'message'   => 'El grupo '.$grupotoremove->getGrupoNombre().' ha sido eliminado',
                'id'  => $id
            );
             return $response;
        } else{
            throw new HttpException (400,"No se ha encontrado el grupo especificado ID: " .$id);
        }
        
    }

}   