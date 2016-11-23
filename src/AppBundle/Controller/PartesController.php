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
            
            $nombre = $request->get('nombre');
            $nombret = $request->get('nombrepieza');
            $nombreinv = $request->get('nombreinventario');
            $parasin = $request->get('paramazon');  
            $parcodigo = $request->get('codigo');          
            $upc = $request->get('codigoupc');
            $pargrupo = $request->get('grupo');
            $parsubgrupo = $request->get('subgrupo');
            $parlargo = $request->get('largo');
            $parancho = $request->get('ancho');
            $parespesor = $request->get('espesor');
            $parpeso = $request->get('peso');
            $parcaracter = $request->get('caracteristicas');
            $parobservacion = $request->get('observacion');
            $parkit = $request->get('kit');
            $equivalencia = $request->get('equivalencia');
            $conjunto = $request->get('conjunto');
            $fabricanteid = $request->get('fabricanteid');
            $fabricante = $this->getDoctrine()
                        ->getRepository('AppBundle:Fabricante')
                        ->find($fabricanteid);

          
            if($nombre == ""){
                throw new HttpException (400,"El campo nombre no puede estar vacío");   
            }
            if($nombret == ""){
                throw new HttpException (400,"El campo nombre parte parte no puede estar vacío");   
            }


            $parte = new Parte();
            $parte -> setParUpc($upc);
            $parte -> setParNombre($nombre);
            $parte -> setParNombret($nombret);
            $parte -> setParNombrein($nombreinv);
            $parte -> setParAsin($parasin);
            $parte -> setParCodigo($parcodigo);
            $parte -> setParGrupo($pargrupo);
            $parte -> setParSubgrupo($parsubgrupo);
            $parte -> setParLargo($parlargo);
            $parte -> setParAncho($parancho);
            $parte -> setParEspesor($parespesor);
            $parte -> setParPeso($parpeso);
            $parte -> setParCaract($parcaracter);
            $parte -> setParObservacion($parobservacion);
            $parte -> setParKit($parkit);
            $parte -> setParEq($equivalencia);
            $parte -> setFabricanteFab($fabricante);
            $parte -> setKit();
            $em = $this->getDoctrine()->getManager();
            
            // tells Doctrine you want to (eventually) save the Product (no queries yet)
            $em->persist($parte);
            
            // actually executes the queries (i.e. the INSERT query)
            $em->flush();
            
            $data = array("Parte" => array(
                array(
                    "Parte:"   => $nombre,
                    "ID" => $parte->getParId()
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
        $query = $repository->createQueryBuilder('parte')///->join('modelo.Marca','m')
           // ->where('m.marNombre = :searchtext')
            ->where('parte.parNombre LIKE :searchtext')
            ->orWhere('parte.parNombret LIKE :searchtext')
            ->orWhere('parte.parNombrein LIKE :searchtext')
            ->orWhere('parte.parUpc LIKE :searchtext')
            ->orWhere('parte.parAsin LIKE :searchtext')
            ->orWhere('parte.parCodigo LIKE :searchtext')
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
     public function postUpdateModeloAction(Request $request)
     {
        $parteid = $request->get('parteid');
        $nombre = $request->get('nombre');
        $nombret = $request->get('nombrepieza');
        $nombreinv = $request->get('nombreinventario');
        $parasin = $request->get('paramazon');  
        $parcodigo = $request->get('codigo');          
        $upc = $request->get('codigoupc');
        $pargrupo = $request->get('grupo');
        $parsubgrupo = $request->get('subgrupo');
        $parlargo = $request->get('largo');
        $parancho = $request->get('ancho');
        $parespesor = $request->get('espesor');
        $parpeso = $request->get('peso');
        $parcaracter = $request->get('caracteristicas');
        $parobservacion = $request->get('observacion');
        $parkit = $request->get('kit');
        $equivalencia = $request->get('equivalencia');
        $conjunto = $request->get('conjunto');
        $fabricanteid = $request->get('fabricanteid');
        $fabricante = $this->getDoctrine()
                    ->getRepository('AppBundle:Fabricante')
                    ->find($fabricanteid);
         


         if($parteid == "" || !$parteid){
             throw new HttpException (400,"Debe proveer un id para modificar el registro.");  
         }

          if($nombre == ""){
                throw new HttpException (400,"El campo nombre no puede estar vacío");   
            }
            if($nombret == ""){
                throw new HttpException (400,"El campo nombre pieza no puede estar vacío");   
            }
         
         $em = $this->getDoctrine()->getManager();
         $parte = $em->getRepository('AppBundle:Parte')
            ->find($parteid);


        if (!$parte) {
        throw new HttpException (400,"No se ha encontrado la parte especificada: " .$parteid);
         }

        $parte -> setParUpc($upc);
        $parte -> setParNombre($nombre);
        $parte -> setParNombret($nombret);
        $parte -> setParNombrein($nombreinv);
        $parte -> setParAsin($parasin);
        $parte -> setParCodigo($parcodigo);
        $parte -> setParGrupo($pargrupo);
        $parte -> setParSubgrupo($parsubgrupo);
        $parte -> setParLargo($parlargo);
        $parte -> setParAncho($parancho);
        $parte -> setParEspesor($parespesor);
        $parte -> setParPeso($parpeso);
        $parte -> setParCaract($parcaracter);
        $parte -> setParObservacion($parobservacion);
        $parte -> setParKit($parkit);
        $parte -> setParEq($equivalencia);
        $parte -> setFabricanteFab($fabricante);
        $parte -> setKit();
        $em->flush();

        $data = array(
            'message' => 'La parte ha sido actualizada',
             'parteid' => $parteid,
             'nombre' => $nombre,
             'observacion' => $observacion
         );

         return $request;

     }

}