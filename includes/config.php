<?php
ob_start();
session_start();

//database credentials
define('DBHOST', 'localhost');
define('DBUSER', 'root');
define('DBPASS', 'maimai1A');
define('DBNAME', 'blog');

$db = new PDO("mysql:host=" . DBHOST . ";dbname=" . DBNAME . ";charset=utf8", DBUSER, DBPASS);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$db->exec("SET NAMES utf8");
// $db = mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAME) or die("Connect Failed: " + mysqli_connect_error());

//set timezone
date_default_timezone_set('Asia/Ho_Chi_Minh');

//load classes as needed
spl_autoload_register(function ($class) {

   $class = strtolower($class);

   //if call from within /assets adjust the path
   $classpath = 'classes/class.' . $class . '.php';
   if (file_exists($classpath)) {
      require_once $classpath;
   }

   //  if call from within admin adjust the path
   $classpath = '../classes/class.' . $class . '.php';
   if (file_exists($classpath)) {
      require_once $classpath;
   }

   //  if call from within admin adjust the path
   $classpath = '../../classes/class.' . $class . '.php';
   if (file_exists($classpath)) {
      require_once $classpath;
   }
});
// $url = 'localhost/blog/';
$user = new User($db);
include('functions.php');
