<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Section;
use AppBundle\Form\SectionType;

/**
 * Section controller.
 *
 * @Route("/admin/sections")
 */
class SectionController extends Controller
{
    /**
     * Lists all Section entities.
     *
     * @Route("/", name="section_index")
     * @Method("GET")
     * @Template
     */
    public function indexAction()
    {
        $section = new Section();
        $form = $this->createForm('AppBundle\Form\SectionType', $section);

        return [
            'sections' => $this->getDoctrine()->getRepository('AppBundle:Section')->findAll(),
            'form' => $form->createView()
        ];
    }

    /**
     * Creates a new Section entity.
     *
     * @Route("/new", name="section_new")
     * @Method({"POST"})
     */
    public function newAction(Request $request)
    {
        $section = new Section();
        $form = $this->createForm('AppBundle\Form\SectionType', $section);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($section);
            $em->flush();

            $this->addFlash('success', $this->get('translator.default')->trans('flash_messages.section_created'));
        }

        return $this->redirectToRoute('section_index');
    }

    /**
     * Displays a form to edit an existing Section entity.
     *
     * @Route("/{id}/edit", name="section_edit")
     * @Method({"GET", "POST"})
     * @Template
     */
    public function editAction(Request $request, Section $section)
    {
        $deleteForm = $this->createDeleteForm($section);
        $editForm = $this->createForm('AppBundle\Form\SectionType', $section);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($section);
            $em->flush();
            $this->addFlash('success', $this->get('translator.default')->trans('flash_messages.edited'));

            return $this->redirectToRoute('section_edit', ['id' => $section->getId()]);
        }

        return [
            'section' => $section,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ];
    }

    /**
     * Deletes a Section entity.
     *
     * @Route("/{id}", name="section_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Section $section)
    {
        $form = $this->createDeleteForm($section);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($section);
            $em->flush();
            $this->addFlash('success', $this->get('translator.default')->trans('flash_messages.deleted'));
        }

        return $this->redirectToRoute('section_index');
    }

    /**
     * Creates a form to delete a Section entity.
     *
     * @param Section $section The Section entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Section $section)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('section_delete', array('id' => $section->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
