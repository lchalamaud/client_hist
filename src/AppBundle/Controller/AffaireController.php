<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Affaire;
use AppBundle\Entity\Commercial;
use AppBundle\Entity\Tache;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
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
     *@Route("/", name="affaire_tab")
     */
    public function Affaire(Request $request)
    {


        $em = $this->getDoctrine()->getManager();


        /*  Formulaire  */
        $affaire = new affaire();
        
        $formBuilder = $this->get('form.factory')->createBuilder(FormType::class, $affaire);
        $formBuilder
            ->add('Civilite', ChoiceType::class, array(
                'choices' =>  array(
                    'M.' => 'M.',
                    'Mme' => 'Mme',
                )))
            ->add('Nom', TextType::class, array( 'attr' => array('placeholder' => 'NOM' )))
            ->add('Prenom', TextType::class, array( 'required' => false, 'mapped' => false, 'attr' => array('placeholder' => 'Prénom' )))
            ->add('Societe', TextType::class, array( 'attr' => array('placeholder' => 'Société' )))
            ->add('Telephone', TelType::class, array( 'attr' => array('placeholder' => 'Téléphone' )))
            ->add('EMail', TextType::class, array( 'attr' => array('placeholder' => 'exemple@mail.com' )))
            ->add('Rue', TextType::class, array( 'attr' => array('placeholder' => 'Rue' )))
            ->add('Complement', TextType::class, array( 'required' => false, 'attr' => array('placeholder' => 'Complément' )))
            ->add('CP', IntegerType::class, array( 'attr' => array('placeholder' => 'Code Postal' )))
            ->add('Ville', TextType::class, array( 'attr' => array('placeholder' => 'VILLE' )))
            ->add('Commercial', EntityType::class, array(
                'class' => 'AppBundle:Commercial',
                'choice_label' => 'acronyme'
            ))            
            ->add('Debut', DateType::class, array('widget' => 'single_text'))
            ->add('DevisType', ChoiceType::class, array(
                'choices' => array(
                    'Achat' => 'Achat',
                    'Location' => 'Location',
                    'Renseignement' => 'Renseignement',
                )))
            ->add('SystemType', ChoiceType::class, array(
                'choices' => array(
                    'QuizzBox Entreprise' => 'QB Entreprise',
                    'Version SSIAP - CQP' => 'SSIAP/CQP',
                    'QuizzBox Campus' => 'QB Campus',
                    'QuizzBox Education' => 'QB Education',
                    'QuizzBox Assemblée Générale' => 'QB Assemblé Générale',
                    'Autres' => 'Autres',
                )))
            ->add('NbController',   IntegerType::class, array( 'attr' => array( 'placeholder' => 'Nb Boitiers' )))
            ->add('Provenance', ChoiceType::class, array(
                'choices' => array(
                    'Recherche Internet (Google...)' => 'RI',
                    'Reseaux sociaux (Facebook)' => 'RS',
                    'Presse' => 'Presse',
                    'Recommandation' => 'Recommandation',
                    'Déja client' => 'Client',
                    'Autres' => 'Autres',
                )))
            ->add('Commentaire',      TextareaType::class, array('required' => false,))
            ->add('Id', IntegerType::class, array( 'required' => false, 'mapped' => false))
            ->add('Ajouter',      SubmitType::class)
        ;
        $addform = $formBuilder->getForm();

       if ($request->isMethod('POST')) {
            $addform->handleRequest($request);
            if ($addform->isValid()) {
                $affaire->setNom( $addform[ 'Nom' ]->getData().' '.$addform[ 'Prenom' ]->getData() );
                
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
            
            $oldestDate= '';
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
                
            }
            elseif($affaire->getEtat() === 'Fin'){
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
