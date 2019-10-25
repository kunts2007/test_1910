<?php

namespace OAuth2ServerExamples\Containers;

use League\OAuth2\Server\AuthorizationServer;
use League\OAuth2\Server\Grant\ClientCredentialsGrant;
use League\OAuth2\Server\Grant\PasswordGrant;
use OAuth2ServerExamples\Repositories\AccessTokenRepository;
use OAuth2ServerExamples\Repositories\ClientRepository;
use OAuth2ServerExamples\Repositories\RefreshTokenRepository;
use OAuth2ServerExamples\Repositories\ScopeRepository;
use OAuth2ServerExamples\Repositories\UserRepository;

class AuthorizationServerContainer extends AbstractContainer
{
    public static function init($container, $entityManager = null)
    {
        $clientRepository = new ClientRepository($entityManager);
        $accessTokenRepository = new AccessTokenRepository();
        $scopeRepository = new ScopeRepository($entityManager);
//        $authCodeRepository = new AuthCodeRepository();
//        $refreshTokenRepository = new RefreshTokenRepository();

        $server = new AuthorizationServer(
            $clientRepository,
            $accessTokenRepository,
            $scopeRepository,
            $container['settings']['privateKey'],
            $container['settings']['encryptionKey']
        );

        $server->enableGrantType(
            new ClientCredentialsGrant(),
            new \DateInterval('PT1H')
        );
        $server->enableGrantType(
            new PasswordGrant(
                new UserRepository($entityManager),
                new RefreshTokenRepository()
            ),
            new \DateInterval('PT1H')
        );
//        $server->enableGrantType(
//            new AuthCodeGrant(
//                $authCodeRepository,
//                $refreshTokenRepository,
//                new \DateInterval('PT10M')
//            ),
//            new \DateInterval('PT1H')
//        );
//        $server->enableGrantType(
//            new RefreshTokenGrant($refreshTokenRepository),
//            new \DateInterval('P1M')
//        );

        return $server;
    }
}