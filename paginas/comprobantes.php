<div class="container-fluid">
	<div class="row page-titles">
      <div class="col-md-12">
        <h4 class="text-white">Listado de Comprobantes</h4>
      </div>
      <div class="col-md-6">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
          <li class="breadcrumb-item "><a href="#">Comprobantes</a></li>
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
                  if (isset($_GET['p']) && $_GET['p'] !== '') {
                      $vendedor = ' and proveedores.id_proveedor = ' . $_GET['p'];
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
              <div class="col-md-2"><small class="form-control-feedback"> Proveedor </small><br>
                <select class="form-control" id="vendedorsel">
                  <option value='' selected>Todos</option>
                  <?php
                  $busca_vende = $link->query("SELECT razon_com_proveedor, id_proveedor FROM `proveedores` WHERE `estado_proveedor` LIKE '1' order by razon_com_proveedor asc");
                  while ($row = mysqli_fetch_array($busca_vende)) {

                    echo '<option value="' . $row['id_proveedor'] . '"';
                    if (isset($_GET['p'])) {
                      if ($_GET['p'] == $row['id_proveedor']) {
                      echo ' selected ';
                    }
                    }
                    
                    echo '>' . $row['razon_com_proveedor'] . '</option>';
                  }
                  ?>
                </select>

              </div>
              <div class="col-md-2" style="align-self: center;">
                <a href="#" onclick="filtrar_vende()" class="btn btn-info btn-lg" role="button">Filtrar</a>
              </div>
              <div class="col-md-2" style="align-self: center;">
                <?php if (isset($_GET['d']) || isset($_GET['h'])) { ?><a href="index.php?pagina=comprobantes">Quitar Filtros</a><?php } ?>
              </div>

            </div>
            <h6 class="card-subtitle"></h6>

            <div class="table-responsive">
              <table id="clientes_lista" class="table m-t-30 table-hover contact-list footable-loaded footable" data-page-size="10">
              	<thead>
                  <tr>
                      <th class="footable-sortable">Fecha<span class="footable-sort-indicator"></span></th>
                      <th class="footable-sortable">Proveedor<span class="footable-sort-indicator"></span></th>
                      <th class="footable-sortable">Tipo Comprobante<span class="footable-sort-indicator"></span></th>
                      <th class="footable-sortable">Nro Comprobante<span class="footable-sort-indicator"></span></th>
                      <th class="footable-sortable">Acciones<span class="footable-sort-indicator"></span></th>
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
                  if (isset($_GET['p']) && $_GET['p'] !== '') {
                      $vendedor = ' and proveedores.id_proveedor = ' . $_GET['p'];
                  } else {
                      $vendedor = '';
                  }
                  $con_compra = $link->query("
                    SELECT * FROM `compra_mercaderia`
                        left join proveedores on proveedores.id_proveedor = compra_mercaderia.prov_compram
                        left join tipo_comprobantes on proveedores.id_proveedor = tipo_comprobantes.id_comprobantes
                             WHERE compra_mercaderia.estado_compram='1' and date(compra_mercaderia.fecha_compram) >= '$desde' and date(compra_mercaderia.fecha_compram) <= '$hasta' $vendedor order by compra_mercaderia.fecha_compram DESC");
                  while ($row = mysqli_fetch_array($con_compra)) {         
                 ?>
                 <tr>
                     <td class="font-weight-normal"><span class="footable-toggle"></span><?php echo date('d/m/Y',strtotime($row['fecha_compram']))?></td>
                     <td class="font-weight-normal"><span class="footable-toggle"></span><?php echo $row['razon_com_proveedor']?></td>
                     <td class="font-weight-normal"><span class="footable-toggle"></span><?php echo $row['nombre_comprobantes'];
                                            ?></td>
                     <td class="font-weight-normal"><span class="footable-toggle"></span><?php echo $row['numcom_compram']?></td>
                     <td>
                        <a class="btn-pure btn-outline-success success-row-btn btn-lg" style="padding:0px;" href="index.php?pagina=compras_view&id=<?=$row['id_compram']?>"><i class="ti-eye" aria-hidden="true"></i></a>
                          
                     </td>
                     <td class="font-weight-normal"><span class="footable-toggle"></span><?php echo $row['id_compram']?></td>
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
    // bind change event to select
    function filtrar_vende() {
      var datodesde = $('#d').val(); // get selected value
      var datohasta = $('#h').val(); // get selected value
      var datovendedor = $('#vendedorsel option:selected').val(); // get selected value


      
      if (datodesde) { // require a URL
        window.location = 'index.php?pagina=comprobantes&d=' + datodesde + '&h=' + datohasta + '&p=' + datovendedor; // redirect
      }
      return false;
    };
  </script>