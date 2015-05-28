<?php
Class ControllerNoticias extends ControllerMain{
    private $_modelNoticias;
    private $table;
    private $msg_error;
    private $msg_good;
    public function __construct(){
            $this->_modelNoticias = new ModelMain();
            $this->table="prensa";
            $this->msg_error ="Error intente la Operación nuevamente, si el problema pesiste comuniquece con el adminsitrador";
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
    public function selectNoticias($vector = false,$where ="estado = 1 order by fecha desc",$sw=1){
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
