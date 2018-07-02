<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use \jamesiarmes\PhpEws\Client;

class MailController extends Controller
{
	/**
	 * @Route("/mail/", name="mail")
	 */
	public function readMail()
	{
		// Set connection information.
		$host = 'mail.nextmedia.fr';
		$username = 'Louis.Chalamaud@quizzbox.com';
		$password = 'QuizzBox-63.';
		$version = '';

		$ews = new ExchangeWebServices($host, $username, $password);
		/*
		// start building the find folder request
		$request = new EWSType_FindFolderType();
		$request->Traversal = EWSType_FolderQueryTraversalType::SHALLOW;
		$request->FolderShape = new EWSType_FolderResponseShapeType();
		$request->FolderShape->BaseShape = EWSType_DefaultShapeNamesType::ALL_PROPERTIES;

		// configure the view
		$request->IndexedPageFolderView = new EWSType_IndexedPageViewType();
		$request->IndexedPageFolderView->BasePoint = 'Beginning';
		$request->IndexedPageFolderView->Offset = 0;

		// set the starting folder as the inbox
		$request->ParentFolderIds = new EWSType_NonEmptyArrayOfBaseFolderIdsType();
		$request->ParentFolderIds->DistinguishedFolderId = new EWSType_DistinguishedFolderIdType();
		$request->ParentFolderIds->DistinguishedFolderId->Id = EWSType_DistinguishedFolderIdNameType::INBOX;

		// make the actual call
		$response = $ews->FindFolder($request);
		echo '<pre>'.print_r($response, true).'</pre>';*/

		return $this->render("Default/mail.html.twig");
	}
}