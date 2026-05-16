<?php

session_start();

require_once "../core/Router.php";

$router = new Router();

require_once "../routes/web.php";

$router->resolve();