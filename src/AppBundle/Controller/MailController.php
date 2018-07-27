<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Affaire;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\JsonResponse;

use AppBundle\Service\Mailer;
use AppBundle\Service\Parser;

use Symfony\Component\Validator\Constraints\DateTime;

use jamesiarmes\PhpEws\ArrayType\NonEmptyArrayOfBaseItemIdsType;


class MailController extends Controller
{

	private $mailer;

	private $parser;
	
	public function __construct(Mailer $mailer, Parser $parser){
		$this->mailer = $mailer;
		$this->parser = $parser;
	}

	/**
	 * @Route("/mail/", name="mail")
	 * @Security("has_role('ROLE_ADMIN')")
	 */
	public function readMail()
	{	
		$mailer = $this->mailer;

		$inboxMsg = array();

		$responseID = $mailer->getInboxMailId();
		$nbMail = $responseID->ResponseMessages->FindItemResponseMessage[0]->RootFolder->TotalItemsInView;
		if( $nbMail ){
			$parts_response = $mailer->getInboxMail( $responseID );

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
	 */
	public function mailDb()
	{
		$mailer = $this->mailer;
		$parser = $this->parser;
		$em = $this->getDoctrine()->getManager();

		$affaireList = array();

		$responseID = $mailer->getInboxMailId();

		$nbMail = $responseID->ResponseMessages->FindItemResponseMessage[0]->RootFolder->TotalItemsInView;
		if( $nbMail ){
			$parts_response = $mailer->getInboxMail( $responseID );

			$itemRspMessages = $parts_response->ResponseMessages->GetItemResponseMessage;

			$otherIds = new NonEmptyArrayOfBaseItemIdsType();
			$otherIds->ItemId = array();

			$affaireIds = new NonEmptyArrayOfBaseItemIdsType();
			$affaireIds->ItemId = array();

			foreach ($itemRspMessages as $itemRspMessage) {
				if($parser->preFilter($itemRspMessage->Items->Message[0])){
					$affaireList[] = $parser->parser($itemRspMessage->Items->Message[0])->affaireToArray(null);
					$affaire = $parser->parser($itemRspMessage->Items->Message[0]);
					$em->persist($affaire);
					$affaireIds->ItemId[] = $itemRspMessage->Items->Message[0]->ItemId;
				}else{
					$otherIds->ItemId[] = $itemRspMessage->Items->Message[0]->ItemId;
				}
			}

			$em->flush();

			$mailer->moveMailToFolder( $affaireIds, 'Affaires');
			$mailer->moveMailToFolder( $otherIds, 'Autres');
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
		$response = $this->mailer->findFolder($name);

		return $this->render("Default/folderName.html.twig", ['folders' => $response]);
	}
}