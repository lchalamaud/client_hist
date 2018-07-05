<?php

namespace AppBundle\EventListener;

use FOS\UserBundle\Event\FilterUserResponseEvent;
use FOS\UserBundle\Event\UserEvent;
use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Security\LoginManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Exception\AccountStatusException;

class RegistrationSuccessEventListener implements EventSubscriberInterface
{
	public static function getSubscribedEvents()
	{
	    return array(
	        FOSUserEvents::REGISTRATION_COMPLETED => 'onRegistrationCompleted',
	    );
	}

	public function onRegistrationCompleted(FilterUserResponseEvent  $event)
	{

	}
}
