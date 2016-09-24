<ul class='nav nav-stacked'>
<?php
$router = Console::getInstance('router');
         $menu_model = new Model();
         $menu_model -> setTable('system_menu');
            			
            			$where = array(

								'parent_id'				=>		'0'						,
								'active'				=>		'1'
            			);

$query = $menu_model -> select('*') -> where($where) -> order('sort','ASC') -> query();
$counter = 0;
if ($query)
{
	foreach ($query as $val):
		$counter ++;
		$menu = (Object)$val;
		
		$menu_url = 'javascript:void(0)';
		if($menu->config){
			$params = unserialize($menu->config);
			$menu_url = $router -> return_url($params['ctrl'],$params['act']);
		} 

		//$haschild = ($menu -> has_child == 1) ? "dropdown-collapse":"";
?>
<li class=''>
	<?php 
	if($menu -> has_child == 1)
	{
	?>
	<a class='dropdown-collapse' href='#'>
	        <i class='icon-<?php echo $menu->gly_icon;?>'></i>
	        <span><?php echo CLanguage::Text('MENU.'.$menu ->name_index)?></span>
	        <i class='icon-angle-down angle-down'></i>
	</a>
	<?php 
			                $where = array(
			                
			                		'parent_id'				=>		$menu->id			,
									'active'				=>		'1'
			                
			                );
	                		$sub_query = $menu_model -> cleanUp('where') -> where($where) -> query();
	                
	if($sub_query):
?>
	<!-- Sub Menus -->
	<ul class="nav nav-stacked">
	                <?php 
	                		foreach ($sub_query as $sub_val):
	                			$sub_menu = (Object)$sub_val;
		                		if($sub_menu->config){
		                			$params = unserialize($sub_menu->config);
		                			$menu_url = $router -> return_url($params['ctrl'],$params['act']);
		                		}
	                ?>
		<li class=''>
			<a href='<?php echo $menu_url;?>'>
		       <i class='icon-caret-right'></i>
		       <span><?php echo CLanguage::Text('MENU.'.$sub_menu ->name_index)?></span>
		    </a>
		</li>
	                <?php 
		                	endforeach;
		            ?>
	</ul>
<?php 
	endif; // for sub_query
 
    }
    else
	{  // for those who has no child
?>
     <a href='<?php echo $menu_url;?>'>
        <i class='icon-<?php echo $menu->gly_icon;?>'></i>
        <span><?php echo CLanguage::Text('MENU.'.$menu ->name_index)?></span>
     </a>
<?php 
       }
?>
</li>
<?php 
endforeach;   // parent menus
}   //for query
?>

</ul>

