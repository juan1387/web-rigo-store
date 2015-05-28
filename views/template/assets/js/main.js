$(document).ready(function(){
    var talla;
    var color;
    var producto;
    var idioma;
    var cantidad;
    var sexo;
    color = $("#colores option:selected").val(); 
    talla = $("input[name='talla'] ").val();
    $("input[type ='radio']").click(function(){
        talla = $(this).val();
        chequearColor(talla,$("#idproducto").attr('val'),$("#idproducto").attr('lan'));
         selccionarGenero(talla,$("#idproducto").attr('val'),$("#idproducto").attr('lan'),color);
    })
    $("#colores").change(function(){
        color = $("#colores option:selected").val(); 
        selccionarGenero(talla,$("#idproducto").attr('val'),$("#idproducto").attr('lan'),color);
    })
    $("#btn-agregar-carro").click(function(){
        color = $("#colores option:selected").val(); 
        producto = $("#idproducto").attr('val');
        idioma = $("#idproducto").attr('lan');
        color = $("#colores option:selected").val(); 
        cantidad =$("input[name ='txtcantidad']").val();
        sexo = $("#sltgenero option:selected").val(); 
        if(talla == ""){
            traducir("No hay valores en tallas",idioma)
        }else{
            if(color == 0){
                traducir("No hay valores en color",idioma)
            }else{
                if(sexo == 0){
                    traducir("No hay valores en sexo",idioma)
                }else{
                    if(cantidad == ""){
                        traducir("No hay valores en cantidad",idioma)
                    }else{
                        
                        if(verificarExistencia(talla,producto,idioma,color,cantidad,sexo)){
                            agregarCarrito(talla,producto,idioma,color,cantidad,sexo);
                        }else{
                        } 
                    }
                }
            }
        }
        
    })
    $("#btn-vaciar").click(function(event){
         event.preventDefault();
         idioma = $("#info-web").attr('lan');
         vaciarCarrito(idioma);
    })
    $(".eliminar-item").click(function(event){
        event.preventDefault();
        var producto = $(this).attr("id");
        eliminarItemcart(producto);
    })
})


