<?php

namespace AppBundle\Entity\Repository;

use AppBundle\Entity\Section;
use Doctrine\ORM\EntityRepository;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;

class LessonRepository extends EntityRepository
{
    public function getPaginatedLessonsBySection(Section $section, $page, $size)
    {
        $result = new Pagerfanta(new DoctrineORMAdapter(
            $this
                ->createQueryBuilder('p')
                ->orderBy('p.time', 'DESC')
                ->where('p.section = :section')
                ->setParameter('section', $section)
        ));

        $result->setMaxPerPage($size);
        $result->setCurrentPage($page);

        return $result;
    }
}
