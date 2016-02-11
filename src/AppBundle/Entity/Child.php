<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
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
    protected $balance;

    /**
     * @ORM\Column(type="integer")
     */
    protected $lessonPrice;

    /**
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\User", mappedBy="children")
     */
    protected $children;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Payment", mappedBy="child")
     */
    protected $payments;

    /**
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Section", mappedBy="children")
     */
    protected $sections;
}
