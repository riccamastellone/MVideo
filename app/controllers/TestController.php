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
        $test->save();
        
        $test->explodeTest();
        return array('result' => 'success', 'message' => '');
    }
    
    /**
     * Ritorniamo un test in json
     * @return TestElement
     */
    public function get() {
        return TestElement::queue()->orderBy('created_at','desc')->first();
    }
    
    /**
     * Segna come iniziato un test
     * @param int $id
     * @return TestElement
     */
    public function start($id = null) {
        if(!$id) return;

        $test = TestElement::find($id);
        $test->started = date("Y-m-d H:i:s");
        $test->save();
        return $test;
    }
    
    /**
     * Segnamo come completato un test
     */
    public function complete() {
        
    }
    
}
