<?php

/** 
 * @author Administrator
 * 
 */
class eventsModel extends \Model
{

    /**
     */
    public function __construct ()
    {
        parent::__construct();
        $this -> setTable('events');
    }
    
    public function save_event($insertArray)
    {
        
    }
}

?>