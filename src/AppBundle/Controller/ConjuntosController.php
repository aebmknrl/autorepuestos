<?php

namespace AppBundle\Controller;

use FOS\RestBundle\Controller\Annotations\QueryParam;
use Symfony\Component\HttpKernel\Exception\HttpException;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\Tools\Pagination\Paginator;
use AppBundle\Entity\ConjuntoV2;

class ConjuntosController extends FOSRestController
{

     /**
     * @Rest\Post("/conjunto/add")
     */

    public function postAddConjuntoAction(Request $request)
    {
        try {
            
            $referencia = $request->get('referencia');
            $cantidad = $request->get('cantidad');
            $parteid = $request->get('parteid');
            $parte = $this->getDoctrine()
                ->getRepository('AppBundle:Parte')
                ->find($parteid);
            
            if($referencia == ""){
                throw new HttpException (400,"El campo referencia no puede estar vacío");   
            }
            if($parteid == ""){
                throw new HttpException (400,"El campo parte no puede estar vacío");   
            }


            $conjunto = new ConjuntoV2();
            $conjunto -> setKitRef($referencia);
            $conjunto -> setKitCount($cantidad);
            $conjunto -> setParteId($parte);
            $em = $this->getDoctrine()->getManager();
            
            // tells Doctrine you want to (eventually) save the Product (no queries yet)
            $em->persist($conjunto);
            
            // actually executes the queries (i.e. the INSERT query)
            $em->flush();
            
            $data = array("Conjunto" => array(
                array(
                    "Conjunto:"   => $referencia,
                    "ID" => $conjunto->getId()
                    )
                )  
            ); 
            return $data;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * @Rest\Get("/conjunto/{conjuntoid}")
     */
    public function getConjuntoAction(Request $request)
    {
        $id = $request->get('conjuntoid');

        $repository = $this->getDoctrine()->getRepository('AppBundle:ConjuntoV2');
        $conjunto = $repository->findOneById($id);
        return $conjunto;
    }
    
    /**
     * @Rest\Get("/conjunto")
     */
    public function getAllConjuntoAction()
    {
        $repository = $this->getDoctrine()->getRepository('AppBundle:ConjuntoV2');
        $query = $repository->createQueryBuilder('c')
            ->getQuery();
        $conjunto = $query->getResult();
        return $conjunto;
    }

     /**
     * @Rest\Get("/conjunto/{limit}/{page}")
     */
    public function getAllConjuntoPaginatedAction(Request $request)
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
        $repository = $this->getDoctrine()->getRepository('AppBundle:ConjuntoV2');
        // The dsql syntax query
        $query = $repository->createQueryBuilder('conjunto')
            ->getQuery()
            ->setFirstResult($limit * ($page - 1))
            ->setMaxResults($limit);
        // Build the paginator
        $paginator = new Paginator($query, $fetchJoinCollection = true);
        // Construct the response
        $response = array(
            'conjunto' => $paginator->getIterator(),
            'totalConjuntosReturned' => $paginator->getIterator()->count(),
            'totalConjuntos' => $paginator->count()
        );
        // Send the response
        return $response;
    }
    /**
     * @Rest\Get("/conjunto/{limit}/{page}/{searchtext}")
     */
    public function getAllConjuntoPaginatedSearchAction(Request $request)
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
        $repository = $this->getDoctrine()->getRepository('AppBundle:ConjuntoV2');
    
        // The dsql syntax query
        $query = $repository->createQueryBuilder('modelo')//->join('modelo.Marca','m')
            //->where('m.marNombre = :searchtext')
            ->where('conjunto.kitRef LIKE :searchtext')
            ->setParameter('searchtext',"%" .$searchtext ."%")
            ->getQuery()
            ->setFirstResult($limit * ($page - 1))
            ->setMaxResults($limit);
        // Build the paginator
        $paginator = new Paginator($query, $fetchJoinCollection = true);
        // Construct the response
        $response = array(
            'conjuntos' => $paginator->getIterator(),
            'totalConjuntosReturned' => $paginator->getIterator()->count(),
            'totalConjuntos' => $paginator->count(),
            'searchedText' => $searchtext
        );
        // Send the response
        return $response;
    }


    /**
     * @Rest\Post("/conjunto/edit/{conjuntoid}")
     */
     public function postUpdateConjuntoAction(Request $request)
     {
            $conjuntoid = $request->get('conjuntoid');
            $referencia = $request->get('referencia');
            $cantidad = $request->get('cantidad');
            $parteid = $request->get('parteid');
            $parte = $this->getDoctrine()
                ->getRepository('AppBundle:Parte')
                ->find($parteid);


         if($conjuntoid == "" || !$conjuntoid){
             throw new HttpException (400,"Debe proveer un id para modificar el registro.");  
         }

         if($referencia == ""){
                throw new HttpException (400,"El campo referencia no puede estar vacío");   
            }
            if($parteid == ""){
                throw new HttpException (400,"El campo parte no puede estar vacío");   
            }
         
         $em = $this->getDoctrine()->getManager();
         $conjunto = $em->getRepository('AppBundle:conjuntoV2')
            ->find($conjuntoid);


        if (!$conjuntoid) {
        throw new HttpException (400,"No se ha encontrado el conjunto especificado: " .$conjuntoid);
         }

        $conjunto -> setKitRef($conjunto);
        $conjunto -> setKitCount($cantidad);
        $conjunto -> setParteId($parte);
        $em->flush();

        $data = array(
            'message' => 'El conjunto ha sido actualizado',
             'conjuntoid' => $conjuntoid,
             'referencia' => $referencia,
         );

         return $request;

     }
}   