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
use AppBundle\Entity\Role;


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

	/**
     * @Rest\Post("/addrole")
     */
     public function addRoleAction(Request $request)
    {
        $userid = $request->get('userid');
        $assignedRole = $request->get('role');
        $assignedRoleName = $request->get('role_name');
        
        // Obtain the User
        $em = $this->getDoctrine()->getManager();
         $user = $em->getRepository('AppBundle:User')
            ->find($userid);
      
      // If the user gives not exists, throw error
        if (!$user) {
            throw new HttpException (400,"No se ha encontrado el usuario solicitado: " .$userid);
         }

         // obtain present user roles
        $presentRoles = $user->getRoles();
        $role_length = count($presentRoles); 
        $role_list = array();
        for ($i=0; $i <$role_length ; $i++) { 
        array_push($role_list,$presentRoles[$i]->getRole());
        }


/* 
VERY BAD CODE:
         $role = new Role();
         $role->setRole($assignedRole);
         $role->setName($assignedRoleName);

THE GOOD CODE:
        $user->addRole($role);
        $em->persist($user);
        $em->flush();

*/

// If the user don't have the role, persist data on DB'

        if(!in_array($assignedRole,$role_list)){
        $role = $em->getRepository('AppBundle:Role')
                ->findOneBy(array('name' => $assignedRoleName));
                $user->addRole($role);
                $em->persist($user); // persisting only the user. 
                $em->flush();
                
                $data = array(
                    'result' => 'Rol asignado',
                    'user' => $user,
                    'assignedRole' => $assignedRole
                );
            return $data;
        } else {
            throw new HttpException (400,"El usuario ya posee el rol solicitado");
        }
        
    }


	/**
     * @Rest\Post("/updateroles")
     */
     public function updateRolesAction(Request $request)
    {

    }

}
