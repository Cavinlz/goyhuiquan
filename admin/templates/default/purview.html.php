<?php
/**
 * @Author: Cavinlz
 * @Date: May 26, 2015
 *
 */

?>
<div class='group-header' style="border-top:0px">
    <div class='row-fluid'>
        <div class='span6 offset3'>
            <div class='text-center'>
                <h2><i class='icon-<?php echo $page_icon;?>'></i> <?php echo $page_header?></h2>
                <small class='muted'>Here is the model to assign required previleges to respective system user roles.</small>
            </div>
        </div>
    </div>
</div>
<hr class='hr-normal' style='margin-bottom:0px;'>
<div class='box-content'>
            <div class='row-fluid'>
                <div class='span12'>
                    <div class='tabbable tabs-left'>
                    <?php 
                    	if(!empty($roles)):
                    		$content_divs = array();
                    		$counter = 1;
                    ?>
                        <ul class='nav nav-tabs' style="padding-top:20px;border-color:#eee;min-height:800px">
                        	<?php 
                        		foreach ($roles as $val):
                        			$cls = ($counter == 1)?'active':'';
                        	?>
                            <li class='<?php echo $cls;?>' id="tab-<?php echo $val['rid']?>">
                                <a data-toggle='tab' href='<?php echo '#role-'.$val['rid']?>'>
                                    <span class='label label-important'><i class='icon-chevron-sign-right'></i></span>
                                    <?php echo $val['eng_name']?>
                                </a>
                            </li>
                            <?php 
                            		array_push($content_divs, $val['rid']);
                            		$counter++;
                            	endforeach;
                            ?>
                        </ul>
                    <?php 
                    	endif;
                    ?>
                        <div class='tab-content' style="padding-top:20px;padding-left:20px;" id="mainarea">
                    <?php 
                    	if($content_divs):
                    		$counter = 1;
                    		
                    		foreach ($content_divs as $val):
                    		$cls = ($counter == 1)?'active':'';
                    ?>
                            <div class='tab-pane <?php echo $cls?>' id='role-<?php echo $val?>'>
                                <?php 
                                	if($counter == 1):
                                		require "ajax_tpl/purview.tpl.php";
                                	endif;
                                ?>
                            </div>
                    <?php 
                    		$counter++;
                    		endforeach;
                    	endif;
                    ?>
                        </div>
                    </div>
                </div>
            </div>
        </div><div class='fade'><iframe id="frobj" name="frobj"></iframe></div>
        <?php $this -> load_js_model('purview')?>