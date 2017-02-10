<?php

namespace AppBundle\Controller;

use FOS\RestBundle\Controller\Annotations\QueryParam;
use Symfony\Component\HttpKernel\Exception\HttpException;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\Tools\Pagination\Paginator;
use AppBundle\Entity\Aplicacion;

class AplicacionesController extends FOSRestController
{

    /**
    * @Rest\Post("/aplicacion/add")
    */
    public function postAddAplicacionAction(Request $request)
    {
            // Obtaining vars from request         
            $aplcantidad    = $request->get('cantidad');
            $observacion    = $request->get('observacion');        
            $vehiculo       = $request->get('vehiculoid');
            $parte          = $request->get('parteid');

            // Check for mandatory fields            
            if($aplcantidad == ""){
                throw new HttpException (400,"El campo cantidad no puede estar vacío");   
            }
            if($vehiculo == ""){
                throw new HttpException (400,"El campo Vehiculo no puede estar vacío");   
            }
            if($parte == ""){
                throw new HttpException (400,"El campo Parte no puede estar vacío");   
            }

            // Find the relationship with Vehiculo
            $vehiculo = $this->getDoctrine()->getRepository('AppBundle:Vehiculo')->find($vehiculo);
            if($vehiculo == ""){
                throw new HttpException (400,"El vehiculo especificado no existe");   
            }
            // Find the relationship with Parte
            $parte = $this->getDoctrine()->getRepository('AppBundle:Parte')->find($parte);
            if($parte == ""){
                throw new HttpException (400,"La parte especificada no existe");   
            }

            // Create the model
            $aplicacion = new Aplicacion();
            $aplicacion -> setAplCantidad($aplcantidad);
            $aplicacion -> setAplObservacion($observacion);
            $aplicacion -> setVehiculoVeh($vehiculo);
            $aplicacion -> setPartePar($parte);
            $em = $this->getDoctrine()->getManager();
            
            // tells Doctrine you want to (eventually) save the Product (no queries yet)
            $em->persist($aplicacion);        
            // actually executes the queries (i.e. the INSERT query)
            $em->flush();
            
            $response = array("Aplicacion" => array(
                array(
                    "message"       => "Nueva aplicación creada",
                    "ID"            => $aplicacion->getAplId(),
                    "vehiculo"      => $vehiculo->getVehVariante(),
                    "parte"         => $parte->getParNombre(),
                    "cantidad"      => $aplcantidad,
                    "observacion"   => $observacion
                    )
                )  
            );
            return $response;           
    }


    /**
    * @Rest\Get("/aplicacion")
    */
    public function getAllAplicacionAction()
    {
        $repository     = $this->getDoctrine()->getRepository('AppBundle:Aplicacion');
        $query          = $repository->createQueryBuilder('a')->getQuery();
        $aplicacion     = $query->getResult();
        return $aplicacion;
    }


    /**
    * @Rest\Get("/aplicacion/{aplicacionid}")
    */
    public function getAplicacionAction(Request $request)
    {
        $aplicacionid   = $request->get('aplicacionid');
        $repository     = $this->getDoctrine()->getRepository('AppBundle:Aplicacion');
        $aplicacion     = $repository->findOneByaplId($aplicacionid);
        return $aplicacion;
    }
    

    /**
    * @Rest\Get("/aplicacion/{limit}/{page}")
    */
    public function getAllAplicacionPaginatedAction(Request $request)
    {
        // Set up the limit and page vars from request
        $limit  = $request->get('limit');
        $page   = $request->get('page');

        // Check if the params are numbers before continue
        if(!is_numeric($page))
        {
            throw new HttpException (400,"Por favor use solo números para la página");  
        }
        // Check if the limit asked has all or not.
        if (!is_numeric($limit))
        {
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
        $repository = $this->getDoctrine()->getRepository('AppBundle:Aplicacion');
        // The dsql syntax query
        $query = $repository->createQueryBuilder('a')->getQuery()->setFirstResult($limit * ($page - 1))->setMaxResults($limit);
        // Build the paginator
        $paginator = new Paginator($query, $fetchJoinCollection = true);
        // Construct the response
        $response = array(
            'aplicaciones' => $paginator->getIterator(),
            'totalAplicacionesReturned' => $paginator->getIterator()->count(),
            'totalAplicaciones' => $paginator->count()
        );
        // Send the response
        return $response;
    }


    /**
    * @Rest\Get("/aplicacion/{limit}/{page}/{searchtext}")
    */
    public function getAllAplicacionPaginatedSearchAction(Request $request)
    {
        // Set up the limit and page vars from request
        $limit      = $request->get('limit');
        $page       = $request->get('page');
        $searchtext = $request->get('searchtext');

        // Check if the params are numbers before continue
        if(!is_numeric($page))
        {
            throw new HttpException (400,"Por favor use solo números para la página");  
        }
        // Check if the limit asked has all or not.
        if (!is_numeric($limit)) 
        {
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

        if($searchtext == "")
        {
            throw new HttpException (400,"Escriba un texto para la búsqueda"); 
        }

        // Connect with the autoparts db repository
        $repository = $this->getDoctrine()->getRepository('AppBundle:Aplicacion');  
        // The dsql syntax query
        $query = $repository->createQueryBuilder('aplicacion')->join('aplicacion.vehiculoVeh','v')->join('aplicacion.partePar','p')
            ->where('aplicacion.aplObservacion LIKE :searchtext')
            ->orWhere('aplicacion.aplCantidad LIKE :searchtext')
            ->orWhere('v.vehVariante LIKE :searchtext')
            ->orWhere('p.parNombre LIKE :searchtext')
            ->setParameter('searchtext',"%" .$searchtext ."%")->getQuery()->setFirstResult($limit * ($page - 1))->setMaxResults($limit);            
        // Build the paginator
        $paginator = new Paginator($query, $fetchJoinCollection = true);
        // Construct the response
        $response = array(
            'aplicaciones' => $paginator->getIterator(),
            'totalAplicacionesReturned' => $paginator->getIterator()->count(),
            'totalAplicaciones' => $paginator->count(),
            'searchedText' => $searchtext
        );
        // Send the response
        return $response;
    }


     /**
     * @Rest\Post("/aplicacion/edit/{aplicacionid}")
     */
     public function postUpdateModeloAction(Request $request)
     {

        $aplicacionid   = $request->get('aplicacionid');
        $aplcantidad    = $request->get('cantidad');
        $observacion    = $request->get('observacion');        
        $vehiculo       = $request->get('vehiculoid');
        $parte          = $request->get('parteid');

        // Check for mandatory fields 
         if($aplicacionid == "" || !$aplicacionid)
         {
             throw new HttpException (400,"Debe proveer un id para modificar el registro.");  
         }
         if($aplcantidad == "")
         {
             throw new HttpException (400,"El campo cantidad no puede estar vacío");   
         }
         if($vehiculo == "")
         {
             throw new HttpException (400,"El campo Vehiculo no puede estar vacío");   
         }
         if($parte == "")
         {
                throw new HttpException (400,"El campo Parte no puede estar vacío");   
         }

        // Find the relationship with Vehiculo
        $vehiculo = $this->getDoctrine()->getRepository('AppBundle:Vehiculo')->find($vehiculo);
        if($vehiculo == "")
        {
               throw new HttpException (400,"El vehiculo especificado no existe");   
        }
        // Find the relationship with Parte
        $parte = $this->getDoctrine()->getRepository('AppBundle:Parte')->find($parte);
        if($parte == "")
        {
               throw new HttpException (400,"La parte especificada no existe");   
        }
         
        $em = $this->getDoctrine()->getManager();
        $aplicacion = $em->getRepository('AppBundle:Aplicacion')->find($aplicacionid);


        if (!$aplicacion) {
        throw new HttpException (400,"No se ha encontrado la aplicacion especificado: " .$aplicacionid);
         }

          $aplicacion -> setAplCantidad($aplcantidad);
          $aplicacion -> setAplObservacion($observacion);
          $aplicacion -> setVehiculoVeh($vehiculo);
          $aplicacion -> setPartePar($parte);
          $em->flush();

        $data = array(
            'message' => 'La aplicacion '.$observacion.' ha sido actualizada',
            'aplicacion' => $aplicacionid,
            'observacion' => $observacion,
            'vehiculo' => $vehiculo->getVehVariante(),
            'parte' => $parte->getParNombre(),
         );

         return $data;

     }


    /**
    * @Rest\Delete("/aplicacion/delete/{aplicacionid}")
    */
    public function deleteRemoveAplicacionAction(Request $request)
    {
        $aplid = $request->get('aplicacionid');
        // get EntityManager
        $em = $this->getDoctrine()->getManager();
        $aplicaciontoremove = $em->getRepository('AppBundle:Aplicacion')->find($aplid);

        if ($aplicaciontoremove != "") {      
            // Remove it and flush
            $em->remove($aplicaciontoremove);
            $em->flush();
            $data = array(
                'message' => 'La aplicacion '.$aplicaciontoremove->getAplObservacion().' ha sido eliminada',
                'aplid' => $aplid
            );
             return $data;
        } else
            {
            throw new HttpException (400,"No se ha encontrado la aplicacion especificada: " .$aplid);
            }      
    }
 
}   