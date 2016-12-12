<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require '../vendor/autoload.php';
require '../controller/autoload.php';

$config['displayErrorDetails'] = true;
$config['addContentLengthHeader'] = false;

$app = new \Slim\App(["settings" => $config]);

//全局middleware。去掉结尾的/
$app->add(function (Request $request, Response $response, callable $next) {
    $uri = $request->getUri();
    $path = $uri->getPath();
    if ($path != '/' && substr($path, -1) == '/') {
        // permanently redirect paths with a trailing slash
        // to their non-trailing counterpart
        $uri = $uri->withPath(substr($path, 0, -1));
        
        if($request->getMethod() == 'GET') {
            return $response->withRedirect((string)$uri, 301);
        }
        else {
            return $next($request->withUri($uri), $response);
        }
    }

    return $next($request, $response);
});

//添加session
$app->add(function ($request, $response, $next) {
    $_SESSION['blogs'] = array(
            array(
                'id' => 1,
                'title' => '第一篇博客',
                'content' => '<h1>第一篇博客</h1>',
                'create_date' => '20161206',  
            ),
            array(
                'id' => 2,
                'title' => '第二篇博客',
                'content' => '<h1>第二篇博客</h1>',
                'create_date' => '20161207',  
            ),
            array(
                'id' => 3,
                'title' => '第三篇博客',
                'content' => '<h1>第三篇博客</h1>',
                'create_date' => '20161208',  
            ),
        );
    $request = $request->withAttribute('session', $_SESSION);
    return $next($request, $response);
});

$container = $app->getContainer();
//依赖注入
$container['logger'] = function($c) {
    $logger = new \Monolog\Logger('my_logger');
    $file_handler = new \Monolog\Handler\StreamHandler("php://stderr");
    $logger->pushHandler($file_handler);
    return $logger;
};


//路由
$app->group('/api', function(){
    $this->get('/blogs', '\BlogController:blogs');
    $this->get('/blogs/{id}', '\BlogController:blog');
    $this->post('/blogs', '\BlogController:addBlogs');
    $this->put('/blogs/{id}', '\BlogController:updateBlog');
    $this->delete('/blogs/{id}', '\BlogController:deleteBlog');
});

$app->run();