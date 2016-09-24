<?php
/**
 * @Author: Cavinlz
 * @Date: May 9, 2016
 *
 */
require_once 'nav.php';
?>
<!-- BEGIN 手动输入 -->
                <div class='row-fluid'>
                    <div class='span8 box'>
                    <div class="box">
                        <div class='box-content box-padding'>
                            <fieldset>
                                    <div class='span4'>
                                        <div class='lead'> 
                                            <i class='icon-user-md text-contrast'></i>
                                           <?php echo CLanguage::Text('code_provider')?>
                                        </div>
                                        <small class='muted'><?php echo CLanguage::Text('code_provider_tips')?></small>
                                    </div>
                                    <div class="span7 offset1">
                                        <?php 
                                        
                                        
                                        $form = $this ->create_nonform_obj();
                                        $form
                                        
                                        -> addElement(new CHTML("<p class='muted text-red'>".CLanguage::Text('code_provider_tips_2')."</p>"))
                                        -> addElement(new CSelect('','provider',get_card_codes_provider(), array('value'=>'','class'=>'span12')))
                                        -> render();
                                         ?>
                                     </div>
                                     
                                </fieldset>
                        </div>
                    </div>
                    
                    
                    <div class="box box-nomargin">
                        <div class='box-content box-padding'>
                            <form class='form' style='margin-bottom: 0;' action='<?=$this->router->return_url('cardcodes','load/'.$cardid.'/')?>' method='post' id='manualupload' target='frobj'> 
                                <fieldset>
                                    <div class='span4'>
                                        <div class='lead'>方式一: 
                                            <i class='icon-edit text-contrast'></i>
                                           <?php echo CLanguage::Text('import_manual')?>
                                        </div>
                                        <small class='muted'><?php echo CLanguage::Text('manual_write_tips')?></small>
                                    </div>
                                    <div class="span7 offset1">
                                         <div class="control-group "><label class="control-label " for="details">
                                            <span class="required" style="color:red;font-weight:bold">* </span>请输入Codes</label><div class="controls ">
                                            <textarea rows="3" name="txt_codes" required="" class="span12 form-control" id="details" placeholder="请输入Codes"></textarea></div>
                                         </div><input class="form-control" name="ways" value="manual" type="hidden"> <input class="form-control" id="code_provider_1" name="code_provider" value="" type="hidden"> 
                                         
                                     </div>
                                     <div class='text-right'>
                                        <a class='btn ' data-toggle="modal" data-target = '#Mymodal' role="button" id="manualbtn">
                                            <i class='icon-save'></i> <?php echo $submit_button_val?> 
                                        </a>
                                    </div>
                                </fieldset>
                               </form>
                                 <hr class='hr-normal' />

                               <!-- BEGIN 导入文件 -->  
                                 <form class='form' style='margin-bottom: 0;' enctype = 'multipart/form-data' action='<?=$this->router->return_url('cardcodes','load/'.$cardid.'/')?>' method='post' id='fileupload' target='frobj'> 
                                <fieldset>
                                    <div class='span4'>
                                        <div class='lead'>方式二: 
                                            <i class='icon-upload-alt text-blue'></i>
                                           <?php echo CLanguage::Text('import_file')?>
                                        </div>
                                        <small class='muted'><?php echo CLanguage::Text('import_file_tips')?></small>
                                    </div>
                                    <div class="span7 offset1">
                                         <?php 
                                            $form = $this ->create_nonform_obj();
                                            $form   
                                            -> addElement(new CFile(CLanguage::Text('choose_file'),'prodimg', array('requried'=>true)))
                                            -> addElement(new CHidden('','ways',array('value'=>'file')))
                                            -> render();
                                         ?>
                                    </div><input class="form-control" name="code_provider" value="" type="hidden" id="code_provider_2"> 
                                    <div class='text-right'>
                                        <a class='btn ' data-toggle="modal" data-target = '#Mymodal' role="button" id="filebtn">
                                            <i class='icon-upload-alt'></i> <?php echo $submit_button_val?> 
                                        </a>
                                    </div>
                                </fieldset>
                                 <hr class='hr-normal' />
                                
                            </form>
                        </div>
                        </div>
                    </div>
                </div>
<!-- END 手动输入 -->  
                
<div class=''><iframe id="frobj" name="frobj" frameborder="no" border="0" style="width:100%"></iframe></div>
<?php $this -> load_js_model('cardcodes','jsmodel');?>
   
                                    <div class="modal hide fade" id="Mymodal" role="dialog" tabindex="-1" aria-hidden="true">
                                        <div class="modal-header dark-background">
                                            <h4 style='color:white'><i class="icon-spinner icon-spin"></i> &nbsp;<?php echo CLanguage::Text('GENMSG.REQ_PROCESSOING')?></h4>
                                        </div>
                                        <div class="modal-body" id='progresslog'>
                                            
                                        </div>
                                        <div class="modal-footer">
                                            <button class="btn" data-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                    