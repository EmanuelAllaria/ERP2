<div class="container-fluid">
    <div class="row page-titles">
        <div class="col-md-12">
            <h4 class="text-white">Listado Fiscal de Ventas</h4>
        </div>
        <div class="col-md-6">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
                <li class="breadcrumb-item"><a href="index.php?pagina=fiscal_ventas">Fiscal de ventas</a></li>
                <?php if (isset($_GET['buscar'])) {
                    echo '<li class="breadcrumb-item"><a href="#">Buscar: [' . htmlspecialchars($_GET['buscar']) . ']</a></li>';
                } ?>
            </ol>
        </div>
        <div class="col-md-6 text-right">
            <form class="app-search d-none d-md-block d-lg-block" method="get">
                <input type="hidden" name="pagina" value="fiscal_ventas">
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
                                                <small class="form-control-feedback"> Sociedad </small><br>
                                                <select id="sociedad_select" name="titular" class="form-control">
                                                    <option value="1">PADSA BAHIA SA</option>
                                                    <option value="36">DPA GROUP SA</option>
                                                </select>
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
                                            <div class="col-md-2" style="align-self: center;">
                                                <a href="#" onclick="filtrar_prov()" class="btn btn-info btn-lg" role="button">Filtrar</a>
                                            </div>
                                            <?php if (isset($_GET['d']) || isset($_GET['h']) || isset($_GET['p'])) { ?>
                                                <div class="col-md-2" style="align-self: center;">
                                                    <a href="index.php?pagina=fiscal_ventas" class="btn btn-secondary">Quitar Filtros</a>
                                                </div>
                                            <?php } ?>

                                            <div id="total_periodo"></div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <tr>

                                      <th class="footable-sortable">Fecha<span class="footable-sort-indicator"></span></th>
                                      <th class="footable-sortable">Cliente<span class="footable-sort-indicator"></span></th>
                                      <th class="footable-sortable">Vendedor<span class="footable-sort-indicator"></span></th>
                                      <th class="footable-sortable">Sociedad<span class="footable-sort-indicator"></span></th>
                                      <th class="footable-sortable">Detalle<span class="footable-sort-indicator"></span></th>
                                      <th class="footable-sortable">Observacion<span class="footable-sort-indicator"></span></th>
                                      <th class="footable-sortable">F. de Pago<span class="footable-sort-indicator"></span></th>
                                      <th class="footable-sortable">Monto<span class="footable-sort-indicator"></span></th>
                                      <th class="footable-sortable" style="width: 110px;">Acciones<span class="footable-sort-indicator"></span></th>
                                      <th class="footable-sortable">#<span class="footable-sort-indicator"></span></th>
                                </tr>
                            </thead>
                            <tbody id="lista_facturas">
                                <?php
                                // Preparar la consulta
                                $busqueda = '';
                                if (isset($_GET['buscar'])) {
                                    $palabra = $link->real_escape_string($_GET['buscar']);
                                    $busqueda .= " AND transaccion.id = '$palabra'";
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
                                    $vendedor = (int)$_GET['p'];
                                    $busqueda .= " AND transaccion.quien = $vendedor";
                                }

                                if (isset($_GET['s']) && $_GET['s'] != '') {
                                    if ($_GET['s'] == 36) {
                                      $sociedad = $_GET['s'];
                                      $busqueda .= " AND transaccion.quien = '$sociedad'";
                                    }else if($_GET['s'] == 1){
                                      $sociedad = $_GET['s'];
                                      $busqueda .= " AND transaccion.quien != 36";
                                    }
                                    
                                }

                                // Realizar la consulta
                                $acumula = 0;
                                $con_pedidos = $link->query("SELECT *, transaccion.id as ide, personal.id as id_p FROM `transaccion`
                                                          inner join clientes on transaccion.cliente = clientes.id_clientes
                                                          inner join personal on transaccion.quien = personal.id
                                                          left join formas_pagos on transaccion.forma_pago = formas_pagos.id_formapago
                                                          WHERE transaccion.estado='1' and transaccion.tipo ='pedido' and transaccion.observacion ='FACTURA' and transaccion.fecha >= '$desde' and transaccion.fecha <= '$hasta' $busqueda order by transaccion.fecha DESC, razon_com_clientes ASC");
                                $acumula = 0;
                                while ($row = mysqli_fetch_array($con_pedidos)) {
                                  $preciocrudo = number_format($row['monto'], 0, '.', '');
                                  
                                  $precio_iva = $preciocrudo / 1.21;
                                  $acumula = ($acumula + $precio_iva);
                                  $sociedad = '';
                                  if ($row["id_p"] == 36) {
                                        $sociedad = 'DPA GROUP SA';
                                  }else{
                                        $sociedad = 'PADSA BAHIA SA';
                                  }

                                ?>

                                <tr>

                                  <td class="font-weight-normal"><span class="footable-toggle"></span><?php echo date('d/m/Y', strtotime($row['fecha'])) ?></td>
                                  <td class="font-weight-normal">
                                    <a href="index.php?pagina=clientes_view&id=<?php echo $row['id_clientes'] ?>" style="color:#262626;" class="font-weight-normal"><!--<img src="img/comercios/<?php echo $row['foto_comclientes'] ?>" alt="user" width="40" style="margin-right: 5px;" class="img-circle">--><?php echo mb_strtoupper($row['razon_com_clientes']) ?></a>
                                  </td>
                                  <td class="font-weight-normal"><?php echo $row['nombre'] . ', ' . $row['apellido'] ?></td>

                                  <td class="font-weight-normal"><?php echo $sociedad?></td>
                                  
                                  <td class="font-weight-normal"><?php echo $row['detalle'] ?></td>
                                  <td class="font-weight-normal"><?php echo $row['observacion'] ?></td>
                                  <td class="font-weight-normal"><?php echo $row['detalle_formapago'] ?></td>
                                  <td class="font-weight-normal">$<?php echo number_format($precio_iva, 2, ',', '.'); ?> </td>
                                  <td>

                                    <?php if ($_SESSION['tipo'] != 'User') { ?>
                                      <a class="btn-pure btn-outline-success success-row-btn btn-lg" style="padding:0px;" href="index.php?pagina=pedido_fac&id=<?php echo $row['ide'] ?>"><i class="ti-eye" aria-hidden="true"></i></a>
                                      <?php if ($_SESSION['personal'] == $row['quien']) { ?>
                                        &nbsp;&nbsp;<a class="btn-pure btn-outline-info edit-row-btn btn-lg" style="padding:0px;" href="index.php?pagina=pedidos_edit&id=<?php echo $row['ide'] ?>" data-toggle="tooltip" data-original-title="Editar"><i class="ti-pencil" aria-hidden="true"></i></a>
                                        &nbsp;&nbsp;<a class="btn-pure btn-outline-danger delete-row-btn btn-lg" style="padding:0px;" href="#" data-toggle="modal" data-target="#delt_<?php echo $row['ide'] ?>" data-original-title="Borrar"><i class="ti-close" aria-hidden="true"></i></a>
                                      <?php } ?>

                                    <?php } ?>
                                  </td>

                                  <td class="font-weight-normal"><span class="footable-toggle"></span><?php echo $row['ide'] ?></td>
                                  <td class="font-weight-normal">
                                  </td>
                                </tr>
                                <?php } ?>
                            </tbody>

                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
  $('#total_periodo').html('<span class="btn btn-success pull-right"><b>TOTAL: $<?php echo number_format($acumula, 2, ',', '.'); ?></b></span>')

    function filtrar_prov() {
        const urlParams = new URLSearchParams(window.location.search);
        urlParams.set('pagina', 'fiscal_ventas');

        const desde = document.getElementById('d').value;
        const hasta = document.getElementById('h').value;
        const prov = document.getElementById('proveedorsel').value;
        const soc = document.getElementById('sociedad_select').value;

        if (desde) urlParams.set('d', desde);
        if (hasta) urlParams.set('h', hasta);
        if (prov) urlParams.set('p', prov);
        if (soc) urlParams.set('s', soc);

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