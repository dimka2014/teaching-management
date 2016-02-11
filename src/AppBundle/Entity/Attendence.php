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
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
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

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set price
     *
     * @param integer $price
     * @return Attendence
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return integer 
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set child
     *
     * @param Child $child
     * @return Attendence
     */
    public function setChild(Child $child = null)
    {
        $this->child = $child;

        return $this;
    }

    /**
     * Get child
     *
     * @return Child
     */
    public function getChild()
    {
        return $this->child;
    }

    /**
     * Set lesson
     *
     * @param Lesson $lesson
     * @return Attendence
     */
    public function setLesson(Lesson $lesson = null)
    {
        $this->lesson = $lesson;

        return $this;
    }

    /**
     * Get lesson
     *
     * @return Lesson
     */
    public function getLesson()
    {
        return $this->lesson;
    }
}
