<?php
use Symfony\Component\HttpFoundation\Request;
use \InnerServe\PdnsPhpApi\Service\RecordService as Service;

$record = $app['controllers_factory'];

/**
 * Return Record Information by id
 */
$record->get('/{id}', function($id) use ($app)  {
    $record_service = new Service($app['pdo']);

    try {
        return $app['json_response']->ok($record_service->get($id));
    } catch(\Exception $e) {
        return $app['json_response']->error($e->getMessage());
    }

})->assert('id', '\d+');

/**
 * Return Record Information by name
 */
$record->get('/{name}', function($name) use ($app)  {
    $record_service = new Service($app['pdo']);

    try {
        return $app['json_response']->ok($record_service->getByName($name));
    } catch(\Exception $e) {
        return $app['json_response']->error($e->getMessage());
    }

});

/**
 * Return Record Information By Domain & Type
 */
$record->get('/{domain}/{type}', function($domain, $type) use ($app)  {
    $record_service = new Service($app['pdo']);

    try {
        return $app['json_response']->ok($record_service->getByType($domain, $type));
    } catch(\Exception $e) {
        return $app['json_response']->error($e->getMessage());
    }

});

/**
 * Create a new record
 */
$record->post('/', function(\Silex\Application $app, Request $request) {
    $record_service = new Service($app['pdo']);

    try {
        return $app['json_response']->ok($record_service->create($request->get('domain'), $request->get('name'), $request->get('type'), $request->get('content'), $request->get('ttl'), $request->get('prio')));
    } catch(\Exception $e) {
        return $app['json_response']->error($e->getMessage());
    }
});

/**
 * Update/Overwrite Domain
 */
$record->put('/{id}', function(\Silex\Application $app, Request $request, $id) {
    $record_service = new Service($app['pdo']);

    try {
        return $app['json_response']->ok($record_service->update($id, $request->get('name'), $request->get('type'), $request->get('content'), $request->get('ttl'), $request->get('prio')));
    } catch(\Exception $e) {
        return $app['json_response']->error($e->getMessage());
    }
})->assert('id', '\d+');

/**
 * Return Domain Information
 */
$record->delete('/{id}', function($id) use ($app)  {
    $record_service = new Service($app['pdo']);

    try {
        return $app['json_response']->ok($record_service->delete($id));
    } catch(\Exception $e) {
        return $app['json_response']->error($e->getMessage());
    }

})->assert('id', '\d+');


// mount to the application
$app->mount('/record', $record);