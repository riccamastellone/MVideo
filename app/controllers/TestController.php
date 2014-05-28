<?php

class TestController extends BaseController {
    
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
    
    
}
