
	<script src="./js/funciones.js?v=1"></script>
	<div class="container-fluid esconder-imprimir">

    <div class="row page-titles">
      <div class="col-md-12">
        <h4 class="text-white">Listado de Adelantos</h4>
      </div>
      <div class="col-md-6">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
          <li class="breadcrumb-item "><a href="#">Adelantos</a></li>
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
            <div class="row">
              <div class="col-md-2">
                <h4 class="card-title">Listado</h4>
              </div>
              <?php
              if (isset($_GET['d'])) {
                    $desde = $_GET['d'];
                  } else {
                    $desde = date('Y-m-01');
                  }
                  if (isset($_GET['h'])) {
                    $hasta = $_GET['h'];
                  } else {
                    $hasta = date('Y-m-d');
                  }
                  if (isset($_GET['v']) && $_GET['v'] !== '') {
                      $vendedor = ' and adelantos.personal_adelanto = ' . $_GET['v'];
                  } else {
                      $vendedor = '';
                  }

              ?>
              <div class="col-md-2"><small class="form-control-feedback"> Desde </small>
                <input class="form-control filtro" type="date" id="d" name="d" value="<?php if (isset($_GET['d'])) {      echo $_GET['d'];     } else {echo date('Y-m-01');} ?>">
              </div>
              <div class="col-md-2"><small class="form-control-feedback"> Hasta </small>
                <input class="form-control filtro" type="date" id="h" name="h" value="<?php if (isset($_GET['h'])) {echo $_GET['h'];} else {echo date('Y-m-d');} ?>">
              </div>
              <div class="col-md-2"><small class="form-control-feedback"> Personal </small><br>
                <select class="form-control" id="vendedorsel">
                  <option value='' selected>Todos</option>
                  <?php
                  $busca_vende = $link->query("SELECT CONCAT(nombre,', ',apellido) as nombre, id FROM `personal` WHERE `estado` LIKE '1' and email2_per = '1' order by nombre asc, apellido ASC");
                  while ($row = mysqli_fetch_array($busca_vende)) {

                    echo '<option value="' . $row['id'] . '"';
                    if (isset($_GET['v'])) {
                      if ($_GET['v'] == $row['id']) {
                      echo ' selected ';
                    }
                    }
                    
                    echo '>' . $row['nombre'] . '</option>';
                  }
                  ?>
                </select>

              </div>
              <div class="col-md-2" style="align-self: center;">
                <a href="#" onclick="filtrar_vende()" class="btn btn-info btn-lg" role="button">Filtrar</a>
              </div>
              <div class="col-md-2" style="align-self: center;">
                <?php if (isset($_GET['d']) || isset($_GET['h'])) { ?><a href="index.php?pagina=adelanto">Quitar Filtros</a><?php } ?>
                <div id="total_periodo">Total $</div>
              </div>

            </div>
            <h6 class="card-subtitle"></h6>

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
                    $hasta = $_GET['h'];
                  } else {
                    $hasta = date('Y-m-d');
                  }
                  if (isset($_GET['v']) && $_GET['v'] !== '') {
                      $vendedor = ' and adelantos.personal_adelanto = ' . $_GET['v'];
                  } else {
                      $vendedor = '';
                  }

              
                  $acumula = 0;
                  $con_adelanto = $link->query("
                                        SELECT * FROM `adelantos`
                                            left join personal on personal.id = adelantos.personal_adelanto
                                            WHERE adelantos.estado_ad='1' and date(adelantos.fecha_adelanto) >= '$desde' and date(adelantos.fecha_adelanto) <= '$hasta' $vendedor order by adelantos.fecha_adelanto DESC");
                  while ($row = mysqli_fetch_array($con_adelanto)) {
                    $preciocrudo = number_format($row['monto_adelanto'], 0, '.', '');
                    $acumula = ($acumula + $preciocrudo);
                    $precioAd =number_format($preciocrudo, 0, '', '.');
                  ?>
                    <tr>
                                        <td class="font-weight-normal"><span class="footable-toggle"></span><?php echo date('d/m/Y',strtotime($row['fecha_adelanto']))?></td>
                                        <td class="font-weight-normal"><span class="footable-toggle"></span><?php echo $row['nombre'].' '.$row['apellido']?></td>
                                        <td class="font-weight-normal"><span class="footable-toggle"></span><?php echo $row['tipo_adelanto'];
                                            ?></td>
                                        <td class="font-weight-normal"><span class="footable-toggle"></span><?php echo $row['observacion_ad']?></td>
                                        <td class="font-weight-normal"><span class="footable-toggle"></span>$<?php echo $precioAd?></td>
                                        <td class="font-weight-normal"><span class="footable-toggle"></span><?php echo $row['id_adelanto']?></td>
                                    </tr>
                                    <?php
                                    }
                                    ?>
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
    $('#total_periodo').html('<span class="btn btn-success pull-right"><b>TOTAL: $<?php echo number_format($acumula, 0, '', '.'); ?></b></span>')

    // bind change event to select
    function filtrar_vende() {
      var datodesde = $('#d').val(); // get selected value
      var datohasta = $('#h').val(); // get selected value
      var datovendedor = $('#vendedorsel option:selected').val(); // get selected value


      
      if (datodesde) { // require a URL
        window.location = 'index.php?pagina=adelanto&d=' + datodesde + '&h=' + datohasta + '&v=' + datovendedor; // redirect
      }
      return false;
    };
  </script>