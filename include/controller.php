<?php

class Controller {
	public $View;
	public $Model;
	public $params;
	public $post;
	public $get;
	public $action;
	public $controller;
	public $id;
	protected $model;
	
	public function __construct()
	{
		$this->collectParams();

		// setup view
		$this->View = new View;
		
		$Reflection = new ReflectionClass($this);
		$this->controller = str_replace('_Controller', '', $Reflection->getName());
		
		$this->initModel();
		
		$this->action = (!empty($this->params['a'])) ? $this->params['a'] : 'index';
		
		$id = isset($this->params['id']) ? $this->params['id'] : null;
		$this->runAction($this->action, $id);
		
	}
	
	private function initModel()
	{
		if(!$this->model) {
			$model = Inflector::classify($this->controller);
		}
		$this->{$model} = new $model();
	}
	
	public function __destruct() {
		$file = Inflector::underscore($this->controller) 
			. DS . Inflector::underscore($this->action);
		if($this->View->autoRender()) {
			try {
				$this->View->render($file . '.haml');
			} catch(Exception $e) {
				$this->View->render($file . '.phtml');
			}
			
		}
			
	}
	
	private function runAction($action, $id=null)
	{
		try {
			if(empty($id))	$this->{$action}();
			else $this->{$action}($id);
		} catch(Exception $e) {
			echo $e;
		}
	}
	
	
	private function collectParams()
	{
		$this->params = $_REQUEST;
		$this->post = $_POST;
		$this->get = $_GET;
	}
}