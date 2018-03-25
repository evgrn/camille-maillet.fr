<?php

namespace App\Controller\Back;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Career;
use App\Form\CareerType;
use App\Service\PublicationToggler;


/**
 * Class CareerController
 * @package App\Controller\Back
 *
 * Contrôleur de la partie "Parcours" du back-office
 */
class CareerController extends Controller
{

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     *
     * Affiche la liste des éléments du parcours et un formulaire pour ajouter / éditer un de ces éléments.
     */
    public function listAction(Request $request, $id){

        // Récupération des entités
        $em = $this->getDoctrine()->getManager();
        $corporate = $em->getRepository('App:Career')->findByCategory('Entreprise');
        $formations = $em->getRepository('App:Career')->findByCategory('Formation');

        // Gestion du formulaire
        $career = ($id) ? $em->getRepository('App:Career')->find($id) : new Career();
        $form = $this->createForm(CareerType::class, $career);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em->persist($career);
            $em->flush();

            $notice= ($id) ? "L'élément du parcours a été modifié" : "L'élément a été ajouté au parcours";
            $this->get('session')->getFlashbag()->add('notice', $notice );


            return $this->redirectToRoute('cm_back_career_list');
        }

        // Génération de la vue
        return $this->render('Back/Career/list.html.twig',
            array(
                'corporate' => $corporate,
                'formations' => $formations,
                'career' => $career,
                'form' => $form->createView()
            ));
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * Affiche la liste des éléments du parcours dans le dashboard.
     */
    public function shortListAction(){
        $em = $this->getDoctrine()->getManager();
        $corporate = $em->getRepository('App:Career')->findByCategory('Entreprise');
        $formation = $em->getRepository('App:Career')->findByCategory('Formation');

        return $this->render('Back/Career/short-list.html.twig',
            array(
                'corporate' => $corporate,
                'formation' => $formation
            ));
    }

    /**
     * @param Request $request
     * @param Career $career
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     *
     * Supprime l'entité entrée en paramètre.
     */
    public function deleteAction(Request $request, Career $career)
    {
        $em = $this->getDoctrine()->getManager();

        $em->remove($career);
        $em->flush();
        $this->get('session')->getFlashbag()->add('notice', "L'élement a été retiré du parcours");
        return $this->redirectToRoute('cm_back_career_list');
    }

    /**
     * @param Career $career
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     *
     * Change le statut de l'élément ( publié / non-publié)
     */
    public function togglePublishedAction(Career $career, PublicationToggler $toggler){
        $this->get('session')->getFlashbag()
            ->add('notice', $toggler->toggle($career) );
        return $this->redirectToRoute('cm_back_career_list');

    }

}