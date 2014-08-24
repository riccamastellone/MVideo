<?php

class TestElement extends Eloquent {
    
     protected $table = 'tests_list';
     
     
    public function test()
    {
        return $this->belongsTo('Test');
    }
    
    /**
     * Relazione con la row risultato
     */
    public function result() {
	$this->hasOne('Result','test_id','id');
    }
    /**
     * Ritorniamo il numero di test ancora da eseguire
     * @param type $query
     * @return int
     */
    public function scopeQueue($query)
    {
        return $query->where('started', NULL);
    }
    
    /**
     * Ritorniamo il numero di test completati
     * @param $query
     * @return int
     */
    public function scopeCompleted($query)
    {
        return $query->whereNotNull('completed');
    }
     
     
}