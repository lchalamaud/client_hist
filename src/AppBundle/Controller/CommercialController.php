<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Commercial;
use AppBundle\Entity\Tache;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ColorType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\JsonResponse;



class CommercialController extends Controller
{	
	/**
	* @Route("/commercial/", name="commercial_detail")
	* @Security("has_role('ROLE_USER')")
	*/
	public function viewCommercial()
	{
		$repository = $this->getDoctrine()->getManager()->getRepository('AppBundle:Commercial');

		$listCommercials = $repository->findAll();

		$commercialTab = array();
		foreach ($listCommercials as $commercial) {
			$com = array(
				'nom' => $commercial->getNom(),
				'acronyme' => $commercial->getAcronyme(),
				'couleur' => $commercial->getCouleur()
			);

			$commercialTab[] = $com;
		}

		$table = [ 'commercialTab' => $commercialTab];

		return $this->render('Default/commercialTable.html.twig', $table);
	}

	/**
	 * @Route("/commercial/add/", name="commercial_add")
	 * @Security("has_role('ROLE_ADMIN')")
	 */
	public function addCommercial(Request $request)
	{
		$commercial = new commercial();

		$formBuilder = $this->get('form.factory')->createBuilder(FormType::class, $commercial);
		$formBuilder
			->add('Nom',      TextType::class)
			->add('Acronyme',     TextType::class)
			->add('Couleur',   ColorType::class)
			->add('Ajouter',      SubmitType::class, array( 'label' => 'button.add'))
		;
		$form = $formBuilder->getForm();

		if ($request->isMethod('POST')) {
			$form->handleRequest($request);

			if ($form->isValid()) {
				$em = $this->getDoctrine()->getManager();
				$em->persist($commercial);
				$em->flush();

				$request->getSession()->getFlashBag()->add('notice', 'Nouveau commercial ajouté');

				return $this->redirectToRoute('commercial_detail');
			}
		}
		return $this->render('Default/commercialForm.html.twig', array('form' => $form->createView(),
		));
	}

	/**
	 * @Route("/commercial/modif/{acronyme}/", name="commercial_modif")
	 * @Security("has_role('ROLE_ADMIN')")
	 */
	public function modifCommercial($acronyme, Request $request)
	{
		$em = $this->getDoctrine()->getManager();
		$commercial = $em->getRepository('AppBundle:Commercial')->find($acronyme);

		$formBuilder = $this->get('form.factory')->createBuilder(FormType::class, $commercial);
		$formBuilder
			->add('Nom',      TextType::class)
			->add('Acronyme',     TextType::class)
			->add('Couleur',   ColorType::class)
			->add('Ajouter',      SubmitType::class, array( 'label' => 'button.modify'))
		;
		$form = $formBuilder->getForm();

		if ($request->isMethod('POST')) {
			$form->handleRequest($request);

			if ($form->isValid()) {
				$em->persist($commercial);
				$em->flush();

				$request->getSession()->getFlashBag()->add('notice', 'Nouveau commercial ajouté');

				return $this->redirectToRoute('commercial_detail');
			}
		}
		return $this->render('Default/commercialForm.html.twig', array('form' => $form->createView(),
		));
	}

	/**
	* @Route("/commercial/del/", name="commercial_del")
	* @Security("has_role('ROLE_ADMIN')")
	*/
	public function delCommercial(Request $request)
	{
		$response = new JsonResponse();

		$em = $this->getDoctrine()->getManager();

		$commDel = $request->get('acronyme');

		/*	Suppression des taches lié au commercial	*/
		$rptTache = $em->getRepository('AppBundle:Tache');
		$listTaches = $rptTache->findBy(['commercial' => $commDel]);

        foreach ($listTaches as $tache) {
			$em->remove($tache);
        }


		$rptAffaire = $em->getRepository('AppBundle:Affaire');
		$listAffaire = $rptAffaire->findBy(['commercial' => $commDel]);
		if( !$listAffaire ){

			/*	Suppression du commercial 	*/
			$rptCommercial = $em->getRepository('AppBundle:Commercial');
	        $commercial = $rptCommercial->findOneBy(['acronyme' => $commDel]);
	        $em->remove($commercial);

	        $em->flush();
	        $response->setData(array('rsp' => 1));
	        
		}else{

			/*	Refus de suppression, commercial lié a au moins une affaire 	*/
			$response->setData(array('rsp' => 10));

		}
        

        return $response;
	}

	/**
	* @Route("/commercial/del/force", name="commercial_del_force")
	* @Security("has_role('ROLE_ADMIN')")
	*/
	public function delCommercialForce(Request $request)
	{
		/*****************************************************************************
		 **	Suppression des taches et affaires lié au commercial et du commercial	**
		 *****************************************************************************/
		
		$em = $this->getDoctrine()->getManager();

		$commDel = $request->get('acronyme');

		$rptTache = $em->getRepository('AppBundle:Tache');
		$listTaches = $rptTache->findBy(['commercial' => $commDel]);

        foreach ($listTaches as $tache) {
			$em->remove($tache);
        }

		$rptAffaire = $em->getRepository('AppBundle:Affaire');
		$listAffaire = $rptAffaire->findBy(['commercial' => $commDel]);
		
		foreach ($listAffaire as $affaire) {
			$em->remove($affaire);
        }

        $rptCommercial = $em->getRepository('AppBundle:Commercial');
        $commercial = $rptCommercial->findOneBy(['acronyme' => $commDel]);
        $em->remove($commercial);

        $em->flush();
        
        $response = new JsonResponse();

        return $response;
	}

	/**
	* @Route("/commercial/set/", name="commercial_set")
	* @Security("has_role('ROLE_USER')")
	*/
	public function setCommercial(Request $request)
	{
		$idAffaire = $request->get('idAffaire');
		$commercialAcronyme = $request->get('commercial');

		$em = $this->getDoctrine()->getManager();

		$affaire = $em->getRepository('AppBundle:Affaire')->find($idAffaire);

		$commercial = $em->getRepository('AppBundle:Commercial')->findOneBy(['acronyme'=> $commercialAcronyme]);

		$affaire->setCommercial($commercial);

		$em->persist($affaire);
		$em->flush();

		$response = new JsonResponse();

        return $response->setData(['couleur'=> $commercial->getCouleur()]);
	}
}