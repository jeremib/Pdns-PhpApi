<?php
/**
 * User: jeremib
 * Date: 4/23/14
 * Time: 5:29 PM
 */
namespace InnerServe\PDNSPHPAPI;

use InnerServe\PdnsPhpApi\Service\JsonResponseService;
use Silex\Application;
use Symfony\Component\ClassLoader\UniversalClassLoader;

require '../src/InnerServe/PdnsPhpApi/Service/JsonResponseService.php';

define('DB_HOST', '127.0.0.1');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_DATABASE', 'bandvista');

require_once __DIR__.'/../vendor/autoload.php';
$app = new Application();
$app['debug'] = true;

$loader = new UniversalClassLoader();
$loader->registerNamespace('InnerServe', __DIR__.'/../src/');
$loader->register();

$app['pdo'] = new \PDO(sprintf('mysql:host=%s;dbname=%s', DB_HOST, DB_DATABASE), DB_USER, DB_PASS);
$app['pdo']->setAttribute( \PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION );
$app['json_response'] = new JsonResponseService();



require '../src/InnerServe/PdnsPhpApi/Controller/domain.php';


$app->run();
