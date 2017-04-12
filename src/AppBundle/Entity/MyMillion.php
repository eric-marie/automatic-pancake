<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MyMillion
 *
 * @ORM\Table(name="my_million", uniqueConstraints={@ORM\UniqueConstraint(name="my_million_uk", columns={"tirage_id"})})
 * @ORM\Entity
 */
class MyMillion
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
     * @var string
     *
     * @ORM\Column(name="lettre1", type="string", length=1, nullable=false)
     */
    private $lettre1;

    /**
     * @var string
     *
     * @ORM\Column(name="lettre2", type="string", length=1, nullable=false)
     */
    private $lettre2;

    /**
     * @var boolean
     *
     * @ORM\Column(name="chiffre1", type="boolean", nullable=false)
     */
    private $chiffre1;

    /**
     * @var boolean
     *
     * @ORM\Column(name="chiffre2", type="boolean", nullable=false)
     */
    private $chiffre2;

    /**
     * @var boolean
     *
     * @ORM\Column(name="chiffre3", type="boolean", nullable=false)
     */
    private $chiffre3;

    /**
     * @var boolean
     *
     * @ORM\Column(name="chiffre4", type="boolean", nullable=false)
     */
    private $chiffre4;

    /**
     * @var boolean
     *
     * @ORM\Column(name="chiffre5", type="boolean", nullable=false)
     */
    private $chiffre5;

    /**
     * @var boolean
     *
     * @ORM\Column(name="chiffre6", type="boolean", nullable=false)
     */
    private $chiffre6;

    /**
     * @var boolean
     *
     * @ORM\Column(name="chiffre7", type="boolean", nullable=false)
     */
    private $chiffre7;

    /**
     * @var \AppBundle\Entity\Tirage
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Tirage")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="tirage_id", referencedColumnName="id")
     * })
     */
    private $tirage;



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
     * Set lettre1
     *
     * @param string $lettre1
     *
     * @return MyMillion
     */
    public function setLettre1($lettre1)
    {
        $this->lettre1 = $lettre1;

        return $this;
    }

    /**
     * Get lettre1
     *
     * @return string
     */
    public function getLettre1()
    {
        return $this->lettre1;
    }

    /**
     * Set lettre2
     *
     * @param string $lettre2
     *
     * @return MyMillion
     */
    public function setLettre2($lettre2)
    {
        $this->lettre2 = $lettre2;

        return $this;
    }

    /**
     * Get lettre2
     *
     * @return string
     */
    public function getLettre2()
    {
        return $this->lettre2;
    }

    /**
     * Set chiffre1
     *
     * @param boolean $chiffre1
     *
     * @return MyMillion
     */
    public function setChiffre1($chiffre1)
    {
        $this->chiffre1 = $chiffre1;

        return $this;
    }

    /**
     * Get chiffre1
     *
     * @return boolean
     */
    public function getChiffre1()
    {
        return $this->chiffre1;
    }

    /**
     * Set chiffre2
     *
     * @param boolean $chiffre2
     *
     * @return MyMillion
     */
    public function setChiffre2($chiffre2)
    {
        $this->chiffre2 = $chiffre2;

        return $this;
    }

    /**
     * Get chiffre2
     *
     * @return boolean
     */
    public function getChiffre2()
    {
        return $this->chiffre2;
    }

    /**
     * Set chiffre3
     *
     * @param boolean $chiffre3
     *
     * @return MyMillion
     */
    public function setChiffre3($chiffre3)
    {
        $this->chiffre3 = $chiffre3;

        return $this;
    }

    /**
     * Get chiffre3
     *
     * @return boolean
     */
    public function getChiffre3()
    {
        return $this->chiffre3;
    }

    /**
     * Set chiffre4
     *
     * @param boolean $chiffre4
     *
     * @return MyMillion
     */
    public function setChiffre4($chiffre4)
    {
        $this->chiffre4 = $chiffre4;

        return $this;
    }

    /**
     * Get chiffre4
     *
     * @return boolean
     */
    public function getChiffre4()
    {
        return $this->chiffre4;
    }

    /**
     * Set chiffre5
     *
     * @param boolean $chiffre5
     *
     * @return MyMillion
     */
    public function setChiffre5($chiffre5)
    {
        $this->chiffre5 = $chiffre5;

        return $this;
    }

    /**
     * Get chiffre5
     *
     * @return boolean
     */
    public function getChiffre5()
    {
        return $this->chiffre5;
    }

    /**
     * Set chiffre6
     *
     * @param boolean $chiffre6
     *
     * @return MyMillion
     */
    public function setChiffre6($chiffre6)
    {
        $this->chiffre6 = $chiffre6;

        return $this;
    }

    /**
     * Get chiffre6
     *
     * @return boolean
     */
    public function getChiffre6()
    {
        return $this->chiffre6;
    }

    /**
     * Set chiffre7
     *
     * @param boolean $chiffre7
     *
     * @return MyMillion
     */
    public function setChiffre7($chiffre7)
    {
        $this->chiffre7 = $chiffre7;

        return $this;
    }

    /**
     * Get chiffre7
     *
     * @return boolean
     */
    public function getChiffre7()
    {
        return $this->chiffre7;
    }

    /**
     * Set tirage
     *
     * @param \AppBundle\Entity\Tirage $tirage
     *
     * @return MyMillion
     */
    public function setTirage(\AppBundle\Entity\Tirage $tirage = null)
    {
        $this->tirage = $tirage;

        return $this;
    }

    /**
     * Get tirage
     *
     * @return \AppBundle\Entity\Tirage
     */
    public function getTirage()
    {
        return $this->tirage;
    }
}