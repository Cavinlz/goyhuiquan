var czcool = {
		
		debug			:true
		,
		interval		:6000
		,
		url_friendly	:true
		,
		url_style		:{hashtml:false}
		,
		uat_host		:'http://192.168.3.134/yhuigo/'
		,
		prd_host		:'http://www.yhuigo.com/'
		,
		host			:''
		,
		prod_flag		:true
		,
		animate			:true
		,
		counter			:0
		,
		lastRun			:[]
		,
		language		:'en'
		,
		admin			:''
		,
		rc				:{success:200, error:201, warn:202}
		,
		js				:'#jsmodel'
		,
		
}



if(czcool.prod_flag == true)
{
	czcool.host = czcool.prd_host;
}
else
{
	czcool.host = czcool.uat_host;
}