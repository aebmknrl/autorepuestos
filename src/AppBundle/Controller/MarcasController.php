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
            // Obtaining vars from request        
            $nombre         = $request->get('nombre');
            $observacion    = $request->get('observacion');
            $marImagen      = $request->get('marImagen');
            // Check for mandatory fields          
            if($nombre == ""){
                throw new HttpException (400,"El campo nombre no puede estar vacío");   
            }

            // Create the Marca
            $marca          = new Marca();
            $marca          -> setMarNombre($nombre);
            $marca          -> setMarObservacion($observacion);
            $marca          -> setMarImagen($marImagen);
            $em = $this->getDoctrine()->getManager();

            // tells Doctrine you want to (eventually) save (no queries yet)
            $em->persist($marca);          
            // actually executes the queries (i.e. the INSERT query)
            $em->flush();
        
            $response = array("marca" => array(
                    array(
                    "marca"   => $nombre,
                    "observación" => $observacion,
                    "id" => $marca->getMarId()
                    )
                )  
            ); 
            return $response;
        }
        catch (\Doctrine\DBAL\Exception\UniqueConstraintViolationException $e){
            throw new HttpException (409,"Error: El nombre de la marca ya esxiste."); 
            } 
        catch (Exception $e) {
            return $e->getMessage();
            } 
    }


    /**
     * @Rest\Get("/marca")
     */  
    public function getAllMarcaAction()
    {
        // Initialize the 'Marca' data repository
        $repository     = $this->getDoctrine()->getRepository('AppBundle:Marca');
        $query          = $repository->createQueryBuilder('m')->getQuery();
        $marcas         = $query->getResult();
        return $marcas;
    }


    /**
     * @Rest\Get("/marca/{marcaid}")
     */
    public function getMarcaAction(Request $request)
    {
        $marcaid        = $request->get('marcaid');
        $repository     = $this->getDoctrine()->getRepository('AppBundle:Marca');
        $marcas         = $repository->findOneBymarId($marcaid);
        return $marcas;
    }
    

     /**
     * @Rest\Get("/marca/{limit}/{page}")
     */
    public function getAllMarcaPaginatedAction(Request $request)
    {
        // Set up the limit and page vars from request
        $limit      = $request->get('limit');
        $page       = $request->get('page');
        // Check if the params are numbers before continue
        if(!is_numeric($page))
        {
            throw new HttpException (400,"Por favor use solo números para la página");  
        }
        // Check if the limit asked has all or not.
        if (!is_numeric($limit)) 
        {
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
        $repository     = $this->getDoctrine()->getRepository('AppBundle:Marca');
        // The dsql syntax query
        $query = $repository->createQueryBuilder('m')->getQuery()->setFirstResult($limit * ($page - 1))->setMaxResults($limit);
        // Build the paginator
        $paginator = new Paginator($query, $fetchJoinCollection = true);
        // Construct the response
        $response = array(
            'marcas' => $paginator->getIterator(),
            'totalMarcasReturned' => $paginator->getIterator()->count(),
            'totalMarcas' => $paginator->count()
        );
        // Send the response
        return $response;
    }

    
    /**
     * @Rest\Get("/marca/{limit}/{page}/{searchtext}")
     */
    public function getAllMarcaPaginatedSearchAction(Request $request)
    {
        // Set up the limit and page vars from request
        $limit      = $request->get('limit');
        $page       = $request->get('page');
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
        $repository     = $this->getDoctrine()->getRepository('AppBundle:Marca');
        $query          = $repository->createQueryBuilder('marca')
            ->where('marca.marObservacion LIKE :searchtext')
            ->orWhere('marca.marNombre LIKE :searchtext')
            ->orWhere('marca.marId LIKE :searchtext')
            ->setParameter('searchtext',"%" .$searchtext ."%")
            ->getQuery()
            ->setFirstResult($limit * ($page - 1))
            ->setMaxResults($limit);
        // Build the paginator
        $paginator = new Paginator($query, $fetchJoinCollection = true);
        // Construct the response
        $response = array(
            'marcas' => $paginator->getIterator(),
            'totalMarcasReturned' => $paginator->getIterator()->count(),
            'totalMarcas' => $paginator->count(),
            'searchedText' => $searchtext
        );
        // Send the response
        return $response;
    }


    /**
     * @Rest\Post("/marca/edit/{marcaid}")
     */
     public function postUpdateMarcaAction(Request $request)
     {
        try
        {
        // Obtaining vars from request
         $marcaid       = $request->get('marcaid');
         $nombre        = $request->get('nombre');
         $observacion   = $request->get('observacion');
         $marImagen     = $request->get('marImagen');

        // Check for mandatory fields 
        if($marcaid == "" || !$marcaid)
        {
                throw new HttpException (400,"Debe proveer un id para modificar el registro.");  
        }
        if($nombre == "")
        {
                throw new HttpException (400,"El campo nombre no puede estar vacío");   
        }


        $em = $this->getDoctrine()->getManager();
        $marca = $em->getRepository('AppBundle:Marca')->find($marcaid);

        if (!$marca) {
                throw new HttpException (400,"No se ha encontrado la marca especificada: " .$marcaid);
         }

        // Create the Marca
        $marca->setMarNombre($nombre);
        $marca->setMarObservacion($observacion);
        $marca-> setMarImagen($marImagen);
        $em->flush();

        $response = array(
             'message'      => 'La marca ha sido actualizada',
             'marcaid'      => $marcaid,
             'nombre'       => $nombre,
             'observacion'  => $observacion
         );
        return $response;
        }
        catch (\Doctrine\DBAL\Exception\UniqueConstraintViolationException $e){
            throw new HttpException (409,"Error: El nombre de la marca ya esxiste."); 
            } 
        catch (Exception $e) {
            return $e->getMessage();
            } 
     }


    /**
     * @Rest\Delete("/marca/delete/{marcaid}")
     */
    public function deleteRemoveMarcaAction(Request $request)
    {
        $marcaid = $request->get('marcaid');
        // get EntityManager
        $em             = $this->getDoctrine()->getManager();
        $marcatoremove  = $em->getRepository('AppBundle:Marca')->find($marcaid);

        if ($marcatoremove != "") 
        {      
            $em->remove($marcatoremove);
            $em->flush();
            $response = array(
                'message'   => 'La marca '.$marcatoremove->getMarNombre().' ha sido eliminada',
                'nombre'    => $marcatoremove->getMarNombre(),
                'id'        => $marcaid
            );
        return $response;
        } else
            {
            throw new HttpException (400,"No se ha encontrado la marca especificada ID: ".$marcaid);
            }
     }

     /**
     * @Rest\Post("/marca/uploadImage/{marcaid}")
     */
    public function postUploadImageAction(Request $request)
    {   
        // Obtain marcaid
        $marcaid = $request->get('marcaid');

        // Check for mandatory fields 
        if($marcaid == "" || !$marcaid)
        {
            throw new HttpException (400,"Debe proveer un id para subir el archivo.");  
        }

        // Obtain default image upload parameter
        $ruta = $this->container->getParameter('img_upload_route');
        $direcorioUploads = $this->container->getParameter('img_upload_folder');
        // Construct the folder route for this Entity
        $directory = __DIR__ .$ruta .$direcorioUploads ."/marcas/";

        // Check if the file exist previously
        foreach (glob($directory .$marcaid ."*") as $nombre_fichero) {
            //if exists, delete the file 
            unlink($nombre_fichero);
        }

        // Count the file qty that is receive
        if(count($request->files) <= 0)
        {
            // If don't have any file, then error
            throw new HttpException (400,"Debe adjuntar un archivo");   
        }

        // Get the DB entity
        $em = $this->getDoctrine()->getManager();
        $marca = $em->getRepository('AppBundle:Marca')->find($marcaid);

        // If the entity value given does not exists, error
        if (!$marca) {
            throw new HttpException (400,"No se ha encontrado la marca especificada: " .$marcaid);
        }

        // Process every file
        foreach($request->files as $uploadedFile) {
             // Get the file from request
            $uploadedfile = $request->files->get('file');
        
            // Obtain the original name of the file uploaded
            $originalFileName = $uploadedFile->getClientOriginalName();
            // Obtain the original extension of the file uploaded. Apply strtolower to remove 
            // the caps of the extension to check
            $originalFileExt =  strtolower($uploadedFile->getClientOriginalExtension());
            // Check if the file extension has a permited extension
            if($originalFileExt != 'jpg' && $originalFileExt != 'jepg' && $originalFileExt != 'png' && $originalFileExt != 'gif'){
                // Is not a permited image file, error
                throw new HttpException (400,"Debe subir un archivo de imagen válido. (Extenciones permitidas: jpg, gif, png). Archivo subido: " .$originalFileExt);
            }
            // Rename the file with the id of the entity and save on the folder
            $file = $uploadedFile->move($directory, $marcaid ."." .$originalFileExt);
            
            // If any error uploading... throw error
           if ($uploadedfile->getError() != 0) {
                throw new HttpException (400,"Ha ocurrido un error subiendo los archivos: " .$uploadedfile->getError());
            }
            // Update the image of the entity
            $marca-> setMarImagen($marcaid ."." .$originalFileExt);
            $em->flush();  
        }
        // Finish
        return "Archivos cargados";
    }

     /**
     * @Rest\Post("/marca/getImageUrl/{marcaid}")
     */
    public function postGetImageUrlAction(Request $request)
    {   
        // Obtain the entity to get the URL
        $marcaid = $request->get('marcaid');
        // If no id of entity provided, error
        if($marcaid == "" || !$marcaid)
        {
            throw new HttpException (400,"Debe proveer un id para obtener las imagenes.");  
        }
        // Connect with the autoparts db repository
        // Get the entity by id and select the image name
        $repository     = $this->getDoctrine()->getRepository('AppBundle:Marca');
        $query          = $repository->createQueryBuilder('marca')
            ->select('marca.marImagen')
            ->where('marca.marId = :marcaid')
            ->setParameter('marcaid',$marcaid)
            ->getQuery();
        $image = $query->getResult();
        // Get de default image folder parameter
        $direcorioUploads = $this->container->getParameter('img_upload_folder');
        // Generate the URL
        $imageUrl = $request->getScheme() . '://' . $request->getHttpHost() . $request->getBasePath() .'/' .$direcorioUploads .'/marcas/'.$image[0]["marImagen"];
        // Return the URL
        return $imageUrl;
    }

}   