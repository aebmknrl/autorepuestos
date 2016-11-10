<?php

namespace AppBundle\Controller;

use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\Marca;

class MarcasController extends FOSRestController
{

     /**
     * @Rest\Post("/addmarca")
     */
    public function postMarcasAction(Request $request)
    {
        $marca = new Marca();
        $marca -> setMarNombre($nombre);
        $marca -> setMarObservacion($observacion);
        $em = $this->getDoctrine()->getManager();
        
        // tells Doctrine you want to (eventually) save the Product (no queries yet)
        $em->persist($marca);
        
        // actually executes the queries (i.e. the INSERT query)
        $em->flush();
        
        $data = array("Marca" => array(
            array(
                "Marca:"   => $nombre,
                "ID" => $marca->getMarId()
                )
            )  
        );  
        return $data;
    }
}