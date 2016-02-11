<?php

namespace StoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Params
 *
 * @ORM\Table(name="params")
 * @ORM\Entity(repositoryClass="StoreBundle\Repository\ParamsRepository")
 */
class Params
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="id_user", type="integer")
     */
    private $idUser;

    /**
     * @var string
     *
     * @ORM\Column(name="bookable_periods", type="simple_array")
     */
    private $bookablePeriods;

    /**
     * @var bool
     *
     * @ORM\Column(name="bookable", type="boolean")
     */
    private $bookable;

    /**
     * @var int
     *
     * @ORM\Column(name="duree", type="smallint")
     */
    private $duree;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set idUser
     *
     * @param integer $idUser
     *
     * @return Params
     */
    public function setIdUser($idUser)
    {
        $this->idUser = $idUser;

        return $this;
    }

    /**
     * Get idUser
     *
     * @return int
     */
    public function getIdUser()
    {
        return $this->idUser;
    }

    /**
     * Set bookablePeriods
     *
     * @param array $bookablePeriods
     *
     * @return Params
     */
    public function setBookablePeriods($bookablePeriods)
    {
        $this->bookablePeriods = $bookablePeriods;

        return $this;
    }

    /**
     * Get bookablePeriods
     *
     * @return array
     */
    public function getBookablePeriods()
    {
        return $this->bookablePeriods;
    }

    /**
     * Set bookable
     *
     * @param boolean $bookable
     *
     * @return Params
     */
    public function setBookable($bookable)
    {
        $this->bookable = $bookable;

        return $this;
    }

    /**
     * Get bookable
     *
     * @return bool
     */
    public function getBookable()
    {
        return $this->bookable;
    }

    /**
     * Set duree
     *
     * @param integer $duree
     *
     * @return Params
     */
    public function setDuree($duree)
    {
        $this->duree = $duree;

        return $this;
    }

    /**
     * Get duree
     *
     * @return int
     */
    public function getDuree()
    {
        return $this->duree;
    }
}

