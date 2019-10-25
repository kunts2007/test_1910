<?php

namespace OAuth2ServerExamples\Repositories;

use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Repositories\UserRepositoryInterface;
use OAuth2ServerExamples\Entities\UserEntity;
use Doctrine\ORM\EntityManager;


class UserRepository implements UserRepositoryInterface
{

    /**
     * @var EntityManager
     */
    private $em;


    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function getUserEntity($identifier)
    {
        return $this->em
            ->getRepository(UserEntity::class)
            ->findOneBy(['id' => $identifier]);
    }

    public function signUp(string $username, string $password, string $clientIdentifier = null)
    {

        $scopeRepository = new ScopeRepository($this->em);
        $scopes[] = $scopeRepository->getScopeEntityByIdentifier('default');
        $scopes[] = $scopeRepository->getScopeEntityByIdentifier('user');

        $user = new UserEntity();
        $user->setUsername($username);
        $user->setPassword($password);
        $user->setClientIdentifier($clientIdentifier);
        $user->setScopes($scopes);
        $this->em->persist($user);
        $this->em->flush();
        return true;
    }

    public function getProfileArray($userId = null)
    {
        $result = $userId
            ? $this->em
                ->getRepository(UserEntity::class)
                ->createQueryBuilder('u')
                ->select('u.username, u.created')
                ->where('u.id =:value')
                ->setParameter('value', $userId)
                ->getQuery()
                ->getArrayResult()[0]
            : [];

        $result['created'] = $result['created']->format('Y-m-d H:i:s');

        return $result;
    }

    public function exists($username = '')
    {
        return $username
            ? !!$this->em
                    ->getRepository(UserEntity::class)
                    ->findOneBy(['username' => $username])
            : false;
    }

    /**
     * {@inheritdoc}
     */
    public function getUserEntityByUserCredentials(
        $username,
        $password,
        $grantType,
        ClientEntityInterface $clientEntity
    ) {
        return $this->em
            ->getRepository(UserEntity::class)
            ->findOneBy([
                'username' => $username,
                'password' => UserEntity::encryptPassword($password)
            ]);
    }
}
