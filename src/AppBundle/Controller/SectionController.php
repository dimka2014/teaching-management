<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Lesson;
use Doctrine\ORM\EntityManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Form\Form;
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
     * @Route("/{id}/lessons", name="lessons_index_by_section")
     * @Method("GET")
     * @Template
     */
    public function lessonsIndexBySectionAction(Section $section, Request $request)
    {
        $form = $this->createForm('AppBundle\Form\LessonType', new Lesson());

        $lessons = $this
            ->getDoctrine()
            ->getRepository('AppBundle:Lesson')
            ->getPaginatedLessonsBySection($section, $request->get('page', 1), $this->getParameter('page_size'));

        return [
            'section' => $section,
            'lessons' => $lessons,
            'form' => $form->createView()
        ];
    }

    /**
     * Creates a new Lesson entity.
     *
     * @Route("/{id}/lessons/new", name="lesson_new")
     * @Method({"POST"})
     */
    public function newLessonAction(Section $section, Request $request)
    {
        $lesson = new Lesson();
        $form = $this->createForm('AppBundle\Form\LessonType', $lesson);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $lesson->setSection($section);
            $em = $this->getDoctrine()->getManager();
            $em->persist($lesson);
            $em->flush();

            $this->addFlash('success', $this->get('translator.default')->trans('flash_messages.lesson_created'));
        } else {
            foreach ($form->getErrors() as $error) {
                $this->addFlash('error', $error->getMessage());
            }
            foreach ($form->get('time')->getErrors() as $error) {
                $this->addFlash('error', $error->getMessage());
            }
        }

        return $this->redirectToRoute('lessons_index_by_section', ['id' => $section->getId()]);
    }

    /**
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
     * @Route("/{id}/lessons/{lessonId}", name="lesson_delete")
     * @ParamConverter("lesson", class="AppBundle:Lesson", options={"id" = "lessonId"})
     * @Method({"DELETE"})
     */
    public function deleteLessonAction(Section $section, Lesson $lesson)
    {
        $em = $this->getDoctrine()->getManager();
        $em->transactional(function (EntityManager $em) use ($lesson) {
            foreach ($lesson->getChilds() as $child) {
                $lesson->removeChild($child);
                $em->persist($child);
            }
            $em->remove($lesson);
        });

        $this->addFlash('success', $this->get('translator.default')->trans('flash_messages.lesson_removed'));

        return $this->render('AppBundle:Admin:layout.html.twig');
    }

    /**
     * @Route("/{id}/lessons/{lessonId}/attendences", name="lesson_attendences")
     * @ParamConverter("lesson", class="AppBundle:Lesson", options={"id" = "lessonId"})
     * @Method({"GET", "POST"})
     * @Template
     */
    public function lessonAttendencesAction(Section $section, Lesson $lesson, Request $request)
    {
        $form = $this->createForm('AppBundle\Form\LessonAttendencesType', $lesson, [
            'section_id' => $section->getId()
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $em->transactional(function(EntityManager $em) use ($lesson) {
                $em->persist($lesson);
            });

            $this->addFlash('success', $this->get('translator.default')->trans('flash_messages.edited'));
        }

        return [
            'section' => $section,
            'form' => $form->createView(),
        ];
    }

    /**
     * @param Section $section The Section entity
     * @return Form The form
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
