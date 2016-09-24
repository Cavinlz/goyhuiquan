<?php
if (defined("CZCool") or die( "Access Denied!" ));
/** 
 * @author Cavinlz
 * 
 */
final class Mailer {
	
	private $email_port = null;
	private $email_server = null;
	private $email_user = null;
	private $email_password = null;
	private $email_from = null;
	private $email_delimiter = "\n";
	private $site_name = null;
	
	public function __construct()
	{
		require_once LibPath.DS.'plugins/swift/swift_required.php';
	}
	
	protected function send($email_to, $subject, $message)
	{
		// Create the Transport
		$transport = Swift_SmtpTransport::newInstance($this->email_server, $this->email_port)
							->setUsername($this->email_user)
							->setPassword($this->email_password);
		
		// Create the Mailer using your created Transport
		$mailer = Swift_Mailer::newInstance($transport);
		
		/*
		if(is_array($email_to)){
			foreach ($email_to as $key => $name){
				$nickname = $name;
			}
		}
		*/
		
		// Create a message
		$message = Swift_Message::newInstance($subject)
							->setFrom(array($this-> email_from =>$this->site_name))
							->setTo($email_to)
		//					->setBody($this->html($subject, $message,current($email_to)),'text/html')
							->setBody($this->html($subject, $message),'text/html')
		;
		
		return $mailer->send($message);
	}
	
	
	public function send_email($email_to, $subject, $message) 
	{
		$this->set("email_server", C('mail.smtp_server'));
		$this->set("email_port", C('mail.smtp_port'));
		$this->set("email_user", C('mail.auth_user'));
		$this->set("email_password", C('mail.auth_pswd'));
		$this->set("email_from", C('mail.from'));
		$this->set("site_name", C('mail.sitename'));

		$result = $this->send($email_to, $subject, $message);
		return $result;
	}
	
	private function html($subject, $message) {
		$message = preg_replace("/href\\=\"(?!http\\:\\/\\/)(.+?)\"/i", "href=\"" . Web_Domain . "\\1\"", $message);
		$tmp.= "<html><head>";
		$tmp.= "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=" . C('html.app_charset') . "\">";
		//$tmp .= '<link href="'.SITE_URL.'/templates/default/openSource/bootstrap-3.2.0/css/bootstrap.css" rel="stylesheet">';
		$tmp.= "<title>" . $subject . "</title>";
		$tmp.= "</head>";
		$tmp.= $this ->html_style();
		$tmp.= '<body style=\"padding-top: 70px;\"><div class="container" style="margin:0px auto">';
		$tmp.= $this ->html_nav();
	//	$tmp.= "<div class='content'>Dear <strong>" . $nickname .'</strong> ,';
	//	$tmp.= "<br />&nbsp;&nbsp;&nbsp;&nbsp;" . $message;
		$tmp.= "<br />" . $message;
		$tmp.= "<br /><div style='font-size:12px;color:#ccc'>Welcome back to the <a href='".Web_Domain."'> ".C('html.web_title')."</a> !</div><br />";
		$tmp.= $this ->html_footer();
		//$tmp.= '</div>';
		$tmp.= "</body></html>";
		$message = $tmp;
		unset($tmp);
		return $message;
	}
	
	private function html_nav()
	{
		return '<div class="header" >'.C('html.web_title').'  </div>
    ';
	}
	
	private function html_style()
	{
		return '<style>
			*{margin:0px;padding:0px;}
			body{font-family:verdana;font-size:12px;width:800px;}
			.content{padding:10px; font-size:12px;min-height:250px;padding-top:30px;}
			.container{width:800px;margin:0px auto;text-align:left;line-height:25px;}
			.header{background-color:#222;color:#777; border-bottom:3px solid #777;padding:10px;font-size:16px;font-weight:bold;}
			.footer{font-size:12px;color:#ccc}
			</style>';
	}
	
	private function html_footer()
	{
		return '<div class="footer">
        <div class="col-md-12">
            <hr />
            <p style="text-align: center;">This is system automatically generated msg. Please do not reply directly.</p>
        </div>
    </div>';
	}
	
	public function get($key) {
		if (!empty($this->$key)) {
			return $this->$key;
		}
		return FALSE;
	}
	
	public function set($key, $value) {
		if (!isset($this->$key)) {
			$this->$key = $value;
			return TRUE;
		}
		return FALSE;
	}
}



function send_sys_msg($to, $message, $subject )
{
	$mail = new Mailer();
	
	switch($subject)
	{
		case 'NT': //new task
			$subject = 'You Got Assigned A New Task';
			break;
		case 'AT': //Accept Task
			$subject = 'Your Translation Request Has Been Accepted By Someone' ;
			break;
		case 'RT': //Reject Task
			$subject = 'Your Translation Request Has Been Rejected By Someone' ;
			break;
		case 'CT': //Complete Task
			$subject = 'Your Translation Request Has Been Done' ;
			break;
		case 'RNT': //Return Task
			$subject = 'Your Submitted Translation Has Been Returned' ;
			break;
	}
	
	switch($message)
	{
		case 'ET': //Essay Translation
			$message = 'Here is to inform you that you have been assigned to translate an article.';
			break;
		case 'EA': //Essay Audit
			$message = 'Here is to inform you that there is an translated article pending for your audit. ';
			break;
	}
	
	if(is_array($to)){
		foreach ($to as $key => $name){
			$mails .= $key.' ,';
		}
	}
	
	if(!$mail ->send_email($to, $subject, $message)){
		
		logg::system('Failed to send email to '.$mails);
		return false;
	}
	
	Logg::system("[swift]Success Send Mail To ".$mails);
	return true;

}
?>