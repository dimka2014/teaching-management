<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="attendence")
 */
class Attendence
{
    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Child", inversedBy="attendences")
     */
    protected $child;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Lesson", inversedBy="attendences")
     */
    protected $lesson;

    /**
     * @ORM\Column(type="integer")
     */
    protected $price;
}
