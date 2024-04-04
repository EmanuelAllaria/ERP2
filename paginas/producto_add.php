<div class="container-fluid">

  <div class="row page-titles">
    <div class="col-md-12">
      <h4 class="text-white">Crear nuevo Producto</h4>
    </div>
    <div class="col-md-6">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
        <li class="breadcrumb-item"><a href="index.php?pagina=productos">Productos</a></li>
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
    <div class="col-md-3">
      <div class="card">
        <div class="card-body">
          <img class=" img-responsive" src="img/product.png" alt="Bussines profile picture">
        </div>
      </div>
      <div class="card">
        <div class="card-body">
          <h4 class="card-title">barra</h4>
        </div>
      </div>
    </div>
    <div class="col-md-9">
      <div class="card">
        <div class="card-body">
          <h4 class="card-title">Ingrese los datos del producto</h4>
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
                <option disabled>Estado</option>
                <option value="1" selected>Activo</option>
                <option value="2">Inactivo</option>
              </select>
            </div>
            <div class="col-md-6 m-b-20">
              <input placeholder="Costo" type="number" id="costo" onchange="calculaventa()" class="form-control costo">
            </div>
            <div class="col-md-6 m-b-20">
              <input placeholder="Utilidad" type="number" id="utilidad" onchange="calculaventa()" class="form-control utilidad">
            </div>
            <div class="col-md-4 m-b-20">
              <input placeholder="Precio de Venta 1" type="number" id="pventa1" class="form-control pventa">
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
            <button type="button" id="" onclick="agrega_prod()" class="btn btn-info waves-effect">Crear Producto</button>
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
  })

  function calculaventa() {
    if ($('#costo').val() != '' && $('#utilidad').val() != '') {
      $('#pventa1').val(parseFloat($('#costo').val()) + (parseFloat($('#costo').val()) * parseFloat($('#utilidad').val()) / 100));
    }
  }

  function agrega_prod() {
    var codigo = $('#codigo').val();
    var nombre = $('#nombre').val();
    var modelo = $('#modelo').val();
    var presentacion = $('#presentacion').val();
    var descripcion = $('#descripcion').val();
    var proveedor = $('#proveedor option:selected').val();
    var categoria = $('#categoria option:selected').val();
    var estado = $('#estado option:selected').val();
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
    formData.append('a', 'add');
    formData.append('codigo', codigo);
    formData.append('nombre', nombre);
    formData.append('modelo', modelo);
    formData.append('presentacion', presentacion);
    formData.append('descripcion', descripcion);
    formData.append('proveedor', proveedor);
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
          alert(data);
        }
      }

    })

  }
</script>