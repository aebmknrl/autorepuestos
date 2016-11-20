<?php

namespace AppBundle\Controller;

use FOS\RestBundle\Controller\Annotations\QueryParam;
use Symfony\Component\HttpKernel\Exception\HttpException;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\Tools\Pagination\Paginator;
use AppBundle\Entity\User;


class UsersController extends FOSRestController
{
	/**
     * @Rest\Post("/register")
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

        return sprintf('User %s successfully created', $user->getUsername());
    }
}
