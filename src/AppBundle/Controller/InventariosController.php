<?php

namespace AppBundle\Controller;

use FOS\RestBundle\Controller\Annotations\QueryParam;
use Symfony\Component\HttpKernel\Exception\HttpException;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\Tools\Pagination\Paginator;
use AppBundle\Entity\Inventario;

class InventariosController extends FOSRestController
{

     /**
     * @Rest\Post("/inventario/add")
     */

    public function postAddInventarioAction(Request $request)
    {
        try {
            
            $cantidad       = $request->get('cantidad');
            $precio         = $request->get('precio');
            $empaque        = $request->get('empaque');
            $observacion    = $request->get('observacion');
            $parteid        = $request->get('parteid');
            $parte          = $this->getDoctrine()->getRepository('AppBundle:Parte')->find($parteid);
            $parteid        = $request->get('parteid');
            $provid         = $request->get('proveedorid');
            $proveedor      = $this->getDoctrine()->getRepository('AppBundle:Proveedor')->find($provid);
            
            if($empaque == ""){
                throw new HttpException (400,"El campo empaque no puede estar vacío");   
            }
            if($observacion == ""){
                throw new HttpException (400,"El campo observacion no puede estar vacío");   
            }


            $inventario = new Inventario();
            $inventario -> setInvCantidad($cantidad);
            $inventario -> setInvPrecio($precio);
            $inventario -> setInvEmpaque($empaque);
            $inventario -> setInvObservacion($observacion);
            $inventario -> setPartePar($parte);
            $inventario -> setProveedorProv($proveedor);
            $em = $this->getDoctrine()->getManager();
            
            // tells Doctrine you want to (eventually) save the Product (no queries yet)
            $em->persist($inventario);
            
            // actually executes the queries (i.e. the INSERT query)
            $em->flush();
            
            $data = array("Inventario" => array(
                array(
                    "Inventario:"   => $empaque,
                    "ID" => $inventario->getInvId()
                    )
                )  
            ); 
            return $data;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * @Rest\Get("/inventario/{inventarioid}")
     */
    public function getInventarioAction(Request $request)
    {
        $invid = $request->get('inventarioid');

        $repository = $this->getDoctrine()->getRepository('AppBundle:Inventario');
        $inventario = $repository->findOneByInvId($invid);
        return $inventario;
    }
    
    /**
     * @Rest\Get("/inventario")
     */
    public function getAllInventarioAction()
    {
        $repository = $this->getDoctrine()->getRepository('AppBundle:Inventario');
        $query = $repository->createQueryBuilder('p')
            ->getQuery();
        $inventario = $query->getResult();
        return $inventario;
    }

     /**
     * @Rest\Get("/inventario/{limit}/{page}")
     */
    public function getAllInventarioPaginatedAction(Request $request)
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
        $repository = $this->getDoctrine()->getRepository('AppBundle:Inventario');
        // The dsql syntax query
        $query = $repository->createQueryBuilder('invenatario')
            ->getQuery()
            ->setFirstResult($limit * ($page - 1))
            ->setMaxResults($limit);
        // Build the paginator
        $paginator = new Paginator($query, $fetchJoinCollection = true);
        // Construct the response
        $response = array(
            'inventario' => $paginator->getIterator(),
            'totalInventarioReturned' => $paginator->getIterator()->count(),
            'totalInventario' => $paginator->count()
        );
        // Send the response
        return $response;
    }

    /**
     * @Rest\Get("/inventario/{limit}/{page}/{searchtext}")
     */
    public function getAllInventarioPaginatedSearchAction(Request $request)
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
        $repository = $this->getDoctrine()->getRepository('AppBundle:Inventario');
    
        // The dsql syntax query
        $query = $repository->createQueryBuilder('inventario')->join('inventario.partePar','par')->join('inventario.proveedorProv','prov')
            ->where('inventario.invObservacion LIKE :searchtext')
            ->orwhere('par.parNombre LIKE :searchtext')
            ->orwhere('prov.provNombre LIKE :searchtext')
            ->orwhere('inventario.invCantidad LIKE :searchtext')
            ->orwhere('inventario.invPrecio LIKE :searchtext')
            ->orwhere('inventario.invEmpaque LIKE :searchtext')
            ->orwhere('inventario.invObservacion LIKE :searchtext')
            ->setParameter('searchtext',"%" .$searchtext ."%")
            ->getQuery()
            ->setFirstResult($limit * ($page - 1))
            ->setMaxResults($limit);
        // Build the paginator
        $paginator = new Paginator($query, $fetchJoinCollection = true);
        // Construct the response
        $response = array(
            'inventarios' => $paginator->getIterator(),
            'totalInventariosReturned' => $paginator->getIterator()->count(),
            'totalInventarios' => $paginator->count(),
            'searchedText' => $searchtext
        );
        // Send the response
        return $response;
    }

    /**
     * @Rest\Post("/inventario/edit/{inventarioid}")
     */
     public function postUpdateInventarioAction(Request $request)
     {
         $inventarioid = $request->get('inventarioid');
         $cantidad = $request->get('cantidad');
         $precio = $request->get('precio');
         $empaque = $request->get('empaque');
         $observacion = $request->get('observacion');
         $parteid = $request->get('parteid');
         $parte = $this->getDoctrine()
                ->getRepository('AppBundle:Parte')
                ->find($parteid);
                        $parteid = $request->get('parteid');
         $provid = $request->get('proveedorid');
         $proveedor = $this->getDoctrine()
                ->getRepository('AppBundle:Proveedor')
                ->find($provid);


         if($inventarioid == "" || !$inventarioid){
             throw new HttpException (400,"Debe proveer un id para modificar el registro.");  
         }

          if($empaque == ""){
                throw new HttpException (400,"El campo empaque no puede estar vacío");   
            }
            if($observacion == ""){
                throw new HttpException (400,"El campo observacion no puede estar vacío");   
            }
         
         $em = $this->getDoctrine()->getManager();
         $inventario = $em->getRepository('AppBundle:Inventario')
            ->find($inventarioid);


        if (!$inventario) {
        throw new HttpException (400,"No se ha encontrado el inventario especificado: " .$inventarioid);
         }

            $inventario -> setInvCantidad($cantidad);
            $inventario -> setInvPrecio($precio);
            $inventario -> setInvEmpaque($empaque);
            $inventario -> setInvObservacion($observacion);
            $inventario -> setPartePar($parte);
            $inventario -> setProveedorProv($proveedor);
        $em->flush();

        $data = array(
            'message' => 'El inventario ha sido actualizado',
             'inventario' => $inventarioid,
             'empaque' => $empaque,
             'observacion' => $observacion
         );

         return $request;

     }
}