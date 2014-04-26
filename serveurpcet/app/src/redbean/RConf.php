<?php

use RedBean_Facade as R;

class RConf {
	public static function conf(){
		R::setup('mysql:host=localhost;dbname=pcet','root','');
	}
	
	public static function confTest(){
		R::setup('mysql:host=localhost;dbname=pcettest','root',''); //sqlite
	}
}
?>