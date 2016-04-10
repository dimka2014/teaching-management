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
}
