<?php

namespace AppBundle\Controller;

use FOS\RestBundle\Controller\Annotations\QueryParam;
use Symfony\Component\HttpKernel\Exception\HttpException;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\Tools\Pagination\Paginator;
use AppBundle\Entity\Fabricante;

class FabricantesController extends FOSRestController
{

     /**
     * @Rest\Post("/fabricante/add")
     */

    public function postAddModeloAction(Request $request)
    {
        try {
            
            $nombre = $request->get('nombre');
            $descripcion = $request->get('descripcion');
            $pais = $request->get('pais');
            $tiempo = $request->get('tiempo');
         
            if($nombre == ""){
                throw new HttpException (400,"El campo Nombre no puede estar vacío");   
            }
            if($descripcion == ""){
                throw new HttpException (400,"El campo Descripción no puede estar vacío");   
            }
            if($pais == ""){
                throw new HttpException (400,"El campo Pais no puede estar vacío");   
            }


            $fabricante = new Fabricante();
            $fabricante -> setFabNombre($nombre);
            $fabricante -> setFabDescripcion($descripcion);
            $fabricante -> setFabPais($pais);
            $fabricante -> setFabTiempo($tiempo);
            $em = $this->getDoctrine()->getManager();
            
            // tells Doctrine you want to (eventually) save the Product (no queries yet)
            $em->persist($fabricante);
            
            // actually executes the queries (i.e. the INSERT query)
            $em->flush();

            $data = array("fabricantes" => array(
                array(
                    "fabricante:"   => $nombre,
                    "id" => $fabricante->getFabId()
                    )
                )  
            );
            return $data;
    
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * @Rest\Get("/fabricante/{fabricanteid}")
     */
    public function getFabricanteAction(Request $request)
    {
        $fabid = $request->get('fabricanteid');

        $repository = $this->getDoctrine()->getRepository('AppBundle:Fabricante');
        $fabricante = $repository->findOneByfabId($fabid);
        return $fabricante;
    }
    
    /**
     * @Rest\Get("/fabricante")
     */
    public function getAllModeloAction()
    {        
        $repository = $this->getDoctrine()->getRepository('AppBundle:Fabricante');
        $query = $repository->createQueryBuilder('f')
            ->getQuery();
        $fabricante = $query->getResult();
        return $fabricante;
    }

     /**
     * @Rest\Get("/fabricante/{limit}/{page}")
     */
    public function getAllFabricantePaginatedAction(Request $request)
    {
        // Set up the limit and page vars from request
        $limit = $request->get('limit');
        $page = $request->get('page');

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
        $repository = $this->getDoctrine()->getRepository('AppBundle:Fabricante');
        // The dsql syntax query
        $query = $repository->createQueryBuilder('fabricante')
            ->getQuery()
            ->setFirstResult($limit * ($page - 1))
            ->setMaxResults($limit);
        // Build the paginator
        $paginator = new Paginator($query, $fetchJoinCollection = true);
        // Construct the response
        $response = array(
            'fabricante' => $paginator->getIterator(),
            'totalModelosReturned' => $paginator->getIterator()->count(),
            'totalModelos' => $paginator->count()
        );
        // Send the response
        return $response;
    }
    /**
     * @Rest\Get("/fabricante/{limit}/{page}/{searchtext}")
     */
    public function getAllFabricantePaginatedSearchAction(Request $request)
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
        $repository = $this->getDoctrine()->getRepository('AppBundle:Fabricante');
    
        // The dsql syntax query
        $query = $repository->createQueryBuilder('fabricante')
            ->where('fabricante.fabNombre = :searchtext')
            ->orwhere('fabricante.fabDescripcion LIKE :searchtext')
            ->orWhere('fabricante.fabPais LIKE :searchtext')
            ->orWhere('fabricante.fabTiempo LIKE :searchtext')
            ->setParameter('searchtext',"%" .$searchtext ."%")
            ->getQuery()
            ->setFirstResult($limit * ($page - 1))
            ->setMaxResults($limit);
        // Build the paginator
        $paginator = new Paginator($query, $fetchJoinCollection = true);
        // Construct the response
        $response = array(
            'fabricante' => $paginator->getIterator(),
            'totalFabricantesReturned' => $paginator->getIterator()->count(),
            'totalFabricantes' => $paginator->count(),
            'searchedText' => $searchtext
        );
        // Send the response
        return $response;
    }


    /**
     * @Rest\Post("/fabricante/edit/{fabricanteid}")
     */
     public function postUpdateModeloAction(Request $request)
     {
         $fabricanteid = $request->get('fabricanteid');
         $nombre = $request->get('nombre');
         $descripcion = $request->get('descripcion');
         $pais = $request->get('pais');
         $tiempo = $request->get('tiempo');

         
         if($fabricanteid == "" || !$fabricanteid){
             throw new HttpException (400,"Debe proveer un id para modificar el registro.");
         }

          if($nombre == ""){
                throw new HttpException (400,"El campo nombre no puede estar vacío");   
            }
            if($descripcion == ""){
                throw new HttpException (400,"El campo descripción no puede estar vacío");   
            }
            if($pais == ""){
                throw new HttpException (400,"El campo pais no puede estar vacío");   
            }
         
         $em = $this->getDoctrine()->getManager();
         $fabricante = $em->getRepository('AppBundle:Fabricante')
            ->find($fabricanteid);


        if (!$fabricante) {
        throw new HttpException (400,"No se ha encontrado el fabricante especificado: " .$fabricanteid);
         }


        $fabricante -> setFabNombre($nombre);
        $fabricante -> setFabDescripcion($descripcion);
        $fabricante -> setFabPais($pais);
        $fabricante -> setFabTiempo($tiempo);
        $em->flush();

        $data = array(
            'message' => 'El fabricante ha sido actualizado',
             'fabricanteid' => $fabricanteid,
             'nombre' => $nombre,
             'descripcion' => $descripcion
         );

         return $data;

     }
}   