<?php
use Phalcon\Loader;
use Phalcon\Mvc\Micro;
use Phalcon\Di\FactoryDefault;
use Phalcon\Db\Adapter\Pdo\Mysql as PdoMysql;

// Use Loader() to autoload our model
$loader = new Loader();

$loader->registerNamespaces(
    [
        'MyModels' => __DIR__ . '/models/',
    ]
);

$loader->register();

$di = new FactoryDefault();

// Set up the database service
$di->set(
    'db',
    function () {
        return new PdoMysql(
            [
                "host"      => "localhost" ,
                "username"  => "root" ,
                "password"  => "",
                'dbname'   => 'inf_person',
            ]
        );
    }
);

$app = new Micro($di);
require_once ("app.php");
$app->handle();