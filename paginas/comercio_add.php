<?php
$clientedata = explode('@',$_GET['id']);
?>

<div class="container-fluid">
  <div class="row page-titles">
    <div class="col-md-12">
      <h4 class="text-white">Asignar Comercio a <?php echo $clientedata[1]?></h4>
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
        <h4 class="card-title" >Ingrese los datos del Comercio</h4>
        <form class="form-horizontal form-material">
          <div class="row">
          <input type="hidden" value="<?php echo $clientedata[0]?>" id="cliente" name="cliente">
          <div class="col-md-12 m-b-20">
            <input placeholder="Nombre del Comercio" type="text" value="<?php echo $row['razon_comclientes'] ;?>" name="razon" class="form-control" required />
          </div>
          <div class="col-md-4 m-b-20">
            <select class="form-control" name="condicioniva" required>
              <option value="" disabled selected>Seleccione condicion IVA</option>
              <option value="cf">Consumidor Final</option>
              <option value="ri">Responsable Inscripto</option>
              <option value="nor">No Responsable</option>
              <option value="m">Responsable Monotributista</option>
              <option value="e">Excento</option>
            </select>
          </div>
          <div class="col-md-4 m-b-20">
            <input type="number" placeholder="CUIT/CUIL (sin guion)" maxlength="11" class="form-control" name="cuit" />
          </div>
          <div class="col-md-4 m-b-20">
            <select class="form-control" name="perifact" required>
              <option value="" disabled selected>Periodo Facturacion</option>
              <option value="n">No Indica</option>
              <option value="a">En el acto</option>
              <option value="q">Quincenal</option>
              <option value="m">Mensual</option>

            </select>
          </div>
          <div class="col-md-6 m-b-20">
          <input type="phone" placeholder="Telefono comercial"  class="form-control"  name="telefono" />
        </div>
        <div class="col-md-6 m-b-20">
          <select class="form-control" name="rubro" >
            <option value="" disabled selected>Seleccione un rubro comercial</option>
              <?php
              $con_rubro= $link->query("SELECT * FROM rubros where estado_rubros ='1' order by nombre_rubros asc");
              while ($rubro  = mysqli_fetch_array($con_rubro)){echo '
              <option value="'.$rubro['id_rubros'].'">'.$rubro['nombre_rubros'].'</option>';}
              ?>
            <option value='2545'>Otros</option>
          </select>
        </div>
      </div>

        <h4>Direccion Comercial</h4>
        <div class="row">
        <div class="col-md-6 m-b-20">
          <select class="form-control provincia" onchange="buscaciudad()" name="provincia" id="provincia" required="">
            <option value="" disabled="" selected="">Seleccione una Provincia</option>
            <?php $consul_provincia1 = $link->query("SELECT * FROM provincia order by provincia_nombre asc") or die(mysqli_error());
            while($provincia= mysqli_fetch_array($consul_provincia1)){echo '
            <option value="'.$provincia['id_provincia'].'"';
              if($provincia['id_provincia']==$clientedata[2]){echo' selected';}
            echo'>'.$provincia['provincia_nombre'].'</option>';} ?>
          </select>
        </div>
        <div class="col-md-6 m-b-20">
          <select id="ciudadcom" name="ciudad" class="form-control ciudad" required="" disabled="disabled">
            <?php $consul_ciudades3 = $link->query("SELECT * FROM ciudad order by ciudad_nombre asc") or die(mysqli_error());
            echo '<option value="" disabled="" selected="">Seleccione una Localidad</option>';
            while($ciudad= mysqli_fetch_array($consul_ciudades3)){echo '<option value="'.$ciudad['id_ciudad'].'"';
              if($ciudad['id_ciudad']==$clientedata[3]){echo' selected';}
              echo'>'.$ciudad['ciudad_nombre'].'</option>';} ?>
          </select>
        </div>
        <div class="col-md-6 m-b-20">
          <input type="text" placeholder="Calle Nombre" value="<?php echo $clientedata[4]?>" class="form-control" name="direccion"  value="<?php echo  $row['direccion_comclientes']?>"  required/>
        </div>
        <div class="col-md-6 m-b-20">
          <input type="number" placeholder="Numero"  value="<?php echo $clientedata[5]?>" class="form-control" name="dirnum"  value="<?php echo  $row['dirnum_comclientes']?>"  required/>
        </div>


        <h4>Asignar a</h4>
        <div class="col-md-12 m-b-20">
          <select class="form-control" name="vendedor" >
            <option value="" disabled selected >Seleccione un Personal</option>
            <?php $con_vendedor = $link->query("SELECT * FROM usuarios WHERE usuarios.estado_usuarios = '1' and id !='0' order by nombre ASC");
            while ($vendedor  = mysqli_fetch_array($con_vendedor)){echo '
            <option value="'.$vendedor['id'].'">'.$vendedor['nombre'].'</option>';}
            ?>
          </select>
        </div>
      <hr/>
      <a href="#" id="agregar_comercio" class="btn btn-info waves-effect">Guardar</a>
      <a href="index.php?pagina=clientes" class="btn btn-danger waves-effect" >Cancelar</a>
    </div>
    </form>
  </div>
</div>
</div>
</div>
