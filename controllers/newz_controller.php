<?php
class Newz_Controller extends Controller {
	public function index()
	{
		if(!empty($this->params['Newz'])) {
			
			$options = $this->params['Newz'];
			$results = $this->View->results = $this->Newz->find($options);
			$slimmed = array();
			foreach($results as $r) {
				$slimmed[] = array(
					'title' => $r['title'],
					'report_id' => $r['report:id'],
					'description' => strip_tags($r['description'], '<ul><li>')
				);
			}
			$this->View->data = $slimmed;

			$this->View->render('layouts/json.phtml');
		}
	}
	
	public function queue($id) {
		
		global $config;
		$host = $config['host'];
		$port = $config['port'];
		$user = $config['user'];
		$passwd = $config['passwd'];
		
		$action = 'enqueuenewzbin';
		
		$result = $this->Newz->queue($host, $port, $user, $passwd, $action, $id);
		$this->View->data = $result;
		$this->View->render('layouts/json.phtml');
	}
	
	public function status()
	{
		$data = $this->Newz->update();

		$this->View->data = $data;
		$this->View->render('layouts/json.phtml');
	}
}