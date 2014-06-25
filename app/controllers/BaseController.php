<?php

class BaseController extends Controller {

	/**
	 * Setup the layout used by the controller.
	 *
	 * @return void
	 */
	protected function setupLayout()
	{
		if ( ! is_null($this->layout))
		{
			$this->layout = View::make($this->layout);
		}
	}
	
	/**
	 * Per qualche motivo la libreria di default di Laral non funziona con 
	 * OpenWrt (cerca di stabilire anche una connessione SFTP non supportata)
	 * @return \Net_SSH2
	 */
	static public function connectSSH() {
	    $ssh = new Net_SSH2('192.168.1.1');
	    $key = new Crypt_RSA();
	    $key->loadKey(file_get_contents('../docs/private.key'));
	    if (!$ssh->login('root', $key)) {
		exit('Login Failed');
	    }
	    return $ssh;
	}
	
	/**
	 * Settiamo la potenza di trasmissione in db partendo dalla percenuale
	 * @param int $level [0-100]
	 * @return int
	 */
	static public function setWifi($level = 100) {
	    if(Config::get('app.pretend')) 
		return;
	    if($level > 100 || $level < 1) 
		$level = 100;
	    
	    $dbm = round(log($level, 10)*10);
	    $ssh = self::connectSSH();
	    $ssh->exec('uci set wireless.radio0.txpower='.(int)$dbm);
	    $ssh->exec('uci commit wireless');
	    return $dbm;
	}

}
