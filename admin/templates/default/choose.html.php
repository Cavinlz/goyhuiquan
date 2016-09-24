<?php
/**
 * @Author: Cavinlz
* @Date: May 9, 2016
*
*/
require_once 'nav.php';
?>
                <div class='row-fluid' >
                    <div class='span3 box'></div>
                    <div class='span6 box'>
                        <div class="box-header banana-background"  style="margin-top:80px;">
                            <div class="title">
                            <i class="icon-question-sign"></i>
                                                                            品牌是否已获得授权 ?
                            </div>
                        </div>
                        <div class='box-content box-double-padding'>
                            <div class="row-fluid">
                            <div class="span5 box-quick-link banana-background">
                                <a href="<?php echo $this -> router -> return_url('brands','create',array('step'=>2,'state'=>'auth'))?>">
                                    <div class="header">
                                        <div class="icon-check-sign"></div>
                                    </div>
                                    <div class="content">Yes</div>
                                </a>
                            </div>
                            <div class="span2"></div>
                            <div class="span5 box-quick-link muted-background">
                                <a href="<?php echo $this -> router -> return_url('brands','create',array('step'=>2,'state'=>'nonauth'))?>">
                                    <div class="header">
                                        <div class="icon-minus-sign"></div>
                                    </div>
                                    <div class="content">Not Yet</div>
                                </a>
                            </div>
                            </div>
                        </div>
                    </div>
                    <div class='span3'></div>
                </div>
