<div class="container-fluid">
    <div class="row page-titles">
        <div class="col-md-12">
            <h4 class="text-white">Listado de Facturas</h4>
        </div>
        <div class="col-md-6">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
                <li class="breadcrumb-item"><a href="index.php?pagina=facturas">Facturas</a></li>
                <?php if (isset($_GET['buscar'])) {
                    echo '<li class="breadcrumb-item"><a href="#">Buscar: [' . htmlspecialchars($_GET['buscar']) . ']</a></li>';
                } ?>
            </ol>
        </div>
        <div class="col-md-6 text-right">
            <form class="app-search d-none d-md-block d-lg-block" method="get">
                <input type="hidden" name="pagina" value="facturas">
                <input type="text" id="buscador" name="buscar" class="form-control" placeholder="Buscar..." value="<?php echo isset($_GET['buscar']) ? htmlspecialchars($_GET['buscar']) : ''; ?>">
            </form>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">
                        <?php echo isset($_GET['buscar']) ? 'Busqueda de N° de Factura: [' . htmlspecialchars($_GET['buscar']) . ']...' : 'Listado'; ?>
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
                                                <small class="form-control-feedback"> Proveedor </small><br>
                                                <select class="form-control" id="proveedorsel">
                                                    <option value='' selected>Todos</option>
                                                    <?php
                                                    $busca_prov = $link->query("SELECT razon_com_proveedor AS nombre, id_proveedor AS id FROM proveedores WHERE estado_proveedor = 1");
                                                    while ($row = mysqli_fetch_assoc($busca_prov)) {
                                                        $selected = (isset($_GET['p']) && $_GET['p'] == $row['id']) ? ' selected' : '';
                                                        echo '<option value="' . htmlspecialchars($row['id']) . '"' . $selected . '>' . htmlspecialchars($row['nombre']) . '</option>';
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                            <div class="col-md-2" style="align-self: center;">
                                                <a href="#" onclick="filtrar_prov()" class="btn btn-info btn-lg" role="button">Filtrar</a>
                                            </div>
                                            <?php if (isset($_GET['d']) || isset($_GET['h']) || isset($_GET['p'])) { ?>
                                                <div class="col-md-2" style="align-self: center;">
                                                    <a href="index.php?pagina=facturas" class="btn btn-secondary">Quitar Filtros</a>
                                                </div>
                                            <?php } ?>
                                            <div id="total_periodo">Total $</div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>#</th>
                                    <th>N° de Factura</th>
                                    <th>Proveedor</th>
                                    <th>Tipo</th>
                                    <th>Fecha</th>
                                    <th>Monto</th>
                                    <th>Acciones</th>
                                    <th>Observaciones</th>
                                </tr>
                            </thead>
                            <tbody id="lista_facturas">
                                <?php
                                // Preparar la consulta
                                $busqueda = '';
                                if (isset($_GET['buscar'])) {
                                    $palabra = $link->real_escape_string($_GET['buscar']);
                                    $busqueda .= " AND nro_factura = '$palabra'";
                                }
                                if (isset($_GET['d']) && $_GET['d'] != '') {
                                    $desde = $link->real_escape_string($_GET['d']);
                                    $busqueda .= " AND facturas.fecha >= '$desde'";
                                } else {
                                    $desde = date('Y-m-01');
                                }
                                if (isset($_GET['h']) && $_GET['h'] != '') {
                                    $hasta = date('Y-m-d', strtotime($link->real_escape_string($_GET['h']) . ' +1 day'));
                                    $busqueda .= " AND facturas.fecha <= '$hasta'";
                                } else {
                                    $hasta = date('Y-m-d 23:59:59');
                                }
                                if (isset($_GET['p']) && $_GET['p'] != '') {
                                    $proveedor_id = (int)$_GET['p'];
                                    $busqueda .= " AND facturas.id_proveedor = $proveedor_id";
                                }

                                // Realizar la consulta
                                $sqlDeuda = "SELECT facturas.*, proveedores.razon_com_proveedor AS proveedor, tipo_comprobantes.nombre_comprobantes, compra_mercaderia.id_compram
                                             FROM facturas
                                             LEFT JOIN proveedores ON facturas.id_proveedor = proveedores.id_proveedor
                                             LEFT JOIN tipo_comprobantes ON facturas.tipo = tipo_comprobantes.id_comprobantes
                                             LEFT JOIN compra_mercaderia ON facturas.nro_factura = compra_mercaderia.numcom_compram
                                             WHERE facturas.id > 0 $busqueda
                                             ORDER BY facturas.fecha ASC";

                                $con_facturas = $link->query($sqlDeuda);
                                $saldo_final = 0;

                                while ($row = mysqli_fetch_assoc($con_facturas)) {
                                    $saldo_final += $row['monto'];
                                    echo "<tr>";
                                    echo "<td>{$row['id']}</td>";
                                    echo "<td>{$row['nro_factura']}</td>";
                                    echo "<td>{$row['proveedor']}</td>";
                                    echo "<td>{$row['nombre_comprobantes']}</td>";
                                    echo "<td>{$row['fecha']}</td>";
                                    echo "<td>$" . number_format($row['monto'], 2, ',', '.') . "</td>";
                                    echo "<td>
                                            <a class='btn btn-outline-danger' href='#' data-toggle='modal' data-target='#del_{$row['id']}' title='Borrar'>
                                                <i class='fa fa-trash'></i>
                                            </a>
                                            <a class='btn btn-outline-info' href='index.php?pagina=compras_view&id={$row['id_compram']}'>
                                                <i class='ti-eye'></i>
                                            </a>
                                            <a class='btn btn-outline-warning' href='#' data-toggle='modal' data-target='#editComp_{$row['id_compram']}' title='Editar'>
                                                <i class='ti-pencil'></i>
                                            </a>
                                          </td>";
                                    echo "<td>{$row['observaciones']}</td>";
                                    echo "</tr>";

                                    // Modal de confirmación de eliminación
                                    echo "
                                    <div class='modal fade' id='del_{$row['id']}' tabindex='-1' role='dialog' aria-labelledby='exampleModalLabel' aria-hidden='true'>
                                        <div class='modal-dialog' role='document'>
                                            <div class='modal-content'>
                                                <div class='modal-header'>
                                                    <h5 class='modal-title'>Confirmar Eliminación</h5>
                                                    <button type='button' class='close' data-dismiss='modal' aria-label='Cerrar'>
                                                        <span aria-hidden='true'>&times;</span>
                                                    </button>
                                                </div>
                                                <div class='modal-body'>
                                                    <h4>¿Seguro que desea eliminar la factura Nro {$row['id']}?</h4>
                                                </div>
                                                <div class='modal-footer'>
                                                    <button type='button' class='btn btn-secondary' data-dismiss='modal'>No</button>
                                                    <button onclick='elimina_f({$row['id']})' class='btn btn-danger'>Sí, Eliminar</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>";

                                    // Modal para editar comprobante
                                    echo "
                                    <div class='modal fade' id='editComp_{$row['id_compram']}' tabindex='-1' role='dialog' aria-labelledby='exampleModalLabel' aria-hidden='true'>
                                        <div class='modal-dialog' role='document'>
                                            <div class='modal-content'>
                                                <div class='modal-header'>
                                                    <h4 class='modal-title'>Datos del Comprobante</h4>
                                                    <button type='button' class='close' data-dismiss='modal' aria-label='Cerrar'>
                                                        <span aria-hidden='true'>&times;</span>
                                                    </button>
                                                </div>
                                                <div class='modal-body'>
                                                    <form id='form_edit_{$row['id_compram']}' method='post'>
                                                        <input type='hidden' name='id' value='{$row['id_compram']}'>
                                                        <div class='form-group'>
                                                            <div class='row p-t-20'>
                                                                <div class='col-md-6'>";
                                    $proveedor = $row['id_proveedor'];
                                    $con_prov = $link->query("SELECT * FROM proveedores WHERE id_proveedor = '$proveedor' AND estado_proveedor = 1");
                                    if ($prov_row = mysqli_fetch_array($con_prov)) {
                                        $razon_com_proveedor = htmlspecialchars($prov_row['razon_com_proveedor']);
                                        $notas_proveedor = htmlspecialchars($prov_row['notas_proveedor']);
                                        $input_value = "$razon_com_proveedor ($notas_proveedor)";
                                        echo "
                                                                    <label for='proveedor_$proveedor'>Proveedor</label>
                                                                    <input type='text' id='proveedor_$proveedor' class='form-control' value='$input_value' readonly>";
                                    }
                                    echo "
                                                                </div>
                                                                <div class='col-md-6'>
                                                                    <label class='control-label'>Fecha de Comprobante</label>
                                                                    <input type='date' id='fecha_card' value='" . date('Y-m-d') . "' class='form-control'>
                                                                </div>
                                                                <div class='col-md-6'>
                                                                    <label class='control-label'>Tipo Comprobante</label>
                                                                    <select id='tipocompro_card' class='form-control'>
                                                                        <option value='' disabled>Seleccione Tipo de Comprobante</option>";
                                    $comp = $row['id_comprobantes'];
                                    $con_tipocomp = $link->query("SELECT * FROM tipo_comprobantes WHERE estado_comprobantes = 1 ORDER BY nombre_comprobantes ASC");
                                    while ($tipo_row = mysqli_fetch_array($con_tipocomp)) {
                                        $selected = ($tipo_row['id_comprobantes'] == $comp) ? 'selected' : '';
                                        echo "<option value='{$tipo_row['id_comprobantes']}' $selected>{$tipo_row['nombre_comprobantes']}</option>";
                                    }
                                    echo "
                                                                    </select>
                                                                </div>
                                                                <div class='col-md-6'>
                                                                    <label class='control-label'>Nº Comprobante</label>
                                                                    <input type='number' id='comprobante_num_card' class='form-control' placeholder='Ingrese el Nº de comprobante' required>
                                                                </div>
                                                                <div class='col-md-6'>
                                                                    <label class='control-label'>Producto</label>
                                                                    <select id='producto_card' name='producto_card' class='form-control'>
                                                                        <option value='' disabled selected>Seleccione un Producto</option>";
                                    $consul_prod = $link->query("SELECT * FROM productos WHERE estado_producto = 1 AND proveedor_producto = '$proveedor' ORDER BY codigo_producto ASC");
                                    while ($prod_row = mysqli_fetch_array($consul_prod)) {
                                        echo "<option value='{$prod_row['id_producto']}'>{$prod_row['codigo_producto']} - {$prod_row['detalle_producto']} ({$prod_row['presentacion_producto']})</option>";
                                    }
                                    echo "
                                                                    </select>
                                                                </div>
                                                                <div class='col-md-3'>
                                                                    <label class='control-label'>Cantidad</label>
                                                                    <input type='number' id='cantproducto_card' class='form-control' value='1' min='1' required>
                                                                </div>
                                                                <div class='col-md-3'>
                                                                    <div class='form-group' style='margin-top: 30px;'>
                                                                        <button type='button' onclick='llena_canasta_edit();' class='btn btn-success'> +</button>
                                                                    </div>
                                                                </div>
                                                                <div id='list_prod_card' style='width:100%'></div>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                                <div class='modal-footer'>
                                                    <button type='button' onclick='editarPersonal({$row['id']});' class='btn btn-success'>Guardar Cambios</button>
                                                    <button type='button' class='btn btn-secondary' data-dismiss='modal'>Cancelar</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>";
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
        urlParams.set('pagina', 'facturas');

        const desde = document.getElementById('d').value;
        const hasta = document.getElementById('h').value;
        const prov = document.getElementById('proveedorsel').value;

        if (desde) urlParams.set('d', desde);
        if (hasta) urlParams.set('h', hasta);
        if (prov) urlParams.set('p', prov);

        window.location.search = urlParams.toString();
    }

    document.getElementById('total_periodo').innerHTML = `<span class="btn btn-danger pull-right"><b>TOTAL: $<?php echo number_format($saldo_final, 0, '', '.'); ?></b></span>`;
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