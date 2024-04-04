            <div class="container-fluid">
                <div class="row page-titles">
                    <div class="col-md-12">
                        <h4 class="text-white">Nuevo Pedido</h4>
                    </div>
                    <div class="col-md-6">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
                            <li class="breadcrumb-item"><a href="index.php?pagina=pedidos">Pedidos</a></li>
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
                        <h4 class="card-title">Complete el formulario de pedido</h4>
                              <form id="add_pedido_form" action="procesos/crud.php" method="post" class="form-horizontal form-material">
                            <div class="modal-body">
                              <input type="hidden" name="accion" value="add_pedido">
                              <input type="hidden" name="comercio" id="comercio" >
                                <div class="row">
                                  <div class="col-md-2 m-b-20">
                                    <input type="date" name="fechapedido" class="form-control fecha" value="<?php echo date("Y-m-d"); ?>" required="">
                                  </div>

                                  <div class="col-md-10 m-b-20">
                                    <select name="comercio_tomo" id="comercio_tomo" placeholder="Seleccione un comercio" class="form-control comercio" required="">

                                      <?php

                                      $consul_comercios = $link->query("SELECT * FROM clientes
                                        inner join clientes_comercios on clientes_comercios.cliente_comclientes = clientes.id_clientes
                                        where clientes_comercios.estado_comclientes ='1' order by clientes_comercios.razon_comclientes ASC");
                                      while ($row= mysqli_fetch_array($consul_comercios)){
                                       echo '<option value="'.$row['id_clientes'].'">'.utf8_encode($row['razon_comclientes']).'</option>';
                                     } ?>
                                    </select>
                                  </div>

                                  <div class="col-md-12 m-b-20">
                                    <textarea placeholder="ingrese el detalle del pedido" rows="5" class="form-control detalle" name="detalle" required=""></textarea>
                                  </div>
                                    <div class="col-md-12 m-b-20">
                                    <select name="tipo_pedido" id="tipo_pedido" class="form-control tipo_pedido" required="">
                                      <option value="" disabled="" selected="">Seleccione Tipo de Producto</option>
                                      <?php
                                      $consul_rubros = $link->query("SELECT * FROM categorias
                                        WHERE estado_categoria ='1' order by titulo_categoria ASC");
                                        while ($row= mysqli_fetch_array($consul_rubros)){
                                          echo '<option value="'.$row['id_categoria'].'" ';
                                          if($consu['tipo_pedido']==$row['id_categoria']){echo ' selected ';}
                                          echo '>'.$row['titulo_categoria'].'</option> ';
                                        }
                                      ?>
                                    </select>
                                  </div>
                                  <div class="col-md-12 m-b-20">
                                    <input type="text" placeholder="Monto del Pedido" class="form-control monto" name="monto" required="">
                                  </div>
                                </div>



                              <button type="submit" id="agregar_pedido" class="btn btn-info waves-effect">Continuar</button>
                              <a href="?pagina=pedidos" class="btn btn-danger waves-effect">Cancelar</a>
                      </div>
</form>

                      
            </div>
          </div>
        </div>
      </div>
      <script>

      $('#comercio_tomo')
    .editableSelect()
    .on('select.editable-select', function (e, li) {
        $('#comercio').val(li.val());
    });
      </script>
