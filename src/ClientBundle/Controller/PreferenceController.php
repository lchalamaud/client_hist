<?php

namespace ClientBundle\Controller;

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
            ."\netat:".$request->get('etat'));



        $response = new JsonResponse();

        return $response->setData(array('rsp'=>'ok'));
    }
}