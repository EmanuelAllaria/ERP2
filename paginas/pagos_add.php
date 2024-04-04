            <div class="container-fluid">
                <div class="row page-titles">
                    <div class="col-md-12">
                        <h4 class="text-white">Nuevo Ingreso</h4>
                    </div>
                    <div class="col-md-6">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
                            <li class="breadcrumb-item"><a href="index.php?pagina=pedidos">Pagos</a></li>
                            <li class="breadcrumb-item active">Nuevo</li>
                        </ol>
                    </div>
                    <div class="col-md-6 text-right">
                        <form class="app-search d-none d-md-block d-lg-block">
                            <input type="text" class="form-control" placeholder="Buscar...">
                        </form>
                    </div>
                </div>
                <div class="row">
                  <div class="card" style="width: 100%;">
                      <div class="card-body">
                        <h4 class="card-title">Complete el formulario de Pago</h4>

                          <form id="add_pago_form" action="procesos/crud.php" method="post" class="form-horizontal form-material">
                            <div class="modal-body">
                                <input type="hidden" name="accion" value="add_pago">
                                <input type="hidden" id="comercio_pago" name="comercio_pago" >
                                <div id="last-selected"></div>
                                <div class="row">
                                  <div class="col-md-2 m-b-20">
                                    <input type="date" name="fecha_pago" class="form-control fecha" value="<?php echo date("Y-m-d"); ?>" required="">
                                  </div>

                                  <div class="col-md-10 m-b-20">
                                    <select name="comercio_toma" id="comercio_toma" placeholder="Seleccione un cliente" class="form-control comercio" required="">

                                      <?php
                                        $consul_comercios = $link->query("SELECT * FROM clientes 
                                        where estado_clientes ='1' order by razon_com_clientes ASC");
                                        while ($row= mysqli_fetch_array($consul_comercios)){
                                        echo '<option value="'.$row['id_clientes'].'">'.utf8_encode($row['razon_com_clientes']).'</option>';
                                        } ?>
                                    </select>
                                  </div>
                                  <div class="col-md-12 m-b-20">
                                    <textarea placeholder="ingrese el detalle del pago" rows="5" class="form-control detalle" name="detalle_pago" required=""></textarea>
                                  </div>
                                   
                                  <div class="col-md-12 m-b-20">
                                    <input type="text" placeholder="Monto del Pago" class="form-control monto" name="monto_pago" required="">
                                  </div>
                                </div>
                                <button type="submit" id="agregar_pago" class="btn btn-info waves-effect">Ingresar Pago</button>
                                  <a href="?pagina=pagos" class="btn btn-danger waves-effect">Cancelar</a>
                                </div>
</form>

                      
            </div>
          </div>
        </div>
      </div>
      <script>

      $('#comercio_toma')
    .editableSelect()
    .on('select.editable-select', function (e, li) {
        $('#comercio_pago').val(li.val());
    });
      </script>
