<?php

namespace AppBundle\Form;

use AppBundle\Entity\Child;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LessonAttendencesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $sectionId = $options['section_id'];
        $builder->add('childs', 'entity', [
            'class' => Child::class,
            'multiple' => true,
            'expanded' => true,
            'label' => 'lesson.attendence',
            'query_builder' => function (EntityRepository $er) use ($sectionId) {
                return $er
                    ->createQueryBuilder('ch')
                    ->orderBy('ch.name', 'ASC')
                    ->innerJoin('ch.sections', 'sec')
                    ->where('sec.id = :sectionId')
                    ->setParameter('sectionId', $sectionId);
            },
        ]);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'AppBundle\Entity\Lesson',
            'section_id' => null
        ]);
    }

    public function getName()
    {
        return 'lesson_attendences';
    }
}
