<?php
require_once 'config/database.php';
require_once 'routes/api.php';

$database = new Database();
$db = $database->getConnection();

$router = new Router($db);
$router->handleRequest();
?>