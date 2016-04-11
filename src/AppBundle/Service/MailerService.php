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
     * @param $recipients
     */
    public function sendMail($subject, $body, $recipients)
    {
        $message = \Swift_Message::newInstance();
        $message
            ->setFrom($this->fromEmail)
            ->setTo($this->prepareRecipients($recipients))
            ->setSubject($subject)
            ->setBody($body);

        $this->mailer->send($message);
    }

    /**
     * @param array $recipients
     * @return array
     */
    private function prepareRecipients($recipients)
    {
        $result = [];
        foreach ($recipients as $data) {
            $result[$data['email']] = $data['name'];
        }

        return $result;
    }
}
