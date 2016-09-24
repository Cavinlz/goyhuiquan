<?php
/**
 * @Author: Cavinlz
 * @Date: May 9, 2016
 *
 */
?>
<div class='row-fluid'>
    <div class='span12'>
        <div class='page-header'>
            <h1 class='pull-left'>
                <i class='icon-<?php echo $page_icon;?>'></i>
                <span><?php echo $page_header?></span>
            </h1>
            <div class='pull-right'>
            <?php 
            	if($page_btn_group):
            ?>
             <div class='btn-group'>
             	<?php 
             		foreach($page_btn_group as $btn):
             	?>
	            <a href="<?php echo $btn['link']?>" class="btn btn-large <?php echo $btn['cls']?>" id="<?php echo $btn['id']?>"><i class='icon-<?php echo $btn['icon']?>'></i>
	               <?php echo $btn['txt']?>
	            </a>
				<?php 
					endforeach;
				?>
			<?php 
				endif;
			
				if($btn_action_dropdown):	
			?>
		
			<a class='btn btn-large btn-primary dropdown-toggle' data-toggle='dropdown' style=''>
                    Action
                    <span class='caret'></span>
                </a>
                <ul class='dropdown-menu'>
                	<?php  
                		$counter = 0;
             			foreach($btn_action_dropdown as $btn):
             			$counter++;
             		?>
                    <li>
                        <a href='<?php echo $btn['link']?>' id="<?php echo $btn['id']?>"><i class='icon-<?php echo $btn['icon']?>'></i>  <?php echo $btn['txt']?></a>
                    </li>
                    <?php 
                    	if($counter %2 ==0):
             		?>
             		<li class="divider"></li>
             		<?php 
             			endif;
             		
            		endforeach;
            ?>
            </ul>
            <?php 
            	endif;
            ?>
        	</div>
            <!-- 
                <ul class='breadcrumb'>
                    <li>
                        <a href="index.html"><i class='icon-home'></i> Dashboard</a>
                    </li>
                    <li class='separator'>
                        <i class='icon-angle-right'></i>
                    </li>
                    <?php echo $page_nav; ?>
                    <li class='active'><i class='icon-<?php echo $page_icon;?>'></i> <?php echo $page_header?></li>
                </ul>
                 -->
            </div>
        </div>
    </div>
</div>