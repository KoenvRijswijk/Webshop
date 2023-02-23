<?php
session_start();
define('DEV', $_SERVER['HTTP_HOST']==='localhost'); 
include './config/'.(DEV?'dev':'prod').'.inc';
require_once SRC.'tools\Tools.php';
require_once SRC.'dal\Crud.php';
require_once SRC.'controllers\MainController.php';
$pagecontroller = new MainController();
$pagecontroller->handleRequest();

//var_dump(WEBIMG_FOLDER);