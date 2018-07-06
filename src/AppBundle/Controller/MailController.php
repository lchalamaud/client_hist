<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Affaire;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\JsonResponse;

use AppBundle\Service\Mail;




class MailController extends Controller
{

	/**
	 * @Route("/mail/", name="mail")
	 * @Security("has_role('ROLE_USER')")
	 */
	public function readMail()
	{
		$parts_response = Mail::getInboxMail();

		$itemRspMessages = $parts_response->ResponseMessages->GetItemResponseMessage;

		$inboxMsg = array();
		foreach ($itemRspMessages as $itemRspMessage) {
			$inboxMsg[] = $itemRspMessage->Items->Message[0];
		}

		$renderResponse = ['inboxMsg' => $inboxMsg];

		return $this->render("Default/mail.html.twig", $renderResponse);
	}

	/**
	 * @Route("/mail/database/", name="mail_database")
	 * @Security("has_role('ROLE_USER')")
	 */
	public function mailDb()
	{
		$parts_response = Mail::getInboxMail();

		$itemRspMessages = $parts_response->ResponseMessages->GetItemResponseMessage;

		$affaireList = array();
		foreach ($itemRspMessages as $itemRspMessage) {
			$affaireList[] = Mail::parser($itemRspMessage->Items->Message[0])->affaireToArray(null);
		}

		$response =  new JsonResponse();
		return $response->setData(['affaires' =>$affaireList]);
	}
}