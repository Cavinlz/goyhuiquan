<?php 
$config_tabs = $this -> data['configs'];
?>
<div class="col-md-12">
            
            
	           <div class="widget" >      
                    <div class="widget-content" >
                     <div class="widget-head"></div>
	                    
	                    
                <ul id="myTab" class="nav nav-tabs" style=' padding-top:20px; '>
                <?php 
               
                	if(is_array($config_tabs)):
                		
                		$count = 1;
                		
                		foreach ($config_tabs as $tab):
                		
                			$class = ($count == 1)?'active':'';
                			$id = ($count == 1)?'':'tab_'.$tab['config_key'];
                ?>
	                      <li class="<?php echo $class;?>"><a href="<?php echo '#'.$tab['config_key']; ?>" data-toggle="tab" data-key="<?php echo $tab['config_key']?>" id="<?php echo $id?>"><?php echo CLanguage::Text($tab['config_label'])?></a></li>
	             
	             <?php 
	             		$count++ ;
	             		endforeach;
	             	endif;
	             ?>
	                    </ul>
                  <div class="padd" id="parent-config">
                  
                 <?php 
                 
                 	if($this -> data['total']):
                 	
                 ?>
                    <div class="tab-pane fade in active"  id="<?php echo $this->data['curr_key']?>">
                    	<hr></br>
                         <?php 
                             $this -> form -> setSideWidth(3,4,'md') -> render();
                             
                         ?>
                      </div>
                   <?php 
                   	endif;
                   ?>
                  </div>
                  <div class="widget-foot" >
                    <!-- Footer goes here -->
                  </div>  <div class='fade'><iframe id="frobj" name="frobj"></iframe></div>
               </div>
                </div>
                 
                </div>     
   <?php $this -> load_js_model('config');?>       