<?php

class View {
	private $auto_render = true;
	private $Haml;
	
	public function __construct()
	{
		$this->Haml = new HamlParser(TMP_DIR . DS . 'cache' . DS . 'haml_c');
	}
	
	public function render($file)
	{
		$this->setAutoRender();
		if(isset($this->vars))	extract($this->vars);
		if(preg_match('/^.*\.haml$/', $file, $matches) > 0) {
			$this->Haml->display(VIEW_DIR . DS . $file);
		} else {
			include(VIEW_DIR . DS . $file);
		}
		ob_end_flush();
	}
	
	public function __set($prop, $val)
	{
		$this->Haml->assign($prop, $val);
		$this->vars[$prop] = $val;
	}
	
	public function setAutoRender() {
		$this->auto_render = false;
	}
	
	public function autoRender() {
		return $this->auto_render;
	}
}