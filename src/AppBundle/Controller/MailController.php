<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Affaire;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\JsonResponse;

use AppBundle\Service\Mail;

use AppBundle\Entity\MailID;

use Symfony\Component\Validator\Constraints\DateTime;

use jamesiarmes\PhpEws\Client;

use jamesiarmes\PhpEws\Request\FindItemType;
use jamesiarmes\PhpEws\Request\GetItemType;

use jamesiarmes\PhpEws\Type\DistinguishedFolderIdType;
use jamesiarmes\PhpEws\Type\FieldOrderType;
use jamesiarmes\PhpEws\Type\ItemIdType;
use jamesiarmes\PhpEws\Type\ItemResponseShapeType;
use jamesiarmes\PhpEws\Type\PathToUnindexedFieldType;

use jamesiarmes\PhpEws\ArrayType\NonEmptyArrayOfBaseFolderIdsType;
use jamesiarmes\PhpEws\ArrayType\NonEmptyArrayOfBaseItemIdsType;
use jamesiarmes\PhpEws\ArrayType\NonEmptyArrayOfFieldOrdersType;
use jamesiarmes\PhpEws\ArrayType\NonEmptyArrayOfPathsToElementType;

use jamesiarmes\PhpEws\Enumeration\BodyTypeResponseType;
use jamesiarmes\PhpEws\Enumeration\DefaultShapeNamesType;
use jamesiarmes\PhpEws\Enumeration\DistinguishedFolderIdNameType;
use jamesiarmes\PhpEws\Enumeration\ItemQueryTraversalType;


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
		$em = $this->getDoctrine()->getManager();
		$parts_response = Mail::getInboxMail();

		$itemRspMessages = $parts_response->ResponseMessages->GetItemResponseMessage;

		$affaireList = array();

		foreach ($itemRspMessages as $itemRspMessage) {
			$affaireList[] = Mail::parser($itemRspMessage->Items->Message[0])->affaireToArray(null);
			$affaire = Mail::parser($itemRspMessage->Items->Message[0]);
			$em->persist($affaire);
		}

		$em->flush();

		$response =  new JsonResponse();
		return $response->setData(['affaires' =>$affaireList]);
	}
}