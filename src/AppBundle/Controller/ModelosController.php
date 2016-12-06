<?php

namespace AppBundle\Controller;

use FOS\RestBundle\Controller\Annotations\QueryParam;
use Symfony\Component\HttpKernel\Exception\HttpException;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\Tools\Pagination\Paginator;
use AppBundle\Entity\Modelo;

class ModelosController extends FOSRestController
{

     /**
     * @Rest\Post("/modelo/add")
     */

    public function postAddModeloAction(Request $request)
    {
        try {
            
            // Obtaining vars from request
            $nombre = $request->get('nombre');
            $observacion = $request->get('observacion');
            $marcaid = $request->get('marcaid');

            // Check for mandatory fields
            if($nombre == ""){
                throw new HttpException (400,"El campo nombre no puede estar vacío");   
            }
            if($marcaid == ""){
                throw new HttpException (400,"Se necesita proveer de un ID de Marca para relacionar el modelo");   
            }


            // Find the relationship with Marcas
            $marca = $this->getDoctrine()
                ->getRepository('AppBundle:Marca')
                ->find($marcaid);
            
            if($marca == ""){
                throw new HttpException (400,"La marca especificada no existe");   
            }


            // Create the model
            $modelo = new Modelo();
            $modelo -> setModNombre($nombre);
            $modelo -> setModObservacion($observacion);
            $modelo -> setMarcaMar($marca);
            $em = $this->getDoctrine()->getManager();
            
            // tells Doctrine you want to (eventually) save the Product (no queries yet)
            $em->persist($modelo);
            
            // actually executes the queries (i.e. the INSERT query)
            $em->flush();
            
            $data = array("modelos" => array(
                array(
                    "modelo"   => $nombre,
                    "id" => $modelo->getModId()
                    )
                )  
            ); 
            return $data;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * @Rest\Get("/modelo/{modeloid}")
     */
    public function getModeloAction(Request $request)
    {
        $modid = $request->get('modeloid');

        $repository = $this->getDoctrine()->getRepository('AppBundle:Modelo');
        $modelo = $repository->findOneBymodId($modid);
        return $modelo;
    }
    
    /**
     * @Rest\Get("/modelo")
     */
    public function getAllModeloAction()
    {
        // Initialize the 'MarcaModelo' data repository
        $repository = $this->getDoctrine()->getRepository('AppBundle:Modelo');
        $query = $repository->createQueryBuilder('p')
            ->getQuery();
        $modelo = $query->getResult();
        return $modelo;
    }

     /**
     * @Rest\Get("/modelo/{limit}/{page}")
     */
    public function getAllModeloPaginatedAction(Request $request)
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
        $repository = $this->getDoctrine()->getRepository('AppBundle:Modelo');
        // The dsql syntax query
        $query = $repository->createQueryBuilder('modelo')
            ->getQuery()
            ->setFirstResult($limit * ($page - 1))
            ->setMaxResults($limit);
        // Build the paginator
        $paginator = new Paginator($query, $fetchJoinCollection = true);
        // Construct the response
        $response = array(
            'modelos' => $paginator->getIterator(),
            'totalModelosReturned' => $paginator->getIterator()->count(),
            'totalModelos' => $paginator->count()
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
            'modelos' => $paginator->getIterator(),
            'totalModelosReturned' => $paginator->getIterator()->count(),
            'totalModelos' => $paginator->count(),
            'searchedText' => $searchtext
        );
        // Send the response
        return $response;
    }


    /**
     * @Rest\Post("/modelo/edit/{modeloid}")
     */
     public function postUpdateModeloAction(Request $request)
     {
         $modeloid = $request->get('modeloid');
         $nombre = $request->get('nombre');
         $observacion = $request->get('observacion');
         $marcaid = $request->get('marcaid');
         $marca = $this->getDoctrine()
                ->getRepository('AppBundle:Marca')
                ->find($marcaid);


         if($modeloid == "" || !$modeloid){
             throw new HttpException (400,"Debe proveer un id para modificar el registro.");  
         }

          if($nombre == ""){
                throw new HttpException (400,"El campo nombre no puede estar vacío");   
            }
            if($marcaid == ""){
                throw new HttpException (400,"Debe especificar un ID de Marca válido");   
            }
         
         $em = $this->getDoctrine()->getManager();
         $modelo = $em->getRepository('AppBundle:Modelo')
            ->find($modeloid);


        if (!$modelo) {
        throw new HttpException (400,"No se ha encontrado el modelo especificado: " .$modeloid);
         }

        $modelo ->setModNombre($nombre);
        $modelo ->setModObservacion($observacion);
        $modelo -> setMarcaMar($marca);
        $em->flush();

        $data = array(
            'message' => 'El modelo ha sido actualizado',
             'modeloid' => $modeloid,
             'nombre' => $nombre,
             'observacion' => $observacion
         );

         return $data;

     }

    /**
     * @Rest\Delete("/modelo/delete/{modid}")
     */
    public function deleteRemoveModeloAction(Request $request)
    {
        $modid = $request->get('modid');

        // get EntityManager
        $em = $this->getDoctrine()->getManager();
        $modelotoremove = $em->getRepository('AppBundle:Modelo')->find($modid);

        if ($modelotoremove != "") {      
            // Remove it and flush
            $em->remove($modelotoremove);
            $em->flush();
            $data = array(
                'message' => 'El modelo ha sido eliminado',
                'modid' => $modid
            );
             return $data;
        } else{
            throw new HttpException (400,"No se ha encontrado el modelo especificado: " .$modid);
        }
        
    }

}   