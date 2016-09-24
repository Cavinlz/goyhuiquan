<?php
/**
 * @Author: Cavinlz
 * @Date: May 18, 2015
 *
 */
require_once 'nav.php';
?>
<div class='row-fluid invoice' id="mainarea">
    <div class='span5 box'>
    	<div class="box-header blue-background">
            <div class="title">
                <i class="icon-copy"></i>
                Source Essay
            </div>
            <div class="actions">
                <a href="#" class="btn box-remove btn-mini btn-link"><i class="icon-remove"></i>
                </a>
                <a href="#" class="btn box-collapse btn-mini btn-link"><i></i>
                </a>
            </div>
        </div>
        <div class='box-content box-double-padding'>

            <div class='row-fluid'>
				<div class='span12'>
				<div class='invoice-header'>
                    <div class='invoice-title'  style='line-height:30px;min-height:60px;'>
                        <?php echo $this->data['eng_title']?>
                        
                    </div>
                </div>
					<?php  echo $this -> data['eng_content']?>
				</div>
			 </div>
        </div>
    </div>
    
     <div class='span7 box'>
        <div class='box-header blue-background'>
            <div class='title'><i class="icon-edit"></i> Translation</div>
            <div class='actions'>
                <a href="#" class="btn box-remove btn-mini btn-link"><i class='icon-remove'></i>
                </a>
                <a href="#" class="btn box-collapse btn-mini btn-link"><i></i>
                </a>
            </div>
        </div>
        <div class='box-content box-no-padding ' style="padding:10px;padding-top:20px;">
        <form action="<?php echo $this->router->return_url('articles','savetask');?>" id='forma' method="post">
                    <div class='control-group' >
                        <?php 
                        	$textbox = new CTextbox('Translated Title Here', 'title',array('class'=>'span12','value'=>$this -> data['chs_title']));
                        	$textbox ->render();
                        ?>
                    </div>
        
			<textarea class='input-block-level wysihtml5' id='wysiwyg2' rows='25' name="abc"><?php  echo $this -> data['chs_content']?></textarea>
		</form>
        </div>
    </div>
    
</div>
<?php $this -> load_js_model('article')?>
