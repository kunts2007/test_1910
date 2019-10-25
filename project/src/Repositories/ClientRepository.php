<?php

namespace OAuth2ServerExamples\Repositories;

use Doctrine\ORM\EntityManager;
use League\OAuth2\Server\Repositories\ClientRepositoryInterface;
use OAuth2ServerExamples\Entities\ClientEntity;


class ClientRepository implements ClientRepositoryInterface
{

    /**
     * @var EntityManager
     */
    private $em;


    /**
     * ClientRepository constructor.
     *
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * {@inheritdoc}
     */
    public function getClientEntity($clientIdentifier)
    {
        return $this->em
            ->getRepository(ClientEntity::class)
            ->findOneBy(['identifier' => $clientIdentifier]);
    }

    /**
     * {@inheritdoc}
     */
    public function validateClient($clientIdentifier, $clientSecret, $grantType)
    {
        $client = $this->getClientEntity($clientIdentifier);
        if(!$client) {
            return false;
        }
        if($client->isConfidential() && !$client->validateSecret($clientSecret)) {
            return false;
        }
        return true;
    }
}
