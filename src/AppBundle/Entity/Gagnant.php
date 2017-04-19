<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Gagnant
 *
 * @ORM\Table(name="gagnant", uniqueConstraints={@ORM\UniqueConstraint(name="gagnant_uk", columns={"tirage_id", "bons_numeros", "bonnes_etoiles"})}, indexes={@ORM\Index(name="IDX_53D089C82579054", columns={"tirage_id"})})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\GagnantRepository")
 */
class Gagnant
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
     * @var integer
     *
     * @ORM\Column(name="rang", type="integer", nullable=false)
     */
    private $rang;

    /**
     * @var integer
     *
     * @ORM\Column(name="bons_numeros", type="integer", nullable=false)
     */
    private $bonsNumeros;

    /**
     * @var integer
     *
     * @ORM\Column(name="bonnes_etoiles", type="integer", nullable=false)
     */
    private $bonnesEtoiles;

    /**
     * @var integer
     *
     * @ORM\Column(name="nombre", type="integer", nullable=false)
     */
    private $nombre;

    /**
     * @var integer
     *
     * @ORM\Column(name="gains", type="integer", nullable=false)
     */
    private $gains;

    /**
     * @var \AppBundle\Entity\Tirage
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Tirage", inversedBy="gagnants")
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
     * Set Rang
     *
     * @param int $rang
     */
    public function setRang($rang)
    {
        $this->rang = $rang;
    }

    /**
     * Get Rang
     *
     * @return int
     */
    public function getRang()
    {
        return $this->rang;
    }

    /**
     * Set bonsNumeros
     *
     * @param integer $bonsNumeros
     *
     * @return Gagnant
     */
    public function setBonsNumeros($bonsNumeros)
    {
        $this->bonsNumeros = $bonsNumeros;

        return $this;
    }

    /**
     * Get bonsNumeros
     *
     * @return integer
     */
    public function getBonsNumeros()
    {
        return $this->bonsNumeros;
    }

    /**
     * Set bonnesEtoiles
     *
     * @param integer $bonnesEtoiles
     *
     * @return Gagnant
     */
    public function setBonnesEtoiles($bonnesEtoiles)
    {
        $this->bonnesEtoiles = $bonnesEtoiles;

        return $this;
    }

    /**
     * Get bonnesEtoiles
     *
     * @return integer
     */
    public function getBonnesEtoiles()
    {
        return $this->bonnesEtoiles;
    }

    /**
     * Set nombre
     *
     * @param integer $nombre
     *
     * @return Gagnant
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre
     *
     * @return integer
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set gains
     *
     * @param integer $gains
     *
     * @return Gagnant
     */
    public function setGains($gains)
    {
        $this->gains = $gains;

        return $this;
    }

    /**
     * Get gains
     *
     * @return integer
     */
    public function getGains()
    {
        return $this->gains;
    }

    /**
     * Set tirage
     *
     * @param \AppBundle\Entity\Tirage $tirage
     *
     * @return Gagnant
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
