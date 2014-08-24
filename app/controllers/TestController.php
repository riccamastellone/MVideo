<?php

class TestController extends BaseController {
    
    /**
     * Creiamo un nuovo test e esplodiamo la lista dei signoli test
     * @return String
     */
    public function create() {
      
        $json = Input::all();
        
        $test = new Test();
        $test->media = $json['media'];
        $test->network = $json['network'];
        
        if($json['signal_strenght_steps'])
            $test->signal_strenght_steps = $json['signal_strenght_steps'];
        
        if($json['volume_steps'])
            $test->volume_steps = $json['volume_steps'];
        
        if($json['brightness'])
            $test->brightness_steps = $json['brightness'];
	
	if(isset($json['length']))
            $test->max_length = $json['length'];
	
        $test->save();
        
        $test->explodeTest();
        return array('result' => 'success', 'message' => 'New tests created');
    }
    
    /**
     * Ritorniamo il test corrente se presente, uno nuovo altrimenti.
     * Quando viene preso un nuovo test, viene anche settata la potenza del wifi,
     * causando un discreto delay nella risposta in quando deve stabilire una connessione
     * SSH al router. Sarebbe da utilizzare un queue manager per gestire questa cosa
     * in maniera asincrona ( o usare exec() )
     * @return Array
     */
    public function get() {
        if(!$this->currentTest()) {
	    $test = TestElement::queue()->orderBy('created_at','desc');
	    if(!$test->count()) {
		return array('status'=> 'error', 'message' => 'No test available');
	    } else {
		$test = $test->first();
		$test->max_length = self::convertSeconds($test->max_length);
	    }
	    MVideo\Controller::setWifi($test->signal_strenght);
            return array('status'=> 'success', 'message' => 'New test retrieved', 'data' => $test->toArray());
        } else {
	    $test = $this->currentTest();
	    $test->max_length = self::convertSeconds($test->max_length);;
            return array('status'=> 'success', 'message' => 'Current test retrieved', 'data' => $test->toArray());
        }
        
    }
    
    /**
     * Convertiamo il formato hh:mm:ss in secondi
     * @param string $str_time
     * @return int
     */
    private static function convertSeconds($str_time) {
	$str_time = preg_replace("/^([\d]{1,2})\:([\d]{2})$/", "00:$1:$2", $str_time);
	sscanf($str_time, "%d:%d:%d", $hours, $minutes, $seconds);
	return $hours * 3600 + $minutes * 60 + $seconds;
    }
    
    /**
     * Segna come iniziato un test
     * @param int $id
     * @return TestElement
     */
    public function start() {
	$id = Input::json('test-id');
        if(!$id) return array('status'=> 'error', 'message' => 'Invalid test id');
        
        // Non vogliamo avere più di un test avviato
        if($this->currentTest() && $this->currentTest()->id != $id) {
            return array('status'=> 'error', 'message' => 'You already started an other test!');
        }

        $test = TestElement::find($id);
	if(!$test) {
	    return array('status'=> 'error', 'message' => 'Invalid test id'); 
	} 
        $test->started = date("Y-m-d H:i:s");
        $test->save();
        return $test;
    }
    
    /**
     * Segnamo come completato un test
     */
    public function complete() {
        $data = Input::json();
	$testElement = TestElement::find($data->get('test-id'));
	if(!$testElement) {
	    return array('status'=> 'error', 'message' => 'Invalid test id'); 
	} else if(!$testElement->started) { // Test non risulta iniziato
	    return array('status'=> 'error', 'message' => 'This test was never marked as started'); 
	} else if (Result::find($data->get('test-id')) &&  $testElement->completed) {
	    return array('status'=> 'error', 'message' => 'Results already exist for this test');  
	}
	
	$result = new Result();
	$result->test_id = $testElement->id;
	$result->imei = $data->get('imei');
	$result->brightness = $data->get('brightness');
	$result->volume = $data->get('volume');
	$result->used_battery = $data->get('battery used');
	$result->voltage = $data->get('voltage');
	$result->temperature = $data->get('temperature');
	$result->health = $data->get('health');
	$result->technology = $data->get('technology');
	$result->wifi = $data->get('wifi status');
	$result->ssid = $data->get('SSID');
	$result->speed = $data->get('speed');
	$result->signal_strength = $data->get('signal strength');
	$result->mobile_status = $data->get('mobile status');
	$result->mobile_network_type = $data->get('mobile network type');
	$result->ip = $_SERVER['REMOTE_ADDR'];
	$result->save();
	
	$testElement->completed = date("Y-m-d H:i:s");
	$testElement->save();
	
	return array('status'=> 'success', 'message' => $result->toArray()); 
    }
    
    /**
     * Ritorniamo il current test se presente, false altrimenti
     * @return boolean|TestElement
     */
    private function currentTest() {
        $query = TestElement::whereNotNull('started')->where('completed', NULL)->orderBy('created_at','desc');
        if($query->count()) {
            return $query->first();
        } else {
            return false;
        }
    }
    
}
