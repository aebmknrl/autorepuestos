<?php

namespace AppBundle\Controller;

use FOS\RestBundle\Controller\Annotations\QueryParam;
use Symfony\Component\HttpKernel\Exception\HttpException;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\Tools\Pagination\Paginator;
use AppBundle\Entity\Vehiculo;

class VehiculosController extends FOSRestController
{

     /**
     * @Rest\Post("/vehiculo/add")
     */


    public function postAddVehiculoAction(Request $request)
    {
        try {
            $anio       = $request->get('anio');
            $variante   = $request->get('variante');
            $vin        = $request->get('vin');
            $nota       = $request->get('nota');
            $modeloid   = $request->get('modeloid');
            $modelo     = $this->getDoctrine()
                        ->getRepository('AppBundle:Modelo')
                        ->find($modeloid);

            
            if($variante == ""){
                throw new HttpException (400,"El campo variante no puede estar vacío");   
            }
            if($vin == ""){
                throw new HttpException (400,"El campo vin no puede estar vacío");   
            }

            $vehiculo = new Vehiculo();
            $vehiculo -> setAnioAniId($anio);
            $vehiculo -> setVehVariante($variante);
            $vehiculo -> setVehVin($vin);
            $vehiculo -> setNota($nota);
            $vehiculo -> setModeloMod($modelo);
            $em = $this->getDoctrine()->getManager();
            
            // tells Doctrine you want to (eventually) save the Product (no queries yet)
            $em->persist($vehiculo);
            
            // actually executes the queries (i.e. the INSERT query)
            $em->flush();
            
            $data = array("Vehiculo" => array(
                array(
                    "Vehiculo:"   => $variante,
                    "ID" => $vehiculo->getVehId()
                    )
                )  
            ); 
            return $data;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * @Rest\Get("/vehiculo/{vehiculoid}")
     */
    public function getVehiculoAction(Request $request)
    {
        $vehid = $request->get('vehiculoid');

        $repository = $this->getDoctrine()->getRepository('AppBundle:Vehiculo');
        $vehiculos = $repository->findOneByvehId($vehid);
        return $vehiculos;
    }
    
    /**
     * @Rest\Get("/vehiculo")
     */
    public function getAllVehiculoAction()
    {
        // Initialize the 'Vehiculo' data repository
        $repository = $this->getDoctrine()->getRepository('AppBundle:Vehiculo');
        $query = $repository->createQueryBuilder('p')
            ->getQuery();
        $vehiculos = $query->getResult();
        return $vehiculos;
    }

     /**
     * @Rest\Get("/vehiculo/{limit}/{page}")
     */
    public function getAllVehiculoPaginatedAction(Request $request)
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
        $repository = $this->getDoctrine()->getRepository('AppBundle:Vehiculo');
        // The dsql syntax query
        $query = $repository->createQueryBuilder('vehiculo')
            ->getQuery()
            ->setFirstResult($limit * ($page - 1))
            ->setMaxResults($limit);
        // Build the paginator
        $paginator = new Paginator($query, $fetchJoinCollection = true);
        // Construct the response
        $response = array(
            'vehiculos' => $paginator->getIterator(),
            'totalVehiculosReturned' => $paginator->getIterator()->count(),
            'totalVehiculos' => $paginator->count()
        );
        // Send the response
        return $response;
    }
    /**
     * @Rest\Get("/vehiculo/{limit}/{page}/{searchtext}")
     */
    public function getAllVehiculoPaginatedSearchAction(Request $request)
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
        $repository = $this->getDoctrine()->getRepository('AppBundle:Vehiculo');
        // The dsql syntax query
        $query = $repository->createQueryBuilder('vehiculo')
            ->where('vehiculo.anioAniId LIKE :searchtext')
            ->orWhere('vehiculo.vehVariante LIKE :searchtext')
            ->orWhere('vehiculo.vehVin LIKE :searchtext')
            ->orWhere('vehiculo.nota LIKE :searchtext')
            ->setParameter('searchtext',"%" .$searchtext ."%")
            ->getQuery()
            ->setFirstResult($limit * ($page - 1))
            ->setMaxResults($limit);
        // Build the paginator
        $paginator = new Paginator($query, $fetchJoinCollection = true);
        // Construct the response
        $response = array(
            'vehiculos' => $paginator->getIterator(),
            'totalVehiculosReturned' => $paginator->getIterator()->count(),
            'totalVehiculos' => $paginator->count(),
            'searchedText' => $searchtext
        );
        // Send the response
        return $response;
    }


    /**
     * @Rest\Post("/vehiculo/edit/{vehiculoid}")
     */
     public function postUpdateVehiculoAction(Request $request)
     {
         $vehiculoid = $request->get('vehiculoid');
         $anio = $request->get('anio');
         $variante = $request->get('variante');
         $vin =$request->get('vin');
         $nota = $request->get('nota');
         $modeloid   = $request->get('modeloid');
         $modelo     = $this->getDoctrine()
                     ->getRepository('AppBundle:Modelo')
                     ->find($modeloid);
         

         if($vehiculoid == "" || !$vehiculoid){
             throw new HttpException (400,"Debe proveer un id para modificar el registro.");  
         }

          if($anio == ""){
                throw new HttpException (400,"El campo año no puede estar vacío");   
            }
            if($variante == ""){
                throw new HttpException (400,"El campo variante no puede estar vacío");   
            }
         
         $em = $this->getDoctrine()->getManager();
         $vehiculo = $em->getRepository('AppBundle:Vehiculo')
            ->find($vehiculoid);


        if (!$vehiculoid) {
        throw new HttpException (400,"No se ha encontrado el vehiculo especificado: " .$vehiculoid);
         }

        $vehiculo = new Vehiculo();
        $vehiculo -> setAnioAniId($anio);
        $vehiculo -> setVehVariante($variante);
        $vehiculo -> setVehVin($vin);
        $vehiculo -> setNota($nota);
        $vehiculo -> setModeloMod($modelo);
        $em->flush();

        $data = array(
            'message' => 'El Vehiculo ha sido actualizado',
             'vehiculoid' => $vehiculoid,
             'variante' => $variante,
             'nota' => $nota
         );

         return $request;

     }
}   