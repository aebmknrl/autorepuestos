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
     * @Rest\Post("/user/register")
     */
    public function registerAction(Request $request)
    {
        $em = $this->get('doctrine')->getManager();
        $encoder = $this->container->get('security.password_encoder');

        $username = $request->request->get('_username');
        $password = $request->request->get('_password');

        $user = new User($username);
        $user->setPassword($encoder->encodePassword($user, $password));
        // Set default role
        $role = $em->getRepository('AppBundle:Role')
                ->findOneBy(array('name' => 'client'));
        $user->addRole($role);
        $em->persist($user);
        $em->flush($user);

        return sprintf('User %s successfully created', $user->getUsername());
    }

	/**
     * @Rest\Post("/user/addrole")
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
    // If the user don't have the role, persist data on DB'

        if(!in_array($assignedRole,$role_list)){
            $role = $em->getRepository('AppBundle:Role')
                ->findOneBy(array('role' => $assignedRole));
            // If the role gives not exists, throw error
            if (!$role) {
                throw new HttpException (400,"No se ha encontrado el rol solicitado: " .$assignedRole);
            }
            // If exists the role, save
            $user->addRole($role);
            $em->persist($user); // persisting only the user. 
            $em->flush();     
                $data = array(
                    'result' => 'Rol asignado',
                    'user' => $userid,
                    'assignedRole' => $assignedRoleName
                );
            return $data;
        } else {
            throw new HttpException (400,"El usuario ya posee el rol solicitado");
        }
        
    }


	/**
     * @Rest\Post("/user/removerole")
     */
     public function removeRoleAction(Request $request)
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

        if(in_array($assignedRole,$role_list)){
            $role = $em->getRepository('AppBundle:Role')
                ->findOneBy(array('role' => $assignedRole));
            // If the role gives not exists, throw error
            if (!$role) {
                throw new HttpException (400,"No se ha encontrado el rol solicitado: " .$assignedRole);
            }
            if ($user->getUsername() == 'admin' && $assignedRole == 'ROLE_ADMIN' ) {
               throw new HttpException (400,"No se puede eliminar el rol solicitado al Administrador del sistema (" .$assignedRole .")");
            }

            // If exists the role, save
            $user->removeRole($role);
            $em->persist($user); // persisting only the user. 
            $em->flush();     
                $data = array(
                    'result' => 'Rol eliminado',
                    'user' => $userid,
                    'Role' => $assignedRoleName
                );
            return $data;
        } else {
            throw new HttpException (400,"El usuario no posee el rol solicitado");
        }




    }

}
