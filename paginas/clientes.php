<div class="container-fluid">

  <div class="row page-titles">
    <div class="col-md-12">
      <h4 class="text-white">Listado de Clientes</h4>
    </div>
    <div class="col-md-6">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
        <li class="breadcrumb-item"><a href="index.php?pagina=clientes">Clientes</a></li>
        <?php if (isset($_GET['buscar'])) {
          echo '<li class="breadcrumb-item"><a href="#">Buscar: [' . $_GET['buscar'] . ']</a></li>';
        } ?>
      </ol>
    </div>
    <div class="col-md-6 text-right">
      <form class="app-search d-none d-md-block d-lg-block" method="get">
        <input type="hidden" name="pagina" value="clientes">
        <input type="text" id="buscador" name="buscar" class="form-control" placeholder="Buscar...">
      </form>
    </div>
  </div>

  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-body">
          <?php if (isset($_GET['buscar'])) {
            echo '<h4 class="card-title">Resultados de [' . $_GET['buscar'] . ']...</h4>';
          } else {
            echo '<h4 class="card-title">Listado</h4>';
          } ?>
          <div class="text-right" style="display: flex;align-items: center;flex-direction: row;justify-content: flex-end;gap: 1em;">
            <a href="procesos/exportar_clientes.php" class="btn btn-success">Exportar a Excel</a>
            <form action="procesos/importar_clientes.php" method="post" enctype="multipart/form-data" style="display: flex;align-items: stretch;flex-direction: column;justify-content: flex-end;width: 15em;">
              <input type="file" name="excel_file" accept=".xlsx">
              <button type="submit" class="btn btn-primary">Importar desde Excel</button>
            </form>
          </div>


          <h6 class="card-subtitle"></h6>
          <div class="table-responsive">
            <table id="clientes_lista" class="table m-t-30 table-hover contact-list footable-loaded footable" data-page-size="10">
              <thead>
                <tr>
                  <td colspan="6">
                    <a href="index.php?pagina=clientes_add" class="btn btn-info btn-rounded">Cargar nuevo Cliente</a>
                  </td>
                </tr>
                <tr>
                  <!--  <th class="footable-sortable">#<span class="footable-sort-indicator"></span></th> -->
                  <th>Cliente</th>
                  <th>Telefono</th>
                  <th>Direccion </th>
                  <th>Saldo</th>
                  <th>Estado </th>
                  <th>Acciones&nbsp;&nbsp;&nbsp;</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
                <?php
                $busqueda = '';
                if (isset($_GET['buscar'])) {
                  $palabra = $_GET['buscar'];
                  $busqueda = "AND (apellido_clientes LIKE '%$palabra%' OR nombre_clientes LIKE '%$palabra%' OR cuitcuil_com_clientes LIKE '%$palabra%' OR dni_clientes LIKE '%$palabra%' OR razon_com_clientes LIKE '%$palabra%' OR direccion_clientes LIKE '%$palabra%')";
                }
                $con_clientes = $link->query("SELECT * FROM clientes LEFT JOIN ciudad ON ciudad.id_ciudad = clientes.ciudad_clientes WHERE estado_clientes != '0' $busqueda ORDER BY apellido_clientes ASC");

                while ($row = mysqli_fetch_array($con_clientes)) {
                  $id = $row['id_clientes'];
                  $acumula_pagos = 0;
                  $acumula_pedidos = 0;
                  $saldo = 0;

                  $cuenta_corriente = $link->query("SELECT * FROM transaccion WHERE cliente = '$id' AND estado = 1");
                  while ($cc1 = mysqli_fetch_array($cuenta_corriente)) {
                    // Verificar cada transacción
                    if ($cc1['tipo'] == 'cheque_rechazado') {
                      $acumula_pedidos += $cc1['monto'];
                      $saldo += $cc1['monto'];
                    }
                    if ($cc1['tipo'] == 'pago') {
                      $acumula_pagos += $cc1['monto2'];
                      $saldo -= $cc1['monto2'];
                    }
                    if ($cc1['tipo'] == 'pedido') {
                      $acumula_pedidos += $cc1['monto'];
                      $saldo += $cc1['monto'];
                    }
                  }
                  $alerta = false;
                  $consulta_corriente = $link->query("SELECT * FROM transaccion WHERE cliente ='$id' 
                  and id > (SELECT MAX(id) from transaccion where tipo = 'pago' and cliente ='$id')
                  AND estado = 1 order by id LIMIT 1");
                  while ($cc = mysqli_fetch_array($consulta_corriente)) {
                    if ($cc['tipo'] == 'pedido') {
                      $fecha_actual = time(); // Timestamp actual
                      $fecha_cc = strtotime($cc['fecha']); // Timestamp de la fecha del CC
                      $dias_financiacion = $row['dias_financiacion']; // Número de días de financiación

                      // Calcula la diferencia en segundos entre las fechas
                      $diferencia_segundos = $fecha_actual - $fecha_cc;

                      // Convierte los segundos en días
                      $diferencia_dias = floor($diferencia_segundos / (60 * 60 * 24));

                      // Compara con el número de días de financiación
                      if ($diferencia_dias > $dias_financiacion) $alerta = true;
                    }
                  }
                ?>
                  <tr <?php if ($alerta) echo " class='bg-danger'" ?>>
                    <!--  <td><span class="footable-toggle"></span><?php echo $row['id_comclientes'] ?></td> -->
                    <td class="font-weight-normal">
                      <a href="index.php?pagina=clientes_view&id=<?php echo $row['id_clientes'] ?>" style="color:#078bdb">

                        <?php
                        echo mb_strtoupper($row['razon_com_clientes']);
                        ?>
                      </a>
                    </td>

                    <td>
                      <a href="tel:<?php echo $row['celular_clientes']; ?>" style="color:#078bdb">
                        <?php echo $row['celular_clientes']; ?>
                      </a>
                    </td>
                    <td class="font-weight-normal"><?php echo $row['direccion_clientes'] . ', ' . $row['dirnum_clientes'] . ' ( ' . $row['ciudad_alias'] . ' )' ?></td>
                    <td>$ <?php echo number_format($saldo, 0, '', '.'); ?>
                    </td>
                    <td class="font-weight-normal"><span class="label label-<?php if ($row['estado_clientes'] == '2') {
                                                                              echo 'danger">Inactivo';
                                                                            } else {
                                                                              echo 'success">Activo';
                                                                            } ?></span></td>
                                                <td >
                                                  <a class=" btn-pure btn-outline-success view-row-btn btn-lg" style="padding:0px;" href="index.php?pagina=clientes_view&id=<?php echo $row['id_clientes'] ?>" data-toggle="tooltip" data-original-title="Ver"><i class="ti-eye" aria-hidden="true"></i></a>
                        <?php if ($_SESSION['tipo'] != 'User' && $row['id_clientes'] != '1') { ?> &nbsp;&nbsp;<a class="btn-pure btn-outline-info edit-row-btn btn-lg" style="padding:0px;" href="index.php?pagina=clientes_edit&id=<?php echo $row['id_clientes'] ?>" data-toggle="tooltip" data-original-title="Editar"><i class="ti-pencil" aria-hidden="true"></i></a>
                          &nbsp;&nbsp;<a class="btn-pure btn-outline-danger delete-row-btn btn-lg" style="padding:0px;" href="#" data-toggle="modal" data-target="#del_<?php echo $row['id_clientes'] ?>" data-original-title="Borrar"><i class="ti-close" aria-hidden="true"></i></a>
                        <?php } ?>
                    </td>


                    <td class="font-weight-normal">
                      <a onclick="envia_productos_wsp(<?php echo $row['lista_precio'] ?>,'<?php echo $row['celular_clientes'] ?>')" class="btn btn-success"> <img style="margin-left:5px;height:15px;" alt="Page principale de WhatsApp" src="https://static.whatsapp.net/rsrc.php/ym/r/36B424nhiL4.svg"></a>

                      <?php if ($row['estado_clientes'] == '1') { ?>
                        <button class="switch on" onclick="desactivarCliente(<?= $row['id_clientes'] ?>)"></button>
                      <?php } else { ?>
                        <button class="switch" onclick="activarCliente(<?= $row['id_clientes'] ?>)"></button>
                      <?php } ?>
                    </td>
                  </tr>
                <?php echo '
                                            <div class="modal fade" id="del_' . $row['id_clientes'] . '" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                              <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                  <div class="modal-header">

                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                                                      <span aria-hidden="true">&times;</span>
                                                    </button>
                                                  </div>
                                                  <div class="modal-body">
                                                    <h4 >Seguro que desea eliminar el cliente "' . $row['razon_com_clientes'] . '" ?</h4>
                                                  </div>
                                                  <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                                                    <button onclick="elimina_c(' . $row['id_clientes'] . ')" class="btn btn-primary">Si, Eliminar</button>
                                                  </div>
                                                </div>
                                              </div>
                                            </div>';
                } ?>
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

<script>
  $(function() {
    var availableTags = [<?php
                          mysqli_data_seek($con_clientes, 0);
                          while ($com_sul = mysqli_fetch_array($con_clientes)) {
                            echo '"' . $com_sul['razon_comclientes'] . '",';
                          } ?>]
    $("#buscador").autocomplete({
      source: availableTags
    });
  });
</script>