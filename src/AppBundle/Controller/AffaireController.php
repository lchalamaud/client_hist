<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Affaire;
use AppBundle\Entity\Commercial;
use AppBundle\Entity\Tache;
use AppBundle\Entity\Preference;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
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
    public function getOldestTaskDate( $affaire, $type )
    {
        $em = $this->getDoctrine()->getManager();

        $taskList = $em->getRepository('AppBundle:Tache')->findBy(['affaire' => $affaire->getId()]);

        $oldestDate= $affaire->getDebut()->format('Y-m-d');

        $oldestTache = new Tache();
        $oldestTache->setDate($oldestDate);

        foreach ($taskList as $task){
            if($task->getType() === $type){ 
                if($task->getDate() > $oldestTache->getDate()){
                    $oldestTache = $task;
                }
            }
        }
        if($oldestTache->getType()){
            $oldestDate = $oldestTache->getDate()->format('Y-m-d');
        }

        return $oldestDate;
    }

    /**
     * @Route("/", name="affaire_tab")
     * @Security("has_role('ROLE_USER')")
     */
    public function indexAffaire(Request $request)
    {

        $em = $this->getDoctrine()->getManager();

        /*********************************
         **  Gestion des préférences    **
         *********************************/
        
        $user = $this->getUser();

        if($user->getPreference() != NULL){
            $preference = $user->getPreference();
        }else{
            $preference = new Preference();
            $user->setPreference($preference);
        }

        $em->persist($preference);
        $em->flush();

        $config = array(
            'timeStep' => $preference->getTemps(),
            'commercialPref' => $preference->getComm(),
            'enCours' => $preference->getEn_cours(),
            'oublie' => $preference->getOublie(),
            'suspendu' => $preference->getSuspendu(),
            'fin' => $preference->getFin(),
            'signe' => $preference->getSigne(),
            'signEC' => $preference->getSigneEC(),
        );



        /******************
         **  Formulaire  **
         ******************/

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


        /*************************
         ** Table des Affaires  **
         *************************/

        $repAffaire = $em->getRepository('AppBundle:Affaire');
        $listAffaires = $repAffaire->findAll();
        $todayDate = date("Y-m-d");
        $affaireTab = array();

        foreach ($listAffaires as $affaire) {

            switch ( $affaire->getEtat() )
            {
                case 'En Cours':
                    $oldestDate = $this->getOldestTaskDate( $affaire, 'Rappel' );
                    if($oldestDate < $todayDate){
                        $affaire->setEtat('Oublié');
                    }
                    break;
                case 'Signé':
                    $oldestDate = $this->getOldestTaskDate( $affaire, 'Signature' );
                    break;
                case 'Sign EC':
                    $oldestDate = $this->getOldestTaskDate( $affaire, 'Sign EC' );
                    break;
                case 'Suspendu':
                    $oldestDate = $this->getOldestTaskDate( $affaire, 'Suspension' );
                    break;
                case 'Fin':
                    $oldestDate = $this->getOldestTaskDate( $affaire, 'Fin' );
                    break;
                default:
                    $oldestDate = $affaire->getDebut()->format('Y-m-d');
                    break;
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
                'commercial' => $affaire->getCommercial(),
                'commentaire' => $affaire->getCommentaire(),
                'info' => $affaire->getInfo(),
                'numDossier' => $affaire->getNumDossier(),
                'id' => $affaire->getId()
            );

            $affaireTab[] = $tmp;
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

        /*  Supprime les taches liés à l'affaire avant de supprimer l'affaire    */
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
        $commercial = $rptComm->findOneBy(['acronyme' => $request->get('commercial')]);

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
        $affaire->setCommercial($commercial);
        $affaire->setCommentaire($request->get('commentaire'));


        $em->persist($affaire);
        $em->flush();
        
        $response = new JsonResponse();

        return $response->setData(array('color' => $commercial->getCouleur()));
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

    /**
     *@Route("/affaire/get/", name="affaire_get")
     */
    public function getAffaire(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $repAffaire = $em->getRepository('AppBundle:Affaire');
        $listAffaires = $repAffaire->findAll();
        
        $todayDate = date("Y-m-d");

        $affaireTab = array();

        foreach ($listAffaires as $affaire) {


            /*  Recherche de la tache la plus ancienne   */
            switch ( $affaire->getEtat() )
            {
                case 'En Cours':
                    $oldestDate = $this->getOldestTaskDate( $affaire, 'Rappel' );
                    if($oldestDate < $todayDate){
                        $affaire->setEtat('Oublié');
                    }
                    break;
                case 'Signé':
                    $oldestDate = $this->getOldestTaskDate( $affaire, 'Signature' );
                    break;
                case 'Sign EC':
                    $oldestDate = $this->getOldestTaskDate( $affaire, 'Sign EC' );
                    break;
                case 'Suspendu':
                    $oldestDate = $this->getOldestTaskDate( $affaire, 'Suspension' );
                    break;
                case 'Fin':
                    $oldestDate = $this->getOldestTaskDate( $affaire, 'Fin' );
                    break;
                default:
                    $oldestDate = $affaire->getDebut()->format('Y-m-d');
                    break;
            }

            $commercial = $affaire->getCommercial();

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
                'commercialAcronyme' => $commercial?$commercial->getAcronyme():null,
                'commercialCouleur' => $commercial?$commercial->getCouleur():null,
                'commentaire' => $affaire->getCommentaire(),
                'info' => $affaire->getInfo(),
                'numDossier' => $affaire->getNumDossier(),
                'id' => $affaire->getId()
            );

            $affaireTab[] = $tmp;
        }

        $response = new JsonResponse;

        return $response->setData(array('affaires' => $affaireTab));
    }
}