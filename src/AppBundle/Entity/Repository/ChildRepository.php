<?php

namespace AppBundle\Entity\Repository;

use AppBundle\Entity\Child;
use AppBundle\Entity\User;
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

    /**
     * @param User $user
     * @return Child[]
     */
    public function getAllChildrenByUser(User $user)
    {
        return $this
            ->createQueryBuilder('ch')
            ->select('ch, sec')
            ->leftJoin('ch.sections', 'sec')
            ->leftJoin('ch.parents', 'par')
            ->where('par = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();
    }

    public function getAllParentsEmailsAndNames()
    {
        return $this
            ->createQueryBuilder('ch')
            ->select('ch.parentEmail AS email, ch.parentName AS name')
            ->distinct(true)
            ->getQuery()
            ->getArrayResult();
    }
}
