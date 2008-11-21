<?php
class Newz_Controller extends Controller {
	public function index()
	{
		if(!empty($this->params['keyword'])) {
			$results = $this->View->results = $this->Newz->search($this->params['keyword']);
			$slimmed = array();
			foreach($results as $r) {
				$slimmed[] = array(
					'title' => $r['title'],
					'report_id' => $r['report:id']
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
}