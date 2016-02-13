<?php

namespace AppBundle\Entity\Repository;

use AppBundle\Entity\Child;
use Doctrine\ORM\EntityRepository;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;

class PaymentRepository extends EntityRepository
{
    public function getPaginatedPaymentsByChild(Child $child, $page, $size)
    {
        $result = new Pagerfanta(new DoctrineORMAdapter(
            $this
                ->createQueryBuilder('p')
                ->orderBy('p.createdAt', 'DESC')
                ->where('p.child = :child')
                ->setParameter('child', $child)
        ));

        $result->setMaxPerPage($size);
        $result->setCurrentPage($page);

        return $result;
    }
}
