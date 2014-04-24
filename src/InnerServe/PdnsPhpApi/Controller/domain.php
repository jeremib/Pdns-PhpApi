<?php
use Symfony\Component\HttpFoundation\Request;

$domain = $app['controllers_factory'];

/**
 * Return Domain Information by domain
 */
$domain->get('/{domain}', function($domain) use ($app)  {
    $domain_service = new \InnerServe\PdnsPhpApi\Service\DomainService($app['pdo']);

    try {
        return $app['json_response']->ok($domain_service->get($domain));
    } catch(\Exception $e) {
        return $app['json_response']->error($e->getMessage());
    }

});

/**
 * Create a new Domain
 */
$domain->post('/', function(\Silex\Application $app, Request $request) {
    $domain_service = new \InnerServe\PdnsPhpApi\Service\DomainService($app['pdo']);

    try {
        return $app['json_response']->ok($domain_service->create($request->get('domain'), $request->get('type'), $request->get('master')));
    } catch(\Exception $e) {
        return $app['json_response']->error($e->getMessage());
    }
});

/**
 * Update/Overwrite Domain
 */
$domain->put('/{domain}', function(\Silex\Application $app, Request $request, $domain) {
    $domain_service = new \InnerServe\PdnsPhpApi\Service\DomainService($app['pdo']);

    try {
        return $app['json_response']->ok($domain_service->update($domain, $request->get('type'), $request->get('master'), $request->get('new_domain')));
    } catch(\Exception $e) {
        return $app['json_response']->error($e->getMessage());
    }
});

/**
 * Return Domain Information
 */
$domain->delete('/{domain}', function($domain) use ($app)  {
    $domain_service = new \InnerServe\PdnsPhpApi\Service\DomainService($app['pdo']);

    try {
        return $app['json_response']->ok($domain_service->delete($domain));
    } catch(\Exception $e) {
        return $app['json_response']->error($e->getMessage());
    }

});


// mount to the application
$app->mount('/domain', $domain);