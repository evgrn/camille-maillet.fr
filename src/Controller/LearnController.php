<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class LearnController extends Controller
{
  
    public function listAction()
    {
        return $this->render('Learn/list.html.twig');
    }

    public function singleAction(Request $request, $id)
    {
       $idList = [1, 2, 3];
       if(!in_array($id, $idList)){
         $request->getSession()->getFlashBag()->add('error', "La technologie à apprendre n°$id n'existe pas.");
         return $this->redirectToRoute('cm_back_learn_list');
       }
       return $this->render('Learn/single.html.twig', array('id' => $id));
    }
}
