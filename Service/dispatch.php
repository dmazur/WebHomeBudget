<?php

// load Tonic
require_once '../config.php';
require_once '../Libs/Tonic/src/Tonic/Autoloader.php';

$config = array(
    'load' => array('Resources/*.php'), // load example resources
    //'mount' => array('Tyrell' => '/nexus'), // mount in example resources at URL /nexus
    #'cache' => new Tonic\MetadataCacheFile('/tmp/tonic.cache') // use the metadata cache
    #'cache' => new Tonic\MetadataCacheAPC // use the metadata cache
);

$app = new Tonic\Application($config);

#echo $app; die;

$uri = explode(SERVICE_PATH, $_SERVER['REQUEST_URI']);

$request = new Tonic\Request(array('uri' => $uri[1]));

#echo  print_r($request, true); die;

try {

    $resource = $app->getResource($request);

    #echo $resource; die;

    $response = $resource->exec();

} catch (Tonic\NotFoundException $e) {
    $response = new Tonic\Response(404, $e->getMessage());

} catch (Tonic\UnauthorizedException $e) {
    $response = new Tonic\Response(401, $e->getMessage());

} catch (Tonic\Exception $e) {
    $response = new Tonic\Response($e->getCode(), $e->getMessage());
}

//echo $response;

$response->output();

