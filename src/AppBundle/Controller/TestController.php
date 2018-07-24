<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends Controller
{
    /**
     *@Route("/test/", name="test")
     */
    public function index()
    {
        
  


        return $this->render('Default/test.html.twig');
    }
}