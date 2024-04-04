<?php
$cliente = $_GET['id'];
$consulto_comercio = $link->query("SELECT * FROM clientes inner join clientes_comercios on clientes.id_clientes = clientes_comercios.cliente_comclientes left join ciudad on ciudad.id_ciudad = clientes_comercios.ciudad_comclientes WHERE estado_clientes ='1' and id_clientes ='$cliente' ");

?>

<div class="container-fluid">
  <div class="row page-titles">
    <div class="col-md-12">
      <h4 class="text-white">Editar Comercio de <?php echo $clientedata[1]?></h4>
    </div>
    <div class="col-md-6">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
        <li class="breadcrumb-item"><a href="index.php?pagina=clientes">Clientes</a></li>
        <li class="breadcrumb-item active">Comercio</li>
      </ol>
    </div>
    <div class="col-md-6 text-right">
      <form class="app-search d-none d-md-block d-lg-block">
        <input type="text" class="form-control" placeholder="Buscar...">
      </form>
    </div>
  </div>

  <div class="row">
    <div class="card">
      <div class="card-body">
        <?php if($row= mysqli_fetch_array($consulto_comercio)){ ?>
        <h4 class="card-title" >Ingrese los datos del Comercio</h4>
        <form class="form-horizontal form-material">
          <input type="hidden" value="<?php echo $row['id_comclientes'] ;?>" id="comercio_id_come">
          <input type="hidden" value="<?php echo $row['id_clientes'] ;?>" id="cliente_id_come">
          <div class="row">
          <div class="col-md-12 m-b-20">
            <input placeholder="Nombre del Comercio" type="text" value="<?php echo $row['razon_comclientes'] ;?>" id="razon_come" class="form-control" required />
          </div>
          <div class="col-md-4 m-b-20">
            <select class="form-control" id="condicioniva_come" required>
              <option value="" disabled selected>Seleccione condicion IVA</option>
              <option value="cf"<?php if($row['condicioniva_comclientes']=='cf'){echo' selected';}?>>Consumidor Final</option>
              <option value="ri"<?php if($row['condicioniva_comclientes']=='ri'){echo' selected';}?>>Responsable Inscripto</option>
              <option value="nor"<?php if($row['condicioniva_comclientes']=='nor'){echo' selected';}?>>No Responsable</option>
              <option value="m"<?php if($row['condicioniva_comclientes']=='m'){echo' selected';}?>>Responsable Monotributista</option>
              <option value="e"<?php if($row['condicioniva_comclientes']=='e'){echo' selected';}?>>Excento</option>
            </select>
          </div>
          <div class="col-md-4 m-b-20">
            <input type="number" placeholder="CUIT/CUIL (sin guion)" maxlength="11" class="form-control" value="<?php echo $row['cuitcuil_comclientes'] ;?>" id="cuit_come" />
          </div>
          <div class="col-md-4 m-b-20">
            <select class="form-control" id="perifact_come" required>
              <option value="" disabled selected>Periodo Facturacion</option>
              <option value="n"<?php if($row['perifact_comclientes']=='n'){echo' selected';}?>>No Indica</option>
              <option value="a"<?php if($row['perifact_comclientes']=='a'){echo' selected';}?>>En el acto</option>
              <option value="q"<?php if($row['perifact_comclientes']=='q'){echo' selected';}?>>Quincenal</option>
              <option value="m"<?php if($row['perifact_comclientes']=='m'){echo' selected';}?>>Mensual</option>

            </select>
          </div>
          <div class="col-md-6 m-b-20">
          <input type="phone" placeholder="Telefono comercial"  value="<?php echo $row['telefono_comclientes'] ;?>" class="form-control"  id="telefono_come" />
        </div>
        <div class="col-md-6 m-b-20">
          <select class="form-control" id="rubro_come" >
            <option value="" disabled selected>Seleccione un rubro comercial</option>
              <?php
              $con_rubro= $link->query("SELECT * FROM rubros where estado_rubros ='1' order by nombre_rubros asc");
              while ($rubro  = mysqli_fetch_array($con_rubro)){echo '
              <option value="'.$rubro['id_rubros'].'"';
                if($rubro['id_rubros']==$row['rubro_comclientes']){echo 'selected'; }
              echo'>'.$rubro['nombre_rubros'].'</option>';}
              ?>

          </select>
        </div>
      </div>

        <h4>Direccion Comercial</h4>
        <div class="row">
        <div class="col-md-6 m-b-20">
          <select class="form-control provincia" onchange="buscaciudad()" id="provincia_come" required="">
            <option value="" disabled="" selected="">Seleccione una Provincia</option>
            <?php $consul_provincia1 = $link->query("SELECT * FROM provincia order by provincia_nombre asc") or die(mysqli_error());
            while($provincia= mysqli_fetch_array($consul_provincia1)){echo '
            <option value="'.$provincia['id_provincia'].'"';
            if($provincia['id_provincia']==$row['provincia_id']){echo 'selected'; }
            echo'>'.$provincia['provincia_nombre'].'</option>';} ?>
          </select>
        </div>
        <div class="col-md-6 m-b-20">
          <select id="ciudadcom_come" class="form-control ciudad" required="" >
            <?php $consul_ciudades3 = $link->query("SELECT * FROM ciudad order by ciudad_nombre asc") or die(mysqli_error());
            echo '<option value="" disabled="" selected="">Seleccione una Localidad</option>';
            while($ciudad= mysqli_fetch_array($consul_ciudades3)){echo '<option value="'.$ciudad['id_ciudad'].'"';
            if($ciudad['id_ciudad']==$row['ciudad_comclientes']){echo ' selected'; }
            echo'>'.$ciudad['ciudad_nombre'].'</option>';} ?>
          </select>
        </div>
        <div class="col-md-6 m-b-20">
          <input type="text" placeholder="Calle Nombre"  class="form-control" id="direccion_come"  value="<?php echo  $row['direccion_comclientes']?>"  required/>
        </div>
        <div class="col-md-6 m-b-20">
          <input type="number" placeholder="Numero"  class="form-control" id="dirnum_come"  value="<?php echo  $row['dirnum_comclientes']?>"  required/>
        </div>


        <h4>Asignar a</h4>
        <div class="col-md-12 m-b-20">
          <select class="form-control" id="vendedor_come" >
            <option value="" disabled selected >Seleccione un Personal</option>
            <?php $con_vendedor = $link->query("SELECT * FROM usuarios WHERE usuarios.estado_usuarios = '1' and id !='0' order by nombre ASC");
            while ($vendedor  = mysqli_fetch_array($con_vendedor)){echo '
            <option value="'.$vendedor['id'].'"';
            if($vendedor['id']==$row['vendedor_comclientes']){echo 'selected'; }
            echo'>'.$vendedor['nombre'].'</option>';}
            ?>
          </select>
        </div>
      <hr/>
      <a href="javascript:void(0)" onclick="modificar_comercio()" class="btn btn-info waves-effect">Guardar</a>
      <a href="index.php?pagina=clientes" class="btn btn-danger waves-effect" >Cancelar</a>
    </div>
    </form>
  <?php }else {echo '<h4 class="card-title" >No se encontro el Comercio</h4>';} ?>
  </div>
</div>
</div>
</div>
