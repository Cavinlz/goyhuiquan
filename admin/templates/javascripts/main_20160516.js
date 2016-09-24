var app = 'yhuigo/'; 

var gconfig = {
		
		tpl:'http://'+window.location.host+'/'+app,
		lib:'http://'+window.location.host+'/'+app+'libraries/assets/javascripts/',

};

require.config({
    baseUrl: gconfig.tpl+'admin/templates/javascripts',
    shim: {   //define the dependencies
        'bs': {
            deps: ['$']
        },
        'sld': {
        	deps:  ['$']
        },
        'scroll':{
        	deps: ['$']
        },
        'bks':{
        	 deps: ['$']
        },
        'fn':{
        	 deps: ['$']
        },
        'dpk':{
        	deps: ['bs']
        },
        'nav':{
        	deps: ['$','mbevnt']
        },
        'mbevnt':{
        	deps: ['$']
        },
        'md5':{
        	deps: ['$']
        },
        'editor': {
            deps: ['$']
        },
        'bs-wysihtml5':{
        	deps: ['$','editor']
        }
        
    },
    paths: {
    	'$': gconfig.lib+'jquery-2.0.3.min',
    	'fn'	 :gconfig.lib+'general_func',
        'c':'config',
        'bs': gconfig.lib+'bootstrap',
        'theme':'theme',
        'scroll':'jquery.scrollLoading-min',
        'mbevnt':'plugins/mobile_events/jquery.mobile-events.min',
        'bks':'blocksit.min',
        'dpk':'bootstrap-datetimepicker.min',
        'nav':'nav'							,
        'md5':'plugins/md5/md5.min'			,
        'editor': 'plugins/xeditable/wysihtml5.min',
        'bs-wysihtml5':'plugins/xeditable/bootstrap-wysihtml5',
        'bst':gconfig.lib+'bootstrap-switch.min',
        'finput'  :'plugins/fileinput/fileinput.min',
        /**
         * Customed CZ JS Module BreakDown
         * 
         */
        'log'		:'login'				,
        'config' 	:'configuration'		,
        'card'		:'card'

    },
    map: {
        '*': {
            'css': 'requirejs/require-css',
            'text': 'requirejs/require-text'
        }
    }
});

require(['c','bs','nav','fn'], function() {'use strict'
	
	
	 var model = $('#jsmodel').data('model');
	 var lang = $('#cvz').data('lang');
	 
	 if(lang != undefined)
	{ 
		 require([lang]);
		 czcool.language = lang;
	}
	 
	 $(".box .box-remove").click(function(e) {
         $(this).parents(".box").first().remove();
         return e.preventDefault();
     });
     $(".box .box-collapse").click(function(e) {
         var box;

         box = $(this).parents(".box").first();
         box.toggleClass("box-collapsed");
         return e.preventDefault();
     });
		if(model != undefined)
		{
			require([model], function(app) {
			        app.run();
			 });
		}
});