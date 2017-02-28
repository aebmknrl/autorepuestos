<?php

namespace AppBundle\Controller;

use FOS\RestBundle\Controller\Annotations\QueryParam;
use Symfony\Component\HttpKernel\Exception\HttpException;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\Tools\Pagination\Paginator;
use AppBundle\Entity\Parte;

class PartesController extends FOSRestController
{

     /**
     * @Rest\Post("/parte/add")
     */

    public function postAddParteAction(Request $request)
    {
        try {
            // Obtaining vars from request
            $parCodigo      = $request->get('parCodigo');
            $parUpc         = $request->get('parUpc');
            $parSku         = $request->get('parSku');
            $parLargo       = $request->get('parLargo');
            $parAncho       = $request->get('parAncho');
            $parEspesor     = $request->get('parEspesor');
            $parPeso        = $request->get('parPeso');
            $parteOrigen    = $request->get('parteOrigen');
            $parCaract      = $request->get('parCaract');
            $parObservacion = $request->get('parObservacion');
            $parAsin        = $request->get('parAsin');          
            $parSubgrupo    = $request->get('parSubgrupo');
            $parKit         = $request->get('parKit');
            $parEq          = $request->get('parEq');
            $fabricanteFab  = $request->get('fabricanteFab'); 
            $parNombre       = $request->get('parNombre');

            // Check for mandatory fields
            if($parCodigo == ""){
            throw new HttpException (400,"El campo nombre no puede estar vacío");   }

            // Find the relationships 
            $fabricante     = $this->getDoctrine()->getRepository('AppBundle:Fabricante')->find($fabricanteFab);
            if (!$parEq == ""){
                $equivalencia   = $this->getDoctrine()->getRepository('AppBundle:Equivalencia')->find($parEq);
            }
            if (!$parGrupo == ""){
                $grupo = $this->getDoctrine()->getRepository('AppBundle:Grupo')->find($parGrupo);
            }            
            if (!$parKit == ""){
                $kit = $this->getDoctrine()->getRepository('AppBundle:Conjunto')->find($parKit);
            }

            // Create the Parte
            $parte = new Parte();
            $parte -> setParCodigo($parCodigo);
            $parte -> setParUpc($parUpc);
            $parte -> setParSku($parSku);
            $parte -> setParLargo($parLargo);
            $parte -> setParAncho($parAncho);
            $parte -> setParEspesor($parEspesor);
            $parte -> setParPeso($parPeso);
            $parte -> setParteOrigen($parteOrigen);
            $parte -> setParCaract($parCaract);
            $parte -> setParObservacion($parObservacion);
            $parte -> setParAsin($parAsin);
            $parte -> setParSubgrupo($parSubgrupo);
            $parte -> setParKit($parkit);
            $parte -> setParEq($equivalencia);
            $parte -> setFabricanteFab($fabricante);
            $parte -> setParGrupo($grupo);
            $em = $this->getDoctrine()->getManager();
            
            // tells Doctrine you want to (eventually) save the Product (no queries yet)
            $em->persist($parte);
            
            // actually executes the queries (i.e. the INSERT query)
            $em->flush();
            
            $data = array("parte" => array(
                array(
                    "parte:"   => $nombre,
                    "id" => $parte->getParId()
                    )
                )  
            ); 
            return $data;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

       /**
     * @Rest\Get("/parte/{parteid}")
     */
    public function getParteAction(Request $request)
    {
        $parid = $request->get('parteid');

        $repository = $this->getDoctrine()->getRepository('AppBundle:Parte');
        $parte = $repository->findOneByparId($parid);
        return $parte;
    }
    
    /**
     * @Rest\Get("/parte")
     */
    public function getAllParteAction()
    {
        $repository = $this->getDoctrine()->getRepository('AppBundle:Parte');
        $query = $repository->createQueryBuilder('p')
            ->getQuery();
        $parte = $query->getResult();
        return $parte;
    }

     /**
     * @Rest\Get("/parte/{limit}/{page}")
     */
    public function getAllPartePaginatedAction(Request $request)
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
        $repository = $this->getDoctrine()->getRepository('AppBundle:Parte');
        // The dsql syntax query
        $query = $repository->createQueryBuilder('parte')
            ->getQuery()
            ->setFirstResult($limit * ($page - 1))
            ->setMaxResults($limit);
        // Build the paginator
        $paginator = new Paginator($query, $fetchJoinCollection = true);
        // Construct the response
        $response = array(
            'partes' => $paginator->getIterator(),
            'totalPartesReturned' => $paginator->getIterator()->count(),
            'totalPartes' => $paginator->count()
        );
        // Send the response
        return $response;
    }

    /**
     * @Rest\Get("/parte/{limit}/{page}/{searchtext}")
     */
    public function getAllPartePaginatedSearchAction(Request $request)
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
        $repository = $this->getDoctrine()->getRepository('AppBundle:Parte');
    
        // The dsql syntax query
        $query = $repository->createQueryBuilder('parte')->join('parte.parEq','e')
        ->join('parte.fabricanteFab','f')->join('parte.parNombre','n')                                                            
            ->where('parte.parCodigo LIKE :searchtext')
            ->orWhere('parte.parUpc LIKE :searchtext')
            ->orWhere('parte.parAsin LIKE :searchtext')
            ->orWhere('parte.parGrupo LIKE :searchtext')
            ->orWhere('parte.parSubgrupo LIKE :searchtext')
            ->orWhere('parte.parLargo LIKE :searchtext')
            ->orWhere('parte.parAncho LIKE :searchtext')
            ->orWhere('parte.parEspesor LIKE :searchtext')
            ->orWhere('parte.parPeso LIKE :searchtext')
            ->orWhere('parte.parCaract LIKE :searchtext')
            ->orWhere('parte.parObservacion LIKE :searchtext')
            ->orWhere('parte.parKit LIKE :searchtext')
            ->setParameter('searchtext',"%" .$searchtext ."%")
            ->getQuery()
            ->setFirstResult($limit * ($page - 1))
            ->setMaxResults($limit);
        // Build the paginator
        $paginator = new Paginator($query, $fetchJoinCollection = true);
        // Construct the response
        $response = array(
            'partes' => $paginator->getIterator(),
            'totalPartesReturned' => $paginator->getIterator()->count(),
            'totalPartes' => $paginator->count(),
            'searchedText' => $searchtext
        );
        // Send the response
        return $response;
    }


    /**
     * @Rest\Post("/parte/edit/{parteid}")
     */
     public function postUpdateParteAction(Request $request)
     {
            // Obtaining vars from request
            $parId          = $request->get('parId');
            $parCodigo      = $request->get('parCodigo');
            $parUpc         = $request->get('parUpc');
            $parSku         = $request->get('parSku');
            $parLargo       = $request->get('parLargo');
            $parAncho       = $request->get('parAncho');
            $parEspesor     = $request->get('parEspesor');
            $parPeso        = $request->get('parPeso');
            $parteOrigen    = $request->get('parteOrigen');
            $parCaract      = $request->get('parCaract');
            $parObservacion = $request->get('parObservacion');
            $parAsin        = $request->get('parAsin');          
            $parSubgrupo    = $request->get('parSubgrupo');
            $parKit         = $request->get('parKit');
            $parEq          = $request->get('parEq');
            $fabricanteFab  = $request->get('fabricanteFab'); 
            $parNombre       = $request->get('parNombre');

         if($parId == "" || !$parId){
             throw new HttpException (400,"Debe proveer un id para modificar el registro.");  
         }

            // Find the relationships 
            $fabricante     = $this->getDoctrine()->getRepository('AppBundle:Fabricante')->find($fabricanteFab);
            if (!$parEq == ""){
                $equivalencia   = $this->getDoctrine()->getRepository('AppBundle:Equivalencia')->find($parEq);
            }
            if (!$parGrupo == ""){
                $grupo = $this->getDoctrine()->getRepository('AppBundle:Grupo')->find($parGrupo);
            }            
            if (!$parKit == ""){
                $kit = $this->getDoctrine()->getRepository('AppBundle:Conjunto')->find($parKit);
            }
                     
         $em = $this->getDoctrine()->getManager();
         $parte = $em->getRepository('AppBundle:Parte')
            ->find($parteid);


        if (!$parte) {
        throw new HttpException (400,"No se ha encontrado la parte especificada: " .$parteid);
         }

            // Create the Parte
            $parte = new Parte();
            $parte -> setParCodigo($parCodigo);
            $parte -> setParUpc($parUpc);
            $parte -> setParSku($parSku);
            $parte -> setParLargo($parLargo);
            $parte -> setParAncho($parAncho);
            $parte -> setParEspesor($parEspesor);
            $parte -> setParPeso($parPeso);
            $parte -> setParteOrigen($parteOrigen);
            $parte -> setParCaract($parCaract);
            $parte -> setParObservacion($parObservacion);
            $parte -> setParAsin($parAsin);
            $parte -> setParSubgrupo($parSubgrupo);
            $parte -> setParKit($parkit);
            $parte -> setParEq($equivalencia);
            $parte -> setFabricanteFab($fabricante);
            $parte -> setParGrupo($grupo);
            $em->flush();

        $data = array(
            'message' => 'La parte ha sido actualizada',
             'parteid' => $parId,
             'parte' => $parCodigo
         );

         return $request;

     }

}