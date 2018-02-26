<?php

namespace ClubsBundle\Controller;

use AppBundle\Entity\Club;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Club controller.
 *
 * @Route("club")
 */
class ClubController extends Controller
{
    /**
     * Lists all club entities.
     *
     * @Route("/all", name="club_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $clubs = $em->getRepository('AppBundle:Club')->findBy(['etat'=>true]);
        return $this->render('club/index.html.twig', array(
            'clubs' => $clubs,
        ));
    }

    /**
     * Lists all club entities.
     *
     * @Route("/{type}", name="club_index_byType")
     * @Method("GET")
     */
    public function filterindexAction($type)
    {
        $em = $this->getDoctrine()->getManager();
        $clubs = $em->getRepository('AppBundle:Club')->findBy(['type' => $type,'etat'=>true]);
        return $this->render('club/index.html.twig', array(
            'clubs' => $clubs,
        ));
    }

    /**
     * Creates a new club entity.
     *
     * @Route("/addClub/new", name="add_Club")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $club = new Club();
        $form = $this->createForm('ClubsBundle\Form\ClubType', $club);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $club->getImage();

            // Generate a unique name for the file before saving it
            $fileName = md5(uniqid()) . '.' . $file->guessExtension();

            // Move the file to the directory where brochures are stored
            $file->move(
                $this->getParameter('brochures_directory'),
                $fileName
            );

            // Update the 'brochure' property to store the PDF file name
            // instead of its contents
            $club->setImage($fileName);

            $em = $this->getDoctrine()->getManager();
            $club->setEtat(false);
            $em->persist($club);
            $em->flush();

            return $this->redirectToRoute('club_show', array('id' => $club->getId()));
        }

        return $this->render('club/new.html.twig', array(
            'club' => $club,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a club entity.
     *
     * @Route("/{id}", name="club_show")
     * @Method("GET")
     */
    public function showAction(Club $club)
    {
        $deleteForm = $this->createDeleteForm($club);

        return $this->render('club/show.html.twig', array(
            'club' => $club,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing club entity.
     *
     * @Route("/{id}/edit", name="club_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Club $club)
    {
        $deleteForm = $this->createDeleteForm($club);
        $editForm = $this->createForm('ClubsBundle\Form\ClubType', $club);
        $editForm->handleRequest($request);
        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $file = $club->getImage();

            // Generate a unique name for the file before saving it
            $fileName = md5(uniqid()) . '.' . $file->guessExtension();

            // Move the file to the directory where brochures are stored
            $file->move(
                $this->getParameter('brochures_directory'),
                $fileName
            );

            // Update the 'brochure' property to store the PDF file name
            // instead of its contents
            $club->setImage($fileName);

            if ($editForm->isSubmitted() && $editForm->isValid()) {
                $this->getDoctrine()->getManager()->flush();

                return $this->redirectToRoute('club_edit', array('id' => $club->getId()));
            }


        }
        return $this->render('club/edit.html.twig', array(
            'club' => $club,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a club entity.
     *
     * @Route("/{id}", name="club_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Club $club)
    {
        $form = $this->createDeleteForm($club);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($club);
            $em->flush();
        }

        return $this->redirectToRoute('club_index');
    }

    /**
     * Creates a form to delete a club entity.
     *
     * @param Club $club The club entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Club $club)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('club_delete', array('id' => $club->getId())))
            ->setMethod('DELETE')
            ->getForm();
    }
}
