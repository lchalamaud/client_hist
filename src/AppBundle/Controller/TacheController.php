<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Tache;
use AppBundle\Entity\Affaire;
use AppBundle\Entity\Commercial;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;


class TacheController extends Controller
{

    /**
     *@Route("/tache/affaire/nom/", name="tache_affaire_nom")
     */
    public function getTacheAndComm(Request $request)
    {
        $id = $request->get('idAffaire');
        $em = $this->getDoctrine()->getManager();
        $repTache = $em->getRepository('AppBundle:Tache');
        $listTaches = $repTache->findBy(['affaire' => $id]);

        $tacheTab = array();
        foreach ($listTaches as $tache) {
            $tmp = array(
                'commercial' => $tache->getCommercial()->getAcronyme(),
                'type' => $tache->getType(),
                'date' => $tache->getDate()->format('Y-m-d'),
                'couleur' => $tache->getCommercial()->getCouleur(),
                'id' => $tache->getId(),
            );
            $tacheTab[] = $tmp;
        }

        $repComm = $em->getRepository('AppBundle:Commercial');

        $listCommercials = $repComm->findAll();
        $commercialTab = array();
        foreach ($listCommercials as $commercial) {
            $com = array(
                'nom' => $commercial->getNom(),
                'acronyme' => $commercial->getAcronyme(),
                'couleur' => $commercial->getCouleur()
            );

            $commercialTab[] = $com;
        }

        $response = new JsonResponse();

        return $response->setData(array( 'taches' => $tacheTab, 'commercial' => $commercialTab));
    }

    /**
     *@Route("/add/tache/")
     */
    public function adderTache(Request $request)
    {
        $idAffaire = $request->get('idAffaire');
        $type = $request->get('type');
        $comm = $request->get('commercial');
        $date = $request->get('date');
        $numDossier = $request->get('numDossier');
        
        $em = $this->getDoctrine()->getManager();

        $tache = new Tache();
        $repAffaire = $em->getRepository('AppBundle:Affaire');
        $affaire = new Affaire();
        $affaire = $repAffaire->find($idAffaire);

        $repComm = $em->getRepository('AppBundle:Commercial');
        $commercial = new Commercial();
        $commercial = $repComm->findOneBy(array( 'acronyme' => $comm ));

        $tache->setAffaire($affaire);
        $tache->setCommercial($commercial);
        $tache->setType($type);
        $tache->setDate(new \DateTime($date));

        $em->persist($tache);

        $commentTmp = $affaire->getInfo();
        $affaire->setInfo( $type.', '.$date.' ('.$comm."):\n\n".$commentTmp );
        $affaire->setNumDossier($numDossier);

        if( $type === 'Signature' ){
            $affaire->setEtat('Signé');
        }elseif( $type === 'Fin' ){
            $affaire->setEtat('Fin');
        }elseif( $type === 'Suspension' ){
            $affaire->setEtat('Suspendu');
        }elseif( $type === 'Sign EC' ){
            $affaire->setEtat('Sign EC');
        }else{
            $affaire->setEtat('En Cours');
        }

        $em->persist($affaire);
        $em->flush();

        $response = new JsonResponse();

        return $response->setData(array( 'couleur' => $commercial->getCouleur(), 'id' => $tache->getId()));
    }


    /**
     *@Route("/del/tache/")
     */
    public function delTache(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $rptTache = $em->getRepository('AppBundle:Tache');
        $tache = $rptTache->find( $request->get('idTache') );
        
        $em->remove($tache);
        $em->flush();

        $response = new JsonResponse();

        return $response;
    }

}
