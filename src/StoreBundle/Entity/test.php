<?php

namespace StoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * test
 *
 * @ORM\Table(name="test")
 * @ORM\Entity(repositoryClass="StoreBundle\Repository\testRepository")
 */
class test
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
     * @var string
     *
     * @ORM\Column(name="varchartest", type="string", length=200)
     */
    private $varchartest;


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
     * Set varchartest
     *
     * @param string $varchartest
     *
     * @return test
     */
    public function setVarchartest($varchartest)
    {
        $this->varchartest = $varchartest;

        return $this;
    }

    /**
     * Get varchartest
     *
     * @return string
     */
    public function getVarchartest()
    {
        return $this->varchartest;
    }
}

