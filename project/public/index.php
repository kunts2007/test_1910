<?php

use League\OAuth2\Server\AuthorizationServer;
use League\OAuth2\Server\Middleware\AuthorizationServerMiddleware;
use League\OAuth2\Server\Middleware\ResourceServerMiddleware;
use League\OAuth2\Server\ResourceServer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

use Doctrine\ORM\EntityManager;
use Slim\App;
use Slim\Container as Container;

use OAuth2ServerExamples\Containers\EntityManagerContainer;
use OAuth2ServerExamples\Containers\AuthorizationServerContainer;
use OAuth2ServerExamples\Containers\ResourceServerContainer;

use OAuth2ServerExamples\Repositories\UserRepository;
use OAuth2ServerExamples\Helpers\RequestHelper;

include __DIR__ . '/../vendor/autoload.php';

$settings = require __DIR__ . '/../settings.php';

$container = new Container($settings);
$container[EntityManager::class] = EntityManagerContainer::init($container);
$container[AuthorizationServer::class] = AuthorizationServerContainer::init($container, $container[EntityManager::class]);
$container[ResourceServer::class] = ResourceServerContainer::init($container);

$app = new App($container);



/**
 * получить токен
 */
$app
    ->post('/access_token', function () {})
    ->add(new AuthorizationServerMiddleware($app->getContainer()->get(AuthorizationServer::class)));


/**
 * авторизованная зона
 */
$app
    ->group('/oauth', function () {

        /**
         * добавление пользователя
         * ClientCredentialsGrant
         */
        $this->post('/signup', function (ServerRequestInterface $request, ResponseInterface $response) {
            $requestParams = $request->getParsedBody();
            $username = $requestParams['username'] ?? '';
            $password = $requestParams['password'] ?? '';

            $authParams = RequestHelper::authParams($request);

            //нет прав
            if (!in_array('signup', $authParams['scopes'])) {
                return $response
                    //->withStatus(400)
                    ->withHeader('Content-type', 'application/json')
                    ->write(json_encode('Permissions denied.', JSON_UNESCAPED_UNICODE));
            }

            //не заполнены поля
            if (!$username || !$password) {
                return $response
                    //->withStatus(400)
                    ->withHeader('Content-type', 'application/json')
                    ->write(json_encode('Empty fields.', JSON_UNESCAPED_UNICODE));
            }

            $userRepository = new UserRepository($this->get(EntityManager::class));

            //пользователь существует
            if ($userRepository->exists($username)) {
                return $response
                    //->withStatus(400)
                    ->withHeader('Content-type', 'application/json')
                    ->write(json_encode('Username is exists.', JSON_UNESCAPED_UNICODE));
            };

            //создание
            if (!$userRepository->signUp($username, $password, $authParams['clientId'])) {
                return $response
                    //->withStatus(400)
                    ->withHeader('Content-type', 'application/json')
                    ->write(json_encode('Error during user creation.', JSON_UNESCAPED_UNICODE));
            };

            $result = ["OK"];
            return $response
                ->withHeader('Content-type', 'application/json')
                ->write(json_encode($result, JSON_UNESCAPED_UNICODE));
        });


        /**
         * авторизация
         * PasswordGrant
         */
        $this->get('/auth', function (ServerRequestInterface $request, ResponseInterface $response) {
            $result = [];

            $authParams = RequestHelper::authParams($request);

            if (!in_array('user', $authParams['scopes'])) {
                return $response
                    //->withStatus(400)
                    ->withHeader('Content-type', 'application/json')
                    ->write(json_encode('Permissions denied.', JSON_UNESCAPED_UNICODE));
            }

            if ($authParams['userId']) {
                $userRepository = new UserRepository($this->get(EntityManager::class));
                $result['profile'] = $userRepository->getProfileArray($authParams['userId']);
            }

            return $response
                ->withHeader('Content-type', 'application/json')
                ->write(json_encode($result, JSON_UNESCAPED_UNICODE));
        });


    })
    ->add(new ResourceServerMiddleware($app->getContainer()->get(ResourceServer::class)));


/**
 *
 */
$app->get('/[{params:.*}/]', function (ServerRequestInterface $request, ResponseInterface $response, $args) {
    $response->write(file_get_contents('index.html'));
    return $response;
});

$app->post('/[{params:.*}/]', function (ServerRequestInterface $request, ResponseInterface $response, $args) {
    $response->write('sssssss');
    return $response;
});

$app->run();
