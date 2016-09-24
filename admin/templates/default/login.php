<?php 
//get the template url
$template_url = CFactory::getApplicationTemplateURL();
?>
<!DOCTYPE html>
<head>
      <meta charset="utf-8">
      <title><?php echo  $this->get_html_title(); ?></title>
     <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport' />
    
    <!--[if lt IE 9]>
    <script src='<?php echo $template_url;?>/javascripts/html5shiv.js' type='text/javascript'></script>
    <![endif]-->
    <link href='<?php echo $template_url;?>/styles/bootstrap/bootstrap.css' media='all' rel='stylesheet' type='text/css' />
    <link href='<?php echo $template_url;?>/styles/bootstrap/bootstrap-responsive.css' media='all' rel='stylesheet' type='text/css' />
	<link href='<?php echo $template_url;?>/styles/light-theme.css' id='color-settings-body-color' media='all' rel='stylesheet' type='text/css' />
<?php 
	$this -> get_html_stylesheet();
?>
</head>
 <body class='contrast-banana sign-in contrast-background'>
<div id='wrapper'>
    <div class='application'>
        <div class='application-content'>
            <a href="javascript:void(0)"><div class='icon-cloud-upload'></div>
                <span>优惠GO平台管理系统</span>
            </a>
        </div>
    </div>
    <?php $this -> load_js_model('log');?> 
    <div class='controls'>
        <div class='caret'></div>
        <div class='form-wrapper'>
            <h1 class='text-center'>Sign in</h1>
            <form accept-charset="UTF-8" method="post" id='loginform'><div style="margin:0;padding:0;display:inline"></div>
                <div class='row-fluid'>
                    <div class='span12 icon-over-input'>
                        <input class="span12" id="email" name="logacc" placeholder="E-mail" type="text" value="" />
                        <i class='icon-user muted'></i>
                    </div>
                </div>
                <div class='row-fluid'>
                    <div class='span12 icon-over-input'>
                        <input class="span12" placeholder="Password" type="password" value="" />
                        <input class="span12" name="logpswd" placeholder="Password" type="hidden" value="" />
                        <i class='icon-lock muted'></i>
                    </div>
                </div>
                <!-- 
                <label class="checkbox" for="remember_me"><input id="remember_me" name="remember_me" type="checkbox" value="1" />
                    Remember me
                </label>
                 -->
                 
                <button class="btn btn-block" name="button" type="button" id='signin'>Sign in</button>
            </form>
            <input type='hidden' value='<?php echo $this -> router -> get('return_url')?>' id='redirect'>
            <div class='text-center'>
                <hr class='hr-normal' />
               <!--  <a href="forgot_password.html">Forgot your password?</a> -->
            </div>
        </div>
    </div>
    <div class='login-action text-center'>
        <a href="javascript:void(0)">
            Copyright 2016 &copy;
            <strong>深圳市八川科技有限公司</strong>
        </a>
    </div>
</div>
</body>
</html>
<script
	 id='cvz' 
    data-main="<?php echo $template_url;?>/../javascripts/main.js" data-lang='<?php echo $template_lang;?>'
    src="<?php echo $template_url;?>/../javascripts/requirejs/require.min.js"></script>