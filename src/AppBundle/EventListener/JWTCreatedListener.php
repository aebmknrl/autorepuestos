<?php
namespace AppBundle\EventListener;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;
use Symfony\Component\HttpFoundation\RequestStack;
/**
 * JWTCreatedListener
 *
 * @author Nicolas Cabot <n.cabot@lexik.fr>
 */
class JWTCreatedListener
{
    /**
    * @var RequestStack
    */
    private $requestStack;

    /**
    * @param RequestStack $requestStack
    */
    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    /**
    * @param JWTCreatedEvent $event
    *
    * @return void
    */
    public function onJWTCreated(JWTCreatedEvent $event)
    {
        $request = $this->requestStack->getCurrentRequest();
        
        // Work of roles
        $roles = $event->getUser()->getRoles();
        $role_length = count($roles); 
        $role_list = array();
        for ($i=0; $i <$role_length ; $i++) { 
        array_push($role_list,$roles[$i]->getRole());
        }


        $payload       = $event->getData();
        $payload['ip'] = $request->getClientIp();
        $payload['roles'] = $role_list;

        $event->setData($payload);
    }
}