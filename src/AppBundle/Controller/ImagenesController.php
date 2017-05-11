<?php

namespace AppBundle\Controller;

use FOS\RestBundle\Controller\Annotations\QueryParam;
use Symfony\Component\HttpKernel\Exception\HttpException;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\Tools\Pagination\Paginator;
use AppBundle\Entity\Imagen;

class ImagenesController extends FOSRestController
{

     /**
     * @Rest\Post("/imagen/add")
     */
    public function postAddImagenAction(Request $request)
    {
        try {       
            // Obtaining vars from request
            $ubicacion         = $request->get('ubicacion');
            $parteid           = $request->get('parteid');
            $file              = $request->get('file');

            // Check for mandatory fields
            if($ubicacion == ""){
                throw new HttpException (400,"El campo ubicación no puede estar vacío");
            }

            if($parteid == ""){
                throw new HttpException (400,"El campo parte no puede estar vacío");
            }
            
            if($file == ""){
                throw new HttpException (400,"El campo archivo no puede estar vacío");
            }

            // Find the relationship with Parte
            $parte = $this->getDoctrine()->getRepository('AppBundle:Parte')->find($parteid);        
            if($parte == ""){
                throw new HttpException (400,"La parte especificada no existe");   
            }

            // Create the "Imagen"
            $imagen     = new Imagen();
            $imagen     -> setImgUbicacion($ubicacion);
            $imagen     -> setPartePar($parte);
            $imagen     -> setImgFile($file);
            $em         = $this->getDoctrine()->getManager();
            
            // tells Doctrine you want to (eventually) save (no queries yet)
            $em->persist($imagen);         
            // actually executes the queries (i.e. the INSERT query)
            $em->flush();
            
            $response = array("imagen" => array(
                array(
                    "Nueva imagen creada"   => $file,
                    "Ubicación: "           => $ubicacion,
                    "Parte:"                => $parte->getParNombre(),
                    "id"                    => $imagen->getImgId()
                    )
                )  
            ); 
            return $response;
        }
        catch (\Doctrine\DBAL\Exception\UniqueConstraintViolationException $e){
            throw new HttpException (409,"Error: El nombre de la imagen ya esxiste."); 
            } 
        catch (Exception $e) {
            return $e->getMessage();
            } 
    }

    
    /**
     * @Rest\Get("/imagen")
     */
    public function getAllImagenAction()
    {
        // Initialize the data repository
        $repository = $this->getDoctrine()->getRepository('AppBundle:Imagen');
        $query      = $repository->createQueryBuilder('i')->getQuery();
        $imagenes    = $query->getResult();
        return $imagenes;
    }


    /**
     * @Rest\Get("/imagen/{imagenid}")
     */
    public function getImagenAction(Request $request)
    {
        $imagenid       = $request->get('imagenid');
        $repository     = $this->getDoctrine()->getRepository('AppBundle:Imagen');
        $imagenes        = $repository->findOneByimgId($imagenid);
        return $imagenes;
    }


     /**
     * @Rest\Get("/imagen/{limit}/{page}")
     */
    public function getAllImagenPaginatedAction(Request $request)
    {
        // Set up the limit and page vars from request
        $limit  = $request->get('limit');
        $page   = $request->get('page');

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
        $repository     = $this->getDoctrine()->getRepository('AppBundle:Imagen');
        // The dsql syntax query
        $query          = $repository->createQueryBuilder('i')->getQuery()->setFirstResult($limit * ($page - 1))->setMaxResults($limit);
        // Build the paginator
        $paginator      = new Paginator($query, $fetchJoinCollection = true);
        // Construct the response
        $response = array(
            'imagenes' => $paginator->getIterator(),
            'totalImagenesReturned' => $paginator->getIterator()->count(),
            'totalImagenes' => $paginator->count()
        );
        // Send the response
        return $response;
    }


    /**
     * @Rest\Get("/imagen/{limit}/{page}/{searchtext}")
     */
    public function getAllImagenPaginatedSearchAction(Request $request)
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
        $repository = $this->getDoctrine()->getRepository('AppBundle:Imagen');
    
        // The dsql syntax query
        $query = $repository->createQueryBuilder('imagen')->join('imagen.partePar','p')
            ->where('p.parNombre LIKE :searchtext')
            ->orwhere('imagen.imgUbicacion LIKE :searchtext')
            ->orWhere('imagen.imgFile LIKE :searchtext')
            ->setParameter('searchtext',"%" .$searchtext ."%")
            ->getQuery()
            ->setFirstResult($limit * ($page - 1))
            ->setMaxResults($limit);
        // Build the paginator
        $paginator = new Paginator($query, $fetchJoinCollection = true);
        // Construct the response
        $response = array(
            'imagenes' => $paginator->getIterator(),
            'totalImagenesReturned' => $paginator->getIterator()->count(),
            'totalImagenes' => $paginator->count(),
            'searchedText' => $searchtext
        );
        // Send the response
        return $response;
    }


    /**
     * @Rest\Post("/imagen/edit/{imagenid}")
     */
     public function postUpdateImagenAction(Request $request)
     {
        try
        {
        
        $imagenid          = $request->get('imagenid');
        $ubicacion         = $request->get('ubicacion');
        $parteid           = $request->get('parteid');
        $file              = $request->get('file');
        $parte             = $this->getDoctrine()->getRepository('AppBundle:Parte')->find($parteid);

        if($imagenid == "" || !$imagenid)
        {
             throw new HttpException (400,"Debe proveer un id para modificar el registro.");  
        }

        if($ubicacion == "")
        {
                throw new HttpException (400,"El campo ubicación no puede estar vacío");   
        }
        if($file == "")
        {
               throw new HttpException (400,"El campo archivo no puede estar vacío");   
        }

        if(!$parteid)
        {
               throw new HttpException (400,"Debe especificar un ID de parte válido");   
        }
         
        $em = $this->getDoctrine()->getManager();
        $imagen = $em->getRepository('AppBundle:Imagen')->find($imagenid);

        if (!$imagen) 
        {
            throw new HttpException (400,"No se ha encontrado la imagen especificada: " .$imagenid);
        }

        $imagen     -> setImgUbicacion($ubicacion);
        $imagen     -> setPartePar($parte);
        $imagen     -> setImgFile($file);
        $em->flush();

        $response = array(
            'message'      => 'La imagen '.$imagen->getImgFile().' ha sido actualizada',
            'ID'           => $imagenid,
            'ubicación'    => $ubicacion,
            'parte'        => $parte->getParNombre()
         ); 
         return $response;
        }
        catch (\Doctrine\DBAL\Exception\UniqueConstraintViolationException $e){
            throw new HttpException (409,"Error: El nombre de la imagen ya esxiste."); 
            } 
        catch (Exception $e) {
            return $e->getMessage();
            } 
     }


    /**
     * @Rest\Delete("/imagen/delete/{imagenid}")
     */
    public function deleteRemoveImagenAction(Request $request)
    {
        $imagenid = $request->get('imagenid');
        // get EntityManager
        $em             = $this->getDoctrine()->getManager();
        $imagentoremove = $em->getRepository('AppBundle:Imagen')->find($imagenid);

        if ($imagentoremove != "") {      
            // Remove it and flush
            $em->remove($imagentoremove);
            $em->flush();
            $response = array(
                'message'   => 'La imagen '.$imagentoremove->getImgFile().' ha sido eliminada',
                'imagen Id'  => $imagenid
            );
             return $response;
        } else{
            throw new HttpException (400,"No se ha encontrado la imagen especificada, ID: " .$imagenid);
        }
        
    }

} 