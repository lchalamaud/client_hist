<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use AppBundle\Service\Mailer;

use jamesiarmes\PhpEws\Type\MessageType;
use jamesiarmes\PhpEws\Type\BodyType;

use jamesiarmes\PhpEws\ArrayType\NonEmptyArrayOfBaseItemIdsType;


class TestController extends Controller
{
    private $mailer;

    public function __construct(Mailer $mailer){
        $this->mailer = $mailer;
    }

    /**
     *@Route("/test/", name="test")
     */
    public function index()
    {
    	$mailer = $this->mailer;

        $responseID = $mailer->getFolderMailId('Affaires');

        $arrayIds = new NonEmptyArrayOfBaseItemIdsType();
        $arrayIds->ItemId = array();

        $mailer->moveMailToFolder( $responseID, 'Boîte de réception' );


        return $this->render('Default/test.html.twig', ['rsp' => $responseID]);
    }
}