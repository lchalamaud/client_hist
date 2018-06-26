<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Commercial;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\JsonResponse;



class CommercialController extends Controller
{	
	/**
	 *@Route("/commercial/add/", name="commercial_add")
	 */
	public function addCommercial(Request $request)
	{
		$commercial = new commercial();

		$formBuilder = $this->get('form.factory')->createBuilder(FormType::class, $commercial);
		$formBuilder
			->add('Nom',      TextType::class)
			->add('Acronyme',     TextType::class)
			->add('Couleur',   IntegerType::class)
			->add('Ajouter',      SubmitType::class)
		;
		$form = $formBuilder->getForm();

		if ($request->isMethod('POST')) {
			$form->handleRequest($request);

			if ($form->isValid()) {
				$em = $this->getDoctrine()->getManager();
				$em->persist($commercial);
				$em->flush();

				$request->getSession()->getFlashBag()->add('notice', 'Nouveau commercial ajouté');

				return $this->redirectToRoute('valid');
			}
		}
		return $this->render('Default/commercialForm.html.twig', array('form' => $form->createView(),
		));
	}

	/**
	*@Route("/json/commercial/", name="json_commercial")
	*/
	public function jsonCommercial()
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
		$table = [ 'commercial' => $commercialTab];

		$response = new JsonResponse();

        return $response->setData($table);
	}

	/**
	*@Route("/commercial/detail/", name="commercial_detail")
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
}