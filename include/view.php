<?php

class View {
	private $auto_render = true;
	
	public function render($file)
	{
		$this->setAutoRender();
		if($this->vars)	extract($this->vars);

		include(VIEW_DIR . DS . $file);
		ob_end_flush();
	}
	
	public function __set($prop, $val)
	{
		$this->vars[$prop] = $val;
	}
	
	public function setAutoRender() {
		$this->auto_render = false;
	}
	
	public function autoRender() {
		return $this->auto_render;
	}
}