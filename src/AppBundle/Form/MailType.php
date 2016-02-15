<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class MailType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('subject', 'text', [
                'constraints' => new NotBlank(),
                'label' => 'mail.subject'
            ])
            ->add('body', 'textarea', [
                'constraints' => new NotBlank(),
                'label' => 'mail.body'
            ]);
    }

    public function getName()
    {
        return 'mail_type';
    }
}
