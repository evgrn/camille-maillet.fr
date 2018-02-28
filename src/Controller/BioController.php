<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class BioController extends Controller
{

    public function defaultAction()
    {
        return $this->render('Bio/default.html.twig');
    }


    public function deleteAction($id){

    }
}
