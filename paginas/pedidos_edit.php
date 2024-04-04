<?php
$id=$_GET['id'];
  $consul_id = $link->query("SELECT * FROM `transaccion` INNER JOIN clientes on clientes.id_clientes = transaccion.cliente INNER JOIN clientes_comercios on clientes_comercios.cliente_comclientes = clientes.id_clientes WHERE estado='1' and id='$id' ");
  $rowid= mysqli_fetch_array($consul_id);
$comerciodb=$rowid['cliente'];
$fechadb=date('Y-m-d',strtotime($rowid['fecha']));
  $num='1';
  $i='0';
  $acumula=0;
  $consul_prod = $link->query("SELECT * FROM productos WHERE estado_producto = 1 order by codigo_producto ASC ") or die(mysqli_error());
    $consul_items = $link->query("SELECT * FROM items_pedidos INNER JOIN productos on productos.id_producto = items_pedidos.prod_itemsp WHERE pedido_itemsp='$id' and estado_itemsp='1'");
$carrito['detalle_pedido']= $rowid['observacion'];
$carrito['cliente_pedido']= $rowid['cliente'];
$carrito['fecha_pedido']= $rowid['fecha'];
    while ($row= mysqli_fetch_array($consul_items)){


      $total = $row['monto_itemsp'] * $row['cantidad_itemsp'];

$carrito['items'][$i]['id']= $row['id_producto'];
$carrito['items'][$i]['detalle']=$row['detalle_producto'];
$carrito['items'][$i]['precio']=$row['monto_itemsp'];
$carrito['items'][$i]['preciosiva']=$row['precio_producto'];
$carrito['items'][$i]['codigo']=$row['codigo_producto'];
$carrito['items'][$i]['cantidad']=$row['cantidad_itemsp'];
$carrito['items'][$i]['bonifica']=$row['bonifica_itemsp'];
$carrito['items'][$i]['calc_total']=$row['monto_itemsp']*$row['cantidad_itemsp'];
$i++;}?>
<div class="container-fluid">
    <div class="row page-titles">
        <div class="col-md-12">
            <h4 class="text-white">Editar Pedido</h4>
        </div>
        <div class="col-md-6">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
                <li class="breadcrumb-item"><a href="index.php?pagina=pedidos">Pedidos</a></li>
                <li class="breadcrumb-item active">Edicion</li>
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
            <h4 class="card-title">Edite el pedido N°<?php echo $_GET['id']?></h4>
                  <form class="form-horizontal form-material">
                <div class="modal-body">
                  <input type="hidden" name="comercio" id="comercio" >
                  <input type="hidden" name="product" id="product" >
                    <div class="row">
                      <div class="col-md-2 m-b-20">
                        <input type="date" id="fechapedido" onchange="updateday(this)" class="form-control fecha" value="<?php echo $fechadb; ?>" required="">
                      </div>

                      <div class="col-md-10 m-b-20">
                        <select name="comercio_tomo" id="comercio_tomo"  placeholder="Seleccione un comercio" class="form-control comercio" required="">

                          <?php

                          $consul_comercios = $link->query("SELECT * FROM clientes
                            inner join clientes_comercios on clientes_comercios.cliente_comclientes = clientes.id_clientes
                            where clientes_comercios.estado_comclientes ='1' order by clientes_comercios.razon_comclientes ASC");
                          while ($row= mysqli_fetch_array($consul_comercios)){
                           echo '<option value="'.$row['id_clientes'].'"';
                           if($row['id_clientes']==$comerciodb){ echo' selected ';}
                           echo '>'.utf8_encode($row['razon_comclientes']).'</option>';
                         } ?>
                        </select>
                      </div>
                    </div>
                    <div class="row">
                      <div class=" col-2">
                          <input type="number" class="form-control cantidad" min="1" max="100" value="" placeholder="Cantidad" id="cantidad" >
                      </div>
                      <div class=" col-2">
                          <input type="number" class="form-control bonifica" min="0" max="100" value="" placeholder="Bonificacion" id="bonifica" >
                      </div>
                      <div class=" col-7">

                                    <select class="form-control product" id="product_list" placeholder="Seleccione un producto">
                                      <?php
                                      while($row = mysqli_fetch_array($consul_prod)){
                                        echo '<option value="'.$row['id_producto'].'@'.$row['codigo_producto'].'@'.$row['detalle_producto'].'@'.$row['precio_producto'].'">'.$row['codigo_producto'].' - '.$row['detalle_producto'].'</option>';
                                      } ?>
                                    </select>

                            </div>

                        <div class=" col-1">
                          <button class="btn btn-success btn-block" type="button" onclick="insertaprod();"><i class="fa fa-plus"></i></button>
                        </div>

                      </div><br>

                      <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th width="50px" class="text-right">Cantidad</th>
                                        <th width="50px" class="text-right">Bonif.</th>
                                        <th width="350px" class="text-right">Codigo</th>
                                        <th class="text-right">Descripcion</th>
                                        <th class="text-right">P. Unitario</th>
                                        <th class="text-right">Total</th>
                                        <th class="text-right">Acc.</th>
                                        <th class="text-center">#</th>
                                    </tr>
                                </thead>
                                <tbody id="items_prod">
                                    <tr class="reset">
                                        <td colspan="8" class="text-center"><center>Aun no se cargaron productos</center></td>
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
                            <hr/><br>
                            <div class="col-md-12 m-b-20">
                              <textarea placeholder="ingrese el detalle del pedido" rows="5" onchange="updatedetalle()" class="form-control detalle" id="detallepedido" ></textarea>
                            </div>
                      <div>
                 
                    </div>



                  <a onclick="edita_pedido_save()" class="btn btn-info waves-effect">Guardar Cambios</a>
                  <a href="?pagina=pedidos" class="btn btn-danger waves-effect">Cancelar</a>
          </div>
</form>

</div>
</div>
</div>
</div>
<script>

var carrito=<?php echo json_encode($carrito);?>;
console.log(carrito);
//console.log(carrito);
function addCommas(nStr){
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
.on('select.editable-select', function (e, li) {

$('#comercio').val(li.val());
carrito.cliente_pedido = li.val();
});
$('#product_list')
.editableSelect()
.on('select.editable-select', function (e, li) {

$('#product').val(li[0].attributes[0].value);
});
$('.product2').editableSelect();



function insertaprod(){

var desarmo =   $('#product').val().split('@');
var id= desarmo[0];
var detalle = desarmo[2];
var precio =desarmo[3];
var codigo =desarmo[1];
var cantidad = $('#cantidad').val();
var bonifica = $('#bonifica').val();
var preciosiva = precio;
// var item = $('#items_prod')[0].childElementCount;
if(cantidad > 0 && codigo!=undefined && ($("#comercio").val()!='' && $("#comercio").val()!=undefined)){
var calc_total = parseFloat(precio) * parseFloat(cantidad);
calc_total = parseFloat(calc_total) + (parseFloat(calc_total) * 0.21);
precio =  parseFloat(precio) + (parseFloat(precio) * 0.21);
carrito.cliente_pedido= $("#comercio").val(),
carrito.fecha_pedido= $("#fechapedido").val();
if(!carrito.items){carrito.items= [];}
precio = precio.toFixed(2);
console.log('Precio_ Final: '+precio);
carrito.items.push({
  id: id,
  detalle: detalle,
  precio: precio,
  preciosiva: preciosiva,
  codigo: codigo,
  cantidad: cantidad,
  bonifica: bonifica,
  calc_total: calc_total.toFixed(2)
});

dibujacarrito();

}else{
  console.log('comercio: '+$("#comercio").val())
  console.log('codigo: '+codigo)
  console.log('cantidad: '+cantidad)
  alert('Seleccione un producto y cantidad')
}
}

function updatedetalle(){
carrito.detalle_pedido = $("#detallepedido").val();
}

function dibujacarrito(){

//  console.log(carrito);
if(carrito.items.length > 0){


  var g_subtotal = 0;
  var g_total = 0;
$('#items_prod').html('');
for(var i=0; i < carrito.items.length; i++){
var lineaitem = '<tr><td class="text-right"><input type="number" onchange="changeitem('+i+',\'cantidad\')" class="text-right form-control" value="'+carrito.items[i].cantidad+'" min="1" id="cantidad_'+i+'"></td>'
+'<td class="text-right"><input type="number" onchange="changeitem('+i+',\'bonifica\')" class="text-right form-control" value="'+carrito.items[i].bonifica+'" id="bonifica_'+i+'"></td>'
+'<td class="text-right"><input type="hidden" id="product_'+i+'" value="0"><select class="form-control product2" onchange="changeitem('+i+',\'codigo\')" id="product_list_'+i+'" required>'
+'    <?php
     mysqli_data_seek($consul_prod, 0);
    while($row = mysqli_fetch_array($consul_prod)){
      echo '<option value="'.$row['id_producto'].'@'.$row['codigo_producto'].'@'.$row['detalle_producto'].'@'.$row['precio_producto'].'">'.$row['codigo_producto'].' - '.$row['detalle_producto'].'</option>';
    } ?>'
  +'</select></td>'
  +'<td class="text-right">'+carrito.items[i].detalle+'</td>'
  +'<td class="text-right">$ '+carrito.items[i].precio+'</td>'
  +'<td class="text-right">$ '+addCommas(carrito.items[i].calc_total)+'</td>' //+'<td class="text-right">$ '+addCommas(carrito.items[i].calc_total.toFixed(2))+'</td>'
  +'<td class="text-right"><a class="btn-pure btn-outline-danger delete-row-btn btn-lg" style="padding:0px;" href="javascript:void(0)" onclick="delitem('+i+')" ><i class="ti-close" aria-hidden="true"></i></a></td>'
  +'<td class="text-center">'+(parseFloat(i)+parseFloat(1))+'</td>';

  $('#items_prod').append(lineaitem);
  var valorselect= carrito.items[i].id+'@'+carrito.items[i].codigo+'@'+carrito.items[i].detalle+'@'+carrito.items[i].preciosiva;
  console.log('valorselect: '+valorselect);
  $('#product_list_'+i).val(valorselect);
  $('#product').val('');
  $('#product_list').val('');
  $('#cantidad').val('');
  $('#bonifica').val('');
  g_subtotal = parseFloat(g_subtotal) + parseFloat(carrito.items[i].calc_total);
  //$('#product_'+i).val('79');
  var iva1 = g_subtotal * 0.21;

  g_total = parseFloat(g_subtotal);// + parseFloat(iva1);
  $('#sub_total').html(addCommas(g_subtotal.toFixed(2)));
  $('#total').html(addCommas(g_total.toFixed(2)));

}
carrito.total_pedido=g_total;
  } else{
    $('#items_prod').html('<tr class="reset"><td colspan="6" class="text-center"><center>Aun no se cargaron productos</center></td></tr>')
    $('#sub_total').html('0');
    $('#total').html('0');
  }
}

function delitem(i){
  carrito.items.splice(i, 1,)
  dibujacarrito()
}

function edita_pedido_save(){

var items_json = JSON.stringify(carrito.items);
   $.ajax({
   url: "procesos/pedidos.php?",
   data:'a=edit&id=<?php echo $id;?>&c='+carrito.cliente_pedido+'&f='+carrito.fecha_pedido+'&d='+carrito.detalle_pedido+'&t='+carrito.total_pedido+'&i='+items_json,
   type: "POST",

   success: function(data){
     if(data=='TRUE'){
  window.location="index.php?pagina=pedidos";
} else {alert('Error Al Editar Pedido,')}
   }

   })

}
  dibujacarrito()

  function changeitem(id,tipo){

console.log('ID: '+id+'- Tipo: '+tipo)
    var valor ='';
    if(tipo=='cantidad'){ valor = $('#cantidad_'+id).val();}
    if(tipo=='bonifica'){ valor = $('#bonifica_'+id).val();}
    if(tipo=='codigo'){
      var descomponer = $('#product_list_'+id+' option:selected').val();
      console.log('Descomponer: '+descomponer)
      var desarmo =   descomponer.split('@');
      var ide= desarmo[0];
      var detalle = desarmo[2];
      var precio =desarmo[3];
      var codigo =desarmo[1];
      $('#product_'+id).val(ide);
      valor = codigo;

      var precioconiva = (parseFloat(precio) + (parseFloat(precio) * 0.21)).toFixed(2);
      carrito.items[id].id=ide;
      carrito.items[id].preciosiva=precio;
      carrito.items[id].precio=precioconiva;
      carrito.items[id].detalle=detalle;

  }


carrito.items[id][tipo]= valor;

if(tipo=='cantidad' || tipo=='codigo'){
  /* Recalcula Subtotal */

  console.log('Precio: '+precio);


carrito.items[id].calc_total = parseFloat(carrito.items[id].precio) * parseFloat(carrito.items[id].cantidad);
}

dibujacarrito();
  }

  function updateday(obj){

  carrito.fecha_pedido=obj.value+' 00:00:00';
  }

   $("#detallepedido").val(carrito.detalle_pedido);
   $("#comercio").val(carrito.cliente_pedido);
   
</script>
