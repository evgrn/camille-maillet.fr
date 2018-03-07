<?php
namespace App\Controller\Front;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;


class HomeController extends Controller{

    public function indexAction(Request $request){
        return $this->render('Front/Home/index.html.twig');
    }

}