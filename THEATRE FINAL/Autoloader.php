<?php

namespace app;

class Autoloader{
	// enregistrer l'autoloader auprès du serveur
	static function register(){
		spl_autoload_register(array(__CLASS__,'autoload'));
	}

	static function autoload($class_name){
		if(strpos($class_name,__NAMESPACE__) == 0){
			$class_name = str_replace(__NAMESPACE__,'', $class_name);
			require __DIR__ . '/' .$class_name.'.php';
		}
	}
}

?>
