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
    $("#sltgenero").change(function(){
        color = $("#colores option:selected").val(); 
        producto = $("#idproducto").attr('val');
        idioma = $("#idproducto").attr('lan');
        color = $("#colores option:selected").val(); 
        sexo = $("#sltgenero option:selected").val(); 
        cargarExistencias(talla,producto,idioma,color,sexo);
    })
    $("#btn-agregar-carro").click(function(){
        color = $("#colores option:selected").val(); 
        producto = $("#idproducto").attr('val');
        idioma = $("#idproducto").attr('lan');
        color = $("#colores option:selected").val(); 
        cantidad =$("#txtcantidad option:selected").val();
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
                    if(cantidad == 0){
                        traducir("No hay valores en cantidad",idioma)
                    }else{
                        agregarCarrito(talla,producto,idioma,color,cantidad,sexo);
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
    $(".txtcantidad").change(function () {
        //Obtengo el registro que quiero modificarle la cantidad
        //Obtengo la cantidad de origen
        var idioma = $("#info-web").attr('lan');
        var id = $(this).attr('id');
        var idres = $(this).attr('pro');
        var cantidad = $("#"+id+" option:selected").val();
        color = $("#colores option:selected").val(); 
        producto = $("#idproducto").attr('val');
        color = $("#colores option:selected").val(); 
        cambiarCantidad(cantidad,idres,idioma);
        sexo = $("#sltgenero option:selected").val(); 
    });
    $("#btn-pagar").click(function(event){
        event.preventDefault();
        var idioma = $("#info-web").attr('lan')
        var fancy = validarSession(idioma,$(this));
        alert(fancy);
        if(fancy == 1){
            location.href=idioma+"-tramitarpedido";
        }else{
            $(this).attr("href","loguin-"+idioma+"-loguin");
            $("#btn-pagar").fancybox({
                maxWidth    : 334,
                 maxHeight   : 261,

                 'autoScale' : true,
                 'transitionIn' : 'none',
                 'transitionOut' : 'none',
                 'type' : 'iframe'
               });
            $(this).fancybox({
            maxWidth    : 334,
            maxHeight   : 261,
            'autoScale' : true,
            'transitionIn' : 'none',
            'transitionOut' : 'none',
            'type' : 'iframe'
            });
        }
    })
})


