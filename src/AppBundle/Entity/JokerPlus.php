<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * JokerPlus
 *
 * @ORM\Table(name="joker_plus", uniqueConstraints={@ORM\UniqueConstraint(name="joker_plus_uk", columns={"tirage_id"})})
 * @ORM\Entity
 */
class JokerPlus
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
     * Set chiffre1
     *
     * @param boolean $chiffre1
     *
     * @return JokerPlus
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
     * @return JokerPlus
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
     * @return JokerPlus
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
     * @return JokerPlus
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
     * @return JokerPlus
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
     * @return JokerPlus
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
     * @return JokerPlus
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
     * @return JokerPlus
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
