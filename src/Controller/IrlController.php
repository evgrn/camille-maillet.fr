<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class IrlController extends Controller
{

    public function listAction()
    {
        return $this->render('Irl/list.html.twig');
    }

    public function singleAction(Request $request, $id)
    {
       $idList = [1, 2, 3];
       if(!in_array($id, $idList)){
         $request->getSession()->getFlashBag()->add('error', "L'élément IRL n°$id n'existe pas.");
         return $this->redirectToRoute('cm_back_irl_list');
       }
       return $this->render('Irl/single.html.twig', array('id' => $id));
    }

    public function deleteAction($id){

    }
}
