<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\User;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadUserData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $admin = new User();
        $admin->setUsername('admin');
        $admin->setEmail('admin@example.com');
        $admin->setPlainPassword('admin');
        $admin->setSuperAdmin(true);
        $admin->setEnabled(true);

        $manager->persist($admin);
        $manager->flush();
    }
}