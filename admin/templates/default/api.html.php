<script>
var gb = {
	seccode	:"<?php echo $partner_id?>",
	alw_adt	:<?php echo ($alw_ch_adt)?'true':'false'?>,
	alw_asgn:<?php echo ($alw_ch_assign)?'true':'false'?>
};
</script>
<body style="">
<div class='row-fluid'>
	<div class='span2 box'></div>
	<div class='span8 box'>
		
				 			<div class='box-content box-double-padding'>
				 				<fieldset>
									<div class='span3'>
                                        <div class='lead'>
                                            <i class='icon-<?php echo $fieldset['icon']?> text-contrast'></i>
                                           <?php echo $fieldset['title']?>
                                        </div>
                                        <small class='muted'><?php echo $fieldset['subtitle']?></small>
                                    </div>
                             		<div class='span8 offset1' >
							             <form class='form' style='margin-bottom: 0;' action='<?=$formaction?>' method='post' target="frobj" id='mainarea'> 
							            <?php 
											$this -> form -> render();
							            	?>
							            </form>
							       
		   						 	</div>
		   						 </fieldset>
		   						 <hr class='hr-normal' />
		   						 <div class='form-actions' style='margin-bottom: 0;'>
                                    <div class='text-right'>
                                        <button class='btn btn-primary btn-large' id='submit'>
                                            <i class='icon-cloud-upload'></i>
                                            Publish
                                        </button>
                                    </div>
                                </div>
		   					</div>
		   	
   		</div>
   	<div class='span2 box'></div>
   	<?php $this -> load_js_model('3rdapi')?>
   </div>
</body>
<script
	 id='cvz' 
    data-main="<?php echo $template_url;?>/../javascripts/main.js" data-lang='<?php echo $template_lang;?>'
    src="<?php echo $template_url;?>/../javascripts/requirejs/require.min.js"></script>
</html>