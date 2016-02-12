<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Child;
use AppBundle\Form\ChildType;

/**
 * Child controller.
 *
 * @Route("/admin/children")
 */
class ChildController extends Controller
{
    /**
     * Lists all Child entities.
     *
     * @Route("/", name="child_index")
     * @Method("GET")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $children = $this
            ->getDoctrine()
            ->getRepository('AppBundle:Child')
            ->listPaginated($request->get('page', 1), $this->getParameter('page_size'));

        return [
            'children' => $children
        ];
    }

    /**
     * Creates a new Child entity.
     *
     * @Route("/new", name="child_new")
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function newAction(Request $request)
    {
        $child = new Child();
        $form = $this->createForm('AppBundle\Form\ChildType', $child);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($child);
            $em->flush();
            $this->addFlash('success', $this->get('translator.default')->trans('flash_messages.child_created'));

            return $this->redirectToRoute('child_index');
        }

        return [
            'child' => $child,
            'form' => $form->createView(),
        ];
    }

    /**
     * Finds and displays a Child entity.
     *
     * @Route("/{id}", name="child_show")
     * @Method("GET")
     * @Template
     */
    public function showAction(Child $child)
    {
        $deleteForm = $this->createDeleteForm($child);

        return [
            'child' => $child,
            'delete_form' => $deleteForm->createView(),
        ];
    }

    /**
     * Displays a form to edit an existing Child entity.
     *
     * @Route("/{id}/edit", name="child_edit")
     * @Method({"GET", "POST"})
     * @Template
     */
    public function editAction(Request $request, Child $child)
    {
        $deleteForm = $this->createDeleteForm($child);
        $editForm = $this->createForm('AppBundle\Form\ChildType', $child);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($child);
            $em->flush();
            $this->addFlash('success', $this->get('translator.default')->trans('flash_messages.edited'));

            return $this->redirectToRoute('child_edit', array('id' => $child->getId()));
        }

        return [
            'child' => $child,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ];
    }

    /**
     * Deletes a Child entity.
     *
     * @Route("/{id}", name="child_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Child $child)
    {
        $form = $this->createDeleteForm($child);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($child);
            $em->flush();
            $this->addFlash('success', $this->get('translator.default')->trans('flash_messages.deleted'));
        }

        return $this->redirectToRoute('child_index');
    }

    /**
     * Creates a form to delete a Child entity.
     *
     * @param Child $child The Child entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Child $child)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('child_delete', array('id' => $child->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
