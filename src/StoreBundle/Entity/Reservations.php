<?php

namespace StoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Reservations
 *
 * @ORM\Table(name="reservations")
 * @ORM\Entity(repositoryClass="StoreBundle\Repository\ReservationsRepository")
 */
class Reservations
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
     * @var datetime
     *
     * @ORM\Column(type="datetime")
     */
    private $date_start;

    /**
     * @var datetime
     *
     * @ORM\Column(type="datetime")
     */
    private $date_end;

    /**
     * @var text
     *
     * @ORM\Column(type="text")
     */
    private $title;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer")
     */
    private $id_user;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer",nullable=true)
     */
    private $id_type;

    /**
     * @var string
     * @ORM\Column(type="string",length=6,nullable=true)
     */
    private $color;

    /**
     * @var smallint
     * @ORM\Column(type="smallint")
     */
    private $duree;

    /**
     * @var integer
     * @ORM\Column(type="integer")
     */
    private $id_client;


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
     * Set dateStart
     *
     * @param \DateTime $dateStart
     *
     * @return Reservations
     */
    public function setDateStart($dateStart)
    {
        $this->date_start = $dateStart;

        return $this;
    }

    /**
     * Get dateStart
     *
     * @return \DateTime
     */
    public function getDateStart()
    {
        return $this->date_start;
    }

    /**
     * Set dateEnd
     *
     * @param \DateTime $dateEnd
     *
     * @return Reservations
     */
    public function setDateEnd($dateEnd)
    {
        $this->date_end = $dateEnd;

        return $this;
    }

    /**
     * Get dateEnd
     *
     * @return \DateTime
     */
    public function getDateEnd()
    {
        return $this->date_end;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Reservations
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set idUser
     *
     * @param integer $idUser
     *
     * @return Reservations
     */
    public function setIdUser($idUser)
    {
        $this->id_user = $idUser;

        return $this;
    }

    /**
     * Get idUser
     *
     * @return integer
     */
    public function getIdUser()
    {
        return $this->id_user;
    }

    /**
     * Set idType
     *
     * @param integer $idType
     *
     * @return Reservations
     */
    public function setIdType($idType)
    {
        $this->id_type = $idType;

        return $this;
    }

    /**
     * Get idType
     *
     * @return \int
     */
    public function getIdType()
    {
        return $this->id_type;
    }

    /**
     * Set color
     *
     * @param string $color
     *
     * @return Reservations
     */
    public function setColor($color)
    {
        $this->color = $color;

        return $this;
    }

    /**
     * Get color
     *
     * @return \varchar
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * Set duree
     *
     * @param integer $duree
     *
     * @return Reservations
     */
    public function setDuree($duree)
    {
        $this->duree = $duree;

        return $this;
    }

    /**
     * Get duree
     *
     * @return integer
     */
    public function getDuree()
    {
        return $this->duree;
    }

    /**
     * Set idClient
     *
     * @param integer $idClient
     *
     * @return Reservations
     */
    public function setIdClient($idClient)
    {
        $this->id_client = $idClient;

        return $this;
    }

    /**
     * Get idClient
     *
     * @return integer
     */
    public function getIdClient()
    {
        return $this->id_client;
    }
}
