<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Entity\Repository\ChildRepository")
 * @ORM\Table(name="child")
 */
class Child
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
     * @ORM\Column(type="string")
     * @Assert\NotBlank
     */
    protected $parentName;

    /**
     * @ORM\Column(type="string", length=13)
     * @Assert\Length(max=13)
     */
    protected $parentPhone;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank
     * @Assert\Email
     */
    protected $parentEmail;

    /**
     * @ORM\Column(type="integer")
     */
    protected $balance = 0;

    /**
     * @ORM\Column(type="integer")
     */
    protected $lessonPrice;

    /**
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\User", mappedBy="children")
     */
    protected $parents;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Payment", mappedBy="child", cascade={"remove"})
     */
    protected $payments;

    /**
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Section", mappedBy="children")
     */
    protected $sections;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Attendence", mappedBy="child", cascade={"remove"})
     */
    protected $attendences;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $createdAt;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->parents = new ArrayCollection();
        $this->payments = new ArrayCollection();
        $this->sections = new ArrayCollection();
        $this->attendences = new ArrayCollection();
        $this->createdAt = new \DateTime();
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
     * Set name
     *
     * @param string $name
     * @return Child
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set parentName
     *
     * @param string $parentName
     * @return Child
     */
    public function setParentName($parentName)
    {
        $this->parentName = $parentName;

        return $this;
    }

    /**
     * Get parentName
     *
     * @return string 
     */
    public function getParentName()
    {
        return $this->parentName;
    }

    /**
     * Set parentPhone
     *
     * @param string $parentPhone
     * @return Child
     */
    public function setParentPhone($parentPhone)
    {
        $this->parentPhone = $parentPhone;

        return $this;
    }

    /**
     * Get parentPhone
     *
     * @return string 
     */
    public function getParentPhone()
    {
        return $this->parentPhone;
    }

    /**
     * Set parentEmail
     *
     * @param string $parentEmail
     * @return Child
     */
    public function setParentEmail($parentEmail)
    {
        $this->parentEmail = $parentEmail;

        return $this;
    }

    /**
     * Get parentEmail
     *
     * @return string 
     */
    public function getParentEmail()
    {
        return $this->parentEmail;
    }

    /**
     * Set balance
     *
     * @param integer $balance
     * @return Child
     */
    public function setBalance($balance)
    {
        $this->balance = $balance;

        return $this;
    }

    /**
     * Get balance
     *
     * @return integer 
     */
    public function getBalance()
    {
        return $this->balance;
    }

    /**
     * Set lessonPrice
     *
     * @param integer $lessonPrice
     * @return Child
     */
    public function setLessonPrice($lessonPrice)
    {
        $this->lessonPrice = $lessonPrice;

        return $this;
    }

    /**
     * Get lessonPrice
     *
     * @return integer 
     */
    public function getLessonPrice()
    {
        return $this->lessonPrice;
    }

    /**
     * Add parent
     *
     * @param User $parent
     * @return Child
     */
    public function addParent(User $parent)
    {
        $this->parents[] = $parent;

        return $this;
    }

    /**
     * Remove parent
     *
     * @param User $parent
     */
    public function removeParent(User $parent)
    {
        $this->parents->removeElement($parent);
    }

    /**
     * Get parents
     *
     * @return Collection
     */
    public function getParents()
    {
        return $this->parents;
    }

    /**
     * Add payments
     *
     * @param Payment $payments
     * @return Child
     */
    public function addPayment(Payment $payments)
    {
        $this->payments[] = $payments;

        return $this;
    }

    /**
     * Remove payments
     *
     * @param Payment $payments
     */
    public function removePayment(Payment $payments)
    {
        $this->payments->removeElement($payments);
    }

    /**
     * Get payments
     *
     * @return Collection
     */
    public function getPayments()
    {
        return $this->payments;
    }

    /**
     * Add sections
     *
     * @param Section $section
     * @return Child
     */
    public function addSection(Section $section)
    {
        if (!$this->sections->contains($section)) {
            $this->sections[] = $section;
            $section->addChild($this);
        }

        return $this;
    }

    /**
     * Remove sections
     *
     * @param Section $section
     * @return Child
     */
    public function removeSection(Section $section)
    {
        if ($this->sections->contains($section)) {
            $this->sections->removeElement($section);
            $section->removeChild($this);
        }

        return $this;
    }

    /**
     * Get sections
     *
     * @return Collection
     */
    public function getSections()
    {
        return $this->sections;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
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
     * @return Collection
     */
    public function getAttendences()
    {
        return $this->attendences;
    }

    public function __toString()
    {
        return $this->getName();
    }
}
