<?php
Class ControllerProductos extends ControllerMain{
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
    public function selectProducto($lang,$id=0,$formashow =0){  
        if($id==0){
            $query ="select productos.*, "
             . "detallexproducto.* from detallexproducto "
             . "inner join productos on detallexproducto.idproducto = productos.idproductos "
             . "where detallexproducto.idioma = '$lang'";
        }else{
            $query ="select productos.*, "
             . "detallexproducto.* from detallexproducto "
             . "inner join productos on detallexproducto.idproducto = productos.idproductos "
             . "where detallexproducto.idioma = '$lang' and productos.idproductos = $id";
        }
        $recursos = $this->_modelo->selectPersonalizado($query);
        if($formashow ==0){
            $n=0;
            $num_rows = $recursos->rowCount();
            if($recursos){
               echo "<div class ='productos'>";
               foreach ($recursos as $value) {
                   echo "<a class ='enlace-producto' href ='$lang-producto-$value[idproductos]'>";
                   $catergorias = $this->selectCategorias($value['idproductos'],$lang);
                   $valor = $value['valordos'] != "" ?"<span class= 'precio-old'>$ ".number_format($value['valordos'])." COP</span><br />": "";

                   echo "<div class = 'titulo-producto'>$value[nombrem]</div>";
                   echo "<div class = 'img-producto'>"
                   . "<img src = 'public".DS."productos".DS."$value[imguno]' alt='$value[seo]' />"
                           . "</div>";
                   echo "<div  class = 'categorias'>";
                   foreach ($catergorias as $item){
                       echo "<a href='#'>".$item['nombre']."</a>&nbsp;";
                   }
                   echo "<div class = 'nombre-producto'>$value[nombre]</div>";
                   echo "<div>"
                   . "$valor"
                           . "<span class ='precio-now'>$ ".number_format($value['valoruno'])." COP </span></div>";
                   echo "</div></div>";
                   echo "</a>";
               }
               echo "</div>";
            }
        }else{
            if($recursos){
             
               foreach ($recursos as $value) {
                    echo "<div class = 'titulo-producto sengle-title'>$value[nombrem] - $value[nombre]</div>";
                    echo "<div class =\"inline\" id =\"fotos-producto\">";
                        if($value['imguno']!=""){
                            echo "<div class = 'img-producto inline'><img src = 'public".DS."productos".DS."$value[imguno]' alt='$value[seo]' /></div>";
                        }
                        if($value['imgdos']!=""){
                            echo "<div class = 'img-producto inline'><img src = 'public".DS."productos".DS."$value[imgdos]' alt='$value[seo]' /></div>";
                        }
                        if($value['imgtres']!=""){
                            echo "<div class = 'img-producto inline mini'><img src = 'public".DS."productos".DS."$value[imgtres]' alt='$value[seo]' /></div>";
                        }
                        if($value['imgcuatro']!=""){
                             echo "<div class = 'img-producto mini'><img src = 'public".DS."productos".DS."$value[imgcuatro]' alt='$value[seo]' /></div>";
                        }
                    echo "</div>";
                    echo "<div class =\"inline\" id =\"informacion-producto\">";
                    echo "<div class ='items-info content-info'><strong>".$this->translate("Referencia",$lang);
                    
                   echo  "</strong>: $value[ref]</div>";
                   $catergorias = $this->selectCategorias($value['idproductos'],$lang);
                   echo "<div = class = 'categorias content-info'><strong>".$this->translate("Categorías",$lang).": </strong>";
                   foreach ($catergorias as $item){
                       echo "<a href='#'>".$item['nombre']."</a>&nbsp;";
                   }
                   $tallas = $this->selectTallas($value['idproductos'],$lang);
                   echo "</div>"
                   . "<div class ='tallas content-info'>".$this->translate("Tallas", $lang).""
                           . ": $tallas </div>";
                   echo "<div class=\" content-info\"><strong>".$this->translate("Genero",$lang).":</strong>"
                   . "<select id =\"sltgenero\"><option value =\"0\">".$this->translate("Seleccione",$lang). " ".$this->translate("Genero",$lang)."</option></select></div>";
                    echo "<div class=\" content-info\"><strong>".$this->translate("Cantidad",$lang).":</strong>"
                   ."<select id ='txtcantidad' name = 'txtcantidad'>"
                            . "<option value ='0'>".$this->translate("Cant",$lang)."</option>"
                            . "</select></div>"
                            . "<div class = \"valor-producto-carro\"> $ ".number_format($value['valoruno'])." C0P</div>"
                            . "<div class =\"botonera-carro\">"
                            . "<a id=\"btn-agregar-carro\" pro = \"$value[idproductos]\" class =\"botones-carro\" href= \"#\">".$this->translate("Añadir al carrito",$lang)."</a>"
                            ."<a id=\"btn-agregar-comprar\" pro = \"$value[idproductos]\"class =\"botones-carro\" href= \"#\">".$this->translate("Comprar",$lang)."</a>"
                            . "</div><br />"
                            . "<span id =\"mensaje\"></span>"; 
                    echo "</div>";
               }
            }
        }
 
    }
    public function selectCantidad($cantidad,$lang, $sw=1,$cantOr=false){
        if($sw==1){
            $select = "<option value ='0'>".$this->translate("Cant",$lang)."</option>";
            for ($index = 1; $index <= $cantidad; $index++) {
                $select .= "<option value ='$index'>$index</option>";
            }
        }else if($sw==2){
            $select = "";
            if($cantOr != false){
                for ($index = 1; $index <= $cantOr; $index++) {
                    if($cantidad==$index){
                         $select .= "<option selected='' value ='$index'>$index</option>";
                    }else{
                         $select .= "<option value ='$index'>$index</option>";
                    }
                }
            }else{
                  for ($index = 1; $index <= $cantidad; $index++) {
                    if($cantidad==$index){
                         $select .= "<option selected='' value ='$index'>$index</option>";
                    }else{
                         $select .= "<option value ='$index'>$index</option>";
                    }
                }
            }
        }
        return $select;
    }
    public function productosBruto($query){
        $tallas = $this->_modelo->selectPersonalizado($query);
        return $tallas->fetch(PDO::FETCH_ASSOC);
    }
    public function selectTallas($idproducto,$lang = "es" ){
        $checks ="";
        $query = "SELECT tallas.* FROM "
                . "inventario inner join tallas on inventario.idtalla = tallas.idtallas "
                . "where inventario.idproducto = $idproducto and inventario.cantidad > 0 group by tallas.nombre order by tallas.orden asc";
        $tallas = $this->_modelo->selectPersonalizado($query);
        $n=0;
        foreach ($tallas as $itemtallas){
            if($n==0){
                $checks .= "<span class ='label-content'>"
                    . "<span class ='label'>$itemtallas[nombre]</span>"
                    . "<input type ='radio' class ='tallas-check' checked name ='talla' value ='$itemtallas[idtallas]' />"
                    . "</span>"; 
                $n = $itemtallas['idtallas'];   
            }else{
                 $checks .= "<span class ='label-content'>"
                    . "<span class ='label'>$itemtallas[nombre]</span>"
                    . "<input type ='radio' class ='tallas-check'  name ='talla' value ='$itemtallas[idtallas]' />"
                    . "</span>"; 
            }
           
        }
        $dataquery = array("producto"=>$idproducto,"talla"=>$n,"idioma"=>$lang);
        $colores = $this->selectColor($dataquery,$sw=1);
        return $checks."<div class ='tallas content-info'>".$this->translate("Color", $lang).":<select id='colores' name = 'colores'><option value =\"0\">".$this->translate("Seleccione",$lang)." ".$this->translate("Color",$lang)."</option>$colores</select>"
                           . "</div>";;
    }
    public function selectProductoSengle($idProducto,$lang){
        $query = "select productos.*, "
             . "detallexproducto.* from detallexproducto "
             . "inner join productos on detallexproducto.idproducto = productos.idproductos "
             . "where detallexproducto.idioma = '$lang' and productos.idproductos = $idProducto";
        $productos = $this->_modelo->selectPersonalizado($query);
        return  $productos->fetch(PDO::FETCH_ASSOC);
    }
    public  function selectColor($vector,$sw=0){
        $colores_select ="";
              $query = "SELECT color.*, colorexidioma.nombreidioma FROM inventario 
                inner join color on inventario.idcolor = color.idcolor inner join colorexidioma on color.idcolor = colorexidioma.color 
                where inventario.idproducto =$vector[producto]  and inventario.idtalla = $vector[talla] and colorexidioma.idioma='$vector[idioma]'";
        $colores = $this->_modelo->selectPersonalizado($query);
        if($sw==0){
            echo "<option value =\"0\">Seleccione color</option>";
            foreach ($colores as $itemcolores){
                echo "<option value ='$itemcolores[idcolor]'>$itemcolores[nombreidioma]</option>";
            }
        }else{
            
            foreach ($colores as $itemcolores){
                $colores_select .= "<option value ='$itemcolores[idcolor]'>$itemcolores[nombreidioma]</option>";
            }
            return $colores_select;
        }
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
                        echo "<option value ='3'>". $this->translate("Ambos", $vector['idioma'])."</option>";
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
        and inventario.sexo = $vector[sexo]";
        $existencia = $this->_modelo->selectPersonalizado($query);
        $vectorValor = $existencia->fetch(PDO::FETCH_ASSOC);
        $num_rows = $existencia->rowCount();
        if($num_rows>=0){
           echo  $this->selectCantidad($vectorValor['cantidad'],$vector['idioma']);
        }else{
            echo "Error";
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
}
