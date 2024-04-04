<div class="container-fluid">
  <div class="row page-titles">
    <div class="col-md-12">
      <h4 class="text-white">Nuevo Pedido</h4>
    </div>
    <div class="col-md-6">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
        <li class="breadcrumb-item"><a href="index.php?pagina=pedidos">Pedidos</a></li>
        <li class="breadcrumb-item active">Nuevo</li>
      </ol>
    </div>
    <div class="col-md-6 text-right">
      <form class="app-search d-none d-md-block d-lg-block">
        <input type="text" class="form-control" placeholder="Buscar...">
      </form>
    </div>
  </div>
  <div class="row">
    <div class="card" style="width: 100%;">
      <div class="card-body">
        <h4 class="card-title">Complete el formulario de pedido</h4>
        <form class="form-horizontal form-material">
          <div class="modal-body">
            <input type="hidden" name="comercio" id="comercio">
            <input type="hidden" name="product" id="product">
            <div class="row">
              <div class="col-md-2 m-b-20">
                <input type="date" id="fechapedido" class="form-control fecha" value="<?php echo date("Y-m-d"); ?>" required="">
              </div>

              <div class="col-md-10 m-b-20">
                <select name="comercio_tomo" id="comercio_tomo" placeholder="Seleccione un comercio" class="form-control comercio" required="">

                  <?php

                  $consul_comercios = $link->query("SELECT * FROM clientes
                            inner join clientes_comercios on clientes_comercios.cliente_comclientes = clientes.id_clientes
                            where clientes_comercios.estado_comclientes ='1' order by clientes_comercios.razon_comclientes ASC");
                  while ($row = mysqli_fetch_array($consul_comercios)) {
                    echo '<option value="' . $row['id_clientes'] . '">' . utf8_encode($row['razon_comclientes']) . '</option>';
                  } ?>
                </select>
              </div>
            </div>
            <div class="row">
              <div class=" col-2">
                <input type="number" class="form-control cantidad" min="1" max="100" value="" placeholder="Cantidad" id="cantidad">

              </div>
              <div class=" col-2">
                <input type="number" class="form-control bonifica" min="0" max="100" value="" placeholder="Bonificacion" id="bonifica">
              </div>
              <div class=" col-7">
                <input type="number" style="display:none" step="any" class="form-control ventadirecta" placeholder="Ingrese Monto" id="ventadirecta">
                <select class="form-control product" id="product_list" placeholder="Seleccione un producto">
                  <?php $consul_prod = $link->query("SELECT * FROM productos WHERE estado_producto = 1 order by codigo_producto ASC ") or die(mysqli_error());
                  while ($row = mysqli_fetch_array($consul_prod)) {
                    echo '<option value="' . $row['id_producto'] . '@' . $row['codigo_producto'] . '@' . $row['detalle_producto'] . '@' . $row['precio_producto'] . '">' . $row['codigo_producto'] . ' - ' . $row['detalle_producto'] . '</option>';
                  } ?>
                </select>

              </div>

              <div class=" col-1">
                <button class="btn btn-success btn-block botonmas" type="button" onclick="insertaprod();"><i class="fa fa-plus"></i></button>
              </div>

            </div><br>

            <table class="table table-hover">
              <thead>
                <tr>
                  <th width="50px" class="text-right">Cantidad</th>
                  <th width="50px" class="text-right">Bonif.</th>
                  <th width="90px" class="text-right">Codigo</th>
                  <th class="text-right">Descripcion</th>
                  <th class="text-right">P. Unitario</th>
                  <th class="text-right">Total</th>
                  <th class="text-right">Acc.</th>
                  <th class="text-center">#</th>
                </tr>
              </thead>
              <tbody id="items_prod">
                <tr class="reset">
                  <td colspan="8" class="text-center">
                    <center>Aun no se cargaron productos</center>
                  </td>
                </tr>
              </tbody>
            </table>
            <div class="col-md-12">
              <div class="pull-right m-t-30 text-right">
                <p style="display:none">SubTotal: $ <span id="sub_total">0</span></p>
                <p id="p_iva1" style="display:none">IVA (21%): $ <span id="iva1">0</span></p>
                <p id="p_iva2" style="display:none">IVA (10.5%): $ <span id="iva2">0</span></p>
                <hr>
                <h3><b>Total :</b> $<span id="total">0</span></h3>
              </div>
              <div class="clearfix"></div>
              <hr>

            </div>
            <hr /><br>
            <div class="col-md-12 m-b-20">
              <textarea placeholder="ingrese el detalle del pedido" rows="5" onchange="updatedetalle()" class="form-control detalle" id="detallepedido"></textarea>
            </div>
            <div>

            </div>



            <a onclick="guarda_pedido()" class="btn btn-info waves-effect">Continuar</a>
            <a href="?pagina=pedidos" class="btn btn-danger waves-effect">Cancelar</a>
          </div>
        </form>


      </div>
    </div>
  </div>
  <script>
    var carrito = [];

    function addCommas(nStr) {
      nStr += '';
      var x = nStr.split('.');
      var x1 = x[0];
      var x2 = x.length > 1 ? ',' + x[1] : ''; // si falla removeer el toFixed
      var rgx = /(\d+)(\d{3})/;
      while (rgx.test(x1)) {
        x1 = x1.replace(rgx, '$1' + '.' + '$2');
      }
      return x1 + x2;
    }



    $('#comercio_tomo')
      .editableSelect()
      .on('select.editable-select', function(e, li) {

        $('#comercio').val(li.val());
        if (li.val() == '218') {
          console.log('entra al if de 218')
          $('#ventadirecta').show();
          $('#bonifica').hide();
          $('#cantidad').hide();
          $('#product_list').hide();
          $('.botonmas').hide();
        } else {
          $('#ventadirecta').hide();
          $('#bonifica').show();
          $('#cantidad').show();
          $('#product_list').show();
          $('.botonmas').show();
        }
      });
    $('#product_list')
      .editableSelect()
      .on('select.editable-select', function(e, li) {

        $('#product').val(li[0].attributes[0].value);
      });

    function insertaprod() {

      var desarmo = $('#product').val().split('@');
      var id = desarmo[0];
      var detalle = desarmo[2];
      var precio = desarmo[3];
      var codigo = desarmo[1];
      var cantidad = $('#cantidad').val();
      var bonifica = $('#bonifica').val();
      // var item = $('#items_prod')[0].childElementCount;
      if (cantidad > 0 && codigo != undefined && ($("#comercio").val() != '' && $("#comercio").val() != undefined)) {
        var calc_total = parseFloat(precio) * parseFloat(cantidad);
        calc_total = parseFloat(calc_total) + (parseFloat(calc_total) * 0.21);
        precio = parseFloat(precio) + (parseFloat(precio) * 0.21);
        carrito.cliente_pedido = $("#comercio").val(),
          carrito.fecha_pedido = $("#fechapedido").val();
        if (!carrito.items) {
          carrito.items = [];
        }

        carrito.items.push({
          id: id,
          detalle: detalle,
          precio: precio.toFixed(2),
          codigo: codigo,
          cantidad: cantidad,
          bonifica: bonifica,
          calc_total: calc_total.toFixed(2)
        });

        dibujacarrito();

      } else {
        alert('Seleccione un producto y cantidad')
      }
    }

    function updatedetalle() {
      carrito.detalle_pedido = $("#detallepedido").val();
    }

    function dibujacarrito() {

      console.log(carrito);
      if (carrito.items.length > 0) {
        $("#comercio_tomo").attr("readonly", "readonly");
        $("#fechapedido").attr("readonly", "readonly");
        var g_subtotal = 0;
        var g_total = 0;
        $('#items_prod').html('');
        for (var i = 0; i < carrito.items.length; i++) {
          $('#items_prod').append('<tr><td class="text-right">' + carrito.items[i].cantidad + '</td><td class="text-right">' + carrito.items[i].bonifica + '</td><td class="text-right">' + carrito.items[i].codigo + '</td><td class="text-right">' + carrito.items[i].detalle + '</td><td class="text-right">$ ' + carrito.items[i].precio + '</td><td class="text-right">$ ' + addCommas(carrito.items[i].calc_total) + '</td><td class="text-right"><a class="btn-pure btn-outline-danger delete-row-btn btn-lg" style="padding:0px;" href="javascript:void(0)" onclick="delitem(' + i + ')" ><i class="ti-close" aria-hidden="true"></i></a></td><td class="text-center">' + (parseFloat(i) + parseFloat(1)) + '</td>');

          $('#product').val('');
          $('#product_list').val('');
          $('#cantidad').val('');
          $('#bonifica').val('');
          g_subtotal = parseFloat(g_subtotal) + parseFloat(carrito.items[i].calc_total);

          var iva1 = g_subtotal * 0.21;
          console.log(iva1);
          g_total = parseFloat(g_subtotal); // + parseFloat(iva1);
          $('#sub_total').html(addCommas(g_subtotal.toFixed(2)));
          $('#total').html(addCommas(g_total.toFixed(2)));
          //  $("#p_iva1").html('IVA (21%): $ <span id="iva1">'+addCommas(iva1.toFixed(2))+'</span>');
          $('#product_list').editableSelect('destroy');
          $('#product_list').editableSelect()
            .on('select.editable-select', function(e, li) {

              $('#product').val(li[0].attributes[0].value);
            });

        }
        carrito.total_pedido = g_total;
      } else {
        $("#comercio_tomo").removeAttr("readonly");
        $("#fechapedido").removeAttr("readonly");
        $('#items_prod').html('<tr class="reset"><td colspan="6" class="text-center"><center>Aun no se cargaron productos</center></td></tr>')
        $('#sub_total').html('0');
        $('#total').html('0');
      }
    }

    function delitem(i) {
      carrito.items.splice(i, 1, )
      dibujacarrito()
    }

    function guarda_pedido() {
      var items_json = JSON.stringify(carrito.items);
      if ($('#ventadirecta').val() != '') {
        var string = 'a=add&c=218&f=' + $('#fechapedido').val() + '&d=' + $('#detallepedido').val() + '&t=' + $('#ventadirecta').val() + '&vd=1';
      } else {
        var string = 'a=add&c=' + carrito.cliente_pedido + '&f=' + carrito.fecha_pedido + '&d=' + carrito.detalle_pedido + '&t=' + carrito.total_pedido + '&i=' + items_json + '&vd=0';
      }

      $.ajax({
        url: "procesos/pedidos.php?",
        data: string,
        // datatype: "html",
        type: "POST",

        success: function(data) {
          if (data == 'TRUE') {
            window.location = "index.php?pagina=pedidos";
          } else {
            alert(data)
          } //'Error Al Grabar Pedido,'
        }

      })

    }
  </script>