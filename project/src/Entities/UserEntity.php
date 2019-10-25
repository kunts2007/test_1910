<?php

namespace OAuth2ServerExamples\Entities;

use League\OAuth2\Server\Entities\UserEntityInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="user")
 */
class UserEntity implements UserEntityInterface
{

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", nullable=false, unique=true)
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=60, nullable=false)
     */
    private $password;

    /**
     * @ORM\Column(type="datetimetz_immutable", nullable=false)
     */
    private $created;

    /**
     * @ORM\Column(type="string")
     */
    private $client_identifier;

    /**
     * @ORM\ManyToMany(targetEntity="ScopeEntity")
     * @ORM\JoinTable(name="user_scopes",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="scope_id", referencedColumnName="id")}
     *      )
     */
    private $scopes;


    public function __construct()
    {
        $this->setCreated(new \DateTimeImmutable('now'));
        $this->scopes = new \Doctrine\Common\Collections\ArrayCollection();
    }


    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param mixed $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password)
    {
        $this->password = $this::encryptPassword($password);
    }

    /**
     * @return mixed
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @param mixed $created
     */
    public function setCreated($created)
    {
        $this->created = $created;
    }

    /**
     * @return mixed
     */
    public function getClientIdentifier()
    {
        return $this->client_identifier;
    }

    /**
     * @param mixed $client_identifier
     */
    public function setClientIdentifier($client_identifier): void
    {
        $this->client_identifier = $client_identifier;
    }

    /**
     * @return mixed
     */
    public function getScopes()
    {
        return $this->scopes;
    }

    /**
     * @param mixed $scopes
     */
    public function setScopes($scopes): void
    {
        $this->scopes = $scopes;
    }




    public static function encryptPassword($string)
    {
        return md5($string);
    }


    /**
     * Return the user's identifier.
     *
     * @return mixed
     */

    public function getIdentifier()
    {
        return $this->getId();
    }
}
