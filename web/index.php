<?php
/**
 * User: Jeremi Bergman <j@jeremi.me>
 * Date: 4/23/14
 * Time: 5:29 PM
 */
namespace InnerServe\PDNSPHPAPI;

use Igorw\Silex\ConfigServiceProvider;
use InnerServe\PdnsPhpApi\Service\JsonResponseService;
use Silex\Application;
use Symfony\Component\ClassLoader\UniversalClassLoader;

require '../src/InnerServe/PdnsPhpApi/Service/JsonResponseService.php';

require_once __DIR__.'/../vendor/autoload.php';
$app = new Application();
$app->register(new ConfigServiceProvider(__DIR__."/../config/settings.json"));

$loader = new UniversalClassLoader();
$loader->registerNamespace('InnerServe', __DIR__.'/../src/');
$loader->register();

$app['pdo'] = new \PDO($app['database.dsn'], $app['database.user'], $app['database.pass']);
$app['pdo']->setAttribute( \PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION );
$app['json_response'] = new JsonResponseService();

// security
$app->register(new \Silex\Provider\SecurityServiceProvider(), array(
    'security.firewalls' => array(
        'admin' => array(
            'pattern' => '^/',
            'http' => true,
            'users' => array(
                // raw password is foo
                $app['security.user'] => array('ROLE_ADMIN', $app['security.pass']),
            ),
        )
    )
));

require '../src/InnerServe/PdnsPhpApi/Controller/domain.php';
require '../src/InnerServe/PdnsPhpApi/Controller/record.php';


$app->run();
