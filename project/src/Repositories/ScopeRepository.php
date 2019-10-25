<?php

namespace OAuth2ServerExamples\Repositories;

use Doctrine\ORM\EntityManager;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Repositories\ScopeRepositoryInterface;
use OAuth2ServerExamples\Entities\ScopeEntity;

class ScopeRepository implements ScopeRepositoryInterface
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
    public function getScopeEntityByIdentifier($scopeIdentifier)
    {
        return $this->em
            ->getRepository(ScopeEntity::class)
            ->findOneBy(['identifier' => $scopeIdentifier]);
    }

    /**
     * {@inheritdoc}
     */
    public function finalizeScopes(
        array $askScopes,
        $grantType,
        ClientEntityInterface $clientEntity,
        $userIdentifier = null
    )
    {
        //права клиента
        $scopes = $this->getScopes($clientEntity->getScopes(), $askScopes);

        //права пользователя
        if (!empty($userIdentifier)) {
            $userRepository = new UserRepository($this->em);
            $user = $userRepository->getUserEntity($userIdentifier);

            $scopes = $this->getScopes($user->getScopes(), $askScopes);
        }

        return $scopes;
    }

    private function getScopes($scopes = [], array $askScopes = [])
    {
        $result = [];
        foreach ($scopes as $scope) {
            if(!$askScopes) {
                $result[] = $scope;
            } elseif (in_array($scope, $askScopes)) {
                $result[] = $scope;
            }
        }
        return $result;
    }
}
