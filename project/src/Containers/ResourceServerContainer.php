<?php

namespace OAuth2ServerExamples\Containers;

use League\OAuth2\Server\ResourceServer;
use OAuth2ServerExamples\Repositories\AccessTokenRepository;

class ResourceServerContainer extends AbstractContainer
{
    public static function init($container, $options = null)
    {
        return new ResourceServer(
            new AccessTokenRepository(),
            $container['settings']['publicKey']
        );
    }
}