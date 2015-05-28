<?php
class ControllerVista extends ControllerMain {
    private $_objMain;
    public function __construct() {
        $this->_objMain = new ControllerMain();
    }
    public function renderizarVista($vista, $lang = "es", $loguin =false) {
        //$datos = array('rutaRecursos' => BASE_URL . 'views' . DS . 'template' . DS . 'assets' . DS);
        if ($loguin) {
            require_once(BASIC.'header.phtml');
            if (file_exists(VISTAS.$vista.".phtml")) {
                require_once(VISTAS.$vista.'.phtml');
            } else {
                echo "<span class ='mensaje_delsistema'>Esta sección no existe</span>";
            }
            require_once(BASIC.'footer.phtml');
        } else {

            if (@$_SESSION['loguin'] != 'lordsxxx' and @ $_SESSION['XBesX'] == '1') {
                require_once($path_defaulf . 'header.phtml');
                if ($vista != "") {
                    require_once($path_view . $vista . '.phtml');
                } else {
                    echo "Esta seccion no existe";
                }
                require_once($path_defaulf . 'footer.phtml');
            } else if ($vista == 'viewMsg') {
                echo "no hay datos en variables";
            }
        }
    }

//    public function renderizarAdmin($vista, $loguin = false) {
//        ;
//        $path_view = ROOT . 'views' . DS . 'template' . DS . 'view_rendering' . DS;
//        $path_defaulf = ROOT . 'views' . DS . 'template' . DS . 'view_basic' . DS;
//        $datos = array('rutaRecursos' => ".." . DS . BASE_URL . 'views' . DS . 'template' . DS . 'assets' . DS);
//
//        if ($vista == '0') {
//            require_once($path_defaulf . 'home.phtml');
//        } else if ($vista == "viewMsg") {
//            require_once($path_view . $vista . '.phtml');
//        } else if ($loguin != false) {
//
//            require_once($path_defaulf . 'header.phtml');
//            if (file_exists(ROOT . 'views' . DS . 'template' . DS . 'view_rendering' . DS . $vista . ".phtml")) {
//                require_once($path_view . $vista . '.phtml');
//            } else {
//                echo "<span class ='mensaje_delsistema'>Esta sección no existe</span>";
//            }
//
//            require_once($path_defaulf . 'footer.phtml');
//        } else {
//
//            if (@$_SESSION['loguin'] != 'lordsxxx' and @ $_SESSION['XBesX'] == '1') {
//
//                require_once($path_defaulf . 'adminheader.phtml');
//                if ($vista != "") {
//                    require_once($path_view . $vista . '.phtml');
//                } else {
//                    echo "Esta seccion no existe";
//                }
//                require_once($path_defaulf . 'adminfooter.phtml');
//            } else if ($vista == 'viewMsg') {
//                echo "no hay datos en variables";
//            }
//        }
//    }
//
//    public function renderizarFancy($vista) {
//        $path_view = ROOT . 'views' . DS . 'template' . DS . 'view_rendering' . DS;
//        $path_defaulf = ROOT . 'views' . DS . 'template' . DS . 'view_basic' . DS;
//        $datos = array('rutaRecursos' => BASE_URL . 'views' . DS . 'template' . DS . 'assets' . DS);
//        require_once($path_defaulf . 'headerFancy.phtml');
//        if ($vista != "") {
//            require_once($path_view . $vista . '.phtml');
//        } else {
//            echo "Esta seccion no existe";
//        }
//        require_once($path_defaulf . 'footerFancy.phtml');
//    }
}
