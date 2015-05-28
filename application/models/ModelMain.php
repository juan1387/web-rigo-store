<?php
class ModelMain{
     public $connect;
        public function __construct(){
            $this->connect = new ClassDatabase();
        }
        public function updateStand($fields,$where,$table,$sw=1){
            if($sw==1){
                $fields = implode(" , ", $fields);
                $query = "UPDATE $table  SET $fields WHERE $where";
                $request = $this->connect->queryDataBase($query);
            /*    echo $query;*/
              
                if($request){
                    return true;
                }else{
                    return false;
                }
            }else if($sw==0){
                $query = "UPDATE $table  SET $fields WHERE $where";
                $request = $this->connect->queryDataBase($query);

             /*   echo $query;*/
                if($request){
                    return true;
                }else{
                    return false;
                }
            }
            
        }

        public function selectStand($tabla,$where = false,$campos = "*"){
            if($campos != "*"){
                $campos = implode(",", $campos);
            }
            if($where == false){
                $query = "SELECT $campos FROM $tabla";
            }else{
                 $query = "SELECT $campos FROM $tabla WHERE $where";
            } 
//        echo $query;
            $request =  $this->connect->con->query($query);
            if($request){
                return $request;
            }else{
                return false;
            }
        }
        public function  selectPersonalizado($query){
            $request =  $this->connect->con->query($query);
//            echo $query;
            if($request){
                return $request;
            }else{
                return false;
            }
        }

        public function insertStand($campostbl,$valores,$tabla){
            $campostbl = implode(",", $campostbl);
            $valores = implode(",", $valores);
            $query ="INSERT INTO $tabla($campostbl) VALUES($valores)";
            $request =  $this->connect->queryDataBase($query);
           /*  echo $query; */
            if($request){
                return true;
            }else{
                return false;
            }
        }
        public function selectUsers($where = false){
            if($where == false){
            $query = "SELECT * FROM usuarios";

            }else{
            $query = "SELECT * FROM usuarios WHERE ". $where;
            }
            /*echo $query;*/
            $request =  $this->connect->queryDataBase($query);
            if(@mysqli_num_rows($request)>0){
                return $request;
            }else{
            return false;
            }
        }
        public function deleteStand($tabla,$where){
            $query = "DELETE  FROM $tabla WHERE $where";
            $request =  $this->connect->queryDataBase($query);
            if($request){
                return true;
            }else{
                return false;
            }
        }

}