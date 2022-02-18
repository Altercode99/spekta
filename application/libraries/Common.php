<?php
//Created by Tobing Apr 2017	

class Common {
	/*
	public static function gridNoLogin() {
		header ("Content-Type: text/xml; charset=ISO-8859-1 "); 
		//encoding may differ in your case
		echo('<?xml version="1.0" ?>'); 
		//output update results
		echo "<rows>";
		echo "<row id='1'><cell>NOT LOGIN</cell></row>";
		echo "</rows>";
	}

	public static function formLoadNoLogin($firstdata) {
		header ("Content-Type: text/xml; charset=ISO-8859-1 "); 
		//encoding may differ in your case
		echo('<?xml version="1.0" ?>'); 
		//output update results
		echo "<data>";
		echo "<".$firstdata."><![CDATA[NOT LOGIN]]></".$firstdata.">";
		echo "</data>";
	}

	public static function formSaveNoLogin() {
		header ("Content-Type: text/xml; charset=ISO-8859-1 "); 
		//encoding may differ in your case
		echo('<?xml version="1.0" ?>'); 
		//output update results
		echo "<data><action type='error' sid='1' tid='1' >";
		echo "<![CDATA[NOT LOGIN]]>";
		echo "</action></data>";
	}


	public static function comboNoLogin() {
		header ("Content-Type: text/xml; charset=ISO-8859-1 "); 
		//encoding may differ in your case
		echo('<?xml version="1.0" ?>'); 
		//output update results
		echo "<complete>";
		echo "<option value='1'><![CDATA[NOT LOGIN]]></option>";
		echo "</complete>";
	}
	*/

	public static function getName() {
		if ( isset($_SESSION['islogin']) ) {
			return $_SESSION['user'];
		} else {
			echo 'NOT LOGIN';
			die();
		}
	}

	public static function getRole() {
		if ( isset($_SESSION['islogin']) ) {
			return $_SESSION['role'];
		} else {
			echo 'NOT LOGIN';
			die();
		}
	}
}

?>