<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Affaire;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

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
		// Set connection information.
		$host = 'mail.nextmedia.fr';
		$username = "quizzbox\\Louisc";
		$password = 'NextMedia-63.';
		$version = '';

		$client = new Client($host, $username, $password);
		$client->setCurlOptions(array(CURLOPT_SSL_VERIFYPEER => false)); //		!!!!	DANGER	!!!!


		/*****************************************************************
		 **	Recherche de l'ID des messages dans la boite de réception	**
		 *****************************************************************/

		$requestID = new FindItemType();
		
		//
		$requestID->ItemShape = new ItemResponseShapeType();
		$requestID->ItemShape->BaseShape = 'IdOnly';
		$requestID->ParentFolderIds = new NonEmptyArrayOfBaseFolderIdsType();
		$requestID->ParentFolderIds->DistinguishedFolderId = new DistinguishedFolderIdType();
		$requestID->ParentFolderIds->DistinguishedFolderId->Id = DistinguishedFolderIdNameType::INBOX;
		$requestID->Traversal = 'Shallow';

		//	Classement Chronologique
		$requestID->SortOrder = new NonEmptyArrayOfFieldOrdersType();
		$requestID->SortOrder->FieldOrder = array();
		$order = new FieldOrderType();
		$order->FieldURI = new PathToUnindexedFieldType();
		$order->FieldURI->FieldURI = 'item:DateTimeReceived'; 
		$order->Order = 'Descending'; 
		$requestID->SortOrder->FieldOrder[] = $order;

		$responseID = $client->FindItem($requestID);


		/*****************************************
		 **	Recherche du contenu des messages	**
		 *****************************************/
		
		$parts_request = new GetItemType();
		$parts_request->ItemShape = new ItemResponseShapeType();
		$parts_request->ItemShape->BaseShape = 'AllProperties';
		$parts_request->ItemShape->BodyType = 'Text';

		// Add the body property.
		$body_property = new PathToUnindexedFieldType();
		$body_property->FieldURI = 'item:Body';
		$parts_request->ItemShape->AdditionalProperties = new NonEmptyArrayOfPathsToElementType();
		$parts_request->ItemShape->AdditionalProperties->FieldURI = array($body_property);

		$parts_request->ItemIds = new NonEmptyArrayOfBaseItemIdsType();
		$parts_request->ItemIds->ItemId = array();

		// Iterate over the messages and add each to the parts request.
		$messages = ($responseID->ResponseMessages->FindItemResponseMessage[0])->RootFolder->Items->Message;
		foreach ($messages as $message) {
			// Add the message to the request.
			$message_item = new ItemIdType();
			$message_item->Id = $message->ItemId->Id;
			$parts_request->ItemIds->ItemId[] = $message_item;
		}

		$parts_response = $client->GetItem($parts_request);

		$itemRspMessages = $parts_response->ResponseMessages->GetItemResponseMessage;

		$inboxMsg = array();
		foreach ($itemRspMessages as $itemRspMessage) {
			$inboxMsg[] = $itemRspMessage->Items->Message[0];

		}

		$renderResponse = ['inboxMsg' => $inboxMsg];

		return $this->render("Default/mail.html.twig", $renderResponse);

	}

	
}