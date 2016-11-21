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



        $data['data'] = array(
            'roles' => $event->getUser()->getRoles()
        );

        $event->setData($data);
    }
}
