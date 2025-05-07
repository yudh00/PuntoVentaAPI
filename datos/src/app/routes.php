<?php
namespace App\controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use Slim\Routing\RouteCollectorProxy;

use App\controllers\Producto;
use App\controllers\Artefacto; 

$app->get('/', function (Request $request, Response $response, $args) {
    $response->getBody()->write("FCB 9 - 2 RVAR");
    return $response;
});

$app->group('/api', function (RouteCollectorProxy $api) {
    
    // Grupo para Producto
    $api->group('/producto', function (RouteCollectorProxy $producto) {
        $producto->get('/read[/{id}]', Producto::class . ':read');
        $producto->post('', Producto::class . ':create');
        $producto->put('/{id}', Producto::class . ':update');
        $producto->delete('/{id}', Producto::class . ':delete');
        $producto->get('/filtrar', Producto::class . ':filtrar');
    });

    // Grupo para Artefacto
    $api->group('/artefacto', function (RouteCollectorProxy $artefacto) {
        $artefacto->get('/read[/{id}]', Artefacto::class . ':read');
        $artefacto->post('', Artefacto::class . ':create');
        $artefacto->put('/{id}', Artefacto::class . ':update');
        $artefacto->delete('/{id}', Artefacto::class . ':delete');
    });

});
