<?php

namespace AppBundle\Controller;

use FOS\RestBundle\Controller\Annotations\QueryParam;
use Symfony\Component\HttpKernel\Exception\HttpException;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\Tools\Pagination\Paginator;
use AppBundle\Entity\Conjunto;

class ConjuntosController extends FOSRestController
{

     /**
     * @Rest\Post("/conjunto/add/{id}")
     */
    public function postAddConjuntoAction(Request $request)
    {
        $params = $request->request->all();
        try{
            $parteKitId = $request->get('id');  // obtengo id de kit   
            $parteKit   = $this->getDoctrine()->getRepository('AppBundle:Parte')->find($parteKitId);            
             
            foreach ($params as $key => $value) { // para cada parte del kit

            $parte = $this->getDoctrine()->getRepository('AppBundle:Parte')->find($value); // obtengo parte  y guardo
            if($parte == ""){
                throw new HttpException (400,"La parte especificada no existe");
                exit ; 
            }

            $conjunto = new Conjunto();
            $conjunto -> setParteKitId($parteKitId);
            $conjunto -> setPartePar($parte);
            $em = $this->getDoctrine()->getManager();

            $em->persist($conjunto);
            $em->flush();           

            }

            $response = array("Conjunto" => array(
                array(
                    'message'   => 'Nuevo conjunto creado',
                    "conjunto"  => $parteKit->getParCodigo(),
                    "ID"        => $parteKitId
                    )
                )  
            ); 
            return $response;
        }

        catch (\Doctrine\DBAL\Exception\UniqueConstraintViolationException $e){
            throw new HttpException (409,"Error: La parte para el conjunto ya esxiste."); 
            } 
        catch (Exception $e) {
            return $e->getMessage();
            } 

 }


    /**
     * @Rest\Get("/conjunto")
     */
    public function getAllConjuntoAction()
    {
        $repository = $this->getDoctrine()->getRepository('AppBundle:Conjunto');
        $query      = $repository->createQueryBuilder('c')->getQuery();
        $conjunto   = $query->getResult();
        return $conjunto;
    }


    /**
     * @Rest\Get("/conjunto/{conjuntoid}")
     */
    public function getConjuntoAction(Request $request)
    {
        $id = $request->get('conjuntoid');
        $repository = $this->getDoctrine()->getRepository('AppBundle:Conjunto');
        $conjunto = $repository->findOneById($id);
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
        $repository = $this->getDoctrine()->getRepository('AppBundle:Conjunto');
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
        $repository = $this->getDoctrine()->getRepository('AppBundle:Conjunto');
        // The dsql syntax query
        $query = $repository->createQueryBuilder('conjunto')->join('conjunto.parte','p1')->join('conjunto.partekit','p2')
            ->where('conjunto.kitRef LIKE :searchtext')
            ->orwhere('conjunto.kitCount LIKE :searchtext')
            ->orwhere('p1.parNombre LIKE :searchtext')
            ->orwhere('p2.parNombre LIKE :searchtext')
            ->setParameter('searchtext',"%" .$searchtext ."%")
            ->getQuery()
            ->setFirstResult($limit * ($page - 1))
            ->setMaxResults($limit);
        // Build the paginator
        $paginator = new Paginator($query, $fetchJoinCollection = true);
        // Construct the response
        $response = array(
            'conjuntos'                     => $paginator->getIterator(),
            'totalConjuntosReturned'     => $paginator->getIterator()->count(),
            'totalConjuntos'   => $paginator->count(),
            'searchedText:'                 => $searchtext
        );
        // Send the response
        return $response;
    }


    /**
     * @Rest\Post("/conjunto/edit/{conjuntoid}")
     */
     public function postUpdateConjuntoAction(Request $request)
     {
            $conjuntoid     = $request->get('conjuntoid');
            $referencia     = $request->get('referencia');
            $cantidad       = $request->get('cantidad');
            $parteid        = $request->get('parteid');
            $partekitid     = $request->get('partekitid');
            $parte          = $this->getDoctrine()->getRepository('AppBundle:Parte')->find($parteid);
            $partekit       = $this->getDoctrine()->getRepository('AppBundle:Parte')->find($partekitid);


         if($conjuntoid == "" || !$conjuntoid){
             throw new HttpException (400,"Debe proveer un id para modificar el registro.");  
         }

         if($referencia == ""){
             throw new HttpException (400,"El campo referencia no puede estar vacío");   
         }

         if($parteid == ""){
             throw new HttpException (400,"El campo parte no puede estar vacío");   
         }

         if($partekitid == ""){
             throw new HttpException (400,"El campo partekit no puede estar vacío");   
         }
         
         $em = $this->getDoctrine()->getManager();
         $conjunto = $em->getRepository('AppBundle:conjunto')->find($conjuntoid);


         if (!$conjuntoid) {
             throw new HttpException (400,"No se ha encontrado el conjunto especificado: " .$conjuntoid);
         }

        $conjunto -> setKitRef($referencia);
        $conjunto -> setKitCount($cantidad);
        $conjunto -> setParte($parte);
        $conjunto -> setPartekit($partekit);
        $em->flush();

        $response = array(
            'message'       => 'El conjunto '.$referencia.' ha sido actualizado',
            'conjuntoid'    => $conjuntoid,
            'parte'         => $parte->getParNombre(),
            'partekit'      => $partekit->getParNombre(),
         );
         return $response;
     }

     /**
     * @Rest\Delete("/conjunto/delete/{conjuntoid}")
     */
      public function deleteRemoveConjuntoAction(Request $request)
    {
        $conjuntoid  = $request->get('conjuntoid');
        // get EntityManager
        $em  = $this->getDoctrine()->getManager();
        $conjuntotoremove = $em->getRepository('AppBundle:Conjunto')->find($conjuntoid);

        if ($conjuntotoremove != "")
        {      
            // Remove it and flush
            $em->remove($conjuntotoremove);
            $em->flush();
            $response = array(
                'message'   => 'El Conjunto '.$conjuntotoremove->getKitRef().' ha sido eliminado',
                'ID'        => $conjuntoid
            );
             return $response;
        } else
        {
            throw new HttpException (400,"No se ha encontrado el conjunto especificado: " .$conjuntoid);
        }     
    }

}   