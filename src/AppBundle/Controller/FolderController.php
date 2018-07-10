<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\JsonResponse;

use \jamesiarmes\PhpEws\Client;

use AppBundle\Service\Mail;

use \jamesiarmes\PhpEws\Request\FindFolderType;
use \jamesiarmes\PhpEws\Request\MoveItemType;

use \jamesiarmes\PhpEws\ArrayType\NonEmptyArrayOfBaseFolderIdsType;

use \jamesiarmes\PhpEws\Enumeration\ContainmentComparisonType;
use \jamesiarmes\PhpEws\Enumeration\ContainmentModeType;
use \jamesiarmes\PhpEws\Enumeration\DefaultShapeNamesType;
use \jamesiarmes\PhpEws\Enumeration\DistinguishedFolderIdNameType;
use \jamesiarmes\PhpEws\Enumeration\FolderQueryTraversalType;
use \jamesiarmes\PhpEws\Enumeration\ResponseClassType;
use \jamesiarmes\PhpEws\Enumeration\UnindexedFieldURIType;

use \jamesiarmes\PhpEws\Type\ConstantValueType;
use \jamesiarmes\PhpEws\Type\DistinguishedFolderIdType;
use \jamesiarmes\PhpEws\Type\FolderResponseShapeType;
use \jamesiarmes\PhpEws\Type\PathToUnindexedFieldType;
use \jamesiarmes\PhpEws\Type\RestrictionType;

class FolderController extends Controller
{

    /**
     * @Route("/mail/move/", name="mail_move")
     * @Security("has_role('ROLE_USER')")
     */
    public function moveFolder()
    {
        return $this->render("Default/testastos.html.twig",['response' => Mail::findFolder('Boîte de réception')[0]]);
        /*$host = "mail.nextmedia.fr";
        $username = "quizzbox\\Louisc";
        $password = "NextMedia-63.";
        $version = "";

        $client = new Client($host, $username, $password);
        $client->setCurlOptions(array(CURLOPT_SSL_VERIFYPEER => false)); 
        // Build the request.
        $request = new MoveItemType();
        $request->BaseMoveCopyItemType = new BaseMoveCopyItemType();
        
        $request->BaseMoveCopyItemType->ToFolderId = new TargetFolderIdType();
        $request->BaseMoveCopyItemType->ToFolderId->FolderId = new FolderIdType();
        $request->BaseMoveCopyItemType->ToFolderId->FolderId->ChangeKey = "AQAAABQAAAAEKe2pF3zFRYfXxABCx7bfAABIBQ==";
        $request->BaseMoveCopyItemType->ToFolderId->FolderId->Id = "AAMkAGI4Y2EwZTQxLWM5MGEtNGFmZi04NWQ5LThmMzViYmE2ZTUzMAAuAAAAAADSth3tv99sTaW0J7dNUOvUAQBjdOyR0YDVRLKyKTs1Kgl5AAAcbjl5AAA=";
        
        $request->BaseMoveCopyItemType->ItemIds;*/

        return $this->render("Default/testastos.html.twig", ['response'=> 0]);
    }

}