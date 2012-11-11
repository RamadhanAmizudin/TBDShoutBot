<?php
/*
    Helper Function
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


function unhtmlspecialchars($string) {
	$string = str_replace('&amp;', '&', $string);
	$string = str_replace('&#039;', '\'', $string);
	$string = str_replace('&quot;', '\"', $string);
	$string = str_replace('&lt;', '<', $string);
	$string = str_replace('&gt;', '>', $string);
	return $string;
}
       
function e($r) {
	print unhtmlspecialchars($r)."\n";
}

function HTTPRequest($url = '', $post = false, $postfield = '') {
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_HEADER, 1);
	curl_setopt($ch, CURLOPT_TIMEOUT, 15);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 6.0; en-US; rv:1.9.2.6) Gecko/20100625 Firefox/3.6.6 ( .NET CLR 3.5.30729; .NET4.0E)");
	curl_setopt($ch, CURLOPT_COOKIEJAR, dirname(__FILE__).'/cookie.txt');
	curl_setopt($ch, CURLOPT_COOKIEFILE, dirname(__FILE__).'/cookie.txt');
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	if($post) {
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postfield);
	}
	$data = curl_exec($ch);
	if($data) {
		return $data;
	} else {
		return false;
	}
}