<?php
$router->post('/todo/api/createGroup', 'App\Controllers\Api\TodoController@createGroup');
$router->post('/todo/api/updateGroup', 'App\Controllers\Api\TodoController@updateGroup');
$router->post('/todo/api/deleteGroup', 'App\Controllers\Api\TodoController@deleteGroup');
$router->get('/todo/api/task', 'App\Controllers\Api\TodoController@getAllTask');
$router->get('/todo/api/task/detail', 'App\Controllers\Api\TodoController@getTaskDetail');
$router->post('/todo/api/createTask', 'App\Controllers\Api\TodoController@createTask');
$router->post('/todo/api/toggleStatus', 'App\Controllers\Api\TodoController@toggleStatus');
$router->post('/todo/api/deleteTask', 'App\Controllers\Api\TodoController@deleteTask');
$router->post('/todo/api/updateTask', 'App\Controllers\Api\TodoController@updateTask');
$router->get('/todo', function () {
    require __DIR__ . '/../public/todo.php';
});
$router->get('/dashboard', function () {
    require __DIR__ . '/../public/dashboard.php';
});

$router->get('/profile', function () {
    require __DIR__ . '/../public/profile.php';
});

$router->get('/settings', function () {
    require __DIR__ . '/../public/settings.php';
});

$router->get('/auth/login', function () {
    require_once __DIR__ . '/../public/auth/login.php';
});

$router->post('/auth/login', function () {
    require_once __DIR__ . '/../public/auth/login.php';
});
$router->get('/auth/register', function () {
    require __DIR__ . '/../public/auth/register.php';
});
$router->post('/auth/register', function () {
    require_once __DIR__ . '/../public/auth/register.php';
});
$router->get('/auth/logout', function () {
    require __DIR__ . '/../public/auth/logout.php';
});