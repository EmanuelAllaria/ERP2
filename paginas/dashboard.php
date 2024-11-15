<?php
$estemes = date('Y-m');
$esteanio = date('Y');

// Inicializar filtros
$fecha_filtro_vendedores_menos = isset($_GET['fecha_filtro_vendedores_menos']) && $_GET['fecha_filtro_vendedores_menos'] == 'anual' ? 'anual' : 'mensual';
$fecha_filtro_vendedores_mas = isset($_GET['fecha_filtro_vendedores_mas']) && $_GET['fecha_filtro_vendedores_mas'] == 'anual' ? 'anual' : 'mensual';
$fecha_filtro_gastos = isset($_GET['fecha_filtro_gastos']) && $_GET['fecha_filtro_gastos'] == 'anual' ? 'anual' : 'mensual';
$fecha_filtro_compras = isset($_GET['fecha_filtro_compras']) && $_GET['fecha_filtro_compras'] == 'anual' ? 'anual' : 'mensual';
$fecha_filtro_vendedores_total = isset($_GET['fecha_filtro_vendedores_total']) && $_GET['fecha_filtro_vendedores_total'] == 'anual' ? 'anual' : 'mensual';
$fecha_filtro_productos_mas_vendidos = isset($_GET['fecha_filtro_productos_mas_vendidos']) && $_GET['fecha_filtro_productos_mas_vendidos'] == 'anual' ? 'anual' : 'mensual';
$fecha_filtro_deben_clientes = isset($_GET['fecha_filtro_deben_clientes']) && $_GET['fecha_filtro_deben_clientes'] == 'anual' ? 'anual' : 'mensual';
// $fecha_filtro_vendedores_clientes = isset($_GET['fecha_filtro_vendedores_clientes']) && $_GET['fecha_filtro_vendedores_clientes'] == 'anual' ? 'anual' : 'mensual';
$fecha_filtro_vendedores_ventas = isset($_GET['fecha_filtro_vendedores_ventas']) && $_GET['fecha_filtro_vendedores_ventas'] == 'anual' ? 'anual' : 'mensual';

// Configurar los filtros de fecha
$filtro_fecha_vendedores_menos = $fecha_filtro_vendedores_menos == 'anual' ? "$esteanio%" : "$estemes%";
$filtro_fecha_vendedores_mas = $fecha_filtro_vendedores_mas == 'anual' ? "$esteanio%" : "$estemes%";
$filtro_fecha_vendedores = $fecha_filtro_compra_vendedores == 'anual' ? "$esteanio%" : "$estemes%";
$filtro_fecha_gastos = $fecha_filtro_gastos == 'anual' ? "$esteanio%" : "$estemes%";
$filtro_fecha_compras = $fecha_filtro_compras == 'anual' ? "$esteanio%" : "$estemes%";
$filtro_fecha_vendedores_total = $fecha_filtro_vendedores_total == 'anual' ? "$esteanio%" : "$estemes%";
$filtro_fecha_productos_mas_vendidos = $fecha_filtro_productos_mas_vendidos == 'anual' ? "$esteanio%" : "$estemes%";
$filtro_fecha_deben_clientes = $fecha_filtro_deben_clientes == 'anual' ? "$esteanio%" : "$estemes%";
// $filtro_fecha_vendedores_clientes = $fecha_filtro_vendedores_clientes == 'anual' ? "$esteanio%" : "$estemes%";
$filtro_fecha_vendedores_ventas = $fecha_filtro_vendedores_ventas == 'anual' ? "$esteanio%" : "$estemes%";
?>
<div class="container-fluid">
    <div class="row page-titles">
        <div class="col-md-12">
            <h4 class="text-white">Dashboard</h4>
        </div>
        <div class="col-md-6">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Inicio</a></li>
                <li class="breadcrumb-item "><a href="#">Dashboard</a></li>
            </ol>
        </div>
    </div>

    <div class="row mt-5">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white" style="display: flex;align-items: center; justify-content: space-between;background: #032df378 !important;">
                    <h5 class="mb-0">Compras</h5>
                    <form method="GET" action="index.php">
                        <input type="hidden" name="pagina" value="dashboard">
                        <input type="hidden" name="fecha_filtro_vendedores_mas" value="<?php echo $fecha_filtro_vendedores_mas; ?>">
                        <input type="hidden" name="fecha_filtro_vendedores_menos" value="<?php echo $fecha_filtro_vendedores_menos; ?>">
                        <input type="hidden" name="fecha_filtro_productos_mas_vendidos" value="<?php echo $fecha_filtro_productos_mas_vendidos; ?>">
                        <select name="fecha_filtro_compras" id="fecha_filtro_compras" onchange="this.form.submit()">
                            <option value="mensual" <?php if ($fecha_filtro_compras == 'mensual') echo 'selected'; ?>>Mensual</option>
                            <option value="anual" <?php if ($fecha_filtro_compras == 'anual') echo 'selected'; ?>>Anual</option>
                        </select>
                    </form>
                </div>
                <div class="card-body">
                    <?php
                    $compras = $link->query("SELECT SUM(facturas.monto) AS monto, proveedores.razon_com_proveedor AS nombre_proveedor FROM facturas LEFT JOIN proveedores ON proveedores.id_proveedor = facturas.id_proveedor WHERE facturas.fecha LIKE '$filtro_fecha_compras%' AND facturas.id_proveedor IN (30, 27, 26, 9) GROUP BY facturas.id_proveedor ORDER BY monto DESC LIMIT 5");

                    $max_vendido = 0;
                    $total_compras = 0;
                    $compras_array = [];
                    while ($compra = mysqli_fetch_array($compras)) {
                        $compras_array[] = $compra;
                        $total_compras += $compra['monto'];
                        if ($compra['monto'] > $max_vendido) {
                            $max_vendido = $compra['monto'];
                        }
                    }

                    echo '<div class="vertical-bar-container" style="display: flex; flex-direction: row; align-items: flex-start;align-items: flex-start;text-wrap: nowrap;">';
                    foreach ($compras_array as $compra) {
                        $porcentaje = $max_vendido > 0 ? ($compra['monto'] / $max_vendido) * 100 : 0;
                        echo '<div class="product-bar" style="margin-bottom: 10px; display: flex; align-items: center;flex-direction: column-reverse;text-wrap: balance;gap: 1em;">';
                        echo '<strong style="width: 150px;text-align: center;">' . $compra['nombre_proveedor'] . '</strong>';
                        echo '<div class="bar-container" style="width: 30px; height: 100px; margin-left: 10px; display: flex; align-items: flex-end;">';
                        echo '<div class="bar" style="height: ' . $porcentaje . '%; background-color: #00800099;"></div>';
                        echo '</div>';
                        echo '<span class="badge badge-success badge-pill" style="margin-left: 10px;">$' . number_format($compra['monto'], 0, ',', '.') . '</span>';
                        echo '</div>';
                    }
                    echo '</div>';
                    ?>
                </div>
                <div class="card-footer">
                    <h4><b>Total:</b> $ <?php echo number_format($total_compras, 0, ',', '.') ?></h4>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white" style="display: flex; align-items: center; justify-content: space-between;background: #d0070775 !important;">
                    <h5 class="mb-0">Gastos</h5>
                    <form method="GET" action="index.php">
                        <input type="hidden" name="pagina" value="dashboard">
                        <input type="hidden" name="fecha_filtro_ventas" value="<?php echo $fecha_filtro_ventas; ?>">
                        <input type="hidden" name="fecha_filtro_vendedores_menos" value="<?php echo $fecha_filtro_vendedores_menos; ?>">
                        <input type="hidden" name="fecha_filtro_vendedores_mas" value="<?php echo $fecha_filtro_vendedores_mas; ?>">
                        <select name="fecha_filtro_gastos" id="fecha_filtro_gastos" onchange="this.form.submit()">
                            <option value="mensual" <?php if ($fecha_filtro_gastos == 'mensual') echo 'selected'; ?>>Mensual</option>
                            <option value="anual" <?php if ($fecha_filtro_gastos == 'anual') echo 'selected'; ?>>Anual</option>
                        </select>
                    </form>
                </div>
                <div class="card-body">
                    <?php
                    $sueldos = $link->query("SELECT SUM(monto_adelanto) AS monto FROM adelantos WHERE cuando_ad LIKE '$filtro_fecha_gastos%'");
                    $valor_sueldos = mysqli_fetch_array($sueldos)[0] ?: 0;

                    $gastos = $link->query("SELECT SUM(facturas.monto) AS monto, proveedores.razon_com_proveedor AS nombre_proveedor 
                        FROM facturas 
                        LEFT JOIN proveedores ON proveedores.id_proveedor = facturas.id_proveedor 
                        WHERE facturas.fecha LIKE '$filtro_fecha_gastos%' 
                        AND proveedores.notas_proveedor LIKE '%gastos operativos%' 
                        GROUP BY facturas.id_proveedor
                        ORDER BY monto DESC
                        LIMIT 5;");

                    $total_gastos = $valor_sueldos;
                    $gastos_data = [];

                    while ($row = mysqli_fetch_array($gastos)) {
                        $monto_gasto = $row['monto'];
                        $nombre_proveedor = $row['nombre_proveedor'];

                        if ($monto_gasto > 0) {
                            $total_gastos += $monto_gasto;
                            $gastos_data[] = [
                                'nombre_proveedor' => $nombre_proveedor,
                                'monto' => $monto_gasto
                            ];
                        }
                    }

                    $porcentaje_sueldos = $total_gastos > 0 ? ($valor_sueldos / $total_gastos) * 100 : 0;

                    echo '<div class="vertical-bar-container" style="display: flex; flex-direction: row; align-items: flex-start; text-wrap: nowrap;">';

                    echo '<div class="product-bar" style="margin-bottom: 10px; display: flex; align-items: center; flex-direction: column-reverse; text-wrap: balance; gap: 1em;">';
                    echo '<strong style="width: 150px; text-align: center;">Sueldos</strong>';
                    echo '<div class="bar-container" style="width: 30px; height: 100px; margin-left: 10px; display: flex; align-items: flex-end;">';
                    echo '<div class="bar" style="height: ' . $porcentaje_sueldos . '%; background-color: #00800099;"></div>';
                    echo '</div>';
                    echo '<span class="badge badge-success badge-pill" style="margin-left: 10px;">$' . number_format($valor_sueldos, 0, ',', '.') . '</span>';
                    echo '</div>';

                    foreach ($gastos_data as $gasto) {
                        $porcentaje_gastos = $total_gastos > 0 ? ($gasto['monto'] / $total_gastos) * 100 : 0;
                        echo '<div class="product-bar" style="margin-bottom: 10px; display: flex; align-items: center; flex-direction: column-reverse; text-wrap: balance; gap: 1em;">';
                        echo '<strong style="width: 150px; text-align: center;">' . $gasto['nombre_proveedor'] . '</strong>';
                        echo '<div class="bar-container" style="width: 30px; height: 100px; margin-left: 10px; display: flex; align-items: flex-end;">';
                        echo '<div class="bar" style="height: ' . $porcentaje_gastos . '%; background-color: #00800099;"></div>';
                        echo '</div>';
                        echo '<span class="badge badge-success badge-pill" style="margin-left: 10px;">$' . number_format($gasto['monto'], 0, ',', '.') . '</span>';
                        echo '</div>';
                    }

                    echo '</div>';
                    ?>
                </div>
                <div class="card-footer">
                    <h4><b>Total:</b> $ <?php echo number_format($total_gastos, 0, ',', '.') ?></h4>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-5">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white" style="display: flex;align-items: center; justify-content: space-between;background: #032df378 !important;">
                    <h5 class="mb-0">Vendedores que más venden</h5>
                    <form method="GET" action="index.php">
                        <input type="hidden" name="pagina" value="dashboard">
                        <input type="hidden" name="fecha_filtro_ventas" value="<?php echo $fecha_filtro_ventas; ?>">
                        <input type="hidden" name="fecha_filtro_vendedores_menos" value="<?php echo $fecha_filtro_vendedores_menos; ?>">
                        <input type="hidden" name="fecha_filtro_vendedores_mas" value=" <?php echo $fecha_filtro_vendedores_mas; ?>">
                        <input type="hidden" name="fecha_filtro_compras" value="<?php echo $fecha_filtro_compras; ?>">
                        <select name="fecha_filtro_vendedores_mas" id="fecha_filtro_vendedores_mas" onchange="this.form.submit()">
                            <option value="mensual" <?php if ($fecha_filtro_vendedores_mas == 'mensual') echo 'selected'; ?>>Mensual</option>
                            <option value="anual" <?php if ($fecha_filtro_vendedores_mas == 'anual') echo 'selected'; ?>>Anual</option>
                        </select>
                    </form>
                </div>
                <div class="card-body">
                    <?php
                    $vendedores = $link->query("SELECT transaccion.quien, COUNT(*) AS total_transacciones, personal.nombre, personal.apellido 
                                        FROM transaccion 
                                        JOIN personal ON transaccion.quien = personal.id 
                                        WHERE transaccion.tipo LIKE 'pedido' 
                                        AND transaccion.estado = '1' 
                                        AND transaccion.fecha LIKE '$filtro_fecha_vendedores_mas%'
                                        GROUP BY transaccion.quien 
                                        ORDER BY total_transacciones DESC 
                                        LIMIT 5");

                    $max_transacciones = 0;
                    $vendedores_array = [];
                    while ($vendedor = mysqli_fetch_array($vendedores)) {
                        $vendedores_array[] = $vendedor;
                        if ($vendedor['total_transacciones'] > $max_transacciones) {
                            $max_transacciones = $vendedor['total_transacciones'];
                        }
                    }

                    echo '<div class="horizontal-bar-container">';
                    foreach ($vendedores_array as $vendedor) {
                        $porcentaje = $max_transacciones > 0 ? ($vendedor['total_transacciones'] / $max_transacciones) * 100 : 0;
                        echo '<div class="vendedor-bar d-flex align-items-center mb-2">';
                        echo '<strong style="width: 150px;">' . $vendedor['nombre'] . ' ' . $vendedor['apellido'] . '</strong>';
                        echo '<div class="bar-container">';
                        echo '<div class="bar" style="width: ' . $porcentaje . '%; background-color: #00800099;"></div>';
                        echo '</div>';
                        echo '<span class="badge badge-success ml-2">' . number_format($vendedor['total_transacciones'], 0, ',', '.') . '</span>';
                        echo '</div>';
                    }
                    echo '</div>';
                    ?>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white" style="display: flex;align-items: center; justify-content: space-between;background: #d0070775 !important;">
                    <h5 class="mb-0">Vendedores que menos venden</h5>
                    <form method="GET" action="index.php">
                        <input type="hidden" name="pagina" value="dashboard">
                        <input type="hidden" name="fecha_filtro_ventas" value="<?php echo $fecha_filtro_ventas; ?>">
                        <input type="hidden" name="fecha_filtro_vendedores_mas" value=" <?php echo $fecha_filtro_vendedores_mas; ?>">
                        <input type="hidden" name="fecha_filtro_compras" value="<?php echo $fecha_filtro_compras; ?>">
                        <select name="fecha_filtro_vendedores_menos" id="fecha_filtro_vendedores_menos" onchange="this.form.submit()">
                            <option value="mensual" <?php if ($fecha_filtro_vendedores_menos == 'mensual') echo 'selected'; ?>>Mensual</option>
                            <option value="anual" <?php if ($fecha_filtro_vendedores_menos == 'anual') echo 'selected'; ?>>Anual</option>
                        </select>
                    </form>
                </div>
                <div class="card-body">
                    <?php
                    $vendedores = $link->query("SELECT transaccion.quien, COUNT(*) AS total_transacciones, personal.nombre, personal.apellido 
                                        FROM transaccion 
                                        JOIN personal ON transaccion.quien = personal.id 
                                        WHERE transaccion.tipo LIKE 'pedido' 
                                        AND transaccion.estado = '1' 
                                        AND transaccion.fecha LIKE '$filtro_fecha_vendedores_menos'
                                        GROUP BY transaccion.quien 
                                        ORDER BY total_transacciones ASC 
                                        LIMIT 5");

                    $max_transacciones = 0;
                    $vendedores_array = [];
                    while ($vendedor = mysqli_fetch_array($vendedores)) {
                        $vendedores_array[] = $vendedor;
                        if ($vendedor['total_transacciones'] > $max_transacciones) {
                            $max_transacciones = $vendedor['total_transacciones'];
                        }
                    }

                    echo '<div class="horizontal-bar-container">';
                    foreach ($vendedores_array as $vendedor) {
                        $porcentaje = $max_transacciones > 0 ? ($vendedor['total_transacciones'] / $max_transacciones) * 100 : 0;
                        echo '<div class="vendedor-bar d-flex align-items-center mb-2">';
                        echo '<strong style="width: 150px;">' . $vendedor['nombre'] . ' ' . $vendedor['apellido'] . '</strong>';
                        echo '<div class="bar-container">';
                        echo '<div class="bar" style="width: ' . $porcentaje . '%; background-color: #00800099;"></div>';
                        echo '</div>';
                        echo '<span class="badge badge-success ml-2">' . number_format($vendedor['total_transacciones'], 0, ',', '.') . '</span>';
                        echo '</div>';
                    }
                    echo '</div>';
                    ?>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-5">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-info text-white" style="display: flex; align-items: center; justify-content: space-between;background: #032df378 !important;">
                    <h5 class="mb-0">Productos más vendidos</h5>
                    <form method="GET" action="index.php">
                        <input type="hidden" name="pagina" value="dashboard">
                        <input type="hidden" name="fecha_filtro_ventas" value="<?php echo $fecha_filtro_ventas; ?>">
                        <input type="hidden" name="fecha_filtro_vendedores_mas" value="<?php echo $fecha_filtro_vendedores_mas; ?>">
                        <input type="hidden" name="fecha_filtro_vendedores_menos" value="<?php echo $fecha_filtro_vendedores_menos; ?>">
                        <input type="hidden" name="fecha_filtro_compras" value="<?php echo $fecha_filtro_compras; ?>">
                        <select name="fecha_filtro_productos_mas_vendidos" id="fecha_filtro_productos_mas_vendidos" onchange="this.form.submit()">
                            <option value="mensual" <?php if ($fecha_filtro_productos_mas_vendidos == 'mensual') echo 'selected'; ?>>Mensual</option>
                            <option value="anual" <?php if ($fecha_filtro_productos_mas_vendidos == 'anual') echo 'selected'; ?>>Anual</option>
                        </select>
                    </form>
                </div>
                <div class="card-body">
                    <?php
                    $productos = $link->query("SELECT productos.detalle_producto AS nombre_producto, SUM(stock_depositos.cantidad_stockd) AS total_vendido 
                                        FROM stock_depositos 
                                        JOIN productos ON stock_depositos.idproducto_stockd = productos.id_producto 
                                        WHERE stock_depositos.tipomov_stockd LIKE 'venta' 
                                        AND stock_depositos.estado_stockd = '1' 
                                        AND stock_depositos.fecha_stockd LIKE '$filtro_fecha_productos_mas_vendidos'
                                        GROUP BY stock_depositos.idproducto_stockd 
                                        ORDER BY total_vendido DESC 
                                        LIMIT 5");

                    $max_vendido = 0;
                    $productos_array = [];
                    while ($producto = mysqli_fetch_array($productos)) {
                        $productos_array[] = $producto;
                        if ($producto['total_vendido'] > $max_vendido) {
                            $max_vendido = $producto['total_vendido'];
                        }
                    }

                    // Generar las barras verticales
                    echo '<div class="vertical-bar-container" style="display: flex; flex-direction: row; align-items: flex-start;align-items: flex-start;text-wrap: nowrap;">';
                    foreach ($productos_array as $producto) {
                        $porcentaje = $max_vendido > 0 ? ($producto['total_vendido'] / $max_vendido) * 100 : 0;
                        echo '<div class="product-bar" style="margin-bottom: 10px; display: flex; align-items: center;flex-direction: column-reverse;text-wrap: balance;gap: 1em;">';
                        echo '<strong style="width: 150px;text-align: center;">' . $producto['nombre_producto'] . '</strong>';
                        echo '<div class="bar-container" style="width: 30px; height: 100px; margin-left: 10px; display: flex; align-items: flex-end;">';
                        echo '<div class="bar" style="height: ' . $porcentaje . '%; background-color: #00800099;"></div>';
                        echo '</div>';
                        echo '<span class="badge badge-success badge-pill" style="margin-left: 10px;">x ' . number_format($producto['total_vendido'], 0, ',', '.') . '</span>';
                        echo '</div>';
                    }
                    echo '</div>';
                    ?>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white" style="display: flex;align-items: center; justify-content: space-between;background: #d0070775 !important;">
                    <h5 class="mb-0">Clientes Que Hace Más Tiempo Deben</h5>
                    <!-- <form method="GET" action="index.php">
                        <input type="hidden" name="pagina" value="dashboard">
                        <input type="hidden" name="fecha_filtro_ventas" value="<?php echo $fecha_filtro_ventas; ?>">
                        <input type="hidden" name="fecha_filtro_vendedores_mas" value=" <?php echo $fecha_filtro_vendedores_mas; ?>">
                        <input type="hidden" name="fecha_filtro_vendedores_menos" value="<?php echo $fecha_filtro_vendedores_menos; ?>">
                        <input type="hidden" name="fecha_filtro_compras" value="<?php echo $fecha_filtro_compras; ?>">
                        <select name="fecha_filtro_deben_clientes" id="fecha_filtro_deben_clientes" onchange="this.form.submit()">
                            <option value="mensual" <?php if ($fecha_filtro_deben_clientes == 'mensual') echo 'selected'; ?>>Mensual</option>
                            <option value="anual" <?php if ($fecha_filtro_deben_clientes == 'anual') echo 'selected'; ?>>Anual</option>
                        </select>
                    </form> -->
                </div>
                <div class="card-body">
                    <?php
                    $clientes_deben = $link->query("SELECT clientes.nombre_clientes, clientes.apellido_clientes, (COALESCE(SUM(CASE WHEN transaccion.tipo = 'pago' THEN transaccion.monto2 END), 0) / COALESCE(SUM(CASE WHEN transaccion.tipo = 'pedido' THEN transaccion.monto END), 1)) * 100 AS porcentaje_pagado FROM transaccion LEFT JOIN clientes ON transaccion.cliente = clientes.id_clientes WHERE transaccion.estado = 1 AND DATEDIFF(CURDATE(), transaccion.fecha) <= clientes.dias_financiacion GROUP BY transaccion.cliente, clientes.nombre_clientes, clientes.apellido_clientes ORDER BY porcentaje_pagado DESC");

                    $max_transacciones = 0;
                    $clientes_deben_array = [];
                    while ($cliente = mysqli_fetch_array($clientes_deben)) {
                        $clientes_deben_array[] = $cliente;
                        if ($cliente[2] > $max_transacciones) {
                            $max_transacciones = $cliente[2];
                        }
                    }

                    echo '<div class="horizontal-bar-container">';
                    foreach ($clientes_deben_array as $cliente) {
                        $porcentaje = $max_transacciones > 0 ? ($cliente[2] / $max_transacciones) * 100 : 0;
                        echo '<div class="vendedor-bar d-flex align-items-center mb-2">';
                        echo '<strong style="width: 150px;">' . $cliente[0] . ' ' . $cliente[1] . '</strong>';
                        echo '<div class="bar-container">';
                        echo '<div class="bar" style="width: ' . $porcentaje . '%; background-color: #00800099;"></div>';
                        echo '</div>';
                        echo '<span class="badge badge-success ml-2">' . number_format($cliente[2], 0, ',', '.') . '</span>';
                        echo '</div>';
                    }
                    echo '</div>';
                    ?>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-5">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-info text-white" style="display: flex;align-items: center; justify-content: space-between;background: #d0070775 !important;">
                    <h5 class="mb-0">Cantidad de Ventas en Vendedores</h5>
                    <form method="GET" action="index.php">
                        <input type="hidden" name="pagina" value="dashboard">
                        <input type="hidden" name="fecha_filtro_ventas" value="<?php echo $fecha_filtro_ventas; ?>">
                        <input type="hidden" name="fecha_filtro_vendedores_mas" value=" <?php echo $fecha_filtro_vendedores_mas; ?>">
                        <input type="hidden" name="fecha_filtro_vendedores_menos" value="<?php echo $fecha_filtro_vendedores_menos; ?>">
                        <input type="hidden" name="fecha_filtro_compras" value="<?php echo $fecha_filtro_compras; ?>">
                        <input type="hidden" name="fecha_filtro_deben_clientes" value="<?php echo $fecha_filtro_deben_clientes; ?>">
                        <!-- <input type="hidden" name="fecha_filtro_vendedores_clientes" value="<?php echo $fecha_filtro_vendedores_clientes; ?>"> -->
                        <select name="fecha_filtro_vendedores_ventas" id="fecha_filtro_vendedores_ventas" onchange="this.form.submit()">
                            <option value="mensual" <?php if ($fecha_filtro_vendedores_ventas == 'mensual') echo 'selected'; ?>>Mensual</option>
                            <option value="anual" <?php if ($fecha_filtro_vendedores_ventas == 'anual') echo 'selected'; ?>>Anual</option>
                        </select>
                    </form>
                </div>
                <div class="card-body">
                    <?php
                    $vendedores_ventas = $link->query("SELECT personal.nombre, personal.apellido, COUNT(DISTINCT transaccion.id) AS cantidad_transacciones FROM personal LEFT JOIN transaccion ON transaccion.quien = personal.id AND transaccion.tipo = 'pedido' AND transaccion.fecha LIKE '$filtro_fecha_vendedores_ventas%' WHERE personal.area = 'Reparto' GROUP BY personal.nombre, personal.apellido ORDER BY personal.id DESC");

                    $max_vendedores_ventas = 0;
                    $vendedores_ventas_array = [];
                    while ($vendedor_venta = mysqli_fetch_array($vendedores_ventas)) {
                        $vendedores_ventas_array[] = $vendedor_venta;
                        if ($vendedor_venta[2] > $max_vendedores_ventas) {
                            $max_vendedores_ventas = $vendedor_venta[2];
                        }
                    }

                    echo '<div class="horizontal-bar-container">';
                    foreach ($vendedores_ventas_array as $vendedor_venta) {
                        $porcentaje = $max_vendedores_ventas > 0 ? ($vendedor_venta[2] / $max_vendedores_ventas) * 100 : 0;
                        echo '<div class="vendedor-bar d-flex align-items-center mb-2">';
                        echo '<strong style="width: 150px;">' . $vendedor_venta['nombre'] . ' ' . $vendedor_venta['apellido'] . ':</strong>';
                        echo '<div class="bar-container">';
                        echo '<div class="bar" style="width: ' . $porcentaje . '%; background-color: #00800099;"></div>';
                        echo '</div>';
                        echo '<span class="badge badge-success ml-2">' . $vendedor_venta['cantidad_transacciones'] . '</span>';
                        echo '</div>';
                    }
                    echo '</div>';
                    ?>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-info text-white" style="display: flex;align-items: center; justify-content: space-between;background: #d0070775 !important;">
                    <h5 class="mb-0">Cantidad de Clientes en Vendedores</h5>
                    <!-- <form method="GET" action="index.php">
                        <input type="hidden" name="pagina" value="dashboard">
                        <input type="hidden" name="fecha_filtro_ventas" value="<?php echo $fecha_filtro_ventas; ?>">
                        <input type="hidden" name="fecha_filtro_vendedores_mas" value=" <?php echo $fecha_filtro_vendedores_mas; ?>">
                        <input type="hidden" name="fecha_filtro_vendedores_menos" value="<?php echo $fecha_filtro_vendedores_menos; ?>">
                        <input type="hidden" name="fecha_filtro_compras" value="<?php echo $fecha_filtro_compras; ?>">
                        <input type="hidden" name="fecha_filtro_deben_clientes" value="<?php echo $fecha_filtro_deben_clientes; ?>">
                        <input type="hidden" name="fecha_filtro_vendedores_ventas" value="<?php echo $fecha_filtro_vendedores_ventas; ?>">
                        <select name="fecha_filtro_vendedores_clientes" id="fecha_filtro_vendedores_clientes" onchange="this.form.submit()">
                            <option value="mensual" <?php if ($fecha_filtro_vendedores_clientes == 'mensual') echo 'selected'; ?>>Mensual</option>
                            <option value="anual" <?php if ($fecha_filtro_vendedores_clientes == 'anual') echo 'selected'; ?>>Anual</option>
                        </select>
                    </form> -->
                </div>
                <div class="card-body">
                    <?php
                    $vendedores_clientes = $link->query("SELECT personal.nombre, personal.apellido, COUNT(DISTINCT clientes.id_clientes) AS cantidad_clientes FROM personal LEFT JOIN clientes ON clientes.asignado_clientes = personal.id WHERE personal.area = 'Reparto' GROUP BY personal.id, personal.nombre, personal.apellido ORDER BY personal.id DESC");

                    $max_vendedores_clientes = 0;
                    $vendedores_clientes_array = [];
                    while ($vendedor_cliente = mysqli_fetch_array($vendedores_clientes)) {
                        $vendedores_clientes_array[] = $vendedor_cliente;
                        if ($vendedor_cliente[2] > $max_vendedores_clientes) {
                            $max_vendedores_clientes = $vendedor_cliente[2];
                        }
                    }

                    echo '<div class="horizontal-bar-container">';
                    foreach ($vendedores_clientes_array as $vendedor_cliente) {
                        $porcentaje = $max_vendedores_clientes > 0 ? ($vendedor_cliente[2] / $max_vendedores_clientes) * 100 : 0;
                        echo '<div class="vendedor-bar d-flex align-items-center mb-2">';
                        echo '<strong style="width: 150px;">' . $vendedor_cliente['nombre'] . ' ' . $vendedor_cliente['apellido'] . ':</strong>';
                        echo '<div class="bar-container">';
                        echo '<div class="bar" style="width: ' . $porcentaje . '%; background-color: #00800099;"></div>';
                        echo '</div>';
                        echo '<span class="badge badge-success ml-2">' . $vendedor_cliente['cantidad_clientes'] . '</span>';
                        echo '</div>';
                    }
                    echo '</div>';
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .horizontal-bar-container {
        display: flex;
        flex-direction: column;
    }

    .horizontal-bar-container .bar-container {
        background-color: #f0f0f0;
        border-radius: 4px;
        height: 20px;
        width: 100%;
        overflow: hidden;
    }

    .horizontal-bar-container .bar {
        height: 100%;
        color: white;
        transition: width 0.3s ease;
    }

    .vendedor-bar {
        display: flex;
        align-items: center;
    }

    .vendedor-bar .bar-container {
        flex-grow: 1;
        margin-left: 10px;
    }

    .vertical-bar-container {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
    }

    .vertical-bar-container .bar-container {
        background-color: #f0f0f0 !important;
        border-radius: 4px !important;
        width: 30px !important;
        height: 100px !important;
        overflow: hidden !important;
        margin-left: 10px !important;
    }

    .vertical-bar-container .bar {
        width: 100% !important;
        transition: height 0.3s ease !important;
    }

    .bar-container {
        background-color: #f0f0f0;
        border-radius: 4px;
        height: 20px;
        overflow: hidden;
    }

    .bar {
        height: 100%;
        color: white;
        transition: width 0.3s ease;
    }

    .bar[style*="width: 0%"] {
        background-color: red;
    }

    .bar[style*="width: 1%"],
    .bar[style*="width: 2%"],
    .bar[style*="width: 3%"],
    .bar[style*="width: 4%"],
    .bar[style*="width: 5%"] {
        background-color: yellow;
    }

    .bar[style*="width: 100%"] {
        background-color: #00800099;
    }
</style>