<?php

require ROOT_DIR . DS . 'vendors' . DS . 'xmlrpc.php';

/**
* Searches Newzbin
*/		
class Newz
{
	public function find($params=array()) {
		require ROOT_DIR . DS . 'vendors' . DS . 'rss_php.php';
		$uri = 'http://v3.newzbin.com/search/query/?fpn=p&searchaction=Go&feed=rss';
		
		$params['area'] = (!isset($params['area'])) ? '-1' : $params['area'];
		$params['areadone'] = $params['area'];
		$params['category'] = str_ireplace('c.', '', $params['area']);
		
		foreach($params as $key=>$val) {
			if(!empty($val))
				$uri_params[] = "{$key}=". urlencode($val);
		}
		
		if(!empty($uri_params)) {
			$uri .= "&" . implode('&', $uri_params);
		}

		try {
			$rss_php = new rss_php;
			$rss_php->load($uri);
			return $rss_php->getItems();
		} catch (Exception $e) {
			echo($e);
		}
		return false;
	}
	
	
	public function queue ($host, $port, $user, $passwd, $action, $id) {

		
		 if ($id <> '')
		 	$f=new xmlrpcmsg($action, array(new xmlrpcval($id, "string")));
		 else
			$f=new xmlrpcmsg($action, "");
			$c=new xmlrpc_client("", $host, $port);
			$c->setCredentials($user,$passwd);
			$c->setDebug(0);
			$r=$c->send($f);
			
			if(!$r->faultCode()) {
				//Got a valid result, decode into php variables
			#	$this->log(var_export(php_xmlrpc_decode($r->value()), true));
				return php_xmlrpc_decode($r->value());
			} else {
				//Got an error, print description
			#	echo "Fault: ";
			#	echo "Code: " . htmlspecialchars($r->faultCode())
			#	. " Reason '" . htmlspecialchars($r->faultString()) . "'<BR>";
			#	$this->log("Code: " . $r->faultCode());
			#	$this->log("Reason: " . $r->faultString());
				return array('code' => $r->faultCode(), 'reason' => $r->faultString());
	         }
	}
	
	public function update()
	{
		global $config;
		$host = $config['host'];
		$port = $config['port'];
		$user = $config['user'];
		$passwd = $config['passwd'];
		
		$f=new xmlrpcmsg("status","");
	    	 //echo "<PRE>Sending the following request:<BR>" . htmlentities($f->serialize()) . "</PRE>\n";
	         $c=new xmlrpc_client("", $host, $port);
	         $c->setCredentials($user,$passwd);
	         $c->setDebug(0);
	         $r=$c->send($f);
	         if(!$r->faultCode())
	         	//Got a valid result, decode into php variables
	                return php_xmlrpc_decode($r->value());
	         else {
				return array('code' => $r->faultCode(), 'reason' => $r->faultString());
		}
	}
}
