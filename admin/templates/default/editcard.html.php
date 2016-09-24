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
                                        <button class='btn btn-success btn-large' type='submit'>
                                            <?php echo $submit_button_val?> <i class='icon-<?php echo $submit_button_icon?>'></i>
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
                    <?php 
                    if($currentstep == 2):
                    ?>
                    <div class="span3 cardbg">
                    <div class="box" id='cardsample' style="background-color: <?php echo $card_basic_info['color']?>">
                        
                            <div class='box-content box-double-padding'><div class="yhg-card-sample">
                             <div class="text-center" >
                                <img src="<?php echo $card_basic_info['logo_url']?>">
                             </div>
                             <h4 class="text-center" style="font-size:0.8em" id='h5_brand_name'><?php echo $card_basic_info['brand_name']?></h4>
                             <h4 class="text-center" style="font-size:1.5em" id='h5_title'><?php echo $card_basic_info['title']?></h4>
                             <h4 class="text-center" style="font-size:0.9em" id='h5_sub_title'><?php echo $card_basic_info['sub_title']?> </h4>
                             <h4 class="text-center" style="font-size:0.8em">有效期: <span id='h5_begin_timestamp'><?php echo $card_basic_info['date_info']['begin_timestamp']?></span>-<span id='h5_end_timestamp'><?php echo $card_basic_info['date_info']['end_timestamp']?></span></h4>
                             <div class="text-center" ><button class='btn' type='button' id="usebtn">立即使用</button></div>
                             </br>
                             <div class="text-center" style='padding:5px;'>
                                <table class="table  table-striped" style="margin-top: 20px;padding:5px;">
                                <tr><td width='95%'>优惠券详情</td><td width='5%' class='text-right'><i class='icon-angle-right'></i></td></tr>
                                <tr><td width='95%'>公众号</td><td width='5%' class='text-right'><i class='icon-angle-right'></i></td></tr>
                                </table>
                             </div>
                    </div></div>
                    </div>
                    <!-- 
                    <div class="box" >
                        <div class='box-content' style='padding-top:10px;'>
                            <h4 class="text-left" style="font-size:1em;border-bottom:1px solid #ccc;padding-bottom:10px;" id='h5_brand_name'><b>优惠券详情</b></h4>
                            <div class="text-center" style='padding:5px;'>
                                <table class="table" style="margin-top: 20px;padding:5px;">
                                <tr><td width='95%'>优惠说明</td><td width='5%' class='text-right'><i class='icon-angle-right'></i></td></tr>
                                <tr><td width='95%'>有效日期</td><td width='5%' class='text-right'><i class='icon-angle-right'></i></td></tr>
                                </table>
                             </div>
                        </div>
                    </div>
                     -->
                    <div class='clearfix'></div></div>
                    <?php 
                    endif;
                    ?>
                </div>
                
<div class=''><iframe id="frobj" name="frobj" frameborder="no" border="0" style="width:100%"></iframe></div>
<?php $this -> load_js_model('card','jsmodel',$this->data['properties']);?>