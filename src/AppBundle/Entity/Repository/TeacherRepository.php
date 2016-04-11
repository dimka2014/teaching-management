<?php

namespace AppBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;

class TeacherRepository extends EntityRepository
{
    public function getAllPaginatedTeachers($page, $size)
    {
        $result = new Pagerfanta(new DoctrineORMAdapter($this->createQueryBuilder('t')));
        $result->setMaxPerPage($size);
        $result->setCurrentPage($page);

        return $result;
    }

    public function findAllNamesAndEmails()
    {
        return $this
            ->createQueryBuilder('t')
            ->select('t.email AS email, t.name AS name')
            ->distinct(true)
            ->getQuery()
            ->getArrayResult();
    }
}
