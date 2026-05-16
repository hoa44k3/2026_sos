<?php

$router->get('/', 'HomeController@index');

$router->get('/login', 'AuthController@login');
$router->post('/login', 'AuthController@loginPost');

$router->get('/register', 'AuthController@register');
$router->post('/register', 'AuthController@registerPost');

$router->get('/logout', 'AuthController@logout');

$router->get('/sos', 'SOSController@index');
$router->post('/sos/store', 'SOSController@store');
$router->get('/sos/list', 'SOSController@getSOSList');
$router->post('/sos/nearby', 'SOSController@getNearbySOS');
$router->post('/sos/join', 'SOSController@joinSOS');

$router->post('/sos/complete', 'SOSController@completeSOS');
$router->get('/sos/count', 'SOSController@countSOS');
$router->get('/admin', 'AdminController@index');

$router->post('/admin/delete-sos', 'AdminController@deleteSOS');
$router->get('/chat', 'MessageController@index');

$router->post('/chat/send', 'MessageController@send');

$router->get('/chat/messages', 'MessageController@getMessages');