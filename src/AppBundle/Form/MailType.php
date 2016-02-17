<?php

namespace AppBundle\Form;

use AppBundle\Entity\Section;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;

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

        if ($options['with_section']) {
            $builder->add('section', 'entity', [
                'class' => Section::class,
                'constraints' => new NotNull(),
                'label' => 'mail.section'
            ]);
        }
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'with_section' => false
        ]);
    }
    public function getName()
    {
        return 'mail_type';
    }
}
