<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="section")
 */
class Section
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank
     */
    protected $name;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Lesson", mappedBy="section")
     */
    protected $lessons;

    /**
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Child", inversedBy="children")
     * @ORM\JoinTable(name="children_sections")
     */
    protected $children;

}
