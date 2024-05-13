
/* inicio variables generales*/
    var canasta=[];

/* fin variables generales*/
$('#avatar').html('<img src="./img/'+localStorage.usuario_avatar+'" alt="user" class=""> <span class="hidden-md-down">'+localStorage.usuario_nombre+' &nbsp;<i class="fa fa-angle-down"></i></span>');
var tomociudad = //creo variable para cargarla luego en la creacion del clkiente y plasmarla en el comercio.

// salida
$("#salir").click(function(){
localStorage.clear();
window.location.href = "login.html";
});

  function notifica(h,t,i,c,ha,p){
    // composicion h=Encabezado - t=Texto - i=Icono - c=Colorbarra(#) - ha= tiempooculta - p=posicion
    // variables icon (i): error || success || WARNING  || info
    if(c=='' || c==null){c='#ff6849';}
    if(ha=='' || ha==null){ha=3500;}
    if(p=='' || p==null){p='top-right';}
    $.toast({
            heading: h,
            text: t,
            position: p,
            loaderBg: c,
            icon: i,
            hideAfter: ha

          });
  }

  

function llena_canasta_compra_stock(){
  var producto = $('#producto_card').val();
  var detalle = $('#producto_card option:selected').text();
  var cantidad = $('#cantproducto_card').val();
  if(producto== null || producto=='' || cantidad==''  ){
    alert('Seleccione un producto y complete la cantidad');
  }else{

    canasta.items.push({
        'id': producto,
        'detalle': detalle,
        'cant': cantidad,
    });

    dibuja_canasta();
    $('#producto_card').val('');
    $('#cantproducto_card').val('1');
  }
}

function edita_canasta_compra_stock() {
  var idProducto = $("#idCanasta").text();
  var detalle = $("#producto").text();
  var cantidad = $("#ncantproducto_card").val();

  var indiceProducto = canasta.items.findIndex(function(elemento) {
    return elemento.id === idProducto;
  });

  if (indiceProducto !== -1) { // Verifica si se encontró el producto
    // Modifica el producto en el arreglo canasta.items
    canasta.items[indiceProducto] = {
      'id': idProducto,
      'detalle': detalle,
      'cant': cantidad
    };

    // Vuelve a dibujar la canasta
    dibuja_canasta();
    $('#myModal').modal('hide');
  } else {
    // Si no se encontró el producto, muestra un mensaje de error
    alert('El producto no se encontró en la canasta.');
  }

}



function llenaprod(){
  var prov=$('#provee_card').val();
  var fecha=$('#fecha_card').val();
  var prov_nom=$('#provee_card option:selected').text();
  canasta.proveedor_id=prov;
  canasta.proveedor_nombre=prov_nom;
  canasta.items=[];
  canasta.fecha = fecha;
  var string='accion=product_list&prov='+prov;

  $.ajax({
      type: "POST",
      url: "procesos/productos.php?",
      data: string,
      success: function(data){
        console.log(data);
        if(data!='FALSE'){
          console.log(data);
          $("#producto_card").prop("disabled", false).val("");
          $('#producto_card').html(data);

        }
        else {alert('Error al obtener produtos')}
      }
  });


}



$('#ingresar_compra').click(function(){
  if($('#provee_card').val()=='' || $('#provee_card').val()==null){
    alert('Seleccione un proveedor');
  }
  else if(canasta.items.length < 1){
    alert('No tiene cargados productos');
  }else{
    var prov_nombre = canasta.proveedor_nombre;
    var proveedor_id = canasta.proveedor_id;
    var fecha = canasta.fecha;
    var tipo = $('#tipocompro_card').val();
    var numero_comprobante = $('#comprobante_num_card').val();
    var en_stock = $('#stock_card').val();
    var vencimiento = $('#vencimiento_card').val();
    var items = JSON.stringify(canasta.items);
    var string = "accion=add_compras&prov_nombre="+prov_nombre+"&proveedor_id="+proveedor_id+"&fecha="+fecha+"&tipo="+tipo+"&numero_comprobante="+numero_comprobante+"&en_stock="+en_stock+"&vencimiento="+vencimiento+"&items="+items;
    /*alert(items);*/
    $.ajax({
                  type: "POST",
                  url: "procesos/crud.php?",
                  data: string,
                  success: function(data){
                    if (data !== 'FALSE') {
                      console.log(data);
                        alert("compra Agregada con éxito");
                        window.location.href = "index.php";
                    } else {
                        alert('Error al agregar compra');
                    }
                  }
              });


  }
 });

function del_item_canasta(id){

canasta.items.splice(id, 1);
dibuja_canasta();
}

function editar_item_canasta(id){
  var cantidadActual = canasta.items[id].cant;
  $('#ncantproducto_card').val(cantidadActual);
  $('#producto').html(canasta.items[id].detalle);
  $('#idCanasta').html(canasta.items[id].id);
}

function envia_a_stock(){

  if($('#provee_card').val()=='' || $('#provee_card').val()==null){
    alert('Seleccione un proveedor');
  }
  else if(canasta.items.length < 1){
    alert('No tiene cargados productos');
  }else{

var canasta_json = {
      proveedor_nombre : canasta.proveedor_nombre,
      proveedor_id : canasta.proveedor_id,
      fecha : canasta.fecha,
      a : "add_comprobante",
      vencimiento : $('#vencimiento_card').val(),
      en_stock : $('#stock_card').val(),
      tipo_comprobante : $('#tipocompro_card').val(),
      numero_comprobante : $('#comprobante_num_card').val(),
      items : JSON.stringify(canasta.items)
    };
    console.log(canasta_json);

    $.ajax({
        type: "POST",
        url: "procesos/compras.php?",
        data: canasta_json,
        success: function(data){
          if(data!='FALSE'){
            //console.log('entra al true');

          window.location.href = "index.php";
          }
          else {alert('Error al insertar cliente')}
        }
    });


  }
}

function dibuja_canasta(){
  var can = canasta.items.length;
  var esqueleto='<table style="width:100%"><th>Producto</th><th>Cantidad</th><th>Acciones</th>';
  for(var i=0; i < can ;i++){
    esqueleto += '<tr><td>'+canasta.items[i].detalle+'</td><td>'+canasta.items[i].cant+'</td><td><i class="ti-close" onclick="del_item_canasta('+i+')" aria-hidden="true"></i> <i class="fa fa-pencil" onclick="editar_item_canasta('+i+')" aria-hidden="true" data-toggle="modal" data-target="#myModal"></i></td></tr>';
  }
  esqueleto += '</table>';
  $('#list_prod_card').html(esqueleto);
}


  $('#agregar_cliente').click(function(){
  //  if (controlCorrecto()) {
    

    var razon = $('.razon').val();
    var tipodni = $('.tipodni option:selected').val();
    var dni = $('.cuit').val();

    var condicioniva = $('.condicioniva option:selected').val();
    var rubro = $('.rubro option:selected').val();

    var telfijo = $('.telfijo').val();
    var celular = $('.celular').val();
    var email = $('.email').val();

    var provincia = $('.provincia option:selected').val();
    var ciudad = $('.ciudad option:selected').val();

    var direccion = $('.direccion').val();
    var numero = $('.numero').val();

    var piso = $('.piso').val();
    var depto = $('.depto').val();

    var notas = $('.notas').val();

    var cumple = $('#cumple').val();
    var asignado = $('.asignado option:selected').val();
    var listap = $('#listap').val();

    var financia = $('#financia').val();
    var limite_financia = $('#limitefinancia').val();

    var dias_financia =$('#financia_dias').val();

    var apellido = $('.apellido').val();
    var nombre = $('.nombre').val();
    var sexoh = $('.sexoh').val();
    var sexom = $('.sexom').val();
    var sexo ='M';
    var ecivil = $('.ecivil option:selected').val();    
    var email2 = $('.email2').val();
    var telfijo_com = $('.telfijo_com').val();
    var celular_com = $('.celular_com').val();
    var direccion_com = $('.direccion_com').val();
    var numero_com = $('.numero_com').val();
    var piso_com = $('.piso_com').val();
    var depto_com = $('.depto_com').val();
    var upload = $('.upload').val(); 
    var dias_financia =$('#financia_dias').val()
    var string2 = "accion=add_clientes&apellido="+apellido+"&nombre="+nombre+"&tipodni="+tipodni+"&dni="+dni+"&sexo="+sexo+"&ecivil="+ecivil+
      "&cumple="+cumple+"&email="+email+"&email2="+email2+"&telfijo="+telfijo+"&celular="+celular+"&provincia="+provincia+"&ciudad="+ciudad+
      "&direccion="+direccion+"&numero="+numero+"&piso="+piso+"&depto="+depto+"&upload="+upload+"&razon="+razon+"&condicioniva="+condicioniva+
      "&rubro="+rubro+"&telfijo_com="+telfijo_com+"&celular_com="+celular_com+"&direccion_com="+direccion_com+"&numero_com="+numero_com+"&piso_com="+piso_com+
      "&depto_com="+depto_com+"&notas="+notas+"&asignado="+asignado+"&financia="+financia+"&limite="+limite_financia+"&listap="+listap+"&dias_financia="+dias_financia;
    tomociudad = ciudad;
    // console.log('datos string2: '+string2);
            $.ajax({
                type: "POST",
                url: "procesos/crud.php?",
                data: string2,
                success: function(data){
                  console.log(data);
                  alert("cliente creado con exito");
                  window.location.href = "index.php?pagina=clientes";
                  if(data!='FALSE'){
                    //console.log('entra al true');
                    window.location.href = "index.php?pagina=clientes&msg="+data;
                  //window.location.href = "index.php?pagina=comercio_add&id="+data;
                  }
                  else {alert('Error al insertar cliente')}
                }
            });
  //}
  });

/*inicio agrega adelanto*/
$("#agregarAdelanto").click(function(){
  var mov = $('#movimiento').val();
  var nroComp = $('#comprobante').val();
  var monto = $('#monto').val();
  var observaciones = $('#detalle-alqui').val();
  var personal = $('#asignado option:selected').val();
  /*guardo datos del formulario y los paso al crud.php*/
  var string2 = "accion=add_ade&mov="+mov+"&nroComp="+nroComp+"&monto="+monto+"&observaciones="+observaciones+"&personal="+personal;
  $.ajax({
      type: "POST",
      url: "procesos/crud.php?",
      data: string2,
      success: function(data){
        console.log(data);
        if(data!='FALSE'){
          alert('adelanto creado');
          window.location.href = "index.php?pagina=adelanto";
        }
        else {alert('Error al insertar adelanto')}
      }
  });
  console.log(string2);
});

/*fin agregar adelanto*/

//Funcion para controlar campos obligarios de nuevo cliente
function controlCorrecto() {
  let correcto = false;

  if ($('.apellido').val()!="") {
    if ($('.nombre').val()!="") {
      if ($('.provincia option:selected').val()!="") {
          if ($('.ciudad option:selected').val()!="") {
            if ($('.direccion_com').val()!="") {
              if ($('.numero_com').val()!="") {
                if ($('.razon').val()!="") {
                  correcto=true;
                }else {
                  alert("Complete el campo razon social");
                }
              }else {
                alert("Complete el campo numero de la direccion");
              }
            }else {
              alert("Complete el campo direccion");
            }
          }else {
            alert("Complete el campo ciudad");
          }
        }else {
          alert("Complete el campo provincia");
        }

    }else {
      alert("Complete el campo nombre");
    }
  }else {
    alert("Complete el campo apellido");
  }
  return correcto;
}

// funcion carga proveedores

$('#agregar_proveedor').click(function(){
  var apellido = $('.apellido').val();
  var nombre = $('.nombre').val();
  var tipodni = $('.tipodni option:selected').val();
  var dni = $('.dni').val();
  var cumple = $('#cumple').val();
  var email = $('.email').val();
  var email2 = $('.email2').val();
  var telfijo = $('.telfijo').val();
  var celular = $('.celular').val();
  var telfijo_com = $('.telfijo_com').val();
  var celular_com = $('.celular_com').val();
  var provincia = $('.provincia option:selected').val();
  var rubro = $('.rubro option:selected').val();
  var ciudad = $('.ciudad option:selected').val();
  var direccion = $('.direccion').val();
  var numero = $('.numero').val();
  var piso = $('.piso').val();
  var depto = $('.depto').val();
  var razon = $('.razon').val();
  var cuit = $('.cuit').val();
  var condicioniva = $('.condicioniva option:selected').val();
  var direccion_com = $('.direccion_com').val();
  var numero_com = $('.numero_com').val();
  var piso_com = $('.piso_com').val();
  var depto_com = $('.depto_com').val();
  var notas = $('.notas').val();
  var celular2 = $('.celular2').val();
  var banco = $('.banco').val();
  var nroCuenta = $('.nroCuenta').val();
  var cbu = $('.cbu').val();
  var nombre_fantasia = $('.nombref').val();
  var alias = $('.alias').val();
  var upload = $('.upload').val();
  var string2 = "accion=add_proveedores&nombre="+nombre+"&tipodni="+tipodni+"&dni="+dni+"&cumple="+cumple+"&email="+email+"&email2="+email2+"&telfijo="+telfijo+"&celular="+celular+"&provincia="+provincia+"&ciudad="+ciudad+"&direccion="+direccion+"&numero="+numero+"&piso="+piso+"&depto="+depto+"&upload="+upload+"&razon="+razon+"&cuit="+cuit+"&condicioniva="+condicioniva+"&rubro="+rubro+"&telfijo_com="+telfijo_com+"&celular_com="+celular_com+"&direccion_com="+direccion_com+"&numero_com="+numero_com+"&piso_com="+piso_com+"&depto_com="+depto_com+"&notas="+notas+"&celular2="+celular2+"&banco="+banco+"&nroCuenta="+nroCuenta+"&cbu="+cbu+"&nombref="+nombre_fantasia+"&alias="+alias;
  tomociudad = ciudad;
  // console.log('datos string2: '+string2);
  if(provincia.trim() !== '' && ciudad.trim() !== '' && direccion.trim() !== '' && numero.trim() !== ''
    && razon.trim() !== '' && rubro.trim() !== '' && condicioniva.trim() !== '' && cuit.trim() !== '' && email.trim() !== '' && email2.trim() !== '' && celular.trim() !== '' && celular2.trim() !== '' && telfijo.trim() !== '' && notas.trim() !== ''
    && banco.trim() !== '' && nroCuenta.trim() !== '' && cbu.trim() !== '' && nombre_fantasia.trim() !== '' && alias.trim() !== ''){
          $.ajax({
              type: "POST",
              url: "procesos/crud.php?",
              data: string2,
              success: function(data){
                alert("Proveedor creado con exito");
                location.reload();
                if(data!='FALSE'){
                  //console.log('entra al true');
                  window.location.href = "index.php?pagina=proveedores&msg="+data;
                //window.location.href = "index.php?pagina=comercio_add&id="+data;
                }
                else {alert('Error al insertar proveedor')}
              }
          });
    }else{
      alert("rellene los todos los datos");
    }

});



          function modificar_comercio(){
            console.log('Entra a la funcion 1');
            var cliente = $('#cliente_id_come').val();
            var comercio = $('#comercio_id_come').val();
            var razon = $('#razon_come').val();
            var condicioniva = $('#condicioniva_come option:selected').val();
            var cuit = $('#cuit_come').val();
            var telefono = $('#telefono_come').val();
            var rubro = $('#rubro_come option:selected').val();
            var ciudad =$('#ciudad_come option:selected').val();
            var direccion = $('#direccion_come').val();
            var perifact = $('#perifact_come option:selected').val();
            var dirnum = $('#dirnum_come').val();
            var vendedor = $('#vendedor_come option:selected').val();
            var string = "accion=edita_comercio&cliente="+cliente+"&razon="+razon+"&condicioniva="+condicioniva+"&cuit="+cuit+"&telefono="+telefono+"&rubro="+rubro+"&ciudad="+ciudad+"&direccion="+direccion+"&dirnum="+dirnum+"&vendedor="+vendedor+"&id="+comercio+"&perifact="+perifact;
            console.log('Entra a la funcion 2');
                    $.ajax({
                        type: "POST",
                        url: "procesos/crud.php?",
                        data: string,
                        success: function(data){
                          if(data!='FALSE'){
                            console.log('Entra a la funcion 3');
                            console.log('entra al true');
                            window.location.href = "index.php?pagina=clientes&msg=ok";
                          }
                          else {alert('Error al insertar comercio')}
                        }
                    });

}


    function buscaciudad(){

      var prov = $('.provincia').val();
    //  console.log('provincia= '+prov);
      $.ajax({
          type: "POST",
          url: "procesos/ciudades.php?",
          data: "p="+prov,
      //    crossDomain: true,
        //  cache: false,
          success: function(data) {
            $('.ciudad').html(data);
            $('.ciudad').prop("disabled", false);
        //    console.log('Resultado= '+data)
          }
      });

    }
/*
  $('#agregar_pago').click(function(){

      var prov = $('.provincia').val();
      var monto = $('[name=monto_pago]').val();
      var detalle = $('[name=detalle_pago]').val();
      var comercio = $('[name=comercio_pago] option:selected').val();
      var fecha = $('[name=fecha_pago]').val();
    //  console.log('provincia= '+prov);
      $.ajax({
          type: "POST",
          url: "procesos/crud.php?",
          data: "accion=add_pago&monto="+monto+"&detalle="+detalle+"&comercio="+comercio+"&fecha="+fecha,
      //    crossDomain: true,
        //  cache: false,
          success: function(data) {

            var cantidad = Object.keys(data).length;
            var ultimo = cantidad-1;
            console.log('Cantidad: '+cantidad)
            $('.card-title').html('Confirme los Pedidos a abonar');
            var confirmapedidos = '<div class="modal-body">'
                                  +'  <div class="row">'
                                  +'    <div class="table-responsive">'
                                  +'            <table class="table">'
                                  +'              <thead>'
                                  +'                <tr>'
                                  +'                  <th>#</th>'
                                  +'                  <th>Fecha</th>'
                                  +'                  <th>Detalle</th>'
                                  +'                  <th>Monto</th>'
                                  +'                  <th>Estado</th>'
                                  +'                  <th>Cuenta</th>'
                                  +'                </tr>'
                                  +'              </thead>'
                                  +'              <tbody>';
for(i=0; i < cantidad-1; i++) {
   confirmapedidos +='<tr> <td>'+data[i].npedido+'</td><td>'+data[i].fechapedido+'</td><td>'+data[i].detalle+'</td><td>$'+data[i].monto+'</td><td>'+data[i].cubierta+'</td><td>'+data[i].saldo+'</td></tr>';
}
 confirmapedidos +=' </tbody>'
 +'            </table>'
 +'          </div>'
 +'<h4>Saldo a cuenta $'+data[ultimo].saldo+'<h4> <button type="button" >Confirmar Pago</button>'
 +'</div></div>';
            $('#add_pago_form').html(confirmapedidos);
          //  console.log('data: '+confirmapedidos)

        //    console.log('Resultado= '+data)
          }
      });

    });

    */
    function editar_cliente(id){
      console.log('Entra a la funcion');
        var tipodni = $('.tipodni_editf option:selected').val(); //ok
    
        var cumple = $('.cumple_editf').val();
        var email = $('.email_editf').val(); //ok
        var telfijo = $('.telfijo_editf').val(); //ok
        var celular = $('.celular_editf').val(); //ok
        var provincia = $('.provincia_editf option:selected').val(); //ok
        var ciudad = $('.ciudad_editf option:selected').val(); //ok
        var razon = $('.razon_editf').val(); //ok
        var cuit = $('.cuit_editf').val(); //ok
        var rubro = $('.rubro_editf option:selected').val(); //ok
        var condicioniva = $('.condicioniva_editf option:selected').val(); //ok
        var asignado = $('.asignado_editf option:selected').val();
        var direccion_com = $('.direccion_com_editf').val(); //ok
        var numero_com = $('.numero_com_editf').val(); //ok
        var piso_com = $('.piso_com_editf').val(); //ok
        var depto_com = $('.depto_com_editf').val(); //ok
        var ecivil = $('.ecivil_editf option:selected').val(); //ok
        var financia = $('#financia_editf').val(); //ok
        var limite_financia = $('#limitefinancia_editf').val(); //ok
        var listap = $('#listap_editf').val();
        var dias_financia =$('#financia_dias_editf').val()
        var string2 = "accion=editar_clientes&id="+id+"&tipodni="+tipodni+"&dni="+cuit+"&cumple="+cumple+"&email="+email+"&telfijo="+telfijo+
          "&celular="+celular+"&provincia="+provincia+"&ciudad="+ciudad+"&asignado="+asignado+"&razon="+razon+"&cuit="+cuit+"&condicioniva="+condicioniva+
          "&rubro="+rubro+"&direccion_com="+direccion_com+"&numero_com="+numero_com+"&piso_com="+piso_com+"&depto_com="+depto_com+"&financia="+financia+
          "&limite="+limite_financia+"&listap="+listap+"&dias_financia="+dias_financia;
      //  tomociudad = ciudad;
         console.log('datos string2: '+string2);

            if(razon=='' || celular=='' || direccion_com=='' || numero_com=='' ){
              alert('Complete el Nombre, Celular y direccion del cliente');
            }else{
              console.log('entra alajax');
                    $.ajax({
                        type: "POST",
                        url: "procesos/crud.php?",
                        data: string2,
                        success: function(data){
                          if(data=='TRUE'){
                            //console.log('entra al true');
                            window.location.href = "index.php?pagina=clientes";
                          }
                          else {alert('Error al editar cliente');}
                          console.log(data)
                        }
                    });
                }

    }

//////////////////////////////////////////////////////////////////////////////////////

function elimina_c(id){
                        console.log('entra a la accion con id: '+id)
                        var string = "accion=elimina_clientes&id="+id;
                              $.ajax({
                                  type: "POST",
                                  url: "procesos/crud.php?",
                                  data: string,
                                  success: function(data){
                                                          if(data=='TRUE'){
                                                                  //console.log('entra al true')
                                                              window.location.href = "index.php?pagina=clientes";
                                                          } else {
                                                             // console.log('entra al false')
                                                          }
                                                        }
                                    });
                      }


  function elimina_carga(id){
            var string = "accion=elimina_carga&id="+id;
                                $.ajax({
                                    type: "POST",
                                    url: "procesos/crud.php?",
                                    data: string,
                                    success: function(data){
                                                            if(data=='TRUE'){
                                                                window.location.href = "index.php?pagina=estadocamion";
                                                            } else {

                                                            }
                                                          }
                                      });
                        }



  

 /*$('#editar_proveedor').click(function(){
  console.log("hola");
  });*/

 /*function editar_prov(id) {
   alert(id);
 }*/

 function editar_prov(id){
  var apellido = $('.apellido').val();
  var nombre = $('.nombre').val();
  var tipodni = $('.tipodni option:selected').val();
  var dni = $('.dni').val();
  var cumple = $('#cumple').val();
  var email = $('.email').val();
  var email2 = $('.email2').val();
  var telfijo = $('.telfijo').val();
  var celular = $('.celular').val();
  var telfijo_com = $('.telfijo_com').val();
  var celular_com = $('.celular_com').val();
  var provincia = $('.provincia option:selected').val();
  var rubro = $('.rubro option:selected').val();
  var ciudad = $('.ciudad option:selected').val();
  var direccion = $('.direccion').val();
  var numero = $('.numero').val();
  var piso = $('.piso').val();
  var depto = $('.depto').val();
  var razon = $('.razon').val();
  var cuit = $('.cuit').val();
  var condicioniva = $('.condicioniva option:selected').val();
  var direccion_com = $('.direccion_com').val();
  var numero_com = $('.numero_com').val();
  var piso_com = $('.piso_com').val();
  var depto_com = $('.depto_com').val();
  var notas = $('.notas').val();
  var celular2 = $('.celular2').val();
  var banco = $('.banco').val();
  var nroCuenta = $('.nroCuenta').val();
  var cbu = $('.cbu').val();
  var nombre_fantasia = $('.nombref').val();
  var alias = $('.alias').val();
  var upload = $('.upload').val();
  
  var string2 = "accion=editar_proveedor&id="+id+"&nombre="+nombre+"&tipodni="+tipodni+"&dni="+dni+"&cumple="+cumple+"&email="+email+"&email2="+email2+"&telfijo="+telfijo+"&celular="+celular+"&provincia="+provincia+"&ciudad="+ciudad+"&direccion="+direccion+"&numero="+numero+"&piso="+piso+"&depto="+depto+"&upload="+upload+"&razon="+razon+"&cuit="+cuit+"&condicioniva="+condicioniva+"&rubro="+rubro+"&telfijo_com="+telfijo_com+"&celular_com="+celular_com+"&direccion_com="+direccion_com+"&numero_com="+numero_com+"&piso_com="+piso_com+"&depto_com="+depto_com+"&notas="+notas+"&celular2="+celular2+"&banco="+banco+"&nroCuenta="+nroCuenta+"&cbu="+cbu+"&nombref="+nombre_fantasia+"&alias="+alias;
      tomociudad = ciudad;
  /*console.log('datos string2: '+string2);*/
  if(provincia.trim() !== '' && ciudad.trim() !== '' && direccion.trim() !== '' && numero.trim() !== ''
    && razon.trim() !== '' && rubro.trim() !== '' && condicioniva.trim() !== '' && cuit.trim() !== '' && email.trim() !== '' && email2.trim() !== '' && celular.trim() !== '' && celular2.trim() !== '' && telfijo.trim() !== '' && notas.trim() !== ''
    && banco.trim() !== '' && nroCuenta.trim() !== '' && cbu.trim() !== '' && nombre_fantasia.trim() !== ''){
              $.ajax({
                  type: "POST",
                  url: "procesos/crud.php?",
                  data: string2,
                  success: function(data){
                    if (data !== 'FALSE') {
                        alert("Proveedor editado con éxito");
                        window.location.href = "index.php?pagina=proveedores";
                    } else {
                        alert('Error al editar proveedor');
                    }
                  }
              });
    }else{
      alert("rellene los campos");
    }
  }




                      function elimina_t(id,tipo){
if(tipo=='1'){var paso = 'pedidos';}else {var paso = 'pagos';}
  console.log('entra a la transaccion con id: '+id+' y tipo'+tipo+' y queda el paso='+paso)
    var string = "accion=elimina_transaccion&id="+id;
          $.ajax({
              type: "POST",
              url: "procesos/crud.php?",
              data: string,
              success: function(data){
              if(data=='TRUE'){
             console.log('entra al true');
              window.location.href = "index.php?pagina="+paso;
          } else {
             // console.log('entra al false')

          }
                                      }
                });


                      }

          function llenaselect(select){
          console.log('entra a llenaselect con:'+select)
            if(select=='fabricante'){var accion='fabrilist';}
            if(select=='categoria'){var accion='catelist';}
            if(select=='proveedor'){var accion='provlist';}

            var url ="procesos/productos.php?";
            var string ='accion='+accion;
             $.ajax({
                     type: "POST",
                     url: url,
                     data: string,
                     success: function(data){
                     if(data != 'FALSE'){
                                 $("#"+select).html(data)
                           }
                      else{
                              alert('Error al cargar el combo');
                          }

                       }
             });
          }

          function activa_afip(){
          if($('.dni').val()!='' || $('.dni').val()!=undefined){$('.btn-afip').attr('disabled',false);}else{$('.btn-afip').attr('disabled',true);}
          }

          function datos_afip(){
            if($('.cuit').val()!=''){
            var dni= $('.cuit').val();
            $('.btn-afip').html('<i class="fa fa-sync-alt fa-spin fa-2x fa-lg" style="font-size:25px;"></i>');
            $('.btn-afip').attr('disabled',true);
            $.ajax({
            url: "consultas/cuit_afpi_topo.php?dni="+dni,
           // datatype: "html",
            type: "GET",
            success: function(data){
              if(data.d!=null){
                if(data.d.CategoriaImpositiva=='MO'){$('.condicioniva').val('m')}
                if(data.d.CategoriaImpositiva=='RI'){$('.condicioniva').val('ri')}
                $('.cuit').val(data.d.NroDoc);
                $('.razon').val(data.d.RazonSocial);
                $('.direccion_com').val(data.d.Domicilio);
                $('.btn-afip').html('<i class="fa fa-refresh"></i> AFIP');$('.btn-afip').attr('disabled',false);
              } else {
                alert('No se encontraron datos en AFIP');$('.btn-afip').html('<i class="fa fa-refresh"></i> AFIP');$('.btn-afip').attr('disabled',false);} //'Error Al Grabar Pedido,'
            }

            })
          }else{alert('Complete el campo DNI');}
      }

      /*
      function actualizaitems(id,cant){
        var linea ='';
        for(var i=0; i < cant ;i++){
          linea += '{"'+$('#iddevol_'+i).val()+'": '++'}';
        }
      }
      */



function liquidacion(id, tipo){
  var acumulando_items =0;
  $(".cantidades").each(function(){
    acumulando_items = parseFloat(acumulando_items) + parseFloat($(this).val());
    });
//  var prov = $('.provincia').val();
//  console.log('provincia= '+prov);
  var idcarga = id;
  var vendedor = $('#personal_'+id).val();
  var camion = vendedor;
  var devoluciones = $('#cantdevol_'+id).val();
  var montototal = $('#tot_a_cobrar_'+id).val();
  var entrega = $('#total_rendido_'+id).val();
  var fecha = $('#fecha_'+id).val();
  var observaciones = $('#observacion_liq_'+id).val();
  var items = $('#items_'+id).val();

  $.ajax({
      type: "POST",
      url: "procesos/camion.php?",
      data: "a=liquida&idcarga="+idcarga+"&vendedor="+vendedor+"&camion="+camion+"&devoluciones="+devoluciones+"&montototal="+montototal+"&entrega="+entrega+"&observaciones="+observaciones+"&items="+items+"&fecha="+fecha+'&tipo='+tipo,
      success: function(data) {
        if(data=='true'){
          window.location.href = "index.php?pagina=liquidaciones";

        }
        console.log('Resultado= '+data)
      }
  });

}

function liquidacion_stock(id){
  var acumulando_items =0;
  $(".cantidades").each(function(){
    acumulando_items = parseFloat(acumulando_items) + parseFloat($(this).val());
    });
  var idcarga = id;
  var vendedor = $('#personal_'+id).val();
  var camion = vendedor;
  var devoluciones = $('#cantidadprod_'+id).val();
  var fecha = $('#fecha_'+id).val();
  var observaciones = $('#observacion_liq_'+id).val();
  var items = $('#items_'+id).val();

  $.ajax({
      type: "POST",
      url: "procesos/camion.php?",
      data: "a=liquida_stock&idcarga="+idcarga+"&vendedor="+vendedor+"&camion="+camion+"&devoluciones="+devoluciones+"&observaciones="+observaciones+"&items="+items+"&fecha="+fecha,
      success: function(data) {
        if(data=='true'){
          window.location.href = "index.php?pagina=liquidaciones";

        }
        console.log('Resultado= '+data)
      }
  });
}
function liquidacion_plata(id){
  var idcarga = id;
  var vendedor = $('#personal_'+id).val();
  var devoluciones = $('#cantidadprod_'+id).val();
  var montototal = $('#tot_a_cobrar_'+id).val();
  var entrega = $('#total_rendido_'+id).val();
  var fecha = $('#fecha_'+id).val();
  var observaciones = $('#observacion_liq_'+id).val();

  $.ajax({
      type: "POST",
      url: "procesos/camion.php?",
      data: "a=liquida_plata&idcarga="+idcarga+"&vendedor="+vendedor+"&devoluciones="+devoluciones+"&montototal="+montototal+"&entrega="+entrega+"&observaciones="+observaciones+"&fecha="+fecha,
      success: function(data) {
        if(data=='true'){
          window.location.href = "index.php?pagina=liquidaciones";

        }
        console.log('Resultado= '+data)
      }
  });
}

function activarCliente(id){
  $.ajax({
    type: "POST",
    url: "procesos/crud.php?",
    data: "accion=activa_cliente&id="+id,
    success: function(data) {
      if(data=='TRUE'){
        
        location.reload()

      }
      console.log('Resultado= '+data)
    }
});
}
function desactivarCliente(id){
  $.ajax({
    type: "POST",
    url: "procesos/crud.php?",
    data: "accion=desactiva_cliente&id="+id,
    success: function(data) {
      if(data=='TRUE'){
        location.reload()

      }
      console.log('Resultado= '+data)
    }
});
}

function add_factura(){
  var proveedor = $('#provee_factura').val();
  var nro_factura = $('#nro_factura').val();
  var tipo = $('#tipo_factura option:selected').val();
  var monto = $('#monto_factura').val();
  var obs = $('#detalle_factura').val();

  console.log(proveedor);
  var string2 = "accion=add_facturas&proveedor="+proveedor+"&nro_factura="+nro_factura+"&tipo="+tipo+"&monto="+monto+"&obs="+obs;
          $.ajax({
              type: "POST",
              url: "procesos/crud.php?",
              data: string2,
              success: function(data){
                console.log(data)
                if(data!='FALSE'){
                  window.location.href = "index.php?pagina=facturas&msg="+data;
                }
                else {alert('Error al insertar factura')}
              }
          });
}
function add_factura_pago(){
  var factura = $('#nro_factura_pago').val();
  var fecha = $('#fecha_factura_pago').val();
  var banco = $('#banco_factura_pago').val();
  var tipo_pago = $('#tipo_pago_factura_pago').val();
  var numero_cheque = $('#numero_cheque_factura_pago').val();
  var fecha_emision = $('#fecha_emision_factura_pago').val();
  var fecha_cobro = $('#fecha_cobro_factura_pago').val();
  var titular = $('#titular_factura_pago').val();
  var cuit = $('#cuit_factura_pago').val();
  var monto = $('#monto_factura_pago').val();
  var total = $('#total_factura_pago').val();
  var facturas = $('#facturas_factura_pago').val();
  var origen = $('#origen_factura_pago').val();
  var obs = $('#detalle_factura_pago').val();
  var string2 = "accion=add_facturas_pago&factura="+factura+"&fecha="+fecha+"&banco="+banco+"&numero_cheque="+numero_cheque+"&fecha_emision="+fecha_emision+"&fecha_cobro="+fecha_cobro+"&titular="+titular+"&cuit="+cuit+"&monto="+monto+"&total="+total+"&facturas="+facturas+"&origen="+origen+"&obs="+obs+"&tipo_pago="+tipo_pago;
  if(!factura || factura==''){
    alert('Seleccionar numero factura')
  }else if(!monto || monto==''){
    alert('Ingresar monto')
  }else{
    $.ajax({
      type: "POST",
      url: "procesos/crud.php?",
      data: string2,
      success: function(data){
        console.log(data)
        data = data.split(' ? ');
        if(data[1] != 'FALSE'){
          window.location.href = "index.php?pagina=facturas&msg="+data;
        }
        else {alert(data[0] || 'Error al insertar pago factura')}
      }
    });
  }
  
}

$("#agregarGasto").click(function(){
  var mov = $('#movimiento').val();
  var nroComp = $('#comprobante').val();
  var monto = $('#monto').val();
  var observaciones = $('#detalle-alqui').val();
  var string2 = "accion=add_gasto&mov="+mov+"&nroComp="+nroComp+"&monto="+monto+"&observaciones="+observaciones;
          $.ajax({
              type: "POST",
              url: "procesos/crud.php?",
              data: string2,
              success: function(data){
                console.log(data);
                if(data!='FALSE'){
                  alert('gasto creado');
                  window.location.href = "index.php";
                }
                else {alert('Error al insertar gasto')}
              }
          });
});





function cambiafacturasprove(){
  var proveedor = $('#provee_pago').val();
  var string2 = "accion=get_facturas&proveedor="+proveedor;
  $('#nro_factura_pago').html('')
  $.ajax({
      type: "POST",
      url: "procesos/crud.php?",
      data: string2,
      success: function(data){
          console.log(data)
        if(data!='FALSE'){
          data = JSON.parse(data);
          console.log(data);
          data.sort((a, b) => a.id - b.id);
          $('#nro_factura_pago').html('');
          var opciones = "";
          data.forEach(e => {
              opciones += `<option saldo="${e.saldo}" value="${e.id}">${e.nro_factura} (SALDO: $${e.saldo})</option>`;
          });
          $('#nro_factura_pago').html(opciones);
          $('#total_factura_pago').val(data.filter((saldo) => saldo.id === $('#nro_factura_pago').val())[0].saldo);
          $('#facturas_factura_pago').val(JSON.stringify(data));
        }
        else {alert('Error al cargar facturas')}
      }
  });
}
function envia_productos_wsp(lista_precio,telefono){
  var dataString = 'productos=GET';
  $.ajax({
    type: "POST",
    url: "procesos/crud.php?",
    data: dataString,
    dataType: 'json',
    crossDomain: true,
    cache: false,
    success: function(data) {
      if (data.estado == 'true') {
        data= data.productos
        let mensaje="LISTADO PRODUCTOS : \n";
        data.forEach(producto => {
          if(lista_precio==1 && producto.precio_producto && producto.precio_producto!='null' && producto.precio_producto!=null){
            mensaje+="- "+producto.detalle_producto+"   $"+producto.precio_producto+"\n";
          } else if(lista_precio==2 && producto.precio_producto2 && producto.precio_producto2!='null' && producto.precio_producto2!=null){
            mensaje+="- "+producto.detalle_producto+"   $"+producto.precio_producto2+"\n";
          } else if(lista_precio==3 && producto.precio_producto3 && producto.precio_producto3!='null' && producto.precio_producto3!=null){
            mensaje+="- "+producto.detalle_producto+"   $"+producto.precio_producto3+"\n";
          }
          mensaje+="\n";
        })
        mensaje = encodeURIComponent(mensaje.replace(/&/g, '%26').replace(/#/g, '%23'));
        let url_wsp='https://wa.me/'+ telefono +'?text='+mensaje;
        var win = window.open(url_wsp, '_blank');
        win.focus()
        
      }
    }
  })
}

function abrirPedido(id) {
  var string = "accion=buscarTrans&id="+id;
  $.ajax({
          type: "POST",
          url: "procesos/crud.php?",
          data: string,
          success: function(data){
            if (data !== 'FALSE') {
              console.log(data);
              $('.pedi').css('display','block');
              $('#contenedor_total').css('display','none');
              $('.pedi').css('padding','20px');
              $('.pedi').html(data);
            } else {
                alert('Error al agregar compra');
            }
          }
      });
  
}

function impresion() {
  $('#contenedor_total').css('display','none');
  $('.footer').css('display','none');
  $('.tooltip').css('display','none');
  $('#back-to-top').css('display','none');
  $('.botones').css('display','none');
    window.print();
    window.location.reload();
}

function cerrar() {
    window.location.reload();
}

function verPass() {
  /*alert('quiere ver su contraseña');*/
  var passwordInput = $('#password');
  var icon = $('#icono');

  if (passwordInput.attr('type') === 'password') {
      passwordInput.attr('type', 'text');
      icon.removeClass('fa-eye-slash');
      icon.addClass('fa-eye');
  } else {
      passwordInput.attr('type', 'password');
      icon.removeClass('fa-eye');
      icon.addClass('fa-eye-slash');
  }
}


/*impresion multiple forma 2*/
function multiple(fecha, fecha2, vendedor) {
  var valoresCheckboxes = [];
  $('.checkMultiple').each(function() {
      if ($(this).prop('checked')) {
          valoresCheckboxes.push($(this).val());
      }
  });


  var string = "accion=select_t&valoresCheckboxes="+valoresCheckboxes+"&desde="+fecha+"&hasta="+fecha2+"&vendedor="+vendedor;
  console.log(string);
  if (valoresCheckboxes.length === 0) {
    alert('Por favor, seleccione una fila para imprimir');
  }else{
    $.ajax({
          type: "POST",
          url: "procesos/crud.php?",
          data: string,
          success: function(data){
            if (data !== 'FALSE') {
              console.log(data);
              $('.modal-impresion').css('display','block');
              $('.add-impresion').html(data);
              $('.esconder-imprimir').css('display','none');
              $('.navbar').css('display','none');
              $('.footer').css('display','none');
              $('.tooltip').css('display','none');
              $('#back-to-top').css('display','none');
              window.print();
              window.location.href = "index.php?pagina=pedidos";
            } else {
                alert('Error al agregar compra');
            }
          }
      });
  }

}





/*impresion multiple forma 1*/

function impresionMultiple(fecha1, fecha2,vendedor) {
  var valoresCheckboxes = [];
  $('.checkMultiple').each(function() {
      if ($(this).prop('checked')) {
          valoresCheckboxes.push($(this).val());
      }
  });
  console.log(fecha1+' '+fecha2+' '+vendedor);

  var string = "accion=select_transaccion&valoresCheckboxes="+valoresCheckboxes+"&desde="+fecha1+"&hasta="+fecha2+"&vendedor="+vendedor;

  if (valoresCheckboxes.length === 0) {
    alert('Por favor, seleccione una fila para imprimir');
  }else{
    $.ajax({
        type: "POST",
        url: "procesos/crud.php?",
        data: string,
        success: function(data){
          if (data !== 'FALSE') {
            $('.tabla-resultado').css('display','block');
            $('.tabla-modal').html(data);
            $('.esconder-imprimir').css('display','none');
            $('.navbar').css('display','none');
            $('.footer').css('display','none');
            $('.tooltip').css('display','none');
            $('#back-to-top').css('display','none');
            window.print();
            window.location.href = "index.php?pagina=pedidos";
          } else {
              alert('Error al agregar compra');
          }
        }
    });
}
}





