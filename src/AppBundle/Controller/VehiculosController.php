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
            $anio           = $request->get('anio');
            $variante       = $request->get('variante');
            $vehCilindros   = $request->get('cilindros');
            $vehLitros      = $request->get('litros');
            $vehValvulas    = $request->get('valvulas');
            $vehLevas       = $request->get('levas');
            $vehVersion     = $request->get('version');
            $vehTipo        = $request->get('tipo');
            $vehTraccion    = $request->get('traccion');
            $vehCaja        = $request->get('caja');
            $vehObservacion = $request->get('observacion');            
            $vin            = $request->get('vin');
            $nota           = $request->get('nota');
            $desde          = $request->get('desde');
            $hasta          = $request->get('hasta');
            $modeloid       = $request->get('modeloid');


            // Check for mandatory fields          
            if($anio == ""){
                throw new HttpException (400,"El campo año no puede estar vacío");   
            }
            if($modeloid == ""){
                throw new HttpException (400,"Se necesita proveer de un ID de Modelo para relacionar el vehiculo");   
            }

            // Find the relationship with Modelo
            $modelo = $this->getDoctrine()->getRepository('AppBundle:Modelo')->find($modeloid);
            if($modelo == "")
            {
                throw new HttpException (400,"El modelo especificado no existe");   
            }

            // Create the model
            $vehiculo = new Vehiculo();
            $vehiculo -> setAnioAniId($anio);
            $vehiculo -> setVehVariante($variante);
            $vehiculo -> setVehCilindros($vehCilindros);
            $vehiculo -> setVehLitros($vehLitros);
            $vehiculo -> setVehValvulas($vehValvulas);
            $vehiculo -> setVehLevas($vehLevas);
            $vehiculo -> setVehVersion($vehVersion);
            $vehiculo -> setVehTipo($vehTipo);
            $vehiculo -> setVehTraccion($vehTraccion);
            $vehiculo -> setVehCaja($vehCaja);
            $vehiculo -> setVehObservacion($vehObservacion);
            $vehiculo -> setVehVin($vin);
            $vehiculo -> setNota($nota);
            $vehiculo -> setVehFabDesde($desde);
            $vehiculo -> setVehFabHasta($hasta);
            $vehiculo -> setModeloMod($modelo);
            $em = $this->getDoctrine()->getManager();
            
            // tells Doctrine you want to (eventually) save the Product (no queries yet)
            $em->persist($vehiculo);       
            // actually executes the queries (i.e. the INSERT query)
            $em->flush();
            
            $response = array("Vehiculo" => array(
                array(
                    "nuevo vehiculo creado"     => $vehCilindros.', '.$vehValvulas,
                    "modelo"                    => $modelo->getModNombre(),
                    "id"                        => $vehiculo->getVehId()
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
            'total vehiculos en pagina' => $paginator->getIterator()->count(),
            'total vehiculos'           => $paginator->count()
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
            'vehiculos'                     => $paginator->getIterator(),
            'total vehiculos en página'     => $paginator->getIterator()->count(),
            'total vehiculos encontrados'   => $paginator->count(),
            'busqueda por'                  => $searchtext
        );
        // Send the response
        return $response;
    }


    /**
     * @Rest\Post("/vehiculo/edit/{vehiculoid}")
     */
     public function postUpdateVehiculoAction(Request $request)
     {
         try
         {
         $vehiculoid        = $request->get('vehiculoid');
         $anio              = $request->get('anio');
         $variante          = $request->get('variante');
         $vehCilindros      = $request->get('cilindros');
         $vehLitros         = $request->get('litros');
         $vehValvulas       = $request->get('valvulas');
         $vehLevas          = $request->get('levas');
         $vehVersion        = $request->get('version');
         $vehTipo           = $request->get('tipo');
         $vehTraccion       = $request->get('traccion');
         $vehCaja           = $request->get('caja');
         $vehObservacion    = $request->get('observacion');            
         $vin               = $request->get('vin');
         $nota              = $request->get('nota');
         $desde             = $request->get('desde');
         $hasta             = $request->get('hasta');
         $modeloid          = $request->get('modeloid');
         $modelo            = $this->getDoctrine()->getRepository('AppBundle:Modelo')->find($modeloid);
         

         if($vehiculoid == "" || !$vehiculoid)
         {
             throw new HttpException (400,"Debe proveer un id para modificar el registro.");  
         }
         if($anio == "")
         {
                throw new HttpException (400,"El campo año no puede estar vacío");   
         }
         if($variante == "")
         {
                throw new HttpException (400,"El campo variante no puede estar vacío");   
         }
         
         $em = $this->getDoctrine()->getManager();
         $vehiculo = $em->getRepository('AppBundle:Vehiculo')->find($vehiculoid);


        if (!$vehiculoid)
        {
        throw new HttpException (400,"No se ha encontrado el vehiculo especificado: " .$vehiculoid);
        }

        $vehiculo -> setAnioAniId($anio);
        $vehiculo -> setVehVariante($variante);
        $vehiculo -> setVehCilindros($vehCilindros);
        $vehiculo -> setVehLitros($vehLitros);
        $vehiculo -> setVehValvulas($vehValvulas);
        $vehiculo -> setVehLevas($vehLevas);
        $vehiculo -> setVehVersion($vehVersion);
        $vehiculo -> setVehTipo($vehTipo);
        $vehiculo -> setVehTraccion($vehTraccion);
        $vehiculo -> setVehCaja($vehCaja);
        $vehiculo -> setVehObservacion($vehObservacion);
        $vehiculo -> setVehVin($vin);
        $vehiculo -> setNota($nota);
        $vehiculo -> setVehFabDesde($desde);
        $vehiculo -> setVehFabHasta($hasta);
        $vehiculo -> setModeloMod($modelo);
        $em->flush();

        $response = array(
            'message'       => 'El Vehiculo '.$vehCilindros.'  ha sido actualizado',
            'vehiculoid'    => $vehiculoid,
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