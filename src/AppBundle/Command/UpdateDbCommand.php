<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use AppBundle\Service\Mail;
use AppBundle\Service\Parser;

use jamesiarmes\PhpEws\ArrayType\NonEmptyArrayOfBaseItemIdsType;


class UpdateDbCommand extends ContainerAwareCommand 
{
    protected function configure()
    {
        $this
            ->setName('update:db')
            ->setDescription('Check mail for new Affaire.')
            ->setHelp('This command will check the applicontact@quizzbox.com mailbox, for create new Affaire.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln([
            '###  Update DB  ###',
            '',
        ]);
        $em = $this->getContainer()->get('doctrine')->getEntityManager();

        $output->writeln('Recherche de mail entrant...');
        $responseID = Mail::getInboxMailId();

        $nbMail = $responseID->ResponseMessages->FindItemResponseMessage[0]->RootFolder->TotalItemsInView;
        if( $nbMail ){

            $output->writeln([
                $nbMail.' mail'.($nbMail>1?'s':'').' à trier,',
                'Tri et créations des nouvelles affaires...'
            ]);

            $parts_response = Mail::getInboxMail( $responseID );

            $itemRspMessages = $parts_response->ResponseMessages->GetItemResponseMessage;

            $otherIds = new NonEmptyArrayOfBaseItemIdsType();
            $otherIds->ItemId = array();

            $affaireIds = new NonEmptyArrayOfBaseItemIdsType();
            $affaireIds->ItemId = array();

            $nbAffaire = 0;
            foreach ($itemRspMessages as $itemRspMessage) {
                if(Parser::Prefilter($itemRspMessage->Items->Message[0])){
                    $affaire = Parser::parser($itemRspMessage->Items->Message[0]);
                    $em->persist($affaire);
                    $affaireIds->ItemId[] = $itemRspMessage->Items->Message[0]->ItemId;
                    $nbAffaire++;
                }else{
                    $otherIds->ItemId[] = $itemRspMessage->Items->Message[0]->ItemId;
                }
            }
            $em->flush();
            $output->writeln($nbAffaire.' nouvelle'.($nbAffaire>1?'s':'').' affaire'.($nbAffaire>1?'s':'').' créée'.($nbAffaire>1?'s.':'.'));

            Mail::moveMailToFolder( $affaireIds, 'Affaires');
            Mail::moveMailToFolder( $otherIds, 'Autres');
        }else{
            $output->writeln('Aucune demande trouvée.');
        }
        $output->writeln('Mise à jour terminée.');
    }
}