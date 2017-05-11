<?php

namespace AppBundle\Controller;

use FOS\RestBundle\Controller\Annotations\QueryParam;
use Symfony\Component\HttpKernel\Exception\HttpException;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\Tools\Pagination\Paginator;
use AppBundle\Entity\VehiculoNota;

class VehiculoNotasController extends FOSRestController
{

    /**
    * @Rest\Post("/vehiculo_nota/add")
    */
    public function postAddVehiculoNotaAction(Request $request)
    {
        try { 
            // Obtaining vars from request          
            $nota           = $request->get('nota');
            $fecha          = $request->get('fecha');
            $vehiculoid     = $request->get('vehiculoid');
                      
            // Check for mandatory fields  
            if($vehiculoid == ""){
                throw new HttpException (400,"El campo Vehiculo no puede estar vacío");   
            }
            if($nota == ""){
                throw new HttpException (400,"El campo nota no puede estar vacío");   
            }

            // Find the relationship with Vehiculo
            $vehiculo = $this->getDoctrine()->getRepository('AppBundle:Vehiculo')->find($vehiculoid);
            if($vehiculo == "")
            {
                throw new HttpException (400,"El vehiculo especificado no existe");   
            }

            // Create the Vehiculo Nota
            $vehnota = new VehiculoNota();
            $vehnota -> setVehiculoVeh($vehiculo);
            $vehnota -> setVehNota($nota);
            $vehnota -> setFechaNota(new \DateTime($fecha));
            $em = $this->getDoctrine()->getManager();
            
            // tells Doctrine you want to (eventually) save the Product (no queries yet)
            $em->persist($vehnota);       
            // actually executes the queries (i.e. the INSERT query)
            $em->flush();
            
            $response = array("VehiculoNota" => array(
                array(
                    "Nota creada "      => $nota,
                    "vehiculo:"            => $vehiculo->getVehVariante(),
                    "Fecha"             => $fecha,
                    "ID"                => $vehnota->getVehNotaId()
                    )
                )  
            ); 
            return $response;

        } catch (Exception $e)
            {
            return $e->getMessage();
            }
    }


    /**
     * @Rest\Get("/vehiculo_nota")
     */
    public function getAllVehiculoNotaAction()
    {
        $repository     = $this->getDoctrine()->getRepository('AppBundle:VehiculoNota');
        $query          = $repository->createQueryBuilder('n')->getQuery();
        $vehnota        = $query->getResult();
        return $vehnota;
    }


    /**
     * @Rest\Get("/vehiculo_nota/{vehnotaid}")
     */
    public function getVehiculoNotaAction(Request $request)
    {
        $vehnotaid      = $request->get('vehnotaid');
        $repository     = $this->getDoctrine()->getRepository('AppBundle:VehiculoNota');
        $vehnota        = $repository->findOneByVehNotaId($vehnotaid);
        return $vehnota;
    }
    


    /**
     * @Rest\Get("/vehiculo_nota/{limit}/{page}")
     */
    public function getAllVehiculoNotaPaginatedAction(Request $request)
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
        $repository = $this->getDoctrine()->getRepository('AppBundle:VehiculoNota');
        // The dsql syntax query
        $query = $repository->createQueryBuilder('vehNota')->getQuery()->setFirstResult($limit * ($page - 1))->setMaxResults($limit);
        // Build the paginator
        $paginator = new Paginator($query, $fetchJoinCollection = true);
        // Construct the response
        $response = array(
            'notasVehiculo' => $paginator->getIterator(),
            'totalNotasVehiculoReturned' => $paginator->getIterator()->count(),
            'totalNotasVehiculo' => $paginator->count()
        );
        // Send the response
        return $response;
    }


    /**
     * @Rest\Get("/vehiculo_nota/{limit}/{page}/{searchtext}")
     */
    public function getAllVehiculoNotaPaginatedSearchAction(Request $request)
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
        $repository = $this->getDoctrine()->getRepository('AppBundle:VehiculoNota'); 
        // The dsql syntax query
        $query = $repository->createQueryBuilder('vehnota')->join('vehnota.vehiculoVeh','v')
            ->where('vehnota.vehNota LIKE :searchtext')
            ->orWhere('vehnota.fechaNota LIKE :searchtext')
            ->orWhere('v.vehVariante LIKE :searchtext')
            ->setParameter('searchtext',"%" .$searchtext ."%")->getQuery()->setFirstResult($limit * ($page - 1))->setMaxResults($limit);
        // Build the paginator
        $paginator = new Paginator($query, $fetchJoinCollection = true);
        // Construct the response
       $response = array(
            'notasVehiculo' => $paginator->getIterator(),
            'totalNotasVehiculoReturned' => $paginator->getIterator()->count(),
            'totalNotasVehiculo' => $paginator->count(),
            'searchedText' => $searchtext
        );
        // Send the response
        return $response;
    }


    /**
     * @Rest\Post("/vehiculo_nota/edit/{vehnotaid}")
     */
     public function postUpdateVehiculoNotaAction(Request $request)
     {
        $vehnotaid      = $request->get('vehnotaid');
        $nota           = $request->get('nota');
        $fecha          = $request->get('fecha');
        $vehiculoid     = $request->get('vehiculoid');
        $vehiculo       = $this->getDoctrine()->getRepository('AppBundle:Vehiculo')->find($vehiculoid);

        if($vehnotaid == "" || !$vehnotaid)
        {
             throw new HttpException (400,"Debe proveer un id para modificar el registro.");  
        }
        if($nota == "")
        {
                throw new HttpException (400,"El campo nota no puede estar vacío");   
        }
        if($fecha == "")
        {
                throw new HttpException (400,"El campo fecha no puede estar vacío");   
        }
        if($vehiculoid == "")
        {
             throw new HttpException (400,"El campo vehiculo no puede estar vacío");  
        }
        if(!$vehiculo)
        {
             throw new HttpException (400,"Debe proveer un vehiculo válido.");  
        }
         
        $em = $this->getDoctrine()->getManager();
        $vehnota = $em->getRepository('AppBundle:VehiculoNota')->find($vehnotaid);

        if (!$vehnota) {
        throw new HttpException (400,"No se ha encontrado la nota especificada: " .$vehnotaid);
         }

        $vehnota -> setVehiculoVeh($vehiculo);
        $vehnota -> setVehNota($nota);
        $vehnota -> setFechaNota(new \DateTime($fecha));
        $em->flush();

        $response = array(
            'message'   => 'La nota ha sido actualizada',
            'nota'      => $vehnota->getVehNota(),
            'fecha'     => $vehnota->getFechaNota(),
            'vehiculo'  => $vehiculo->getVehVariante(),
            'ID'        => $vehnotaid,
         );

         return $response;
     }


    /**
    * @Rest\Delete("/vehiculo_nota/delete/{vehnotaid}")
    */
      public function deleteRemoveVehiculoNotaAction(Request $request)
    {
        $vehnotaid  = $request->get('vehnotaid');
        // get EntityManager
        $em  = $this->getDoctrine()->getManager();
        $vehnotatoremove = $em->getRepository('AppBundle:VehiculoNota')->find($vehnotaid);

        if ($vehnotatoremove != "")
        {      
            // Remove it and flush
            $em->remove($vehnotatoremove);
            $em->flush();
            $response = array(
                'message'   => 'La nota '.$vehnotatoremove->getVehNota().' ha sido eliminada',
                'id'        => $vehnotaid
            );
             return $response;
        } else
        {
            throw new HttpException (400,"No se ha encontrado la nota especificado: " .$vehnotaid);
        }     
    }

}   
