<?php

namespace AppBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use FOS\UserBundle\Model\UserInterface;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;

class UserRepository extends EntityRepository
{
    public function getAllPaginatedUsers($page, $size)
    {
        $result = new Pagerfanta(new DoctrineORMAdapter(
            $this
                ->createQueryBuilder('us')
        ));

        $result->setMaxPerPage($size);
        $result->setCurrentPage($page);

        return $result;
    }

    public function getNewPaginatedUsers($page, $size)
    {
        $result = new Pagerfanta(new DoctrineORMAdapter(
            $this
                ->createQueryBuilder('us')
                ->leftJoin('us.children', 'child')
                ->where('child IS NULL')
                ->andWhere('us.roles NOT LIKE :role')
                ->setParameter('role', '%' . UserInterface::ROLE_SUPER_ADMIN . '%')
        ));

        $result->setMaxPerPage($size);
        $result->setCurrentPage($page);

        return $result;
    }
}
