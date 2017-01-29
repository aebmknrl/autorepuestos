<?php

namespace AppBundle\Controller;

use FOS\RestBundle\Controller\Annotations\QueryParam;
use Symfony\Component\HttpKernel\Exception\HttpException;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\Tools\Pagination\Paginator;
use AppBundle\Entity\Equivalencia;

class EquivalenciasController extends FOSRestController
{

     /**
     * @Rest\Post("/equivalencia/add")
     */

    public function postAddEquivalenciaAction(Request $request)
    {
        try {
            // Obtaining vars from request
            $referencia = $request->get('referencia');
            $parte1id     = $request->get('parte1id');
            $parte2id     = $request->get('parte2id');
            
            // Check for mandatory fields
            if($referencia == ""){
                throw new HttpException (400,"El campo Referencia no puede estar vacío");   
            }
            if($parte1id == ""){
                throw new HttpException (400,"El campo Parte 1 no puede estar vacío");   
            }
            if($parte2id == ""){
                throw new HttpException (400,"El campo Parte 2 no puede estar vacío");   
            }

            // Find the relationship with Parte
            $parte1      = $this->getDoctrine()->getRepository('AppBundle:Parte')->find($parte1id);
            if($parte1 == "")
            {
                throw new HttpException (400,"La parte especificada no existe");   
            }
    
            $parte2      = $this->getDoctrine()->getRepository('AppBundle:Parte')->find($parte2id);
            if($parte2 == "")
            {
                throw new HttpException (400,"La parte especificada no existe");   
            }

            // Create the model
            $equivalencia = new Equivalencia();
            $equivalencia -> setEquivalenciaRef($referencia);
            $equivalencia -> setPart1($parte1);
            $equivalencia -> setPart2($parte2);
            $em = $this->getDoctrine()->getManager();
            
            // tells Doctrine you want to (eventually) save the Product (no queries yet)
            $em->persist($equivalencia);
            
            // actually executes the queries (i.e. the INSERT query)
            $em->flush();
            
            $response = array("Equivalencia" => array(
                array(
                    "Nueva equivalencia creada: "   => $referencia,
                    "Parte 1: "                     => $parte1->getParNombre(),
                    "Parte 2: "                     => $parte2->getParNombre(),
                    "ID"                            => $equivalencia->getId()
                    )
                )  
            ); 
            return $response;
        } 
        catch (\Doctrine\DBAL\Exception\UniqueConstraintViolationException $e){
            throw new HttpException (409,"Error: La equivalencia especificada ya existe."); 
            } 
        catch (Exception $e) {
            return $e->getMessage();
            } 
    }

    /**
     * @Rest\Get("/equivalencia")
     */
    public function getAllEquivalenciaAction()
    {
        $repository     = $this->getDoctrine()->getRepository('AppBundle:Equivalencia');
        $query          = $repository->createQueryBuilder('e')->getQuery();
        $equivalencia   = $query->getResult();
        return $equivalencia;
    }


    /**
     * @Rest\Get("/equivalencia/{equivalenciaid}")
     */
    public function getEquivalenciaAction(Request $request)
    {
        $eqid           = $request->get('equivalenciaid');
        $repository     = $this->getDoctrine()->getRepository('AppBundle:Equivalencia');
        $equivalencia   = $repository->findOneById($eqid);
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
        $repository = $this->getDoctrine()->getRepository('AppBundle:Equivalencia');
        // The dsql syntax query
        $query = $repository->createQueryBuilder('equivalencia')
            ->getQuery()
            ->setFirstResult($limit * ($page - 1))
            ->setMaxResults($limit);
        // Build the paginator
        $paginator = new Paginator($query, $fetchJoinCollection = true);
        // Construct the response
        $response = array(
            'equivalencias'                 => $paginator->getIterator(),
            'total equivalencias en página' => $paginator->getIterator()->count(),
            'total equivalencias'           => $paginator->count()
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
        $repository = $this->getDoctrine()->getRepository('AppBundle:Equivalencia');
    
        // The dsql syntax query
        $query = $repository->createQueryBuilder('equivalencia')->join('equivalencia.part1','e1')->join('equivalencia.part2','e2')
            ->where('equivalencia.equivalenciaRef LIKE :searchtext')
            ->orWhere('e1.parNombre LIKE :searchtext')
            ->orWhere('e2.parNombre LIKE :searchtext')
            ->setParameter('searchtext',"%" .$searchtext ."%")->getQuery()->setFirstResult($limit * ($page - 1))->setMaxResults($limit);
        // Build the paginator
        $paginator = new Paginator($query, $fetchJoinCollection = true);
        // Construct the response
        $response = array(
            'equivalencias'                     => $paginator->getIterator(),
            'total equivalencias en página'     => $paginator->getIterator()->count(),
            'total equivalencias encontradas'   => $paginator->count(),
            'busqueda por'                      => $searchtext
        );
        // Send the response
        return $response;
    }


    /**
     * @Rest\Post("/equivalencia/edit/{equivalenciaid}")
     */
     public function postUpdateEquivalenciaAction(Request $request)
     {

        try
        { 
        $equivalenciaid     = $request->get('equivalenciaid');
        $referencia         = $request->get('referencia');
        $parte1id           = $request->get('parte1id');
        $parte1             = $this->getDoctrine()->getRepository('AppBundle:Parte')->find($parte1id);
        $parte2id           = $request->get('parte2id');
        $parte2             = $this->getDoctrine()->getRepository('AppBundle:Parte')->find($parte2id);


         if($equivalenciaid == "" || !$equivalenciaid){
             throw new HttpException (400,"Debe proveer un id para modificar el registro.");  
         }

          if($parte1 == ""){
                throw new HttpException (400,"El campo Parte 1 no puede estar vacío");   
            }
            if($parte2 == ""){
                throw new HttpException (400,"El campo Parte 2 no puede estar vacío");   
            }
         
         $em = $this->getDoctrine()->getManager();
         $equivalencia = $em->getRepository('AppBundle:Equivalencia')
            ->find($equivalenciaid);


        if (!$equivalenciaid) {
        throw new HttpException (400,"No se ha encontrado la equivalencia especificada: " .$equivalenciaid);
         }

            $equivalencia -> setEquivalenciaRef($referencia);
            $equivalencia -> setPart1($parte1);
            $equivalencia -> setPart2($parte2);
        $em->flush();

        $response = array(
            'message'   => "La Equivalencia ".$referencia." ha sido actualizada",
            'Parte 1'   => $parte1->getParNombre(),
            'Parte 2'   => $parte2->getParNombre(),
         );
         return $response;
        }
        catch (\Doctrine\DBAL\Exception\UniqueConstraintViolationException $e)
        {
            throw new HttpException (409,"Error: La referencia ya existe para la equivalencia indicada."); 
            } 
                catch (Exception $e)
        {
            return $e->getMessage();
            }   
     }


     /**
     * @Rest\Delete("/equivalencia/delete/{equivalenciaid}")
     */
      public function deleteRemoveVehiculoAction(Request $request)
    {
        $equivalenciaid  = $request->get('equivalenciaid');
        // get EntityManager
        $em  = $this->getDoctrine()->getManager();
        $equivalenciatoremove = $em->getRepository('AppBundle:Equivalencia')->find($equivalenciaid);

        if ($equivalenciatoremove != "")
        {      
            // Remove it and flush
            $em->remove($equivalenciatoremove);
            $em->flush();
            $response = array(
                'message'   => 'La equivalencia '.$equivalenciatoremove->getEquivalenciaRef().' ha sido eliminada',
                'ID'     => $equivalenciaid
            );
             return $response;
        } else
        {
            throw new HttpException (400,"No se ha encontrado la equivalencia: " .$equivalenciaid);
        }     
    }

}