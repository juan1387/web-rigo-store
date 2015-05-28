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
                    if($_SESSION['carrito'][$i]['sexo']==$sexo && $_SESSION['carrito'][$i]['sexo'] != 3){
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
    public function mostrarCarrito($lang){
        $objeProducto = ControllerMain::makeObjects("Productos",1);
        for($i = 0;$i< $_SESSION['contador'];$i++){
                $campos = $objeProducto->selectProductoSengle($_SESSION['carrito'][$i]['producto'],$lang);
            $tallacolorsexo =$this->getTallasSexo($_SESSION['carrito'][$i]['sexo'],$_SESSION['carrito'][$i]['talla'],$lang,$_SESSION['carrito'][$i]['color']);
            $cantidad = $_SESSION['carrito'][$i]['cantidad'];
            $producto = $_SESSION['carrito'][$i]['id'];
            echo "<div class=\"producto-cart-block\">"
                . "<div class =\"inline \" ><div class=\"foto-cart\"><img src = 'public".DS."productos".DS."$campos[imguno]' alt='$campos[seo]' /></div></div>"
                . "<div class =\"inline info-cart-pro\">"
                . "<div><span class =\"rosa-text\">$campos[nombrem]</span> <br /> $campos[nombre]</div>"
                . "<div><strong>".$this->translate("Referencia",$lang).": $campos[ref]</div>"
                .$tallacolorsexo
                . "<div><strong>".$this->translate("Cantidad",$lang).": <input type ='number' value ='$cantidad' name ='txtcantidadcart' id ='txtcantidadcart' /></div>"
                . "<a href='' class ='botones-carro eliminar-item' id=' $producto'>Eliminar</a>"
                . "</div>"
                ."<div class =\"inline\">"
                . "<span class =\"rosa-text\"> $ ".  number_format($campos['valoruno']*$_SESSION['carrito'][$i]['cantidad'])." COP </span>"
                . "</div>"
                . "</div>";
        }
    }
    public function eliminarItem($vector){
        
        $i = intval($vector['producto']);
        unset($_SESSION['carrito'][$i]);
        $_SESSION['carrito'] = array_values($_SESSION['carrito']);
        $_SESSION['contador'] = $_SESSION['contador']-1;
        if($_SESSION['contador']==0){
            $this->vaciarCarrito($vector);
        }
//        return true;
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


    public  function selectTalla($idtalla,$lang){
        $query ="";
    }
    public function selectGenero($vector){
        $colores_select ="";
        $query = "SELECT color.*, colorexidioma.nombreidioma,inventario.sexo FROM inventario 
        inner join color on inventario.idcolor = color.idcolor inner join colorexidioma on color.idcolor = colorexidioma.color 
        where inventario.idproducto =$vector[producto]  and inventario.idtalla = $vector[talla] and colorexidioma.idioma='$vector[idioma]' and inventario.idcolor = $vector[color]";
        $genero = $this->_modelo->selectPersonalizado($query);
        echo "<option value =\"0\">Seleccione Genero</option>";
        foreach ($genero as $itemgenero){
            if($itemgenero['sexo'] == 1){
                echo "<option value ='1'>". $this->translate("Hombre", $vector['idioma'])."</option>";
            }else{
                if($itemgenero['sexo']==2){
                     echo "<option value ='2'>". $this->translate("Mujer", $vector['idioma'])."</option>";
                }else{
                    if($itemgenero['sexo']==3){
                        echo "<option value ='2'>". $this->translate("Mujer", $vector['idioma'])."</option>";
                        echo "<option value ='1'>". $this->translate("Hombre", $vector['idioma'])."</option>";
                    }
                }
            }
            
        }
    }
    public function  consultarExistencia($vector){
        $query = "SELECT color.*,inventario.sexo,inventario.cantidad,inventario.idtalla,inventario.idcolor  
        FROM inventario 
        inner join color on inventario.idcolor = color.idcolor  
        where inventario.idproducto =$vector[producto] 
        and inventario.idtalla = $vector[talla]  
        and inventario.idcolor = $vector[color] 
        and inventario.sexo = $vector[sexo]
         and inventario.cantidad >= $vector[cantidad]";
        $existencia = $this->_modelo->selectPersonalizado($query);
        $vectorValor = $this->arrayPdo($existencia);
        $num_rows = $existencia->rowCount();
        if($num_rows==0){
            
            print_r($vectorValor);
            echo $this->translate("No hay suficiente existencia de este producto, actualmente hay ", $vector['idioma']).$vectorValor[cantidad];
        }else{
            echo 1;
        }
    }

    public function selectCategorias($idProducto,$lang){
        $query = "SELECT categoriaxidioma.nombre from categorias 
        inner join categoriaxidioma on categorias.idcategorias = categoriaxidioma.categoria
        inner join categoriaxproducto on categorias.idcategorias = categoriaxproducto.categoria
        where categoriaxidioma.idioma = '$lang' and categoriaxproducto.idproducto = $idProducto";
        $categorias = $this->_modelo->selectPersonalizado($query);
        return $categorias;
    }
    public function deleteNoticias($vector){
        $request = $this->_modelNoticias->deleteNoticias($this->table,"idnoticias = $vector[id]");
        if($request){
                $this->redirect("index.php?vista=adminNoticias",0,$this->msg_good);
        }else{
                $this->redirect("index.php?vista=adminNoticias",0,$this->msg_error);
        }
    }
    public function updateNoticia($vector){
        $descripcion = str_replace("'", "\'", $vector['textnoticia']);
        $descripcion = str_replace("\"", "\\\"", $descripcion);
        $fields =$this->getFieldsTable($this->table);
        unset($fields[0]);
        $fields = array_values($fields);
        $fields[0] .=" = '$vector[txtitulo]' ";
        $fields[1] .= " = '$vector[txtfoto]' ";
        $fields[2] .=" = \"$descripcion\" ";
        $fields[3] .=" = '$vector[txtFecha]' ";
        $fields[4] .=" = $vector[sltestado] ";
        $where ="idnoticias = $vector[txtIdnoticia]"; 
        $request = $this->_modelNoticias->updateNoticia($fields,$where,$this->table);
        if($request){
                $this->redirect("index.php?vista=adminNoticias",0,$this->msg_good);
        }else{
                $this->redirect("index.php?vista=adminNoticias",0,$this->msg_error);
        }
    }
    function savaNoticia($vector){
        $descripcion = str_replace("'", "\'", $vector['textnoticia']);
        $descripcion = str_replace("\"", "\\\"", $descripcion);
        $valores = array();
        $campos = array();
        $fiels =$this->getFieldsTable($this->table);
        $valores[]="null";
        $valores[]="'$vector[txtitulo]'";
        $valores[]="'$vector[txtfoto]'";
        $valores[]="\"$descripcion\"";
        $valores[]="$vector[txtFecha]";
        $valores[]="$vector[sltestado]";

        $request = $this->_modelNoticias->savaNoticia($fiels,$valores,$this->table);
        if($request){
                echo 1;
        }else{
                echo 2;
        }
    }
}
