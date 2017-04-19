<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Tirage
 *
 * @ORM\Table(name="tirage", uniqueConstraints={@ORM\UniqueConstraint(name="tirage_uk", columns={"jour"})})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TirageRepository")
 */
class Tirage
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="jour", type="date", nullable=false)
     */
    private $jour;

    /**
     * @var integer
     *
     * @ORM\Column(name="boule1", type="integer", nullable=false)
     */
    private $boule1;

    /**
     * @var integer
     *
     * @ORM\Column(name="boule2", type="integer", nullable=false)
     */
    private $boule2;

    /**
     * @var integer
     *
     * @ORM\Column(name="boule3", type="integer", nullable=false)
     */
    private $boule3;

    /**
     * @var integer
     *
     * @ORM\Column(name="boule4", type="integer", nullable=false)
     */
    private $boule4;

    /**
     * @var integer
     *
     * @ORM\Column(name="boule5", type="integer", nullable=false)
     */
    private $boule5;

    /**
     * @var integer
     *
     * @ORM\Column(name="etoile1", type="integer", nullable=false)
     */
    private $etoile1;

    /**
     * @var integer
     *
     * @ORM\Column(name="etoile2", type="integer", nullable=false)
     */
    private $etoile2;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Gagnant", mappedBy="tirage")
     */
    private $gagnants;

    /**
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\JokerPlus", mappedBy="tirage")
     */
    private $jokerPlus;

    /**
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\MyMillion", mappedBy="tirage")
     */
    private $myMillion;

    /**
     * Tirage constructor.
     */
    public function __construct()
    {
        $this->gagnants = new ArrayCollection();
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
     * Set jour
     *
     * @param \DateTime $jour
     *
     * @return Tirage
     */
    public function setJour($jour)
    {
        $this->jour = $jour;

        return $this;
    }

    /**
     * Get jour
     *
     * @return \DateTime
     */
    public function getJour()
    {
        return $this->jour;
    }

    /**
     * Set boule1
     *
     * @param integer $boule1
     *
     * @return Tirage
     */
    public function setBoule1($boule1)
    {
        $this->boule1 = $boule1;

        return $this;
    }

    /**
     * Get boule1
     *
     * @return integer
     */
    public function getBoule1()
    {
        return $this->boule1;
    }

    /**
     * Set boule2
     *
     * @param integer $boule2
     *
     * @return Tirage
     */
    public function setBoule2($boule2)
    {
        $this->boule2 = $boule2;

        return $this;
    }

    /**
     * Get boule2
     *
     * @return integer
     */
    public function getBoule2()
    {
        return $this->boule2;
    }

    /**
     * Set boule3
     *
     * @param integer $boule3
     *
     * @return Tirage
     */
    public function setBoule3($boule3)
    {
        $this->boule3 = $boule3;

        return $this;
    }

    /**
     * Get boule3
     *
     * @return integer
     */
    public function getBoule3()
    {
        return $this->boule3;
    }

    /**
     * Set boule4
     *
     * @param integer $boule4
     *
     * @return Tirage
     */
    public function setBoule4($boule4)
    {
        $this->boule4 = $boule4;

        return $this;
    }

    /**
     * Get boule4
     *
     * @return integer
     */
    public function getBoule4()
    {
        return $this->boule4;
    }

    /**
     * Set boule5
     *
     * @param integer $boule5
     *
     * @return Tirage
     */
    public function setBoule5($boule5)
    {
        $this->boule5 = $boule5;

        return $this;
    }

    /**
     * Get boule5
     *
     * @return integer
     */
    public function getBoule5()
    {
        return $this->boule5;
    }

    /**
     * Set etoile1
     *
     * @param integer $etoile1
     *
     * @return Tirage
     */
    public function setEtoile1($etoile1)
    {
        $this->etoile1 = $etoile1;

        return $this;
    }

    /**
     * Get etoile1
     *
     * @return integer
     */
    public function getEtoile1()
    {
        return $this->etoile1;
    }

    /**
     * Set etoile2
     *
     * @param integer $etoile2
     *
     * @return Tirage
     */
    public function setEtoile2($etoile2)
    {
        $this->etoile2 = $etoile2;

        return $this;
    }

    /**
     * Get etoile2
     *
     * @return integer
     */
    public function getEtoile2()
    {
        return $this->etoile2;
    }

    /**
     * @return ArrayCollection
     */
    public function getGagnants()
    {
        return $this->gagnants;
    }

    /**
     * @param ArrayCollection $gagnants
     */
    public function setGagnants($gagnants)
    {
        $this->gagnants = $gagnants;
    }

    /**
     * @return JokerPlus|null
     */
    public function getJokerPlus()
    {
        return $this->jokerPlus;
    }

    /**
     * @param JokerPlus $jokerPlus
     */
    public function setJokerPlus($jokerPlus)
    {
        $this->jokerPlus = $jokerPlus;
    }

    /**
     * @return MyMillion|null
     */
    public function getMyMillion()
    {
        return $this->myMillion;
    }

    /**
     * @param MyMillion $myMillion
     */
    public function setMyMillion($myMillion)
    {
        $this->myMillion = $myMillion;
    }


}
