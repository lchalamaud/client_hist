<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class TestController extends Controller
{
    /**
     *@Route("/test/", name="test")
     */
    public function TestIndex(){

        

        return $this->render('Default/test.html.twig');
    }
}