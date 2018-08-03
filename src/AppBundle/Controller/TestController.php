<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use AppBundle\Service\Mailer;
use AppBundle\Service\Parser;

use jamesiarmes\PhpEws\Type\MessageType;
use jamesiarmes\PhpEws\Type\BodyType;

use jamesiarmes\PhpEws\ArrayType\NonEmptyArrayOfBaseItemIdsType;


class TestController extends Controller
{
    private $mailer;
    private $parser;

    public function __construct(Mailer $mailer, Parser $parser){
        $this->mailer = $mailer;
        $this->parser = $parser;
    }

    /**
     *@Route("/test/", name="test")
     */
    public function index()
    {
    	$mailer = $this->mailer;
        $parser = $this->parser;

        $responseID = $mailer->getInboxMailId();

        $nbMail = $responseID->ResponseMessages->FindItemResponseMessage[0]->RootFolder->TotalItemsInView;
        if( $nbMail ){
            $parts_response = $mailer->getBodyMail( $responseID );

            $itemRspMessages = $parts_response->ResponseMessages->GetItemResponseMessage;

            $otherIds = new NonEmptyArrayOfBaseItemIdsType();
            $otherIds->ItemId = array();

            $affaireIds = new NonEmptyArrayOfBaseItemIdsType();
            $affaireIds->ItemId = array();

            $nbAffaire = 0;
            foreach ($itemRspMessages as $itemRspMessage) {
                $mail = $itemRspMessage->Items->Message[0];
                $subject = $mail->Subject;
                $isTR = strpos('z'.$subject, "TR :"); // 'z'. permet de ne jamais avoir 0 si TR: est le premier mot
                dump('TR : '.$isTR);
                /*if($parser->Prefilter($itemRspMessage->Items->Message[0])){
                    $affaire = $parser->parser($itemRspMessage->Items->Message[0]);
                    $affaireIds->ItemId[] = $itemRspMessage->Items->Message[0]->ItemId;
                    $nbAffaire++;
                }else{
                    $otherIds->ItemId[] = $itemRspMessage->Items->Message[0]->ItemId;
                }*/
            }
            die;
            if( $nbAffaire ){
                $mailer->moveMailToFolder( $affaireIds, 'Affaires');
            }
            if( $nbAffaire-$nbMail ){
                $mailer->moveMailToFolder( $otherIds, 'Autres');
            }
        }
        return $this->render('Default/test.html.twig', ['rsp' => 'o']);
    }
}