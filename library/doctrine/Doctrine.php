<?php

use Doctrine\Common\ClassLoader,
    Doctrine\ORM\Configuration,
    Doctrine\ORM\EntityManager,
    Doctrine\Common\Cache\ArrayCache,
    Doctrine\DBAL\Logging\EchoSQLLogger;

class Doctrine {

    private static $em = null;

    public function __construct($docConfig = array()) {
        require_once 'Doctrine/Common/ClassLoader.php';

        $doctrineClassLoader = new ClassLoader('Doctrine', 'doctrine');
        $doctrineClassLoader->register();
        $doctrineClassLoader = new ClassLoader('DoctrineExtensions', 'doctrine');
        $doctrineClassLoader->register();

        // Set up caches
        $config = new Configuration;
        $cache = new ArrayCache;
        $config->setMetadataCacheImpl($cache);
        $driverImpl = $config->newDefaultAnnotationDriver(array(APPLICATION_PATH . 'models'));
        $config->setMetadataDriverImpl($driverImpl);
        $config->setQueryCacheImpl($cache);

        $config->setQueryCacheImpl($cache);

        $logger = new EchoSQLLogger;
        //$config->setSQLLogger($logger);
        
        // Proxy configuration
        $config->setProxyDir(APPLICATION_PATH . '/models/proxies');
        $config->setProxyNamespace('Proxies');

        $config->setAutoGenerateProxyClasses(true);
        
        // table prefix
        $evm = new \Doctrine\Common\EventManager;
        $tablePrefix = new \DoctrineExtensions\TablePrefix('qw_');
        $evm->addEventListener(\Doctrine\ORM\Events::loadClassMetadata, $tablePrefix);

        // Create EntityManager
        $conn = array(
            'driver' => "pdo_".$docConfig['driver'],
            'user' => $docConfig['user'],
            'pass' => $docConfig['pass'],
            'dbname' => $docConfig['dbname'],
            'host' => $docConfig['host']
        );
        self::$em = EntityManager::create($conn, $config, $evm);
    }

    public static function getEntityManager() {
        return self::$em;
    }

}