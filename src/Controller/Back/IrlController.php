<?php

namespace App\Controller\Back;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Irl;
use App\Form\IrlType;
use App\Form\IrlEditType;
use App\Service\ImageManager;
use Symfony\Component\HttpFoundation\File\File;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use App\Service\PublicationToggler;

/**
 * Class IrlController
 * @package App\Controller\Back
 *
 * Contrôleur de la partie "Irl" du back-offica
 */
class IrlController extends Controller
{

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * Affiche la liste des éléments IRL.
     */
    public function listAction(){
        $em = $this->getDoctrine()->getManager();
        $irl = $em->getRepository('App:Irl')->findAll();

        return $this->render('Back/Irl/list.html.twig', array('irl' => $irl));
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * Affiche la version dashboard de la liste des éléments IRL.
     */
    public function shortListAction(){
        $em = $this->getDoctrine()->getManager();
        $irl = $em->getRepository('App:Irl')->findAll();

        return $this->render('Back/Irl/short-list.html.twig', array('irl' => $irl));
    }

    /**
     * @param Request $request
     * @param ImageManager $imageManager
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     *
     * Affiche un formulaire d'ajout d'élément IRL.
     */
    public function addAction(Request $request, ImageManager $imageManager)
    {
        $em = $this->getDoctrine()->getManager();

        $irl = new Irl();
        $form = $this->createForm(IrlType::class, $irl);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $irl->setImage(
                $imageManager->uploadImage($irl->getImage())
            );
            $em->persist($irl);
            $em->flush();
            $this->get('session')->getFlashbag()->add('notice', "L'élément IRL a été ajouté" );
            return $this->redirectToRoute('cm_back_irl_list');
        }

        return $this->render('Back/Irl/add.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @param Request $request
     * @param ImageManager $imageManager
     * @param Irl $irl
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     *
     * Affiche l'aperçu de l'entité IRL entrée en paramètre et un formulaire permettant de l'éditer.
     */
    public function singleAction(Request $request, ImageManager $imageManager, Irl $irl){

        // Récupération des entités
        $em = $this->getDoctrine()->getManager();
        $irlPreview = clone $irl;

        // Afin de ne pas avoir de problème avec le champ image du formulaire, on clone l'objet $irl.
        // Son clone $irlPreview servira à hydrater la partie aperçue
        // L'objet $irl initial, qui servira à hydrater le formulaire, se voit remplacer sa propriété $image par un objet de type File attendu par le formulaire.
        $originalImage = $irl->getImage();
        $irl->setImage(new File($this->getParameter('images_directory').'/'.$originalImage));

        // Gestion du formulaire
        $form = $this->createForm(IrlEditType::class, $irl);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Mise à jour de l'image
            $irl->setImage(
                $imageManager->manageImageUpdate($irl->getImage(), $originalImage)
            );
            $em->flush();
            $this->get('session')->getFlashbag()->add('notice', "L'élément IRL a été modifié" );
            return $this->redirectToRoute('cm_back_irl_list');
        }

        // Génération de la vue
        return $this->render('Back/Irl/single.html.twig', array('irl' => $irlPreview, 'form' => $form->createView()));

    }

    /**
     * @param Request $request
     * @param ImageManager $imageManager
     * @param Irl $irl
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     *
     * Suppression de l'ntité IRL entrée en paramètre.
     */
    public function deleteAction(Request $request, ImageManager $imageManager, Irl $irl)
    {
        $em = $this->getDoctrine()->getManager();

        $imageManager->delete($irl->getImage()); // Suppression de l'image associée
        $em->remove($irl);
        $em->flush();
        $this->get('session')->getFlashbag()->add('notice', "L'élément IRL a été supprimé" );

        return $this->redirectToRoute('cm_back_irl_list');
    }

    /**
     * @param Irl $irl
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     *
     * Change le statut de l'entité $irl entrée en paramètre ( publié / non-publié)
     */
    public function togglePublishedAction(PublicationToggler $toggler, Irl $irl){
        $this->get('session')->getFlashbag()
            ->add('notice', $toggler->toggle($irl) );
        return $this->redirectToRoute('cm_back_irl_list');
    }

}