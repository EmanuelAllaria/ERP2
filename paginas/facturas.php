<div class="container-fluid">
    <div class="row page-titles">
        <div class="col-md-12">
            <h4 class="text-white">Listado de Facturas</h4>
        </div>
        <div class="col-md-6">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
                <li class="breadcrumb-item"><a href="index.php?pagina=clientes">Facturas</a></li>
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

                    <div class="table-responsive">
                        <table id="facturas_lista" class="table m-t-30 table-hover contact-list footable-loaded footable" data-page-size="10">
                            <thead>
                                <tr>
                                    <td colspan="12">
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
                                            <div class="col-md-2" style="align-self: center;">
                                                <a href="#" onclick="filtrar_prov()" class="btn btn-info btn-lg" role="button">Filtrar</a>
                                            </div>
                                            <?php if (isset($_GET['d']) || isset($_GET['h']) || isset($_GET['p'])) { ?>
                                                <div class="col-md-2" style="align-self: center;"><a href="index.php?pagina=facturas">Quitar Filtros</a></div>
                                            <?php } ?>

                                            <div id="total_periodo">Total $</div>
                                        </div>
                                    </td>
                                </tr>
                                <tr style="width: 100%;">
                                    <th style="width: 20%;">Número de Factura</th>
                                    <th style="width: 20%;">Proveedor</th>
                                    <th style="width: 20%;">Fecha</th>
                                    <th style="width: 20%;">Monto</th>
                                    <th style="width: 20%;">Observaciones</th>
                                </tr>
                            </thead>
                            <tbody id="lista_facturas">
                                <?php
                                $busqueda = '';
                                $busquedaProveedor = '';
                                if (isset($_GET['buscar'])) {
                                    $palabra = $_GET['buscar'];
                                    $busqueda = "and (nro_factura like '%$palabra%' or tipo like '%$palabra%' )";
                                }
                                if (isset($_GET['d']) && $_GET['d'] != '') {
                                    $desde = $_GET['d'];
                                    $busqueda = $busqueda . ' and facturas.fecha >= "' . $desde . '"';
                                } else {
                                    $desde = date('Y-m-01');
                                }
                                if (isset($_GET['h']) && $_GET['h'] != '') {
                                    $hasta = date('Y-m-d', strtotime($_GET['h'] . ' +1 day'));;
                                    $busqueda = $busqueda . ' and facturas.fecha <= "' . $hasta . '"';
                                } else {
                                    $hasta = date('Y-m-d 23:59:59');
                                }
                                if (isset($_GET['p']) && $_GET['p'] != '') {
                                    $busqueda = $busqueda . ' and facturas.id_proveedor = ' . $_GET['p'];
                                } else {
                                    $vendedor = '';
                                }
                                if (isset($_GET['proveedor']) && $_GET['proveedor'] != '') {
                                    $busquedaProveedor = " and facturas.id_proveedor = '" . $_GET['proveedor'] . "'";
                                } else {
                                    $vendedor = '';
                                }

                                $sqlDeuda = "SELECT facturas.*, proveedores.razon_com_proveedor AS proveedor, tipo_comprobantes.nombre_comprobantes
                                            FROM facturas
                                            LEFT JOIN proveedores ON facturas.id_proveedor = proveedores.id_proveedor
                                            LEFT JOIN tipo_comprobantes ON facturas.tipo = tipo_comprobantes.id_comprobantes
                                            WHERE facturas.id > 0
                                            $busquedaProveedor
                                            $busqueda
                                            ORDER BY facturas.fecha ASC";

                                $saldo_final = 0;

                                $con_facturas = $link->query($sqlDeuda);

                                while ($row = mysqli_fetch_assoc($con_facturas)) {
                                    $saldo_final += $row['monto'];
                                    echo "<tr style='width: 100%;'>";
                                    echo "<td style='width: 20%;word-break: break-all;'>{$row['nro_factura']}</td>";
                                    echo "<td style='width: 20%;word-break: break-all;'>{$row['proveedor']}</td>";
                                    echo "<td style='width: 20%;word-break: break-all;'>{$row['fecha']}</td>";
                                    echo "<td style='width: 20%;word-break: break-all;'>$" . number_format($row['monto'], 2, ',', '.') . "</td>";
                                    echo "<td style='width: 20%;word-break: break-all;'>{$row['observaciones']}</td>";
                                    echo "</tr>";
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

    $('#total_periodo').html('<span class="btn btn-danger pull-right"><b>TOTAL: $<?php echo number_format($saldo_final, 0, '', '.'); ?></b></span>')
</script>