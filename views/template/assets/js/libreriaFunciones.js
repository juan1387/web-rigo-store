var sw;
var fancy;
function chequearColor(talla,producto,idioma){
    $.post('accion-selectColor-Productos',{talla:talla,producto:producto,idioma:idioma}, function(data){
       $("#colores").html(data);
    });
}
function selccionarGenero(talla,producto,idioma,color){
    $.post('accion-selectGenero-Productos',{talla:talla,producto:producto,idioma:idioma,color:color}, function(data){
       $("#sltgenero").html(data);
    });
}
function asignar(val){
   if(val == "1"){
       return false;
   }else{
       return true;
   }
}
//function verificarExistencia(talla,producto,idioma,color,cantidad,sexo){
//    $.ajax({
//        url: 'accion-consultarExistencia-Productos',
//        async: false,   // this is the important line that makes the request sincronous
//        type: 'post',
//        data:{},
//        success: function(data) {
//            console.log(data);
//            if(data != 1){
//                $("#mensaje").html(data);
//                 setTimeout( "limpirMensaje('#mensaje')", 2000 );
//                sw = 1;
//            }else{
//                sw = 0;
//            }
//        },
//        error: function(data){
//            console.log(data);
//        }
//    });
//    if(sw==0){
//        return true
//    }else{
//        return false;
//    }
//
//}
function limpirMensaje(caja){
     $(caja).html("");
}
function traducir(text,idioma){
    $.post('accion-translateJava-Main',{text:text,idioma:idioma}, function(data){
       alert(data);
    });
}
function agregarCarrito(talla,producto,idioma,color,cantidad,sexo){
    $.post('accion-addCarrito-Carrito',{talla:talla,producto:producto,idioma:idioma,color:color,cantidad:cantidad,sexo:sexo}, function(data){
        if(data == 0){
            traducir("Este producto ya se encuentra en su lista de pedido verique el carrito de compras",idioma)
        }else{
            $("#cantidad-cart").html("("+data+")");
        }
       
    });
}
function vaciarCarrito(idioma){
    $.post('accion-vaciarCarrito-Carrito',{idioma:idioma}, function(data){
        console.log(data);
        location.reload(); 
    });
}
function eliminarItemcart(producto){
    $.post('accion-eliminarItem-Carrito',{producto:producto}, function(data){
       location.reload(); 
    });
}
function cambiarCantidad(cantidad,registro,idioma){
     $.post('accion-modificarCantidadCart-Carrito',{cantidad:cantidad,registro:registro,idioma:idioma}, function(data){
      location.reload();
    });
}
function cargarExistencias(talla,producto,idioma,color,sexo){
    $.post('accion-consultarExistencia-Productos',{talla:talla,producto:producto,idioma:idioma,color:color,sexo:sexo}, function(data){
        $("#txtcantidad").html(data);
    });
}
function validarSession(idioma,objeto){
    $.ajax({
        url: 'accion-pagarProductos-Carrito',
        async: false,   // this is the important line that makes the request sincronous
        type: 'post',
        data:{idioma:idioma},
        success: function(data) {
           if(data != 0){
               fancy = data;
            }else{
                fancy = 1;
            }
        },
        error: function(data){
            console.log(data);
        }
    });
    return fancy;
}