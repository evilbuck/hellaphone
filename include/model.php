<?php


function auto_load_model($class) {
	include Inflector::toModelFilename($class);
}
spl_autoload_register('auto_load_model');

class Model {
	
}