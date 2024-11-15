<div class="container-fluid">
  <div class="row page-titles">
    <div class="col-md-12">
      <h4 class="text-white">Nueva Carga</h4>
    </div>
    <div class="col-md-6">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
        <li class="breadcrumb-item"><a href="index.php?pagina=estadocamion">Cargas</a></li>
        <li class="breadcrumb-item active">Nueva</li>
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
        <h4 class="card-title">Complete el formulario de carga</h4>
        <form class="form-horizontal form-material">
          <div class="modal-body">
            <input type="hidden" name="personal" id="personal">
            <input type="hidden" name="camion" id="camion">
            <input type="hidden" name="product" id="product">
            <div class="row">
              <div class="col-md-2 m-b-20">
                <input type="datetime-local" id="fechacarga" class="form-control fecha" value="<?php echo date("Y-m-d\TH:i"); ?>" required="">
              </div>

              <div class="col-md-10 m-b-20">
                <select name="personal_tomo" id="personal_tomo" placeholder="Seleccione el Personal" class="form-control personal" required="">

                  <?php

                  $consul_personal = $link->query("SELECT * FROM personal where estado !='0' and area = 'Reparto' order by apellido ASC");
                  while ($row = mysqli_fetch_array($consul_personal)) {
                    echo '<option value="' . $row['id'] . '">' . utf8_encode($row['nombre'] . ', ' . $row['apellido']) . '</option>';
                  } ?>
                </select>
              </div>
            </div>
            <div class="row">
              <div class=" col-4">
                <input type="number" class="form-control cantidad" min="1" max="100" value="" placeholder="Cantidad" id="cantidad">

              </div>

              <div class=" col-7">
                <input type="number" style="display:none" step="any" class="form-control ventadirecta" placeholder="Ingrese Monto" id="ventadirecta">
                <select class="form-control product" id="product_list" placeholder="Seleccione un producto">
                  <?php $consul_prod = $link->query("SELECT * FROM productos WHERE estado_producto = 1 order by descripcion_producto asc ") or die(mysqli_error());
                  while ($row = mysqli_fetch_array($consul_prod)) {
                    echo '<option value="' . $row['id_producto'] . '@' . $row['codigo_producto'] . '@' . $row['detalle_producto'] . '@' . $row['presentacion_producto'] . '@' . $row['descripcion_producto'] . '">' . $row['codigo_producto'] . ' - ' . $row['descripcion_producto'] . ' (' . $row['presentacion_producto'] . ') </option>';
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
                  <th width="90px" class="text-right">Codigo</th>
                  <th class="text-right">Descripcion</th>
                  <th class="text-right">Presentacion</th>
                  <th class="text-right">Opciones</th>
                  <th class="text-center">#</th>
                </tr>
              </thead>
              <tbody id="items_prod">
                <tr class="reset">
                  <td colspan="6" class="text-center">
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

              </div>
              <div class="clearfix"></div>
              <hr>

            </div>
            <hr /><br>
            <div class="col-md-12 m-b-20">
              <textarea placeholder="ingrese una observacion" rows="5" onchange="updatedetalle()" class="form-control detalle" id="detallepedido"></textarea>
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
</div>
<script>
  var carrito = [];

  function addCommas(nStr) {
    nStr += '';
    var x = nStr.split('.');
    var x1 = x[0];
    var x2 = x.length > 1 ? ',' + x[1] : '';
    var rgx = /(\d+)(\d{3})/;
    while (rgx.test(x1)) {
      x1 = x1.replace(rgx, '$1' + '.' + '$2');
    }
    return x1 + x2;
  }



  $('#personal_tomo')
    .editableSelect()
    .on('select.editable-select', function(e, li) {
      $('#personal').val(li.val());
      controlcarga(li.val());
      if (li.val() == '218') {
        console.log('entra al if de 218')
        $('#ventadirecta').show();
        $('#cantidad').hide();
        $('#product_list').hide();
        $('.botonmas').hide();
      } else {
        $('#ventadirecta').hide();

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
    var presentacion = desarmo[3];

    var descripcion = desarmo[4];
    var codigo = desarmo[1];
    var cantidad = $('#cantidad').val();
    // var item = $('#items_prod')[0].childElementCount;
    if (cantidad > 0 && codigo != undefined && ($("#personal").val() != '' && $("#personal").val() != undefined)) {

      carrito.cliente_pedido = $("#personal").val(),
        carrito.fecha_pedido = $("#fechacarga").val();
      if (!carrito.items) {
        carrito.items = [];
      }

      carrito.items.push({
        id: id,
        detalle: detalle,

        descripcion: descripcion,
        presentacion: presentacion,
        codigo: codigo,
        cantidad: cantidad
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
      console.log('entra');
      $("#personal_tomo").attr("readonly", "readonly");
      $("#fechacarga").attr("readonly", "readonly");
      var g_subtotal = 0;
      var g_total = 0;
      $('#items_prod').html('');
      for (var i = 0; i < carrito.items.length; i++) {
        console.log('entra al append');
        $('#items_prod').append('<tr><td class="text-right">' + carrito.items[i].cantidad + '</td><td class="text-right">' + carrito.items[i].codigo + '</td><td class="text-right">' + carrito.items[i].descripcion + '</td><td class="text-right">' + carrito.items[i].presentacion + '</td><td class="text-right"><a class="btn-pure btn-outline-danger delete-row-btn btn-lg" style="padding:0px;" href="javascript:void(0)" onclick="delitem(' + i + ')" ><i class="ti-close" aria-hidden="true"></i></a></td><td class="text-center">' + (parseFloat(i) + parseFloat(1)) + '</td>');

        $('#product').val('');
        $('#product_list').val('');
        $('#cantidad').val('');

        $('#product_list').editableSelect('destroy');
        $('#product_list').editableSelect()
          .on('select.editable-select', function(e, li) {

            $('#product').val(li[0].attributes[0].value);
          });

      }
      carrito.total_pedido = g_total;
    } else {
      $("#personal_tomo").removeAttr("readonly");
      $("#fechacarga").removeAttr("readonly");
      $('#items_prod').html('<tr class="reset"><td colspan="6" class="text-center"><center>Aun no se cargaron productos</center></td></tr>')
      $('#sub_total').html('0');
      $('#total').html('0');
    }
  }

  function delitem(i) {
    carrito.items.splice(i, 1, )
    dibujacarrito()
  }

  function controlcarga(personal) {
    var string = 'a=existe_carga&p=' + personal;
    $.ajax({
      url: "procesos/camion.php?",
      data: string,
      type: "POST",
      success: function(data) {
        if (data == 'TRUE') {
          //    alert('El personal aun tiene carga en el camion. Es recomendable realizar la liquidacion antes de volder a cargar mercaderia');
        }
      }
    })

  }

  function guarda_pedido() {
    var items_json = JSON.stringify(carrito.items);
    console.log(carrito.items)
    var string = 'a=add_carga&c=' + carrito.cliente_pedido + '&f=' + carrito.fecha_pedido + '&d=' + carrito.detalle_pedido + '&t=' + carrito.total_pedido + '&i=' + items_json + '&vd=0';

    $.ajax({
      url: "procesos/camion.php?",
      data: string,
      // datatype: "html",
      type: "POST",

      success: function(data) {
        if (data == 'TRUE') {
          window.location = "index.php?pagina=estadocamion";
        } else {
          alert('Error al guardar carga: ' + data)
        } //'Error Al Grabar Pedido,'
      }

    })

  }
</script>