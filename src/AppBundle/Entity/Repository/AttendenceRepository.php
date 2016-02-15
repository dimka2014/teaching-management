<?php

namespace AppBundle\Entity\Repository;

use AppBundle\Entity\Child;
use AppBundle\Entity\Section;
use Doctrine\ORM\EntityRepository;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;

class AttendenceRepository extends EntityRepository
{
    /**
     * @param $page
     * @param $size
     * @return Pagerfanta
     */
    public function listPaginatedByChild(Child $child, $page, $size)
    {
        $result = new Pagerfanta(new DoctrineORMAdapter(
            $this
                ->createQueryBuilder('at')
                ->select('at, les, sec')
                ->innerJoin('at.lesson', 'les')
                ->innerJoin('les.section', 'sec')
                ->orderBy('les.time', 'DESC')
                ->where('at.child = :child')
                ->setParameter('child', $child)
        ));

        $result->setMaxPerPage($size);
        $result->setCurrentPage($page);

        return $result;
    }
}
