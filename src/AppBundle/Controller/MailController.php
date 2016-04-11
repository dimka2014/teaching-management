<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/admin/mail")
 */
class MailController extends Controller
{
    /**
     * @Route("/all", name="send_mail_all")
     * @Method({"GET", "POST"})
     * @Template(template="AppBundle:Mail:mail.html.twig")
     */
    public function allAction(Request $request)
    {
        $repository = $this->getDoctrine()->getRepository('AppBundle:Child');

        return $this->handleForm($request, false, function () use ($repository) {
            return $repository->getParentsEmailsAndNames(null);
        });
    }

    /**
     * @Route("/section", name="send_mail_section")
     * @Method({"GET", "POST"})
     * @Template(template="AppBundle:Mail:mail.html.twig")
     */
    public function sectionAction(Request $request)
    {
        $repository = $this->getDoctrine()->getRepository('AppBundle:Child');

        return $this->handleForm($request, true, function ($section) use ($repository) {
            return $repository->getParentsEmailsAndNames($section);
        });
    }

    /**
     * @Route("/teachers", name="send_mail_teachers")
     * @Method({"GET", "POST"})
     * @Template(template="AppBundle:Mail:mail.html.twig")
     */
    public function teachersAction(Request $request)
    {
        $repository = $this->getDoctrine()->getRepository('AppBundle:Teacher');

        return $this->handleForm($request, false, function () use ($repository) {
            return $repository->findAllNamesAndEmails();
        });
    }

    private function handleForm(Request $request, $withSection, $getRecipients)
    {
        $form = $this->createForm('AppBundle\Form\MailType', null, ['with_section' => $withSection]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->get('app_bundle.mailer_service')->sendMail(
                $form->get('subject')->getData(),
                $form->get('body')->getData(),
                $getRecipients($withSection ? $form->get('section')->getData() : null)
            );
            $this->addFlash('success', $this->get('translator.default')->trans('flash_messages.message_sended'));

            return $this->redirectToRoute('child_index');
        }

        return [
            'form' => $form->createView(),
        ];
    }
}
