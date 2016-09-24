<?php
/**
 * @Author: Cavinlz
 * @Date: May 9, 2016
 *
 */
?>
                <div class='row-fluid' style="margin-top:100px;">
                    <div class="span2 box">
                        
                    </div>
                    <div class='span8 box'>
                    <div class="box-header banana-background"  style="margin-top:80px;">
                            <div class="title">
                            <i class="icon-info-sign"></i> 系统信息
                            </div>
                        </div>
                        <div class='box-content box-double-padding'>
                            <form class='form' style='margin-bottom: 0;' enctype = 'multipart/form-data' action='<?=$formaction?>' method='post' id='cardupload'> 
                            <?php 
                            	if($fieldsets =  $this -> get_form_fieldsets()):
                            	
                            		foreach($fieldsets as $fieldset):
                            ?>
                                <fieldset>
                                    <div class='span12'>
                                        <div class='lead'>
                                            <i class='icon-<?php echo $fieldset['icon']?> text-contrast'></i>
                                           <?php echo $fieldset['title']?>
                                        </div>
                                        <small class='muted'><?php echo $fieldset['subtitle']?></small>
                                    </div>
                                </fieldset>
                                 <hr class='hr-normal' />
                           <?php 
                           			endforeach;
                           		endif;
                           ?>
 
                                <?php 
                                	if($page_btn_group):
                                ?>
                                 <div class='btn-group'>
                                 	<?php 
                                 		foreach($page_btn_group as $btn):
                                 	?>
                    	            <a href="<?php echo $btn['link']?>" class="btn"><i class='icon-<?php echo $btn['icon']?>'></i>
                    	               <?php echo $btn['txt']?>
                    	            </a>
                    				<?php 
                    					endforeach;
                    				?>
                    			<?php 
                    				endif;
                    			
                    			?>
                                </div>
                                <div class='form-actions' style='margin-bottom: 0;'>
                                    <div class='text-left'>* 若需要协助, 请发送邮件到管理员邮箱 zliang_148@hotmail.com</div>
                                </div>
                            </form>
                        </div>
                    </div>
                     
                    <div class="span2 box">
                        
                    </div>
                    
                </div>
<div class=''><iframe id="frobj" name="frobj" frameborder="no" border="0" style="width:100%"></iframe></div>
<?php $this -> load_js_model('card','jsmodel',$this->data['properties']);?>