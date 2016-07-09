<?php
/**
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use Slim\App;

require __DIR__ . '/../bootstrap.php';


$config = [
	'displayErrorDetails' => true,
	'logger' => [
		'path' => __DIR__ . '/../logs/app.log'
	]
];

$app = new App(["settings" => $config]);
$container = $app->getContainer();

$container['logger'] = function ($config) {
	$logger = new Logger('araneola');
	$fileHandler = new StreamHandler($config['settings']['logger']['path']);
	$logger->pushHandler($fileHandler);
	return $logger;
};

$app->get('/hello/{name}', function (Request $request, Response $response) {
	$name = $request->getAttribute('name');
	$response->getBody()->write("Hello, $name");

	$this->logger->addInfo("log");

	return $response;
});
$app->run();
