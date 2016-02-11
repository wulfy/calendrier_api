<?php

namespace StoreBundle\Entity;

use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="user")
 *@ORM\Entity(repositoryClass="StoreBundle\Repository\UserRepository")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", unique=true)
     */
    private $username;

    /**
     * @ORM\Column(type="string", unique=false)
     */
    private $password;

    /**
     * @ORM\Column(type="string", unique=true, nullable=true)
     */
    private $apiKey;

    /**
     * @ORM\Column(name="roles", type="simple_array")
     */
    private $roles;

    public function getId(){
        return $this->id;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function getRoles()
    {
        return $this->roles;
    }

    public function getPassword()
    {
        return $this->password;
    }
    public function getSalt()
    {
    }
    public function eraseCredentials()
    {
    }

    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }
    public function setRoles($roles)
    {
        $this->roles = $roles;

        return $this;
    }

    // more getters/setters
}
