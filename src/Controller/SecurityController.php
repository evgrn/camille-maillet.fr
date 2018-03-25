<?php


namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class SecurityController
 * @package App\Controller
 *
 * Contrôleur affichant le formulaire de connexion.
 */
class SecurityController extends Controller
{
    public function loginAction(Request $request)
    {



        $authenticationUtils = $this->get('security.authentication_utils');

        return $this->render('Security/login.html.twig', array(
            'last_username' => $authenticationUtils->getLastUsername(),
            'error'         => $authenticationUtils->getLastAuthenticationError(),
        ));
    }
}
