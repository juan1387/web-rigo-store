<?php
//require_once(LIBRARYS_LOCAL."phpmailer".DS."class.phpmailer.php");
//class ControllerMain extends PHPMailer {
class ControllerMain {
    private $_modelo;
    private $path_view;
    private $imagen_base;

    function __construct() {
        $this->_modelo = new ModelMain();
        $this->imagen_base = BASE_URL . 'views' . DS . 'template' . DS . 'assets' . DS;
    }

    private function loguin($array) {
        $_SESSION['sessionusuario'];
        $where = "loguin = '$array[user]' and pwd = '$array[pwd]' and estado = 1";
        $_SESSION['loguin'] = "12";
        $_SESSION['XBesX'] = "2";
        $request = $this->_modelo->selectUsers($where);

        if ($request) {
            $array_request = mysqli_fetch_assoc($request);

            $_SESSION['loguin'] = $array_request['loguin'];
            $_SESSION['XBesX'] = '1';
            $_SESSION['perfil'] = $array_request['perfil'];
            $_SESSION['nombre'] = $array_request['nombre'];
            $_SESSION['id'] = $array_request['idUsuario'];
            $this->redirect('index.php?vista=dashboarh', 1, null);
        } else {
            $this->redirect('index.php', 0, 'Sus datos no son validos');
        }
    }

    private function out() {
        unset($_SESSION['loguin']);
        unset($_SESSION['XBesX']);
        unset($_SESSION['perfil']);
        unset($_SESSION['nombre']);
        unset($_SESSION['id']);
        $this->redirect('index.php', 0, 'Su sesión ha terminado');
    }

    public function redirect($ruta, $sw, $mensaje) {

        if ($sw == 0) {
            echo "<script>
                                        alert('" . $mensaje . "');
                                        window.location ='" . $ruta . "';

                                </script>";
        } else if ($sw == 1) {
            header("Location: " . $ruta);
        }
    }
    public function verificarFuncion($vectorFuncion) {
        if (method_exists($vectorFuncion['1'], $vectorFuncion['2'])) {
            $funcion = $vectorFuncion[2];
         
            $resquest = $this->$funcion($vectorFuncion['post']);
            
            return $resquest;
        } else {
            echo "No exite este metodo";
        }
    }
    /* private function download($files){
      if (!isset($files['file']) || empty($files['file'])) {
      exit();
      }
      $root = "public";
      $file = basename($files['file']);
      $path = $root.$files['folder']."/".$files['carpeta']."/".$file;
      $type = '';

      if (is_file($path)) {
      $size = filesize($path);
      if (function_exists('mime_content_type')) {
      $type = mime_content_type($path);
      } else if (function_exists('finfo_file')) {
      $info = finfo_open(FILEINFO_MIME);
      $type = finfo_file($info, $path);
      finfo_close($info);
      }
      if ($type == '') {
      $type = "application/force-download";
      }

      header("Content-Type: $type");
      header("Content-Disposition: attachment; filename=\"$file\"");
      header("Content-Transfer-Encoding: binary");
      header("Content-Length: " . $size);
      // descargar achivo
      readfile($path);

      } else {
      echo $path;
      die("File not exist !!");
      }
      }
     */
    function download_file($files, $downloadfilename = null) {
        $file = basename($files['file']);
        $archivo = "public/" . $files['carpeta'] . "/" . $file;
        if (file_exists($archivo)) {
            $downloadfilename = $downloadfilename !== null ? $downloadfilename : basename($archivo);
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename=' . $downloadfilename);
            header('Content-Transfer-Encoding: binary');
            header('Expires: 0');
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header('Pragma: public');
            header('Content-Length: ' . filesize($archivo));
            ob_clean();
            flush();
            readfile($archivo);
            exit;
        }
    }
    static function makeObjects($nombre,$sw=0) {
        require_once(CONTROLLERS . "Controller" . $nombre . ".php");
        if($sw==0){
            require_once(MODELS . "Model" . $nombre . ".php");
        }
        $nombre = "Controller" . $nombre;
        $_control = new $nombre;
        return $_control;
    }
    public function cargarRecursos($clase){
       $recursos = $this->_modelo->selectStand("recursos","estado =1 and claserecurso = '$clase' order by orden asc");
       $headMain ="";
       if($recursos){
           foreach ($recursos as $value) {
              if($value['tipo']=="js"){
                  $headMain .= " <script type=\"text/javascript\" src=\"".RECURSOS."js".DS."$value[file].$value[tipo]\"></script>";
              }else if($value['tipo'] === "css"){
                   $headMain .= "<link rel=\"stylesheet\" href=\"".RECURSOS."css".DS."$value[file].$value[tipo]\" />";
              }
           }
       }
       return $headMain;
    }
    public function cargarMenu($lang){
       $recursos = $this->_modelo->selectStand("menu","idioma_abrevitura = '$lang' and estado =1 order by orden asc");
       $n=0;
       $num_rows = $recursos->rowCount();
       if($recursos){
           echo "<ul id =\"menu\">";
           foreach ($recursos as $value) {
               if($n == $num_rows){
                   echo "<li><a  href =\"$value[idioma_abrevitura]-$value[url]\">$value[nombreitem]</a></li>";
               }else{
                  echo "<li><a id='last' href =\"$value[idioma_abrevitura]-$value[url]\">$value[nombreitem]</a></li>";  
               }
               $n++;
           }
           echo "</ul>";
       }
    }
    public function carRedes(){
       $recursos = $this->_modelo->selectStand("redessociales","estado =1 order by orden asc");
       if($recursos){
           echo "<ul class =\"redes\">";
           foreach ($recursos as $value) {
               echo "<li><a id= \"$value[nombre]\" target=\"_blank\" href =\"$value[url]\" alt =\"$value[nombre]\"></a></li>";
           }
           echo "</ul>";
       }
    }
    public function cargarIdiomas(){
        $recursos = $this->_modelo->selectStand("idioma","estado =1");
       if($recursos){
           echo "<ul id =\"idiomas\">";
           foreach ($recursos as $value) {
               echo "<li><a href =\"$value[abrevitura]\">$value[nombre]</a></li>";
           }
           echo "</ul>";
       }
    }
    public function  cargarPrensa($lang){
       $recursos = $this->_modelo->selectStand("prensa","idioma = '$lang' and estado =1 order by fecha limit 2");
       if($recursos){
           echo "<ul id =\"presaMini\">";
           foreach ($recursos as $value) {
               echo "<li><a class ='foto' href =\"#\"><img src=\"public/prensa/$value[imgprensa]\" /></a><a href =\"#\">". $value['titulprensa']."</a></li>";
           }
           echo "</ul>";
       }
    }
    public function arrayPdo($pdo){
      $arrayEnd =  array();
        foreach ($pdo as $value) {
            $arrayEnd = $value;
        }
        return $arrayEnd;
    }

    public function loadslider($nombre,$idioma){
       $recursos = $this-> cargarRecursos("slider");
         echo $recursos;
        $queryConfi ="select configuracionslider.alto,
        configuracionslider.animacion,  configuracionslider.ancho,  configuracionslider.control, 
        configuracionslider.navegacion, configuracionslider.reproduccion 
        from configuracionslider inner join slider on configuracionslider.idconfiguracionslider = slider.idslider 
        where  slider.nombre ='$nombre'";
        $queryItems = " SELECT itemslider.img, itemslider.seo,slider.nombre from slider inner join itemslider on slider.idslider = itemslider.slider "
                . "where itemslider.idioma = '$idioma' and slider.nombre ='$nombre'";
        $configurarionSlider = $this->_modelo->selectPersonalizado($queryConfi);
        $itemSlider = $this->_modelo->selectPersonalizado($queryItems);
        if($itemSlider){
            echo "<span class ='border-blanco-info'>“El ciclismo es mi vida parce,
siempre pedaleo hacia la meta
con berraquera y ahora quiero
compartirles algo de lo que
me apasiona”</span>";
            echo "<div id=\"$nombre\">"
            . "<div class=\"slides-container\">";
            foreach ($itemSlider as $value) {
                echo "<img src='public".DS."item-sliders".DS."$value[img]' alt='$value[seo]' />";
                 
                }
                 
            echo "</div>"
            . " <nav class=\"slides-navigation\">
	      <a href=\"#\" class=\"next\"></a>
	      <a href=\"#\" class=\"prev\"></a>
	    </nav></div>"
                ;
        }
        $num_rows = $itemSlider->rowCount();
        $config = $this->arrayPdo($configurarionSlider);
       
        echo "<script>";
        if($num_rows <= 1){
             echo "
                $(function() {
                    $('#$nombre').superslides({
                      hashchange: $config[control],
                      play: $config[reproduccion],
                      animation: '$config[animacion]'
                    });
                    $('#$nombre').superslides('stop');                              
                });";
        }else{
           echo "$(function() {
                    $('#$nombre').superslides({
                      hashchange: $config[control],
                      play: $config[reproduccion],
                      animation: '$config[animacion]'
                    });
                    $('#$nombre').superslides('stop');                              
                    $('#$nombre').on('mouseenter', function() {
                      $(this).superslides('stop');
                      console.log('Stopped')
                    });
                    $('#$nombre').on('mouseleave', function() {
                      $(this).superslides('start');
                      console.log('Started')
                    });
                });
            ";
        }
        echo "</script>";
    }

    public function urls_amigables($url) {
        // Tranformamos todo a minusculas
        $url = strtolower($url);
        //Rememplazamos caracteres especiales latinos
        $find = array('á', 'é', 'í', 'ó', 'ú', 'ñ');
        $repl = array('a', 'e', 'i', 'o', 'u', 'n');
        $url = str_replace($find, $repl, $url);
        // Añadimos los guiones
        $find = array(' ', '&', '\r\n', '\n', '+');
        $url = str_replace($find, '-', $url);
        // Eliminamos y Reemplazamos otros carácteres especiales
        $find = array('/[^a-z0-9\-<>]/', '/[\-]+/', '/<[^>]*>/');
        $repl = array('', '-', '');
        $url = preg_replace($find, $repl, $url);
        return $url;
    }
    public function libreriasbasicas($objeFramework){
        foreach($objeFramework as $valor){
            require_once MODELS."Model".$valor.".php";
            require_once CONTROLLERS."Controller".$valor.".php";
        }
    }
    public  function translate($palabra,$lang){
        require 'lang/lenguaje.php';
	$translation = null;
	switch ($lang) {
            case 'es':
                $translation = $es[$palabra];
                break;
            case 'it':
                $translation = $it[$palabra];
                break;
            case 'en':
                $translation = $en[$palabra];
                break;
	}
	return $translation; 
    }
    public  function translateJava($vector){
        require 'lang/lenguaje.php';
        $translation = null;
        switch ($vector['idioma']) {
            case 'es':
                $translation = $es[$vector['text']];
                break;
            case 'it':
                $translation = $it[$vector['text']];
                break;
            case 'en':
                $translation = $en[$vector['text']];
                break;
        }
        echo $translation;
    }
}