<?php

namespace AppBundle\Controller;

use FOS\RestBundle\Controller\Annotations\QueryParam;
use Symfony\Component\HttpKernel\Exception\HttpException;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\Tools\Pagination\Paginator;
use AppBundle\Entity\Marca;

class MarcasController extends FOSRestController
{

     /**
     * @Rest\Post("/marca/add")
     */
    public function postAddMarcaAction(Request $request)
    {
        try {
            
            $nombre = $request->get('nombre');
            $observacion = $request->get('observacion');
            
            if($nombre == ""){
                throw new HttpException (400,"El campo nombre no puede estar vacío");   
            }
            if($observacion == ""){
                throw new HttpException (400,"El campo observacion no puede estar vacío");   
            }

            $marca = new Marca();
            $marca -> setMarNombre($nombre);
            $marca -> setMarObservacion($observacion);
            $em = $this->getDoctrine()->getManager();
            
            // tells Doctrine you want to (eventually) save the Product (no queries yet)
            $em->persist($marca);
            
            // actually executes the queries (i.e. the INSERT query)
            $em->flush();
            
            $data = array("marca" => array(
                array(
                    "marca"   => $nombre,
                    "id" => $marca->getMarId()
                    )
                )  
            ); 
            return $data;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * @Rest\Get("/marca/{marcaid}")
     */
    public function getMarcaAction(Request $request)
    {
        $marcaid = $request->get('marcaid');

        $repository = $this->getDoctrine()->getRepository('AppBundle:Marca');
        $marcas = $repository->findOneBymarId($marcaid);
        return $marcas;
    }
    
    /**
     * @Rest\Get("/marca")
     */
    public function getAllMarcaAction()
    {
        // Initialize the 'Marca' data repository
        $repository = $this->getDoctrine()->getRepository('AppBundle:Marca');
        $query = $repository->createQueryBuilder('p')
            ->getQuery();
        $marcas = $query->getResult();
        return $marcas;
    }

     /**
     * @Rest\Get("/marca/{limit}/{page}")
     */
    public function getAllMarcaPaginatedAction(Request $request)
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
        $repository = $this->getDoctrine()->getRepository('AppBundle:Marca');
        // The dsql syntax query
        $query = $repository->createQueryBuilder('marca')
            ->getQuery()
            ->setFirstResult($limit * ($page - 1))
            ->setMaxResults($limit);
        // Build the paginator
        $paginator = new Paginator($query, $fetchJoinCollection = true);
        // Construct the response
        $response = array(
            'marcas' => $paginator->getIterator(),
            'totalMarcasReturned' => $paginator->getIterator()->count(),
            'totalMarcas' => $paginator->count()
        );
        // Send the response
        return $response;
    }
    /**
     * @Rest\Get("/marca/{limit}/{page}/{searchtext}")
     */
    public function getAllMarcaPaginatedSearchAction(Request $request)
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
        $repository = $this->getDoctrine()->getRepository('AppBundle:Marca');
        // The dsql syntax query
        $query = $repository->createQueryBuilder('marca')
            ->where('marca.marObservacion LIKE :searchtext')
            ->orWhere('marca.marNombre LIKE :searchtext')
            ->orWhere('marca.marId LIKE :searchtext')
            ->setParameter('searchtext',"%" .$searchtext ."%")
            ->getQuery()
            ->setFirstResult($limit * ($page - 1))
            ->setMaxResults($limit);
        // Build the paginator
        $paginator = new Paginator($query, $fetchJoinCollection = true);
        // Construct the response
        $response = array(
            'marcas' => $paginator->getIterator(),
            'totalMarcasReturned' => $paginator->getIterator()->count(),
            'totalMarcas' => $paginator->count(),
            'searchedText' => $searchtext
        );
        // Send the response
        return $response;
    }


    /**
     * @Rest\Post("/marca/edit/{marcaid}")
     */
     public function postUpdateMarcaAction(Request $request)
     {
         $marcaid = $request->get('marcaid');
         $nombre = $request->get('nombre');
         $observacion = $request->get('observacion');

         if($marcaid == "" || !$marcaid){
             throw new HttpException (400,"Debe proveer un id para modificar el registro.");  
         }

          if($nombre == ""){
                throw new HttpException (400,"El campo nombre no puede estar vacío");   
            }
            if($observacion == ""){
                throw new HttpException (400,"El campo observacion no puede estar vacío");   
            }
         
         $em = $this->getDoctrine()->getManager();
         $marca = $em->getRepository('AppBundle:Marca')
            ->find($marcaid);


        if (!$marca) {
        throw new HttpException (400,"No se ha encontrado la marca especificada: " .$marcaid);
         }

        $marca->setMarNombre($nombre);
        $marca->setMarObservacion($observacion);
        $em->flush();

        $data = array(
            'message' => 'La marca ha sido actualizada',
             'marcaid' => $marcaid,
             'nombre' => $nombre,
             'observacion' => $observacion
         );

         return $request;

     }
}   