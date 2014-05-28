<?php

class Test extends Eloquent {
    
     protected $table = 'tests';
     
    public function tests()
    {
        return $this->hasMany('TestElement');
    }
    
    /**
     * In base ai parametri, ritorniamo il numero totale di test necessari per 
     * completare la richiesta
     * 
     * @return int
     */
    public function getTotalTests() {

        if($this->volume_steps) {
            $audio = floor(100/$this->volume_steps);
        } else {
            $audio = 1;
        }
    
        if($this->signal_strenght_steps) {
            $wifi = floor(100/$this->signal_strenght_steps);
        } else {
            $wifi = 1;
        }

        if($this->brightness_steps) {
            $brightness = floor(100/$this->brightness_steps);
        } else {
            $brightness = 1;
        }

        if($this->network == '3g') {
            $network = 2;
        } else {
            $network = 1;
        }

        return $audio*$wifi*$brightness*$network;
    }
    
    /**
     * Con i parametri richiesti generiamo la lista di tutti i test se questo 
     * non è gia stato fatto
     * 
     * @return boolean
     */
    public function explodeTest() {
        if($this->exploded) {
            return false;
        }
        

        if($this->brightness_steps) {
            $i = 100;
            while($i >= 0) {
                $brightness[] = $i;
                $i = $i - $this->brightness_steps;
            }
        } else {
            $brightness[] = 80; // Luminosità di default
        }
        
        if($this->signal_strenght_steps) { // TODO - Wifi in realtà non è in 0-100%
            $i = 100;
            while($i >= 0) {
                $signal[] = $i;
                $i = $i - $this->signal_strenght_steps;
            }
        } else {
            $signal[] = 100; // Segnale wifi di default
        }
        
        if($this->volume_steps) { // TODO - Volume in realtà non è in 0-100%
            $i = 100;
            while($i >= 0) {
                $volume[] = $i;
                $i = $i - $this->volume_steps;
            }
        } else {
            $volume[] = 0; // Livello volume di default
        }
        
        
        
        foreach ($brightness as $bright) {
            foreach ($signal as $sig) {
                foreach ($volume as $vol) {
                    $test = new TestElement();
                    $test->media = $this->media;
                    $test->test_id = $this->id;
                    $test->brightness = $bright;
                    $test->signal_strenght = $sig;
                    $test->volume = $vol;
                    $test->save();
                    
                    if($this->network == '3g') {
                        $test = new TestElement();
                        $test->media = $this->media;
                        $test->test_id = $this->id;
                        $test->brightness = $bright;
                        $test->signal_strenght = $sig;
                        $test->volume = $vol;
                        $test->network = '3g';
                        $test->save();
                    }  
                }     
           }
        }
        $this->exploded = 1;
        $this->save();
        return true;
    }
}