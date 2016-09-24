var app = ''; 

var gconfig = {
		
		tpl:'http://'+window.location.host+'/'+app,
		lib:'http://'+window.location.host+'/'+app+'libraries/assets/javascripts/',

};

require.config({
    baseUrl: gconfig.tpl+'templates/javascripts/',  
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
        ,
        'finput':{
        	deps: ['$']
        }
    },
    paths: {
        '$': gconfig.lib+'jquery-2.0.3.min',
        'c':'config',
        'scroll':'plugins/jquery.scrollLoading-min',
        'bs': gconfig.lib+'bootstrap',
      //  'bst':'bootstrap-switch.min',
      //  'wupl':'plugins/webupload/webuploader.min',
      //  'ptypto':'js/jquery.prettyPhoto',
      //  'finput'  :'js/plugins/fileinput/fileinput.min',
       // 'h5val':gconfig.lib+'jquery-html5Validate',
        /**
         * Customed CZ JS Module BreakDown
         * 
         */
        'cm'	 :'custom_func'		,
        'fn'	 :gconfig.lib+'general_func'		,
        'wcjdk'  :'snsshare'

    },
    map: {
        '*': {
            'css': 'requirejs/require-css',
            'text': 'requirejs/require-text'
        }
    }
});

require(['c','bs','fn'], function() {'use strict'
	
	var model = $(czcool.js).data('model');
	//$(".scrollLoading").scrollLoading();

	if(model != undefined)
	{
		require([model]);
	}
    
});