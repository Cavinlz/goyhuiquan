<?php
/**
 * @Author: Cavinlz
 * @Date: May 31, 2015
 *
 */
?>
<div class='tab-pane fade in active' id='users'>
				    <ul class='unstyled users list-hover list-striped'>
				    <?php 
				    	if($this-> data):
				    		foreach($this-> data as $val):
				    ?>
				        <li>
				            <div class='avatar pull-left'>
				               <i class="icon-<?php echo $val['i']?>"></i>
				            </div>
				            <div class='action pull-left muted'>
				                <a href="javascript:void(0)" class="text-contrast"><?php echo $val['user']?></a>
				                <span class="muted" style="font-size:80%"><?php echo $val['msg']?></span>
				            </div>
				            <small class='date pull-right muted'>
				                <span><?php echo $val['datetime']?></span>
				                <i class='icon-time'></i>
				            </small>
				        </li>
				    <?php 
				    		endforeach;
				    	endif;
				    ?>
				    </ul>
</div>