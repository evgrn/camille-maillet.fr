<?php

namespace App\Controller\Back;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\UserType;
use Symfony\Component\Security\Core\User\UserInterface;


class UserController extends Controller
{


    public function adminUserEditAction(Request $request, UserPasswordEncoderInterface $passwordEncoder, UserInterface $user){
        $em = $this->getDoctrine()->getManager();
        $adminUserId = $user->getId();
        $adminUser = $em->getRepository('App:User')->find($adminUserId);


        $form = $this->createForm(UserType::class, $adminUser);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $password = $passwordEncoder->encodePassword($adminUser, $adminUser->getPassword());
            $adminUser->setPassword($password);
            $em->flush();

            $this->get('session')->getFlashbag()->add('notice', "Le compte a été mis à jour" );
            return $this->redirectToRoute('cm_back_admin_user_edit');
        }
        return $this->render('Back/User/edit.html.twig', array('user' => $adminUser, "form" => $form->createView()));
    }



}
