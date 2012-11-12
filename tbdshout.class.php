<?php
/*
    TBDShoutBox Class
    Copyright (C) 2012  Ramadhan Amizudin <ramadhan92@gmail.com>

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/


class TBDShoutBox {
	var $add_shout = 'shout_data=%s&shout_key=%s';
	var $host, $path, $data;
	var $last_shout, $shouted;
	var $httpHeader = "GET %s HTTP/1.1\r\nHost: %s\r\nConnection: keep-alive\r\nX-Requested-With: XMLHttpRequest\r\nIf-None-Match: 0\r\nIf-Modified-Since: %s\r\n\r\n";
	var $pushServer, $pushServerPort, $pushServerPath, $fsock;
	static $lastmsg = array();
	
	function __construct($host) {
		$this->host = $host;
		$this->pushServer = 'chat.tbd.my';
		$this->pushServerPort = 80;
		$this->pushServerPath = '/channel?id=tbdshout';
	}
	
	function SendShout($text, $key) {
		HTTPRequest($this->host.'/xmlhttp.php?action=add_shout', true, sprintf($this->add_shout, urlencode($text), $key));
	}
	
	function FetchChat() {
		$this->fsock = fsockopen($this->pushServer, $this->pushServerPort, $errn, $errstr);
		if($this->fsock) {
			fwrite($this->fsock, sprintf($this->httpHeader, $this->pushServerPath, $this->pushServer.":".$this->pushServerPort, gmdate("r", time())));
			if($fread = fread($this->fsock, 8192)) {
				$a = explode("\r\n\r\n", $fread);
				$data = json_decode($a[1]);
				if(!isset($lastmsg[$data->shout_id])) {
					return array('user' => $this->_clean_name($data->uname), 'msg' => strip_tags($this->_clean_msg($data->shout_msg)), 'shout_id' => $data->shout_id);
					$lastmsg[$data->shout_id] = true;
				}
			}
			fclose($this->fsock);
		}
	}
	
	function _clean_name($s) {
		return preg_replace("/\<a target=\"_top\" href=\"(.+)\"\>(.*?)\<\/a\>/", "$2", $s);
	}

	function _clean_msg($s) {
		$s = preg_replace("/\<img src=\"(.+)\" style=\"vertical-align: middle;\" border=\"0\" alt=\"(.+)\" title=\"(.*?)\" \/\>/", ":$3:", $s);
		$s = preg_replace("/<a href=\"(.+)\" target=\"_blank\"\>(.*?)\<\/a\>/", "$1", $s);
		$s = preg_replace("/\<div class=\"codeblock\"\>
	\<div class=\"title\"\>Code:\<br \/\>
	\<\/div\>\<div class=\"body\" dir=\"ltr\"\>\<code\>(.*?)\<\/code\>\<\/div\>\<\/div\>/", "code:$1:code", $s);
		$s = unhtmlspecialchars($s);
		return $s;
	}
}

?>