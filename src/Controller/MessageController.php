<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class MessageController extends Controller
{

    public function listAction()
    {
        return $this->render('Message/list.html.twig');
    }

    public function singleAction(Request $request, $id)
    {
       $idList = [1, 2, 3];
       if(!in_array($id, $idList)){
         $request->getSession()->getFlashBag()->add('error', "Le message n°$id n'existe pas.");
         return $this->redirectToRoute('cm_back_message_list');
       }
       return $this->render('Message/single.html.twig', array('id' => $id));
    }

    public function deleteAction($id){

    }
}
