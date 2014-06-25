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

}
