<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\User;
use Symfony\Component\HttpFoundation\JsonResponse;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {

       $repository = $this->getDoctrine()->getRepository('AppBundle:User');
        $user = $repository->findOneByusername('abriceno');


        $username = $user->getUsername();
        $roles = $user->getRoles();

        $response = new Response(
            '<pre>' .print_r($roles[0]->getName()) .'</pre>',
            Response::HTTP_OK,
            array('content-type' => 'text/html')
        );

        $arr = array(
            'username' => $user->getUsername(),
            'password' => $user->getPassword(),
            'roles' => $user->getRoles(),
            'dataX' => var_dump($roles)
        );
        return $response;
      // return new JsonResponse($arr);



        // replace this example code with whatever you need
        //return $this->render('default/index.html.twig', [
        //    'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
        //]);
    }
    /**
     * @Route("/register", name="register")
     * @Method({"POST"})
     */
    public function registerAction(Request $request)
    {
        $em = $this->get('doctrine')->getManager();
        $encoder = $this->container->get('security.password_encoder');

        $username = $request->request->get('_username');
        $password = $request->request->get('_password');

        $user = new User($username);
        $user->setPassword($encoder->encodePassword($user, $password));

        $em->persist($user);
        $em->flush($user);

        return new Response(sprintf('User %s successfully created', $user->getUsername()));
    }
}
