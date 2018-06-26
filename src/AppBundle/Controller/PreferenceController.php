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

        $publicResourcesFolderPath = $this->get('kernel')->getRootDir() . '/../web/preferences/';
        $filename = "userprofile.conf";

        $file = $publicResourcesFolderPath.$filename;
        file_put_contents($file,
            'temps:'.$request->get('timeStep')
            ."\ncomm:".$request->get('commercial')
            ."\nEn Cours:".$request->get('enCours')
            ."\nOublié:".$request->get('oublie')
            ."\nSuspendu:".$request->get('suspendu')
            ."\nFin:".$request->get('fin')
            ."\nSigné:".$request->get('signe')
        );

        $response = new JsonResponse();

        return $response->setData(array('rsp'=>'ok'));
    }
}