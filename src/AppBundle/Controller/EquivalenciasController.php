<?php

namespace AppBundle\Controller;

use FOS\RestBundle\Controller\Annotations\QueryParam;
use Symfony\Component\HttpKernel\Exception\HttpException;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\Tools\Pagination\Paginator;
use AppBundle\Entity\EquivalenciaV2;

class EquivalenciasController extends FOSRestController
{

     /**
     * @Rest\Post("/equivalencia/add")
     */

    public function postAddEquivalenciaAction(Request $request)
    {
        try {
            
            $referencia = $request->get('referencia');
            $par1id = $request->get('parte1id');
            $parte1 = $this->getDoctrine()
                ->getRepository('AppBundle:Parte')
                ->find($par1id);
            $par2id = $request->get('parte2id');
            $parte2 = $this->getDoctrine()
                ->getRepository('AppBundle:Parte')
                ->find($par2id);
            
            if($par1id == ""){
                throw new HttpException (400,"El campo Parte 1 no puede estar vacío");   
            }
            if($par2id == ""){
                throw new HttpException (400,"El campo Parte2 no puede estar vacío");   
            }


            $equivalencia = new EquivalenciaV2();
            $equivalencia -> setEquivalenciaRef($referencia);
            $equivalencia -> setPart1Id($parte1);
            $equivalencia -> setPart2Id($parte2);
            $em = $this->getDoctrine()->getManager();
            
            // tells Doctrine you want to (eventually) save the Product (no queries yet)
            $em->persist($equivalencia);
            
            // actually executes the queries (i.e. the INSERT query)
            $em->flush();
            
            $data = array("Equivalencia" => array(
                array(
                    "Equivalencia"   => $referencia,
                    "ID" => $equivalencia->getId()
                    )
                )  
            ); 
            return $data;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * @Rest\Get("/equivalencia/{equivalenciaid}")
     */
    public function getEquivalenciaAction(Request $request)
    {
        $eqid = $request->get('equivalenciaid');

        $repository = $this->getDoctrine()->getRepository('AppBundle:EquivalenciaV2');
        $equivalencia = $repository->findOneById($eqid);
        return $equivalencia;
    }
    
    /**
     * @Rest\Get("/equivalencia")
     */
    public function getAllEquivalenciaAction()
    {
        $repository = $this->getDoctrine()->getRepository('AppBundle:EquivalenciaV2');
        $query = $repository->createQueryBuilder('eq')
            ->getQuery();
        $equivalencia = $query->getResult();
        return $equivalencia;
    }

     /**
     * @Rest\Get("/equivalencia/{limit}/{page}")
     */
    public function getAllEquivalenciaPaginatedAction(Request $request)
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
        $repository = $this->getDoctrine()->getRepository('AppBundle:EquivalenciaV2');
        // The dsql syntax query
        $query = $repository->createQueryBuilder('equivalencia')
            ->getQuery()
            ->setFirstResult($limit * ($page - 1))
            ->setMaxResults($limit);
        // Build the paginator
        $paginator = new Paginator($query, $fetchJoinCollection = true);
        // Construct the response
        $response = array(
            'equivalencias' => $paginator->getIterator(),
            'totalEquivalenciasReturned' => $paginator->getIterator()->count(),
            'totalEquivalencias' => $paginator->count()
        );
        // Send the response
        return $response;
    }
    /**
     * @Rest\Get("/equivalencia/{limit}/{page}/{searchtext}")
     */
    public function getAllEquivalenciaPaginatedSearchAction(Request $request)
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
        $repository = $this->getDoctrine()->getRepository('AppBundle:EquivalenciaV2');
    
        // The dsql syntax query
        $query = $repository->createQueryBuilder('equivalencia')//->join('modelo.Marca','m')
            //->where('m.marNombre = :searchtext')
            ->where('equivalencia.equivalenciaRef LIKE :searchtext')
            ->orWhere('equivalencia.part1id LIKE :searchtext')
            ->orWhere('equivalencia.part2id LIKE :searchtext')
            ->setParameter('searchtext',"%" .$searchtext ."%")
            ->getQuery()
            ->setFirstResult($limit * ($page - 1))
            ->setMaxResults($limit);
        // Build the paginator
        $paginator = new Paginator($query, $fetchJoinCollection = true);
        // Construct the response
        $response = array(
            'equivalencia' => $paginator->getIterator(),
            'totalEquivalenciasReturned' => $paginator->getIterator()->count(),
            'totalEquivalencias' => $paginator->count(),
            'searchedText' => $searchtext
        );
        // Send the response
        return $response;
    }


    /**
     * @Rest\Post("/equivalencia/edit/{equivalencia}")
     */
     public function postUpdateEquivalenciaAction(Request $request)
     {
        $equivalenciaid = $request->get('equivalenciaid');
        $referencia = $request->get('referencia');
        $par1id = $request->get('parte1id');
        $parte1 = $this->getDoctrine()
                ->getRepository('AppBundle:Parte')
                ->find($par1id);
        $par2id = $request->get('parte2id');
        $parte2 = $this->getDoctrine()
                ->getRepository('AppBundle:Parte')
                ->find($par2id);


         if($equivalenciaid == "" || !$equivalenciaid){
             throw new HttpException (400,"Debe proveer un id para modificar el registro.");  
         }

          if($par1id == ""){
                throw new HttpException (400,"El campo Parte 1 no puede estar vacío");   
            }
            if($par2id == ""){
                throw new HttpException (400,"El campo Parte 2 no puede estar vacío");   
            }
         
         $em = $this->getDoctrine()->getManager();
         $modelo = $em->getRepository('AppBundle:EquivalenciaV2')
            ->find($equivalenciaid);


        if (!$equivalencia) {
        throw new HttpException (400,"No se ha encontrado el modelo especificado: " .$equivalenciaid);
         }

        $equivalencia -> setEquivalenciaRef($referencia);
        $equivalencia -> setPart1Id($parte1);
        $equivalencia -> setPart2Id($parte2);
        $em->flush();

        $data = array(
            'message' => 'La Equivalencia ha sido actualizada',
             'referencia' => $referencia,

         );

         return $request;

     }
}