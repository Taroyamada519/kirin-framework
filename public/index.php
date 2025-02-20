<?php
require_once __DIR__ . '/../vendor/autoload.php';

use App\Core\Router;
use App\Core\Request;
use App\Core\Response;

// エラーハンドリングの設定
error_reporting(E_ALL);
ini_set('display_errors', 1);

// CORSヘッダーの設定
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

// リクエストの処理
$request = new Request();
$response = new Response();
$router = new Router();

// ルートの定義
$router->get('/api/users', 'UserController@index');
$router->get('/api/users/{id}', 'UserController@show');
$router->post('/api/users', 'UserController@store');
$router->put('/api/users/{id}', 'UserController@update');
$router->delete('/api/users/{id}', 'UserController@delete');

try {
    $router->dispatch($request, $response);
} catch (Exception $e) {
    $response->json([
        'error' => true,
        'message' => $e->getMessage()
    ], 500);
}
