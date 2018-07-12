<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Affaire;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\JsonResponse;

use AppBundle\Service\Mail;
use AppBundle\Service\Parser;

use Symfony\Component\Validator\Constraints\DateTime;

use jamesiarmes\PhpEws\ArrayType\NonEmptyArrayOfBaseItemIdsType;


class MailController extends Controller
{

	/**
	 * @Route("/mail/", name="mail")
	 * @Security("has_role('ROLE_USER')")
	 */
	public function readMail()
	{	
		$inboxMsg = array();

		$responseID = Mail::getInboxMailId();
		$nbMail = $responseID->ResponseMessages->FindItemResponseMessage[0]->RootFolder->TotalItemsInView;
		if( $nbMail ){
			$parts_response = Mail::getInboxMail( $responseID );

			$itemRspMessages = $parts_response->ResponseMessages->GetItemResponseMessage;

			foreach ($itemRspMessages as $itemRspMessage) {
				$inboxMsg[] = $itemRspMessage->Items->Message[0];
			}
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
		$affaireList = array();

		$em = $this->getDoctrine()->getManager();
		
		$responseID = Mail::getInboxMailId();

		$nbMail = $responseID->ResponseMessages->FindItemResponseMessage[0]->RootFolder->TotalItemsInView;
		if( $nbMail ){
			$parts_response = Mail::getInboxMail( $responseID );

			$itemRspMessages = $parts_response->ResponseMessages->GetItemResponseMessage;

			$otherIds = new NonEmptyArrayOfBaseItemIdsType();
			$otherIds->ItemId = array();

			$affaireIds = new NonEmptyArrayOfBaseItemIdsType();
			$affaireIds->ItemId = array();

			foreach ($itemRspMessages as $itemRspMessage) {
				if(Parser::Prefilter($itemRspMessage->Items->Message[0])){
					$affaireList[] = Parser::parser($itemRspMessage->Items->Message[0])->affaireToArray(null);
					$affaire = Parser::parser($itemRspMessage->Items->Message[0]);
					$em->persist($affaire);
					$affaireIds->ItemId[] = $itemRspMessage->Items->Message[0]->ItemId;
				}else{
					$otherIds->ItemId[] = $itemRspMessage->Items->Message[0]->ItemId;
				}
			}

			$em->flush();

			Mail::moveMailToFolder( $affaireIds, 'Affaires');
			Mail::moveMailToFolder( $otherIds, 'Autres');
		}

		$response =  new JsonResponse();
		return $response->setData(['affaires' =>$affaireList]);
	}

	/**
	 * @Route("/mail/folder/{name}", name="mail_folder_name")
	 * @Security("has_role('ROLE_SUPER_ADMIN')")
	 */
	public function folderName($name)
	{
		$response = Mail::findFolder($name);

		return $this->render("Default/folderName.html.twig", ['folders' => $response]);
	}
}