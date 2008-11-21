<?php
session_start();
ob_start();

define('INCLUDE_DIR', ROOT_DIR . DS . 'include');
define('VIEW_DIR', ROOT_DIR . DS . 'views');
define('CONTROLLER_DIR', ROOT_DIR . DS . 'controllers');
define('MODEL_DIR', ROOT_DIR . DS . 'models');
define('CONFIG_DIR', ROOT_DIR . DS . 'config');

require CONFIG_DIR . DS . 'hella.config.php';
require INCLUDE_DIR . DS . 'inflector.class.php';
require INCLUDE_DIR . DS . 'model.php';
require INCLUDE_DIR . DS . 'view.php';
require INCLUDE_DIR . DS . 'controller.php';

require 'FirePHPCore/fb.php';
