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
            
            $data = array("Marca" => array(
                array(
                    "Marca:"   => $nombre,
                    "ID" => $marca->getMarId()
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
        $marcas = $repository->findAll();
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
        

        /*
        $repository = $this->getDoctrine()->getRepository('AppBundle:Marca');
        $query = $repository->createQueryBuilder('p')
            ->getQuery()
            ->setFirstResult(0)
            ->setMaxResults(100);
        $marcas = $query->getResult();
        $paginator = new Paginator($query, $fetchJoinCollection = true);
        $c = count($paginator);

        return $paginator;*/
    }

     /**
     * @Rest\Get("/marca/{limit}/{page}")
     */
    public function getAllMarcaPaginatedAction(Request $request)
    {
        $limit = $request->get('limit');
        $page = $request->get('page');

        if(!is_numeric($limit) || !is_numeric($page)) {
            throw new HttpException (400,"Por favor use solo números");  
        }

        $data = array("config" => array(
            'limit'=> $limit,
            'page' => $page
            )
        );
        return $data;
    }
}   