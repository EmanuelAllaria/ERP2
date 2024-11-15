<div class="container-fluid">
    <div class="row page-titles">
        <div class="col-md-12">
            <h4 class="text-white">Listado de Fiscales</h4>
        </div>
        <div class="col-md-6">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
                <li class="breadcrumb-item"><a href="index.php?pagina=fiscal">Fiscales</a></li>
                <?php if (isset($_GET['buscar'])) {
                    echo '<li class="breadcrumb-item"><a href="#">Buscar: [' . htmlspecialchars($_GET['buscar']) . ']</a></li>';
                } ?>
            </ol>
        </div>
        <div class="col-md-6 text-right">
            <form class="app-search d-none d-md-block d-lg-block" method="get">
                <input type="hidden" name="pagina" value="fiscal">
                <input type="text" id="buscador" name="buscar" class="form-control" placeholder="Buscar..." value="<?php echo isset($_GET['buscar']) ? htmlspecialchars($_GET['buscar']) : ''; ?>">
            </form>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">
                        <?php echo isset($_GET['buscar']) ? 'Busqueda : [' . htmlspecialchars($_GET['buscar']) . ']...' : 'Listado'; ?>
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
                                            <div class="col-md-2">
                                                <small class="form-control-feedback"> Sociedad </small><br>
                                                <select id="sociedad_select" name="titular" class="form-control">
                                                    <option value="PADSA BAHIA SA">PADSA BAHIA SA</option>
                                                    <option value="DPA GROUP SA">DPA GROUP SA</option>
                                                </select>
                                            </div>
                                            <div class="col-md-2">
                                                <small class="form-control-feedback"> Ingresa a Cuenta </small><br>
                                                <select id="cc_select" name="titular" class="form-control">
                                                    <option value="1">SI</option>
                                                    <option value="0">NO</option>
                                                </select>
                                            </div>
                                            <div class="col-md-2" style="align-self: center;">
                                                <a href="#" onclick="filtrar_prov()" class="btn btn-info btn-lg" role="button">Filtrar</a>
                                            </div>
                                            <?php if (isset($_GET['d']) || isset($_GET['h']) || isset($_GET['p'])) { ?>
                                                <div class="col-md-2" style="align-self: center;">
                                                    <a href="index.php?pagina=fiscal" class="btn btn-secondary">Quitar Filtros</a>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>#</th>
                                    <th>Proveedor</th>
                                    <th>Tipo</th>
                                    <th>Sociedad</th>
                                    <th>Subtotal</th>
                                    <th>I.V.A 27%</th>
                                    <th>I.V.A 21%</th>
                                    <th>I.V.A 10,5%</th>
                                    <th>Perc. I.V.A</th>
                                    <th>IIBB</th>
                                    <th>Imp. Interno</th>
                                    <th>Tasas</th>
                                    <th>Total</th>
                                    <th>Ingreso a Cuenta Corriente?</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="lista_facturas">
                                <?php
                                // Preparar la consulta
                                $busqueda = '';
                                if (isset($_GET['buscar'])) {
                                    $palabra = $link->real_escape_string($_GET['buscar']);
                                    $busqueda .= " AND compra_mercaderia.id_compram = '$palabra'";
                                }
                                if (isset($_GET['d']) && $_GET['d'] != '') {
                                    $desde = $link->real_escape_string($_GET['d']);
                                    $busqueda .= " AND compra_mercaderia.fecha_compram >= '$desde'";
                                } else {
                                    $desde = date('Y-m-01');
                                }
                                if (isset($_GET['h']) && $_GET['h'] != '') {
                                    $hasta = date('Y-m-d', strtotime($link->real_escape_string($_GET['h']) . ' +1 day'));
                                    $busqueda .= " AND compra_mercaderia.fecha_compram <= '$hasta'";
                                } else {
                                    $hasta = date('Y-m-d 23:59:59');
                                }
                                if (isset($_GET['p']) && $_GET['p'] != '') {
                                    $proveedor_id = (int)$_GET['p'];
                                    $busqueda .= " AND compra_mercaderia.prov_compram = $proveedor_id";
                                }

                                if (isset($_GET['c']) && $_GET['c'] != '') {
                                    $cc = (int)$_GET['c'];
                                    $busqueda .= " AND compra_mercaderia.ingresacuenta_corriente = $cc";
                                }

                                if (isset($_GET['s']) && $_GET['s'] != '') {
                                    $sociedad = $_GET['s'];
                                    $busqueda .= " AND impuesto_compra_mercaderia.sociedad = '$sociedad'";
                                }

                                // Realizar la consulta
                                $sql = "SELECT * FROM `compra_mercaderia`
                                        left join proveedores on proveedores.id_proveedor = compra_mercaderia.prov_compram
                                        left join impuesto_compra_mercaderia on compra_mercaderia.id_compram = impuesto_compra_mercaderia.id_compra_mercaderia
                                        left join tipo_comprobantes on compra_mercaderia.tipocom_compram = tipo_comprobantes.id_comprobantes WHERE compra_mercaderia.estado_compram = 1 $busqueda ORDER BY compra_mercaderia.id_compram DESC";

                                $con_facturas = $link->query($sql);
                                $total_subtotal = 0;
                                $total_iva_27 = 0;
                                $total_iva_21 = 0;
                                $total_iva_10_5 = 0;
                                $total_per_iva = 0;
                                $total_iibb = 0;
                                $total_it = 0;
                                $total_tasa = 0;
                                $total_total = 0;
                                while ($row = mysqli_fetch_assoc($con_facturas)) {
                                    $subtotal = 0;
                                    $total_per_iva += $row['per_iva'];
                                    $total_iibb += $row['iibb'];
                                    $total_it += $row['imp_interno'];
                                    $total_tasa += $row['tasa'];
                                    $total_total += $row['total_compra'];

                                    if ($row['por_iva'] == 27) {
                                        $total_iva_27 += $row['iva'];
                                    } elseif ($row['por_iva'] == 21) {
                                        $total_iva_21 += $row['iva'];
                                    } elseif ($row['por_iva'] == 10.5) {
                                        $total_iva_10_5 += $row['iva'];
                                    }

                                    $consul_items = $link->query("
                                        SELECT * FROM `productos_comprados`
                                        LEFT JOIN productos ON productos.id_producto = productos_comprados.idProducto
                                        WHERE productos_comprados.idCMercaderia = {$row['id_compram']}
                                    ");



                                    while ($row2 = mysqli_fetch_array($consul_items)){
                                        $subtotal += $row2['importe'];
                                    }

                                    $total_subtotal += $subtotal;

                                    if ($row['ingresacuenta_corriente'] == 0) {
                                        $cc = "NO";
                                    }else{
                                        $cc = "SI";
                                    }

                                    echo "<tr>";
                                    echo "<td>{$row['id_compram']}</td>";
                                    echo "<td>{$row['razon_com_proveedor']}</td>";
                                    echo "<td>{$row['nombre_comprobantes']}</td>";
                                    echo "<td>{$row['sociedad']}</td>";
                                    echo "<td>$" . number_format($subtotal, 2, ',', '.') . "</td>";

                                    if ($row['por_iva'] == 27.0) {
                                        echo "<td>$" . number_format($row['iva'], 2, ',', '.') . "</td>";
                                        echo "<td></td>";
                                        echo "<td></td>";
                                    }else if ($row['por_iva'] == 21.0) {
                                        echo "<td></td>";
                                        echo "<td>$" . number_format($row['iva'], 2, ',', '.') . "</td>";
                                        echo "<td></td>";
                                    }else{
                                        echo "<td></td>";
                                        echo "<td></td>";
                                        echo "<td>$" . number_format($row['iva'], 2, ',', '.') . "</td>";
                                    }
                                    
                                    echo "<td>$" . number_format($row['per_iva'], 2, ',', '.') . "</td>";
                                    echo "<td>$" . number_format($row['iibb'], 2, ',', '.') . "</td>";
                                    echo "<td>$" . number_format($row['imp_interno'], 2, ',', '.') . "</td>";
                                    echo "<td>$" . number_format($row['tasa'], 2, ',', '.') . "</td>";
                                    echo "<td>$" . number_format($row['total_compra'], 2, ',', '.') . "</td>";
                                    echo "<td>{$cc}</td>";
                                    echo "<td>
                                            <a class='btn btn-outline-info' href='index.php?pagina=compras_view&id={$row['id_compram']}'>
                                                <i class='ti-eye'></i>
                                            </a>
                                          </td>";

                                }
                                ?>
                            </tbody>

                            <tfoot>
                                <tr>
                                    <td colspan="2"><strong>Totales</strong></td>
                                    <td></td>
                                    <td></td>
                                    <td style="font-weight: bold">$<?php echo number_format($total_subtotal, 2, ',', '.'); ?></td>
                                    <td style="font-weight: bold">$<?php echo number_format($total_iva_27, 2, ',', '.'); ?></td>
                                    <td style="font-weight: bold">$<?php echo number_format($total_iva_21, 2, ',', '.'); ?></td>
                                    <td style="font-weight: bold">$<?php echo number_format($total_iva_10_5, 2, ',', '.'); ?></td>
                                    <td style="font-weight: bold">$<?php echo number_format($total_per_iva, 2, ',', '.'); ?></td>
                                    <td style="font-weight: bold">$<?php echo number_format($total_iibb, 2, ',', '.'); ?></td>
                                    <td style="font-weight: bold">$<?php echo number_format($total_it, 2, ',', '.'); ?></td>
                                    <td style="font-weight: bold">$<?php echo number_format($total_tasa, 2, ',', '.'); ?></td>
                                    <td style="font-weight: bold">$<?php echo number_format($total_total, 2, ',', '.'); ?></td>
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
    function filtrar_prov() {
        const urlParams = new URLSearchParams(window.location.search);
        urlParams.set('pagina', 'fiscal');

        const desde = document.getElementById('d').value;
        const hasta = document.getElementById('h').value;
        const prov = document.getElementById('proveedorsel').value;
        const soc = document.getElementById('sociedad_select').value;
        const cc = document.getElementById('cc_select').value;

        if (desde) urlParams.set('d', desde);
        if (hasta) urlParams.set('h', hasta);
        if (prov) urlParams.set('p', prov);
        if (soc) urlParams.set('s', soc);
        if (cc) urlParams.set('c', cc);

        window.location.search = urlParams.toString();
    }

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