var objeto;
var posicion;
var credito_s;
var credito_a;
var total_billetera = 0;
var fechas_liquidar = '';
var cant_clientess = [];

var url_gral = 'https://sistemabigpollo.mayoristasbahia.com/procesos/functions-online.php?';

$(document).ready(function() {
  $("#carga").swipe({
    //Single swipe handler for left swipes
    swipeDown: function(event, direction, distance, duration, fingerCount) {
      chequeo_carga();
    },
    //Default is 75px, set to 0 for demo so any distance triggers swipe
    threshold: 0
  });
});

//-------------------------------------------------------------------//
//Recorre el pedido y te dice cuantos hay en el pedido
function cantEnPedido(idProducto) {
  var pedido = [];
  if (localStorage.pedido != undefined) {
    pedido = JSON.parse(localStorage.pedido);
  }

  var cantEnPedido = 0;
  for (var i = 0; i < pedido.length; i++) {
    if (pedido[i].id == idProducto) {
      cantEnPedido = cantEnPedido + parseFloat(pedido[i].cantidad);
    }
  }

  return cantEnPedido;
}

function abrelink(link) {
  window.location.href = link + '.html';
}

function cierra_cliente() {
  Swal.fire({
    title: 'Esta Seguro?',
    text: "¡Se elminara el pedido actual!",
    //icon: 'warning',
    type: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Eliminar!'
  }).then((result) => {
    if (result.value) {
      localStorage.lectura = '';
      localStorage.removeItem('pedido');
      $('.cliente_select').hide();
      $('#botonera_cliente').hide();
      lnkint('welcome');
    }
  })

}


function sortJSON(data, key, orden) {
  return data.sort(function(a, b) {
    var x = a[key],
      y = b[key];

    if (orden === 'asc') {
      return ((x < y) ? -1 : ((x > y) ? 1 : 0));
    }

    if (orden === 'desc') {
      return ((x > y) ? -1 : ((x < y) ? 1 : 0));
    }
  });
}

function add_producto(id) {

  if (id != undefined) {
    //comprobar cliente seleccionado
    if (localStorage.lectura != '' && localStorage.lectura != undefined) {

      //comprobar stock en camion

      // comprobar si existe el producto ya en el carro

      //comprobar campo precio cantidad
      //comprobar que la cantidad no sea mayor que la del camion

      //Registrar en localstorage
      var data = [];
      if (localStorage.productos != undefined) {
        data = JSON.parse(localStorage.productos)
      }
      var cliente = JSON.parse(localStorage.clientes)[localStorage.lectura];

      var cantidad = data.length;
      var stock = 1;
      for (var i = 0; i < cantidad; i++) {

        if (data[i].id == id) {
          var detalle = data[i].presentacion;
          var descripcion = data[i].titulo;
          var img = data[i].foto;
          var codigo = data[i].codigo;
          var precio = data[i].precio1;
          if(cliente.lista_precio==2) precio = data[i].precio2;
          if(cliente.lista_precio==3) precio = data[i].precio3;
          var titulo = data[i].titulo;
          stock = data[i].stock;
          if (img == 'undefined' || img == undefined) {
            img = 'sin-imagen.png';
          }
        }
      }
      var pedido = [];
      if (localStorage.pedido != undefined) {
        pedido = JSON.parse(localStorage.pedido);
      }

      var cantEnPedido = 0;
      for (var i = 0; i < pedido.length; i++) {

        if (pedido[i].id == id) {
          cantEnPedido = cantEnPedido + parseFloat(pedido[i].cantidad);
        }
      }
        console.log( parseFloat(stock))
      var maximoAAgregarAlPedido = parseFloat(stock) - parseFloat(cantEnPedido);
      if(localStorage.id=="2") maximoAAgregarAlPedido=9999;
      if (maximoAAgregarAlPedido > 0 || localStorage.id=="2") {


        var modal = '';

        modal = '<div class="modal fade bottom" id="producto_pop" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="true" aria-hidden="true">' +
          '    <div class="modal-dialog modal-full-height modal-bottom modal-notify modal-danger" role="document">' +
          '      <div class="modal-content">' +
          ' <input type="hidden" id="codigo_pop" value="' + codigo + '">' +
          ' <input type="hidden" id="titulo_pop" value="' + titulo + '">' +
          ' <input type="hidden" id="foto_pop" value="' + img + '">' +
          ' <input type="hidden" id="detalle_pop" value="' + detalle + '">' +
          ' <input type="hidden" id="descripcion_pop" value="' + descripcion + '">' +
          ' <input type="hidden" id="id_pop" value="' + id + '">' +
          '        <div class="modal-body" style="font-size: 18px;">' +
          '        <div class="row" >' +
          '          <div class="col-xs-4" ><img style="width:100%;border-radius: 10%;" onerror="this.src=\'img/prod/sin-imagen.png\'"  src="../../img/prod/' + img + '" alt="' + codigo + '" /></div>' +
          '          <div class="col-xs-8" ><div class="col-xs-12" style="text-align:center">' + descripcion + ' (' + detalle + ')</div><div class="col-xs-12" style="background-color: gold;text-align: center;font-weight: 800;">$ ' + precio + '</div></div>' +
          '        </div>' +
          '          <div id="detalle_cobra">' +
          '            <div class="center" style="margin-top: 20px;">' +
          '              <span style="text-align:center;font-weight: bold;">Cantidades:</span></br>' +
          '              <div class="input-group">' +
          '                <span class="input-group-btn">' +
          '                  <button type="button" onclick="cantidadresta(\'prod\')" class="btn btn-danger btn-number" data-tipo="prod" data-type="minus" data-field="quant[1]">' +
          '                    <span class="glyphicon glyphicon-minus"></span>' +
          '                  </button>' +
          '                </span>' +
          '                <input type="number" id="cant_prodmodal" name="quant[1]" onchange="cambiapreciopros();" min="1" max="' + maximoAAgregarAlPedido + '" style="height: 45px;text-align: center;font-weight: bold;font-size: xx-large;" class="form-control input-number" value="1">' +
          '                <span class="input-group-btn">' +
          '                  <button type="button" onclick="cantidadsuma(\'prod\')" class="btn btn-success btn-number" data-tipo="prod" data-type="plus" data-field="quant[1]">' +
          '                    <span class="glyphicon glyphicon-plus"></span>' +
          '                  </button>' +
          '                </span>' +
          '              </div>' +
          '              <p></p>' +
          '            </div>' +
          '            <div class="center" style="margin-top: 20px;">' +
          '              <span style="text-align:center;font-weight: bold;">Cantidad en pedido:</span></br>' +
          '                <input type="number" id="cant_pedido" name="quant[1]" min="1000" style="width:100%;height: 45px;text-align: center;font-weight: bold;" class="form-control input-number" disabled  value="' + cantEnPedido + '">' +
          '              <p></p>' +
          '            </div>' +
          '            <div class="center" style="margin-top: 20px;">' +
          '              <span style="text-align:center;font-weight: bold;">Precio Unitario:</span></br>' +
          '                <input type="number" id="precio_prod" onkeyup="cambiapreciopros()" name="quant[1]" min="1000" style="width:100%;height: 45px;text-align: center;font-weight: bold;" class="form-control input-number" value="' + precio + '">' +
          '              <p></p>' +
          '            </div>' +
          '              <div class="forms compose-list">' +
          '                  <div id="cobra-total" class="group clearfix bounceIn animated" style="margin-bottom: 5px;margin-top: 20px;font-weight: bold;font-size: x-large;">' +
          '                    <center>TOTAL $ <span id="subtotal_pop"></span></center>' +
          '                  </div>' +
          '              </div>' +
          '          </div>'  +
          '          </div>' +
          '        <div class="modal-footer" style="text-align:center">' +
          '          <a type="button" class="btn btn-success waves-effect waves-light" onclick="confirmaaddprod()"  style="background:#4cae4c">Incluir' +
          '            <i class="far fa-gem ml-1"></i>' +
          '          </a>' +
          '          <a type="button" class="btn btn-danger waves-effect" data-dismiss="modal">Cancelar</a>' +
          '        </div>' +
          '      </div>' +
          '    </div>' +
          '  </div>' +
          '</div>';

        $('modal').html(modal);
        cambiapreciopros();
        $('#producto_pop').modal('show');
      } else {
        Swal.fire({
          // position: 'top-end',
          type: 'info',
          title: 'No tiene mas de este producto en stock',
          //html: '',
          showConfirmButton: false,
          timer: 2000,
          allowOutsideClick: false,
          onClose: () => {
            //Aca hacemos que vaya al carrito.
            //lnkint('pedido');
          }
        })
      }
    } else {
      Swal.fire({
        title: 'Seleccion de cliente!',
        text: "Para incluir un producto, previamente es necesario seleccionar el cliente.",
        //icon: 'warning',
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Seleccionar!'
      }).then((result) => {
        if (result.value) {
          prod_cliente(id)
        }
      })


    }
  }
}

//------------------------------------------------//
function prod_cliente(id) {
  console.log('entra a seleccion')
  lnkint('clientes', id);
}

//------------------------------------------------//
function confirmaaddprod() {
  var pedido = [];
  var idprod=$('#id_pop').val();
  if (localStorage.pedido) {
    pedido = JSON.parse(localStorage.pedido);
  } else {
    var data = JSON.parse(localStorage.clientes);
    pedido.nose = 'NI idea';
    pedido.id_cliente = data[localStorage.lectura].id;
    pedido.nombre_cliente = data[localStorage.lectura].nombre + ' ' + data[localStorage.lectura].apellido;
  }
  var esta = false;
  var valido = true;
  for (let i = 0; i < pedido.length; i++) {
    if (pedido[i].id === idprod) {
        pedido[i].cantidad += parseInt($('#cant_prodmodal').val());
        pedido[i].subtotal = pedido[i].cantidad * pedido[i].preciou
        esta=true;
        break;
    }
  }
  if(!esta){
    var precio = $('#precio_prod').val()
    if(precio==undefined || precio.trim()=='' || precio=='undefined'){
      valido=false;
      Swal.fire({
        type: 'info',
        title: 'Precio para esta lista de cliente no disponible',
        showConfirmButton: true
      })
      $('#producto_pop').modal('hide');
      return;
    }else{
      pedido.push({
        id: $('#id_pop').val(),
        codigo: $('#codigo_pop').val(),
        cantidad: $('#cant_prodmodal').val(),
        foto: $('#foto_pop').val(),
        titulo: $('#titulo_pop').val(),
        detalle: $('#detalle_pop').val(),
        preciou: $('#precio_prod').val(),
        subtotal: $('#subtotal_pop').html(),
        descripcion: $('#descripcion_pop').val()
      });
    }
  }
  
  if(valido){
    if (localStorage.pedido = JSON.stringify(pedido)) {
      $('#producto_pop').modal('hide');
      listado_pedidos();
      /*Swal.fire({
        // position: 'top-end',
        type: 'success',
        title: 'El producto se cargo al pedido correctamente',
        //html: '',
        showConfirmButton: false,
        timer: 2000,
        allowOutsideClick: false,
        onClose: () => {
          console.log("Estoy yendo");
          $('#unidad-'+idprod).html(cantEnPedido(idprod)+"un")
        }
      })*/
       $('#unidad-'+idprod).html(cantEnPedido(idprod)+"un")
    }
  }
  

}
function confirmaaddprod2(idprod) {
  var pedido = [];
  var cliente = JSON.parse(localStorage.clientes)[localStorage.lectura];
  var totalPedido = 0; // Inicializar el total del pedido
  
  if (localStorage.pedido) {
    pedido = JSON.parse(localStorage.pedido);
  } else {
    var data = JSON.parse(localStorage.clientes);
    pedido.nose = 'NI idea';
    pedido.id_cliente = data[localStorage.lectura].id;
    pedido.nombre_cliente = data[localStorage.lectura].nombre + ' ' + data[localStorage.lectura].apellido;
  }
  var valido = true;
  
  //buscar producto
  var data_prods = [];
    if (localStorage.productos != undefined) {
      data_prods = JSON.parse(localStorage.productos)
    }
   
    var cantidad = data_prods.length;
    var stock = 1;
    for (var i = 0; i < cantidad; i++) {

      if (data_prods[i].id == idprod) {
        var detalle = data_prods[i].presentacion;
        var descripcion = data_prods[i].titulo;
        var img = data_prods[i].foto;
        var codigo = data_prods[i].codigo;
        var precio = data_prods[i].precio1;
        if(cliente.lista_precio==2) precio = data_prods[i].precio2;
        if(cliente.lista_precio==3) precio = data_prods[i].precio3;
        var titulo = data_prods[i].titulo;
        stock = data_prods[i].stock;
        if (img == 'undefined' || img == undefined) {
          img = 'sin-imagen.png';
        }
      }
    }

  // buscar si en pedido no existe ya uno con ese id de producto y sumarle la cantidad
  var esta = false;
  for (let i = 0; i < pedido.length; i++) {
    if (pedido[i].id === idprod) {
        if(parseFloat(stock)-parseFloat(pedido[i].cantidad)>0){
            pedido[i].cantidad++;
            pedido[i].subtotal = pedido[i].cantidad * pedido[i].preciou;
        
        }else{
            Swal.fire({
            type: 'info',
            title: 'No tiene mas de este producto en stock',
            showConfirmButton: false,
            timer: 2000,
            allowOutsideClick: false,
            onClose: () => {
              return;
            }
          })
        }
       esta=true;
        break;
    }
  }
  if(!esta){
    
    

    if(precio==undefined || precio.trim()=='' || precio=='undefined'){
      Swal.fire({
        // position: 'top-end',
        type: 'info',
        title: 'Precio para esta lista de cliente no disponible',
        //html: '',
        showConfirmButton: true
      })
      valido = false;
      $('#producto_pop').modal('hide');
      return
    }else{
      var maximoAAgregarAlPedido = parseFloat(stock) - parseFloat(cantEnPedido(idprod));
      if (maximoAAgregarAlPedido > 0 || localStorage.id=="2") {
        pedido.push({
          id: idprod,
          codigo: codigo,
          cantidad: 1,
          foto: img,
          titulo: titulo,
          detalle: detalle,
          preciou: precio,
          subtotal: precio,
          descripcion: descripcion
        });
        

        totalPedido += parseFloat(precio);
      }else {
          Swal.fire({
            // position: 'top-end',
            type: 'info',
            title: 'No tiene mas de este producto en stock',
            //html: '',
            showConfirmButton: false,
            timer: 2000,
            allowOutsideClick: false,
            onClose: () => {
              //
            }
          })
        }
      

   
    }
  }
  if(valido){
    if (localStorage.pedido = JSON.stringify(pedido)) {
      $('#producto_pop').modal('hide');
      listado_pedidos();
      /*Swal.fire({
        // position: 'top-end',
        type: 'success',
        title: 'El producto se cargo al pedido correctamente',
        //html: '',
        showConfirmButton: false,
        timer: 2000,
        allowOutsideClick: false,
        onClose: () => {
          console.log(cantEnPedido(idprod));
          $('#unidad-'+idprod).html(cantEnPedido(idprod)+"un")
        }
      })*/
      $('#unidad-'+idprod).html(cantEnPedido(idprod)+"un")
    }
  }
  
  totalPedido = pedido.reduce((total, producto) => total + parseFloat(producto.subtotal), 0);
  localStorage.setItem('pedidoValor', totalPedido);
  /*console.log(localStorage.pedidoValor);*/
  $('#total').html(localStorage.pedidoValor);
}


  


//------------------------------------------------//

function cambiapreciopros() {

  console.log('Cantidad: ' + $('#cant_prodmodal').val());
  console.log('Precio: ' + $('#precio_prod').val());

  var total = parseFloat($('#cant_prodmodal').val()) * parseFloat($('#precio_prod').val());
  $('#subtotal_pop').html(total.toFixed(2));
}

//-----------------------------------------------//

//***********************************************//
function envia_pedido() {
  console.log('entra');
  //Aca tengo que ver si es solo contado o no.
  var cliente = JSON.parse(localStorage.clientes)[localStorage.lectura];
  var saldo=0;
  $.ajax({
    type: "POST",
    url: url_gral,
    data: 'consul_cc_clientes&u=' + localStorage.id + '&c=' + cliente.id,
    dataType: 'json',
    crossDomain: true,
    cache: false,
    success: function(data2) {
      saldo = data2.saldo;
      saldo = Number(saldo.replace('.',''))
      var total =Number($("#total_final").html());
      if (Number(cliente.financiacion.tope)-saldo -total > 0){
        let mensaje="hola";
        var modal = '';
        modal = '<div class="modal fade bottom" id="modalenvia" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="true" aria-hidden="true">' +
          '    <div class="modal-dialog modal-full-height modal-bottom modal-notify modal-danger" role="document">' +
          '      <div class="modal-content">' +
          '        <div class="modal-body" style="font-size: 18px;">' +
          '              <h4 style="text-align:center">Se esta por enviar el pedido!</h4><div class="forms compose-list">' +
          '                  <div id="cobra-total" class="group clearfix bounceIn animated" style="margin-bottom: 5px;margin-top: 20px;font-weight: bold;font-size: x-large;">' +
          '                    <center>Por un Total de $' + $("#total_final").html() + ' </center>' +
          '                  </div>' +
          '                  <div id="opciones" class="group clearfix bounceIn animated" style="margin-bottom: 5px;margin-top: 20px;text-align:center">' +
          '                     <p>A Cuenta Corriente</p>'+
          '                  </div>' +
          '                  <div id="opciones_monto" class="group clearfix bounceIn animated hide" style="margin-bottom: 5px;margin-top: 20px;font-weight: bold;font-size: x-large;">' +
          '                    <label for="motoabona" style="font-size: 18px;text-align: center;width: 100%;">Ingrese el monto a cancelar' +
          '                      <input onchange="habilitobtn()" type="number" value="' + $("#total_final").html() + '" step="any" id="motoabona" class="form-control">' +
          '                    </label>' +
          '                  </div>' +
          '              </div>' +
          '          </div>' +
          '        <div class="modal-footer" style="text-align:center">' +
          '          <button type="button" class="btn btn-primary waves-effect waves-light remito-factura" min="1" id="remito" onclick="wsp(1)">Remito' +
          '            <i class="far fa-gem ml-1"></i>' +
          '          </button>' +
// {{urlencode(str_replace(["&", "#"], ["%26", "%23"], $message))}}
          '          <button type="button" class="btn btn-danger waves-effect waves-light remito-factura" min="1" id="factura" onclick="wsp(2)">Factura' +
          '            <i class="far fa-gem ml-1"></i>' +
          '          </button>' +
          '        </div>' +
          '        <div id="wsp" class="modal-footer" style="text-align:center">' +
          '          <button type="button" class="btn btn-success waves-effect waves-light" onclick="envia_pedido_ok()" min="1" id="confpago"  style="background:#4cae4c">Confirmar y enviar por WhatsApp' +
          '            <i class="far fa-gem ml-1"></i>' +
          '          </button>' +
// {{urlencode(str_replace(["&", "#"], ["%26", "%23"], $message))}}
          '          <a type="button" class="btn btn-danger waves-effect" data-dismiss="modal">Cancelar</a>' +
          '        </div>' +
          '      </div>' +
          '    </div>' +
          '  </div>' +
          '</div>';
        
        $('modal').html(modal);
        $('#confpago').prop('disabled', true);
        $('#modalenvia').modal('show');
      }else{
        Swal.fire({
          type: 'error',
          title: 'No puede avanzar con esta compra porque supera su tope de financiación',
          showConfirmButton: true
        })
      }
      
    }
  })
}

function wsp(dato) {
  if (dato == 1) {
    localStorage.setItem('comprobante', 'REMITO');
  }else{
    localStorage.setItem('comprobante', 'FACTURA');
  }
  alert("usted eligio? "+localStorage.comprobante);
  $('#confpago').prop('disabled', false);
  $('#remito').prop('disabled', true);
  $('#factura').prop('disabled', true);
}

function habilitobtn() {
  //$('#motoabona').val($("#total_final").html());
  //$('#motoabona').val($("#total_final").html());
  if ($('#fp option:selected').val() == '1') {
    $('#confpago').attr('disabled', false);
    $('#opciones_monto').addClass("hide");
  //  console.log('ingresa a pagar');
  } else if ($('#fp option:selected').val() == '2') {
//    console.log('ingresa a pagar');
    $('#opciones_monto').removeClass("hide");
    console.log($('#motoabona').val());
    $('#confpago').attr('disabled', true);
    if ($('#motoabona').val() != '' && $('#motoabona').val() != '0') {
      console.log('Entra a mostrar el monto a pagar');
      $('#confpago').attr('disabled', false);
    }
  } else {
    $('#confpago').attr('disabled', true);
    $('#opciones_monto').addClass("hide");
  }
}

//---------------------------------------------------------------//

function confirma_retiro() {
  if ($('#monto_modal_retiro').val() != '') {

    var total = $('#monto_modal_retiro').val().replace(".", "");
    var detalle = $('#detalle_modal_retiro').val();
    var tipo = $('#tipo_retiro option:selected').val();
    var string = 'a=retiro&u=' + localStorage.id + '&d=' + detalle + '&m=' + total+ '&t=' + tipo;
    $.ajax({
      type: "POST",
      url: url_gral,
      data: string,
      crossDomain: true,
      cache: false,
      success: function(data) {
        if (data == 'TRUE') {
          Swal.fire({ // position: 'top-end',
            type: 'success',
            title: 'El Retiro de $' + total + ' por ' + $('#tipo_retiro option:selected').text() + ' fue procesado correctamente',
            //html: '',
            showConfirmButton: false,
            timer: 3000,
            allowOutsideClick: false,
            onClose: () => {}
          })
          $('#modalretiro').modal('hide');
          consul_billetera();

        }
      }
    })
  } else {
    Swal.fire({ // position: 'top-end',
      type: 'warning',
      title: 'Complete los campos de retiro',
      //html: '',
      showConfirmButton: false,
      timer: 2000,
      allowOutsideClick: false,
      onClose: () => {

      }
    })

  }
}

//---------------------------------------------------------------//
function confirma_pago() {
  if ($('#monto_modal_pagos').val() != '') {
    var datas = JSON.parse(localStorage.clientes);
    var cliente = datas[localStorage.lectura].id;
    var total = $('#monto_modal_pagos').val().replace(".", "");
    var detalle = $('#detalle_modal_pagos').val();
    var opcion = $('#opcion_modal_pagos').val()
    var opcion_forma = $('#opcion_modal_pagos option:selected').text()
    var string = 'a=pago&u=' + localStorage.id + '&c=' + cliente + '&d=' + detalle + '&t=' + total+ '&o=' + opcion + '&camion=' + localStorage.id_camion;
    $.ajax({
      type: "POST",
      url: url_gral,
      data: string,
      crossDomain: true,
      cache: false,
      success: function(data) {
        if (data == 'TRUE') {
          Swal.fire({ // position: 'top-end',
            type: 'success',
            title: 'El pago de ' + datas[localStorage.lectura].nombre + ' ' + datas[localStorage.lectura].apellido + ' fue procesado correctamente',
            //html: '',
            showConfirmButton: false,
            timer: 2000,
            allowOutsideClick: false,
            onClose: () => {
              var cliente_wsp = datas[localStorage.lectura].telefono;
              var datos_cliente = datas[localStorage.lectura]
              console.log(datos_cliente)
              let mensaje="*RECIBÍ $"+total+"* \n Forma de pago: "+opcion_forma+" (Obs.: "+detalle+") \n";
              var fecha = new Date().toLocaleDateString('es-ES');
              let saldo =parseFloat($('#saldo_pagos').html().replace('.',''))
              var saldo_pendiente= saldo - parseFloat(total)
              mensaje+="*SALDO PENDIENTE: * $"+saldo_pendiente+"\n";
              mensaje+="FECHA: "+fecha+" \n";           
              mensaje = encodeURIComponent(mensaje.replace(/&/g, '%26').replace(/#/g, '%23'));
              let url_wsp='https://wa.me/'+ cliente_wsp +'?text='+mensaje;
              var win = window.open(url_wsp, '_blank');
              win.focus()
              $('#modalpago').modal('hide');
              localStorage.lectura = '';
              localStorage.removeItem('pedido');
              $('.cliente_select').hide();
              $('#botonera_cliente').hide();
              lnkint('welcome');
              chequeo_lectura();
            }
          })
          
        }
      }
    })
  } else {
    Swal.fire({ // position: 'top-end',
      type: 'warning',
      title: 'Complete los campos de pago',
      //html: '',
      showConfirmButton: false,
      timer: 2000,
      allowOutsideClick: false,
      onClose: () => {

      }
    })

  }
}

//------------------------------------------------------------------//

function envia_pedido_ok() {
  $("#confpago").attr("disabled", true);
  if (localStorage.pedido) {
    var total = $("#total_final").html();
    var datas = JSON.parse(localStorage.clientes);
    var detalle = "";
    var comprobante = localStorage.comprobante;
    var fp = "1";
    var ma = $('#motoabona').val();
    //Nos fijamos que no pase el tope de financiacion
    var datas = JSON.parse(localStorage.clientes);
    var clienteJson = datas[localStorage.lectura];
    var cliente = datas[localStorage.lectura].id;
    console.log(localStorage);
    var dataString = 'consul_ultimos_pagos&u=' + localStorage.id + '&c=' + cliente + '&camion=' + localStorage.id_camion;
    var saldo = 0;
    /*console.log(dataString);*/

    $.ajax({
      type: "POST",
      url: url_gral,
      data: dataString,
      dataType: 'json',
      crossDomain: true,
      cache: false,
      success: function(ultimosPagos) {
        //    if(ultimosPagos.estado=='true'){
        saldo = (ultimosPagos.saldo).replace('.', '');
        /*console.log(saldo);*/
        let resto = total - parseFloat(ma);
        let topeDisponible = parseFloat(clienteJson.financiacion.tope) - parseFloat(saldo);
        //debugger;
        if ((parseFloat(topeDisponible) - parseFloat(resto)) >= 0 || fp == '2') {
          var items_json = localStorage.pedido; //JSON.stringify(pedido);
          var string = 'a=add&u=' + localStorage.id + '&c=' + cliente + '&fp=' + fp + '&ma=' + ma + '&d=' + detalle + '&obse=' + comprobante + '&t=' + total + '&i=' + items_json + '&vd=0&camion=' + localStorage.id_camion;
          /*console.log(string);*/
          $.ajax({
            type: "POST",
            url: url_gral,
            data: string,
            crossDomain: true,
            cache: false,
            success: function(data) {
              console.log(data);
              $("#confpago").attr("disabled", false);
              if (data == 'TRUE') {
                Swal.fire({ // position: 'top-end',
                  type: 'success',
                  title: 'El pedido de ' + datas[localStorage.lectura].nombre + ' ' + datas[localStorage.lectura].apellido + ' fue enviado correctamente',
                  //html: '',
                  showConfirmButton: false,
                  timer: 3000,
                  allowOutsideClick: false,
                  onClose: () => {
                    var cliente_wsp = datas[localStorage.lectura].telefono;
                    var datos_cliente = datas[localStorage.lectura];
                    var comprobante = localStorage.comprobante;
                    /*console.log(datos_cliente);*/
                    let mensaje="*"+comprobante+"* \n Nro de cliente: "+datos_cliente.id+" ("+datos_cliente.razon+") Dirección: "+datos_cliente.direccion+" "+datos_cliente.dirnum+" \n ITEMS:\n";
                    let items = JSON.parse(localStorage.pedido)
                    items.forEach(e => {
                      mensaje+="- "+e.titulo+" ("+e.cantidad+"Un) $"+e.subtotal+"\n";

                    });
                    mensaje+="*TOTAL:* $"+total;
                    mensaje = encodeURIComponent(mensaje.replace(/&/g, '%26').replace(/#/g, '%23'));
                    let url_wsp='https://wa.me/'+ cliente_wsp +'?text='+mensaje;
                    var win = window.open(url_wsp, '_blank');
                    win.focus()
                    $('#modalenvia').modal('hide');
                    localStorage.lectura = '';
                    localStorage.removeItem('pedido');
                    $('.cliente_select').hide();
                    $('#botonera_cliente').hide();
                    lnkint('welcome');
                    chequeo_lectura();
                  }
                })
                
              } /*else {
                alert("No inserto!" + data);
              }*/
            }
          })
        } else {
          let alMenos = (parseFloat(resto) - parseFloat(topeDisponible));
          Swal.fire({
            type: 'info',
            title: 'Ya alcanzo el tope de financiación, pague al menos: ' + alMenos,
            //html: '',
            showConfirmButton: false,
            timer: 3000,
            allowOutsideClick: false,
            onClose: () => {}
          })
        }
        //  } condicion si es true
      }
    })

  } else {
    $("#confpago").attr("disabled", false);
    Swal.fire({ // position: 'top-end',
      type: 'warning',
      title: 'No tiene ningun producto seleccionado',
      //html: '',
      showConfirmButton: false,
      timer: 2000,
      allowOutsideClick: false,
      onClose: () => {

      }
    })

  }
}

//***********************************************//

//-----------------------------------------------//

function sincronizartodo() {
  var dataString = 'sincroniza&u=' + localStorage.id;

  $.ajax({
    type: "POST",
    url: url_gral,
    data: dataString,
    dataType: 'json',
    crossDomain: true,
    cache: false,
    success: function(data) {
      if (data.estado == 'true') {
        localStorage.productos = JSON.stringify(data.productos);
        localStorage.marcas = JSON.stringify(data.marcas);
        localStorage.clientes = JSON.stringify(data.clientes);
        localStorage.perfil = JSON.stringify(data.perfil);
        localStorage.fecha_sync = data.fecha;
      }
    }
  })
}



function confirma_auth() {
  var pass = md5($('#numero_pin').val());
  // chiqueo pin vacio
  //chequeo pin

  if (localStorage.candado == '') {
    alert('No hay pin configurado');
  }
  if (localStorage.candado == pass) {

    var dataString = 'confirmacarga&u=' + localStorage.id + '&c=' + $('#id_carga').val() + '&o=' + $('#observacion_pin').val();

    $.ajax({
      type: "POST",
      url: url_gral,
      data: dataString,
      crossDomain: true,
      cache: false,
      success: function(data) {
        if (data == 'true') {
          Swal.fire({ // position: 'top-end',
            type: 'success',
            title: 'Mercaderia ingresada, ya puede venderla.',
            //html: '',
            showConfirmButton: false,
            timer: 2000,
            allowOutsideClick: false,
            onClose: () => {

            }
          })
          $('#modalpin').modal('hide');
          $('.autorizacion').hide();
          window.location.href = "index.html";
        } else {
          alert('Error al confirmar');
        }
      }
    })

  } else {
    Swal.fire({ // position: 'top-end',
      type: 'warning',
      title: 'Error de pin',
      //html: '',
      showConfirmButton: false,
      timer: 2000,
      allowOutsideClick: false,
      onClose: () => {

      }
    })
  }

}


function chequeo_carga() {
  var dataString = 'haycarga&u=' + localStorage.id;

  $.ajax({
    type: "POST",
    url: url_gral,
    data: dataString,
    dataType: 'json',
    crossDomain: true,
    cache: false,
    success: function(data) {
      // console.log(data)
      if (data.estado == 'true') {
        localStorage.setItem('id_camion', data.carga[0].id_carga);
        $('.mobile-wrap').hide();

        var cantidad = data.carga.length;

        esqueleto = '<div class="forecast clearfix">' +
          '    <div class="datetime pull-left bounceInLeft animated">' +
          '      <div >' + cantidad + ' Productos</div>' +
          '      <div class="date">' + data.fecha + '</div>' +
          '    </div>' +
          '    <div class="temperature pull-right bounceInRight animated">' +
          '      <div class="unit"><span class="fa fa-hashtag"></span> <i>' + data.items + '</i></div>' +
          '      <div class="location">Total de Items</div>' +
          '    </div>' +
          '  </div>' +
          '  <hr> ' +
          '  <div class="alarm-list">';
        for (var i = 0; i < cantidad; i++) {
          esqueleto += '  <input type="hidden" value="' + data.carga[i].id_carga + '" id="id_carga">' +
            '  <div class="note clearfix slideInRight animated">' +
            '    <div class="time pull-left">' +
            '      <div class="hour">' + data.carga[i].cantidad_producto + '</div>' +
            '    </div>' +
            '    <div class="to-do pull-left">' +
            '      <div class="title">' + data.carga[i].nombre_producto + '</div>' +
            '    </div>' +
            '    <div class="to-do pull-right">' +
            '    </div>' +
            '  </div>';

        }

        esqueleto += '</div>';


        $('.lista_autorizar').html(esqueleto);

        $('.autorizacion').show();
        $('#botoneraCarga').show();

      } else {
        $('.mobile-wrap').show();
        $('.autorizacion').hide();
        $('#botoneraCarga').hide();
      }
    }
  })
}


function abrepag(pagina) {
  $('.html').removeClass('visible');
  $('.html .' + pagina).addClass('visible');

  //ejecuta function
  if (pagina == 'productos') {
    productoscate();
  }

}

function productos() {
  var dataString = 'productos&u=' + localStorage.id;
  $.ajax({
    type: "POST",
    url: url_gral,
    data: dataString,
    dataType: 'json',
    crossDomain: true,
    cache: false,
    success: function(data) {
      if (data.estado == 'true') {
        localStorage.productos = JSON.stringify(data.productos);
      } else {
        //  alert('Error de Conexion');
      }
    }
  })
}
//---------------------------------------------------------------------//
function consul_ultimos_pagos() {
  var datas = JSON.parse(localStorage.clientes);
  var cliente = datas[localStorage.lectura].id;
  var dataString = 'consul_ultimos_pagos&u=' + localStorage.id + '&c=' + cliente + '&camion=' + localStorage.id_camion;

  $.ajax({
    type: "POST",
    url: url_gral,
    data: dataString,
    dataType: 'json',
    crossDomain: true,
    cache: false,
    success: function(data) {
      if (data.estado == 'true') {


        var cantidad = data.upagos.length;

        esqueleto = '<div class="forecast clearfix">' +
          '    <div class="datetime pull-left bounceInLeft animated">' +
          '      <div >' + cantidad + ' Operaciones</div>' +
          '      <div class="date">En este mes</div>' +
          '    </div>' +
          '    <div class="temperature pull-right bounceInRight animated">' +
          '      <div class="unit"><span class="fa fa-money"></span> $<i id="saldo_pagos">' + data.saldo + '</i></div>' +
          '      <div class="location">Saldo Actual</div>' +
          '    </div>' +
          '  </div>' +
          '  <hr>' +
          '  <div class="alarm-list">';
        for (var i = 0; i < cantidad; i++) {
          esqueleto += '  <div class="note clearfix slideInRight animated">' +
            '    <div class="time pull-left">' +
            '      <div class="hour">' + data.upagos[i].dia + '</div>' +
            '      <div class="shift">' + data.upagos[i].mes + '</div>' +
            '    </div>' +
            '    <div class="to-do pull-left">' +
            '      <div class="title">' + data.upagos[i].nombre + ' ' + data.upagos[i].apellido + '</div>' +
            '      <div class="subject">' + data.upagos[i].detalle + '</div>' +
            '    </div>' +
            '    <div class="to-do pull-right">' +
            '      <div class="title">$' + data.upagos[i].total + '</div>' +
            '    </div>' +
            '  </div>';

        }

        esqueleto += '</div>' +
          '<hr>' +
          '<div class="action center flipInY animated">' +
          '  <button class="btn btn-danger"  onclick="lnkint(\'pedido\')" style="background-color:#2196f3;"><i class="ion-ios-arrow-back"></i> Regresar</button>' +
          '  &nbsp;&nbsp;&nbsp;<button class="btn btn-danger"  onclick="modal_pago(this)" style="background-color:#0074d0;"><i class="fa fa-money"></i> Registrar Pago</button>' +
          '</div>';


        $('.pagos').html(esqueleto);




      } else if (data.estado == 'false') {
        esqueleto = '<div class="forecast clearfix">' +
          '    <div class="datetime pull-left bounceInLeft animated">' +
          '      <div >Sin Operaciones</div>' +
          '      <div class="date">En este mes</div>' +
          '    </div>' +
          '    <div class="temperature pull-right bounceInRight animated">' +
          '      <div class="unit"><span class="fa fa-money"></span> $<i id="saldo_pagos">' + data.saldo + '</i></div>' +
          '        <div class="location">Saldo Actual</div>' +
          '      </div>' +
          '  </div>' +
          '  <hr>' +
          '  <div class="alarm-list">' +
          '    <div class="note clearfix slideInRight animated">' +
          '      <div class="title" style="text-align: center;">Este mes NO se registraron pagos.</div>' +
          '    </div>' +
          '  </div>' +
          '<hr>' +
          '<div class="action center flipInY animated">' +
          '  <button class="btn btn-danger"  onclick="lnkint(\'pedidos\')" style="background-color:#2196f3;"><i class="ion-ios-arrow-back"></i> Regresar</button>' +
          '  &nbsp;&nbsp;&nbsp;<button class="btn btn-danger"  onclick="modal_pago(this)" style="background-color:#0074d0;"><i class="fa fa-money"></i> Registrar Pago</button>' +
          '</div>';


        $('.pagos').html(esqueleto);


      } else {
        //  alert('Error de Conexion');
      }
    }
  })
}
//---------------------------------------------------------------------//
function consul_mov_diario() {

  var dataString = 'consul_mov_diario&u=' + localStorage.id;

  $.ajax({
    type: "POST",
    url: url_gral,
    data: dataString,
    dataType: 'json',
    crossDomain: true,
    cache: false,
    success: function(data) {
      if (data.estado == 'true') {


        var cantidad = data.mov_diario.length;

        esqueleto = '<div class="forecast clearfix">' +
          '    <div class="datetime pull-left bounceInLeft animated">' +
          '      <div >' + data.fecha + '</div>' +
          '      <div class="date">' + cantidad + ' Operaciones</div>' +
          '    </div>' +
          '    <div class="temperature pull-right bounceInRight animated">' +
          '      <div class="unit"><span class="fa fa-money"></span> $<i id="saldo_pagos">' + data.saldo + '</i></div>' +
          '      <div class="location">Venta Diaria</div>' +
          '    </div>' +
          '  </div>' +
          '  <hr>' +
          '  <div class="alarm-list">';
        for (var i = 0; i < cantidad; i++) {
          esqueleto += '  <div class="note clearfix slideInRight animated">' +
            '    <div class="time pull-left" style="margin-right: 15px;">' ;
            if (data.mov_diario[i].tipo == 'pedido') {
              esqueleto += '<div class="hour" style="font-size: smaller;"><span class="fa fa-cart-arrow-down"></span> #' + data.mov_diario[i].id_transaccion + '</div>';
            } else {
              esqueleto += '<div class="hour" style="font-size: smaller;"><span class="fa fa-money"></span> #' + data.mov_diario[i].id_transaccion + '</div>';
            }
          esqueleto +=   '      <div class="shift">' + data.mov_diario[i].hora + '</div>' +
            '    </div>' +
            '    <div class="to-do pull-left" style="display: contents;">'+
            '     <div class="title">' + data.mov_diario[i].razon + '<div class="pull-right" ></div></div>' +
            '      <div class="subject" style="display: flex;">' + data.mov_diario[i].detalle + '</div>' +
            '    </div>' +
            '    <div class="to-do pull-right">';
          if (data.mov_diario[i].tipo == 'pedido') {
            esqueleto += '      <div class="title">$' + data.mov_diario[i].total + '</div>';
          } else {
            esqueleto += '      <div class="title">-$' + data.mov_diario[i].total2 + '</div>';
          }

          esqueleto += '    </div>' +
            '  </div>';

        }

        esqueleto += '</div>' +
          '<hr>' +
          '<div class="action center flipInY animated">' +
          '  <button class="btn btn-danger"  onclick="lnkint(\'welcome\')" style="background-color:#2196f3;"><i class="ion-ios-arrow-back"></i> Regresar</button>' +
          '</div>';


        $('.movimientos').html(esqueleto);




      } else if (data.estado == 'false') {
        esqueleto = '<div class="forecast clearfix">' +
          '    <div class="datetime pull-left bounceInLeft animated">' +
          '      <div >Sin Operaciones</div>' +
          '      <div class="date">En este día</div>' +
          '    </div>' +
          '    <div class="temperature pull-right bounceInRight animated">' +
          '      <div class="unit"><span class="fa fa-money"></span> $<i id="saldo_pagos">' + data.saldo + '</i></div>' +
          '        <div class="location">Saldo Actual</div>' +
          '      </div>' +
          '  </div>' +
          '  <hr>' +
          '  <div class="alarm-list">' +
          '    <div class="note clearfix slideInRight animated">' +
          '      <div class="title" style="text-align: center;">Este dia NO se registraron movimientos.</div>' +
          '    </div>'; +
        '  </div>' +
        '<hr>' +
        '<div class="action center flipInY animated">' +
        '  <button class="btn btn-danger"  onclick="lnkint(\'welcome\')" style="background-color:#2196f3;"><i class="ion-ios-arrow-back"></i> Regresar</button>' +
        '</div>';


        $('.movimientos').html(esqueleto);


      } else { //alert('Error de Conexion');
      }
    }
  })
}


//---------------------------------------------------------------------//
function del_mov(transaccion){
  Swal.fire({
    title: 'Esta Seguro?',
    text: "Quieres eliminar este movimiento?",
    //icon: 'warning',
    type: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Eliminar!'
  }).then((result) => {

    if (result.value) {
        del_oficial_mov(transaccion);
      }
    })
}

//---------------------------------------------------------------------//

function del_oficial_mov(transaccion){
  var dataString = 'elimino_mov&u=' + localStorage.id + '&t=' + transaccion;

  $.ajax({
    type: "POST",
    url: url_gral,
    data: dataString,
  //  dataType: 'json',
    crossDomain: true,
    cache: false,
    success: function(data) {
      if (data == 'TRUE') {
        Swal.fire({ // position: 'top-end',
          type: 'success',
          title: 'El movimiento se eliminino correctamente',
          //html: '',
          showConfirmButton: false,
          timer: 2000,
          allowOutsideClick: false,
          onClose: () => {
            consul_mov_diario();
          }
        })

      }else{
        Swal.fire({ // position: 'top-end',
          type: 'warning',
          title: 'No se pudo eliminar el movimiento',
          //html: '',
          showConfirmButton: false,
          timer: 2000,
          allowOutsideClick: false,
          onClose: () => {}
        })
      }
    }
  })
}

//---------------------------------------------------------------------//

function consul_cc_clientes() {
  var datas = JSON.parse(localStorage.clientes);
  var cliente = datas[localStorage.lectura].id;
  console.log(cliente)
  var dataString = 'consul_cc_clientes&u=' + localStorage.id + '&c=' + cliente;

  $.ajax({
    type: "POST",
    url: url_gral,
    data: dataString,
    dataType: 'json',
    crossDomain: true,
    cache: false,
    success: function(data) {
      if (data.estado == 'true') {


        var cantidad = data.cc_cliente.length;

        esqueleto = '<div class="forecast clearfix">' +
          '    <div class="datetime pull-left bounceInLeft animated">' +
          '      <div >' + cantidad + ' Operaciones</div>' +
          '      <div class="date">En este mes</div>' +
          '    </div>' +
          '    <div class="temperature pull-right bounceInRight animated">' +
          '      <div class="unit"><span class="fa fa-money"></span> $<i id="saldo_pagos">' + data.saldo + '</i></div>' +
          '      <div class="location">Saldo Actual</div>' +
          '    </div>' +
          '  </div>' +
          '  <hr>' +
          '  <div class="alarm-list">';
        for (var i = 0; i < cantidad; i++) {
          esqueleto += '  <div class="note clearfix slideInRight animated">' +
            '    <div class="time pull-left">' +
            '      <div class="hour">' + data.cc_cliente[i].dia + '</div>' +
            '      <div class="shift">' + data.cc_cliente[i].mes + '</div>' +
            '    </div>' +
            '    <div class="to-do pull-left">';
          if (data.cc_cliente[i].tipo == 'pedido') {
            esqueleto += '<div class="title">Pedido #' + data.cc_cliente[i].id_transaccion + '</div>';
          } else {
            esqueleto += '<div class="title">Pago #' + data.cc_cliente[i].id_transaccion + '</div>';
          }
          esqueleto += '      <div class="subject">' + data.cc_cliente[i].detalle + '</div>' +
            '    </div>' +
            '    <div class="to-do pull-right">';
          if (data.cc_cliente[i].tipo == 'pedido') {
            esqueleto += '      <div class="title">$' + data.cc_cliente[i].total + '</div>';
          } else {
            esqueleto += '      <div class="title">-$' + data.cc_cliente[i].total2 + '</div>';
          }

          esqueleto += '    </div>' +
            '  </div>';

        }

        esqueleto += '</div>' +
          '<hr>' +
          '<div class="action center flipInY animated">' +
          '  <button class="btn btn-danger"  onclick="lnkint(\'pedido\')" style="background-color:#2196f3;"><i class="ion-ios-arrow-back"></i> Regresar</button>';
        console.log('Telefono: ' + data.cc_cliente[0].telefono);
        if (data.cc_cliente[0].telefono != '') {
          var texto = data.cc_cliente[0].nombre + ' ' + data.cc_cliente[0].apellido + ', Nos contactamos con usted, para facilitarle el resumen de su Cuenta Corriente de Big Pollo %0A%0APodra ingresar al mismo haciendo click en este enlace. http://www.prompt.com.ar/bigpollo/%0AAtte.%0ABig Pollo.%0A%0A';
          esqueleto += '&nbsp;&nbsp;&nbsp;<button class="btn btn-danger"  onclick="envia_por_wp(\'' + data.cc_cliente[0].telefono + '\',\'' + texto + '\')" style="background-color:#2196f3;"><i class="fa fa-whatsapp"></i> Enviar por WP</button>';
        }
        //+'  &nbsp;&nbsp;&nbsp;<button class="btn btn-danger"  onclick="envia_por_wp('+data.cc_cliente[0].telefono+',\', Nos contactamos con usted, para facilitarle el resumen de su Cuenta Corriente de Big Pollo %0A%0A\''+$('.cc_cliente').html().remove('button')+\''%0AAtte.%0ABig Pollo.%0A%0A\')" style="background-color:#2196f3;"><i class="fa fa-whatsapp"></i> Enviar por WP</button>'
        esqueleto += '</div>';


        $('.cc_cliente').html(esqueleto);




      } else if (data.estado == 'false') {
        esqueleto = '<div class="forecast clearfix">' +
          '    <div class="datetime pull-left bounceInLeft animated">' +
          '      <div >Sin Operaciones</div>' +
          '      <div class="date">En este mes</div>' +
          '    </div>' +
          '    <div class="temperature pull-right bounceInRight animated">' +
          '      <div class="unit"><span class="fa fa-money"></span> $<i id="saldo_pagos">' + data.saldo + '</i></div>' +
          '        <div class="location">Saldo Actual</div>' +
          '      </div>' +
          '  </div>' +
          '  <hr>' +
          '  <div class="alarm-list">' +
          '    <div class="note clearfix slideInRight animated">' +
          '      <div class="title" style="text-align: center;">Este mes NO se registraron pagos.</div>' +
          '    </div>'; +
        '  </div>' +
        '<hr>' +
        '<div class="action center flipInY animated">' +
        '  <button class="btn btn-danger"  onclick="lnkint(\'pedidos\')" style="background-color:#2196f3;"><i class="ion-ios-arrow-back"></i> Regresar</button>' +
        '  &nbsp;&nbsp;&nbsp;<button class="btn btn-danger"  onclick="modal_pago(this)" style="background-color:#0074d0;"><i class="fa fa-money"></i> Registrar Pago</button>' +
        '</div>';


        $('.pagos').html(esqueleto);


      } else { //alert('Error de Conexion');
      }
    }
  })
}


//---------------------------------------------------------------------//

function envia_por_wp(tel, texto) {
  var link = 'https://api.whatsapp.com/send?phone=+54' + tel + '&text=' + texto;
  window.open(link);
}

//---------------------------------------------------------------------//
function consul_billetera() {
  var dataString = 'consul_billetera&u=' + localStorage.id;

  $.ajax({
    type: "POST",
    url: url_gral,
    data: dataString,
    dataType: 'json',
    crossDomain: true,
    cache: false,
    success: function(data) {
      if (data.estado == 'true') {

        var cantidad = data.billetera.length;

        esqueleto = '<div class="forecast clearfix">' +
          '    <div class="datetime pull-left bounceInLeft animated">' +
          '      <div >' + cantidad + ' Operaciones</div>' +
          '      <div class="date">' + data.billetera[0].fecha + '</div>' +
          '    </div>' +
          '    <div class="temperature pull-right bounceInRight animated">' +
          '      <div class="unit"><span class="fa fa-money"></span> $<i class="a_rendir">' + data.billetera[cantidad - 1].total_acumulado + '</i></div>' +
          '      <div class="location">Total a Rendir</div>' +
          '    </div>' +
          '  </div>' +
          '  <hr>' +
          '  <div class="alarm-list">';
        for (var i = 0; i < cantidad; i++) {
          esqueleto += '  <div class="note clearfix slideInRight animated">' +
            '    <div class="time pull-left">' +
            '      <div class="hour">' + data.billetera[i].hora + '</div>' +
            '      <div class="shift">Hs.</div>' +
            '    </div>' +
            '    <div class="to-do pull-left" ';
            if(data.billetera[i].tipo=='gasto'){ esqueleto += ' style="margin-left: 25px;font-weight: 800;"';}
             esqueleto += ' ><div class="title">' + data.billetera[i].razon + '</div>' +
            '      <div class="subject">' + data.billetera[i].detalle + '</div>' +
            '    </div>' +
            '    <div class="to-do pull-right">';
          if(data.billetera[i].tipo=='gasto'){esqueleto += ' <div class="title">-$' + data.billetera[i].total + '</div>';
        }else{
          esqueleto += ' <div class="title">$' + data.billetera[i].total + '</div>';
        }

          esqueleto += '    </div>' +
            '  </div>';

        }

        esqueleto += '</div>' +
          '<hr>' +
          '<div class="action center flipInY animated">' +
          '  <button class="btn btn-danger"  onclick="lnkint(\'welcome\')" style="background-color:#2196f3;"><i class="ion-ios-arrow-back"></i> Regresar</button>' ;
          if(data.billetera[cantidad - 1].total_acumulado > 0 ){ esqueleto +='  <button class="btn btn-danger"  onclick="modal_retiro()" ><i class="ion-ios-arrow-forward"> </i> Realizar Retiro</button>' ;}
        //  '  &nbsp;&nbsp;&nbsp;<button class="btn btn-danger"  onclick="rendir()" style="background-color:#2196f3;"><i class="fa fa-money"></i> Rendir</button>' +
        esqueleto +=  '</div>';


        $('.billetera').html(esqueleto);




      } else {
        //alert('Error de Conexion');
        esqueleto = '<div class="forecast clearfix">' +
          '    <div class="datetime pull-left bounceInLeft animated">' +
          '      <div >Sin Operaciones</div>' +
          '      <div class="date"></div>' +
          '    </div>' +
          '    <div class="temperature pull-right bounceInRight animated">' +
          '      <div class="unit"><span class="fa fa-money"></span> $<i>0.00</i></div>' +
          '      <div class="location">Total a Rendir</div>' +
          '    </div>' +
          '  </div>' +
          '  <hr>' +
          '  <div class="alarm-list">';


        esqueleto += '</div>' +
          '<hr>' +
          '<div class="action center flipInY animated">' +
          '  <button class="btn btn-danger"  onclick="lnkint(\'welcome\')" style="background-color:#2196f3;"><i class="ion-ios-arrow-back"></i> Regresar</button>' +
          '</div>';


        $('.billetera').html(esqueleto);
      }
    }
  })
}


function chequeo_lectura() {
  if (localStorage.lectura != '') {
    var data = JSON.parse(localStorage.clientes);
    $('.cliente_select').html('<span style="padding-left: 10px;">' + data[localStorage.lectura].razon + '</span><!--<span onclick="cierra_cliente()" class="fa fa-times-circle" style="float: right;padding-right: 10px;"></span>-->');
    $('.cliente_select').show();
    $('#botonera_cliente').show();
  }
}

/*function btn_cc_cliente(){
  console.log('entra al btn')
  if($('.cc_cliente').is(":visible")){
    console.log('muestra')
    lnkint('pedido')
  }else{
    console.log('oculto')
    lnkint('cc_cliente')
  }
}*/


function muestro_tacho(id) {
  console.log('entra');
  if ($('#botones_' + id).toggleClass("hide")) {
    $('#botones_' + id).show();
  } else {
    $('#botones_' + id).hide();
  }
  //;


}

function listado_pedidos() {
  if (localStorage.pedido != '' && localStorage.pedido != undefined) {
    var data = JSON.parse(localStorage.pedido);
    //console.log(data)
    var esqueleto = '';
    var acumula = 0;
    for (var i = 0; i < data.length; i++) {
      /**/
      acumula = parseFloat(acumula) + parseFloat(data[i].subtotal);
      if (data[i].foto != 'undefined') {
        var foto = data[i].foto;
      } else {
        var foto = 'sin-imagen.png';
      }
      esqueleto += '<div class="row">';
      esqueleto += '<div class="col-xs-12"';
      if (data[i].stock != '0' && data[i].stock != '00') {
        esqueleto += 'id="producto_' + i + '"  '; //onclick="add_producto(' + data[i].id + ')"
      }
      esqueleto += ' style="color: #0a1050; border: 1px solid #0a1050;display:flex;border-radius: 5px;margin-bottom: 10px;margin-left: 15px;margin-right: 15px;width:inherit'; //height: 40px
      if (data[i] == '0' || data[i].stock == '00') {
        esqueleto += ' opacity: 0.4;';
        data[i].stock = '00';
      }
      esqueleto += '">' +
        '<div class="col-xs-2" style="display:flex;margin-left: -15px;padding-left: 0px;">' +
        ' <div class="my-list" style="margin: auto;overflow: hidden;height: 40px;width: 40px;background-color: white;color: brown;text-align: center;border-radius: 10%;padding-top: 1px;">' +
        '   <img style="width:100%;border-radius: 25%;"  onerror="this.src=\'img/prod/sin-imagen.png\'" src="../../img/prod/' + foto + '" alt="' + data[i].detalle + '" />'
        //+'    <h4>'+data.productos[i].codigo+'</h4>'
        +
        ' </div>' +
        '</div>' +
        '<div class="col-xs-8" style="padding-top: 1%;font-size: small;margin-left: -20px;width: 100%;">' +
        data[i].titulo + ' (' + data[i].detalle + ') X ' + data[i].cantidad + ' U.' +
        '</div>' +
        '<div class="col-xs-1" style="display:flex;">' //top: 10px;
        +
        '<span class="" style="float: right;margin-right: -60px; text-align: right;margin-top: 23px;font-size: small;">$' + data[i].subtotal + '</span>' +
        '</div>' +
        '<div class="col-xs-1" style="display:flex;top: -20px;"  onclick="eliminarProductoEn(' + i + ')"><i class="fa fa-trash" style="float: right;margin-right: -60px; text-align: right;margin-top: 23px"></i></div>' +
        '</div>'


      esqueleto += '</div>';
    }
    esqueleto += ' <div class="col-xs-12" style="color: #0a1050; border: 1px solid #0a1050; border-radius: 5px;margin-bottom: 10px;font-size: smaller;font-weight: 600;text-align: center;">' +
      '   Total: $<span id="total_final">' + acumula + '</span>' +
      ' </div>'

    $('#listado_pedidos').html(esqueleto);
    /*
    for (var i = 0; i < data.length; i++) {
      $("#producto_" + i).swipe({
        //Single swipe handler for left swipes
        swipeLeft: function(event, direction, distance, duration, fingerCount) {
          let id = this[0].id;
          console.log(id);
          let indice = id.slice(9, 99);
          eliminarProductoEn(indice);
        },
        //Default is 75px, set to 0 for demo so any distance triggers swipe
        threshold: 0
      });

    }
    */
    $('#nohayprod').hide();

    $('.btn-confirma').show();

  } else {
    $('.btn-confirma').hide();
    $('#listado_pedidos').html('');
    $('#nohayprod').show();
  }
}

function eliminarProductoEn(indice) {
  var data = JSON.parse(localStorage.pedido);
  var subtotalEliminado = parseFloat(data[indice].subtotal);
  localStorage.pedidoValor -= subtotalEliminado;
  data.splice(indice, 1);
  localStorage.pedido = JSON.stringify(data);

  $('#total').html(localStorage.pedidoValor);


  // Restar el subtotal eliminado del totalPedido
  /**/

  /*console.log(localStorage.pedidoValor);*/

  Swal.fire({
    type: 'success',
    title: 'Se eliminó el producto del carrito',
    showConfirmButton: false,
    timer: 1500,
    allowOutsideClick: false,
    onClose: () => {
      lnkint('pedido');
    }
  });
}


function productoscate() {
  //var dataString = 'categorias&u=' + localStorage.id;
  var dataString = 'productos&u=' + localStorage.id;
  var cliente = JSON.parse(localStorage.clientes)[localStorage.lectura];
  console.log('Entra a mostrar productos cate')
  $.ajax({
    type: "POST",
    url: url_gral,
    data: dataString,
    dataType: 'json',
    crossDomain: true,
    cache: false,
    success: function(data) {
      if (data.estado == 'true') {
        var cantidad = data.productos.length;
        esqueleto = '<audio id="miSonido" src="../img/notification-sound-7062.mp3" preload="auto"></audio><div class="container">' +
        //  '<div class="row" style="padding-bottom: 10px;"><input type="search" data-ide="' + id + '" id="search_prod_cat" placeholder="Buscar Producto..." style="width:80%" > <button onclick="filtra_prod()" class="btn btn-success" style="border-radius:0px;padding: 3px 15px 3px 15px">Ok</button></div>'; +
        '<div class="row">';

        for (var i = 0; i < cantidad; i++) {
          if (data.productos[i].foto != 'undefined') {
            var foto = data.productos[i].foto;
          } else {
            var foto = 'sin-imagen.png';
          }
          esqueleto += '<div class="col-xs-6 product-single" data-id="'+data.productos[i].id+'"';
          if (data.productos[i].stock != '0' && data.productos[i].stock != '00') {
          }
          esqueleto += ' style="display:flex;border-radius: 5px;margin-bottom: 10px;'; //height: 40px
          if (data.productos[i].stock == '0' || data.productos[i].stock == '00') {
            esqueleto += ' opacity: 0.4;';
            data.productos[i].stock = '00';
          }
          let stock = parseFloat(data.productos[i].stock) - parseFloat(cantEnPedido(data.productos[i].id));
          if (stock < 10) {
            stock = '0' + stock;
          }
          var precio = data.productos[i].precio1;
          if(cliente.lista_precio==2) precio = data.productos[i].precio2;
          if(cliente.lista_precio==3) precio = data.productos[i].precio3;
          if(precio=='') precio='-'
          esqueleto += '">' +
            //'<div class="col-xs-6" style="margin-left:-15px;padding-left: 0px;width:100%;display:block">' +
            ' <div class="thumbnail">' +
            '   <img style="width:30vw;height:25rem;border-radius: 25%;" onerror="this.src=\'img/prod/sin-imagen.png\'" src="../../img/prod/' + foto + '" alt="' + data.productos[i].detalle + '" />'
            +
            ' <div class="caption">'+
            '   <p class="nombre_producto">'+ data.productos[i].titulo + ' (' + data.productos[i].presentacion + ')'   +' $'+precio+'</p>'+
            '   <p>';
            if(localStorage.id!=2){
              esqueleto+='<span class="contador-productos" style="margin: auto;">' + stock + '</span> '
            
            }
            esqueleto+='    <span class="unidades" id="unidad-'+data.productos[i].id+'">'+cantEnPedido(data.productos[i].id)+'Un</span></p>' +
            ' </div>'+
            '</div>' +
            
            '</div>';
        }
        esqueleto += '</div><center style="padding-top: 15px;"><button class="btn" onclick="lnkint(\'pedido\')" style="background-color:#2196f3;"><i class="ion-ios-arrow-back"></i> Regresar</button></center>' +
          '  </div>' +
          '</div>';
        $('#listado_productos').html(esqueleto);

        var temporizador;
        var divs = document.querySelectorAll('.product-single');
        var miSonido = document.getElementById('miSonido');

        divs.forEach(function(div) {
          div.addEventListener('mousedown', function() {
            temporizador = setTimeout(function() {
              var id_producto = div.getAttribute('data-id');
              add_producto(id_producto);
            }, 1000); 
          });
          
          div.addEventListener('mouseup', function() {
            clearTimeout(temporizador); 
            //alert('agregar una unidad')
            var id_producto = div.getAttribute('data-id');
            confirmaaddprod2(id_producto)
          });
          
          div.addEventListener('touchstart', function() {
            temporizador = setTimeout(function() {
              var id_producto = div.getAttribute('data-id');
              add_producto(id_producto);
            }, 1000);
          });
        
          div.addEventListener('touchend', function() {
            clearTimeout(temporizador);
          });

          div.addEventListener('click', function(event) {
            event.stopPropagation();
            miSonido.play()
          });

          

        });
        
      }else{
        alert('Error: No hay productos en stock.')
      }
    },
    error:function(data){
      console.log(data.responseText)
    }
  })
  chequeo_lectura();
}

// Selecciona el elemento al que deseas cambiar la clase
var elemento = document.querySelector('.col-xs-6');

// Verifica si la pantalla es de un dispositivo móvil



//--------------------------------------------------------------------//


function productoslist(m) {
  console.log(m);
  var data = JSON.parse(localStorage.productos);
  var cantidad = data.length;
  var cliente = JSON.parse(localStorage.clientes)[localStorage.lectura];

  var esqueleto = '<div class="container">' +
    '<div class="row">';
  for (var i = 0; i < cantidad; i++) {
    var categoria = data[i].marca_id;
    var precio = data[i].precio1;
    if(cliente.lista_precio==2) precio = data[i].precio2;
    if(cliente.lista_precio==3) precio = data[i].precio3;
    if (categoria.toString().toLowerCase().indexOf(m.toString().toLowerCase()) >= 0) {
      esqueleto += '<div class="col-xs-4" style="margin-bottom: 25px;">' +
        ' <div class="my-list" onclick="abremodal(this)" data-id="' + data[i].id + '" data-img="' + data[i].foto + '" data-codigo="' + data[i].codigo + '" data-precio="' + precio + '" data-marca="' + data[i].marca_logo + '" data-detalle="' + data[i].detalle + '" style="height: 64px;background-color: white;color: brown;text-align: center;border-radius: 10%;padding-top: 1px;">' +
        '   <img style="width:100%;border-radius: 10%;" onerror="this.src=\'img/prod/sin-imagen.png\'" src="../../img/prod/' + data[i].foto + '" alt="' + data[i].codigo + '" />'
        //+'    <h4>'+data.productos[i].codigo+'</h4>'
        +
        ' </div>' +
        '</div>';
    }
  }
  esqueleto += '</div>' +
    '</div>';

  $('#listado_productos').html(esqueleto);


}

//--------------------------------------------------------------------//

function lnk(link) {
  //alert('entra a la funcion');
  window.location.href = link + ".html";
}

function lnkint(h, v) {
  //alert('entra a la funcion con :' + h);
  $('.html').removeClass("visible");
  localStorage.marcador = h;

  if (h == 'productos') {
    productos();
    $('.productos').addClass("visible");
    productoscate();
    App.title("Productos");
  }


  if (h == 'clientes') {
    $('.clientes').addClass("visible");
    if (v == '' || v == undefined) {
      v = '0';
    }
    clienteslist(v);
    App.title("Clientes");
  }
  if (h == 'agregarCliente') {
    llenaprovincia();
    llenalocalidad(1);
    llenarubro();
    if(localStorage.id==3){
      $('#displayciudad').show();
      $('#displayprovincia').show();
    }
    $('.agregarCliente').addClass("visible");

    App.title("Agregar cliente");
  }

  if (h == 'welcome') {
    $('.welcome').addClass("visible");
    App.title("HOME");
    $('#botonera_cliente').hide();
  }

  if (h == 'cc_cliente') {
    $('.cc_cliente').addClass("visible");
    consul_cc_clientes();
    App.title("Cuenta Corriente");
  }

  if (h == 'buscaprod') {
    $('.buscaprod').addClass("visible");
    App.title("Buscador de productos");
  }

  if (h == 'pagos') {
    $('.pagos').addClass("visible");
    consul_ultimos_pagos();
    App.title("Ultimo Pagos");
  }

  if (h == 'nuevo_pago') {
    $('.nuevo_pago').addClass("visible");
    App.title("Registrar Nuevo Pago");
  }

  if (h == 'pedido') {
    $('.pedido').addClass("visible");
    var data = JSON.parse(localStorage.clientes);
    $('.cliente_select').html('<span style="padding-left: 10px;">' + data[localStorage.lectura].razon +'</span><!--<span onclick="cierra_cliente()" class="fa fa-times-circle" style="float: right;padding-right: 10px;"></span>-->');
    $('.cliente_select').show();
    $('#botonera_cliente').show();
    listado_pedidos();
    App.title("Pedidos");
  }

}

//-------------------------------------------------------------------//
function filtra_prod() {
  var palabra = $('#search_prod_cat').val();
  var id = $('#search_prod_cat').data('ide');
  console.log('palabra:' + palabra + '-ID:' + id);
  productoslistss(id, palabra);
}
//-------------------------------------------------------------------//
function filtra_cliente() {
  var palabra = $('#search_clie').val();
  clienteslist(0, palabra);
}

//-------------------------------------------------------------------//
function productoslistss(id, palabra) {

  var dataString = 'productos&c=' + id + '&u=' + localStorage.id + '&b=' + palabra;

  $.ajax({
    type: "POST",
    url: url_gral,
    data: dataString,
    dataType: 'json',
    crossDomain: true,
    cache: false,
    success: function(data) {

      if (data.estado == 'true') {

        var cantidad = data.productos.length;
        esqueleto = '<div class="container">' +
          '<div class="row" style="padding-bottom: 10px;"><input type="search" data-ide="' + id + '" id="search_prod_cat" placeholder="Buscar Producto..." style="width:80%" > <button onclick="filtra_prod()" class="btn btn-success" style="border-radius:0px;padding: 3px 15px 3px 15px">Ok</button></div>'; +
        '<div class="row">';

        for (var i = 0; i < cantidad; i++) {
          if (data.productos[i].foto != 'undefined') {
            var foto = data.productos[i].foto;
          } else {
            var foto = 'sin-imagen.png';
          }
          esqueleto += '<div class="col-xs-12"';
          if (data.productos[i].stock != '0' && data.productos[i].stock != '00') {
            esqueleto += ' onclick="add_producto(' + data.productos[i].id + ')" ';
          }
          esqueleto += ' style="background-color: #0a1050;display:flex;border-radius: 5px;margin-bottom: 10px;'; //height: 40px
          if (data.productos[i].stock == '0' || data.productos[i].stock == '00') {
            esqueleto += ' opacity: 0.4;';
            data.productos[i].stock = '00';
          }
          let stock = parseFloat(data.productos[i].stock) - parseFloat(cantEnPedido(data.productos[i].id));
          if (stock < 10) {
            stock = '0' + stock;
          }
          esqueleto += '">' +
            '<div class="col-xs-2" style="display:flex;margin-left: -15px;padding-left: 0px;">' +
            ' <div class="my-list" style="margin: auto;overflow: hidden;height: 40px;width: 40px;background-color: white;color: brown;text-align: center;border-radius: 10%;padding-top: 1px;">' +
            '   <img style="width:100%;border-radius: 25%;" onerror="this.src=\'img/prod/sin-imagen.png\'" src="../../img/prod/' + foto + '" alt="' + data.productos[i].detalle + '" />'
            //+'    <h4>'+data.productos[i].codigo+'</h4>'
            +
            ' </div>' +
            '</div>' +
            '<div class="col-xs-9" style="padding-top: 1%;font-size: small;margin-left: -20px;width: 100%;">' +
            data.productos[i].titulo + ' (' + data.productos[i].presentacion + ')' +
            '</div>' +
            '<div class="col-xs-1" style="display:flex;right: 10px;">' //top: 10px;
            +
            '<span class="contador-productos" style="margin: auto;">' + stock + '</span>' +
            '</div>' +
            '</div>';

          /*

          */
        }
        esqueleto += '<center style="padding-top: 15px;"><button class="btn" onclick="lnkint(\'productos\')" style="background-color:#2196f3;"><i class="ion-ios-arrow-back"></i> Regresar</button></center>' +
          '  </div>' +
          '</div>';
        $('#listado_productos').html(esqueleto);
      } else {

        Swal.fire({ // position: 'top-end',
          type: 'warning',
          title: 'No se encontro productos en esta Categoria',
          //html: '',
          showConfirmButton: false,
          timer: 2000,
          allowOutsideClick: false,
          onClose: () => {

          }
        })
      }




    }
  })
}
//-------------------------------------------------------------------//
function productosdevolucion() {
  var dataString = 'productos_a_devolver&u=' + localStorage.id;
  $.ajax({
    type: "POST",
    url: url_gral,
    data: dataString,
    dataType: 'json',
    crossDomain: true,
    cache: false,
    success: function(data) {

      if (data.estado == 'true') {

        var cantidad = data.productos.length;
        esqueleto = '<div class="container">' +
        //'<div class="row" style="padding-bottom: 10px;"><input type="search" data-ide="' + id + '" id="search_prod_cat" placeholder="Buscar Producto..." style="width:80%" > <button onclick="filtra_prod()" class="btn btn-success" style="border-radius:0px;padding: 3px 15px 3px 15px">Ok</button></div>'; +
        '<div class="row">';

        for (var i = 0; i < cantidad; i++) {
          if (data.productos[i].foto != 'undefined') {
            var foto = data.productos[i].foto;
          } else {
            var foto = 'sin-imagen.png';
          }
          esqueleto += '<div class="col-xs-12"';
          if (data.productos[i].stock != '0' && data.productos[i].stock != '00') {
            esqueleto += ' onclick="add_producto(' + data.productos[i].id + ')" ';
          }
          esqueleto += ' style="border:1px solid;display:flex;border-radius: 5px;margin-bottom: 10px;'; //height: 40px
          if (data.productos[i].stock == '0' || data.productos[i].stock == '00') {
            esqueleto += ' opacity: 0.4;';
            data.productos[i].stock = '00';
          }
          let stock = parseFloat(data.productos[i].stock) - parseFloat(cantEnPedido(data.productos[i].id));
          if (stock < 10) {
            stock = '0' + stock;
          }
          esqueleto += '">' +
            '<div class="col-xs-2" style="display:flex;margin-left: -15px;padding-left: 0px;">' +
            ' <div class="my-list" style="margin: auto;overflow: hidden;height: 40px;width: 40px;background-color: white;color: brown;text-align: center;border-radius: 10%;padding-top: 1px;">' +
            '   <img style="width:100%;border-radius: 25%;" onerror="this.src=\'img/prod/sin-imagen.png\'" src="../../img/prod/' + foto + '" alt="' + data.productos[i].detalle + '" />'
            //+'    <h4>'+data.productos[i].codigo+'</h4>'
            +
            ' </div>' +
            '</div>' +
            '<div class="col-xs-9" style="padding-top: 1%;font-size: small;margin-left: -20px;width: 100%;">' +
            data.productos[i].titulo + ' (' + data.productos[i].presentacion + ')' +
            '</div>' +
            '<div class="col-xs-1" style="display:flex;right: 10px;">' //top: 10px;
            +
            '<span class="contador-productos" style="margin: auto;">' + stock + '</span>' +
            '</div>' +
            '</div>';

          /*

          */
        }
        esqueleto += '<center style="padding-top: 15px;"><button class="btn" onclick="lnkint(\'welcome\')" style="background-color:#2196f3;"><i class="ion-ios-arrow-back"></i> Regresar</button></center>' +
          '  </div>' +
          '</div>';
        $('#listado_devolucion').html(esqueleto);
      } else {
          $('#listado_devolucion').html('');
        Swal.fire({ // position: 'top-end',
          type: 'warning',
          title: 'No se encontraron productos para devolucion',
          //html: '',
          showConfirmButton: false,
          timer: 2000,
          allowOutsideClick: false,
          onClose: () => {

          }
        })
      }




    }
  })
}

//-------------------------------------------------------------------//
function buscaprod() {
  var palabra = $('#search_prod').val();
  var dataString = 'productos&u=' + localStorage.id + '&b=' + palabra;

  $.ajax({
    type: "POST",
    url: url_gral,
    data: dataString,
    dataType: 'json',
    crossDomain: true,
    cache: false,
    success: function(data) {

      if (data.estado == 'true') {

        var cantidad = data.productos.length;
        esqueleto = '<div class="container">' +
          '<div class="row" style="padding-bottom: 10px;"><input type="search" data-ide="1" id="search_prod" placeholder="Buscar Producto..." style="width:80%"> <button onclick="buscaprod()" class="btn btn-success" style="border-radius:0px;padding: 3px 15px 3px 15px">Ok</button></div>' +
          '<div class="row">';

        for (var i = 0; i < cantidad; i++) {
          if (data.productos[i].foto != 'undefined') {
            var foto = data.productos[i].foto;
          } else {
            var foto = 'sin-imagen.png';
          }
          esqueleto += '<div class="col-xs-12"';
          if (data.productos[i].stock != '0' && data.productos[i].stock != '00') {
            esqueleto += ' onclick="add_producto(' + data.productos[i].id + ')" ';
          }
          esqueleto += ' style="background-color: #0a1050;display:flex;border-radius: 5px;margin-bottom: 10px;'; //height: 40px
          if (data.productos[i].stock == '0' || data.productos[i].stock == '00') {
            esqueleto += ' opacity: 0.4;';
            data.productos[i].stock = '00';
          }
          esqueleto += '">' +
            '<div class="col-xs-2" style="display:flex;margin-left: -15px;padding-left: 0px;">' +
            ' <div class="my-list" style="margin: auto;overflow: hidden;height: 40px;width: 40px;background-color: white;color: brown;text-align: center;border-radius: 10%;padding-top: 1px;">' +
            '   <img style="width:100%;border-radius: 25%;" onerror="this.src=\'img/prod/sin-imagen.png\'" src="../../img/prod/' + foto + '" alt="' + data.productos[i].detalle + '" />'
            //+'    <h4>'+data.productos[i].codigo+'</h4>'
            +
            ' </div>' +
            '</div>' +
            '<div class="col-xs-9" style="padding-top: 1%;font-size: small;margin-left: -20px;width: 100%;">' +
            data.productos[i].titulo + ' (' + data.productos[i].presentacion + ')' +
            '</div>' +
            '<div class="col-xs-1" style="display:flex;right: 10px;">' //top: 10px;
            +
            '<span class="contador-productos" style="margin: auto;">' + data.productos[i].stock + '</span>' +
            '</div>' +
            '</div>';

          /*

          */
        }
        esqueleto += '<center style="padding-top: 15px;"><button class="btn" onclick="lnkint(\'productos\')" style="background-color:#2196f3;"><i class="ion-ios-arrow-back"></i> Regresar</button></center>' +
          '  </div>' +
          '</div>';
        $('#listado_buscaprod').html(esqueleto);
        lnkint('buscaprod');
      } else {

        Swal.fire({ // position: 'top-end',
          type: 'warning',
          title: 'No se encontraron productos',
          //html: '',
          showConfirmButton: false,
          timer: 2000,
          allowOutsideClick: false,
          onClose: () => {

          }
        })
      }




    }
  })
}

//-----------------------------------------------------------------//
function clienteslist(prod, busca) {
  console.log('Entra con producto: ' + prod);
  var dataString = 'clientes&u=' + localStorage.id + '&b=' + busca;
  
  $.ajax({
    type: "POST",
    url: url_gral,
    data: dataString,
    dataType: 'json',
    crossDomain: true,
    cache: false,
    success: function(data) {
      if (data.estado == 'true') {
        localStorage.clientes = JSON.stringify(data.clientes);
        var esqueleto = '';
        var saldo=0;

        for (var i = 0; i < data.clientes.length; i++) {
          (function(index) { // Utilizamos una función de cierre (closure)
            var row = data.clientes[index];
            $.ajax({
              type: "POST",
              url: url_gral,
              data: 'consul_cc_clientes&u=' + localStorage.id + '&c=' + row.id,
              dataType: 'json',
              crossDomain: true,
              cache: false,
              success: function(data2) {
                saldo = data2.saldo;
                esqueleto += '<a href="javascript:void(0)" class="fila" onclick="lectura(' + index + ',\'' + saldo + '\');';
                if (prod != '0') {
                  esqueleto += ' add_producto(' + prod + '); ';
                }
                esqueleto += '">' +
                  '    <div  class="media  heading flipInY animated">' +
                  '       <div class="price" style="height: 100%;">';
                if(index < 10) {esqueleto += '0' + index;} else {esqueleto += index;}
                esqueleto += ' </div>' +
                  '    <div class="media-body pl-3" style="padding-top: 0px;">' +
                  '       <div class="address" style="margin-top: 15px;">' + row.rubro + ' <br/>' + row.razon + '<br/>';
                if(index != 0) {esqueleto += '<span style="font-size: 12px">Dir: ' + row.direccion + ' ' + row.dirnum + '</span>';}
                esqueleto += '</div> </div>' +
                  '   <div class="saldo">SALDO: $ ' + saldo + ' - TOPE: $ '+ row.financiacion.tope +'</div>' +
                  '   </div>' +
                  '</a>';
                $('#listado_clientes').html(esqueleto);
                ordenar_clientes();
              }
            });
          })(i); // Llamamos a la función de cierre con el valor actual de i
        }
      } else {
        alert('error al recuperar');
      }
    },
    error:function(data){
      console.log(data.responseText)
    }
  });
}
function ordenar_clientes (){
  const listadoClientes = document.getElementById('listado_clientes');
  const filasClientes = listadoClientes.querySelectorAll('.fila');
  const arrayFilas = Array.from(filasClientes);
  arrayFilas.sort((a, b) => {
    const numeroA = parseInt(a.querySelector('.price').textContent);
    const numeroB = parseInt(b.querySelector('.price').textContent);
    return numeroA - numeroB;
  });
  while (listadoClientes.firstChild) {
    listadoClientes.removeChild(listadoClientes.firstChild);
  }
  arrayFilas.forEach(fila => {
    listadoClientes.appendChild(fila);
  });
}


function consulta(data, tipo) {
  if (mydb) {
    if (tipo == 'clientes') {
      mydb.transaction(function(t) {

        for (var i = '0'; i < data.clientes.length; i++) {
          var apellido = data.clientes[i].apellido;
          var cumple = data.clientes[i].cumple;
          var direccion = data.clientes[i].direccion;
          var direccion_com = data.clientes[i].direccion_com;
          var dirnum = data.clientes[i].dirnum;
          var dirnum_com = data.clientes[i].dirnum_com;
          var dni = data.clientes[i].dni;
          var email = data.clientes[i].email;
          var foto = data.clientes[i].foto;
          var filial = data.clientes[i].filial;
          var cliente = data.clientes[i].id;
          var id_rubro = data.clientes[i].id_rubro;
          var latitud = data.clientes[i].latitud;
          var longitud = data.clientes[i].longitud;
          var nombre = data.clientes[i].nombre;
          var razon = data.clientes[i].razon;
          var rubro = data.clientes[i].rubro;
          var situacion = data.clientes[i].situacion;
          var telefono = data.clientes[i].telefono;
          var ruta = 0;
          var posicion = i + 1;

          t.executeSql("INSERT INTO clientes (apellido, cumple, direccion, direccion_com, dirnum, dirnum_com, dni, email, foto, longitud, cliente, id_rubro, latitud, nombre, razon, rubro, situacion, telefono) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)", [apellido, cumple, direccion, direccion_com, dirnum, dirnum_com, dni, email, foto, longitud, cliente, id_rubro, latitud, nombre, razon, rubro, situacion, telefono]);
          //t.executeSql("INSERT INTO rutas (direccion_com, cliente, dirnum_com, posicion, sucursal, ruta) VALUES (?, ?, ?, ?, ?, ?)", [direccion_com, cliente, dirnum_com, posicion, filial, ruta]);
        }
      }, function(err) {
        console.log('error al insertar clientes: ' + err.message);
      });
    }




    if (tipo == 'creditos') {
      mydb.transaction(function(t) {
        console.log('entra al la transaccion de add_creditos');
        console.log(t);
        for (var i = '0'; i < data.creditos.length; i++) {

          var atrasadas = data.creditos[i].atrasadas;
          var cliente = data.creditos[i].cliente;
          var cliente2 = data.creditos[i].cliente2;
          var cobrador = data.creditos[i].cobrador;
          var cobrador2 = data.creditos[i].cobrador2;
          var codigo = data.creditos[i].codigo;
          var crc = data.creditos[i].crc;
          var credito = data.creditos[i].credito;
          var estado = data.creditos[i].estado;
          var pagas = data.creditos[i].pagas;
          var totcuotas = data.creditos[i].totcuotas;
          var upago = data.creditos[i].upago;
          var valor = data.creditos[i].valor;
          console.log('codigo: ' + codigo);
          t.executeSql("INSERT INTO creditos (atrasadas, cliente, cliente2, cobrador, cobrador2, codigo, crc, credito, estado, pagas, totcuotas, upago, valor_c) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)", [atrasadas, cliente, cliente2, cobrador, cobrador2, codigo, crc, credito, estado, pagas, totcuotas, upago, valor]);

        }
      }, function(err) {
        console.log('error insertar creditos: ' + err.message);
      });
    }


    if (tipo == 'rutas') {
      mydb.transaction(function(t) {
        console.log('entra al la transaccion de Rutas');
        console.log(t);
        for (var i = '0'; i < data.rutas.length; i++) {

          var apellido = data.rutas[i].apellido;
          var calle = data.rutas[i].calle;
          var cliente = data.rutas[i].cliente;
          var cobrador = data.rutas[i].cobrador;
          var crc = data.rutas[i].crc;
          var crc2 = data.rutas[i].crc2;
          var cuando = data.rutas[i].cuando;
          var id_ruta = data.rutas[i].id_ruta;
          var nombre = data.rutas[i].nombre;
          var nombre_ruta = data.rutas[i].nombre_ruta;
          var numcalle = data.rutas[i].numcalle;
          var posicion = data.rutas[i].posicion;
          var razon = data.rutas[i].razon;
          var rubro = data.rutas[i].rubro;
          var ruta = data.rutas[i].ruta;
          var sucursal = data.rutas[i].sucursal;


          t.executeSql("INSERT INTO rutas (apellido, calle, cliente, cobrador, crc, crc2, cuando, id_ruta, nombre, nombre_ruta, numcalle, posicion, razon, rubro, ruta, sucursal) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)", [apellido, calle, cliente, cobrador, crc, crc2, cuando, id_ruta, nombre, nombre_ruta, numcalle, posicion, razon, rubro, ruta, sucursal]);

        }
      }, function(err) {
        console.log('error al insertar rutas: ' + err.message);
      });
    }


    if (tipo == 'akn') {
      if (data.akn) {
        mydb.transaction(function(t) {
          for (var i = '0'; i < data.akn.length; i++) {
            var akn = data.akn[i].akn;
            var cobrador = data.akn[i].cobrador;
            console.log('akn: ' + akn + ' cobrador: ' + cobrador);
            t.executeSql("DELETE FROM pagos WHERE akn = " + akn + " and cobrador = " + cobrador + " and estado='0' ", []);
          }
        }, function(err) {
          console.log('error al updatear akn: ' + err.message);
        });
      }
    }


    if (tipo == 'perfil') {

    }

    //console.log(db)
    //db.close();
  } else {
    alert("db not found, your browser does not support web sql!");
  }
}


function loadrutas() {
  //check to ensure the mydb object has been created
  if (mydb) {
    //Get all the cars from the database with a select statement, set outputCarList as the callback function for the executeSql command
    mydb.transaction(function(t) {
      t.executeSql("SELECT * FROM rutas where ruta !='0' group by cliente", [], muestrarutas);
    }, function(err) {
      console.log('error al leer muestrarutas: ' + err.message);
    });
  } else {
    alert("db not found, your browser does not support web sql!");
  }
}

function loadclientes() {
  //check to ensure the mydb object has been created
  if (mydb) {
    //Get all the cars from the database with a select statement, set outputCarList as the callback function for the executeSql command
    mydb.transaction(function(t) {
      t.executeSql("SELECT * FROM rutas where ruta !='0' group by cliente", [], muestraclientes);
    }, function(err) {
      console.log('error al leer muestrarutas: ' + err.message);
    });
  } else {
    alert("db not found, your browser does not support web sql!");
  }
}


//--------------------Funciones de Pedidos----------------------//

function delitem(id) {
  Swal.fire({
    title: 'Esta Seguro?',
    text: "Quieres quitar este item?",
    //icon: 'warning',
    type: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Quitar!'
  }).then((result) => {

    if (result.value) {
      var data = JSON.parse(localStorage.pedido);
      data.splice(id, 1);

      if (localStorage.pedido = JSON.stringify(data)) {
        if (data.length == '0') {
          localStorage.removeItem('pedido');
        }
        listado_pedidos();
        Swal.fire({ // position: 'top-end',
          type: 'success',
          title: 'El producto se elimino del pedido correctamente',
          //html: '',
          showConfirmButton: false,
          timer: 2000,
          allowOutsideClick: false,
          onClose: () => {

          }
        })
      }

    }
  })

}

//------------------FIN Funciones de Pedidos------------------//

function cuentacliente(callback) {
  mydb.transaction(function(t) {
    t.executeSql('SELECT COUNT(ruta) as cantidad FROM rutas where ruta="0"', [], function(tx, rs) {
      console.log('Cantidad final: ' + rs.rows[0].cantidad);

      callback(rs.rows[0].cantidad);
      //  callback(ruta,rs.rows[0].cantidad);
    });
  }, function(err) {
    console.log('error al leer muestrarutas: ' + err.message);
  });


}

/* async function muestracuentacliente() {
  var responde = await cuentacliente();
  var muestro = await muestrarutas();
  console.log(responde)
  return muestro
}
*/

function muestraclientes(transaction, results) {

  cuentacliente(function(result) {
    localStorage.cantidadcli = result;

    //  console.log('Carga listado rutas');
    //    console.log(elements)
    var outerHTML = '';
    var i;
    var cantidadcli = localStorage.cantidadcli; //'0';//
    //  console.log(results)

    console.log('devuelve muestraclientes: ' + cantidadcli);
    outerHTML += '<a href="javascript:void(0)" onclick="load_clientes_order(\'defecto\')"><div class="media heading flipInY animated">' +
      '     <div class="price">' + cantidadcli + '<small>Clientes</small></div>' +
      '     <div class="media-body pl-3">' +
      '        <div class="address">Por Defecto</div>' +
      '     </div>' +
      '  </div>' +
      '</a>';
    for (i = 0; i < results.rows.length; i++) {
      //Get the current row
      var row = results.rows.item(i);

      var cantidadcli2 = cant_cli_rut(row.id_ruta);
      console.log('devuelve2: ' + cant_cli_rut(row.id_ruta))
      outerHTML += '<a href="javascript:void(0)" onclick="load_clientes_order(' + row.id_ruta + ')"><div  class="media  heading flipInY animated">' +
        '     <div class="price">' + cantidadcli2 + '<small>';
      if (cantidadcli2 == '1') {
        outerHTML += 'Cliente';
      } else {
        outerHTML += 'Clientes';
      }

      outerHTML += '</small></div> ' +
        '     <div class="media-body pl-3"> ' +
        '        <div class="address">' + row.nombre_ruta + '</div> ' +
        '     </div> ' +
        '  </div> ' +
        '</a>';
      console.log(outerHTML);

    };

    $('#listado_rutas').html(outerHTML);
  });

}




function muestrarutas(transaction, results) {

  cuentacliente(function(result) {
    localStorage.cantidadcli = result;

    //  console.log('Carga listado rutas');
    //    console.log(elements)
    var outerHTML = '';
    var i;
    var cantidadcli = localStorage.cantidadcli; //'0';//
    //  console.log(results)

    console.log('devuelve: ' + cantidadcli);
    outerHTML += '<a href="javascript:void(0)" onclick="load_clientes_order(\'defecto\')"><div class="media heading flipInY animated">' +
      '     <div class="price">' + cantidadcli + '<small>Clientes</small></div>' +
      '     <div class="media-body pl-3">' +
      '        <div class="address">Por Defecto</div>' +
      '     </div>' +
      '  </div>' +
      '</a>';
    for (i = 0; i < results.rows.length; i++) {
      //Get the current row
      var row = results.rows.item(i);

      var cantidadcli2 = cant_cli_rut(row.id_ruta);
      console.log('devuelve2: ' + cant_cli_rut(row.id_ruta))
      outerHTML += '<a href="javascript:void(0)" onclick="load_clientes_order(' + row.id_ruta + ')"><div  class="media  heading flipInY animated">' +
        '     <div class="price">' + cantidadcli2 + '<small>';
      if (cantidadcli2 == '1') {
        outerHTML += 'Cliente';
      } else {
        outerHTML += 'Clientes';
      }

      outerHTML += '</small></div> ' +
        '     <div class="media-body pl-3"> ' +
        '        <div class="address">' + row.nombre_ruta + '</div> ' +
        '     </div> ' +
        '  </div> ' +
        '</a>';
      console.log(outerHTML);

    };

    $('#listado_rutas').html(outerHTML);
  });

}


function load_clientes_order() {
  data = JSON.parse(localStorage.clientes);

  $('.html').removeClass("visible");
  $('.rutas_det').addClass("visible");
  var outerHTML = '';
  var i;

  for (i = 0; i < data.length; i++) {
    console.log('entra al for')
    //Get the current row
    var row = data[i];
    outerHTML += '<a href="javascript:void(0)" onclick="lectura(' + row.id + ')">' +
      '    <div  class="media  heading flipInY animated">' +
      '       <div class="price" style="height: 100px;padding-top: 10%">' + i +
      '    </div>' +
      '    <div class="media-body pl-3" style="padding-top: 0px;">' +
      '       <div class="address" style="margin-top: 15px;">' + row.rubro + ' <br/> ' + row.razon + '<br/>' + row.apellido + ', ' + row.nombre + '<br/>' + row.direccion_com + ' ' + row.dirnum_com + '</div>' +
      '    </div>' +
      '   </div>' +
      '</a>';
  }

  $('#listado_rutas_detalle').html(outerHTML);
}



function lectura(id,saldo=99999999) {
  saldo=saldo.replace('.',"")
  saldo=Number(saldo)
  localStorage.lectura = id;
  if (id == "0") {
    $(".fa-money").hide();
  } else {
    $(".fa-money").show();
  }
  var cliente = JSON.parse(localStorage.clientes)[localStorage.lectura];
  if(cliente.financiacion && cliente.financiacion.tope && cliente.financiacion.tope<saldo){
    Swal.fire({
      type: 'warning',
      title: 'Tope financiación superado, no puede hacer más compras',
      showConfirmButton: false
    })
  }else{
    localStorage.marcador = 'pedido';
    lnkint('pedido');
  }

}

/////////////////////////////////////////////////

function confirma_trans() {
  transferir()
}

function transferir() {
  console.log('entra a transferirrrrrrr');
  //tomar db y pasarla a variable
  mydb.transaction(function(t) {
    var a = new Date;
    var horas = a.getHours()
    var minutos = a.getMinutes();
    var segundos = a.getSeconds();
    var dd = a.getDate();
    var mm = a.getMonth() + 1;
    var yyyy = a.getFullYear();

    dd = addZero(dd);
    mm = addZero(mm);
    horas = addZero(horas);
    minutos = addZero(minutos);
    segundos = addZero(segundos);
    console.log("Fecha: " + dd + "/" + mm + "/" + yyyy + "");
    t.executeSql("SELECT * FROM pagos WHERE estado =0 and fecha =" + dd + "/" + mm + "/" + yyyy, [], updateakn);

    //  t.executeSql("UPDATE pagos SET akn="+akn+" WHERE estado ='1' ", []);
    //    t.executeSql("SELECT * FROM pagos WHERE estado !=0 ", [], load_pagos_listos);

  }, function(err) {

    var a = new Date;
    var horas = a.getHours()
    var minutos = a.getMinutes();
    var segundos = a.getSeconds();
    var dd = a.getDate();
    var mm = a.getMonth() + 1;
    var yyyy = a.getFullYear();

    dd = addZero(dd);
    mm = addZero(mm);
    horas = addZero(horas);
    minutos = addZero(minutos);
    segundos = addZero(segundos);
    var akn = mm + yyyy + dd;
    t.executeSql("UPDATE pagos SET akn=" + akn + " WHERE estado ='1' ", []);
    console.log('error al seleccionar akn: ' + err.message);
  });
}



function updateakn(transaction, results) {
  console.log('Entra a updatear el AKN');
  console.log(results);

  if (results.rows.length == 0) {
    var a = new Date;
    var horas = a.getHours()
    var minutos = a.getMinutes();
    var segundos = a.getSeconds();
    var dd = a.getDate();
    var mm = a.getMonth() + 1;
    var yyyy = a.getFullYear();

    dd = addZero(dd);
    mm = addZero(mm);
    horas = addZero(horas);
    minutos = addZero(minutos);
    segundos = addZero(segundos);
    var akn = mm + yyyy + dd;
    console.log('AKN: ' + akn);
  } else {
    var row = results.rows.item(0);
    var akn = row.akn;
    console.log('AKN else: ' + akn);
  }

  mydb.transaction(function(t) {

    t.executeSql("UPDATE pagos SET akn=" + akn + " WHERE estado ='1' ", []);
    t.executeSql("SELECT * FROM pagos WHERE estado !=0 ", [], load_pagos_listos);

  }, function(err) {
    console.log('error al updatear akn: ' + err.message);
  });

}


function load_pagos_listos(transaction, results) {
  var elements = [];
  for (i = 0; i < results.rows.length; i++) {
    //Get the current row
    var row = results.rows.item(i);
    elements.push({
      cant: row.cant,
      cliente: row.cliente,
      clienteh: row.clienteh,
      cobrador: row.cobrador,
      credito: row.credito_pag,
      estado: row.estado,
      fecha: row.fecha,
      hora: row.hora,
      producto: row.producto,
      total: row.total,
      valor: row.valor_p,
      akn: row.akn
    });
  }
  console.log(elements)
  billetera_json = JSON.stringify(elements);
  elements = [];

  var dataString = 'transfiere&c=' + localStorage.id + '&b=' + billetera_json;
  // conectar ajax y enviar json con cobrador, etc.

  $.ajax({
    type: "POST",
    url: url_gral,
    data: dataString,
    crossDomain: true,
    cache: false,
    success: function(data) {
      // si devuelve true, pasar los pagos a (0)
      //sino indicar error y no realizar nada.
      if (data != 'FALSE') {

        mydb.transaction(function(t) {
          console.log('Borra las Tablas');
          // t.executeSql("DROP TABLE IF EXISTS pagos");
          t.executeSql("UPDATE pagos SET estado='0' ");
          t.executeSql("DROP TABLE IF EXISTS clientes");
          t.executeSql("DROP TABLE IF EXISTS creditos");
          t.executeSql("DROP TABLE IF EXISTS rutas");
        }, function(err) {
          console.log('error al eliminar tablas: ' + err.message);
        });

        syncro();
        Swal.fire({
          // position: 'top-end',
          type: 'success',
          title: 'La Billetera se transfirio correctamente',
          //html: '',
          showConfirmButton: false,
          timer: 2000,
          allowOutsideClick: false,
          onClose: () => {
            localStorage.removeItem('lectura');
            //    window.location.href = "index.html";

          }
        })



      } else {
        alert('error al transferir');
      }
    }
  })

}


//------------------------------------------------------//
function loadcreditos() {
  $('.html').removeClass("visible");
  $('.pedido').addClass("visible");
}
//------------------------------------------------------//
function load_creditos_list(transaction, results) {

  loadcliente();
  var outerHTML = '';
  var cliecantcredit = '0';
  var total_cuotas = '0';
  var cerdito_limite = '0';
  var i;

  console.log('Muestra Cantidad: ' + results.rows.length)

  for (i = 0; i < results.rows.length; i++) {
    var row = results.rows.item(i);
    var credito = row.credito;
    var enbilletera = row.ca;

    //Get the current row
    console.log(results);
    console.log('aca');
    console.log(row.credito);
    var ultipago;
    var atrasadasfinal;
    var totalpagas;
    if (row.cantipa != null) {
      atrasadasfinal = parseFloat(row.atrasadas) - parseFloat(row.cantipa);
    } else {
      atrasadasfinal = row.atrasadas;
    }
    if (row.fecha != null) {
      ultipago = row.fecha;
    } else {
      ultipago = row.upago;
    }
    if (row.totalpagas != null) {
      totalpagas = row.totalpagas;
    } else {
      totalpagas = row.pagas;
    }



    outerHTML += '<a href="javascript:void(0)" onclick="abremodal(this)" data-cobra="' + row.cobrador + '" data-cliente="' + row.cliente2 + '" data-cliente2="' + row.cliente + '" data-id="' + row.credito + '" data-vc="' + row.valor_c + '" data-codigo="' + row.codigo + '" data-tc="' + row.totcuotas + '" data-up="' + ultipago + '" data-cp="' + totalpagas + '" data-ca="' + atrasadasfinal + '" class="waves-effect waves-light" style="color: #fff;" >' +
      '<div class="note clearfix slideInRight animated" style="border-bottom: 1px solid;">' +
      '    <div class="time pull-left">' +
      '     <div class="hour">' + credito + '</div>' +
      '   <div class="shift">#</div>' +
      ' </div>' +
      ' <div class="to-do pull-left">' +
      '   <div class="title">Cod.: ' + row.codigo + ' || $ ' + row.valor_c + '<br/> U. Pago: ' + ultipago + ' </div>' +
      ' <div class="subject">C. Pag(' + totalpagas + ') || C. Atr.(' + atrasadasfinal + ') || C. Tot.(' + row.totcuotas + ')</div>' +
      '</div>' +
      '  </div>' +
      '  </a>';
    total_cuotas = parseFloat(total_cuotas) + parseFloat(row.valor_c);
    cliecantcredit++;

  }

  outerHTML += '<center><button class="btn btn-danger" onclick="pagomultiple(\'todos\',abremodal);" style="text-align: center;margin-top: 15px;margin-bottom: 25px;font-weight: bold;">Abonar todos los creditos</button><input type="hidden" id="cant_creditos_cliente" value="' + cliecantcredit + '"></center><input type="hidden" id="tot_clientes_cuotas" value="' + total_cuotas + '"></center>';

  outerHTML += '<center><button class="btn btn-success" onclick="load_clientes_order(\'defecto\')" style="float:left;text-align: center;margin-top: 15px;margin-bottom: 25px;font-weight: bold;">Volver a la ruta</button></center>';
  outerHTML += '<button class="btn btn-success" onclick="siguientecli();" style="float:right;text-align: center;margin-top: 15px;margin-bottom: 25px;font-weight: bold;">Proximo Cliente</button></center><div class="clearfix"></div>';


  elements = [];
  if (outerHTML == '') {
    outerHTML += '<span style="text-align:center">No se encontraron creditos de este cliente</span><input type="hidden" id="cant_creditos_cliente" value="0">';
  }
  //      console.log('entra al elements');
  console.log(outerHTML);
  $('#listado_creditos').html(outerHTML);
};
//    console.log('entra al loadcreditos');

function loadcliente() {
  //check to ensure the mydb object has been created
  if (mydb) {
    //Get all the cars from the database with a select statement, set outputCarList as the callback function for the executeSql command
    mydb.transaction(function(t) {
      t.executeSql("SELECT * FROM clientes WHERE cliente = '" + localStorage.lectura + "'", [], load_clientes_credits);
    }, function(err) {
      console.log('error al leer load_clientes_credits: ' + err.message);
    });
  } else {
    alert("db not found, your browser does not support web sql!");
  }
}


function load_clientes_credits(transaction, results) {
  var i;

  for (i = 0; i < results.rows.length; i++) {
    //Get the current row
    var row = results.rows.item(i);
    datoscliente = '<div class="user clearfix rotateInDownLeft animated">' +
      '<div class="photo pull-left">' +
      '<img src="img/frente_sinfoto.jpg">' //+result.avatar+'">'
      +
      '</div>' +
      '<div class="desc pull-left">' +
      '<p class="name">' + row.apellido + ', ' + row.nombre + '  <span style="font-size: x-small;font-weight: normal;"> <br/>DNI/CUIT: ' + row.dni + '</span></p>' +
      '<p class="position">' + row.direccion_com + ' ' + row.dirnum_com + ' &nbsp;&nbsp;<br/>' + row.rubro + '</p>' +
      '</div>' +
      '<div class="idle pull-right"><span class="cerrado"></span></div>' +
      '</div>';
    //}
    $('#datos_cliente').html(datoscliente);
    //         console.log('datoscliente');
    //         console.log(datoscliente);
    // };

  };

  //    console.log('entra al loadcliente');
}

function siguientecli() {
  console.log(parseInt(localStorage.posicion) + 1);
}

function abremodal(obj) {
  console.log('abre el modal');
  console.log('entro con: ' + obj);

  var detalle = obj.getAttribute("data-detalle");
  var img = obj.getAttribute("data-img");
  var codigo = obj.getAttribute("data-codigo");
  var precio = obj.getAttribute("data-precio");
  var detalle = obj.getAttribute("data-detalle");

  var modal = '';

  modal = '<div class="modal fade bottom" id="modalcuotas" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="true" aria-hidden="true" style="display: none;">' +
    '    <div class="modal-dialog modal-full-height modal-bottom modal-notify modal-danger" role="document">' +
    '      <div class="modal-content">' +
    '        <div class="modal-body" style="font-size: 18px;">' +
    '        <div class="row" >' +
    '          <div class="col-xs-4" ><img style="width:100%;border-radius: 10%;" onerror="this.src=\'img/prod/sin-imagen.png\'" src="../../img/prod/' + img + '" alt="' + codigo + '" /></div>' +
    '          <div class="col-xs-8" ><div class="col-xs-12" >' + detalle + '</div><div class="col-xs-12" style="background-color: gold;text-align: center;font-weight: 800;">$ ' + precio + '</div></div>' +
    '        </div>' +
    '          <div id="detalle_cobra">' +
    '            <div class="center" style="margin-top: 30px;">' +
    '              <span style="text-align:center;font-weight: bold;">Cantidades:</span></br>' +
    '              <div class="input-group">' +
    '                <span class="input-group-btn">' +
    '                  <button type="button" onclick="cantidadresta(\'prod\')" class="btn btn-danger btn-number" data-tipo="prod" data-type="minus" data-field="quant[1]">' +
    '                    <span class="glyphicon glyphicon-minus"></span>' +
    '                  </button>' +
    '                </span>' +
    '                <input type="number" id="cant_prodmodal" name="quant[1]" onchange="cambiapreciopros();" min="1" max="" style="height: 45px;text-align: center;font-weight: bold;font-size: xx-large;" class="form-control input-number" value="1">' +
    '                <span class="input-group-btn">' +
    '                  <button type="button" onclick="cantidadsuma(\'prod\')" class="btn btn-success btn-number" data-tipo="prod" data-type="plus" data-field="quant[1]">' +
    '                    <span class="glyphicon glyphicon-plus"></span>' +
    '                  </button>' +
    '                </span>' +
    '              </div>' +
    '              <p></p>' +
    '            </div>' +
    '            <div class="center" style="margin-top: 30px;">' +
    '              <span style="text-align:center;font-weight: bold;">Bonificaciones:</span></br>' +
    '              <div class="input-group">' +
    '                <span class="input-group-btn">' +
    '                  <button type="button" onclick="cantidadresta(\'bonif\')" class="btn btn-danger btn-number" data-tipo="bonif" data-type="minus" data-field="quant[1]">' +
    '                    <span class="glyphicon glyphicon-minus"></span>' +
    '                  </button>' +
    '                </span>' +
    '                <input type="number" id="cant_bonifmodal" name="quant[1]" min="0" max="100" style="height: 45px;text-align: center;font-weight: bold;font-size: xx-large;" class="form-control input-number" value="0">' +
    '                <span class="input-group-btn">' +
    '                  <button type="button" onclick="cantidadsuma(\'bonif\')" class="btn btn-success btn-number" data-tipo="bonif" data-type="plus" data-field="quant[1]">' +
    '                    <span class="glyphicon glyphicon-plus"></span>' +
    '                  </button>' +
    '                </span>' +
    '              </div>' +
    '              <p></p>' +
    '            </div>' +
    '              <div class="forms compose-list">' +
    '                  <div id="cobra-total" class="group clearfix bounceIn animated" style="margin-bottom: 5px;margin-top: 20px;font-weight: bold;font-size: x-large;">' +
    '                    <center>TOTAL $ </center>' +
    '                  </div>' +
    '              </div>' +
    '          </div>' +
    '        <div class="modal-footer" style="text-align:center">' +
    '          <a type="button" class="btn btn-success waves-effect waves-light" href="javascript:void(0)" id="con-fpago"  style="background:#4cae4c">Abonar' +
    '            <i class="far fa-gem ml-1"></i>' +
    '          </a>' +
    '          <a type="button" class="btn btn-danger waves-effect" data-dismiss="modal">Cancelar</a>' +
    '        </div>' +
    '      </div>' +
    '    </div>' +
    '  </div>' +
    '</div>';

  $('body').after(modal);
  $('#modalcuotas').modal('show');
}

//------------------------------------------------------------------------------------//

function modal_retiro() {
  var modal = '';

  modal = '<div class="modal fade bottom" id="modalretiro" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="true" aria-hidden="true">' +
    '    <div class="modal-dialog modal-full-height modal-bottom modal-notify modal-danger" role="document">' +
    '      <div class="modal-content">' +
    '        <div class="modal-body" style="font-size: 18px;">' +
    '        <div class="row" >' +
    '          <div class="col-xs-4" ><img style="width:100%;border-radius: 10%;" onerror="this.src=\'img/prod/sin-imagen.png\'" src="../../img/prod/" alt="codigo" /></div>' +
    '          <div class="col-xs-8" ><div class="col-xs-12" style="text-align: center;"> Pepito gonzales </div><div class="col-xs-12" style="background-color: gold;text-align: center;font-weight: 800;"></div></div>' +
    '        </div>' +
    '          <div id="detalle_cobra">' +
    '            <div class="center" style="margin-top: 30px;">' +
    '              <span style="text-align:center;font-weight: bold;">Tipo de Retiro:</span></br>' +
    '              <select id="tipo_retiro" class="form-control"><option disabled selected>Seleccione un tipo</option>' +
    '                <option value="1">Retiro</option> ' +
    '                <option value="2">Combustible</option> ' +
    '                <option value="3">Otro</option> ' +
    '              </select>' +
    '              </br></br><span style="text-align:center;font-weight: bold;">Monto a Retirar:</span></br>' +
    '              <input type="number" id="monto_modal_retiro" max="'+$('.a_rendir').html().replace('.', '')+'" min="5" style="height: 45px;text-align: center;font-weight: bold;font-size: xx-large;" class="form-control input-number" value="">' +
    '              </br></br><span style="text-align:center;font-weight: bold;">Observaciones:</span></br>' +
    '              <textarea placeholder="ingrese una observacion" id="detalle_modal_retiro" class="form-control"></textarea>' +
    '            </div>' +
    '           </div>' +
    '          </div>' +
    '        <div class="modal-footer" style="text-align:center">' +
    '          <a type="button" class="btn btn-success waves-effect waves-light" href="javascript:void(0)" id="btn_retiro" onclick="confirma_retiro()" style="background:#4cae4c">Retirar' +
    '            <i class="far fa-gem ml-1"></i>' +
    '          </a>' +
    '          <a type="button" class="btn btn-danger waves-effect" data-dismiss="modal">Cancelar</a>' +
    '        </div>' +
    '      </div>' +
    '    </div>' +
    '  </div>' +
    '</div>';

  $('modal').html(modal);
  $('#monto_modal_retiro').val($('#saldo_pagos').html());


  $('#modalretiro').modal('show');
}

//------------------------------------------------------------------------------------//

function modal_pago(obj) {
  console.log('abre el modal pago');
  console.log('entro con: ' + obj);

  var data = JSON.parse(localStorage.clientes);
  var id_cliente = data[localStorage.lectura].id;
  var nombre_cliente = data[localStorage.lectura].nombre;
  var apellido_cliente = data[localStorage.lectura].apellido;
  var telefono_cliente = data[localStorage.lectura].telefono;
  var email_cliente = data[localStorage.lectura].email;
  var foto_cliente = data[localStorage.lectura].foto;

  var modal = '';

  modal = '<div class="modal fade bottom" id="modalpago" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="true" aria-hidden="true">' +
    '    <div class="modal-dialog modal-full-height modal-bottom modal-notify modal-danger" role="document">' +
    '      <div class="modal-content">' +
    '        <div class="modal-body" style="font-size: 18px;">' +
    '        <div class="row" >' +
    '          <div class="col-xs-4" ><img style="width:100%;border-radius: 10%;" onerror="this.src=\'img/prod/sin-imagen.png\'" src="../../img/prod/" alt="codigo" /></div>' +
    '          <div class="col-xs-8" ><div class="col-xs-12" style="text-align: center;">' + nombre_cliente + ' ' + apellido_cliente + ' </div><div class="col-xs-12" style="background-color: gold;text-align: center;font-weight: 800;">' + data[localStorage.lectura].direccion + ' ' + data[localStorage.lectura].dirnum + ' </div></div>' +
    '        </div>' +
    '          <div id="detalle_cobra">' +
    '            <div class="center" style="margin-top: 30px;">' +
    '              <span style="text-align:center;font-weight: bold;">Monto a Abonar:</span></br>' +
    '              <input type="number" id="monto_modal_pagos" name="quant[1]" onchange="cambiapreciopros();" min="500" max="" style="height: 45px;text-align: center;font-weight: bold;font-size: xx-large;" class="form-control input-number" value="">' +
    '              </br></br><span style="text-align:center;font-weight: bold;">Opciones de pago:</span></br>' +
    '              </br><select id="opcion_modal_pagos" class="form-control mb-3">'+
    '               </br><option value="2">Efectivo</option>' +
    '               </br><option value="3">Transferencia</option>' +
    '               </br><option value="4">Cheque</option>' +
    '               </br><option value="5">Mercado Pago</option>' +
    '              </select>' +
    '              </br></br><span style="text-align:center;font-weight: bold;">Observaciones:</span></br>' +
    '              <textarea placeholder="ingrese una observacion" id="detalle_modal_pagos" class="form-control"></textarea>' +
    '            </div>' +
    '           </div>' +
    '          </div>' +
    '        <div class="modal-footer" style="text-align:center">' +
    '          <a type="button" class="btn btn-success waves-effect waves-light" href="javascript:void(0)" id="con-fpago" onclick="confirma_pago()" style="background:#4cae4c;margin-bottom:10px">Abonar y enviar por WhatsApp' +
    '            <i class="far fa-gem ml-1"></i>' +
    '          </a>' +
    '          <a type="button" class="btn btn-danger waves-effect" data-dismiss="modal">Cancelar</a>' +
    '        </div>' +
    '      </div>' +
    '    </div>' +
    '  </div>' +
    '</div>';

  $('modal').html(modal);
  $('#monto_modal_pagos').val($('#saldo_pagos').html());


  $('#modalpago').modal('show');
}

//------------------------------------------------------------------------------------//

function modal_pin() {

  var modal = '';

  modal = '<div class="modal fade bottom" id="modalpin" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="true" aria-hidden="true">' +
    '    <div class="modal-dialog modal-full-height modal-bottom modal-notify modal-danger" role="document">' +
    '      <div class="modal-content">' +
    '        <div class="modal-body" style="font-size: 18px;">' +
    '            <div class="center" style="margin-top: 30px;">' +
    '              <h2>Confirma la carga de la mercaderia?</h2>' +
    '              <input type="password" placeholder="Ingrese su PIN" id="numero_pin" style="height: 45px;text-align: center;font-weight: bold;font-size: xx-large;" class="form-control input-number" value="">' +
    '              </br></br><span style="text-align:center;font-weight: bold;">Observaciones:</span></br>' +
    '              <textarea placeholder="ingrese una observacion" id="observacion_pin" class="form-control"></textarea>' +
    //'              <h2>Por Favor informar a Alejandro Rozas Dennis de Sistema antes de confirmar la carga</h2>' +
    '            </div>' +
    '         </div>' +
    '        <div class="modal-footer" style="text-align:center">' +
    '          <a type="button" class="btn btn-success waves-effect waves-light" href="javascript:void(0)" id="con-fpago" onclick="confirma_auth()" style="background:#4cae4c">Confirmar' +
    '            <i class="far fa-gem ml-1"></i>' +
    '          </a>' +
    '          <a type="button" class="btn btn-danger waves-effect" data-dismiss="modal">Cancelar</a>' +
    '        </div>' +
    '      </div>' +
    '    </div>' +
    '  </div>' +
    '</div>';

  $('modal').html(modal);

  $('#modalpin').modal('show');
}


//------------------------------------------------------------------------------------//
function productopop() {

}


function realizapago(objgps) {
  //    alert('entro con GPS');
  console.log(objgps);

  console.log('entra a realizar pago');

  if (objgps != 'todos') {
    objeto.cant = $('#cant_cuotasmodal').val();
    objeto.total = objeto.valor * objeto.cant;
    objeto.fecha = hoyFecha();
    //objeto.fecha = '12/05/2019';
    var a = new Date;
    var horas = a.getHours()
    var minutos = a.getMinutes();
    var segundos = a.getSeconds();
    objeto.hora = addZero(horas) + ':' + addZero(minutos) + ':' + addZero(segundos);
    objeto.estado = '1';
    console.log(objeto);
    //  objeto.cliente = '0';
    //  objeto.clienteh = '0';


    //  console.log('carga cliente')
    mydb.transaction(function(t) {
      t.executeSql("INSERT INTO pagos (cant, cliente, clienteh, cobrador, credito_pag, estado, fecha, hora, producto, total, valor_p) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)", [objeto.cant, objeto.cliente, objeto.clienteh, localStorage.idcobrador, objeto.credito, objeto.estado, objeto.fecha, objeto.hora, objeto.producto, objeto.total, objeto.valor]);
    }, function(err) {
      console.log('error insertar pagos: ' + err.message);
    })

    Swal.fire({
      // position: 'top-end',
      type: 'success',
      title: 'El Pago se realizo correctamente',
      //html: '',
      showConfirmButton: false,
      timer: 2000,
      allowOutsideClick: false,
      onClose: () => {
        localStorage.removeItem('lectura');
        $('#modalcuotas').modal('hide');
        //  window.location.href = "index.html";
      }
    })

  } else {
    //selecciono los credito_s
    //Get all the cars from the database with a select statement, set outputCarList as the callback function for the executeSql command
    mydb.transaction(function(t) {
      t.executeSql("SELECT * FROM creditos WHERE cliente2 = '" + localStorage.lectura + "'", [], load_creditos_pagos);
    }, function(err) {
      console.log('error al leer creditos: ' + err.message);
    });

    // dentro del while inserto pagos x credito

    //mensaje de Swal.fire

  }

}

function load_creditos_pagos(transaction, results) {

  mydb.transaction(function(t) {
    150
    for (i = 0; i < results.rows.length; i++) {
      //Get the current row
      var row = results.rows.item(i);
      var valor = row.valor_c;
      var producto = row.codigo;
      var cliente = row.cliente2;
      var clienteh = row.cliente;
      var cobrador = row.cobrador;
      var estado = '1';
      var credito = row.credito;
      var fecha = hoyFecha();
      var a = new Date;
      var horas = a.getHours()
      var minutos = a.getMinutes();
      var segundos = a.getSeconds();
      var hora = addZero(horas) + ':' + addZero(minutos) + ':' + addZero(segundos);
      var cant = $('#cant_cuotasmodal').val();
      var total = parseFloat(valor) * parseFloat(cant);
      console.log('Precio: ' + valor + ' Cantidad: ' + cant + ' credito: ' + credito);

      console.log('inserta ' + i);
      t.executeSql("INSERT INTO pagos (cant, cliente, clienteh, cobrador, credito_pag, estado, fecha, hora, producto, total, valor_p) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)", [cant, cliente, clienteh, cobrador, credito, estado, fecha, hora, producto, total, valor]);
    }

    Swal.fire({
      // position: 'top-end',
      type: 'success',
      title: 'Los Pagos se realizaron correctamente',
      //html: '',
      showConfirmButton: false,
      timer: 2000,
      allowOutsideClick: false,
      onClose: () => {
        localStorage.removeItem('lectura');
        //  window.location.href = "index.html";
      }
    })

  }, function(err) {
    console.log('error insertar pagos: ' + err.message);
  })

}


function loadbilletera() {
  $('#transfiere').show();
  console.log('total_billetera');
  console.log(total_billetera);
  total_billetera = 0;
  fechas_liquidar = '';

  console.log('entra a la funcion billetera')

  //load_billetera_list
  // calculamos monto
}


function load_billetera_detalle(transaction, results) {
  var i;
  var iddiv = '';
  var totalesfecha = '0';
  for (i = 0; i < results.rows.length; i++) {
    //Get the current row
    var row = results.rows.item(i);
    total_billetera = parseFloat(total_billetera) + parseFloat(row.total);

    totalesfecha = parseFloat(totalesfecha) + parseFloat(row.total);
    var pagolist = '<a href="javascript:void(0)" data-cobra="' + row.cobrador + '" data-cliente="' + row.cliente2 + '" data-cliente2="' + row.cliente + '" data-id="' + row.credito + '" data-vc="' + row.valor_p + '" data-codigo="' + row.codigo + '" data-tc="' + row.totcuotas + '" data-up="' + row.upago + '" data-cp="' + row.pagas + '" data-ca="' + row.atrasadas + '" class="waves-effect waves-light" style="color: #fff;" >' +
      '<div class="note clearfix slideInRight animated" style="border-bottom: 1px solid;">' +
      '    <div class="time pull-left">' +
      '     <div class="hour">' + row.credito_pag + '</div>' +
      '   <div class="shift">#</div>' +
      ' </div>' +
      ' <div class="to-do pull-left">' +
      '   <div class="title"><b>Cod.: </b>' + row.producto + ' <br/><b>Cuotas:</b>  ' + row.cant + ' ||<b> Valor: </b>$ ' + row.valor_p + ' || <b>Total:</b> $  ' + row.total +
      ' <br/> <b>Fecha de Pago:</b> ' + row.fecha + ' (' + row.hora + ') </div>'
      // +' <div class="subject">C. Pag('+ row.pagas +') || C. Atr('+ row.atrasadas +') || C. Tot.('+ row.totcuotas +')</div>'
      +
      '</div>' +
      '  </div>' +
      '  </a>';
    iddiv = '_pagos_' + remplazastr(row.fecha, '/');
    $('#listado' + iddiv).append(pagolist);
    $('#total' + iddiv).html(parseFloat($('#total' + iddiv).text()) + parseFloat(row.total));
    console.log($('#total' + iddiv).text());
  }

  $('#transfiere').html('Transferir billetera ($' + total_billetera + ')');
  $('#total_billetera').html(total_billetera);
  if (fechas_liquidar == '') {
    $('#txtcobranza').html('No se encontro dinero en su Billetera');
    $('#transfiere').hide();

  } else {
    $('#fechasaliquidar').html(fechas_liquidar);
  }
}


function load_billetera_list(transaction, results) {
  var i;
  var estructura = '';
  console.log('Entra al load_billetera_list')
  for (i = 0; i < results.rows.length; i++) {
    //Get the current row
    var row = results.rows.item(i);
    fechas_liquidar += row.fecha + '<br>';
    //select fechas_liquidar
    // select detalle
    var idcob = remplazastr(row.fecha, '/');
    //  mifecha = row.fecha.replace('/','');
    estructura += '<p  class="heading flipInY animated">' +
      '      <span class="name">Cobranza del : </span>' +
      '      <span class="position">' + row.fecha + '</span>' +
      '    </p>' +
      '    <div class="active-users" id="datos_cliente">' +
      '    </div>' +
      '    <div id="listado_pagos_' + idcob + '" class="alarm-list"></div>' +
      '    <center><button class="btn btn-success" onclick="" style="text-align: center;margin-top: 15px;margin-bottom: 25px;font-weight: bold;" >Billetera Diaria ($<span id="total_pagos_' + idcob + '">0</span>)</button></center>';
  }
  estructura += ' <center><button class="btn btn-success" onclick="" style="text-align: center;margin-top: 15px;margin-bottom: 25px;font-weight: bold;" >Total en mi Billetera ($<span id="total_billetera"></span>)</button></center>';
  $('.billetera').html(estructura);
  if (mydb) {
    //Get all the cars from the database with a select statement, set outputCarList as the callback function for the executeSql command
    mydb.transaction(function(t) {
      t.executeSql("SELECT * FROM pagos WHERE estado != '0'", [], load_billetera_detalle);
    }, function(err) {
      console.log('error al leer load_billetera_list: ' + err.message);
    });
  } else {
    alert("db not found, your browser does not support web sql!");
  }



}




function desactivabilletera() {

  var db = indexedDB.open("internaldb");
  db.onsuccess = function(event) {
    db = event.target.result;
    var datito = db.transaction("pagos", "readwrite");
    var otrodato = datito.objectStore("pagos");
    var elements = [];

    otrodato.openCursor().onsuccess = function(f) {
      var result = f.target.result;
      if (result === null) {
        return;
      }
      elements.put(result.value.estado = '0');
      result.continue();
    };
    datito.oncomplete = function() {

    }
  }
}

function pagomultiple(dnd, callback) {
  var cantidadcuotas = '1';
  var lecturacliente = localStorage.lectura;

  if (mydb) {
    //Get all the cars from the database with a select statement, set outputCarList as the callback function for the executeSql command
    mydb.transaction(function(t) {
      t.executeSql("SELECT * FROM creditos WHERE estado != '0' and cliente2 ='" + lecturacliente + "'", [], load_multiple);
    }, function(err) {
      console.log('error al leer pagomultiple: ' + err.message);
    });
  } else {
    alert("db not found, your browser does not support web sql!");
  }
}

//loadcliente();


function load_multiple(transaction, results) {
  var i;
  var cantidadcuotas = '1';
  var estructura = '';
  var totaltotal = '0';
  var cuotamax = '0';
  var sumatotal = '0';

  for (i = 0; i < results.rows.length; i++) {
    var row = results.rows.item(i);
    var pendientecuotas = parseFloat(row.totcuotas) - parseFloat(row.pagas);
    if (cuotamax < pendientecuotas) {
      cuotamax = pendientecuotas;
    }
    if (cantidadcuotas <= pendientecuotas) {
      sumatotal = parseFloat(cantidadcuotas) * parseFloat(row.valor_c);
    } else {
      sumatotal = parseFloat(pendientecuotas) * parseFloat(row.valor_c);
    }

    console.log('Cuotas Totales: ' + pendientecuotas);
    console.log('Cuotas Maxima: ' + cuotamax);
    console.log('Suma Totales: ' + sumatotal);
    totaltotal = parseFloat(totaltotal) + parseFloat(sumatotal);
  }

  var resultonga = totaltotal + '@' + cuotamax;
  console.log('resultonga: ' + resultonga)
  abremodal('todos', resultonga);
}

function agregarCliente() {
  if (camposCorrectos()) {

    var celularCliente = $("#celularCliente").val();
    var calleCliente = $("#calleCliente").val();
    var numeroCliente = $("#numeroCliente").val();
    var ciudadCliente = $("#ciudadCliente").val();
    var provinciaCliente = $("#provinciaCliente").val();
    var razonSocial = $("#razonSocial").val();

    var rubro = $("#rubro").val();
    var idUsuario = localStorage.id;
    var string = "add_clientes=1&u=" + idUsuario + "&celular=" + celularCliente + "&provincia=" + provinciaCliente + "&ciudad=" + ciudadCliente + "&direccion=" + calleCliente + "&numero=" + numeroCliente + "&razon=" + razonSocial + "&rubro=" + rubro;
    console.log('datos string: ' + string);

    $.ajax({
      type: "POST",
      url: "../procesos/functions-online.php?",
      data: string,
      success: function(data) {
        if (data.estado != 'false') {
          //lnkint('clientes')
        } else {
          alert('Error al insertar cliente')
        }
      }
    });
  }
}

function llenaprovincia(){
  $.ajax({
    type: "POST",
    url: "../procesos/functions-online.php?",
    data: 'llenaprov=1',
    success: function(data) {
      data = JSON.parse(data);
      if (data.estado != 'false') {
        //lnkint('clientes')
        var esqueleto='<option value="" disabled selected>Seleccione una provincia</option>';
        for(var i=0; i < data.provincias.length; i++){
        //  console.log('entro:'+i);
          esqueleto +='<option value="'+data.provincias[i].id+'">'+data.provincias[i].desc+'</option>';
        }
      //  console.log(esqueleto);
        $('#provinciaCliente').html(esqueleto);
        $('#provinciaCliente').val('1');
      } else {
        alert('Error al recuperar las provincias');
      }
    }
  });
}

function llenalocalidad(prov) {
  var string='llenaloc=1';
  if(prov!='' && prov!=undefined){string='llenaloc=1&p='+prov;}
  $.ajax({
    type: "POST",
    url: "../procesos/functions-online.php?",
    data: string,
    success: function(data) {
      data = JSON.parse(data);
      if (data.estado != 'false') {
        //lnkint('clientes')
        var esqueleto='<option value="" disabled selected>Seleccione una Localidad</option>';
        for(var i=0; i < data.localidad.length; i++){
//          console.log('entro:'+i);
          esqueleto +='<option value="'+data.localidad[i].id+'">'+data.localidad[i].desc+'</option>';
        }
  //      console.log(esqueleto);
        $('#ciudadCliente').html(esqueleto);
        if(prov=='1'){$('#ciudadCliente').val('16353');}

      } else {
        alert('Error al recuperar las localidades');
      }
    }
  });
}

function llenarubro() {
  var string='llenarubro=1';
  $.ajax({
    type: "POST",
    url: "../procesos/functions-online.php?",
    data: string,
    success: function(data) {
      data = JSON.parse(data);
      if (data.estado != 'false') {
        //lnkint('clientes')
        var esqueleto='<option value="" disabled selected>Seleccione un Rubro</option>';
        for(var i=0; i < data.rubro.length; i++){
//          console.log('entro:'+i);
          esqueleto +='<option value="'+data.rubro[i].id+'">'+data.rubro[i].desc+'</option>';
        }
  //      console.log(esqueleto);
        $('#rubro').html(esqueleto);


      } else {
        alert('Error al recuperar los rubros');
      }
    }
  });
}

function camposCorrectos() {
  let correcto = false;

      if ($("#celularCliente").val() != "") {
        if ($("#provinciaCliente").val() != "") {
          if ($("#ciudadCliente").val() != "") {
            if ($("#calleCliente").val() != "") {
              if ($("#numeroCliente").val() != "") {
                if ($("#razonSocial").val()) {
                  correcto = true;
                } else {
                  Swal.fire({ // position: 'top-end',
                    type: 'info',
                    title: 'Complete la razon social',
                    showConfirmButton: false,
                    timer: 1000,
                    allowOutsideClick: false,
                    onClose: () => {

                    }
                  })
                }
              } else {
                Swal.fire({ // position: 'top-end',
                  type: 'info',
                  title: 'Complete el numero de la calle',
                  showConfirmButton: false,
                  timer: 1000,
                  allowOutsideClick: false,
                  onClose: () => {

                  }
                })
              }
            } else {
              Swal.fire({ // position: 'top-end',
                type: 'info',
                title: 'Complete la calle',
                showConfirmButton: false,
                timer: 1000,
                allowOutsideClick: false,
                onClose: () => {

                }
              })
            }
          } else {
            Swal.fire({ // position: 'top-end',
              type: 'info',
              title: 'Complete la ciudad',
              showConfirmButton: false,
              timer: 1000,
              allowOutsideClick: false,
              onClose: () => {

              }
            })
          }
        } else {
          Swal.fire({ // position: 'top-end',
            type: 'info',
            title: 'Complete la provincia',
            showConfirmButton: false,
            timer: 1000,
            allowOutsideClick: false,
            onClose: () => {

            }
          })
        }
      } else {
        Swal.fire({ // position: 'top-end',
          type: 'info',
          title: 'Compplete el celular',
          showConfirmButton: false,
          timer: 1000,
          allowOutsideClick: false,
          onClose: () => {

          }
        })
      }

  return correcto;
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