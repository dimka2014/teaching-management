<?php

namespace AppBundle\Service;

use AppBundle\Entity\Section;
use Doctrine\ORM\EntityManager;

class MailerService
{
    private $em;

    private $mailer;

    private $fromEmail;

    public function __construct(EntityManager $em, \Swift_Mailer $mailer, $fromEmail)
    {
        $this->em = $em;
        $this->mailer = $mailer;
        $this->fromEmail = $fromEmail;
    }

    /**
     * @param $subject
     * @param $body
     * @param Section|null $section
     */
    public function sendMail($subject, $body, Section $section = null)
    {
        $message = \Swift_Message::newInstance();
        $message
            ->setFrom($this->fromEmail)
            ->setTo($this->getAllRecipients($section))
            ->setSubject($subject)
            ->setBody($body);

        $this->mailer->send($message);
    }

    /**
     * @param Section $section
     * @return array
     */
    private function getAllRecipients(Section $section = null)
    {
        $result = [];
        foreach ($this->em->getRepository('AppBundle:Child')->getParentsEmailsAndNames($section) as $data) {
            $result[$data['email']] = $data['name'];
        }

        return $result;
    }
}
