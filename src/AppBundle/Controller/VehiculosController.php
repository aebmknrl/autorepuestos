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
            // Obtaining vars from request
            $anioAniId           = $request->get('anioAniId');
            $vehVariante       = $request->get('vehVariante');
            $vehCilindros   = $request->get('vehCilindros');
            $vehLitros      = $request->get('vehLitros');
            $vehValvulas    = $request->get('vehValvulas');
            $vehLevas       = $request->get('vehLevas');
            $vehVersion     = $request->get('vehVersion');
            $vehTipo        = $request->get('vehTipo');
            $vehTraccion    = $request->get('vehTraccion');
            $vehCaja        = $request->get('vehCaja');
            $vehObservacion = $request->get('vehObservacion');            
            $vehVin         = $request->get('vehVin');
            $nota           = $request->get('nota');
            $vehFabDesde    = $request->get('vehFabDesde');
            $vehFabHasta          = $request->get('vehFabHasta');
            $modeloMod       = $request->get('modeloMod');


            // Check for mandatory fields          
            if($modeloMod == ""){
                throw new HttpException (400,"Se necesita proveer de un ID de Modelo para relacionar el vehiculo");   
            }

            // Find the relationship with Modelo
            $modelo = $this->getDoctrine()->getRepository('AppBundle:Modelo')->find($modeloMod);
            if($modelo == "")
            {
                throw new HttpException (400,"El modelo especificado no existe");   
            }

            // Create the Vehiculo
            $vehiculo = new Vehiculo();
            $vehiculo -> setAnioAniId($anioAniId);
            $vehiculo -> setVehVariante($vehVariante);
            $vehiculo -> setVehCilindros($vehCilindros);
            $vehiculo -> setVehLitros($vehLitros);
            $vehiculo -> setVehValvulas($vehValvulas);
            $vehiculo -> setVehLevas($vehLevas);
            $vehiculo -> setVehVersion($vehVersion);
            $vehiculo -> setVehTipo($vehTipo);
            $vehiculo -> setVehTraccion($vehTraccion);
            $vehiculo -> setVehCaja($vehCaja);
            $vehiculo -> setVehObservacion($vehObservacion);
            $vehiculo -> setVehVin($vehVin);
            $vehiculo -> setNota($nota);
            $vehiculo -> setVehFabDesde($vehFabDesde);
            $vehiculo -> setVehFabHasta($vehFabHasta);
            $vehiculo -> setModeloMod($modelo);
            $em = $this->getDoctrine()->getManager();
            
            // tells Doctrine you want to (eventually) save the Product (no queries yet)
            $em->persist($vehiculo);       
            // actually executes the queries (i.e. the INSERT query)
            $em->flush();
            
            $response = array("vehiculo" => array(
                array(
                    "vehiculoid"     => $vehiculo->getVehId(),
                    "modelo"         => $modelo->getModNombre()
                    )
                )  
            ); 
            return $response;
        }
        catch (\Doctrine\DBAL\Exception\UniqueConstraintViolationException $e){
            throw new HttpException (409,"Error: La variante ya existe para el modelo indicado."); 
            } 
        catch (Exception $e) {
            return $e->getMessage();
            } 
    }


    /**
    * @Rest\Get("/vehiculo")
    */
    public function getAllVehiculoAction()
    {
        // Initialize the 'Vehiculo' data repository
        $repository     = $this->getDoctrine()->getRepository('AppBundle:Vehiculo');
        $query          = $repository->createQueryBuilder('v')->getQuery();
        $vehiculos      = $query->getResult();
        return $vehiculos;
    }


    /**
     * @Rest\Get("/vehiculo/{vehiculoid}")
     */
    public function getVehiculoAction(Request $request)
    {
        $vehiculoid     = $request->get('vehiculoid');
        $repository     = $this->getDoctrine()->getRepository('AppBundle:Vehiculo');
        $vehiculos      = $repository->findOneByvehId($vehiculoid);
        return $vehiculos;
    }
    
 
     /**
     * @Rest\Get("/vehiculo/{limit}/{page}")
     */
    public function getAllVehiculoPaginatedAction(Request $request)
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
        $repository = $this->getDoctrine()->getRepository('AppBundle:Vehiculo');
        // The dsql syntax query
        $query = $repository->createQueryBuilder('v')->getQuery()->setFirstResult($limit * ($page - 1))->setMaxResults($limit);
        // Build the paginator
        $paginator = new Paginator($query, $fetchJoinCollection = true);
        // Construct the response
        $response = array(
            'vehiculos'                 => $paginator->getIterator(),
            'totalVehiculosReturned'    => $paginator->getIterator()->count(),
            'totalVehiculos'            => $paginator->count()
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
        $repository = $this->getDoctrine()->getRepository('AppBundle:Vehiculo');
        // The dsql syntax query
        $query = $repository->createQueryBuilder('vehiculo')->join('vehiculo.modeloMod','mod')->join('mod.marcaMar','mar')
            ->Where('mod.modNombre LIKE :searchtext')
            ->orwhere('mar.marNombre LIKE :searchtext')
            ->orwhere('vehiculo.anioAniId LIKE :searchtext')
            ->orwhere('vehiculo.vehCilindros LIKE :searchtext')
            ->orwhere('vehiculo.vehLitros LIKE :searchtext')
            ->orwhere('vehiculo.vehValvulas LIKE :searchtext')
            ->orwhere('vehiculo.vehLevas LIKE :searchtext')
            ->orwhere('vehiculo.vehCaja LIKE :searchtext')
            ->orwhere('vehiculo.vehTipo LIKE :searchtext')
            ->orwhere('vehiculo.vehTraccion LIKE :searchtext')
            ->orwhere('vehiculo.vehObservacion LIKE :searchtext')
            ->orwhere('vehiculo.vehVersion LIKE :searchtext')
            ->orWhere('vehiculo.vehVin LIKE :searchtext')
            ->orWhere('vehiculo.nota LIKE :searchtext')
            ->setParameter('searchtext',"%" .$searchtext ."%")->getQuery()->setFirstResult($limit * ($page - 1))->setMaxResults($limit);
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
     * @Rest\Post("/vehiculo/edit/{vehId}")
     */
     public function postUpdateVehiculoAction(Request $request)
     {
         try
         {
         $vehId        = $request->get('vehId');
         $anioAniId              = $request->get('anioAniId');
         $vehVariante          = $request->get('vehVariante');
         $vehCilindros      = $request->get('vehCilindros');
         $vehLitros         = $request->get('vehLitros');
         $vehValvulas       = $request->get('vehValvulas');
         $vehLevas          = $request->get('vehLevas');
         $vehVersion        = $request->get('vehVersion');
         $vehTipo           = $request->get('vehTipo');
         $vehTraccion       = $request->get('vehTraccion');
         $vehCaja           = $request->get('vehCaja');
         $vehObservacion    = $request->get('vehObservacion');            
         $vehVin               = $request->get('vehVin');
         $nota              = $request->get('nota');
         $vehFabDesde             = $request->get('vehFabDesde');
         $vehFabHasta             = $request->get('vehFabHasta');
         $modeloMod          = $request->get('modeloMod');
         $modelo            = $this->getDoctrine()->getRepository('AppBundle:Modelo')->find($modeloMod);
         

         if($vehId == "" || !$vehId)
         {
             throw new HttpException (400,"Debe proveer un id para modificar el registro.");  
         }
        if($modeloid == ""){
            throw new HttpException (400,"Se necesita proveer de un ID de Modelo para relacionar el vehiculo");   
        }
         
         $em = $this->getDoctrine()->getManager();
         $vehiculo = $em->getRepository('AppBundle:Vehiculo')->find($vehId);

        if (!$vehiculo || $vehiculo == "")
        {
        throw new HttpException (400,"No se ha encontrado el vehiculo especificado: " .$vehId);
        }

        if($modelo == "")
        {
            throw new HttpException (400,"El modelo especificado no existe");   
        }

        $vehiculo -> setAnioAniId($anioAniId);
        $vehiculo -> setVehVariante($vehVariante);
        $vehiculo -> setVehCilindros($vehCilindros);
        $vehiculo -> setVehLitros($vehLitros);
        $vehiculo -> setVehValvulas($vehValvulas);
        $vehiculo -> setVehLevas($vehLevas);
        $vehiculo -> setVehVersion($vehVersion);
        $vehiculo -> setVehTipo($vehTipo);
        $vehiculo -> setVehTraccion($vehTraccion);
        $vehiculo -> setVehCaja($vehCaja);
        $vehiculo -> setVehObservacion($vehObservacion);
        $vehiculo -> setVehVin($vehVin);
        $vehiculo -> setNota($nota);
        $vehiculo -> setVehFabDesde($vehFabDesde);
        $vehiculo -> setVehFabHasta($vehFabHasta);
        $vehiculo -> setModeloMod($modelo);
        $em->flush();

        $response = array(
            'message'       => 'El Vehiculo ha sido actualizado',
            'vehiculoid'    => $vehId,
            "modelo"        => $modelo->getModNombre(),
            'nota'          => $nota
         );        
         return $response;
         }
        catch (\Doctrine\DBAL\Exception\UniqueConstraintViolationException $e)
        {
            throw new HttpException (409,"Error: La variante ya existe para el modelo indicado."); 
            } 
        catch (Exception $e)
        {
            return $e->getMessage();
            } 
     }


     /**
     * @Rest\Delete("/vehiculo/delete/{vehiculoid}")
     */
      public function deleteRemoveVehiculoAction(Request $request)
    {
        $vehiculoid  = $request->get('vehiculoid');
        // get EntityManager
        $em  = $this->getDoctrine()->getManager();
        $vehiculotoremove = $em->getRepository('AppBundle:Vehiculo')->find($vehiculoid);

        if ($vehiculotoremove != "")
        {      
            // Remove it and flush
            $em->remove($vehiculotoremove);
            $em->flush();
            $response = array(
                'message'   => 'El vehiculo '.$vehiculotoremove->getVehVariante().' ha sido eliminado',
                'vehid'     => $vehiculoid
            );
             return $response;
        } else
        {
            throw new HttpException (400,"No se ha encontrado el vehiculo especificado: " .$vehiculoid);
        }     
    }
}   