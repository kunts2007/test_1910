<?php

namespace OAuth2ServerExamples\Containers;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Cache\FilesystemCache;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use Doctrine\ORM\Tools\Setup;

class EntityManagerContainer extends AbstractContainer
{
    public static function init($container, $options = null)
    {
        $config = Setup::createAnnotationMetadataConfiguration(
            $container['settings']['doctrine']['metadata_dirs'],
            $container['settings']['doctrine']['dev_mode']
        );
        $config->setMetadataDriverImpl(
            new AnnotationDriver(
                new AnnotationReader,
                $container['settings']['doctrine']['metadata_dirs']
            )
        );
        $config->setMetadataCacheImpl(
            new FilesystemCache(
                $container['settings']['doctrine']['cache_dir']
            )
        );

        return EntityManager::create(
            $container['settings']['doctrine']['connection'],
            $config
        );
    }
}