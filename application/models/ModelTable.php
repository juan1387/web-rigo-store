<?php
class ModelTable{
    		public function __construct(){
			parent::__construct();
		}
	    public function queryTable($fiels,$table, $where = false){

            $values = implode(",", $fiels);
            if($where == false)
              $query = "SELECT  $values 
                        FROM  $table ";
            else{
                $query = "SELECT  $values
                        FROM  $table WHERE $where";
            }
          
            $request = $this->connect->queryDataBase($query);

            if($request){
                return $request;
            }else{
                return false;
            }
    	}
        public function howToSee($id,$state= 1){
            $query ="select count(*) from
             envios inner join registrocorreo on envios.idEnvio = registrocorreo.idEnvio 
             where envios.id_campaing = $id and registrocorreo.state_see = $state";
            $request = $this->connect->queryDataBase($query);
            if($request){
                return $request;
            }else{
                return false;
            }
        }
        public function nroSents($id,$state= 1){
            $query ="select count(*) from 
            registrocorreo inner join envios on registrocorreo.idEnvio = envios.idEnvio 
            where envios.id_campaing = $id and registrocorreo.estado = $state";
            $request = $this->connect->queryDataBase($query);
            if($request){
                return $request;
            }else{
                return false;
            }
        }
        public function nroSee($id){
            $query ="select sum(envios.nro_vistos) from envios where envios.id_campaing = $id";
            $request = $this->connect->queryDataBase($query);
            if($request){
                return $request;
            }else{
                return false;
            }
        }
}
