<?php
$id = $_GET['id'];
$con_detalle = $link->query("SELECT * FROM personal

  left join ciudad on ciudad.id_ciudad = personal.ciudad
  left join provincia on provincia.id_provincia = ciudad.provincia_id
  where personal.estado !='0' and personal.id='$id' ");
$row = mysqli_fetch_array($con_detalle);
?>
<div class="container-fluid">

    <div class="row page-titles">
        <div class="col-md-12">
            <h4 class="text-white">Detalle de <?php echo $row['nombre'] . ', ' . $row['apellido']; ?> || Balance Actual: $<span id="balance"></span></h4>
        </div>
        <div class="col-md-6">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
                <li class="breadcrumb-item"><a href="index.php?pagina=clientes">Personal</a></li>
                <li class="breadcrumb-item"><a href="#">Detalle</a></li>
            </ol>
        </div>
        <div class="col-md-6 text-right">
            <form class="app-search d-none d-md-block d-lg-block" method="get">
                <input type="hidden" name="pagina" value="personal">
                <input type="text" name="buscar" class="form-control" placeholder="Buscar...">
            </form>
        </div>
    </div>

    <div class="row">
        <!-- Column -->
        <div class="col-lg-3 col-xlg-3 col-md-3">
            <div class="card"> <img class="card-img" src="/img/areas/<?php if ($row['imagen_areas'] != '') {
                                                                            echo $row['imagen_areas'];
                                                                        } else {
                                                                            echo 'reparto.jpg';
                                                                        } ?>" height="456" alt="Imagen del Area">
                <div class="card-img-overlay card-inverse text-white social-profile d-flex justify-content-center">
                    <div class="align-self-center"> <img src="img/avatar/<?php echo $row['foto']; ?>" class="img-circle" width="100">
                        <br>
                        <h4 class="card-title" style="padding-top: 10px;"><?php echo $row['nombre'] . ', ' . $row['apellido']; ?></h4>
                        <!-- <p class="text-white"><?php echo $row['nombre_rubros']; ?><br/><?php echo $row['razon_comclientes']; ?></p> -->
                        <h4 class="card-title">Billetera Actual: <br />$<span id="balance2">0</span></h4>
                    </div>
                </div>

            </div>
              <?php
              if($_SESSION['tipo'] != 'Despacho'){
              echo '<button class="btn btn-block btn-success"  data-toggle="modal" data-target="#modificaPass">Cambiar Contraseña</button><br>';
              }
              ?>
            <div class="card">
                <div class="card-body"> <small class="text-muted">Correo </small>
                    <h6><?php echo $row['email']; ?></h6>
                    <small class="text-muted p-t-30 db">Telefono</small>
                    <h6><?php echo $row['telefono'] . '</h6>'; ?>
                        <?php if ($row['celular'] != '') { ?><small class="text-muted p-t-30 db">Celular</small>
                            <h6><?php echo $row['celular'] . '</h6>';
                            } ?>
                            <small class="text-muted p-t-30 db">Direccion</small>
                            <h6><?php echo $row['direccion'] . ' ' . $row['direccion_num'] . '<br/>  ' . ucwords(strtolower($row['ciudad_nombre'])) . ', ' . $row['provincia_nombre']; ?></h6>
                            <small class="text-muted p-t-30 db">Ubicacion Actual</small>
                            <div class="map-box">
                                <iframe src="https://maps.google.com/?q=<?php echo $row['latitud'] ?>,<?php echo $row['longitud'] ?>&output=embed" width="100%" height="150" frameborder="0" style="border:0" allowfullscreen=""></iframe>
                            </div>

                </div>
            </div>
        </div>

        <div class="col-lg-9 col-xlg-12 col-md-9">
            <div class="card">
                <!-- Nav tabs -->
                <ul class="nav nav-tabs profile-tab" role="tablist">
                    <li class="nav-item"> <a class="nav-link active" data-toggle="tab" href="#clientes" role="tab">Clientes</a> </li>
                    <!-- <li class="nav-item"> <a class="nav-link active" data-toggle="tab" href="#ccorriente" role="tab">Cuenta Corriente</a> </li> -->
                    <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#home" role="tab">Ultimos Movimientos</a> </li>
                    <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#notas" role="tab">Notas</a> </li>
                </ul>
                <!-- Tab panes -->
                <div class="tab-content">
                    <div class="tab-pane " id="home" role="tabpanel">
                        <div class="card-body">
                            <div class="profiletimeline">
                                <?php
                                $consulta_movimientos = $link->query("SELECT tipo, fecha, detalle, monto, monto2
                                                                              FROM transaccion
                                                                              WHERE estado = '1'  AND quien ='$id'
                                                                              UNION
                                                                              SELECT 'gasto' as tipo , fecha_gasto as fecha, nombre_tipogasto as detalle, monto_gasto as monto, monto_gasto as monto2
                                                                              FROM `gastos` INNER JOIN tipo_gastos on tipo_gastos.id_tipogasto = gastos.tipo_gasto
                                                                              WHERE gastos.estado_gasto='1' AND gastos.personal_gasto ='$id' ORDER BY `fecha` DESC limit 6
                                                                              ");


                                while ($mov = mysqli_fetch_array($consulta_movimientos)) {
                                    if ($mov['tipo'] == 'pedido') {
                                        $icono = "ti-shopping-cart-full";
                                        $colorbt = 'btn-warning';
                                        $tipot = 'Pedido';
                                    }
                                    if ($mov['tipo'] == 'pago') {
                                        $icono = "ti-money";
                                        $colorbt = 'btn-success';
                                        $tipot = 'Pago';
                                    }
                                    if ($mov['tipo'] == 'gasto') {
                                        $icono = "ti-money";
                                        $colorbt = 'btn-danger';
                                        $tipot = 'Gasto';
                                    }
                                ?>
                                    <div class="sl-item">
                                        <div class="sl-left"> <button type="button" class="btn <?php echo $colorbt ?>  btn-circle btn-lg"><i class="<?php echo $icono ?>"></i> </button> </div>
                                        <div class="sl-right">
                                            <div>
                                                <a href="javascript:void(0)" class="link"><?php echo $tipot ?></a>
                                                <span class="sl-date"><?php echo date('d/m/Y', strtotime($mov['fecha'])); ?></span>
                                                <p class="m-t-10">Detalle: <?php echo $mov['detalle']; ?></p>
                                            </div>
                                            <div class="like-comm m-t-20"> <a href="javascript:void(0)" class="link m-r-10"><b>Monto: $ <?php if ($mov['tipo'] == 'pedido') {
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
                    <div class="tab-pane active" id="clientes" role="tabpanel">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Nombre</th>
                                                    <th>Rubro</th>
                                                    <th>Telefono</th>
                                                    <th>Direccion</th>
                                                    <th>E-mail</th>
                                                    <th>Saldo</th>
                                                    <th>Estado</th>
                                                    <th>Acciones</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $personal_vende = $_GET['id'];
                                                $consulta_clientes = $link->query("SELECT * FROM clientes
                                                            LEFT JOIN ciudad ON ciudad.id_ciudad = clientes.ciudad_clientes
                                                            LEFT JOIN rubros ON rubros.id_rubros = clientes.rubro_com_clientes
                                                            WHERE estado_clientes !='0' and asignado_clientes='$personal_vende' ORDER BY apellido_clientes ASC ");

                                                while ($cli = mysqli_fetch_array($consulta_clientes)) {
                                                ?>
                                                    <tr>

                                                        <td> <?php echo strtoupper(utf8_encode($cli['razon_com_clientes'])); ?></td>
                                                        <td> <?php echo strtoupper(utf8_encode($cli['nombre_rubros'])) ?></td>
                                                        <td> <a href="tel:<?php if ($cli['telefono_comclientes'] != '') {
                                                                                echo $cli['telefono_comclientes'];
                                                                            } else {
                                                                                echo $cli['celular_clientes'];
                                                                            } ?>">
                                                                <?php if ($cli['celular_clientes'] != '') {
                                                                    echo $cli['celular_clientes'];
                                                                } else {
                                                                    echo $cli['telefono_clientes'];
                                                                } ?>
                                                            </a></td>
                                                        <td><?php echo utf8_encode($cli['direccion_clientes']) . ', ' . $cli['dirnum_clientes'] . ' ( ' . $cli['ciudad_alias'] . ' )' ?></td>
                                                        <td> <a href="mailto:<?php echo $cli['email_clientes'] ?>?subject=Big%20Pollo&body=Hola,<?php echo $cli['nombre_clientes'] ?>"><?php echo $cli['email_clientes'] ?>
                                                            </a></td>
                                                        <td>$<?php echo number_format(0, 0, '', '.'); ?></td>
                                                        <td><?php if ($row['estado_clientes'] == '2') {
                                                                echo '<span class="label label-danger">Inactivo</span>';
                                                            } else {
                                                                echo '<span class="label label-success">Activo</span>';
                                                            } ?></td>
                                                        <td><a class="btn-pure btn-outline-info edit-row-btn btn-lg" style="padding:0px;" href="index.php?pagina=clientes_edit&id=<?php echo $cli['id_clientes']; ?>" data-toggle="tooltip" data-original-title="Editar"><i class="ti-pencil" aria-hidden="true"></i></a></td>
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


                    <div class="tab-pane" id="ccorriente" role="tabpanel">
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
                                                ?>
                                                    <tr>

                                                        <td><?php echo date('d/m/Y', strtotime($cc['fecha'])); ?></td>
                                                        <td><?php echo $cc['detalle']; ?></td>
                                                        <td><?php echo $tipo; ?></td>
                                                        <td style="text-align: right;"><?php if ($cc['tipo'] == 'pedido') {
                                                                                            echo $signo . number_format($cc['monto'], 0, ',', '.');
                                                                                        } else {
                                                                                            echo $signo . number_format($cc['monto2'], 0, ',', '.');
                                                                                        } ?></td>
                                                        <td style="text-align: right;">$<?php echo number_format($saldo, 0, '', '.'); ?></td>
                                                        <td><?php echo $cc['id']; ?></td>
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

<!-- change pass -->
<div class="modal fade" id="modificaPass" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
<div class="modal-dialog" role="document">
<div class="modal-content">
<div class="modal-header">

  <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
    <span aria-hidden="true">&times;</span>
  </button>
</div>
<div class="modal-body">
  <h4 ><center>Ingrese la nueva contraeña</center></h4>
  <form>
    <div class="form-group">
   <label for="newpassword">Nueva Contraseña</label>
    <input type="password" name="pass" class="form-control" id='newpassword'>
     <label for="retrypassword">Reingrese la Contraseña</label>
    <input type="password" name="retrypass" class="form-control" id='retrypassword'>
  </div>
  </form>
</div>
<div class="modal-footer">
  <a href="#" onclick="change_pass()" class="btn btn-success">Cambiar Contraseña</a>
  <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
</div>
</div>
</div>
</div>


<script>
    $('#balance').html('<?php echo $balance_final ?>');

    function change_pass(){
        if($('#newpassword').val()=='' || $('#retrypassword').val()==''){
          alert('Complete los campos');
          return;
      }
      if($('#newpassword').val() != $('#retrypassword').val()){
        alert('las contraseñas son distintas');
        return;
      }else{
        var userId = <?php echo $_GET['id'] ?> ;
        var newpass = $('#newpassword').val();
        var tipo = '<?php echo $row['area'] ?>';
        var string = "accion=changePassWord&id="+userId+"&newpass="+newpass+"&tipo="+tipo;
              $.ajax({
                  type: "POST",
                  url: "procesos/crud.php?",
                  data: string,
                  success: function(data){
                  if(data=='TRUE'){
                   alert('La contraseña se modifico correctamente');
                   $('#modificaPass').modal('hide');
                   } else {
                      alert('No se pudo modificar la contraseña');
                   }
                 }
              });
      }
    }
</script>
