<?php

namespace AppBundle\EventListener;

use AppBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use FOS\UserBundle\Event\FilterUserResponseEvent;
use FOS\UserBundle\FOSUserEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class RegistrationConfirmationListener implements EventSubscriberInterface
{
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * {@inheritDoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            FOSUserEvents::REGISTRATION_CONFIRMED => 'onRegistrationConfirmed',
        );
    }

    public function onRegistrationConfirmed(FilterUserResponseEvent $event)
    {
        /** @var User $user */
        $user = $event->getUser();
        $children = $this->em->getRepository('AppBundle:Child')->findByParentEmail($user->getEmail());
        if (!empty($children)) {
            foreach ($children as $child) {
                $user->addChild($child);
            }
            $this->em->persist($user);
            $this->em->flush();
        }
    }
}
