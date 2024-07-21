<?php
$id = $_GET['id'];
$con_detalle = $link->query("SELECT * FROM clientes

  left join ciudad on ciudad.id_ciudad = clientes.ciudad_clientes
  left join provincia on provincia.id_provincia = ciudad.provincia_id
  left join personal on personal.id = clientes.asignado_clientes
  left join rubros on rubros.id_rubros = rubro_com_clientes
  where estado_clientes !='0' and id_clientes='$id' ");
$row = mysqli_fetch_array($con_detalle);
?>
<div class="container-fluid" id="contenedor_total">

    <div class="row page-titles">
        <div class="col-md-12">
            <h4 class="text-white">Detalle de <?php echo $row['apellido_clientes'] . ', ' . $row['nombre_clientes']; ?> || Balance Actual: $<span id="balance"></span></h4>
        </div>
        <div class="col-md-6">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
                <li class="breadcrumb-item"><a href="index.php?pagina=clientes">Clientes</a></li>
                <li class="breadcrumb-item"><a href="#">Detalle</a></li>
            </ol>
        </div>
        <div class="col-md-6 text-right">
            <form class="app-search d-none d-md-block d-lg-block" method="get">
                <input type="hidden" name="pagina" value="clientes">
                <input type="text" name="buscar" class="form-control" placeholder="Buscar...">
            </form>
        </div>
    </div>

    <div class="row">
        <!-- Column -->
        <div class="col-lg-3 col-xlg-3 col-md-3">
            <div class="card"> <img class="card-img" src="/img/rubros/<?php if ($row['imagen_rubros'] != '') {
                                                                            echo $row['imagen_rubros'];
                                                                        } else {
                                                                            echo 'otros.jpg';
                                                                        } ?>" height="456" alt="Imagen del Rubro">
                <div class="card-img-overlay card-inverse text-white social-profile d-flex justify-content-center">
                    <div class="align-self-center"> <img src="img/comercios/<?php echo $row['foto_clientes']; ?>" class="img-circle" width="100">
                        <br>
                        <h4 class="card-title" style="padding-top: 10px;"><?php echo mb_strtoupper($row['razon_com_clientes']); ?></h4>
                        <p class="text-white"><?php echo $row['nombre_rubros']; ?><br /></p>
                        <h4 class="card-title">Balance Actual: <br />$<span id="balance2"></span></h4>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body"> <small class="text-muted">Correo </small>
                    <h6><?php echo $row['email_clientes']; ?></h6>
                    <?php if ($row['telefono_clientes'] != '') { ?> <small class="text-muted p-t-30 db">Telefono</small>
                        <h6><?php echo $row['telefono_clientes'] . '</h6>';
                        } ?>
                        <?php if ($row['celular_clientes'] != '') { ?><small class="text-muted p-t-30 db">Celular</small>
                            <h6><?php echo $row['celular_clientes'] . '</h6>';
                            } ?>
                            <small class="text-muted p-t-30 db">Zona de:</small>
                            <h6><?php echo $row['nombre'] . ', ' . $row['apellido'] . '</h6>'; ?>
                                <small class="text-muted p-t-30 db">Direccion</small>

                                <h6><?php echo $row['direccion_clientes'] . ' ' . $row['dirnum_clientes'] . '<br/>  ' . ucwords(strtolower($row['ciudad_nombre'])) . ', ' . $row['provincia_nombre']; ?></h6>
                                <div class="map-box">
                                    <div id="mapa" style="width: 100%; height: 300px; position: relative; overflow: hidden;"></div>
                                </div>

                </div>
            </div>
        </div>
        <div class="col-lg-9 col-xlg-12 col-md-9">
            <div class="card">
                <!-- Nav tabs -->
                <ul class="nav nav-tabs profile-tab" role="tablist">
                    <li class="nav-item"> <a class="nav-link active" data-toggle="tab" href="#ccorriente" role="tab">Cuenta Corriente</a> </li>
                    <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#home" role="tab">Ultimos Movimientos</a> </li>
                    <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#notas" role="tab">Notas</a> </li>
                </ul>
                <!-- Tab panes -->
                <div class="tab-content">
                    <div class="tab-pane " id="home" role="tabpanel">
                        <div class="card-body">
                            <div class="profiletimeline">
                                <?php
                                $consulta_movimientos = $link->query("SELECT * FROM transaccion WHERE cliente ='$id' AND estado = 1 order by id DESC limit 6 ");

                                while ($mov = mysqli_fetch_array($consulta_movimientos)) {
                                    if ($mov['tipo'] == 'pedido') {
                                        $icono = "ti-shopping-cart-full";
                                        $colorbt = 'btn-warning';
                                        $tipot = 'Pedido';
                                        $id_fila = 'mPedido';
                                    }
                                    if ($mov['tipo'] == 'pago') {
                                        $icono = "ti-money";
                                        $colorbt = 'btn-success';
                                        $tipot = 'Pago';
                                        $id_fila = '';
                                    }
                                    if ($cc['tipo'] == 'cheque_rechazado') {
                                        $icono = "ti-shopping-cart-full";
                                        $colorbt = 'btn-warning';
                                        $tipot = 'Cheque Rechazado';
                                        $id_fila = 'mChequeRechazado';
                                    }
                                ?>
                                    <div class="sl-item">
                                        <div class="sl-left"> <button type="button" class="btn <?php echo $colorbt ?>  btn-circle btn-lg"><i class="<?php echo $icono ?>"></i> </button> </div>
                                        <div class="sl-right">
                                            <div><a href="javascript:void(0)" class="link"><?php echo $tipot ?></a> <span class="sl-date"><?php echo date('d/m/Y', strtotime($mov['fecha'])); ?></span>
                                                <p class="m-t-10">Detalle: <?php echo $mov['detalle']; ?></p>
                                            </div>
                                            <div class="like-comm m-t-20"> <a href="javascript:void(0)" class="link m-r-10"><b>Monto: $ <?php if ($mov['tipo'] == 'pedido' || $cc['tipo'] == 'cheque_rechazado') {
                                                                                                                                            echo number_format($mov['monto'], 0, ",", ".");
                                                                                                                                        } else {
                                                                                                                                            echo number_format($mov['monto2'], 0, ",", ".");
                                                                                                                                        } ?></b></a></div>
                                        </div>
                                    </div>
                                    <hr>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                    <!--second tab-->
                    <div class="tab-pane active" id="ccorriente" role="tabpanel">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Fecha</th>
                                                    <th>Detalle</th>
                                                    <th>Tipo</th>
                                                    <th>Monto</th>
                                                    <th>Saldo</th>
                                                    <th>#</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $filtro = "";
                                                $acumula_pagos = '0';
                                                $acumula_pedidos = '0';
                                                $saldo = '0';
                                                $consulta_corriente = $link->query("SELECT * FROM transaccion WHERE cliente ='$id' AND estado = 1 order by id asc $filtro ");
                                                while ($cc = mysqli_fetch_array($consulta_corriente)) {
                                                    if ($cc['tipo'] == 'pago') {
                                                        $acumula_pagos = $acumula_pagos + $cc['monto2'];
                                                        $saldo = $saldo - $cc['monto2'];
                                                        $tipo = "<span class='label label-success btn-block'>PAGO</span>";
                                                        $signo = '-$';
                                                    }
                                                    if ($cc['tipo'] == 'pedido') {
                                                        $acumula_pedidos = $acumula_pedidos + $cc['monto'];
                                                        $saldo = $saldo + $cc['monto'];
                                                        $tipo = "<span class='label label-danger btn-block'>PEDIDO</span>";
                                                        $signo = '$';
                                                    }
                                                    if ($cc['tipo'] == 'cheque_rechazado') {
                                                        $acumula_pedidos = $acumula_pedidos + $cc['monto'];
                                                        $saldo = $saldo + $cc['monto'];
                                                        $tipo = "<span class='label label-danger btn-block'>CHEQUE RECHAZADO</span>";
                                                        $signo = '$';
                                                    }
                                                ?>
                                                    <tr>

                                                        <td><?php if ($cc['tipo'] == 'pedido') {
                                                                echo '<a href="javascript:void(0)" onclick="abrirPedido(' . $cc['id'] . ')" data-toggle="modal" data-target="#myModal">' . date('d/m/Y', strtotime($cc['fecha'])) . '</a>';
                                                            } else if ($cc['tipo'] == 'cheque_rechazado') {
                                                                echo '<p style="color: #db3707">' . date('d/m/Y', strtotime($cc['fecha'])) . '</p>';
                                                            } else {
                                                                echo date('d/m/Y', strtotime($cc['fecha']));
                                                            } ?></td>
                                                        <td><?php
                                                            if ($cc['tipo'] == 'pedido') {
                                                                echo '<a href="javascript:void(0)" onclick="abrirPedido(' . $cc['id'] . ')" data-toggle="modal" data-target="#myModal">' . $cc['detalle'] . '</a>';
                                                            } else if ($cc['tipo'] == 'cheque_rechazado') {
                                                                echo '<p style="color: #db3707">' . $cc['detalle'] . '</p>';
                                                            } else {
                                                                echo $cc['detalle'];
                                                            }
                                                            ?></td>
                                                        <td><?php echo $tipo; ?></td>
                                                        <td style="text-align: right;"><?php if ($cc['tipo'] == 'pedido') {
                                                                                            echo '<a href="javascript:void(0)" onclick="abrirPedido(' . $cc['id'] . ')" data-toggle="modal" data-target="#myModal">' . $signo . number_format($cc['monto'], 0, ',', '.') . '</a>';
                                                                                        } else if ($cc['tipo'] == 'cheque_rechazado') {
                                                                                            echo '<p style="color: #db3707">' . $signo . number_format($cc['monto'], 0, ',', '.') . '</p>';
                                                                                        } else {
                                                                                            echo $signo . number_format($cc['monto2'], 0, ',', '.');
                                                                                        } ?></td>
                                                        <td style="text-align: right;"><?php
                                                                                        if ($cc['tipo'] == 'pedido') {
                                                                                            echo '<a href="javascript:void(0)" onclick="abrirPedido(' . $cc['id'] . ')" data-toggle="modal" data-target="#myModal">$' . number_format($saldo, 0, '', '.') . '</a>';
                                                                                        } else if ($cc['tipo'] == 'cheque_rechazado') {
                                                                                            echo '<p style="color: #db3707">$' . number_format($saldo, 0, '', '.') . '</p>';
                                                                                        } else {
                                                                                            echo number_format($saldo, 0, '', '.');
                                                                                        } ?></td>
                                                        <td><?php
                                                            if ($cc['tipo'] == 'pedido') {
                                                                echo '<a href="javascript:void(0)" onclick="abrirPedido(' . $cc['id'] . ')" data-toggle="modal" data-target="#myModal">' . $cc['id'] . '</a>';
                                                            } else if ($cc['tipo'] == 'cheque_rechazado') {
                                                                echo '<p style="color: #db3707">' . $cc['id'] . '</p>';
                                                            } else {
                                                                echo $cc['id'];
                                                            }
                                                            ?></td>
                                                    </tr>
                                                <?php }
                                                $balance_final = number_format($acumula_pedidos - $acumula_pagos, 0, '', '.');
                                                ?>
                                            </tbody>
                                        </table>
                                        <div class="row">
                                            <div class="col-md-4"><span class="text-danger font-weight-normal">Total de Pedidos: $-<?php echo number_format($acumula_pedidos, 0, '', '.') ?></span></div>
                                            <div class="col-md-4"><span class="text-success font-weight-normal">Total de Pagos: $<?php echo number_format($acumula_pagos, 0, '', '.') ?></span></div>
                                            <div class="col-md-4"><span class="text-info font-weight-normal">Balance Total: $<?php echo $balance_final ?></span></div>
                                            <br />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="tab-pane" id="notas" role="tabpanel">
                        <div class="card-body">
                            <form action="procesos/crud.php" method="post" class="form-horizontal form-material">
                                <input type="hidden" name="accion" value="up_notas">
                                <input type="hidden" name="id" value="<?php echo $_GET['id']; ?>">
                                <div class="form-group">
                                    Notas sobre el cliente
                                    <div class="col-md-12">
                                        <textarea class="form-control" rows="8" name="notas"><?php echo trim($row['notas_clientes']); ?></textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <button class="btn btn-success">Actualizar Notas</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Column -->
    </div>
</div>
<div class="container-fluid pedi" style="display: none; width: 90%;">

</div>
<script src="https://maps.googleapis.com/maps/api/js"></script>
<script>
    $('#balance').html('<?php echo $balance_final ?>');
    $('#balance2').html('<?php echo $balance_final ?>');

    function init_map() {

        var myOptions = {
            zoom: 16,

            center: new google.maps.LatLng(<?php echo $row['lat_clientes'] ?>, <?php echo $row['lng_clientes'] ?>),
            mapTypeId: google.maps.MapTypeId.ROADMAP

        };
        map = new google.maps.Map(document.getElementById("mapa"), myOptions);

        marker = new google.maps.Marker({
            map: map,

            position: new google.maps.LatLng(<?php echo $row['lat_clientes'] ?>, <?php echo $row['lng_clientes'] ?>)
        });

        infowindow = new google.maps.InfoWindow({
            content: "<?php echo mb_strtoupper($row['direccion_clientes']) . ' ' . $row['dirnum_clientes'] . ', ' . ucwords(strtolower($row['ciudad_nombre'])) . ', ' . $row['provincia_nombre']; ?>"

        });
        google.maps.event.addListener(marker, "click", function() {

            infowindow.open(map, marker);
        });

        infowindow.open(map, marker);
    }

    google.maps.event.addDomListener(window, 'load', init_map);
</script>
<script src="https://maps.google.com/maps/api/js?key=AIzaSyAn-9AHLG_ItYNb47GXXFiIPoH_UFn_jow"></script>