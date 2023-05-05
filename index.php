<?php
    require_once 'modules/user/user-controller.php';
    require 'modules/user/user-repository-mysql.php';
    require_once 'shared/db/DB.php';

    $pdo = \api\DB::getInstance();
    $userRepository = new \api\UserRepositoryMysql($pdo);
    $userController = new \api\UserController($userRepository);

    $resource = $_SERVER['REQUEST_URI'];
    $method = $_SERVER['REQUEST_METHOD'];

    function isIDBasedRoute($route) {
        return !!preg_match('/^\/api\/user(?:\/([^\/#\?]+?))[\/#\?]?$/', $route);
    }

    if(isIDBasedRoute($resource) && $method == 'DELETE') {
        $id = explode('/', $resource)[3];

        return $userController->delete($id);
    }

    if(isIDBasedRoute($resource) && $method == 'PUT') {
        $id = explode('/', $resource)[3];
        
        return $userController->update($id);
    }

    if($resource == '/api/user' && $method == 'POST') {
        return $userController->create();
    }

    if($resource == '/api/user' && $method == 'GET') {
        return $userController->getAll();
    }