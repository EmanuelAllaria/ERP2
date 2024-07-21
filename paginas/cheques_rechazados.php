<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['rechazado']) && isset($_POST['id'])) {
  $rechazado = intval($_POST['rechazado']);
  $id = intval($_POST['id']);

  $updateQuery = "UPDATE facturas_cheques_rechazados SET rechazado = $rechazado WHERE id = $id";
  if ($link->query($updateQuery) === TRUE) {
    echo "Estado actualizado correctamente.";
  } else {
    echo "Error actualizando el estado: " . $link->error;
  }
}

$selectTotalFacturas = $link->query('SELECT SUM(monto) AS total_facturas, id_proveedor
              FROM facturas
              GROUP BY id_proveedor;');
$total_facturas = 0;
while ($row = mysqli_fetch_array($selectTotalFacturas)) {
  $total_facturas += intval($row['total_facturas']);
}
?>

<?php if (isset($_GET['add']) && $_GET['add'] == '1') {
  include('clientes_add.php');
} else { ?><div class="container-fluid">

    <div class="row page-titles">
      <div class="col-md-12">
        <h4 class="text-white">Listado de Cheques Rechazados</h4>
      </div>
      <div class="col-md-6">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
          <li class="breadcrumb-item"><a href="#">Cheques Rechazados</a></li>
        </ol>
      </div>
      <div class="col-md-6 text-right">
        <form class="app-search d-none d-md-block d-lg-block" method="get">
          <input type="hidden" name="pagina" value="cheques_rechazados">
          <input type="text" id="buscador" name="buscar" class="form-control" placeholder="Buscar...">
        </form>
      </div>
    </div>

    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-body">
            <h4 class="card-title">Listado</h4>
            <?php if (isset($_GET['buscar'])) {
              echo '<h5><a href="index.php?pagina=cheques_rechazados">Número de cheque: [' . $_GET['buscar'] . ']</a></h5>';
            } ?>
            <div class="row">
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
                <select class="form-control" id="proveedorsel">
                  <option value='' selected>Todos</option>
                  <?php
                  $busca_prov = $link->query("SELECT razon_com_proveedor as nombre, id_proveedor as id FROM `proveedores` WHERE `estado_proveedor` LIKE '1'");
                  while ($row = mysqli_fetch_array($busca_prov)) {
                    echo '<option value="' . $row['id'] . '"';
                    if (isset($_GET['p']) && $_GET['p'] == $row['id']) {
                      echo ' selected ';
                    }
                    echo '>' . $row['nombre'] . '</option>';
                  }
                  ?>
                </select>
              </div>
              <div class="col-md-2" style="margin-top:17px">
                <a href="#" onclick="filtrar_vende()" class="btn btn-info btn-lg" role="button">Filtrar</a>
                <?php if (isset($_GET['d']) || isset($_GET['h'])) { ?><a href="index.php?pagina=cheques_rechazados">Quitar Filtros</a><?php } ?>
              </div>

              <div class="col-md-2" style="align-self: center;">
                <div id="totales_formas">
                  <?php
                  $busqueda = '';
                  if (isset($_GET['buscar'])) {
                    $palabra = $_GET['buscar'];
                    $busqueda = "and numero_cheque like '%$palabra%'";
                  }
                  if (isset($_GET['d']) && $_GET['d'] != '') {
                    $desde = $_GET['d'];
                    $busqueda = $busqueda . ' and facturas_cheques_rechazados.fecha_emision >= "' . $desde . '"';
                  } else {
                    $desde = date('Y-m-01');
                  }
                  if (isset($_GET['h']) && $_GET['h'] != '') {
                    $hasta = date('Y-m-d', strtotime($_GET['h'] . ' +1 day'));;
                    $busqueda = $busqueda . ' and facturas_cheques_rechazados.fecha_emision <= "' . $hasta . '"';
                  } else {
                    $hasta = date('Y-m-d 23:59:59');
                  }
                  if (isset($_GET['p']) && $_GET['p'] != '') {
                    $busqueda = $busqueda . " and facturas_cheques_rechazados.id_proveedor = '" . $_GET['p'] . "'";
                  } else {
                    $vendedor = '';
                  }
                  ?>
                </div>

                <div id="total_periodo">Total $</div>
              </div>

            </div>
            <h6 class="card-subtitle"></h6>
            <div class="table-responsive">
              <table id="clientes_lista" class="table m-t-30 table-hover contact-list footable-loaded footable" data-page-size="10">
                <thead>
                  <tr style="width: 100%;">
                    <th>#</th>
                    <th>Número de Cheque</th>
                    <th>Proveedor</th>
                    <th>Titular</th>
                    <th>Banco</th>
                    <th>Cuit</th>
                    <th>Fecha Emisión</th>
                    <th>Fecha Cobro</th>
                    <th>Monto</th>
                    <th>ORIGEN</th>
                    <th>Acciones</th>
                  </tr>
                </thead>
                <tbody>
                  <?php

                  $sqlChequesRechazados = "SELECT facturas_cheques_rechazados.*, proveedores.razon_com_proveedor AS proveedor, clientes.nombre_clientes, clientes.apellido_clientes
                              FROM facturas_cheques_rechazados
                              LEFT JOIN proveedores ON facturas_cheques_rechazados.id_proveedor = proveedores.id_proveedor
                              LEFT JOIN clientes ON facturas_cheques_rechazados.origen = clientes.id_clientes
                              WHERE facturas_cheques_rechazados.id > 0
                              $busqueda
                              ORDER BY facturas_cheques_rechazados.id ASC";

                  $saldo_final = 0;

                  $con_facturas = $link->query($sqlChequesRechazados);

                  while ($row = mysqli_fetch_assoc($con_facturas)) {
                    $saldo_final += $row['monto'];
                    $id_pago = $row['id'];
                    $rechazado = intval($row['rechazado']) === 1 ? 0 : 1;
                  ?>
                    <tr style='width: 100%;'>
                      <td><?php echo $row['id'] ?></td>
                      <td><?php echo $row['numero_cheque'] ?></td>
                      <td><?php echo $row['proveedor'] ?></td>
                      <td><?php echo $row['titular'] ?></td>
                      <td><?php echo $row['banco'] ?></td>
                      <td><?php echo $row['cuit'] ?></td>
                      <td><?php echo $row['fecha_emision'] ?></td>
                      <td><?php echo $row['fecha_cobro'] ?></td>
                      <td><?php echo number_format($row['monto'], 2, ',', '.') ?></td>
                      <td><?php echo $row['nombre_clientes'] . " " . $row['apellido_clientes'] ?></td>
                      <td>
                        <form method='post'>
                          <input type='hidden' name='id' id="id-<?php echo $row['id'] ?>" value='<?php echo $row['id'] ?>'>
                          <label class='switch'>
                            <input name='rechazado' onchange="submitForm(<?php echo $row['id'] ?>, <?php echo $rechazado ?>)" type='checkbox' id='check-cheques-cancelados' <?php echo intval($row['rechazado']) === 1 ? '' : 'checked' ?> />
                            <span class='slider round-sweetch'></span>
                          </label>
                        </form>
                      </td>
                    </tr>
                  <?php
                  }
                  ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>


      </div>
    </div>

  </div>

  <script>
    $('#total_periodo').html('<span class="btn btn-success pull-right"><b>TOTAL: $<?php echo number_format($total_facturas - abs($saldo_final), 0, '', '.'); ?></b></span>')

    function filtrar_vende() {
      var datodesde = $('#d').val(); // get selected value
      var datohasta = $('#h').val(); // get selected value
      var datovendedor = $('#proveedorsel').val(); // get selected value
      if (datodesde) { // require a URL
        window.location = 'index.php?pagina=cheques_rechazados&d=' + datodesde + '&h=' + datohasta + '&p=' + datovendedor; // redirect
      }
      return false;
    };
  </script>
  <script>
    var click = 0;

    function mostrar_tr_cheques(id_tr) {
      click++;
      if (click % 2 === 1) {
        $('.' + id_tr).show();
      } else {
        $('.' + id_tr).hide();
      }
    }
  </script>
  <script>
    function submitForm(id, rechazado) {
      var formData = new FormData();
      formData.append('id', id);
      formData.append('rechazado', rechazado);

      fetch('', {
          method: 'POST',
          body: formData
        })
        .then(() => window.location.reload())
        .catch(error => {
          console.error('Error:', error);
        });
    }
  </script>
<?php } ?>