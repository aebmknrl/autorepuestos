<?php

namespace AppBundle\Controller;

use FOS\RestBundle\Controller\Annotations\QueryParam;
use Symfony\Component\HttpKernel\Exception\HttpException;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\Tools\Pagination\Paginator;
use AppBundle\Entity\Proveedor;

class ProveedoresController extends FOSRestController
{

     /**
     * @Rest\Post("/proveedor/add")
     */
    public function postAddProveedorAction(Request $request)
    {
        try {
            
            $nombre = $request->get('nombre');
            $direccion = $request->get('direccion');
            $rif = $request->get('rif');
            $estatus = $request->get('estatus');
            $observacion = $request->get('observacion');
            
            if($nombre == ""){
                throw new HttpException (400,"El campo nombre no puede estar vacío");   
            }

            if($rif == ""){
                throw new HttpException (400,"El campo rif no puede estar vacío");   
            }
           
            if(($estatus == "") || ($estatus != "ACTIVO") && ($estatus != "INACTIVO")){
                throw new HttpException (400,"El campo estatus no puede estar vacío o debe contener un valor válido");   
            }

            $proveedor = new Proveedor();
            $proveedor -> setProvNombre($nombre);
            $proveedor -> setProvDireccion($direccion);
            $proveedor -> setProvRif($rif);
            $proveedor -> setProvStatus($estatus);
            $proveedor -> setProvObservacion($observacion);
            $em = $this->getDoctrine()->getManager();
            
            // tells Doctrine you want to (eventually) save the Proveedor (no queries yet)
            $em->persist($proveedor);
            
            // actually executes the queries (i.e. the INSERT query)
            $em->flush();
            
            $data = array("proveedor" => array(
                array(
                    "proveedor"   => $nombre,
                    "id" => $proveedor->getProvId()
                    )
                )  
            ); 
            return $data;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * @Rest\Get("/proveedor/{proveedorid}")
     */
    public function getProveedorAction(Request $request)
    {
        $proveedorid = $request->get('proveedorid');

        $repository = $this->getDoctrine()->getRepository('AppBundle:Proveedor');
        $proveedores = $repository->findOneByprovId($proveedorid);
        return $proveedores;
    }
    
    /**
     * @Rest\Get("/proveedor")
     */
    public function getAllProveedorAction()
    {
        // Initialize the 'Proveedor' data repository
        $repository = $this->getDoctrine()->getRepository('AppBundle:Proveedor');
        $query = $repository->createQueryBuilder('p')
            ->getQuery();
        $proveedores = $query->getResult();
        return $proveedores;
    }

     /**
     * @Rest\Get("/proveedor/{limit}/{page}")
     */
    public function getAllProveedorPaginatedAction(Request $request)
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
        $repository = $this->getDoctrine()->getRepository('AppBundle:Proveedor');
        // The dsql syntax query
        $query = $repository->createQueryBuilder('Proveedor')
            ->getQuery()
            ->setFirstResult($limit * ($page - 1))
            ->setMaxResults($limit);
        // Build the paginator
        $paginator = new Paginator($query, $fetchJoinCollection = true);
        // Construct the response
        $response = array(
            'proveedores' => $paginator->getIterator(),
            'totalProveedoresReturned' => $paginator->getIterator()->count(),
            'totalProveedores' => $paginator->count()
        );
        // Send the response
        return $response;
    }
    /**
     * @Rest\Get("/proveedor/{limit}/{page}/{searchtext}")
     */
    public function getAllProveedorPaginatedSearchAction(Request $request)
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
        $repository = $this->getDoctrine()->getRepository('AppBundle:Proveedor');
        // The dsql syntax query
        $query = $repository->createQueryBuilder('Proveedor')
            ->where('Proveedor.provObservacion LIKE :searchtext')
            ->orWhere('Proveedor.provNombre LIKE :searchtext')
            ->orWhere('Proveedor.provRif LIKE :searchtext')
            ->orWhere('Proveedor.provDireccion LIKE :searchtext')
            ->orWhere('Proveedor.provStatus LIKE :searchtext')
            ->orWhere('Proveedor.provId LIKE :searchtext')
            ->setParameter('searchtext',"%" .$searchtext ."%")
            ->getQuery()
            ->setFirstResult($limit * ($page - 1))
            ->setMaxResults($limit);
        // Build the paginator
        $paginator = new Paginator($query, $fetchJoinCollection = true);
        // Construct the response
        $response = array(
            'proveedores' => $paginator->getIterator(),
            'totalProveedoresReturned' => $paginator->getIterator()->count(),
            'totalProveedores' => $paginator->count(),
            'searchedText' => $searchtext
        );
        // Send the response
        return $response;
    }


    /**
     * @Rest\Post("/proveedor/edit/{provid}")
     */
     public function postUpdateProveedorAction(Request $request)
     {
         $provid = $request->get('provid');
         $nombre = $request->get('nombre');
         $direccion = $request->get('direccion');
         $rif = $request->get('rif');
         $estatus = $request->get('estatus');
         $observacion = $request->get('observacion');

         if($provid == "" || !$provid){
             throw new HttpException (400,"Debe proveer un id para modificar el registro.");  
         }


            if($nombre == ""){
                throw new HttpException (400,"El campo nombre no puede estar vacío");   
            }

            if($rif == ""){
                throw new HttpException (400,"El campo rif no puede estar vacío");   
            }
           
            if(($estatus == "") || ($estatus != "ACTIVO") && ($estatus != "INACTIVO")){
                throw new HttpException (400,"El campo estatus no puede estar vacío o debe contener un valor válido");   
            }

         $em = $this->getDoctrine()->getManager();
         $proveedor = $em->getRepository('AppBundle:Proveedor')
            ->find($provid);


        if (!$proveedor) {
        throw new HttpException (400,"No se ha encontrado el Proveedor especificado: " .$provid);
         }

        $proveedor->setProvNombre($nombre);
        $proveedor->setProvObservacion($observacion);
        $proveedor -> setProvDireccion($direccion);
        $proveedor -> setProvRif($rif);
        $proveedor -> setProvStatus($estatus);

        $em->flush();

        $data = array(
            'message' => 'El Proveedor ha sido actualizado',
             'proveedorid' => $provid,
             'nombre' => $nombre,
             'observacion' => $observacion
         );

         return $data;

     }

    /**
     * @Rest\Delete("/proveedor/delete/{provid}")
     */
    public function deleteRemoveProveedorAction(Request $request)
    {
        $provid = $request->get('provid');

        // get EntityManager
        $em = $this->getDoctrine()->getManager();
        $proveedortoremove = $em->getRepository('AppBundle:Proveedor')->find($provid);

        if ($proveedortoremove != "") {      
            // Remove it and flush
            $em->remove($proveedortoremove);
            $em->flush();
            $data = array(
                'message' => 'El proveedor ha sido eliminada',
                'provid' => $provid
            );
             return $data;
        } else{
            throw new HttpException (400,"No se ha encontrado el proveedor especificado: " .$provid);
        }
        
    }
}   