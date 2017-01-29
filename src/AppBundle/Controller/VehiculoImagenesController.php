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
            $nombre         = $request->get('nombre');
            $url            = $request->get('url');
            $vehiculoid     = $request->get('vehiculoid');
                      
            // Check for mandatory fields  
            if($nombre == ""){
                throw new HttpException (400,"El campo nombre no puede estar vacío");   
            }
            if($url == ""){
                throw new HttpException (400,"El campo url no puede estar vacío");   
            }
            if($vehiculoid == ""){
                throw new HttpException (400,"El campo vehiculo no puede estar vacío");   
            }

            // Find the relationship with Vehiculo
            $vehiculo = $this->getDoctrine()->getRepository('AppBundle:Vehiculo')->find($vehiculoid);
            if($vehiculo == "")
            {
                throw new HttpException (400,"El vehiculo especificado no existe");   
            }

            // Create the model
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
                    "imagen vehiculo guardada " => $nombre,
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




}