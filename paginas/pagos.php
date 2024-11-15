<div class="container-fluid">
    <div class="row page-titles">
        <div class="col-md-12">
            <h4 class="text-white">Listado de Pagos</h4>
        </div>
        <div class="col-md-6">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
                <li class="breadcrumb-item"><a href="index.php?pagina=pagos">Pagos</a></li>
                <?php if (isset($_GET['buscar'])) {
                    echo '<li class="breadcrumb-item"><a href="#">Buscar: [' . htmlspecialchars($_GET['buscar']) . ']</a></li>';
                } ?>
            </ol>
        </div>
        <div class="col-md-6 text-right">
            <form class="app-search d-none d-md-block d-lg-block" method="get">
                <input type="hidden" name="pagina" value="pagos">
                <input type="text" id="buscador" name="buscar" class="form-control" placeholder="Buscar..." value="<?php echo isset($_GET['buscar']) ? htmlspecialchars($_GET['buscar']) : ''; ?>">
            </form>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">
                        <?php echo isset($_GET['buscar']) ? 'Busqueda de vendedor: [' . htmlspecialchars($_GET['buscar']) . ']...' : 'Listado'; ?>
                    </h4>

                    <div class="table-responsive">
                        <table id="facturas_lista" class="table m-t-30 table-hover contact-list footable-loaded footable" data-page-size="10">
                            <thead>
                                <tr>
                                    <td colspan="12">
                                        <div class="row">
                                            <div class="col-md-2">
                                                <small class="form-control-feedback"> Desde </small>
                                                <input class="form-control filtro" type="date" id="d" name="d" value="<?php echo isset($_GET['d']) ? htmlspecialchars($_GET['d']) : date('Y-m-01'); ?>">
                                            </div>
                                            <div class="col-md-2">
                                                <small class="form-control-feedback"> Hasta </small>
                                                <input class="form-control filtro" type="date" id="h" name="h" value="<?php echo isset($_GET['h']) ? htmlspecialchars($_GET['h']) : date('Y-m-d'); ?>">
                                            </div>
                                            <div class="col-md-2">
                                                <small class="form-control-feedback"> Vendedor </small><br>
                                                <select class="form-control" id="proveedorsel">
                                                    <option value='' selected>Todos</option>
                                                    <?php
                                                    $busca_prov = $link->query("SELECT CONCAT(nombre,', ',apellido) as nombre, id FROM `personal` WHERE `estado` LIKE '1' AND `area` LIKE 'reparto' order by nombre asc, apellido ASC");
                                                    while ($row = mysqli_fetch_assoc($busca_prov)) {
                                                        $selected = (isset($_GET['p']) && $_GET['p'] == $row['id']) ? ' selected' : '';
                                                        echo '<option value="' . htmlspecialchars($row['id']) . '"' . $selected . '>' . htmlspecialchars($row['nombre']) . '</option>';
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                            <div class="col-md-2"><small class="form-control-feedback">Forma pago </small><br>
                                              <select class="form-control" id="formasel">
                                                <option value='' selected>Todas</option>
                                                <?php
                                                $busca_f = $link->query("SELECT * FROM `formas_pagos` WHERE `estado_formapago` LIKE '1'");
                                                while ($row = mysqli_fetch_array($busca_f)) {
                                                  echo '<option value="' . (strtolower($row['id_formapago'])) . '"';
                                                  if (isset($_GET['f']) && $_GET['f'] == strtolower($row['detalle_formapago'])) {
                                                    echo ' selected ';
                                                  }
                                                  echo '>' . $row['detalle_formapago'] . '</option>';
                                                }
                                                ?>
                                              </select>

                                            </div>
                                            <div class="col-md-2" style="align-self: center;">
                                                <a href="#" onclick="filtrar_prov()" class="btn btn-info btn-lg" role="button">Filtrar</a>
                                            </div>
                                            <?php if (isset($_GET['d']) || isset($_GET['h']) || isset($_GET['p'])) { ?>
                                                <div class="col-md-2" style="align-self: center;">
                                                    <a href="index.php?pagina=pagos" class="btn btn-secondary">Quitar Filtros</a>
                                                </div>
                                            <?php } ?>
                                            <div id="total_periodo">Total $</div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>#</th>
                                    <th>Vendedor</th>
                                    <th>Cliente</th>
                                    <th>Tipo de pago</th>
                                    <th>Nro Cheque</th>
                                    <th>Fecha</th>
                                    <th>Monto</th>
                                    <th>Observaciones</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="lista_facturas">
                                <?php
                                // Preparar la consulta
                                $busqueda = '';
                                if (isset($_GET['buscar'])) {
                                    $palabra = $link->real_escape_string($_GET['buscar']);
                                    $busqueda .= " AND personal.nombre = '$palabra'";
                                }
                                if (isset($_GET['d']) && $_GET['d'] != '') {
                                    $desde = $link->real_escape_string($_GET['d']);
                                    $busqueda .= " AND transaccion.fecha >= '$desde'";
                                } else {
                                    $desde = date('Y-m-01');
                                }
                                if (isset($_GET['h']) && $_GET['h'] != '') {
                                    $hasta = date('Y-m-d', strtotime($link->real_escape_string($_GET['h']) . ' +1 day'));
                                    $busqueda .= " AND transaccion.fecha <= '$hasta'";
                                } else {
                                    $hasta = date('Y-m-d 23:59:59');
                                }
                                if (isset($_GET['p']) && $_GET['p'] != '') {
                                    $v_id = (int)$_GET['p'];
                                    $busqueda .= " AND transaccion.quien = $v_id";
                                }

                                if (isset($_GET['f']) && $_GET['f'] != '') {
                                    $f_id = (int)$_GET['f'];
                                    $busqueda .= " AND transaccion.forma_pago = $f_id";
                                }

                                // Realizar la consulta
                                $sql = "SELECT transaccion.id as id_t,transaccion.monto2,transaccion.fecha,transaccion.fecha,transaccion.detalle, clientes.*, personal.*,formas_pagos.* FROM `transaccion`
                                            inner join clientes on transaccion.cliente = clientes.id_clientes
                                            inner join personal on transaccion.quien = personal.id
                                            inner join formas_pagos on transaccion.forma_pago = formas_pagos.id_formapago
                                            WHERE transaccion.estado='1' and transaccion.tipo ='pago' and date(transaccion.fecha) >= '$desde' and date(transaccion.fecha) <= '$hasta'  $busqueda order by transaccion.fecha DESC";

                                $con_pedidos = $link->query($sql);
                                $acumula = 0;
                                $contador = 1;

                                while ($row = mysqli_fetch_assoc($con_pedidos)) {
                                    $acumula += $row['monto2'];
                                    $id_pago = $row['id_t'];
                                    echo "<tr>";
                                    echo "<td>{$contador}</td>";
                                    echo "<td>{$row['apellido']}, {$row['nombre']}</td>";
                                    echo "<td>{$row['razon_com_clientes']}</td>";
                                    echo "<td>{$row['detalle_formapago']}</td>";
                                    echo "<td></td>";
                                    echo "<td>{$row['fecha']}</td>";
                                    echo "<td>$" . number_format($row['monto2'], 2, ',', '.') . "</td>";
                                    echo "<td>{$row['detalle']}</td>";
                                    echo "<td>";

                                    // Mostrar botón si es un cheque
                                    if ($row['detalle_formapago'] == 'Cheque') {
                                        echo '<button onclick="mostrar_tr_cheques(`tr_cheques_' . $id_pago . '`)" class="btn btn-info btn-lg">Ver Cheques</button>';
                                    }
                                    echo "</td>";
                                    echo "</tr>";

                                    // Si es cheque, mostrar cheques asociados
                                    if ($row['detalle_formapago'] == 'Cheque') {
                                        $pagos_cheque = $link->query("SELECT * FROM pagos_cheque WHERE id_pago = $id_pago");

                                        while ($row_cheque = mysqli_fetch_assoc($pagos_cheque)) {
                                            echo "<tr style='display:none;' class='tr_cheques_$id_pago'>";
                                            echo "<td>{$row_cheque['id']}</td>";
                                            echo "<td>{$row['apellido']}, {$row['nombre']}</td>";
                                            echo "<td>{$row['razon_com_clientes']}</td>";
                                            echo "<td>{$row['detalle_formapago']}</td>";
                                            echo "<td>{$row_cheque['nro_cheque']}</td>";
                                            echo "<td>{$row['fecha']}</td>";
                                            echo "<td>$" . number_format($row_cheque['monto'], 2, ',', '.') . "</td>";
                                            echo "<td>{$row['detalle']}</td>";
                                            echo "</tr>";  
                                        }
                                    }

                                    $contador++;
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
    function filtrar_prov() {
        const urlParams = new URLSearchParams(window.location.search);
        urlParams.set('pagina', 'pagos');

        const desde = document.getElementById('d').value;
        const hasta = document.getElementById('h').value;
        const prov = document.getElementById('proveedorsel').value;

        var datoforma = $('#formasel').val();

        if (desde) urlParams.set('d', desde);
        if (hasta) urlParams.set('h', hasta);
        if (prov) urlParams.set('p', prov);
        if (datoforma) urlParams.set('f', datoforma);

        window.location.search = urlParams.toString();
    }

    document.getElementById('total_periodo').innerHTML = `<span class="btn btn-danger pull-right"><b>TOTAL: $<?php echo number_format($acumula, 0, '', '.'); ?></b></span>`;
</script>
<script>
    function editarPersonal(id) {
        if (
            $('#telefono-edit-' + id).val() == '' ||
            $('#direccion-edit-' + id).val() == '' ||
            $('#area-edit-' + id).val() == ''
        ) {
            alert('Complete todos los campos');
        } else {

            var string = "accion=editarPersonal&area=" + $('#area-edit-' + id).val() + "&telefono=" + $('#telefono-edit-' + id).val() + "&direccion=" + $('#direccion-edit-' + id).val() + "&id=" + id;
            $.ajax({
                type: "POST",
                url: "procesos/crud.php?",
                data: string,
                success: function(data) {
                    if (data == 'TRUE') {
                        alert('El personal se editó correctamente');
                        window.location.href = "index.php?pagina=personal";
                    } else {
                        alert('No se pudo editar el personal');
                        console.log(data);
                    }
                }
            });
        }
    }
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