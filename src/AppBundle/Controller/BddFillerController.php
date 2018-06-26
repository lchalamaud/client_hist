<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Affaire;
use AppBundle\Entity\Tache;
use AppBundle\Entity\Commercial;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class BddFillerController extends Controller
{
    /**
     *@Route("/affaire/add/{number}", name="affaire_filler")
     */
    public function AffaireFiller($number)
    {
        /*  Constante   */
        $devis = array('Achat', 'Location', 'Renseignement');
        $company = array('Valeo', 'Michelin', 'CGI', 'Valeo', 'UCA', 'NextMedia', 'Limagrin', 'Constelium', 'ERDF', 'Airbus', 'Thalès', 'Conseil Générale', 'Crédit Agricole');
        $prHomme = array('Pierre', 'Paul', 'Jacques', 'Patrick', 'Serge', 'Francois', 'Jean', 'Michel', 'Albert', 'Alain', 'Marcel');
        $prFemme = array('Sophie', 'Marie', 'Léa', 'Sarah', 'Céline', 'Anne', 'Emma', 'Patricia');
        $nom = array('MARTIN', 'THOMAS', 'PETIT', 'DURAND', 'MOREAU', 'LEFEBVRE', 'LEROY', 'ROUX', 'MOREL', 'FOURNIER', 'GIRARD', 'BONNET', 'DUPONT', 'LAMBERT', 'FAURE');
        $address = array('PARIS', 'MARSEILLE', 'LYON', 'CLERMONT-FERRAND', 'TOULOUSE', 'BORDEAUX', 'LILLE', 'NANTE', 'NANCY', 'STRASBOURG', 'NICE', 'DIJON', 'BIARRITZ', 'LA ROCHELLE', 'BREST', 'LE HAVRE', 'METZ', 'GRENOBLE', 'NEVERS');
        $type = array('Entreprise', 'SSIAP-CQP', 'Campus', 'Education', 'Assemblée Générale');
        $provenance = array('RI', 'RS', 'Presse', 'Recommandation', 'Client', 'Autre');
        $info = array('', 'Bonjour, nous souhaitons organiser un séminaire pour 200 personnes, Il nous faudrait donc 200 voir 250 appareils. Je reste disponible au 06 07 08 09 10. Merci', 'Notre entreprise prépare une journée decouverte, avec activité vote interactif. Nous aurions besoin de 50 boitiers de vote. Quel serait-votre prix?', 'Salut, je suis indépendant, je monte une auto école, il me faudrait des boitier interactif. Une 50aine serait bien. Merci');
        $commentaire = array('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed non risus. Suspendisse lectus tortor, dignissim sit amet, adipiscing nec, ultricies sed, dolor. Cras elementum ultrices diam. Maecenas ligula massa, varius a, semper congue, euismod non, mi. Proin porttitor, orci nec nonummy molestie, enim est eleifend mi, non fermentum diam nisl sit amet erat. Duis semper. Duis arcu massa, scelerisque vitae, consequat in, pretium a, enim. Pellentesque congue. Ut in risus volutpat libero pharetra tempor. Cras vestibulum bibendum augue. Praesent egestas leo in pede. Praesent blandit odio eu enim. Pellentesque sed dui ut augue blandit sodales. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Aliquam nibh. Mauris ac mauris sed pede pellentesque fermentum. Maecenas adipiscing ante non diam sodales hendrerit.', 'Ut velit mauris, egestas sed, gravida nec, ornare ut, mi. Aenean ut orci vel massa suscipit pulvinar. Nulla sollicitudin. Fusce varius, ligula non tempus aliquam, nunc turpis ullamcorper nibh, in tempus sapien eros vitae ligula. Pellentesque rhoncus nunc et augue. Integer id felis. Curabitur aliquet pellentesque diam. Integer quis metus vitae elit lobortis egestas. Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Morbi vel erat non mauris convallis vehicula. Nulla et sapien. Integer tortor tellus, aliquam faucibus, convallis id, congue eu, quam. Mauris ullamcorper felis vitae erat. Proin feugiat, augue non elementum posuere, metus purus iaculis lectus, et tristique ligula justo vitae magna.', 'Aliquam convallis sollicitudin purus. Praesent aliquam, enim at fermentum mollis, ligula massa adipiscing nisl, ac euismod nibh nisl eu lectus. Fusce vulputate sem at sapien. Vivamus leo. Aliquam euismod libero eu enim. Nulla nec felis sed leo placerat imperdiet. Aenean suscipit nulla in justo. Suspendisse cursus rutrum augue. Nulla tincidunt tincidunt mi. Curabitur iaculis, lorem vel rhoncus faucibus, felis magna fermentum augue, et ultricies lacus lorem varius purus. Curabitur eu amet.');
        $rue = array('Impasse', 'Rue', 'Avenue', 'Boulevard', 'Place', 'Route');
        $de = array('du', 'des', 'de la', '');
        $fin = array('Marechal Bouginat', 'mer', 'Boucher', 'sans fin', 'soif', 'Michel Albert', 'St Hyppolite', 'refectoire', 'dortoire allongé');
        $ext_mail = array('.com', '.fr', '.net');
        $complement = array('Service Informatique', 'Batiment B', 'Pole Communication', '3 Etage Couloir C', 'Unité de recherche');
        $system = array('QB Entreprise', 'SSIAP/CQP', 'QB Campus', 'QB Education', 'QB Assemblé Générale', 'Autres');
        $etat = array('En Cours', 'Fin', 'Signé', 'Suspendu');

        $repository = $this->getDoctrine()->getManager()->getRepository('AppBundle:Commercial');
        $listCommercials = $repository->findAll();

        /*  Classe Creation   */
        for ($i = 1; $i <= $number; $i++)
        {
            $test = new Affaire();
            $em = $this->getDoctrine()->getManager();
            if(rand(0, 1)){
                $test->setCivilite('M.');
                $test->setNom($nom[rand(0, 14)].' '.$prHomme[rand(0, 10)]);
            }else{
                $test->setCivilite('Mme');
                $test->setNom($nom[rand(0, 14)].' '.$prFemme[rand(0, 7)]);
            }

            $test->setSociete($company[rand(0, 12)]);
            $test->setTelephone('0'.rand(100000000,899999999));
            $test->setRue(rand(1, 100).' '.$rue[rand(0, 5)].' '.$de[rand(0, 3)].' '.$fin[rand(0, 8)]);
            if(rand(0, 1)){
                $test->setComplement($complement[rand(0, 4)]);
            }
            $test->setCP(10*rand(1000, 9699));
            $test->setVille($address[rand(0, 18)]);
            $test->setEmail($test->getNom().'@'.$test->getSociete().$ext_mail[rand(0, 2)]);
            $test->setDebut(new \DateTime(rand(1,30).'-'.rand(1,12).'-'.rand(2017,2019)));
            $test->setCommentaire($commentaire[rand(0, 2)]);
            $test->setDevisType($devis[rand(0, 2)]);
            $test->setSystemType($system[rand(0, 5)]);
            $test->setNbController(10*rand(5, 20));
            $test->setProvenance($provenance[rand(0, 5)]);
            $test->setCommercial($listCommercials[rand(0, 3)]);
            $test->setEtat($etat[rand(0, 3)]);

            /*  Submit data   */
            $em->persist($test);
            $em->flush();
        }

        $num = ['number' => $number];
        return $this->render('Default/waiter.html.twig', $num);
    }

}
