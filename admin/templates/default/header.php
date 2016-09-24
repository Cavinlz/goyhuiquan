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
    <link href='<?php echo $template_url;?>/styles/font-awesome.css' media='all' rel='stylesheet' type='text/css' />
<?php 
	$this -> get_html_stylesheet();
?>
	<link href='<?php echo $template_url;?>/styles/light-theme.css' id='color-settings-body-color' media='all' rel='stylesheet' type='text/css' />
	<link href='<?php echo $template_url;?>/styles/style.css' id='color-settings-body-color' media='all' rel='stylesheet' type='text/css' />

</head>
 