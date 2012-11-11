<?php

class MyBB {

	var $username, $password, $post_key, $host;
	var $login_post = 'username=%s&password=%s&remember=yes&submit=Login&action=do_login&url=%s';
	var $send_pm = 'my_post_key=#post_key#&to=#to#&bcc=&subject=#subject#&icon=-1&message_new=#body#&message=#body#&options[signature]=1&options[savecopy]=1&options[readreceipt]=1&action=do_send&pmid=&do=&submit=Send+Message';
	
	function __construct($host) {
		$this->host = $host;
	}
	
	function Login($username = '', $password = '') {
		$data = HTTPRequest($this->host . '/member.php', true, sprintf($this->login_post, $username, $password, $this->host));
		if(preg_match("/You have entered an invalid/i", $data)) {
			return false;
		} else {
			$this->GetPostKey($data);
			return $this->post_key;
		}
	}
	
	function SendPM($to, $subject, $message) {
		$crafted = str_replace("#post_key#", $this->post_key, $this->send_pm);
		$crafted = str_replace("#to#", $to, $crafted);
		$crafted = str_replace("#subject#", $subject, $crafted);
		$crafted = str_replace("#body#", $message, $crafted);
		HTTPRequest($this->host . '/private.php', true, $crafted);
	}
	
	function GetPostKey($response) {
		preg_match_all("/var\ my_post_key\ =\ \"(.+?)\"\;/", $response, $post_key);
		$this->post_key = $post_key[1][0];
	}
	
}

?>