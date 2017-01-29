<?php

namespace AppBundle\Controller;

use FOS\RestBundle\Controller\Annotations\QueryParam;
use Symfony\Component\HttpKernel\Exception\HttpException;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\Tools\Pagination\Paginator;
use AppBundle\Entity\HistoricoInv;

class HistoricoInvController extends FOSRestController
{

     /**
     * @Rest\Post("/historico_inv/add")
     */

    public function postAddModeloAction(Request $request)
    {
        try {
            
            $fecha = $request->get('fecha');
            $cantidad = $request->get('cantidad');
            $inventarioid = $request->get('inventario');
            $inventario = $this->getDoctrine()
                ->getRepository('AppBundle:Inventario')
                ->find($inventarioid);
            
            if($inventarioid == ""){
                throw new HttpException (400,"El campo inventario no puede estar vacío");   
            }
            if($fecha == ""){
                throw new HttpException (400,"El campo fecha no puede estar vacío");   
            }
            if($cantidad == ""){
                throw new HttpException (400,"El campo cantidad no puede estar vacío");   
            }

            $historico = new HistoricoInv();
            $historico -> setHisInvFecha(new \DateTime($fecha));
            $historico -> setHisInvCantidad($cantidad);
            $historico -> setInventarioInv($inventario);
            $em = $this->getDoctrine()->getManager();
            
            // tells Doctrine you want to (eventually) save the Product (no queries yet)
            $em->persist($historico);
            
            // actually executes the queries (i.e. the INSERT query)
            $em->flush();
            
            $data = array("historicoInv" => array(
                array(
                    "Fecha:"   => $fecha,
                    "Cantidad" => $cantidad,
                    "ID" => $historico->gethisInvId()
                    )
                )  
            ); 
            return $data;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * @Rest\Get("/historico_inv/{hisInvId}")
     */
    public function getHistoricoInvAction(Request $request)
    {
        $hisInvId = $request->get('hisInvId');

        $repository = $this->getDoctrine()->getRepository('AppBundle:HistoricoInv');
        $historico = $repository->findOneByhisInvId($hisInvId);
        return $historico;
    }
    
    /**
     * @Rest\Get("/historico_inv")
     */
    public function getAllHistoricoInvAction()
    {
        // Initialize the 'MarcaModelo' data repository
        $repository = $this->getDoctrine()->getRepository('AppBundle:HistoricoInv');
        $query = $repository->createQueryBuilder('h')
            ->getQuery();
        $historico = $query->getResult();
        return $historico;
    }
    /**
     * @Rest\Get("/historico_inv/{limit}/{page}")
     */
    public function getAllHistoricoInvPaginatedAction(Request $request)
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
        $repository = $this->getDoctrine()->getRepository('AppBundle:HistoricoInv');
        // The dsql syntax query
        $query = $repository->createQueryBuilder('historicoInv')
            ->getQuery()
            ->setFirstResult($limit * ($page - 1))
            ->setMaxResults($limit);
        // Build the paginator
        $paginator = new Paginator($query, $fetchJoinCollection = true);
        // Construct the response
        $response = array(
            'historicoInv' => $paginator->getIterator(),
            'totalHistoricoReturned' => $paginator->getIterator()->count(),
            'totalHistorico' => $paginator->count()
        );
        // Send the response
        return $response;
    }
    /**
     * @Rest\Get("/modelo/{limit}/{page}/{searchtext}")
     */
    public function getAllHistoricoInvPaginatedSearchAction(Request $request)
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
        $repository = $this->getDoctrine()->getRepository('AppBundle:HistoricoInv');
    
        // The dsql syntax query
        $query = $repository->createQueryBuilder('historico_inv')//->join('modelo.Marca','m')
            //->where('m.marNombre = :searchtext')
            ->where('historico_inv.hisInvFecha LIKE :searchtext')
            ->orWhere('historico_inv.hisInvCantidad LIKE :searchtext')
            ->setParameter('searchtext',"%" .$searchtext ."%")
            ->getQuery()
            ->setFirstResult($limit * ($page - 1))
            ->setMaxResults($limit);
        // Build the paginator
        $paginator = new Paginator($query, $fetchJoinCollection = true);
        // Construct the response
        $response = array(
            'historicoInv' => $paginator->getIterator(),
            'totalHistoricoReturned' => $paginator->getIterator()->count(),
            'totalHistorico' => $paginator->count(),
            'searchedText' => $searchtext
        );
        // Send the response
        return $response;
    }


    /**
     * @Rest\Post("/hsitorico_inv/edit/{hisInvId}")
     */
     public function postUpdateHistoricoInvAction(Request $request)
     {
         $hisInvId = $request->get('hisInvId');
         $fecha = $request->get('fecha');
         $cantidad = $request->get('cantidad');
         $inventarioid = $request->get('inventario');
         $inventario = $this->getDoctrine()
                ->getRepository('AppBundle:Inventario')
                ->find($inventarioid);


         if($hisInvId == "" || !$hisInvId){
             throw new HttpException (400,"Debe proveer un id para modificar el registro.");  
         }

         if($inventarioid == ""){
                throw new HttpException (400,"El campo inventario no puede estar vacío");   
            }
            if($fecha == ""){
                throw new HttpException (400,"El campo fecha no puede estar vacío");   
            }
            if($cantidad == ""){
                throw new HttpException (400,"El campo cantidad no puede estar vacío");   
            }
         
         $em = $this->getDoctrine()->getManager();
         $historico = $em->getRepository('AppBundle:HistoricoInv')
            ->find($hisInvId);


        if (!$historico) {
        throw new HttpException (400,"No se ha encontrado el modelo especificado: " .$hisInvId);
         }

        $historico -> setHisInvFecha(new \DateTime($fecha));
        $historico -> setHisInvCantidad($cantidad);
        $historico -> setInventarioInv($inventario);
        $em->flush();

        $data = array(
            'message' => 'El Historico ha sido actualizado',
             'Historico' => $hisInvId,
             'fecha' => $fecha,
             'cantidad' => $cantidad
         );

         return $request;

     }

}