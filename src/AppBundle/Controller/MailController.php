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
     * @Route("/", name="send_mail")
     * @Method({"GET", "POST"})
     * @Template
     */
    public function mailAction(Request $request)
    {
        $withSection = $request->get('section', false);

        $form = $this->createForm('AppBundle\Form\MailType', null, [
            'with_section' => $withSection,
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->get('app_bundle.mailer_service')->sendMail(
                $form->get('subject')->getData(),
                $form->get('body')->getData(),
                $withSection ? $form->get('section')->getData() : null
            );
            $this->addFlash('success', $this->get('translator.default')->trans('flash_messages.message_sended'));

            return $this->redirectToRoute('child_index');
        }

        return [
            'form' => $form->createView(),
        ];
    }
}
