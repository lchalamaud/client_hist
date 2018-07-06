<?php

namespace AppBundle\Service;

use AppBundle\Entity\Affaire;

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

class Mail
{

	public function getInboxMail()
	{
		// Set connection information.
		$host = "";
		$username = "";
		$password = "";
		$version = "";

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

		$affaire->setDevisType(Mail::parseLine($token[0]));
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
		$affaire->setSystemType(Mail::parseLine($token[9]));
		$affaire->setNbController(Mail::parseLine($token[10]));
		$affaire->setProvenance(Mail::parseLine($token[11]));
		$affaire->setCommentaire(Mail::parseLine($token[12]));

		$affaire->setDebut( substr( $mail->DateTimeCreated, 0, 10) );
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

		$affaire->setDebut( substr( $mail->DateTimeCreated, 0, 10) );
		$affaire->setEtat( 'En Cours' );

		return $affaire;
	}


}