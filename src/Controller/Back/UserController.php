<?php

namespace App\Controller\Back;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Form\UserType;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class UserController
 * @package App\Controller\Back
 *
 * Contrôleur de la partie "Utilisateur" du back-office
 */
class UserController extends Controller
{

    /**
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param UserInterface $user
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     *
     * Affiche l'interface d'édition de l'administrateur avec un aperçu et un formulaire d'édition.
     */
    public function adminUserEditAction(Request $request, UserPasswordEncoderInterface $passwordEncoder, UserInterface $user){
        $em = $this->getDoctrine()->getManager();

        // Récupération de l'id de l'utilisateur et de l'entité correspondante
        $adminUserId = $user->getId();
        $adminUser = $em->getRepository('App:User')->find($adminUserId);


        $form = $this->createForm(UserType::class, $adminUser);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Encodage du mot de passe
            $password = $passwordEncoder->encodePassword($adminUser, $adminUser->getPassword());
            $adminUser->setPassword($password);

            $em->flush();

            $this->get('session')->getFlashbag()->add('notice', "Le compte a été mis à jour" );
            return $this->redirectToRoute('cm_back_admin_user_edit');
        }
        return $this->render('Back/User/edit.html.twig', array('user' => $adminUser, "form" => $form->createView()));
    }



}
