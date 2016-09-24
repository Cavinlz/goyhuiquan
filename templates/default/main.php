<body>
<!-- 
<header>
    <div class='navbar navbar-default navbar-fixed-top yh-header yh-m-color-bg'  >
            微信公众号: 优惠GO
           <div class="btn-group yhg-nav-btn">
            <button class="btn btn-default dropdown-toggle" data-toggle="dropdown"  type="button"> <span class="icon-ellipsis-horizontal"></span></button>
            <ul class="dropdown-menu">
                <li><a href="#" data-toggle="modal" data-target = '#Mymodal'><i class='icon-qrcode'></i> 公众号二维码</a></li>
            </ul>
        </div>
    </div>
</header>
-->
<div class='container yh-container' style="">
    <div style=''>
        <div class='row' id='content-wrapper'>
            <div class='cols-12'>
				<?php 
				if($this->get_template())
					require_once $this->get_template();
				?>
            </div>
        </div>
    </div>
</div>

</body>

<script
    data-main="<?php echo $template_url;?>/../javascripts/main.js" 
    id="jsmodel"
    src="<?php echo $template_url;?>/../../libraries/assets/javascripts/requirejs/require.min.js"></script>