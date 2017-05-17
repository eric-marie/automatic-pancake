<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Statistique
 *
 * @ORM\Table(name="statistique", uniqueConstraints={@ORM\UniqueConstraint(name="statistique_uk", columns={"tirage_id"})})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\StatistiqueRepository")
 */
class Statistique
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false, options={"unsigned": true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var Tirage
     *
     * @ORM\ManyToOne(targetEntity="Tirage", inversedBy="statistique")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="tirage_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
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
     * Set tirage
     *
     * @param Tirage $tirage
     *
     * @return Statistique
     */
    public function setTirage(Tirage $tirage = null)
    {
        $this->tirage = $tirage;

        return $this;
    }

    /**
     * Get tirage
     *
     * @return Tirage
     */
    public function getTirage()
    {
        return $this->tirage;
    }
}
