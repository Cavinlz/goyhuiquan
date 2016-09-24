<?php
/**
 * @Author: Cavinlz
* @Date: May 9, 2016
*
*/
require_once 'nav.php';
?>
                <div class='row-fluid'>
                    <div class='span8 box'>
                        <div class='box-content box-double-padding'>
                            <form class='form' style='margin-bottom: 0;' enctype = 'multipart/form-data' action='<?=$formaction?>' method='post' id='cardupload'> 
                            <?php 
                            	if($fieldsets =  $this -> get_form_fieldsets()):
                            	
                            		foreach($fieldsets as $fieldset):
                            ?>
                                <fieldset>
                                    <div class='span4'>
                                        <div class='lead'>
                                            <i class='icon-<?php echo $fieldset['icon']?> text-contrast'></i>
                                           <?php echo $fieldset['title']?>
                                        </div>
                                        <small class='muted'><?php echo $fieldset['subtitle']?></small>
                                    </div>
                                    <div class='span7 offset1'>
                                         <?php if(is_object($fieldset['fieldset'])) $fieldset['fieldset']->render();?>
                                    </div>
                                </fieldset>
                                 <hr class='hr-normal' />
                           <?php 
                           			endforeach;
                           		endif;
                           ?>
                                <div class='form-actions' style='margin-bottom: 0;'>
                                    <div class='text-right'>
                                        <button class='btn btn-primary btn-large' type='submit'>
                                            <i class='icon-save'></i>
                                            Save
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <!--  
                    <div class="span3 box">
                        <div class="box-content">
                            <?php echo $column2?>
                        </div>
                    </div>
                    -->
                </div>
<div class=''><iframe id="frobj" name="frobj" frameborder="no" border="0" style="width:100%"></iframe></div>
<?php $this -> load_js_model($jsmodel,'jsmodel');?>