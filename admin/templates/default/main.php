<?php 

$router = Console::getInstance('router');
$logout_url = $router->return_url('login','logout');
$profile_url = $router->return_url('profile');
?>

<body class='fixed-header fixed-navigation contrast-banana'>
<header>
    <div class='navbar navbar-fixed-top'>
        <div class='navbar-inner'>
            <div class='container-fluid'>
                <a class='brand' href='javascript:void(0)'>
                    <i class='icon-cloud-upload'></i>
                    <span class='hidden-phone'>优惠GO平台管理系统</span>
                </a>
                <a class='toggle-nav btn pull-left' href='#'>
                    <i class='icon-reorder'></i>
                </a>
                <ul class='nav pull-right'>
                    <li class='dropdown user-menu'>
                        <a class='dropdown-toggle' data-toggle='dropdown' href='#'  style="height:23px">
                            <span class='user-name hidden-phone'><?php echo $this->get_login_username();?></span>
                            <b class='caret'></b>
                        </a>
                        <ul class='dropdown-menu'>
                            <li>
                                <a href='<?=$profile_url?>'>
                                    <i class='icon-user'></i>
                                    Profile
                                </a>
                            </li>
                            <li class='divider'></li>
                            <li>
                                <a href='<?=$logout_url?>'>
                                    <i class='icon-signout'></i>
                                    Sign out
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</header>
<div id='wrapper'>
<div id='main-nav-bg'></div>
<nav class='main-nav-fixed' id='main-nav'>
<div class='navigation'>
<div class='search'>
    <form accept-charset="UTF-8" action="search_results.html" method="get" /><div style="margin:0;padding:0;display:inline"><input name="utf8" type="hidden" value="&#x2713;" /></div>
        <div class='search-wrapper'>
            <input autocomplete="off" class="search-query" id="q" name="q" placeholder="Search..." type="text" value="" />
            <button class="btn btn-link icon-search" name="button" type="submit"></button>
        </div>
    </form>
</div>
<?php ($this -> has_sidebar())?$this -> load_sidebar():'';?>
</div>
</nav>
<section id='content'>
    <div class='container-fluid'>
        <div class='row-fluid' id='content-wrapper'>
            <div class='span12'>
				<?php 
				if($this->get_template())
					require_once $this->get_template();
				?>
            </div>
        </div>
    </div>
</section>
</div>
</body>
<script
	 id='cvz' 
    data-main="<?php echo $template_url;?>/../javascripts/main.js" data-lang='<?php echo $template_lang;?>'
    src="<?php echo $template_url;?>/../javascripts/requirejs/require.min.js"></script>
</html>