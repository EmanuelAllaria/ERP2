<script src="./js/funciones.js?v=1"></script>
<div class="container-fluid">
  <div class="row page-titles">
    <div class="col-md-12">
      <h4 class="text-white">Listado de Comprobantes</h4>
    </div>
    <div class="col-md-6">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
        <li class="breadcrumb-item "><a href="#">Comprobantes</a></li>
      </ol>
    </div>
    <div class="col-md-6 text-right">
      <form class="app-search d-none d-md-block d-lg-block">
        <input type="text" class="form-control" placeholder="Buscar...">
      </form>
    </div>
  </div>

  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-body">
          <div class="row">
            <div class="col-md-2">
              <h4 class="card-title">Listado</h4>
            </div>
            <?php
            if (isset($_GET['d'])) {
              $desde = $_GET['d'];
            } else {
              $desde = date('Y-m-01');
            }
            if (isset($_GET['h'])) {
              $hasta = $_GET['h'];
            } else {
              $hasta = date('Y-m-d');
            }
            if (isset($_GET['p']) && $_GET['p'] !== '') {
              $vendedor = ' and proveedores.id_proveedor = ' . $_GET['p'];
            } else {
              $vendedor = '';
            }

            ?>
            <div class="col-md-2"><small class="form-control-feedback"> Desde </small>
              <input class="form-control filtro" type="date" id="d" name="d" value="<?php if (isset($_GET['d'])) {
                                                                                      echo $_GET['d'];
                                                                                    } else {
                                                                                      echo date('Y-m-01');
                                                                                    } ?>">
            </div>
            <div class="col-md-2"><small class="form-control-feedback"> Hasta </small>
              <input class="form-control filtro" type="date" id="h" name="h" value="<?php if (isset($_GET['h'])) {
                                                                                      echo $_GET['h'];
                                                                                    } else {
                                                                                      echo date('Y-m-d');
                                                                                    } ?>">
            </div>
            <div class="col-md-2"><small class="form-control-feedback"> Proveedor </small><br>
              <select class="form-control" id="vendedorsel">
                <option value='' selected>Todos</option>
                <?php
                $busca_vende = $link->query("SELECT razon_com_proveedor, id_proveedor FROM `proveedores` WHERE `estado_proveedor` LIKE '1' order by razon_com_proveedor asc");
                while ($row = mysqli_fetch_array($busca_vende)) {

                  echo '<option value="' . $row['id_proveedor'] . '"';
                  if (isset($_GET['p'])) {
                    if ($_GET['p'] == $row['id_proveedor']) {
                      echo ' selected ';
                    }
                  }

                  echo '>' . $row['razon_com_proveedor'] . '</option>';
                }
                ?>
              </select>

            </div>
            <div class="col-md-2" style="align-self: center;">
              <a href="#" onclick="filtrar_vende()" class="btn btn-info btn-lg" role="button">Filtrar</a>
            </div>
            <div class="col-md-2" style="align-self: center;">
              <?php if (isset($_GET['d']) || isset($_GET['h'])) { ?><a href="index.php?pagina=comprobantes">Quitar Filtros</a><?php } ?>
            </div>

          </div>
          <h6 class="card-subtitle"></h6>

          <div class="table-responsive">
            <table id="clientes_lista" class="table m-t-30 table-hover contact-list footable-loaded footable" data-page-size="10">
              <thead>
                <tr>
                  <th class="footable-sortable">Fecha<span class="footable-sort-indicator"></span></th>
                  <th class="footable-sortable">Proveedor<span class="footable-sort-indicator"></span></th>
                  <th class="footable-sortable">Tipo Comprobante<span class="footable-sort-indicator"></span></th>
                  <th class="footable-sortable">Nro Comprobante<span class="footable-sort-indicator"></span></th>
                  <th class="footable-sortable">Acciones<span class="footable-sort-indicator"></span></th>
                  <th class="footable-sortable">#<span class="footable-sort-indicator"></span></th>
                </tr>
              </thead>
              <tbody>
                <?php
                if (isset($_GET['d'])) {
                  $desde = $_GET['d'];
                } else {
                  $desde = date('Y-m-01');
                }
                if (isset($_GET['h'])) {
                  $hasta = $_GET['h'];
                } else {
                  $hasta = date('Y-m-d');
                }
                if (isset($_GET['p']) && $_GET['p'] !== '') {
                  $vendedor = ' and proveedores.id_proveedor = ' . $_GET['p'];
                } else {
                  $vendedor = '';
                }
                $con_compra = $link->query("
                    SELECT * FROM `compra_mercaderia`
                        left join proveedores on proveedores.id_proveedor = compra_mercaderia.prov_compram
                        left join tipo_comprobantes on compra_mercaderia.tipocom_compram = tipo_comprobantes.id_comprobantes
                             WHERE compra_mercaderia.estado_compram='1' and date(compra_mercaderia.fecha_compram) >= '$desde' and date(compra_mercaderia.fecha_compram) <= '$hasta' $vendedor order by compra_mercaderia.fecha_compram DESC");
                while ($row = mysqli_fetch_array($con_compra)) {
                  /*var_dump($row);*/
                ?>

                  <tr>
                    <td class="font-weight-normal"><span class="footable-toggle"></span><?php echo date('d/m/Y', strtotime($row['fecha_compram'])) ?></td>
                    <td class="font-weight-normal"><span class="footable-toggle"></span><?php echo $row['razon_com_proveedor'] ?></td>
                    <td class="font-weight-normal"><span class="footable-toggle"></span><?php echo $row['nombre_comprobantes'];
                                                                                        ?></td>
                    <td class="font-weight-normal"><span class="footable-toggle"></span><?php echo $row['numcom_compram'] ?></td>
                    <td>
                      <a class="btn-pure btn-outline-success success-row-btn btn-lg" style="padding:0px;" href="index.php?pagina=compras_view&id=<?= $row['id_compram'] ?>"><i class="ti-eye" aria-hidden="true"></i></a>
                      &nbsp;&nbsp;<a class="btn-pure btn-outline-info edit-row-btn btn-lg" style="padding:0px;" href="#" data-toggle="modal" data-target="#editComp_<?php echo $row['id_compram'] ?>" data-id="<?php echo $row['id_compram'] ?>" data-toggle="tooltip" data-original-title="Editar"><i class="ti-pencil" aria-hidden="true"></i></a>
                    </td>
                    <td class="font-weight-normal"><span class="footable-toggle"></span><?php echo $row['id_compram'] ?></td>
                  </tr>
                <?php
                }
                ?>
              </tbody>
              <tfoot>

                <tr>
                  <td colspan="7">
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
                  </td>
                </tr>
              </tfoot>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php
$con_compra2 = $link->query("
                    SELECT * FROM `compra_mercaderia`
                        left join proveedores on proveedores.id_proveedor = compra_mercaderia.prov_compram
                        left join tipo_comprobantes on compra_mercaderia.tipocom_compram = tipo_comprobantes.id_comprobantes
                             WHERE compra_mercaderia.estado_compram='1' and date(compra_mercaderia.fecha_compram) >= '$desde' and date(compra_mercaderia.fecha_compram) <= '$hasta' $vendedor order by compra_mercaderia.fecha_compram DESC");
while ($row2 = mysqli_fetch_array($con_compra2)) {
  /*  echo "<pre>";
  var_dump($row2['tipocom_compram']);
  echo "</pre>";*/
?>
  <div class="modal fade" id="editComp_<?php echo $row2['id_compram'] ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">
            <center>Datos del comprobante</center>
          </h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form id="form_edit_<?php echo $row2['id_compram']; ?>" method="post">
            <input type="hidden" name="id" value="<?php echo $row2['id_compram']; ?>">
            <div class="form-group">
              <div class="row p-t-20">
                <div class="col-md-6">
                  <?php
                  $proveedor = $row2['id_proveedor'];
                  $con_prov = $link->query("SELECT * FROM proveedores WHERE id_proveedor = '$proveedor' AND estado_proveedor = '1'");
                  while ($row = mysqli_fetch_array($con_prov)) {
                    $id_proveedor = $row['id_proveedor'];
                    $razon_com_proveedor = utf8_encode($row['razon_com_proveedor']);
                    $notas_proveedor = utf8_encode($row['notas_proveedor']);
                    $input_value = $razon_com_proveedor . ' (' . $notas_proveedor . ')';

                    echo '<div class="form-group">';
                    echo '<label for="proveedor_' . $id_proveedor . '">Proveedor</label>';
                    echo '<input type="text" id="proveedor_' . $id_proveedor . '" name="proveedor_' . $id_proveedor . '" class="form-control" value="' . $input_value . '" readonly="llenaprod();">';
                    echo '</div>';
                  }
                  ?>
                </div>
                <div class="col-md-6">
                  <label class="control-label">Fecha de Comprobante</label>
                  <input type="date" id="fecha_card" value="<?php echo date('Y-m-d') ?>" class="form-control ">
                </div>
                <div class="col-md-6">
                  <label class="control-label">Tipo Comprobante</label>
                  <select id="tipocompro_card" class="form-control">
                    <option value='' disabled>Selecione Tipo de Comprobante</option>
                    <?php
                    $comp = $row2['id_comprobantes'];
                    $con_tipocomp = $link->query("SELECT * FROM `tipo_comprobantes` WHERE `estado_comprobantes` = 1 ORDER BY `tipo_comprobantes`.`nombre_comprobantes` ASC");
                    while ($row = mysqli_fetch_array($con_tipocomp)) {
                      $id_comp = $row['id_comprobantes'];
                      $selected = ($id_comp == $comp) ? 'selected' : '';
                      echo '<option value="' . $id_comp . '" ' . $selected . '>' . $row['nombre_comprobantes'] . '</option>';
                    }
                    ?>
                  </select>
                </div>
                <div class="col-md-6">
                  <label class="control-label">Nº Comprobante</label>
                  <input type="number" id="comprobante_num_card" class="form-control " placeholder="Ingrese el Nº de comprobante">
                </div>
                <div class="col-md-6">
                  <label class="control-label">Producto</label>
                  <select id="producto_card" name="producto_card" class="form-control producto">
                    <?php
                    $proveedor = $row2['id_proveedor'];
                    echo '<option value="" disabled selected>Seleccione un Producto </option>';
                    $consul_prod = $link->query("SELECT * FROM `productos` WHERE `estado_producto` = 1 and proveedor_producto='$proveedor' order by codigo_producto ASC ") or die(mysqli_error());
                    while ($row = mysqli_fetch_array($consul_prod)) {
                      echo '<option value="' . $row['id_producto'] . '">' . $row['codigo_producto'] . ' - ' . $row['detalle_producto'] . ' (' . $row['presentacion_producto'] . ')</option>';
                    }
                    ?>
                  </select>
                </div>
                <div class="col-md-3">
                  <label class="control-label">Cantidad</label>
                  <input type="number" id="cantproducto_card" class="form-control " value="1" min="1">
                </div>
                <div class="col-md-3">
                  <div class="form-group" style="margin-top: 30px;">
                    <label class="control-label"></label>
                    <button onclick="llena_canasta_edit();" class="btn btn-success"> +</button>
                  </div>
                </div>
                <div id="list_prod_card" style="width:100%"></div>
              </div>

            </div>
        </div>
        <div class="modal-footer">
          <button type="button" onclick="editarPersonal(<?php echo $row2['id']; ?>);" class="btn btn-success">Guardar Cambios</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
        </div>
        </form>
      </div>
    </div>
  </div>

<?php
}
?>
<script>
  // bind change event to select
  function filtrar_vende() {
    var datodesde = $('#d').val(); // get selected value
    var datohasta = $('#h').val(); // get selected value
    var datovendedor = $('#vendedorsel option:selected').val(); // get selected value



    if (datodesde) { // require a URL
      window.location = 'index.php?pagina=comprobantes&d=' + datodesde + '&h=' + datohasta + '&p=' + datovendedor; // redirect
    }
    return false;
  };
</script>