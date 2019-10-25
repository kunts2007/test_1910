<?php

namespace OAuth2ServerExamples\Helpers;

use Psr\Http\Message\ServerRequestInterface;

class RequestHelper
{
    public static function authParams(ServerRequestInterface $request)
    {
        return [
            'accessTokenId' => $request->getAttribute('oauth_access_token_id'),
            'clientId' => $request->getAttribute('oauth_client_id'),
            'userId' => $request->getAttribute('oauth_user_id'),
            'scopes' => $request->getAttribute('oauth_scopes'),
        ];
    }
}