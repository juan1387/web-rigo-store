<?php
Class ControllerCarrito extends ControllerMain{
    private $_modelNoticias;
    private $table;
    private $msg_error;
    private $msg_good;
    public function __construct(){
        $this->_modelo = new ModelMain();
        $this->table="producto";
        $this->msg_error ="Error. Intente nuevamente, si el problema pesiste contáctenos";
        $this->msg_good ="La Operación se realiazo con exito";
    }
    public  function getFieldsTable($table){
            $vectorFields = array();
            $request_gross = $this->_modelNoticias->fieldDbase($table);
            if($request_gross){
                while ($row = mysqli_fetch_assoc($request_gross)) {
                    $vectorFields[]= $row['Field'];
                }
            }
            return $vectorFields;
    }
    public function addCarrito($vector){ 
        //Valido existencia en el carrito y si no esta agrego 
        if($this->verificarexistencia($vector['producto'],$vector['talla'],$vector['sexo'],$vector['color'])){
            //Construyo el producto
            $arrayProducto = array("id"=>$_SESSION['contador'],
                "producto"=>$vector['producto'],
                "talla"=> $vector['talla'],
                "cantidad"=>$vector['cantidad'],
                "sexo"=>$vector['sexo'],
                "color"=> $vector['color']);
            //El array lo asigno a la variable de session
            $_SESSION['carrito'][]=$arrayProducto;
            //Aumento el valor del contador
            $_SESSION['contador']++;
            //Devuelvo este valor para llevar el control de la cantidad de productos
            echo $_SESSION['contador'];
       }else{
           echo 0; 
        }
    }
    public  function vaciarCarrito($vector){
        session_unset($_SESSION['carrito']);
        session_unset($_SESSION['contador']);
    }
    public function verificarexistencia($producto,$talla,$sexo,$color){
        for($i = 0;$i< $_SESSION['contador'];$i++){
            if($_SESSION['carrito'][$i]['producto']==$producto){
                if($_SESSION['carrito'][$i]['talla']==$talla){
                    if($_SESSION['carrito'][$i]['sexo']== $sexo){
                        if($_SESSION['carrito'][$i]['color']==$color){
                            return false;
                            break;
                        }
                    }
                }
            }
        }
        return true;
    }
    public function modificarCantidadCart($vector){
        $i = intval($vector['registro']);
        $talla = $_SESSION['carrito'][$i]['talla'];
        $color = $_SESSION['carrito'][$i]['color'];
        $sexo = $_SESSION['carrito'][$i]['sexo'];
        $objeProducto = ControllerMain::makeObjects("Productos",1);
        $query ="SELECT * FROM tiendarigobertouran.inventario where idtalla = $talla and idcolor = $color and sexo = $sexo";
        $camposInventario = $objeProducto->productosBruto($query);
        if($vector['cantidad']>$camposInventario['cantidad']){
            echo $this->translate("No hay suficiente existencia de este producto, actualmente hay ",$vector['idioma']).$camposInventario['cantidad'];
        }else{
            $_SESSION['carrito'][$i]['cantidad'] = $vector['cantidad'];
            //echo $_SESSION['carrito'][$i]['cantidad']; 
            $objeProducto = ControllerMain::makeObjects("Productos",1);
            echo $objeProducto->selectCantidad($camposInventario['cantidad'],'es',2,$vector['cantidad']);
            
        }   
    }
    public function mostrarCarrito($lang){
        $objeProducto = ControllerMain::makeObjects("Productos",1);
        for($i = 0;$i< $_SESSION['contador'];$i++){
            $campos = $objeProducto->selectProductoSengle($_SESSION['carrito'][$i]['producto'],$lang);
            $tallacolorsexo =$this->getTallasSexo($_SESSION['carrito'][$i]['sexo'],$_SESSION['carrito'][$i]['talla'],$lang,$_SESSION['carrito'][$i]['color']);
            $cantidad = $_SESSION['carrito'][$i]['cantidad'];
            $producto = $_SESSION['carrito'][$i]['id'];
            $arryQuery = array(
               "producto"=>$_SESSION['carrito'][$i]['producto'],
               "color"=>$_SESSION['carrito'][$i]['color'],
               "talla"=>$_SESSION['carrito'][$i]['talla'],
               "sexo"=>$sexo = $_SESSION['carrito'][$i]['sexo']);
            $queryCantidadProducto = "SELECT cantidad FROM inventario where idproducto = $arryQuery[producto] and idtalla = $arryQuery[talla] and idcolor = $arryQuery[color] and sexo = $arryQuery[sexo];";
            $cantidadProducto = $objeProducto->productosBruto($queryCantidadProducto);
            echo "<div class=\"producto-cart-block\">"
            . "<div class =\"inline \" ><div class=\"foto-cart\"><img src = 'public".DS."productos".DS."$campos[imguno]' alt='$campos[seo]' /></div></div>"
            . "<div class =\"inline info-cart-pro\">"
            . "<div><span class =\"rosa-text\">$campos[nombrem]</span> <br /> $campos[nombre]</div>"
            . "<div><strong>".$this->translate("Referencia",$lang).": $campos[ref]</div>"
            .$tallacolorsexo
            . "<div><strong>".$this->translate("Cantidad",$lang).":<select id ='cant$producto'  pro ='$producto' name = 'txtcantidadcart' class ='txtcantidad'>"
                            .$objeProducto->selectCantidad($cantidad,$lang,2,$cantidadProducto['cantidad'])
                            . "</select></div>"
            . "<div id ='msg$producto' class ='existencia-cart'></div>"
            . "<a href='' class ='botones-carro eliminar-item' id=' $producto'>Eliminar</a>"
            . "</div>"
            ."<div class =\"inline\">"
            . "<span class =\"rosa-text\"> $ ".  number_format($campos['valoruno']*$_SESSION['carrito'][$i]['cantidad'])." COP </span>"
            . "</div>"
            . "</div>";
        }   
    }
    public function totalCart($lang){
        $valor ="";
        for($i = 0;$i< $_SESSION['contador'];$i++){
           //Consulto el producto para conocer su valor
           $objeProducto = ControllerMain::makeObjects("Productos",1);
           $campos = $objeProducto->selectProductoSengle($_SESSION['carrito'][$i]['producto'],$lang);
           $valor = $valor + $campos['valoruno']*$_SESSION['carrito'][$i]['cantidad'];
        }
        return $valor;
    }
    public function eliminarItem($vector){
        print_r($vector);
        $i = intval($vector['producto']);
        
        for($x = $i+1;$x <= $_SESSION['contador'];$x++){
            if($_SESSION['carrito'][$x]['id']-1<0){
                $ind = 0;
            }else{
                $ind = $_SESSION['carrito'][$x]['id']-1;
            }  
            $_SESSION['carrito'][$x]['id'] = $ind;
            echo   $_SESSION['carrito'][$x]['id'];
        }
        unset($_SESSION['carrito'][$i]);
        $_SESSION['carrito'] = array_values($_SESSION['carrito']);
        $_SESSION['contador'] = $_SESSION['contador']-1;
        if($_SESSION['contador']==0){
            $this->vaciarCarrito($vector);
        }
    }
    public function getTallasSexo($sexo,$talla,$lang,$color){
        //QUERY DE TALLAS COLOR Y SEXO
        $querytallas = "SELECT * FROM tiendarigobertouran.tallas where idtallas = $talla";
        //COLORES
        $querycolor ="select colorexidioma.nombreidioma from colorexidioma where colorexidioma.color = $color and colorexidioma.idioma = '$lang'";
        //SEXO
        $querySexo ="SELECT * FROM tiendarigobertouran.sexo where idSexo = $sexo";
        //CONSULTA TALLAS
        $querytallas = $this->_modelo->selectPersonalizado($querytallas);
        //CONVERSION EN VECTOR TALLAS
        $querytallas = $querytallas->fetch(PDO::FETCH_ASSOC);
        //CONSULTA COLOR
        $querycolor = $this->_modelo->selectPersonalizado($querycolor);
        //CONVERSION EN VECTOR COLOR
        $querycolor = $querycolor->fetch(PDO::FETCH_ASSOC);
        //CONSULTA SEXO
        $querySexo = $this->_modelo->selectPersonalizado($querySexo);
         //CONVERSION EN VECTOR SEXO
        $querySexo = $querySexo->fetch(PDO::FETCH_ASSOC);
        //CREACION HTML DE TALLAS
        $valtalla = ($querytallas['nombre']=="Única")?$this->translate($queryTallas[nombre], $lang):$querytallas['nombre'];
        $htmltallas ="<div><strong>".$this->translate("Talla", $lang).": </strong><span class='cuadro-talla'>$valtalla</span></div>";
         //CREACION HTML DE GENERO
        $valsexo = "";
        if($querySexo['sexo'] == 1 || $querySexo['sexo'] == 2){
            $valsexo = $this->translate($querySexo['sexo'], $lang);
        }else{
            $valsexo = $this->translate("Ambos", $lang);
        }
        $htmlsexo ="<div><strong>".$this->translate("Genero", $lang).": </strong>$valsexo</div>";
        //CREACION HTML COLOR
        $htmlcolor = "<div><strong>".$this->translate("Color", $lang).": </strong>$querycolor[nombreidioma]</div>";
        return "<div class ='info-basica-cart'>$htmlsexo  $htmltallas  $htmlcolor </div>";
    }
    private function validarLoguin(){
        
       
    }

    public function  pagarProductos($vector){
        $formIniciarPago ="$vector[idioma]-formPago";
        
        if(isset($_SESSION['sessionusuario'])){
           echo  0;
        }else{
           echo $formIniciarPago;
           
        }
    }
}
