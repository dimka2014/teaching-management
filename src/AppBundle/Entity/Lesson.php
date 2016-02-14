<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Entity\Repository\LessonRepository")
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
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Attendence", mappedBy="lesson", orphanRemoval=true, cascade={"persist"})
     */
    protected $attendences;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->attendences = new ArrayCollection();
        $this->time = new \DateTime();
    }

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
     * Set time
     *
     * @param \DateTime $time
     * @return Lesson
     */
    public function setTime($time)
    {
        $this->time = $time;

        return $this;
    }

    /**
     * Get time
     *
     * @return \DateTime 
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * Set section
     *
     * @param Section $section
     * @return Lesson
     */
    public function setSection(Section $section = null)
    {
        $this->section = $section;

        return $this;
    }

    /**
     * Get section
     *
     * @return Section
     */
    public function getSection()
    {
        return $this->section;
    }

    /**
     * Add attendences
     *
     * @param Attendence $attendences
     * @return Lesson
     */
    public function addAttendence(Attendence $attendences)
    {
        $this->attendences[] = $attendences;
        $attendences->setLesson($this);

        return $this;
    }

    /**
     * Remove attendences
     *
     * @param Attendence $attendences
     */
    public function removeAttendence(Attendence $attendences)
    {
        $this->attendences->removeElement($attendences);
    }

    /**
     * Get attendences
     *
     * @return Collection|Attendence[]
     */
    public function getAttendences()
    {
        return $this->attendences;
    }

    /**
     * @return Collection|Child[]
     */
    public function getChilds()
    {
        return $this->attendences->map(function (Attendence $attendence) {
            return $attendence->getChild();
        });
    }

    public function addChild(Child $child)
    {
        if (!$this->getAttendenceByChild($child)) {
            $attendence = new Attendence();
            $attendence->setChild($child);
            $attendence->setPrice($child->getLessonPrice());
            $child->setBalance($child->getBalance() - $child->getLessonPrice());
            $this->addAttendence($attendence);
        }

        return $this;
    }

    public function removeChild(Child $child)
    {
        if ($attendence = $this->getAttendenceByChild($child)) {
            $attendence->getChild()->setBalance($attendence->getChild()->getBalance() + $attendence->getPrice());
            $this->removeAttendence($attendence);
        }

        return $this;
    }

    /**
     * @param Child $child
     * @return Attendence|null
     */
    public function getAttendenceByChild(Child $child)
    {
        foreach ($this->getAttendences() as $attendence) {
            if ($attendence->getChild()->getId() == $child->getId()) {
                return $attendence;
            }
        }

        return null;
    }
}
