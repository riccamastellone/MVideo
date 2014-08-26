<?php 

namespace MVideo;
use Config;

/**
 * Controller Class
 * 
 * Classe con metodi statici per il controllo di un router con OpenWrt.
 * Testato con un TP-Link TL-WDR4300. Le porte gpio relativi alle porte USB
 * potrebbero variare
 * 
 * 
 * @author Riccardo Mastellone <riccardo.mastellone@mail.polimi.it>
 * 
 */
class Controller {
    
	// Ip del router
	static protected $ip = '192.168.1.1';

	// Identificatore del modulo wifi
	static protected $radio = "radio0";

	// General Purpose Input/Output delle porte USB 
	static protected $gpio = array('gpio22','gpio21');
    
	/**
	 * Per qualche motivo la libreria di default di Laral non funziona con 
	 * OpenWrt (cerca di stabilire anche una connessione SFTP non supportata)
	 * @return \Net_SSH2
	 */
	static protected function connectSSH() {
	    $ssh = new \Net_SSH2(self::$ip);
	    $key = new \Crypt_RSA();
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
	    
	    // Il valore 0 non ha senso, come quelli > 100
	    if($level > 100 || $level < 0) 
		$level = 100;
		    
	    $dbm = round(log($level, 10)*10);
	    $ssh = self::connectSSH();
	    $ssh->exec('uci set wireless.'.self::$radio.'.txpower='.(int)$dbm);
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
	    
	    $ssh = self::connectSSH();
	    $dbm = intval($ssh->exec('uci get wireless.'.self::$radio.'.txpower'));
	    return $returnDbm ? $dbm : (10^($dbm/10));
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
	    
	    $ssh = self::connectSSH();
	    // Cambiamo i valori di tutte le porte USB
	    foreach (self::$gpio as $gpio) {
		$ssh->exec('echo '.$value.' > /sys/class/gpio/'.$gpio.'/value');
	    }
	    
	}
	
	/**
	 * Ritorniamo lo stato dell'alimentazione delle porte USB
	 * @return array
	 */
	static public function powerStatus() {
	    $values = array();
	    
	    if(Config::get('mvideo.pretend')) {
		foreach (self::$gpio as $gpio) {
		    $values[$gpio] = 1;
		}
	    } else {
		$ssh = self::connectSSH();
		foreach (self::$gpio as $gpio) {
		    $values[$gpio] = $ssh->exec('cat /sys/class/gpio/'.$gpio.'/value');
		}
	    }
	    return $values;
	}
}