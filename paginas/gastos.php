<div class="container-fluid">
    <div class="row page-titles">
        <div class="col-md-12">
            <h4 class="text-white">Listado de Gastos</h4>
        </div>
        <div class="col-md-6">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
                <li class="breadcrumb-item"><a href="#">Gastos</a></li>
            </ol>
        </div>
        <div class="col-md-6 text-right">
            <form class="app-search d-none d-md-block d-lg-block">
                <input type="text" class="form-control" placeholder="Buscar...">
            </form>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Listado</h4>
                    <div class="row">
                        <div class="col-md-2">
                            <small class="form-control-feedback"> Desde </small>
                            <input class="form-control filtro" type="date" id="d" name="d" value="<?php if(isset($_GET['d'])){echo $_GET['d'];}else{echo date('Y-m-01');}?>">
                        </div>
                        <div class="col-md-2">
                            <small class="form-control-feedback"> Hasta </small>
                            <input class="form-control filtro" type="date" id="h" name="h" value="<?php if(isset($_GET['h'])){echo $_GET['h'];}else{echo date('Y-m-d');}?>">
                        </div>
                        <div class="col-md-2" style="margin-top:17px">
                            <a href="#" onclick="filtrar_gasto()" class="btn btn-info btn-lg" role="button" >Filtrar</a>
                                  <?php if(isset($_GET['d']) || isset($_GET['h'])){?>
                            <a href="index.php?pagina=gastos">Quitar Filtros</a><?php }?>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table id="clientes_lista" class="table m-t-30 table-hover contact-list footable-loaded footable" data-page-size="10">
                            <thead>
                                <tr>
                                    <th class="footable-sortable">Fecha<span class="footable-sort-indicator"></span></th>
                                    <th class="footable-sortable">Personal<span class="footable-sort-indicator"></span></th>
                                    <th class="footable-sortable">Tipo<span class="footable-sort-indicator"></span></th>
                                                <!-- <th class="footable-sortable">Cliente<span class="footable-sort-indicator"></span></th> -->
                                    <th class="footable-sortable">Observación<span class="footable-sort-indicator"></span></th>
                                    <th class="footable-sortable">Monto<span class="footable-sort-indicator"></span></th>
                                              <!--  <th class="footable-sortable">Estado<span class="footable-sort-indicator"></span></th> -->
                                    <th class="footable-sortable">#<span class="footable-sort-indicator"></span></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    if (isset($_GET['d'])) {
                                        $desde = $_GET['d'];
                                    } else {
                                        $desde = date('Y-m-01');
                                    }
                                    if (isset($_GET['h'])) {
                                        $hasta = $_GET['h'] . ' ' . date('H:i:s');
                                    } else {
                                        $hasta = date('Y-m-d H:i:s');
                                    }

                                    $con_gastos = $link->query("
                                        SELECT * FROM `gastos`
                                            left join usuarios on usuarios.id = gastos.quien_gasto
                                            WHERE gastos.estado_gasto='1' and date(gastos.fecha_gasto) >= '$desde' and date(gastos.fecha_gasto) <= '$hasta' order by gastos.fecha_gasto DESC");
                                    while ($row = mysqli_fetch_array($con_gastos)) {
                                    ?>
                                    <tr>
                                        <td class="font-weight-normal"><span class="footable-toggle"></span><?php echo date('d/m/Y',strtotime($row['fecha_gasto']))?></td>
                                        <td class="font-weight-normal"><span class="footable-toggle"></span><?php echo $row['nombre']?></td>
                                        <td class="font-weight-normal"><span class="footable-toggle"></span><?php if($row['tipo_gasto']== 0){echo "Salida";}
                                            ?></td>
                                        <td class="font-weight-normal"><span class="footable-toggle"></span><?php echo $row['observacion_gasto']?></td>
                                        <td class="font-weight-normal"><span class="footable-toggle"></span>$<?php
                                        $monto_gastado = 
                                        number_format($row['monto_gasto'], 0, '', '.');
                                         echo $monto_gastado;?></td>
                                        <td class="font-weight-normal"><span class="footable-toggle"></span><?php echo $row['id_gasto']?></td>
                                    </tr>
                                    <?php
                                    }
                                    ?>

                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>

    // bind change event to select
    function filtrar_gasto() {
      var datodesde = $('#d').val(); // get selected value
      var datohasta = $('#h').val(); // get selected value
      if (datodesde) { // require a URL
        window.location = 'index.php?pagina=gastos&d=' + datodesde + '&h=' + datohasta
      }
      return false;
    };
  </script>