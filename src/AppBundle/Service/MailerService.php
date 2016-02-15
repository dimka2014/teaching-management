<?php

namespace AppBundle\Service;

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

    public function sendMail($subject, $body)
    {
        $message = \Swift_Message::newInstance();
        $message
            ->setFrom($this->fromEmail)
            ->setTo($this->getAllRecipients())
            ->setSubject($subject)
            ->setBody($body);

        $this->mailer->send($message);
    }

    /**
     * @return array
     */
    private function getAllRecipients()
    {
        $result = [];
        foreach ($this->em->getRepository('AppBundle:Child')->getAllParentsEmailsAndNames() as $data) {
            $result[$data['email']] = $data['name'];
        }

        return $result;
    }
}
