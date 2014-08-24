<?php

class Result extends Eloquent {
    
     protected $table = 'results';
     
    /**
     * Relazione con il test
     */
    public function test()
    {
        return $this->belongsTo('TestElement','test_id');
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