<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="lesson")
 */
class Lesson
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $time;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Section", inversedBy="lessons")
     */
    protected $section;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Attendence", mappedBy="lessons")
     */
    protected $attendences;

}
