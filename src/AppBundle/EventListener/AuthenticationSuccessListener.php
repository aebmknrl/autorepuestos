<?php

namespace AppBundle\EventListener;
// src/AppBundle/EventListener/AuthenticationSuccessListener.php

use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;



class AuthenticationSuccessListener {
    /**
    * @param AuthenticationSuccessEvent $event
    */
    public function onAuthenticationSuccessResponse(AuthenticationSuccessEvent $event)
    {
        $data = $event->getData();
        $user = $event->getUser();
        $roles =  $event->getUser()->getRoles();

        $role_length = count($roles); 

        $role_list = array();

        for ($i=0; $i <$role_length ; $i++) { 
        array_push($role_list,$roles[$i]->getRole());
        }

            $data['data'] = array(
                'roles' => $role_list
            );

        $event->setData($data);
    }
}
