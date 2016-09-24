<?php
/**
 * @Author: Cavinlz
 * @Date: May 9, 2016
 *
 */
$router =  $this -> load('router');
$url = $router -> return_url($router -> route_url['controller'],$router -> route_url['action']);
require_once 'nav.php';
$this -> load_js_model('listview');

?> 
<div class='row-fluid'>
<div class='span12 box bordered-box orange-border' style='margin-bottom:0;'>
<!--  
<div class='box-header grass-green-background'>
    <div class='title'>Sortable data table with pagination</div>
    <div class='actions'>
        <a href="#" class="btn box-collapse btn-mini btn-link"><i></i></a>
    </div>
</div>
-->
<div class='box-content box-no-padding'>
<div class='responsive-table'>

<div class="dataTables_wrapper form-inline">
<div class="row-fluid"><div class="span12"><div class="dataTables_length" ><?php $this -> get_helper_toolbar(); echo $router-> post('query');?></div></div></div>
<hr class='hr-double' />
<div class="responsive-table">
    <div class="scrollable-area">
 <table class="table" id="lv_table">
                      <thead>
                        <tr>
<?php 
	if($headers = $this -> get_table_headers()):
		foreach($headers as $val):
?>
                          <th><?php echo $val;?></th>
<?php 
		endforeach;
	endif;
?>
                        </tr>
                      </thead>
                      <tbody class="gallery">
<?php 
if($this -> get_records()):

	do{
		$val = $this -> get_current(false);
?>
<tr>
<?php 
	for ($i = 0; $i < count($val); $i++):
?>
<td><?php echo $val[$i]?></td>
<?php 
	endfor;
?>
</tr>
<?php 
	}while($this -> has_next()); 
	
endif;
?>
                      </tbody>

</table></div></div>
<hr class='hr-double' />
<!-- pagenav -->
<div class="row-fluid">
<div class="span12 text-right"><div class="dataTables_paginate paging_bootstrap pagination pagination-small">
  <?php $this -> print_page_nav($url,'faltty');?>
</div></div>
</div>
<!-- end pagenav -->
</div>                    

</div>
</div>
</div>
</div>