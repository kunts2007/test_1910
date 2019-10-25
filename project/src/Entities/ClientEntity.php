<?php

namespace OAuth2ServerExamples\Entities;

use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Entities\Traits\ClientTrait;
use League\OAuth2\Server\Entities\Traits\EntityTrait;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;


/**
 * @ORM\Entity()
 * @ORM\Table(name="client")
 */
class ClientEntity implements ClientEntityInterface
{

    use EntityTrait, ClientTrait;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     */
    protected $identifier;


    /**
     * @ORM\Column(type="string")
     */
    protected $name;

    /**
     * @ORM\Column(type="string", length=60, nullable=false)
     */
    protected $secret;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $redirectUri;


    /**
     * @ORM\Column(type="boolean")
     */
    protected $isConfidential = false;


    /**
     * @ORM\ManyToMany(targetEntity="ScopeEntity")
     * @ORM\JoinTable(name="client_scopes",
     *      joinColumns={@ORM\JoinColumn(name="client_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="scope_id", referencedColumnName="id")}
     *      )
     */
    private $scopes;



    public function __construct() {
        $this->scopes = new ArrayCollection();
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
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getSecret()
    {
        return $this->secret;
    }

    /**
     * @param mixed $secret
     */
    public function setSecret($secret)
    {
        $this->secret = $this::encryptPassword($secret);
    }

    /**
     * @param mixed $redirectUri
     */
    public function setRedirectUri($redirectUri)
    {
        $this->redirectUri = $redirectUri;
    }


    public static function encryptPassword($string)
    {
        return $string;
        //return password_hash($string, PASSWORD_BCRYPT);
    }


    public function validateSecret($value)
    {
        return $this->getSecret() && $this->getSecret() == $this->encryptPassword($value);
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




    /**
     * @return mixed
     */
    public function getConfidential()
    {
        return $this->isConfidential;
    }

    /**
     * @param mixed $isConfidential
     */
    public function setConfidential($isConfidential = true)
    {
        $this->isConfidential = $isConfidential;
    }

}
