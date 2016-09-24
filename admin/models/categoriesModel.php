<?php
/**
 *
 * @author Administrator
 *        
 */
class categoriesModel extends \Model
{

    /**
     */
    public function __construct ()
    {
        parent::__construct();
        $this -> setTable('card_category');
    }
    
    
    public function get_categories()
    {
        if(!$rs = CMemory::mc_get('cardcategory')){
            $rs = $this -> select('*') -> query();
            CMemory::mc_set('cardcategory', $rs,0,7200);
        }
        
        return $rs;
    }
}

?>