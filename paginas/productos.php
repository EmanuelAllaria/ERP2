<div class="container-fluid">

  <div class="row page-titles">
    <div class="col-md-12">
      <h4 class="text-white">Listado de Productos</h4>
    </div>
    <div class="col-md-6">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
        <li class="breadcrumb-item"><a href="index.php?pagina=productos">Productos</a></li>
        <?php if (isset($_GET['buscar'])) {
          echo '<li class="breadcrumb-item"><a href="#">Buscar: [' . $_GET['buscar'] . ']</a></li>';
        } ?>
      </ol>
    </div>
    <div class="col-md-6 text-right">
      <form class="app-search d-none d-md-block d-lg-block" method="get">
        <input type="hidden" name="pagina" value="productos">
        <input type="text" id="buscador" name="buscar" class="form-control" placeholder="Buscar...">
      </form>
    </div>
  </div>

  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-body">
          <?php if (isset($_GET['buscar'])) {
            echo '<h4 class="card-title">Resulados de [' . $_GET['buscar'] . ']...</h4>';
          } else {
            echo '<h4 class="card-title">Listado</h4>';
          } ?>


          <h6 class="card-subtitle"></h6>
          <div class="table-responsive">
            <table id="clientes_lista" class="table m-t-30 table-hover contact-list footable-loaded footable" data-page-size="10">
              <thead>
                <tr>
                  <!--  <th class="footable-sortable">#<span class="footable-sort-indicator"></span></th> -->
                  <th>Cod.</th>
                  <th>Producto</th>
                  <th>Proveedor</th>
                  <th>Precio </th>
                  <th>Precio2 </th>
                  <th>Precio3 </th>
                  <th>Categoria </th>
                  <th>Marca </th>
                  <th>Stock </th>
                  <th>Acciones&nbsp;&nbsp;&nbsp;</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $busqueda = '';
                if (isset($_GET['buscar'])) {
                  $palabra = $_GET['buscar'];
                  $busqueda = "and (descripcion_producto like '%$palabra%' or presentacion_producto like '%$palabra%' or detalle_producto like '%$palabra%' or modelo_producto like '%$palabra%' )";
                }
                //$con_clientes = $link->query("SELECT * FROM clientes inner join clientes_comercios on clientes_comercios.cliente_comclientes = clientes.id_clientes INNER join ciudad on ciudad.id_ciudad = clientes.ciudad_clientes where clientes_comercios.estado_comclientes ='1' order by clientes.apellido_clientes, clientes.nombre_clientes ASC ");
                $con_productos = $link->query("SELECT * FROM productos
                                              left join categorias on categorias.id_categoria = productos.categoria_producto
                                              left join marcas on marcas.id_marca = productos.marca_producto
                                              left join proveedores on proveedores.id_proveedor = productos.proveedor_producto
                                              WHERE estado_producto ='1' $busqueda order by codigo_producto ASC ");
                while ($row = mysqli_fetch_array($con_productos)) {
                  if ($row['logo_marca'] != '0' && $row['logo_marca'] != '') {
                    $logo = $row['logo_marca'];
                  } else {
                    $logo = 'sinlogo.png';
                  }
                ?>
                  <tr>
                    <!--  <td><span class="footable-toggle"></span><?php echo $row['id_comclientes'] ?></td> -->
                    <td>
                      <a href="index.php?pagina=prod_view&id=<?php echo $row['id_producto'] ?>" style="color:#262626;" class="font-weight-normal"><?php echo $row['codigo_producto'] ?></a>
                    </td>
                    <td><?php echo $row['detalle_producto'] . ' ' . $row['modelo_producto'] . ' ' . $row['presentacion_producto']; ?></td>
                    <td><?php
                        $razon_com_proveedor = isset($row['razon_com_proveedor']) ? htmlspecialchars($row['razon_com_proveedor']) : '';
                        $notas_proveedor = isset($row['notas_proveedor']) ? htmlspecialchars($row['notas_proveedor']) : '';
                        echo $razon_com_proveedor . ' (' . $notas_proveedor . ')';
                        ?></td>
                    <td class="font-weight-normal">
                      <div class="input-group mb-3">
                        <div class="input-group-prepend">
                          <span class="input-group-text" id="basic-addon1"><i class="fa fa-usd"></i></span>
                        </div>
                        <input onchange="cambia_precio(<?php echo $row['id_producto']; ?>)" type="number" id="precio_<?php echo $row['id_producto']; ?>" data-base="<?php echo $row['precio_producto'] ?>" step="any" value="<?php echo $row['precio_producto'] ?>" class="form-control" aria-label="Precio" aria-describedby="basic-addon1">
                        <div class="input-group-append" style="display:none" id="chk_btn_<?php echo $row['id_producto']; ?>">
                          <span class="input-group-text" id="basic-addon1"><button onclick="upd_precio(<?php echo $row['id_producto']; ?>, 1)" class="btn btn-success"> <i class="fa fa-check"></i></button> </span>
                        </div>
                      </div>
                    </td>
                    <td class="font-weight-normal">
                      <div class="input-group mb-3">
                        <div class="input-group-prepend">
                          <span class="input-group-text" id="basic-addon2"><i class="fa fa-usd"></i></span>
                        </div>
                        <input onchange="cambia_precio('<?php echo $row['id_producto']; ?>b')" type="number" id="precio_<?php echo $row['id_producto']; ?>b" data-base="<?php echo $row['precio_producto2'] ?>" step="any" value="<?php echo $row['precio_producto2'] ?>" class="form-control" aria-label="Precio" aria-describedby="basic-addon2">
                        <div class="input-group-append" style="display:none" id="chk_btn_<?php echo $row['id_producto']; ?>b">
                          <span class="input-group-text" id="basic-addon2"><button onclick="upd_precio('<?php echo $row['id_producto']; ?>b', 2)" class="btn btn-success"> <i class="fa fa-check"></i></button> </span>
                        </div>
                      </div>
                    </td>
                    <td class="font-weight-normal">
                      <div class="input-group mb-3">
                        <div class="input-group-prepend">
                          <span class="input-group-text" id="basic-addon3"><i class="fa fa-usd"></i></span>
                        </div>
                        <input onchange="cambia_precio('<?php echo $row['id_producto']; ?>c')" type="number" id="precio_<?php echo $row['id_producto']; ?>c" data-base="<?php echo $row['precio_producto3'] ?>" step="any" value="<?php echo $row['precio_producto3'] ?>" class="form-control" aria-label="Precio" aria-describedby="basic-addon3">
                        <div class="input-group-append" style="display:none" id="chk_btn_<?php echo $row['id_producto']; ?>c">
                          <span class="input-group-text" id="basic-addon3"><button onclick="upd_precio('<?php echo $row['id_producto']; ?>c', 3)" class="btn btn-success"> <i class="fa fa-check"></i></button> </span>
                        </div>
                      </div>
                    </td>
                    <td class="font-weight-normal"><span class="label" style="background-color:<?php echo $row['color_categoria'] ?>"><?php echo $row['titulo_categoria'] ?></span></td>
                    <td class="font-weight-normal"><img src="img/logos/<?php echo $logo ?>" style="max-width: 100px;max-height: 40.5px;"></td>
                    <td class="font-weight-normal"><span class="label label-<?php if ($row['stock_producto'] < 15) {
                                                                              echo 'primary';
                                                                            } else {
                                                                              echo 'success';
                                                                            } ?>"><?php echo $row['stock_producto'] ?></span></td>
                    <td>
                      <!-- <a class="btn-pure btn-outline-success view-row-btn btn-lg" style="padding:0px;" href="index.php?pagina=prod_view&id=<?php echo $row['id_producto'] ?>"  data-toggle="tooltip" data-original-title="Ver"><i class="ti-eye" aria-hidden="true"></i></a>-->
                      &nbsp;&nbsp;<a class="btn-pure btn-outline-info edit-row-btn btn-lg" style="padding:0px;" href="index.php?pagina=producto_edit&id=<?php echo $row['id_producto'] ?>" data-toggle="tooltip" data-original-title="Editar"><i class="ti-pencil" aria-hidden="true"></i></a>
                      &nbsp;&nbsp;<a class="btn-pure btn-outline-danger delete-row-btn btn-lg" style="padding:0px;" href="#" data-toggle="modal" data-target="#del_<?php echo $row['id_producto'] ?>" data-original-title="Borrar"><i class="ti-close" aria-hidden="true"></i></a>
                    </td>
                  </tr>
                <?php echo '
                                            <div class="modal fade" id="del_' . $row['id_producto'] . '" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                              <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                  <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                                                      <span aria-hidden="true">&times;</span>
                                                    </button>
                                                  </div>
                                                  <div class="modal-body">
                                                    <h4 >Seguro que desea desactivar el producto "' . $row['codigo_producto'] . '" ?</h4>
                                                  </div>
                                                  <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                                                    <button onclick="elimina_p(' . $row['id_producto'] . ')" class="btn btn-primary">Si, Desactivar</button>
                                                  </div>
                                                </div>
                                              </div>
                                            </div>';
                } ?>
              </tbody>
            </table>
            <div class="d-flex flex-row align-items-center justify-content-between">
              <?php if ($_SESSION['tipo'] != 'User') { ?> <a href="index.php?pagina=producto_add" class="btn btn-info btn-rounded">Cargar nuevo Producto</a> <?php } ?>
              <div class="text-right">
                <ul class="pagination">
                  <li class="footable-page-arrow disabled"><a data-page="first" href="#first">«</a></li>
                  <li class="footable-page-arrow disabled"><a data-page="prev" href="#prev">‹</a></li>
                  <li class="footable-page active"><a data-page="0" href="#">1</a></li>
                  <li class="footable-page"><a data-page="1" href="#">2</a></li>
                  <li class="footable-page-arrow"><a data-page="next" href="#next">›</a></li>
                  <li class="footable-page-arrow"><a data-page="last" href="#last">»</a></li>
                </ul>
              </div>
              <a href="#" class="btn btn-info btn-rounded" style="background: none;" onclick="imprimirTabla()"><i class="fa fa-print" style="color: #000;"></i></a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  function imprimirTabla() {
    var contenidoTabla = document.getElementById("clientes_lista").outerHTML;
    var ventanaImpresion = window.open('', '_blank', 'width=800,height=600');
    ventanaImpresion.document.write('<html><head><title>Imprimir Tabla</title></head><body>');
    ventanaImpresion.document.write('<h1>Listado de Productos</h1>');
    ventanaImpresion.document.write(contenidoTabla);
    ventanaImpresion.document.write('</body></html>');
    ventanaImpresion.document.close();
    ventanaImpresion.print();
  }
</script>

<script>
  $(function() {
    var availableTags = [<?php
                          mysqli_data_seek($con_productos, 0);
                          while ($com_prod = mysqli_fetch_array($con_productos)) {
                            echo '"' . $com_prod['descripcion_producto'] . '",';
                          } ?>]
    $("#buscador").autocomplete({
      source: availableTags
    });
  });

  function elimina_p(id) {
    $.ajax({
      url: "procesos/productos.php?",
      data: 'a=delp&id=' + id,
      type: "POST",
      success: function(data) {
        if (data != 'FALSE') {

        }
      }

    })

  }


  function cambia_precio(id) {
    console.log(id);
    console.log($('#precio_' + id).val());
    console.log($('#precio_' + id).data('base'));
    if ($('#precio_' + id).val() == $('#precio_' + id).data('base')) {
      $('#chk_btn_' + id).hide();
    } else {
      $('#chk_btn_' + id).show();
    }


  }

  function upd_precio(id, cual_pre) {
    var precio = $('#precio_' + id).val();
    $.ajax({
      url: "procesos/productos.php?",
      data: 'a=updateprecio&id=' + id + '&pre=' + precio + '&cual_pre=' + cual_pre,
      type: "POST",
      success: function(data) {
        if (data == 'TRUE') {
          $('#chk_btn_' + id).hide();
        } else {
          alert('No se pudo actualizar el precio');
        }
      }

    })


  }
</script>