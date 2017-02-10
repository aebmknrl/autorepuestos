<?php

namespace AppBundle\Controller;

use FOS\RestBundle\Controller\Annotations\QueryParam;
use Symfony\Component\HttpKernel\Exception\HttpException;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\Tools\Pagination\Paginator;
use AppBundle\Entity\VehiculoImagen;

class VehiculoImagenesController extends FOSRestController
{

    /**
    * @Rest\Post("/vehiculo_imagen/add")
    */
    public function postAddVehiculoImagenesAction(Request $request)
    {
        try { 
            // Obtaining vars from request          
            $vehImgNombre   = $request->get('nombre');
            $vehImgUrl      = $request->get('url');
            $veh            = $request->get('vehiculoid');
                      
            // Check for mandatory fields  
            if($vehImgNombre == ""){
                throw new HttpException (400,"El campo nombre no puede estar vacío");   
            }
            if($vehImgUrl == ""){
                throw new HttpException (400,"El campo url no puede estar vacío");   
            }
            if($veh == ""){
                throw new HttpException (400,"El campo vehiculo no puede estar vacío");   
            }

            // Find the relationship with Vehiculo
            $vehiculo = $this->getDoctrine()->getRepository('AppBundle:Vehiculo')->find($veh);
            if($vehiculo == "")
            {
                throw new HttpException (400,"El vehiculo especificado no existe");   
            }

            // Create the Vehiculo Imagen
            $vehimagen = new VehiculoImagen();
            $vehimagen -> setVehImgNombre($nombre);
            $vehimagen -> setVehImgUrl($url);
            $vehimagen -> setVehId($vehiculoid);
            $em = $this->getDoctrine()->getManager();
            
            // tells Doctrine you want to (eventually) save the Product (no queries yet)
            $em->persist($vehimagen);       
            // actually executes the queries (i.e. the INSERT query)
            $em->flush();
            
            $response = array("vehiculoimagen" => array(
                array(
                    "imagen vehiculo guardada " => $vehImgNombre,
                    "vehiculo:"                 => $vehiculo->getVehVariante(),
                    "url"                       => $vehimagen->getVehImgUrl(),
                    "ID"                        => $vehimagen->getVehImgId()
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
     * @Rest\Get("/vehiculo_imagen")
     */
    public function getAllVehiculoImagenAction()
    {
        $repository     = $this->getDoctrine()->getRepository('AppBundle:VehiculoImagen');
        $query          = $repository->createQueryBuilder('i')->getQuery();
        $vehimagen      = $query->getResult();
        return $vehimagen;
    }


    /**
     * @Rest\Get("/vehiculo_imagen/{vehimgid}")
     */
    public function getVehiculoImagenAction(Request $request)
    {
        $vehImgId       = $request->get('vehimgid');
        $repository     = $this->getDoctrine()->getRepository('AppBundle:VehiculoImagen');
        $vehImagenes    = $repository->findOneBymodId($vehImgId);
        return $vehImagenes;
    }



     /**
     * @Rest\Get("/vehiculo_imagen/{limit}/{page}")
     */
    public function getAllVehiculoImagenPaginatedAction(Request $request)
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
        $repository     = $this->getDoctrine()->getRepository('AppBundle:VehiculoImagen');
        // The dsql syntax query
        $query          = $repository->createQueryBuilder('v')->getQuery()->setFirstResult($limit * ($page - 1))->setMaxResults($limit);
        // Build the paginator
        $paginator      = new Paginator($query, $fetchJoinCollection = true);
        // Construct the response
        $response = array(
            'vehiculoImagenes'              => $paginator->getIterator(),
            'totalVehiculoImagenesReturned' => $paginator->getIterator()->count(),
            'totalVehiculoImagenes'         => $paginator->count()
        );
        // Send the response
        return $response;
    }



    /**
     * @Rest\Get("/vehiculo_imagen/{limit}/{page}/{searchtext}")
     */
    public function getAllVehiculoImagenPaginatedSearchAction(Request $request)
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
        $repository = $this->getDoctrine()->getRepository('AppBundle:VehiculoImagen');
    
        // The dsql syntax query
        $query = $repository->createQueryBuilder('vehImagen')->join('vehImagen.veh','v')
            ->where('v.vehVin LIKE :searchtext')
            ->orwhere('vehImagen.vehImgNombre LIKE :searchtext')
            ->orWhere('vehImagen.vehImgUrl LIKE :searchtext')
            ->setParameter('searchtext',"%" .$searchtext ."%")
            ->getQuery()
            ->setFirstResult($limit * ($page - 1))
            ->setMaxResults($limit);
        // Build the paginator
        $paginator = new Paginator($query, $fetchJoinCollection = true);
        // Construct the response
        $response = array(
            'vehiculoImagenes' => $paginator->getIterator(),
            'totalVehiculoImagenesReturned' => $paginator->getIterator()->count(),
            'totalVehiculoImagenes' => $paginator->count(),
            'searchedText' => $searchtext
        );
        // Send the response
        return $response;
    }


    /**
     * @Rest\Post("/vehiculo_imagen/edit/{vehimgid}")
     */
     public function postUpdateVehiculoImagenAction(Request $request)
     {
        try
        {
        $vehImgId       = $request->get('vehimgid');
        $vehImgNombre   = $request->get('nombre');
        $vehImgUrl      = $request->get('url');
        $veh            = $request->get('vehiculoid');
        $vehiculo       = $this->getDoctrine()->getRepository('AppBundle:Vehiculo')->find($veh);


        if($vehImgId == "" || !$vehImgId)
        {
             throw new HttpException (400,"Debe proveer un id para modificar el registro.");  
        }

        if($vehImgNombre == "")
        {
                throw new HttpException (400,"El campo nombre no puede estar vacío");   
        }
        if($vehImgUrl == "")
        {
               throw new HttpException (400,"El campo Url no puede estar vacío");   
        }
        if(!$vehiculo)
        {
               throw new HttpException (400,"Debe especificar un ID de Vehiculo válido");   
        }
         
        $em = $this->getDoctrine()->getManager();
        $vehiculoImagen = $em->getRepository('AppBundle:VehiculoImagen')->find($vehImgId);

        if (!$vehiculoImagen) 
        {
            throw new HttpException (400,"No se ha encontrado el vehiculo especificado: " .$vehImgId);
        }

        $vehimagen -> setVehImgNombre($nombre);
        $vehimagen -> setVehImgUrl($url);
        $vehimagen -> setVehId($vehiculoid);
        $em->flush();

        $response = array(
            'message'       => 'Imagen '.$vehimagen->getVehImgNombre().' ha sido actualizada',
            'url'           => $vehimagen->$getVehImgUrl,
            'vehiculo'      => $vehiculo->getVehVin()
         ); 
         return $response;
        }
        catch (\Doctrine\DBAL\Exception\UniqueConstraintViolationException $e){
            throw new HttpException (409,"Error: El nombre del vehiculo ya esxiste."); 
            } 
        catch (Exception $e) {
            return $e->getMessage();
            } 
     }


    /**
     * @Rest\Delete("/vehiculo_imagen/delete/{vehimgid}")
     */
    public function deleteRemoveVehiculoImagenAction(Request $request)
    {
        $vehImgId = $request->get('vehimgid');
        // get EntityManager
        $em             = $this->getDoctrine()->getManager();
        $vehImgtoremove = $em->getRepository('AppBundle:VehiculoImagen')->find($vehImgId);

        if ($vehImgtoremove != "") {      
            // Remove it and flush
            $em->remove($vehImgtoremove);
            $em->flush();
            $response = array(
                'message'   => 'La imagen '.$vehImgtoremove->getVehImgNombre().' ha sido eliminada',
                'vehImgId'  => $vehImgId
            );
             return $response;
        } else{
            throw new HttpException (400,"No se ha encontrado la imagen especificada, ID: " .$modeloid);
        }
        
    }


}