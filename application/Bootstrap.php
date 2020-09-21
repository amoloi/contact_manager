<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
	    protected function _initDatabase(){
			define('DB_ADAPTER','Pdo_Mysql');
			define('DB_USERNAME','root');
			define('DB_PASSWORD','');
			define('DB_NAME','proj_contactmanager');
			define('DB_HOST','localhost');
			define('DB_DNS','mysql:host=localhost;dbname=proj_contactmanager');
            
            
            try{
                $OPTIONS = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION);
    		    $connection = new PDO(DB_DNS , DB_USERNAME , DB_PASSWORD , $OPTIONS);
                //return $connection;
                Zend_Registry::set('connection',$connection);
    		}catch(PDOException $pde){
    		    echo $pde->getMessage();
    		}
		//
	}
    
}

