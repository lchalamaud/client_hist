<?php

namespace AppBundle\Service;

use AppBundle\Entity\Affaire;

use Symfony\Component\Validator\Constraints\DateTime;

use jamesiarmes\PhpEws\Client;

use jamesiarmes\PhpEws\Request\FindFolderType;
use jamesiarmes\PhpEws\Request\FindItemType;
use jamesiarmes\PhpEws\Request\GetItemType;

use jamesiarmes\PhpEws\Type\ConstantValueType;
use jamesiarmes\PhpEws\Type\DistinguishedFolderIdType;
use jamesiarmes\PhpEws\Type\FieldOrderType;
use jamesiarmes\PhpEws\Type\FolderResponseShapeType;
use jamesiarmes\PhpEws\Type\ItemIdType;
use jamesiarmes\PhpEws\Type\ItemResponseShapeType;
use jamesiarmes\PhpEws\Type\PathToUnindexedFieldType;
use jamesiarmes\PhpEws\Type\RestrictionType;

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

class Mail
{	
	const HOST = "Exchange.Server.com";
    const USERNAME = "domain\\username";
    const PASSWORD = "password";
    const VERSION = "";


	public function findFolder($folderName)
    {
        $client = new Client( Mail::HOST, Mail::USERNAME, Mail::PASSWORD);
        $client->setCurlOptions(array(CURLOPT_SSL_VERIFYPEER => false)); 
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

	public function getInboxMailId()
	{
		// Set connection information.
        $client = new Client( Mail::HOST, Mail::USERNAME, Mail::PASSWORD);

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

		return $client->FindItem($requestID);
	}

	public function getInboxMail()
	{
		// Set connection information.
        $client = new Client( Mail::HOST, Mail::USERNAME, Mail::PASSWORD);

		$client->setCurlOptions(array(CURLOPT_SSL_VERIFYPEER => false)); //		!!!!	DANGER	!!!!

		$responseID = Mail::getInboxMailId();

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

		return $client->GetItem($parts_request);
	}

	/**
     * {@inheritdoc}
     */
	private function parseLine( string $line ): string
	{
		$data = explode(" : ", $line, 2);
		if(sizeof($data) >= 2){
			return explode(" : ", $line, 2)[1];
		}else{
			return '';
		}
	}

	public function parser($mail){

		$body = $mail->Body->_;
		$token = explode("\r\n", $body);

		switch ($token[0])
		{
			case 'Demande de devis site Allemand':
				return Mail::alParser($mail);
			default:
				return Mail::frParser($mail);
		}

	}

	public function frParser($mail)
	{
		$body = $mail->Body->_;
		$data = array();
		$token = explode("\r\n", $body, 13);

		$affaire = new Affaire();

		switch (Mail::parseLine($token[0])) {
			case 'Renseignement':
				$affaire->setDevisType('Rens.');
				break;
			case 'Location':
				$affaire->setDevisType('Loc.');
				break;
			default:
				$affaire->setDevisType(Mail::parseLine($token[0]));
				break;
		}
		
		$affaire->setSociete(Mail::parseLine($token[1]));
		if( Mail::parseLine($token[2]) == 'Monsieur'){
			$affaire->setCivilite('M.');
		}else{
			$affaire->setCivilite('Mme');
		}
		$affaire->setNom(Mail::parseLine($token[3]));
		$withGoogleMap = Mail::parseLine($token[4]);
		$affaire->setRue(explode("<https://", $withGoogleMap)[0]);
		$affaire->setCP(Mail::parseLine($token[5]));
		$affaire->setVille(Mail::parseLine($token[6]));
		$affaire->setTelephone(str_replace(' ', '', Mail::parseLine($token[7])));
		$withMailTo = Mail::parseLine($token[8]);
		$affaire->setEmail(explode("<mailto:" ,$withMailTo)[0]);
		switch (Mail::parseLine($token[9])) {
			case 'Entreprise':
				$affaire->setSystemType('Ent.');
				break;
			case 'Education':
				$affaire->setSystemType('Educ.');
				break;
			case 'Assemblée Générale':
				$affaire->setSystemType('AG');
				break;
			default:
				$affaire->setSystemType(Mail::parseLine($token[9]));
				break;
		}
		$affaire->setNbController(Mail::parseLine($token[10]));
		$affaire->setProvenance(Mail::parseLine($token[11]));
		$affaire->setCommentaire(Mail::parseLine($token[12]));

		$affaire->setDebut(new \DateTime(substr( str_replace("T", " ", $mail->DateTimeCreated), 0, 19)));
		$affaire->setEtat( 'En Cours' );

		return $affaire;
	}


	public function alParser($mail)
	{
		$body = $mail->Body->_;
		$data = array();
		$token = explode("\r\n", $body, 15);

		$affaire = new Affaire();

		$affaire->setDevisType(Mail::parseLine($token[1]));
		$affaire->setSociete(Mail::parseLine($token[2]));
		if( Mail::parseLine($token[3]) == 'Monsieur'){
			$affaire->setCivilite('M.');
		}else{
			$affaire->setCivilite('Mme');
		}
		$affaire->setNom(Mail::parseLine($token[4]));
		$withMailTo = Mail::parseLine($token[5]);
		$affaire->setEmail(explode("<mailto:", $withMailTo)[0]);
		$withGoogleMap = Mail::parseLine($token[6]);
		$affaire->setRue(explode("<https://", $withGoogleMap)[0]);
		$affaire->setTelephone(str_replace(' ', '', Mail::parseLine($token[7])));
		$affaire->setCP(Mail::parseLine($token[8]));
		$affaire->setVille(Mail::parseLine($token[9]));
		
		
		$affaire->setSystemType(Mail::parseLine($token[10]));
		//Nb systeme...
		$affaire->setNbController(Mail::parseLine($token[12]));
		$affaire->setProvenance(Mail::parseLine($token[13]));
		$affaire->setCommentaire(Mail::parseLine($token[14]));

		$affaire->setDebut(new \DateTime(substr( str_replace("T", " ", $mail->DateTimeCreated), 0, 19)));
		$affaire->setEtat( 'En Cours' );

		return $affaire;
	}



}