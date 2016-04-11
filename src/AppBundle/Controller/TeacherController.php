<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Teacher;
use AppBundle\Form\TeacherType;

/**
 * Teacher controller.
 *
 * @Route("/admin/teachers")
 */
class TeacherController extends Controller
{
    /**
     * Lists all Teacher entities.
     *
     * @Route("/", name="teacher_index")
     * @Method("GET")
     * @Template
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $teachers = $em
            ->getRepository('AppBundle:Teacher')
            ->getAllPaginatedTeachers($request->get('page', 1), $this->getParameter('page_size'));

        return [
            'teachers' => $teachers,
        ];
    }

    /**
     * Creates a new Teacher entity.
     *
     * @Route("/new", name="teacher_new")
     * @Method({"GET", "POST"})
     * @Template
     */
    public function newAction(Request $request)
    {
        $teacher = new Teacher();
        $form = $this->createForm('AppBundle\Form\TeacherType', $teacher);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($teacher);
            $em->flush();

            $this->addFlash('success', $this->get('translator.default')->trans('flash_messages.teacher_created'));

            return $this->redirectToRoute('teacher_index');
        }

        return [
            'form' => $form->createView(),
        ];
    }

    /**
     * Displays a form to edit an existing Teacher entity.
     *
     * @Route("/{id}/edit", name="teacher_edit")
     * @Method({"GET", "POST"})
     * @Template
     */
    public function editAction(Request $request, Teacher $teacher)
    {
        $deleteForm = $this->createDeleteForm($teacher);
        $editForm = $this->createForm('AppBundle\Form\TeacherType', $teacher);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($teacher);
            $em->flush();
            $this->addFlash('success', $this->get('translator.default')->trans('flash_messages.edited'));

            return $this->redirectToRoute('teacher_edit', ['id' => $teacher->getId()]);
        }

        return [
            'teacher' => $teacher,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ];
    }

    /**
     * Deletes a Teacher entity.
     *
     * @Route("/{id}", name="teacher_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Teacher $teacher)
    {
        $form = $this->createDeleteForm($teacher);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($teacher);
            $em->flush();
            $this->addFlash('success', $this->get('translator.default')->trans('flash_messages.deleted'));
        }

        return $this->redirectToRoute('teacher_index');
    }

    /**
     * Creates a form to delete a Teacher entity.
     *
     * @param Teacher $teacher The Teacher entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Teacher $teacher)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('teacher_delete', ['id' => $teacher->getId()]))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
