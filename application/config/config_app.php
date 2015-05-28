<?php
    //CONFIGURACION BASICA
    define('BASE_URL', ""); //URL basico para navegar
    define('APP_NAME', 'Nombre del sitio');
    define('APP_SLOGAN', 'quien hace el sitio');
    define('APP_COMPANY', 'www.drink.com.co');
    
    //DATOS DE LA BASE DE DATOS
    define('DB_HOST', 'localhost');
    define('DB_USER', 'root');
    define('DB_PASS', '23camilo');
    define('DB_NAME', 'tiendarigobertouran');
    define('DB_CHAR', 'utf8');
    //RUTAS PARA RENDERIZAR
    define('VISTAS',ROOT . 'views' . DS . 'template' . DS . 'view_rendering' . DS);
    define('BASIC',ROOT . 'views' . DS . 'template' . DS . 'view_basic' . DS);
    define("RECURSOS", BASE_URL . 'views' . DS . 'template' . DS . 'assets' . DS);
    
    //CONFIGURACIONES APLICACION
    define('TYPE_APP',"1");//1 sin loguin dos con loguin. 2 con loguin
    define('CONTROLLERS',APP_PATH.'controllers'.DS);
    define('MODELS',APP_PATH.'models'.DS);
    define('VIEWS',APP_PATH.'view'.DS);
   
    define('LIBRARYS_LOCAL', $_SERVER["DOCUMENT_ROOT"].DS."microstio_chevignon".DS."library".DS);
    define('LIBRARYS',BASE_URL.'library'.DS);
    define('LIBRARYS_ADMIN',"..".DS.BASE_URL.'library'.DS);
    
    define("LANG", "en");
    
    $objeFramework = array("greedTable"=>"Table",
                            "fileLoad"=>"Upload",
                            "ControlaVistas"=>"Vistas"
        );
    
   

