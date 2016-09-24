<?php
/**
 * @Author: Cavinlz
 * @Date: May 17, 2015
 *
 */
?>
<div class="application-error 500-error">
<div id='wrapper'>
<div class='error-type <?php echo $class?>'>
        <i class='icon-<?php echo $icon?> '></i>
        <span><?php echo $header?></span>
    </div>
    <div class='error-message'>
       <?php echo $msg?>
    </div>
    <?php 
    	if($links):
    ?>
    <div class='youcan'>
        <small class='text-center'>You can</small>
    </div>
    <?php 
    	foreach ($links as $val):
    ?>
    	<a href="<?php echo $val['url']?>"><i class='icon-<?php echo $val['icon']?>'><?php echo $val['txt']?></i></a>
    <?php 
    	endforeach;
    	endif;
    ?>
    </div>
    </div>