<?php
/**
 * @Author: Cavinlz
 * @Date: May 12, 2015
 *
 */
$router = $this -> load('router');
require_once 'nav.php';

$card_basic_info = (Object)$card_info['card'][strtolower($card_info['card']['card_type'])]['base_info'];
//print_r($card_basic_info);

?>
<div class='row-fluid invoice'>
    <div class='span12 box'>
        <div class='box-content box-double-padding'>
            <div class='row-fluid'>
                <div class='invoice-header'>
                    <div class='invoice-title'>
                        <?php echo $card_basic_info ->title?>
                        <span class='muted'><?php echo $card_basic_info->sub_title?></span>
                    </div>
                    <div class='invoice-number'>
                        <span class='invoice-name'>卡券ID</span>
                        <span class='invoice-no'><?php echo $card_basic_info->id?></span>
                    </div>
                </div>
            </div>
            <div class='row-fluid'>
<table class="table table-bordered table-striped" id="inplaceediting-user" style="margin-top: 20px">
                <tbody>
                <tr>
                    <td style="width:15%">商户名称</td>
                    <td style="width:35%"><?php echo $card_basic_info->brand_name?></td>
                    <td style="width:15%">卡券类型</td>
                    <td style="width:35%"><?php echo card_types($card_info['card']['card_type'])?></td>
                </tr>
                <tr>
                    <td style="width:15%">当前库存</td>
                    <td style="width:35%"><font color='red'><?php echo $card_basic_info->sku['quantity']?></font> / <?php echo $card_basic_info->sku['total_quantity']?></td>
                    <td style="width:15%">自定义券码?</td>
                    <td style="width:35%"><?php echo $card_basic_info -> use_custom_code?'是':'否'?></td>
                </tr>
                <tr>
                    <td style="width:15%">每人可以领取卡券数量</td>
                    <td style="width:35%"><?php echo $card_basic_info -> get_limit?> 张</td>
                    <td style="width:15%">券码类型</td>
                    <td style="width:35%"><?php echo card_code_types( $card_basic_info ->code_type)?></td>
                </tr>
                <tr>
                    <td>使用时间的类型</td>
                    <td><?php echo card_efficient_type($card_basic_info->date_info ['type'])?></td>
                    <td>有效期范围 (固定日期区时有效)</td>
                    <td><i class='icon-calendar'></i> <?php echo format_timestamp_onlydate($card_basic_info->date_info['begin_timestamp'])?> 至 <?php echo format_timestamp_onlydate($card_basic_info->date_info['end_timestamp']) ?></td>
                </tr>
                <tr>
                    <td>自领取后多少天内有效<br>(固定时长时有效)</td>
                    <td><?php echo $card_basic_info-> date_info['fixed_term']?></td>
                    <td>自领取后多少天开始生效<br>(固定时长时有效)</td>
                    <td><?php echo $card_basic_info-> date_info['fixed_begin_term']?></td>
                </tr>
                <!-- 
                <tr>
                    <td>卡券统一过期时间<br>(固定时长时有效)</td>
                    <td colspan='3'><?php echo format_timestamp_onlydate($card_basic_info->date_info -> end_timestamp)?></td>
                </tr>
                 -->
                <tr>
                    <td>卡券颜色</td>
                    <td><div class='' style ="background:<?php echo $card_basic_info -> color?>;width:25px;height:25px;"></div></td>
                    <td>卡券使用提醒</td>
                    <td><?php echo $card_basic_info -> notice?></td>
                </tr>
               
                <tr>
                    <td>使用说明</td>
                    <td><?php echo nl2br($card_basic_info->description)?></td>
                    <td>优惠详情</td>
                    <td><?php echo nl2br($card_info['card'][strtolower($card_info['card']['card_type'])]['default_detail'])?></td>
                </tr>
                </tbody>
            </table>
            </div>
            <div class='row-fluid'>
                <div class='span12'>
                    <div class='responsive-table'>
                        <div class='scrollable-area'>
                            <table class='table table-striped table-hover table-bordered' id="cardcodes">
                                <thead>
                                <?php 
	if($headers = $this -> get_table_headers()):
		foreach($headers as $val):
?>
                          <th><?php echo $val;?></th>
<?php 
		endforeach;
	endif;
?>
                                </thead>
                                <tbody>
                                <tr>
                                   <?php 
                                    if($this -> get_records()):
                                    
                                    	do{
                                    		$val = $this -> get_current(false);
                                    ?>
                                    <tr>
                                    <?php 
                                    	for ($i = 0; $i < count($val); $i++):
                                    ?>
                                    <td><?php echo $val[$i]?></td>
                                    <?php 
                                    	endfor;
                                    ?>
                                    </tr>
                                    <?php 
                                    	}while($this -> has_next()); 
                                    	
                                    endif;
                                    ?>
                                </tr>
                                
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <hr class='hr-normal' />
            <div class="row-fluid">
<div class="span12 text-right"><div class="dataTables_paginate paging_bootstrap pagination pagination-small">
  <?php $this -> print_page_nav($url,'faltty');?>
</div></div>
</div>
            <div class='row-fluid'>
                <div class='span12'>
                    <div class='well comment'>
                        Notes: Please contact IT team in case you have any doubts.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div><?php $this -> load_js_model('card')?>