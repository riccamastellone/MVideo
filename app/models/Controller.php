<?php
/**
 * Classe con metodi statici per il controllo di un router con OpenWrt
 * @author Riccardo Mastellone <riccardo.mastellone@mail.polimi.it>
 */
class Controller {
    
    
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
	    if(Config::get('mvideo.pretend')) {
		return;
	    }
	    
	    if($level > 100 || $level < 1) 
		$level = 100;
		    
	    $dbm = round(log($level, 10)*10);
	    $ssh = self::connectSSH();
	    $ssh->exec('uci set wireless.radio0.txpower='.(int)$dbm);
	    $ssh->exec('uci commit wireless');
	    return $dbm;
	}
	
	/**
	 * Ritorniamo il valore corrente del segnale wifi in dBm o in percentuale
	 * @param bool $returnDbm optional
	 * @return int
	 */
	static public function getWifiStatus($returnDbm = false) {
	    if(Config::get('mvideo.pretend')) {
		return $returnDbm ? 20 : 100;
	    }
	    
	    $ssh = BaseController::connectSSH();
	    $dbm = (int)$ssh->exec('uci get wireless.radio0.txpower');
	    return $returnDbm ? $dbm : exp($dbm)/10;
	}
	
	/**
	 * Accendiamo o stacchiamo le porte USB
	 * @param string $power
	 * @return 
	 */
	static public function power($power = 'on') {
	    
	    if(Config::get('mvideo.pretend')) {
		return;
	    }
	    
	    if($power == 'on') {
		$value = 1;
	    } else {
		$value = 0;
	    }
	    
	    $ssh = BaseController::connectSSH();
	    // Cambiamo i valori di entrambe le porte USB
	    $ssh->exec('echo '.$value.' > /sys/class/gpio/gpio22/value');
	    $ssh->exec('echo '.$value.' > /sys/class/gpio/gpio22/value');
	}
}