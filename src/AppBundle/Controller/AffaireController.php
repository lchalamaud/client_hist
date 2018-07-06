<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Affaire;
use AppBundle\Entity\Commercial;
use AppBundle\Entity\Tache;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\JsonResponse;

class AffaireController extends Controller
{
    /**
     * @Route("/", name="affaire_tab")
     * @Security("has_role('ROLE_USER')")
     */
    public function Affaire(Request $request)
    {

        $em = $this->getDoctrine()->getManager();


        /*  Formulaire  */
        $affaire = new affaire();
        $addform = $this->createForm('AppBundle\Form\AffaireType', $affaire);
        
        if ($request->isMethod('POST')) {
            $addform->handleRequest($request);
            if ($addform->isValid()) {
                $affaire->setNom( $addform[ 'Nom' ]->getData().' '.$addform[ 'Prenom' ]->getData() );
                $affaire->setEtat( 'En Cours' );

                $em->persist($affaire);
                $em->flush();

                $request->getSession()->getFlashBag()->add('notice', 'Nouvelle affaire ajoutée');

                return $this->redirectToRoute('affaire_tab');
                
            }
        }


        /*  Table des Affaires  */
        $repAffaire = $em->getRepository('AppBundle:Affaire');
        $listAffaires = $repAffaire->findAll();

        $repTache = $em->getRepository('AppBundle:Tache');
        
        $todayDate = date("Y-m-d");

        $affaireTab = array();

        foreach ($listAffaires as $affaire) {


            $commercial = $affaire->getCommercial();

            $listTaches = $repTache->findBy(['affaire' => $affaire->getId()]);
            
            $oldestDate= $affaire->getDebut()->format('Y-m-d');
            $oldestTache = new Tache();
            $oldestTache->setDate($oldestDate);

            if($affaire->getEtat() === 'En Cours'){
                foreach ($listTaches as $tache){
                    if($tache->getType() === 'Rappel'){ 
                        if($tache->getDate() > $oldestTache->getDate()){
                            $oldestTache = $tache;

                        }
                    }
                }
                if($oldestTache->getType()){
                    $oldestDate = $oldestTache->getDate()->format('Y-m-d');
                    if($oldestDate < $todayDate){
                        $affaire->setEtat('Oublié');
                    }

                }
            }elseif($affaire->getEtat() === 'Signé'){
                foreach ($listTaches as $tache){
                    if($tache->getType() === 'Signature'){ 
                        if($tache->getDate() > $oldestTache->getDate()){
                            $oldestTache = $tache;

                        }
                    }
                }
                if($oldestTache->getType()){
                    $oldestDate = $oldestTache->getDate()->format('Y-m-d');
                }
                
            }elseif($affaire->getEtat() === 'Suspendu'){
                foreach ($listTaches as $tache){
                    if($tache->getType() === 'Suspension'){ 
                        if($tache->getDate() > $oldestTache->getDate()){
                            $oldestTache = $tache;

                        }
                    }
                }
                if($oldestTache->getType()){
                    $oldestDate = $oldestTache->getDate()->format('Y-m-d');
                }
                
            }elseif($affaire->getEtat() === 'Fin'){
                foreach ($listTaches as $tache){
                    if($tache->getType() === 'Fin'){ 
                        if($tache->getDate() > $oldestTache->getDate()){
                            $oldestTache = $tache;

                        }
                    }
                }
                if($oldestTache->getType()){
                    $oldestDate = $oldestTache->getDate()->format('Y-m-d');
                }
                
            }

            $tmp = array(
                'civilite' => $affaire->getCivilite(),
                'nom' => $affaire->getNom(),
                'societe' => $affaire->getSociete(),

                'rue' => $affaire->getRue(),
                'complement' => $affaire->getComplement(),
                'cp' => $affaire->getCP(),
                'ville' => $affaire->getVille(),

                'mail' => $affaire->getEmail(),
                'telephone' => $affaire->getTelephone(),

                'nb_controller' => $affaire->getNbController(),
                'devi_type' => $affaire->getDevisType(),
                'system_type' => $affaire->getSystemType(),
                'provenance' => $affaire->getProvenance(),

                'debut' => $affaire->getDebut()->format('Y-m-d'),
                'etat' => $affaire->getEtat(),
                'rappel' => $oldestDate,
                'commercial' => $commercial ? $commercial->getAcronyme() : null,
                'commentaire' => $affaire->getCommentaire(),
                'info' => $affaire->getInfo(),
                'id' => $affaire->getId()
            );

            $affaireTab[] = $tmp;
        }


        /*  Lecture du fichier de préférence    */
        
        $publicResourcesFolderPath = $this->get('kernel')->getRootDir() . '/../web/preferences/';
        $filename = "userprofile.conf";

        $file = $publicResourcesFolderPath.$filename;
        $configFile = explode("\n", file_get_contents($file));
        if(sizeof($configFile) > 1){
            $timeStep = explode(":",$configFile[0])[1];
            $commercialPref = explode(":",$configFile[1])[1];
            $enCours = explode(":",$configFile[2])[1];
            $oublie = explode(":",$configFile[3])[1];
            $suspendu = explode(":",$configFile[4])[1];
            $fin = explode(":",$configFile[5])[1];
            $signe = explode(":",$configFile[6])[1];

            $config = array(
                'timeStep' => $timeStep,
                'commercialPref' => $commercialPref,
                'enCours' => $enCours,
                'oublie' => $oublie,
                'suspendu' => $suspendu,
                'fin' => $fin,
                'signe' => $signe,
            );
        }else{
            $config = array(
                'timeStep' => '',
                'commercialPref' => '',
                'enCours' => '',
                'oublie' => '',
                'suspendu' => '',
                'fin' => '',
                'signe' => '',
            );
        }
        

        


        $response = array( 'addform' => $addform->createView(), 'affaires' => $affaireTab, 'config'=> $config );

        return $this->render('Default/affaireTable.html.twig', $response);
    }

    /**
     *@Route("/del/affaire/{id}/", name="del_affaire")
     */
    public function supprAffaire($id)
    {
        $em = $this->getDoctrine()->getManager();

        $rptTache = $em->getRepository('AppBundle:Tache');
        $listTaches = $rptTache->findBy(['affaire' => $id]);
        foreach ($listTaches as $tache) {
            $em->remove($tache);
        }

        $rptAffaire = $em->getRepository('AppBundle:Affaire');
        $affaire = $rptAffaire->find($id);
        $em->remove($affaire);
        $em->flush();

        return $this->redirectToRoute( 'affaire_tab' );
    }

    /**
     *@Route("/modif/affaire/", name="modif_affaire")
     */
    public function modifAffaire(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $rptAffaire = $em->getRepository('AppBundle:Affaire');
        $rptComm = $em->getRepository('AppBundle:Commercial');

        $affaire = $rptAffaire->find($request->get('id'));

        $affaire->setCivilite($request->get('civilite'));
        $affaire->setNom($request->get('nom'));
        $affaire->setSociete($request->get('societe'));

        $affaire->setRue($request->get('rue'));
        $affaire->setComplement($request->get('complement'));
        $affaire->setCP($request->get('cp'));
        $affaire->setVille($request->get('ville'));

        $affaire->setEmail($request->get('email'));
        $affaire->setTelephone($request->get('telephone'));

        $affaire->setNbController($request->get('nbController'));
        $affaire->setDevisType($request->get('devisType'));
        $affaire->setSystemType($request->get('systemType'));
        $affaire->setProvenance($request->get('provenance'));

        $affaire->setDebut(new \DateTime($request->get('debut')));
        $affaire->setCommercial($rptComm->findOneBy(['acronyme' => $request->get('commercial')]));
        $affaire->setCommentaire($request->get('commentaire'));


        $em->persist($affaire);
        $em->flush();
        
        $response = new JsonResponse();

        return $response->setData(array('nom' => $affaire->getNom(), 'societe' => $affaire->getSociete()));
    }

    /**
     *@Route("/set/info/", name="set_info")
     */
    public function setInfo(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $rptAffaire = $em->getRepository('AppBundle:Affaire');
        $affaire = $rptAffaire->find($request->get('idAffaire'));

        $affaire->setInfo($request->get('info'));

        $em->persist($affaire);
        $em->flush();

        return new JsonResponse();
    }
}