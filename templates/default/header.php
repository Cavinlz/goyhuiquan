<?php 
//get the template url
$template_url = CFactory::getApplicationTemplateURL();
?>
<!DOCTYPE html>
<head>
      <meta charset="utf-8">
      <title>天虹会员现金券包</title>
     <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport' />
    <!--[if lt IE 9]>
    <script src='<?php echo $template_url;?>/javascripts/html5shiv.js' type='text/javascript'></script>
    <![endif]-->
    <link href="http://libs.baidu.com/bootstrap/3.0.3/css/bootstrap.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="<?php echo $template_url;?>/styles/font-awesome.css" >
    <link href='<?php echo $template_url;?>/styles/yhuigo.css' media='all' rel='stylesheet' type='text/css' />
    <script type="text/javascript" src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<?php 
	$this -> get_html_stylesheet();
?>

</head>
 
