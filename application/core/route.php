<?php

/**
 * Route Class
 *
 * core
 *
 */

class Route{

    static function start(){
        //параметры по умолчанию
        $controller_name = 'Main';
        $action_name = 'index';

        $routes = explode('/', $_SERVER['REQUEST_URI']);

        if(!empty($routes[2])){
            $controller_name = strtok($routes[2], '?');
        }

        if(!empty($routes[3])){
            $action_name = $routes[3];
        }

        // Добавляем префиксы имен файлов
        $model_name = 'Model_' . $controller_name;
        $controller_name = 'Controller_' . $controller_name;
        $action_name = 'action_' .$action_name;

        //Подключаем файлы
        $model_file = strtolower($model_name).'.php';
        $model_path = 'application/models/'.$model_file;

        if(file_exists($model_path)){
            //include $model_path;
            include 'application/models/'.$model_file;
        }

        $controller_file = strtolower($controller_name) . '.php';
        $controller_path = 'application/controllers/' . $controller_file;

        if(file_exists($controller_path)){
            include 'application/controllers/' . $controller_file;
        }
        else{
            Route::ErrorPage404();
        }

        //Работа с контроллеров
        $controller = new $controller_name;
        $action = $action_name;

        if(method_exists($controller, $action)){
            $controller->$action();
        }
        else{
            Route::ErrorPage404();
        }
    }

     static function ErrorPage404(){
        $host = 'http://'.$_SERVER['HTTP_HOST'].'/';
    }

}