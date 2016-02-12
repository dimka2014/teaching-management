<?php

namespace AppBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;

class ChildRepository extends EntityRepository
{
    /**
     * @param $page
     * @param $size
     * @return Pagerfanta
     */
    public function listPaginated($page, $size)
    {
        $result = new Pagerfanta(new DoctrineORMAdapter(
            $this
                ->createQueryBuilder('ch')
                ->orderBy('ch.name', 'ASC')
        ));

        $result->setMaxPerPage($size);
        $result->setCurrentPage($page);

        return $result;
    }
}
