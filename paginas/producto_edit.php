<?php
$id = $_GET['id'];
include_once 'inc/barcode.php';

$img  =  code128BarCode($id, 1);

ob_start();
imagepng($img);



$output_img    =  ob_get_clean();

?>
<div class="container-fluid">

  <div class="row page-titles">
    <div class="col-md-12">
      <h4 class="text-white">Editar Producto</h4>
    </div>
    <div class="col-md-6">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
        <li class="breadcrumb-item"><a href="index.php?pagina=productos">Productos</a></li>
        <li class="breadcrumb-item active">Editar</li>
      </ol>
    </div>
    <div class="col-md-6 text-right">
      <form class="app-search d-none d-md-block d-lg-block">
        <input type="text" class="form-control" placeholder="Buscar...">
      </form>
    </div>
  </div>

  <div class="row">
    <div class="col-md-3">
      <div class="card">
        <div class="card-body">
          <img id="imgprod" class=" img-responsive" src="img/product.png" alt="">
        </div>
      </div>
      <div class="card">
        <div class="card-body">
          <h4 class="card-title" style="text-align: center;"> <img src="data:image/png;base64,<?php echo base64_encode($output_img) ?>" class="img-responsive" alt="Codigo de Barra"></h4>
        </div>
      </div>
    </div>
    <div class="col-md-9">
      <div class="card">
        <div class="card-body">
          <h4 class="card-title">Datos del producto</h4>
          <div class="row">
            <div class="col-md-6 m-b-20">
              <input placeholder="Código" type="text" id="codigo" class="form-control codigo" required="">
            </div>
            <div class="col-md-6 m-b-20">
              <input placeholder="Nombre" type="text" id="nombre" class="form-control nombre" required="">
            </div>
            <div class="col-md-6 m-b-20">
              <input placeholder="Modelo" type="text" id="modelo" class="form-control modelo">
            </div>
            <div class="col-md-6 m-b-20">
              <input placeholder="Presentacion" type="text" id="presentacion" class="form-control presentacion">
            </div>
            <div class="col-md-12 m-b-20">
              <textarea placeholder="Descripción" rows="4" id="descripcion" class="form-control descripcion" required=""></textarea>
            </div>
            <div class="col-md-4 m-b-20">
              <select id="proveedor" class="form-control proveedor">
                <option>Proveedor</option>
              </select>
            </div>
            <div class="col-md-4 m-b-20">
              <select id="categoria" class="form-control categoria">
                <option>Categoria</option>
              </select>
            </div>
            <div class="col-md-4 m-b-20">
              <select id="estado" class="form-control estado">
                <option value="1">Activo</option>
                <option value="2">Inactivo</option>
              </select>
            </div>
            <div class="col-md-6 m-b-20">
              <input placeholder="Costo" type="number" id="costo" class="form-control costo">
            </div>
            <div class="col-md-6 m-b-20">
              <input placeholder="Utilidad" type="number" id="utilidad" class="form-control utilidad">
            </div>
            <div class="col-md-4 m-b-20">
              <input placeholder="Precio de Venta" step="any" type="number" id="pventa1" class="form-control pventa">
            </div>
            <div class="col-md-4 m-b-20">
              <input placeholder="Precio de Venta 2" type="number" id="pventa2" class="form-control pventa">
            </div>
            <div class="col-md-4 m-b-20">
              <input placeholder="Precio de Venta 3" type="number" id="pventa3" class="form-control pventa">
            </div>
            <div class="col-md-4 m-b-20">
              <input placeholder="Stock Inicial" type="number" id="stock" class="form-control stock">
            </div>
            <div class="col-md-4 m-b-20">
              <input placeholder="Stock Minimo" type="number" id="stockmin" class="form-control stockmin">
            </div>

          </div>

          <div class="form-group">
            <div class="col-md-12 m-b-20">
              <div class="fileupload btn btn-danger btn-rounded waves-effect waves-light">
                <center><span><i class="ion-upload m-r-5"></i>Cargar Imagen</span></center>
                <input type="file" class="upload">
              </div>
            </div>
          </div>

          <center>
            <a href="index.php?pagina=productos" class="btn btn-danger waves-effect" style="color:#FFF">Cerrar</a>
            <button type="button" id="" onclick="actualizaprod(<?php echo $_GET['id'] ?>)" class="btn btn-info waves-effect">Guardar Cambios</button>
          </center>

        </div>
      </div>
    </div>
  </div>

</div>





</div>
</div>
</div>


<script>
  $(document).ready(function() {
    llenaselect('proveedor');
    llenaselect('categoria');
    datosprod(<?php echo $_GET['id']; ?>);
  })



  function actualizaprod(id) {
    var codigo = $('#codigo').val();
    var nombre = $('#nombre').val();
    var modelo = $('#modelo').val();
    var presentacion = $('#presentacion').val();
    var descripcion = $('#descripcion').val();
    var fabricante = $('#proveedor option:selected').val();
    var categoria = $('#categoria option:selected').val();
    var estado = $('#estado').val();
    var costo = $('#costo').val();
    var utilidad = $('#utilidad').val();
    var pventa1 = $('#pventa1').val();
    var pventa2 = $('#pventa2').val();
    var pventa3 = $('#pventa3').val();
    var stock = $('#stock').val();
    var stockmin = $('#stockmin').val();
    var imagen = $('.upload')[0].files[0];

    var url = "procesos/productos.php?";

    var formData = new FormData();
    formData.append('a', 'actualiza');
    formData.append('id', id);
    formData.append('codigo', codigo);
    formData.append('nombre', nombre);
    formData.append('modelo', modelo);
    formData.append('presentacion', presentacion);
    formData.append('descripcion', descripcion);
    formData.append('fabricante', fabricante);
    formData.append('categoria', categoria);
    formData.append('estado', estado);
    formData.append('costo', costo);
    formData.append('utilidad', utilidad);
    formData.append('pventa1', pventa1);
    formData.append('pventa2', pventa2);
    formData.append('pventa3', pventa3);
    formData.append('stock', stock);
    formData.append('stockmin', stockmin);
    formData.append('imagen', imagen);

    $.ajax({
      type: "POST",
      url: url,
      data: formData,
      processData: false,
      contentType: false,
      success: function(data) {
        if (data == 'TRUE') {
          window.location.href = 'index.php?pagina=productos';
        } else {
          console.log(data);
        }
      }
    })
  }

  function datosprod(id) {
    console.log('entra a datos')
    var url = "procesos/productos.php?";
    var string = 'accion=datosprod&id=' + id;
    $.ajax({
      type: "POST",
      dataType: "JSON",
      url: url,
      data: string,
      success: function(data) {
        if (data != 'FALSE') {
          console.log(data.data)
          console.log('Inserta: ' + data.data.foto)
          $("#codigo").val(data.data.codigo)
          $("#nombre").val(data.data.nombre)
          $("#modelo").val(data.data.modelo)
          $("#presentacion").val(data.data.presentacion)
          $("#descripcion").val(data.data.descripcion)
          $("#proveedor").val(data.data.fabricante)
          $("#categoria").val(data.data.categoria)
          $("#estado").val(data.data.estado)
          $("#costo").val(data.data.costo)
          $("#utilidad").val(data.data.utilidad)
          $("#pventa1").val(data.data.precio1)
          $("#pventa2").val(data.data.precio2)
          $("#pventa3").val(data.data.precio3)
          $("#stock").val(data.data.stock)
          $("#stockmin").val(data.data.stock_min)
          if (data.data.foto != '' && data.data.foto != 'undefined') {
            $('#imgprod').attr('src', 'img/prod/' + data.data.foto);
          } else {
            $('#imgprod').attr('src', '/img/product.png');
          }


        } else {
          alert('error al consultar producto');
        }

      }
    });

  }
</script>