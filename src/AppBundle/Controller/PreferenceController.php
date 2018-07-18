<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class PreferenceController extends Controller
{
    /**
     *@Route("/pref/save/", name="pref_save")
     */
    public function PreferenceSave(Request $request){

        $em = $this->getDoctrine()->getManager();

        $user = $this->getUser();

        $preference = $user->getPreference();

        $json = json_decode($request->getContent());

        $preference->setTemps($json->timeStep);
        $preference->setComm($json->commercial);
        $preference->setEn_cours($json->enCours);
        $preference->setOublie($json->oublie);
        $preference->setSuspendu($json->suspendu);
        $preference->setFin($json->fin);
        $preference->setSigne($json->signe);
        $preference->setSigneEC($json->signEC);

        $em->persist($preference);
        $em->flush();

        $response = new JsonResponse();

        return $response->setData(array(
            'rsp' => array(
                'temps' => $preference->getTemps(),
                'comm' => $preference->getComm(),
                'enCours' => $preference->getEn_cours(),
                'oublie' => $preference->getOublie(),
                'suspendu' =>$preference->getSuspendu(),
                'fin' =>$preference->getFin(),
                'sign' =>$preference->getSigne(),
                'signEC' =>$preference->getSigneEC()
            )
        ));
    }
}