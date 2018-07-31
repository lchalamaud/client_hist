<?php

namespace AppBundle\Service;

use AppBundle\Entity\Affaire;

use Symfony\Component\Validator\Constraints\DateTime;

use jamesiarmes\PhpEws\Client;

use jamesiarmes\PhpEws\Request\FindFolderType;
use jamesiarmes\PhpEws\Request\FindItemType;
use jamesiarmes\PhpEws\Request\GetItemType;
use jamesiarmes\PhpEws\Request\MoveItemType;

use jamesiarmes\PhpEws\Type\ConstantValueType;
use jamesiarmes\PhpEws\Type\DistinguishedFolderIdType;
use jamesiarmes\PhpEws\Type\FieldOrderType;
use jamesiarmes\PhpEws\Type\FolderResponseShapeType;
use jamesiarmes\PhpEws\Type\ItemIdType;
use jamesiarmes\PhpEws\Type\ItemResponseShapeType;
use jamesiarmes\PhpEws\Type\PathToUnindexedFieldType;
use jamesiarmes\PhpEws\Type\RestrictionType;
use jamesiarmes\PhpEws\Type\TargetFolderIdType;

use jamesiarmes\PhpEws\ArrayType\NonEmptyArrayOfBaseFolderIdsType;
use jamesiarmes\PhpEws\ArrayType\NonEmptyArrayOfBaseItemIdsType;
use jamesiarmes\PhpEws\ArrayType\NonEmptyArrayOfFieldOrdersType;
use jamesiarmes\PhpEws\ArrayType\NonEmptyArrayOfPathsToElementType;

use jamesiarmes\PhpEws\Enumeration\BodyTypeResponseType;
use jamesiarmes\PhpEws\Enumeration\ContainmentComparisonType;
use jamesiarmes\PhpEws\Enumeration\ContainmentModeType;
use jamesiarmes\PhpEws\Enumeration\DefaultShapeNamesType;
use jamesiarmes\PhpEws\Enumeration\DistinguishedFolderIdNameType;
use jamesiarmes\PhpEws\Enumeration\FolderQueryTraversalType;
use jamesiarmes\PhpEws\Enumeration\ItemQueryTraversalType;
use jamesiarmes\PhpEws\Enumeration\ResponseClassType;
use jamesiarmes\PhpEws\Enumeration\UnindexedFieldURIType;

class Mailer
{	
    const HOST = "exchange.server.com";
    const USERNAME = "domainne\\username";
    const PASSWORD = "p******d";
    const VERSION = "";
    
    /**
     *	Configure les options de connection au serveur Exchange
     *
     *	@return : jamesiarmes\PhpEws\Client
     */
    private function connectConfig()
    {
    	$client = new Client( Mailer::HOST, Mailer::USERNAME, Mailer::PASSWORD);

        return $client;
    }

    /**
     *	Recherche les Identifiants d'un dossier
     *
     *	@param $folderName : Nom du dossier à rechercher
     *	@return : jamesiarmes\PhpEws\Type\FolderIdType
     */
	public function findFolder( $folderName )
    {
     	$client = $this->connectConfig();

        // Build the request.
        $request = new FindFolderType();
        $request->FolderShape = new FolderResponseShapeType();
        $request->FolderShape->BaseShape = DefaultShapeNamesType::ALL_PROPERTIES;
        $request->ParentFolderIds = new NonEmptyArrayOfBaseFolderIdsType();
        $request->Restriction = new RestrictionType();
        // Search recursively.
        $request->Traversal = FolderQueryTraversalType::DEEP;

        $parent = new DistinguishedFolderIdType();
        $parent->Id = DistinguishedFolderIdNameType::ROOT;
        $request->ParentFolderIds->DistinguishedFolderId[] = $parent;

        $contains = new \jamesiarmes\PhpEws\Type\ContainsExpressionType();
        $contains->FieldURI = new PathToUnindexedFieldType();
        $contains->FieldURI->FieldURI = UnindexedFieldURIType::FOLDER_DISPLAY_NAME;
        $contains->Constant = new ConstantValueType();
        $contains->Constant->Value = $folderName;
        $contains->ContainmentComparison = ContainmentComparisonType::EXACT;
        $contains->ContainmentMode = ContainmentModeType::SUBSTRING;
        $request->Restriction->Contains = $contains;

        return $client->FindFolder($request)->ResponseMessages->FindFolderResponseMessage[0]->RootFolder->Folders->Folder;
    }

	/**
     *	Recherche les Identifiants des Mails de la Boite de Réception
     *
     *	@return : jamesiarmes\PhpEws\Type\ItemIdType
     */
	public function getInboxMailId()
	{
     	$client = $this->connectConfig();

		$requestID = new FindItemType();
		
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

		return $client->FindItem($requestID);
	}

	/**
     *	Recherche le contenu des mails passés en paramètre.
     *
     *	@param $responseID : liste des jamesiarmes\PhpEws\Type\ItemIdType des mails à chercher.
     *	@return : jamesiarmes\PhpEws\Type\ItemType
     */
	public function getInboxMail( $responseID )
	{
     	$client = $this->connectConfig();

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

		return $client->GetItem($parts_request);
	}

	/**
     *	Déplace une liste de mail dans un dossier.
     *
     *	@param $ItemIds : Liste des jamesiarmes\PhpEws\Type\ItemIdType mails à déplacer.
     *	@param $destination : Nom du dossier de destination.
     */
	public function moveMailToFolder( $ItemIds, $destination )
    {
     	$client = $this->connectConfig();

        $request = new MoveItemType();

        $request->ToFolderId = new TargetFolderIdType();
        $request->ToFolderId->FolderId = $this->findFolder( $destination )[0]->FolderId;
        
        $request->ItemIds = new NonEmptyArrayOfBaseItemIdsType();
        $request->ItemIds = $ItemIds;

        $client->MoveItem($request)->ResponseMessages->MoveItemResponseMessage;
    }

}