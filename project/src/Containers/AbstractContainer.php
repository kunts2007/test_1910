<?php
namespace OAuth2ServerExamples\Containers;

use Slim\Container;

abstract class AbstractContainer
{
    abstract static function init(Container $container, $options = null);
}