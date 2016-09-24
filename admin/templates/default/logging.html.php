<?php
/**
 * @Author: Cavinlz
 * @Date: May 11, 2015
 *
 */
require_once 'nav.php';
?>
<div class='row-fluid'>
				<ul class='nav nav-tabs'>
                            <li class='active'>
                                <a data-toggle='tab' href='#tabsimple1'>
                                    <i class='icon-info-sign text-orange'></i>
                                    <strong><?php echo CLanguage::Text('MENU.APPL_LOG')?></strong>
                                </a>
                            </li>
                            <li>
                                <a data-toggle='tab' href='#tabsimple2'>
                                    <i class='icon-ban-circle text-red'></i>
                                     <strong><?php echo CLanguage::Text('SYSTEM_ERR_LOG')?></strong>
                                </a>
                            </li>
                </ul>
                 <div class='tab-content'>
                    <div class='error-log tab-pane active well comment' id='tabsimple1'>
                    <?php 
                    			if($data):
                    				foreach ($data as $val):
                    					if(empty($val)) continue;
                    					//get the date time
                    					$datetime = substr($val, 0,20);
                    					//get the rest of the infomation
                    					$body = substr($val, 21);
                    					$msg = explode('|',$body);
                    	?>
                    	<p><span class="text-green">[<?php echo $datetime;?>]</span> <span>[<?php echo $this->get_event_type($msg)?>]</span> <span class="text-muted">[<?php echo $this->get_event_session($msg);?>]</span> <span class="text-blue">[<?php echo $this->get_event_url($msg);?>]</span> <span class="text-red"><?php echo $this->get_event_msg($msg);?></span></p>
                    	<?php 
                      			endforeach;
                      		endif;
                      	?>
                    </div>
                    <div class='error-log tab-pane well comment' id='tabsimple2'>
                    <?php 
                    			if($dataerr):
                    				foreach ($dataerr as $val):
                    					if(empty($val)) continue;
                    					//get the date time
                    					$datetime = substr($val, 0,20);
                    					//get the rest of the infomation
                    					$body = substr($val, 21);
                    					$msg = explode('|',$body);
                    	?>
                        <p><span class="text-green">[<?php echo $datetime;?>]</span> <span class="text-muted">[<?php echo $this->get_event_session($msg);?>]</span> <span class="text-blue">[<?php echo $this->get_event_url($msg,'sys');?>]</span> <span class="text-red"><?php echo $this->get_event_msg($msg,'sys');?></span></p>
                        <?php 
                      			endforeach;
                      		endif;
                      	?>
                    </div>
                </div>
                <div class='form-actions' style='margin-bottom: 0;'>
                                    <div class='text-right'>
                                        <button class='btn btn-warning btn-large' type='submit'>
                                            <i class='icon-eraser'></i>
                                            Clean Logs
                                        </button>
                                    </div>
                                </div>
	 
</div>