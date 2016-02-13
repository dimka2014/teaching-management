<?php

namespace AppBundle\Form;

use AppBundle\Entity\Child;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChildType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text', [
                'label' => 'children.form.name'
            ])
            ->add('parentName', 'text', [
                'label' => 'children.form.parent'
            ])
            ->add('parentPhone', 'text', [
                'label' => 'children.form.parent_phone'
            ])
            ->add('parentEmail', 'email', [
                'label' => 'children.form.parent_email'
            ])
            ->add('lessonPrice', 'integer', [
                'label' => 'children.form.leson_price'
            ])
            ->add('sections', null, [
                'label' => 'children.form.sections'
            ])
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Child'
        ));
    }
}
