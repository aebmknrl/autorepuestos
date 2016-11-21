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

        $payload       = $event->getData();
        $payload['ip'] = $request->getClientIp();
        $payload['roles'] = $event->getUser()->getRoles();

        $event->setData($payload);
    }
}