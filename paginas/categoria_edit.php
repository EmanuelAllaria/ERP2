<?php
$id = $_GET['id'];
$consul_id = $link->query("SELECT * FROM categorias  WHERE estado_categoria ='1' and id_categoria ='$id' ");
$cat = mysqli_fetch_array($consul_id);
?>
<style>
  .selecticon {
    font-family: 'FontAwesome', 'Second Font name';
    font-size: large;
    text-align: center;
  }
</style>
<div class="container-fluid">
  <div class="row page-titles">
    <div class="col-md-12">
      <h4 class="text-white">Nueva Categoria</h4>
    </div>
    <div class="col-md-6">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
        <li class="breadcrumb-item"><a href="index.php?pagina=pedidos">Categorias</a></li>
        <li class="breadcrumb-item active">Nueva</li>
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
        <h4 class="card-title">Nueva Categoria</h4>

        <form id="add_pago_form" action="procesos/crud.php" method="post" class="form-horizontal form-material">
          <div class="modal-body">
            <input type="hidden" name="accion" value="edit_cate">
            <input type="hidden" name="id" value="<?php echo $_GET['id'] ?>">
            <input type="hidden" value="<?php echo $cat['icono_categoria'] ?>" id="iconitos" name="iconito">
            <div id="last-selected"></div>
            <div class="row">

              <div class="col-md-12 m-b-20">
                <input placeholder="ingrese el nombre de la categoria" value="<?php echo $cat['titulo_categoria'] ?>" class="form-control detalle" name="nombre" required="">
              </div>
              <div class="col-md-1 m-b-20">
                Color:
              </div>
              <div class="col-md-1 m-b-20">
                <input type="color" value="<?php echo $cat['color_categoria'] ?>" name="color" class="form-control color">
              </div>
              <div class="col-md-1 m-b-20">
                Imagen:
              </div>
              <div class="col-md-7 m-b-20">
                <input type="file" name="foto" class="form-control foto">
              </div>
              <div class="col-md-1 m-b-20">
                Icono:
              </div>
              <div class="col-md-1 m-b-20">
                <select name="icono" id="icono" value="&#x<?php echo $cat['icono_categoria'] ?>;" onchange="selecticon()" class="form-control selecticon">
                  <option value="f26e" <?php if ($cat['icono_categoria'] == 'f26e') {
                                          echo ' selected ';
                                        } ?>>&#xf26e;</option>
                  <option value="f2b9" <?php if ($cat['icono_categoria'] == 'f2b9') {
                                          echo ' selected ';
                                        } ?>>&#xf2b9;</option>
                  <option value="f2ba" <?php if ($cat['icono_categoria'] == 'f2ba') {
                                          echo ' selected ';
                                        } ?>>&#xf2ba;</option>
                  <option value="f2bb" <?php if ($cat['icono_categoria'] == 'f2bb') {
                                          echo ' selected ';
                                        } ?>>&#xf2bb;</option>
                  <option value="f2bc" <?php if ($cat['icono_categoria'] == 'f2bc') {
                                          echo ' selected ';
                                        } ?>>&#xf2bc;</option>
                  <option value="f042" <?php if ($cat['icono_categoria'] == 'f042') {
                                          echo ' selected ';
                                        } ?>>&#xf042;</option>
                  <option value="f170" <?php if ($cat['icono_categoria'] == 'f170') {
                                          echo ' selected ';
                                        } ?>>&#xf170;</option>
                  <option value="f037" <?php if ($cat['icono_categoria'] == 'f037') {
                                          echo ' selected ';
                                        } ?>>&#xf037;</option>
                  <option value="f039" <?php if ($cat['icono_categoria'] == 'f039') {
                                          echo ' selected ';
                                        } ?>>&#xf039;</option>
                  <option value="f036" <?php if ($cat['icono_categoria'] == 'f036') {
                                          echo ' selected ';
                                        } ?>>&#xf036;</option>
                  <option value="f038" <?php if ($cat['icono_categoria'] == 'f038') {
                                          echo ' selected ';
                                        } ?>>&#xf038;</option>
                  <option value="f270" <?php if ($cat['icono_categoria'] == 'f270') {
                                          echo ' selected ';
                                        } ?>>&#xf270;</option>
                  <option value="f0f9" <?php if ($cat['icono_categoria'] == 'f0f9') {
                                          echo ' selected ';
                                        } ?>>&#xf0f9;</option>
                  <option value="f2a3" <?php if ($cat['icono_categoria'] == 'f2a3') {
                                          echo ' selected ';
                                        } ?>>&#xf2a3;</option>
                  <option value="f13d" <?php if ($cat['icono_categoria'] == 'f13d') {
                                          echo ' selected ';
                                        } ?>>&#xf13d;</option>
                  <option value="f17b" <?php if ($cat['icono_categoria'] == 'f17b') {
                                          echo ' selected ';
                                        } ?>>&#xf17b;</option>
                  <option value="f209" <?php if ($cat['icono_categoria'] == 'f209') {
                                          echo ' selected ';
                                        } ?>>&#xf209;</option>
                  <option value="f103" <?php if ($cat['icono_categoria'] == 'f103') {
                                          echo ' selected ';
                                        } ?>>&#xf103;</option>
                  <option value="f100" <?php if ($cat['icono_categoria'] == 'f100') {
                                          echo ' selected ';
                                        } ?>>&#xf100;</option>
                  <option value="f101" <?php if ($cat['icono_categoria'] == 'f101') {
                                          echo ' selected ';
                                        } ?>>&#xf101;</option>
                  <option value="f102" <?php if ($cat['icono_categoria'] == 'f102') {
                                          echo ' selected ';
                                        } ?>>&#xf102;</option>
                  <option value="f107" <?php if ($cat['icono_categoria'] == 'f107') {
                                          echo ' selected ';
                                        } ?>>&#xf107;</option>
                  <option value="f104" <?php if ($cat['icono_categoria'] == 'f104') {
                                          echo ' selected ';
                                        } ?>>&#xf104;</option>
                  <option value="f105" <?php if ($cat['icono_categoria'] == 'f105') {
                                          echo ' selected ';
                                        } ?>>&#xf105;</option>
                  <option value="f106" <?php if ($cat['icono_categoria'] == 'f106') {
                                          echo ' selected ';
                                        } ?>>&#xf106;</option>
                  <option value="f179" <?php if ($cat['icono_categoria'] == 'f179') {
                                          echo ' selected ';
                                        } ?>>&#xf179;</option>
                  <option value="f187" <?php if ($cat['icono_categoria'] == 'f187') {
                                          echo ' selected ';
                                        } ?>>&#xf187;</option>
                  <option value="f1fe" <?php if ($cat['icono_categoria'] == 'f1fe') {
                                          echo ' selected ';
                                        } ?>>&#xf1fe;</option>
                  <option value="f0ab" <?php if ($cat['icono_categoria'] == 'f0ab') {
                                          echo ' selected ';
                                        } ?>>&#xf0ab;</option>
                  <option value="f0a8" <?php if ($cat['icono_categoria'] == 'f0a8') {
                                          echo ' selected ';
                                        } ?>>&#xf0a8;</option>
                  <option value="f01a" <?php if ($cat['icono_categoria'] == 'f01a') {
                                          echo ' selected ';
                                        } ?>>&#xf01a;</option>
                  <option value="f190" <?php if ($cat['icono_categoria'] == 'f190') {
                                          echo ' selected ';
                                        } ?>>&#xf190;</option>
                  <option value="f18e" <?php if ($cat['icono_categoria'] == 'f18e') {
                                          echo ' selected ';
                                        } ?>>&#xf18e;</option>
                  <option value="f01b" <?php if ($cat['icono_categoria'] == 'f01b') {
                                          echo ' selected ';
                                        } ?>>&#xf01b;</option>
                  <option value="f0a9" <?php if ($cat['icono_categoria'] == 'f0a9') {
                                          echo ' selected ';
                                        } ?>>&#xf0a9;</option>
                  <option value="f0aa" <?php if ($cat['icono_categoria'] == 'f0aa') {
                                          echo ' selected ';
                                        } ?>>&#xf0aa;</option>
                  <option value="f063" <?php if ($cat['icono_categoria'] == 'f063') {
                                          echo ' selected ';
                                        } ?>>&#xf063;</option>
                  <option value="f060" <?php if ($cat['icono_categoria'] == 'f060') {
                                          echo ' selected ';
                                        } ?>>&#xf060;</option>
                  <option value="f061" <?php if ($cat['icono_categoria'] == 'f061') {
                                          echo ' selected ';
                                        } ?>>&#xf061;</option>
                  <option value="f062" <?php if ($cat['icono_categoria'] == 'f062') {
                                          echo ' selected ';
                                        } ?>>&#xf062;</option>
                  <option value="f047" <?php if ($cat['icono_categoria'] == 'f047') {
                                          echo ' selected ';
                                        } ?>>&#xf047;</option>
                  <option value="f0b2" <?php if ($cat['icono_categoria'] == 'f0b2') {
                                          echo ' selected ';
                                        } ?>>&#xf0b2;</option>
                  <option value="f07e" <?php if ($cat['icono_categoria'] == 'f07e') {
                                          echo ' selected ';
                                        } ?>>&#xf07e;</option>
                  <option value="f07d" <?php if ($cat['icono_categoria'] == 'f07d') {
                                          echo ' selected ';
                                        } ?>>&#xf07d;</option>
                  <option value="f2a3" <?php if ($cat['icono_categoria'] == 'f2a3') {
                                          echo ' selected ';
                                        } ?>>&#xf2a3;</option>
                  <option value="f2a2" <?php if ($cat['icono_categoria'] == 'f2a2') {
                                          echo ' selected ';
                                        } ?>>&#xf2a2;</option>
                  <option value="f069" <?php if ($cat['icono_categoria'] == 'f069') {
                                          echo ' selected ';
                                        } ?>>&#xf069;</option>
                  <option value="f1fa" <?php if ($cat['icono_categoria'] == 'f1fa') {
                                          echo ' selected ';
                                        } ?>>&#xf1fa;</option>
                  <option value="f29e" <?php if ($cat['icono_categoria'] == 'f29e') {
                                          echo ' selected ';
                                        } ?>>&#xf29e;</option>
                  <option value="f1b9" <?php if ($cat['icono_categoria'] == 'f1b9') {
                                          echo ' selected ';
                                        } ?>>&#xf1b9;</option>
                  <option value="f04a" <?php if ($cat['icono_categoria'] == 'f04a') {
                                          echo ' selected ';
                                        } ?>>&#xf04a;</option>
                  <option value="f24e" <?php if ($cat['icono_categoria'] == 'f24e') {
                                          echo ' selected ';
                                        } ?>>&#xf24e;</option>
                  <option value="f05e" <?php if ($cat['icono_categoria'] == 'f05e') {
                                          echo ' selected ';
                                        } ?>>&#xf05e;</option>
                  <option value="f2d5" <?php if ($cat['icono_categoria'] == 'f2d5') {
                                          echo ' selected ';
                                        } ?>>&#xf2d5;</option>
                  <option value="f19c" <?php if ($cat['icono_categoria'] == 'f19c') {
                                          echo ' selected ';
                                        } ?>>&#xf19c;</option>
                  <option value="f080" <?php if ($cat['icono_categoria'] == 'f080') {
                                          echo ' selected ';
                                        } ?>>&#xf080;</option>
                  <option value="f080" <?php if ($cat['icono_categoria'] == 'f080') {
                                          echo ' selected ';
                                        } ?>>&#xf080;</option>
                  <option value="f02a" <?php if ($cat['icono_categoria'] == 'f02a') {
                                          echo ' selected ';
                                        } ?>>&#xf02a;</option>
                  <option value="f0c9" <?php if ($cat['icono_categoria'] == 'f0c9') {
                                          echo ' selected ';
                                        } ?>>&#xf0c9;</option>
                  <option value="f2cd" <?php if ($cat['icono_categoria'] == 'f2cd') {
                                          echo ' selected ';
                                        } ?>>&#xf2cd;</option>
                  <option value="f2cd" <?php if ($cat['icono_categoria'] == 'f2cd') {
                                          echo ' selected ';
                                        } ?>>&#xf2cd;</option>
                  <option value="f240" <?php if ($cat['icono_categoria'] == 'f240') {
                                          echo ' selected ';
                                        } ?>>&#xf240;</option>
                  <option value="f244" <?php if ($cat['icono_categoria'] == 'f244') {
                                          echo ' selected ';
                                        } ?>>&#xf244;</option>
                  <option value="f243" <?php if ($cat['icono_categoria'] == 'f243') {
                                          echo ' selected ';
                                        } ?>>&#xf243;</option>
                  <option value="f242" <?php if ($cat['icono_categoria'] == 'f242') {
                                          echo ' selected ';
                                        } ?>>&#xf242;</option>
                  <option value="f241" <?php if ($cat['icono_categoria'] == 'f241') {
                                          echo ' selected ';
                                        } ?>>&#xf241;</option>
                  <option value="f240" <?php if ($cat['icono_categoria'] == 'f240') {
                                          echo ' selected ';
                                        } ?>>&#xf240;</option>
                  <option value="f244" <?php if ($cat['icono_categoria'] == 'f244') {
                                          echo ' selected ';
                                        } ?>>&#xf244;</option>
                  <option value="f240" <?php if ($cat['icono_categoria'] == 'f240') {
                                          echo ' selected ';
                                        } ?>>&#xf240;</option>
                  <option value="f242" <?php if ($cat['icono_categoria'] == 'f242') {
                                          echo ' selected ';
                                        } ?>>&#xf242;</option>
                  <option value="f243" <?php if ($cat['icono_categoria'] == 'f243') {
                                          echo ' selected ';
                                        } ?>>&#xf243;</option>
                  <option value="f241" <?php if ($cat['icono_categoria'] == 'f241') {
                                          echo ' selected ';
                                        } ?>>&#xf241;</option>
                  <option value="f236" <?php if ($cat['icono_categoria'] == 'f236') {
                                          echo ' selected ';
                                        } ?>>&#xf236;</option>
                  <option value="f0fc" <?php if ($cat['icono_categoria'] == 'f0fc') {
                                          echo ' selected ';
                                        } ?>>&#xf0fc;</option>
                  <option value="f1b4" <?php if ($cat['icono_categoria'] == 'f1b4') {
                                          echo ' selected ';
                                        } ?>>&#xf1b4;</option>
                  <option value="f1b5" <?php if ($cat['icono_categoria'] == 'f1b5') {
                                          echo ' selected ';
                                        } ?>>&#xf1b5;</option>
                  <option value="f0f3" <?php if ($cat['icono_categoria'] == 'f0f3') {
                                          echo ' selected ';
                                        } ?>>&#xf0f3;</option>
                  <option value="f0a2" <?php if ($cat['icono_categoria'] == 'f0a2') {
                                          echo ' selected ';
                                        } ?>>&#xf0a2;</option>
                  <option value="f1f6" <?php if ($cat['icono_categoria'] == 'f1f6') {
                                          echo ' selected ';
                                        } ?>>&#xf1f6;</option>
                  <option value="f1f7" <?php if ($cat['icono_categoria'] == 'f1f7') {
                                          echo ' selected ';
                                        } ?>>&#xf1f7;</option>
                  <option value="f206" <?php if ($cat['icono_categoria'] == 'f206') {
                                          echo ' selected ';
                                        } ?>>&#xf206;</option>
                  <option value="f1e5" <?php if ($cat['icono_categoria'] == 'f1e5') {
                                          echo ' selected ';
                                        } ?>>&#xf1e5;</option>
                  <option value="f1fd" <?php if ($cat['icono_categoria'] == 'f1fd') {
                                          echo ' selected ';
                                        } ?>>&#xf1fd;</option>
                  <option value="f171" <?php if ($cat['icono_categoria'] == 'f171') {
                                          echo ' selected ';
                                        } ?>>&#xf171;</option>
                  <option value="f172" <?php if ($cat['icono_categoria'] == 'f172') {
                                          echo ' selected ';
                                        } ?>>&#xf172;</option>
                  <option value="f15a" <?php if ($cat['icono_categoria'] == 'f15a') {
                                          echo ' selected ';
                                        } ?>>&#xf15a;</option>
                  <option value="f27e" <?php if ($cat['icono_categoria'] == 'f27e') {
                                          echo ' selected ';
                                        } ?>>&#xf27e;</option>
                  <option value="f29d" <?php if ($cat['icono_categoria'] == 'f29d') {
                                          echo ' selected ';
                                        } ?>>&#xf29d;</option>
                  <option value="f293" <?php if ($cat['icono_categoria'] == 'f293') {
                                          echo ' selected ';
                                        } ?>>&#xf293;</option>
                  <option value="f294" <?php if ($cat['icono_categoria'] == 'f294') {
                                          echo ' selected ';
                                        } ?>>&#xf294;</option>
                  <option value="f032" <?php if ($cat['icono_categoria'] == 'f032') {
                                          echo ' selected ';
                                        } ?>>&#xf032;</option>
                  <option value="f0e7" <?php if ($cat['icono_categoria'] == 'f0e7') {
                                          echo ' selected ';
                                        } ?>>&#xf0e7;</option>
                  <option value="f1e2" <?php if ($cat['icono_categoria'] == 'f1e2') {
                                          echo ' selected ';
                                        } ?>>&#xf1e2;</option>
                  <option value="f02d" <?php if ($cat['icono_categoria'] == 'f02d') {
                                          echo ' selected ';
                                        } ?>>&#xf02d;</option>
                  <option value="f02e" <?php if ($cat['icono_categoria'] == 'f02e') {
                                          echo ' selected ';
                                        } ?>>&#xf02e;</option>
                  <option value="f097" <?php if ($cat['icono_categoria'] == 'f097') {
                                          echo ' selected ';
                                        } ?>>&#xf097;</option>
                  <option value="f2a1" <?php if ($cat['icono_categoria'] == 'f2a1') {
                                          echo ' selected ';
                                        } ?>>&#xf2a1;</option>
                  <option value="f0b1" <?php if ($cat['icono_categoria'] == 'f0b1') {
                                          echo ' selected ';
                                        } ?>>&#xf0b1;</option>
                  <option value="f15a" <?php if ($cat['icono_categoria'] == 'f15a') {
                                          echo ' selected ';
                                        } ?>>&#xf15a;</option>
                  <option value="f188" <?php if ($cat['icono_categoria'] == 'f188') {
                                          echo ' selected ';
                                        } ?>>&#xf188;</option>
                  <option value="f1ad" <?php if ($cat['icono_categoria'] == 'f1ad') {
                                          echo ' selected ';
                                        } ?>>&#xf1ad;</option>
                  <option value="f0f7" <?php if ($cat['icono_categoria'] == 'f0f7') {
                                          echo ' selected ';
                                        } ?>>&#xf0f7;</option>
                  <option value="f0a1" <?php if ($cat['icono_categoria'] == 'f0a1') {
                                          echo ' selected ';
                                        } ?>>&#xf0a1;</option>
                  <option value="f140" <?php if ($cat['icono_categoria'] == 'f140') {
                                          echo ' selected ';
                                        } ?>>&#xf140;</option>
                  <option value="f207" <?php if ($cat['icono_categoria'] == 'f207') {
                                          echo ' selected ';
                                        } ?>>&#xf207;</option>
                  <option value="f20d" <?php if ($cat['icono_categoria'] == 'f20d') {
                                          echo ' selected ';
                                        } ?>>&#xf20d;</option>
                  <option value="f1ba" <?php if ($cat['icono_categoria'] == 'f1ba') {
                                          echo ' selected ';
                                        } ?>>&#xf1ba;</option>
                  <option value="f1ec" <?php if ($cat['icono_categoria'] == 'f1ec') {
                                          echo ' selected ';
                                        } ?>>&#xf1ec;</option>
                  <option value="f073" <?php if ($cat['icono_categoria'] == 'f073') {
                                          echo ' selected ';
                                        } ?>>&#xf073;</option>
                  <option value="f274" <?php if ($cat['icono_categoria'] == 'f274') {
                                          echo ' selected ';
                                        } ?>>&#xf274;</option>
                  <option value="f272" <?php if ($cat['icono_categoria'] == 'f272') {
                                          echo ' selected ';
                                        } ?>>&#xf272;</option>
                  <option value="f133" <?php if ($cat['icono_categoria'] == 'f133') {
                                          echo ' selected ';
                                        } ?>>&#xf133;</option>
                  <option value="f271" <?php if ($cat['icono_categoria'] == 'f271') {
                                          echo ' selected ';
                                        } ?>>&#xf271;</option>
                  <option value="f273" <?php if ($cat['icono_categoria'] == 'f273') {
                                          echo ' selected ';
                                        } ?>>&#xf273;</option>
                  <option value="f030" <?php if ($cat['icono_categoria'] == 'f030') {
                                          echo ' selected ';
                                        } ?>>&#xf030;</option>
                  <option value="f083" <?php if ($cat['icono_categoria'] == 'f083') {
                                          echo ' selected ';
                                        } ?>>&#xf083;</option>
                  <option value="f1b9" <?php if ($cat['icono_categoria'] == 'f1b9') {
                                          echo ' selected ';
                                        } ?>>&#xf1b9;</option>
                  <option value="f0d7" <?php if ($cat['icono_categoria'] == 'f0d7') {
                                          echo ' selected ';
                                        } ?>>&#xf0d7;</option>
                  <option value="f0d9" <?php if ($cat['icono_categoria'] == 'f0d9') {
                                          echo ' selected ';
                                        } ?>>&#xf0d9;</option>
                  <option value="f0da" <?php if ($cat['icono_categoria'] == 'f0da') {
                                          echo ' selected ';
                                        } ?>>&#xf0da;</option>
                  <option value="f150" <?php if ($cat['icono_categoria'] == 'f150') {
                                          echo ' selected ';
                                        } ?>>&#xf150;</option>
                  <option value="f191" <?php if ($cat['icono_categoria'] == 'f191') {
                                          echo ' selected ';
                                        } ?>>&#xf191;</option>
                  <option value="f152" <?php if ($cat['icono_categoria'] == 'f152') {
                                          echo ' selected ';
                                        } ?>>&#xf152;</option>
                  <option value="f151" <?php if ($cat['icono_categoria'] == 'f151') {
                                          echo ' selected ';
                                        } ?>>&#xf151;</option>
                  <option value="f0d8" <?php if ($cat['icono_categoria'] == 'f0d8') {
                                          echo ' selected ';
                                        } ?>>&#xf0d8;</option>
                  <option value="f218" <?php if ($cat['icono_categoria'] == 'f218') {
                                          echo ' selected ';
                                        } ?>>&#xf218;</option>
                  <option value="f217" <?php if ($cat['icono_categoria'] == 'f217') {
                                          echo ' selected ';
                                        } ?>>&#xf217;</option>
                  <option value="f20a" <?php if ($cat['icono_categoria'] == 'f20a') {
                                          echo ' selected ';
                                        } ?>>&#xf20a;</option>
                  <option value="f1f3" <?php if ($cat['icono_categoria'] == 'f1f3') {
                                          echo ' selected ';
                                        } ?>>&#xf1f3;</option>
                  <option value="f24c" <?php if ($cat['icono_categoria'] == 'f24c') {
                                          echo ' selected ';
                                        } ?>>&#xf24c;</option>
                  <option value="f1f2" <?php if ($cat['icono_categoria'] == 'f1f2') {
                                          echo ' selected ';
                                        } ?>>&#xf1f2;</option>
                  <option value="f24b" <?php if ($cat['icono_categoria'] == 'f24b') {
                                          echo ' selected ';
                                        } ?>>&#xf24b;</option>
                  <option value="f1f1" <?php if ($cat['icono_categoria'] == 'f1f1') {
                                          echo ' selected ';
                                        } ?>>&#xf1f1;</option>
                  <option value="f1f4" <?php if ($cat['icono_categoria'] == 'f1f4') {
                                          echo ' selected ';
                                        } ?>>&#xf1f4;</option>
                  <option value="f1f5" <?php if ($cat['icono_categoria'] == 'f1f5') {
                                          echo ' selected ';
                                        } ?>>&#xf1f5;</option>
                  <option value="f1f0" <?php if ($cat['icono_categoria'] == 'f1f0') {
                                          echo ' selected ';
                                        } ?>>&#xf1f0;</option>
                  <option value="f0a3" <?php if ($cat['icono_categoria'] == 'f0a3') {
                                          echo ' selected ';
                                        } ?>>&#xf0a3;</option>
                  <option value="f0c1" <?php if ($cat['icono_categoria'] == 'f0c1') {
                                          echo ' selected ';
                                        } ?>>&#xf0c1;</option>
                  <option value="f127" <?php if ($cat['icono_categoria'] == 'f127') {
                                          echo ' selected ';
                                        } ?>>&#xf127;</option>
                  <option value="f00c" <?php if ($cat['icono_categoria'] == 'f00c') {
                                          echo ' selected ';
                                        } ?>>&#xf00c;</option>
                  <option value="f058" <?php if ($cat['icono_categoria'] == 'f058') {
                                          echo ' selected ';
                                        } ?>>&#xf058;</option>
                  <option value="f05d" <?php if ($cat['icono_categoria'] == 'f05d') {
                                          echo ' selected ';
                                        } ?>>&#xf05d;</option>
                  <option value="f14a" <?php if ($cat['icono_categoria'] == 'f14a') {
                                          echo ' selected ';
                                        } ?>>&#xf14a;</option>
                  <option value="f046" <?php if ($cat['icono_categoria'] == 'f046') {
                                          echo ' selected ';
                                        } ?>>&#xf046;</option>
                  <option value="f13a" <?php if ($cat['icono_categoria'] == 'f13a') {
                                          echo ' selected ';
                                        } ?>>&#xf13a;</option>
                  <option value="f137" <?php if ($cat['icono_categoria'] == 'f137') {
                                          echo ' selected ';
                                        } ?>>&#xf137;</option>
                  <option value="f138" <?php if ($cat['icono_categoria'] == 'f138') {
                                          echo ' selected ';
                                        } ?>>&#xf138;</option>
                  <option value="f139" <?php if ($cat['icono_categoria'] == 'f139') {
                                          echo ' selected ';
                                        } ?>>&#xf139;</option>
                  <option value="f078" <?php if ($cat['icono_categoria'] == 'f078') {
                                          echo ' selected ';
                                        } ?>>&#xf078;</option>
                  <option value="f053" <?php if ($cat['icono_categoria'] == 'f053') {
                                          echo ' selected ';
                                        } ?>>&#xf053;</option>
                  <option value="f054" <?php if ($cat['icono_categoria'] == 'f054') {
                                          echo ' selected ';
                                        } ?>>&#xf054;</option>
                  <option value="f077" <?php if ($cat['icono_categoria'] == 'f077') {
                                          echo ' selected ';
                                        } ?>>&#xf077;</option>
                  <option value="f1ae" <?php if ($cat['icono_categoria'] == 'f1ae') {
                                          echo ' selected ';
                                        } ?>>&#xf1ae;</option>
                  <option value="f268" <?php if ($cat['icono_categoria'] == 'f268') {
                                          echo ' selected ';
                                        } ?>>&#xf268;</option>
                  <option value="f111" <?php if ($cat['icono_categoria'] == 'f111') {
                                          echo ' selected ';
                                        } ?>>&#xf111;</option>
                  <option value="f10c" <?php if ($cat['icono_categoria'] == 'f10c') {
                                          echo ' selected ';
                                        } ?>>&#xf10c;</option>
                  <option value="f1ce" <?php if ($cat['icono_categoria'] == 'f1ce') {
                                          echo ' selected ';
                                        } ?>>&#xf1ce;</option>
                  <option value="f1db" <?php if ($cat['icono_categoria'] == 'f1db') {
                                          echo ' selected ';
                                        } ?>>&#xf1db;</option>
                  <option value="f0ea" <?php if ($cat['icono_categoria'] == 'f0ea') {
                                          echo ' selected ';
                                        } ?>>&#xf0ea;</option>
                  <option value="f017" <?php if ($cat['icono_categoria'] == 'f017') {
                                          echo ' selected ';
                                        } ?>>&#xf017;</option>
                  <option value="f24d" <?php if ($cat['icono_categoria'] == 'f24d') {
                                          echo ' selected ';
                                        } ?>>&#xf24d;</option>
                  <option value="f00d" <?php if ($cat['icono_categoria'] == 'f00d') {
                                          echo ' selected ';
                                        } ?>>&#xf00d;</option>
                  <option value="f0c2" <?php if ($cat['icono_categoria'] == 'f0c2') {
                                          echo ' selected ';
                                        } ?>>&#xf0c2;</option>
                  <option value="f0ed" <?php if ($cat['icono_categoria'] == 'f0ed') {
                                          echo ' selected ';
                                        } ?>>&#xf0ed;</option>
                  <option value="f0ee" <?php if ($cat['icono_categoria'] == 'f0ee') {
                                          echo ' selected ';
                                        } ?>>&#xf0ee;</option>
                  <option value="f157" <?php if ($cat['icono_categoria'] == 'f157') {
                                          echo ' selected ';
                                        } ?>>&#xf157;</option>
                  <option value="f121" <?php if ($cat['icono_categoria'] == 'f121') {
                                          echo ' selected ';
                                        } ?>>&#xf121;</option>
                  <option value="f126" <?php if ($cat['icono_categoria'] == 'f126') {
                                          echo ' selected ';
                                        } ?>>&#xf126;</option>
                  <option value="f1cb" <?php if ($cat['icono_categoria'] == 'f1cb') {
                                          echo ' selected ';
                                        } ?>>&#xf1cb;</option>
                  <option value="f284" <?php if ($cat['icono_categoria'] == 'f284') {
                                          echo ' selected ';
                                        } ?>>&#xf284;</option>
                  <option value="f0f4" <?php if ($cat['icono_categoria'] == 'f0f4') {
                                          echo ' selected ';
                                        } ?>>&#xf0f4;</option>
                  <option value="f013" <?php if ($cat['icono_categoria'] == 'f013') {
                                          echo ' selected ';
                                        } ?>>&#xf013;</option>
                  <option value="f085" <?php if ($cat['icono_categoria'] == 'f085') {
                                          echo ' selected ';
                                        } ?>>&#xf085;</option>
                  <option value="f0db" <?php if ($cat['icono_categoria'] == 'f0db') {
                                          echo ' selected ';
                                        } ?>>&#xf0db;</option>
                  <option value="f075" <?php if ($cat['icono_categoria'] == 'f075') {
                                          echo ' selected ';
                                        } ?>>&#xf075;</option>
                  <option value="f0e5" <?php if ($cat['icono_categoria'] == 'f0e5') {
                                          echo ' selected ';
                                        } ?>>&#xf0e5;</option>
                  <option value="f27a" <?php if ($cat['icono_categoria'] == 'f27a') {
                                          echo ' selected ';
                                        } ?>>&#xf27a;</option>
                  <option value="f27b" <?php if ($cat['icono_categoria'] == 'f27b') {
                                          echo ' selected ';
                                        } ?>>&#xf27b;</option>
                  <option value="f086" <?php if ($cat['icono_categoria'] == 'f086') {
                                          echo ' selected ';
                                        } ?>>&#xf086;</option>
                  <option value="f0e6" <?php if ($cat['icono_categoria'] == 'f0e6') {
                                          echo ' selected ';
                                        } ?>>&#xf0e6;</option>
                  <option value="f14e" <?php if ($cat['icono_categoria'] == 'f14e') {
                                          echo ' selected ';
                                        } ?>>&#xf14e;</option>
                  <option value="f066" <?php if ($cat['icono_categoria'] == 'f066') {
                                          echo ' selected ';
                                        } ?>>&#xf066;</option>
                  <option value="f20e" <?php if ($cat['icono_categoria'] == 'f20e') {
                                          echo ' selected ';
                                        } ?>>&#xf20e;</option>
                  <option value="f26d" <?php if ($cat['icono_categoria'] == 'f26d') {
                                          echo ' selected ';
                                        } ?>>&#xf26d;</option>
                  <option value="f0c5" <?php if ($cat['icono_categoria'] == 'f0c5') {
                                          echo ' selected ';
                                        } ?>>&#xf0c5;</option>
                  <option value="f1f9" <?php if ($cat['icono_categoria'] == 'f1f9') {
                                          echo ' selected ';
                                        } ?>>&#xf1f9;</option>
                  <option value="f25e" <?php if ($cat['icono_categoria'] == 'f25e') {
                                          echo ' selected ';
                                        } ?>>&#xf25e;</option>
                  <option value="f09d" <?php if ($cat['icono_categoria'] == 'f09d') {
                                          echo ' selected ';
                                        } ?>>&#xf09d;</option>
                  <option value="f283" <?php if ($cat['icono_categoria'] == 'f283') {
                                          echo ' selected ';
                                        } ?>>&#xf283;</option>
                  <option value="f125" <?php if ($cat['icono_categoria'] == 'f125') {
                                          echo ' selected ';
                                        } ?>>&#xf125;</option>
                  <option value="f05b" <?php if ($cat['icono_categoria'] == 'f05b') {
                                          echo ' selected ';
                                        } ?>>&#xf05b;</option>
                  <option value="f13c" <?php if ($cat['icono_categoria'] == 'f13c') {
                                          echo ' selected ';
                                        } ?>>&#xf13c;</option>
                  <option value="f1b2" <?php if ($cat['icono_categoria'] == 'f1b2') {
                                          echo ' selected ';
                                        } ?>>&#xf1b2;</option>
                  <option value="f1b3" <?php if ($cat['icono_categoria'] == 'f1b3') {
                                          echo ' selected ';
                                        } ?>>&#xf1b3;</option>
                  <option value="f0c4" <?php if ($cat['icono_categoria'] == 'f0c4') {
                                          echo ' selected ';
                                        } ?>>&#xf0c4;</option>
                  <option value="f0f5" <?php if ($cat['icono_categoria'] == 'f0f5') {
                                          echo ' selected ';
                                        } ?>>&#xf0f5;</option>
                  <option value="f0e4" <?php if ($cat['icono_categoria'] == 'f0e4') {
                                          echo ' selected ';
                                        } ?>>&#xf0e4;</option>
                  <option value="f210" <?php if ($cat['icono_categoria'] == 'f210') {
                                          echo ' selected ';
                                        } ?>>&#xf210;</option>
                  <option value="f1c0" <?php if ($cat['icono_categoria'] == 'f1c0') {
                                          echo ' selected ';
                                        } ?>>&#xf1c0;</option>
                  <option value="f2a4" <?php if ($cat['icono_categoria'] == 'f2a4') {
                                          echo ' selected ';
                                        } ?>>&#xf2a4;</option>
                  <option value="f03b" <?php if ($cat['icono_categoria'] == 'f03b') {
                                          echo ' selected ';
                                        } ?>>&#xf03b;</option>
                  <option value="f1a5" <?php if ($cat['icono_categoria'] == 'f1a5') {
                                          echo ' selected ';
                                        } ?>>&#xf1a5;</option>
                  <option value="f108" <?php if ($cat['icono_categoria'] == 'f108') {
                                          echo ' selected ';
                                        } ?>>&#xf108;</option>
                  <option value="f1bd" <?php if ($cat['icono_categoria'] == 'f1bd') {
                                          echo ' selected ';
                                        } ?>>&#xf1bd;</option>
                  <option value="f219" <?php if ($cat['icono_categoria'] == 'f219') {
                                          echo ' selected ';
                                        } ?>>&#xf219;</option>
                  <option value="f1a6" <?php if ($cat['icono_categoria'] == 'f1a6') {
                                          echo ' selected ';
                                        } ?>>&#xf1a6;</option>
                  <option value="f155" <?php if ($cat['icono_categoria'] == 'f155') {
                                          echo ' selected ';
                                        } ?>>&#xf155;</option>
                  <option value="f192" <?php if ($cat['icono_categoria'] == 'f192') {
                                          echo ' selected ';
                                        } ?>>&#xf192;</option>
                  <option value="f019" <?php if ($cat['icono_categoria'] == 'f019') {
                                          echo ' selected ';
                                        } ?>>&#xf019;</option>
                  <option value="f17d" <?php if ($cat['icono_categoria'] == 'f17d') {
                                          echo ' selected ';
                                        } ?>>&#xf17d;</option>
                  <option value="f2c2" <?php if ($cat['icono_categoria'] == 'f2c2') {
                                          echo ' selected ';
                                        } ?>>&#xf2c2;</option>
                  <option value="f2c3" <?php if ($cat['icono_categoria'] == 'f2c3') {
                                          echo ' selected ';
                                        } ?>>&#xf2c3;</option>
                  <option value="f16b" <?php if ($cat['icono_categoria'] == 'f16b') {
                                          echo ' selected ';
                                        } ?>>&#xf16b;</option>
                  <option value="f1a9" <?php if ($cat['icono_categoria'] == 'f1a9') {
                                          echo ' selected ';
                                        } ?>>&#xf1a9;</option>
                  <option value="f282" <?php if ($cat['icono_categoria'] == 'f282') {
                                          echo ' selected ';
                                        } ?>>&#xf282;</option>
                  <option value="f044" <?php if ($cat['icono_categoria'] == 'f044') {
                                          echo ' selected ';
                                        } ?>>&#xf044;</option>
                  <option value="f2da" <?php if ($cat['icono_categoria'] == 'f2da') {
                                          echo ' selected ';
                                        } ?>>&#xf2da;</option>
                  <option value="f052" <?php if ($cat['icono_categoria'] == 'f052') {
                                          echo ' selected ';
                                        } ?>>&#xf052;</option>
                  <option value="f141" <?php if ($cat['icono_categoria'] == 'f141') {
                                          echo ' selected ';
                                        } ?>>&#xf141;</option>
                  <option value="f142" <?php if ($cat['icono_categoria'] == 'f142') {
                                          echo ' selected ';
                                        } ?>>&#xf142;</option>
                  <option value="f1d1" <?php if ($cat['icono_categoria'] == 'f1d1') {
                                          echo ' selected ';
                                        } ?>>&#xf1d1;</option>
                  <option value="f0e0" <?php if ($cat['icono_categoria'] == 'f0e0') {
                                          echo ' selected ';
                                        } ?>>&#xf0e0;</option>
                  <option value="f003" <?php if ($cat['icono_categoria'] == 'f003') {
                                          echo ' selected ';
                                        } ?>>&#xf003;</option>
                  <option value="f2b6" <?php if ($cat['icono_categoria'] == 'f2b6') {
                                          echo ' selected ';
                                        } ?>>&#xf2b6;</option>
                  <option value="f2b7" <?php if ($cat['icono_categoria'] == 'f2b7') {
                                          echo ' selected ';
                                        } ?>>&#xf2b7;</option>
                  <option value="f199" <?php if ($cat['icono_categoria'] == 'f199') {
                                          echo ' selected ';
                                        } ?>>&#xf199;</option>
                  <option value="f299" <?php if ($cat['icono_categoria'] == 'f299') {
                                          echo ' selected ';
                                        } ?>>&#xf299;</option>
                  <option value="f12d" <?php if ($cat['icono_categoria'] == 'f12d') {
                                          echo ' selected ';
                                        } ?>>&#xf12d;</option>
                  <option value="f2d7" <?php if ($cat['icono_categoria'] == 'f2d7') {
                                          echo ' selected ';
                                        } ?>>&#xf2d7;</option>
                  <option value="f153" <?php if ($cat['icono_categoria'] == 'f153') {
                                          echo ' selected ';
                                        } ?>>&#xf153;</option>
                  <option value="f153" <?php if ($cat['icono_categoria'] == 'f153') {
                                          echo ' selected ';
                                        } ?>>&#xf153;</option>
                  <option value="f0ec" <?php if ($cat['icono_categoria'] == 'f0ec') {
                                          echo ' selected ';
                                        } ?>>&#xf0ec;</option>
                  <option value="f12a" <?php if ($cat['icono_categoria'] == 'f12a') {
                                          echo ' selected ';
                                        } ?>>&#xf12a;</option>
                  <option value="f06a" <?php if ($cat['icono_categoria'] == 'f06a') {
                                          echo ' selected ';
                                        } ?>>&#xf06a;</option>
                  <option value="f071" <?php if ($cat['icono_categoria'] == 'f071') {
                                          echo ' selected ';
                                        } ?>>&#xf071;</option>
                  <option value="f065" <?php if ($cat['icono_categoria'] == 'f065') {
                                          echo ' selected ';
                                        } ?>>&#xf065;</option>
                  <option value="f23e" <?php if ($cat['icono_categoria'] == 'f23e') {
                                          echo ' selected ';
                                        } ?>>&#xf23e;</option>
                  <option value="f08e" <?php if ($cat['icono_categoria'] == 'f08e') {
                                          echo ' selected ';
                                        } ?>>&#xf08e;</option>
                  <option value="f14c" <?php if ($cat['icono_categoria'] == 'f14c') {
                                          echo ' selected ';
                                        } ?>>&#xf14c;</option>
                  <option value="f06e" <?php if ($cat['icono_categoria'] == 'f06e') {
                                          echo ' selected ';
                                        } ?>>&#xf06e;</option>
                  <option value="f070" <?php if ($cat['icono_categoria'] == 'f070') {
                                          echo ' selected ';
                                        } ?>>&#xf070;</option>
                  <option value="f1fb" <?php if ($cat['icono_categoria'] == 'f1fb') {
                                          echo ' selected ';
                                        } ?>>&#xf1fb;</option>
                  <option value="f2b4" <?php if ($cat['icono_categoria'] == 'f2b4') {
                                          echo ' selected ';
                                        } ?>>&#xf2b4;</option>
                  <option value="f09a" <?php if ($cat['icono_categoria'] == 'f09a') {
                                          echo ' selected ';
                                        } ?>>&#xf09a;</option>
                  <option value="f09a" <?php if ($cat['icono_categoria'] == 'f09a') {
                                          echo ' selected ';
                                        } ?>>&#xf09a;</option>
                  <option value="f230" <?php if ($cat['icono_categoria'] == 'f230') {
                                          echo ' selected ';
                                        } ?>>&#xf230;</option>
                  <option value="f082" <?php if ($cat['icono_categoria'] == 'f082') {
                                          echo ' selected ';
                                        } ?>>&#xf082;</option>
                  <option value="f049" <?php if ($cat['icono_categoria'] == 'f049') {
                                          echo ' selected ';
                                        } ?>>&#xf049;</option>
                  <option value="f050" <?php if ($cat['icono_categoria'] == 'f050') {
                                          echo ' selected ';
                                        } ?>>&#xf050;</option>
                  <option value="f1ac" <?php if ($cat['icono_categoria'] == 'f1ac') {
                                          echo ' selected ';
                                        } ?>>&#xf1ac;</option>
                  <option value="f09e" <?php if ($cat['icono_categoria'] == 'f09e') {
                                          echo ' selected ';
                                        } ?>>&#xf09e;</option>
                  <option value="f182" <?php if ($cat['icono_categoria'] == 'f182') {
                                          echo ' selected ';
                                        } ?>>&#xf182;</option>
                  <option value="f0fb" <?php if ($cat['icono_categoria'] == 'f0fb') {
                                          echo ' selected ';
                                        } ?>>&#xf0fb;</option>
                  <option value="f15b" <?php if ($cat['icono_categoria'] == 'f15b') {
                                          echo ' selected ';
                                        } ?>>&#xf15b;</option>
                  <option value="f1c6" <?php if ($cat['icono_categoria'] == 'f1c6') {
                                          echo ' selected ';
                                        } ?>>&#xf1c6;</option>
                  <option value="f1c7" <?php if ($cat['icono_categoria'] == 'f1c7') {
                                          echo ' selected ';
                                        } ?>>&#xf1c7;</option>
                  <option value="f1c9" <?php if ($cat['icono_categoria'] == 'f1c9') {
                                          echo ' selected ';
                                        } ?>>&#xf1c9;</option>
                  <option value="f1c3" <?php if ($cat['icono_categoria'] == 'f1c3') {
                                          echo ' selected ';
                                        } ?>>&#xf1c3;</option>
                  <option value="f1c5" <?php if ($cat['icono_categoria'] == 'f1c5') {
                                          echo ' selected ';
                                        } ?>>&#xf1c5;</option>
                  <option value="f1c8" <?php if ($cat['icono_categoria'] == 'f1c8') {
                                          echo ' selected ';
                                        } ?>>&#xf1c8;</option>
                  <option value="f016" <?php if ($cat['icono_categoria'] == 'f016') {
                                          echo ' selected ';
                                        } ?>>&#xf016;</option>
                  <option value="f1c1" <?php if ($cat['icono_categoria'] == 'f1c1') {
                                          echo ' selected ';
                                        } ?>>&#xf1c1;</option>
                  <option value="f1c5" <?php if ($cat['icono_categoria'] == 'f1c5') {
                                          echo ' selected ';
                                        } ?>>&#xf1c5;</option>
                  <option value="f1c5" <?php if ($cat['icono_categoria'] == 'f1c5') {
                                          echo ' selected ';
                                        } ?>>&#xf1c5;</option>
                  <option value="f1c4" <?php if ($cat['icono_categoria'] == 'f1c4') {
                                          echo ' selected ';
                                        } ?>>&#xf1c4;</option>
                  <option value="f1c7" <?php if ($cat['icono_categoria'] == 'f1c7') {
                                          echo ' selected ';
                                        } ?>>&#xf1c7;</option>
                  <option value="f15c" <?php if ($cat['icono_categoria'] == 'f15c') {
                                          echo ' selected ';
                                        } ?>>&#xf15c;</option>
                  <option value="f0f6" <?php if ($cat['icono_categoria'] == 'f0f6') {
                                          echo ' selected ';
                                        } ?>>&#xf0f6;</option>
                  <option value="f1c8" <?php if ($cat['icono_categoria'] == 'f1c8') {
                                          echo ' selected ';
                                        } ?>>&#xf1c8;</option>
                  <option value="f1c2" <?php if ($cat['icono_categoria'] == 'f1c2') {
                                          echo ' selected ';
                                        } ?>>&#xf1c2;</option>
                  <option value="f1c6" <?php if ($cat['icono_categoria'] == 'f1c6') {
                                          echo ' selected ';
                                        } ?>>&#xf1c6;</option>
                  <option value="f0c5" <?php if ($cat['icono_categoria'] == 'f0c5') {
                                          echo ' selected ';
                                        } ?>>&#xf0c5;</option>
                  <option value="f008" <?php if ($cat['icono_categoria'] == 'f008') {
                                          echo ' selected ';
                                        } ?>>&#xf008;</option>
                  <option value="f0b0" <?php if ($cat['icono_categoria'] == 'f0b0') {
                                          echo ' selected ';
                                        } ?>>&#xf0b0;</option>
                  <option value="f06d" <?php if ($cat['icono_categoria'] == 'f06d') {
                                          echo ' selected ';
                                        } ?>>&#xf06d;</option>
                  <option value="f134" <?php if ($cat['icono_categoria'] == 'f134') {
                                          echo ' selected ';
                                        } ?>>&#xf134;</option>
                  <option value="f269" <?php if ($cat['icono_categoria'] == 'f269') {
                                          echo ' selected ';
                                        } ?>>&#xf269;</option>
                  <option value="f2b0" <?php if ($cat['icono_categoria'] == 'f2b0') {
                                          echo ' selected ';
                                        } ?>>&#xf2b0;</option>
                  <option value="f024" <?php if ($cat['icono_categoria'] == 'f024') {
                                          echo ' selected ';
                                        } ?>>&#xf024;</option>
                  <option value="f11e" <?php if ($cat['icono_categoria'] == 'f11e') {
                                          echo ' selected ';
                                        } ?>>&#xf11e;</option>
                  <option value="f11d" <?php if ($cat['icono_categoria'] == 'f11d') {
                                          echo ' selected ';
                                        } ?>>&#xf11d;</option>
                  <option value="f0e7" <?php if ($cat['icono_categoria'] == 'f0e7') {
                                          echo ' selected ';
                                        } ?>>&#xf0e7;</option>
                  <option value="f0c3" <?php if ($cat['icono_categoria'] == 'f0c3') {
                                          echo ' selected ';
                                        } ?>>&#xf0c3;</option>
                  <option value="f16e" <?php if ($cat['icono_categoria'] == 'f16e') {
                                          echo ' selected ';
                                        } ?>>&#xf16e;</option>
                  <option value="f0c7" <?php if ($cat['icono_categoria'] == 'f0c7') {
                                          echo ' selected ';
                                        } ?>>&#xf0c7;</option>
                  <option value="f07b" <?php if ($cat['icono_categoria'] == 'f07b') {
                                          echo ' selected ';
                                        } ?>>&#xf07b;</option>
                  <option value="f114" <?php if ($cat['icono_categoria'] == 'f114') {
                                          echo ' selected ';
                                        } ?>>&#xf114;</option>
                  <option value="f07c" <?php if ($cat['icono_categoria'] == 'f07c') {
                                          echo ' selected ';
                                        } ?>>&#xf07c;</option>
                  <option value="f115" <?php if ($cat['icono_categoria'] == 'f115') {
                                          echo ' selected ';
                                        } ?>>&#xf115;</option>
                  <option value="f031" <?php if ($cat['icono_categoria'] == 'f031') {
                                          echo ' selected ';
                                        } ?>>&#xf031;</option>
                  <option value="f2b4" <?php if ($cat['icono_categoria'] == 'f2b4') {
                                          echo ' selected ';
                                        } ?>>&#xf2b4;</option>
                  <option value="f280" <?php if ($cat['icono_categoria'] == 'f280') {
                                          echo ' selected ';
                                        } ?>>&#xf280;</option>
                  <option value="f286" <?php if ($cat['icono_categoria'] == 'f286') {
                                          echo ' selected ';
                                        } ?>>&#xf286;</option>
                  <option value="f211" <?php if ($cat['icono_categoria'] == 'f211') {
                                          echo ' selected ';
                                        } ?>>&#xf211;</option>
                  <option value="f04e" <?php if ($cat['icono_categoria'] == 'f04e') {
                                          echo ' selected ';
                                        } ?>>&#xf04e;</option>
                  <option value="f180" <?php if ($cat['icono_categoria'] == 'f180') {
                                          echo ' selected ';
                                        } ?>>&#xf180;</option>
                  <option value="f2c5" <?php if ($cat['icono_categoria'] == 'f2c5') {
                                          echo ' selected ';
                                        } ?>>&#xf2c5;</option>
                  <option value="f119" <?php if ($cat['icono_categoria'] == 'f119') {
                                          echo ' selected ';
                                        } ?>>&#xf119;</option>
                  <option value="f1e3" <?php if ($cat['icono_categoria'] == 'f1e3') {
                                          echo ' selected ';
                                        } ?>>&#xf1e3;</option>
                  <option value="f11b" <?php if ($cat['icono_categoria'] == 'f11b') {
                                          echo ' selected ';
                                        } ?>>&#xf11b;</option>
                  <option value="f0e3" <?php if ($cat['icono_categoria'] == 'f0e3') {
                                          echo ' selected ';
                                        } ?>>&#xf0e3;</option>
                  <option value="f154" <?php if ($cat['icono_categoria'] == 'f154') {
                                          echo ' selected ';
                                        } ?>>&#xf154;</option>
                  <option value="f1d1" <?php if ($cat['icono_categoria'] == 'f1d1') {
                                          echo ' selected ';
                                        } ?>>&#xf1d1;</option>
                  <option value="f013" <?php if ($cat['icono_categoria'] == 'f013') {
                                          echo ' selected ';
                                        } ?>>&#xf013;</option>
                  <option value="f085" <?php if ($cat['icono_categoria'] == 'f085') {
                                          echo ' selected ';
                                        } ?>>&#xf085;</option>
                  <option value="f22d" <?php if ($cat['icono_categoria'] == 'f22d') {
                                          echo ' selected ';
                                        } ?>>&#xf22d;</option>
                  <option value="f265" <?php if ($cat['icono_categoria'] == 'f265') {
                                          echo ' selected ';
                                        } ?>>&#xf265;</option>
                  <option value="f260" <?php if ($cat['icono_categoria'] == 'f260') {
                                          echo ' selected ';
                                        } ?>>&#xf260;</option>
                  <option value="f261" <?php if ($cat['icono_categoria'] == 'f261') {
                                          echo ' selected ';
                                        } ?>>&#xf261;</option>
                  <option value="f06b" <?php if ($cat['icono_categoria'] == 'f06b') {
                                          echo ' selected ';
                                        } ?>>&#xf06b;</option>
                  <option value="f1d3" <?php if ($cat['icono_categoria'] == 'f1d3') {
                                          echo ' selected ';
                                        } ?>>&#xf1d3;</option>
                  <option value="f1d2" <?php if ($cat['icono_categoria'] == 'f1d2') {
                                          echo ' selected ';
                                        } ?>>&#xf1d2;</option>
                  <option value="f09b" <?php if ($cat['icono_categoria'] == 'f09b') {
                                          echo ' selected ';
                                        } ?>>&#xf09b;</option>
                  <option value="f113" <?php if ($cat['icono_categoria'] == 'f113') {
                                          echo ' selected ';
                                        } ?>>&#xf113;</option>
                  <option value="f092" <?php if ($cat['icono_categoria'] == 'f092') {
                                          echo ' selected ';
                                        } ?>>&#xf092;</option>
                  <option value="f296" <?php if ($cat['icono_categoria'] == 'f296') {
                                          echo ' selected ';
                                        } ?>>&#xf296;</option>
                  <option value="f184" <?php if ($cat['icono_categoria'] == 'f184') {
                                          echo ' selected ';
                                        } ?>>&#xf184;</option>
                  <option value="f000" <?php if ($cat['icono_categoria'] == 'f000') {
                                          echo ' selected ';
                                        } ?>>&#xf000;</option>
                  <option value="f2a5" <?php if ($cat['icono_categoria'] == 'f2a5') {
                                          echo ' selected ';
                                        } ?>>&#xf2a5;</option>
                  <option value="f2a6" <?php if ($cat['icono_categoria'] == 'f2a6') {
                                          echo ' selected ';
                                        } ?>>&#xf2a6;</option>
                  <option value="f0ac" <?php if ($cat['icono_categoria'] == 'f0ac') {
                                          echo ' selected ';
                                        } ?>>&#xf0ac;</option>
                  <option value="f1a0" <?php if ($cat['icono_categoria'] == 'f1a0') {
                                          echo ' selected ';
                                        } ?>>&#xf1a0;</option>
                  <option value="f0d5" <?php if ($cat['icono_categoria'] == 'f0d5') {
                                          echo ' selected ';
                                        } ?>>&#xf0d5;</option>
                  <option value="f2b3" <?php if ($cat['icono_categoria'] == 'f2b3') {
                                          echo ' selected ';
                                        } ?>>&#xf2b3;</option>
                  <option value="f2b3" <?php if ($cat['icono_categoria'] == 'f2b3') {
                                          echo ' selected ';
                                        } ?>>&#xf2b3;</option>
                  <option value="f0d4" <?php if ($cat['icono_categoria'] == 'f0d4') {
                                          echo ' selected ';
                                        } ?>>&#xf0d4;</option>
                  <option value="f1ee" <?php if ($cat['icono_categoria'] == 'f1ee') {
                                          echo ' selected ';
                                        } ?>>&#xf1ee;</option>
                  <option value="f19d" <?php if ($cat['icono_categoria'] == 'f19d') {
                                          echo ' selected ';
                                        } ?>>&#xf19d;</option>
                  <option value="f184" <?php if ($cat['icono_categoria'] == 'f184') {
                                          echo ' selected ';
                                        } ?>>&#xf184;</option>
                  <option value="f2d6" <?php if ($cat['icono_categoria'] == 'f2d6') {
                                          echo ' selected ';
                                        } ?>>&#xf2d6;</option>
                  <option value="f0c0" <?php if ($cat['icono_categoria'] == 'f0c0') {
                                          echo ' selected ';
                                        } ?>>&#xf0c0;</option>
                  <option value="f0fd" <?php if ($cat['icono_categoria'] == 'f0fd') {
                                          echo ' selected ';
                                        } ?>>&#xf0fd;</option>
                  <option value="f1d4" <?php if ($cat['icono_categoria'] == 'f1d4') {
                                          echo ' selected ';
                                        } ?>>&#xf1d4;</option>
                  <option value="f255" <?php if ($cat['icono_categoria'] == 'f255') {
                                          echo ' selected ';
                                        } ?>>&#xf255;</option>
                  <option value="f258" <?php if ($cat['icono_categoria'] == 'f258') {
                                          echo ' selected ';
                                        } ?>>&#xf258;</option>
                  <option value="f0a7" <?php if ($cat['icono_categoria'] == 'f0a7') {
                                          echo ' selected ';
                                        } ?>>&#xf0a7;</option>
                  <option value="f0a5" <?php if ($cat['icono_categoria'] == 'f0a5') {
                                          echo ' selected ';
                                        } ?>>&#xf0a5;</option>
                  <option value="f0a4" <?php if ($cat['icono_categoria'] == 'f0a4') {
                                          echo ' selected ';
                                        } ?>>&#xf0a4;</option>
                  <option value="f0a6" <?php if ($cat['icono_categoria'] == 'f0a6') {
                                          echo ' selected ';
                                        } ?>>&#xf0a6;</option>
                  <option value="f256" <?php if ($cat['icono_categoria'] == 'f256') {
                                          echo ' selected ';
                                        } ?>>&#xf256;</option>
                  <option value="f25b" <?php if ($cat['icono_categoria'] == 'f25b') {
                                          echo ' selected ';
                                        } ?>>&#xf25b;</option>
                  <option value="f25a" <?php if ($cat['icono_categoria'] == 'f25a') {
                                          echo ' selected ';
                                        } ?>>&#xf25a;</option>
                  <option value="f255" <?php if ($cat['icono_categoria'] == 'f255') {
                                          echo ' selected ';
                                        } ?>>&#xf255;</option>
                  <option value="f257" <?php if ($cat['icono_categoria'] == 'f257') {
                                          echo ' selected ';
                                        } ?>>&#xf257;</option>
                  <option value="f259" <?php if ($cat['icono_categoria'] == 'f259') {
                                          echo ' selected ';
                                        } ?>>&#xf259;</option>
                  <option value="f256" <?php if ($cat['icono_categoria'] == 'f256') {
                                          echo ' selected ';
                                        } ?>>&#xf256;</option>
                  <option value="f2b5" <?php if ($cat['icono_categoria'] == 'f2b5') {
                                          echo ' selected ';
                                        } ?>>&#xf2b5;</option>
                  <option value="f2a4" <?php if ($cat['icono_categoria'] == 'f2a4') {
                                          echo ' selected ';
                                        } ?>>&#xf2a4;</option>
                  <option value="f292" <?php if ($cat['icono_categoria'] == 'f292') {
                                          echo ' selected ';
                                        } ?>>&#xf292;</option>
                  <option value="f0a0" <?php if ($cat['icono_categoria'] == 'f0a0') {
                                          echo ' selected ';
                                        } ?>>&#xf0a0;</option>
                  <option value="f1dc" <?php if ($cat['icono_categoria'] == 'f1dc') {
                                          echo ' selected ';
                                        } ?>>&#xf1dc;</option>
                  <option value="f025" <?php if ($cat['icono_categoria'] == 'f025') {
                                          echo ' selected ';
                                        } ?>>&#xf025;</option>
                  <option value="f004" <?php if ($cat['icono_categoria'] == 'f004') {
                                          echo ' selected ';
                                        } ?>>&#xf004;</option>
                  <option value="f08a" <?php if ($cat['icono_categoria'] == 'f08a') {
                                          echo ' selected ';
                                        } ?>>&#xf08a;</option>
                  <option value="f21e" <?php if ($cat['icono_categoria'] == 'f21e') {
                                          echo ' selected ';
                                        } ?>>&#xf21e;</option>
                  <option value="f1da" <?php if ($cat['icono_categoria'] == 'f1da') {
                                          echo ' selected ';
                                        } ?>>&#xf1da;</option>
                  <option value="f015" <?php if ($cat['icono_categoria'] == 'f015') {
                                          echo ' selected ';
                                        } ?>>&#xf015;</option>
                  <option value="f0f8" <?php if ($cat['icono_categoria'] == 'f0f8') {
                                          echo ' selected ';
                                        } ?>>&#xf0f8;</option>
                  <option value="f236" <?php if ($cat['icono_categoria'] == 'f236') {
                                          echo ' selected ';
                                        } ?>>&#xf236;</option>
                  <option value="f254" <?php if ($cat['icono_categoria'] == 'f254') {
                                          echo ' selected ';
                                        } ?>>&#xf254;</option>
                  <option value="f251" <?php if ($cat['icono_categoria'] == 'f251') {
                                          echo ' selected ';
                                        } ?>>&#xf251;</option>
                  <option value="f252" <?php if ($cat['icono_categoria'] == 'f252') {
                                          echo ' selected ';
                                        } ?>>&#xf252;</option>
                  <option value="f253" <?php if ($cat['icono_categoria'] == 'f253') {
                                          echo ' selected ';
                                        } ?>>&#xf253;</option>
                  <option value="f252" <?php if ($cat['icono_categoria'] == 'f252') {
                                          echo ' selected ';
                                        } ?>>&#xf252;</option>
                  <option value="f250" <?php if ($cat['icono_categoria'] == 'f250') {
                                          echo ' selected ';
                                        } ?>>&#xf250;</option>
                  <option value="f251" <?php if ($cat['icono_categoria'] == 'f251') {
                                          echo ' selected ';
                                        } ?>>&#xf251;</option>
                  <option value="f27c" <?php if ($cat['icono_categoria'] == 'f27c') {
                                          echo ' selected ';
                                        } ?>>&#xf27c;</option>
                  <option value="f13b" <?php if ($cat['icono_categoria'] == 'f13b') {
                                          echo ' selected ';
                                        } ?>>&#xf13b;</option>
                  <option value="f246" <?php if ($cat['icono_categoria'] == 'f246') {
                                          echo ' selected ';
                                        } ?>>&#xf246;</option>
                  <option value="f2c1" <?php if ($cat['icono_categoria'] == 'f2c1') {
                                          echo ' selected ';
                                        } ?>>&#xf2c1;</option>
                  <option value="f2c2" <?php if ($cat['icono_categoria'] == 'f2c2') {
                                          echo ' selected ';
                                        } ?>>&#xf2c2;</option>
                  <option value="f2c3" <?php if ($cat['icono_categoria'] == 'f2c3') {
                                          echo ' selected ';
                                        } ?>>&#xf2c3;</option>
                  <option value="f20b" <?php if ($cat['icono_categoria'] == 'f20b') {
                                          echo ' selected ';
                                        } ?>>&#xf20b;</option>
                  <option value="f03e" <?php if ($cat['icono_categoria'] == 'f03e') {
                                          echo ' selected ';
                                        } ?>>&#xf03e;</option>
                  <option value="f2d8" <?php if ($cat['icono_categoria'] == 'f2d8') {
                                          echo ' selected ';
                                        } ?>>&#xf2d8;</option>
                  <option value="f01c" <?php if ($cat['icono_categoria'] == 'f01c') {
                                          echo ' selected ';
                                        } ?>>&#xf01c;</option>
                  <option value="f03c" <?php if ($cat['icono_categoria'] == 'f03c') {
                                          echo ' selected ';
                                        } ?>>&#xf03c;</option>
                  <option value="f275" <?php if ($cat['icono_categoria'] == 'f275') {
                                          echo ' selected ';
                                        } ?>>&#xf275;</option>
                  <option value="f129" <?php if ($cat['icono_categoria'] == 'f129') {
                                          echo ' selected ';
                                        } ?>>&#xf129;</option>
                  <option value="f05a" <?php if ($cat['icono_categoria'] == 'f05a') {
                                          echo ' selected ';
                                        } ?>>&#xf05a;</option>
                  <option value="f156" <?php if ($cat['icono_categoria'] == 'f156') {
                                          echo ' selected ';
                                        } ?>>&#xf156;</option>
                  <option value="f16d" <?php if ($cat['icono_categoria'] == 'f16d') {
                                          echo ' selected ';
                                        } ?>>&#xf16d;</option>
                  <option value="f19c" <?php if ($cat['icono_categoria'] == 'f19c') {
                                          echo ' selected ';
                                        } ?>>&#xf19c;</option>
                  <option value="f26b" <?php if ($cat['icono_categoria'] == 'f26b') {
                                          echo ' selected ';
                                        } ?>>&#xf26b;</option>
                  <option value="f224" <?php if ($cat['icono_categoria'] == 'f224') {
                                          echo ' selected ';
                                        } ?>>&#xf224;</option>
                  <option value="f208" <?php if ($cat['icono_categoria'] == 'f208') {
                                          echo ' selected ';
                                        } ?>>&#xf208;</option>
                  <option value="f033" <?php if ($cat['icono_categoria'] == 'f033') {
                                          echo ' selected ';
                                        } ?>>&#xf033;</option>
                  <option value="f1aa" <?php if ($cat['icono_categoria'] == 'f1aa') {
                                          echo ' selected ';
                                        } ?>>&#xf1aa;</option>
                  <option value="f157" <?php if ($cat['icono_categoria'] == 'f157') {
                                          echo ' selected ';
                                        } ?>>&#xf157;</option>
                  <option value="f1cc" <?php if ($cat['icono_categoria'] == 'f1cc') {
                                          echo ' selected ';
                                        } ?>>&#xf1cc;</option>
                  <option value="f084" <?php if ($cat['icono_categoria'] == 'f084') {
                                          echo ' selected ';
                                        } ?>>&#xf084;</option>
                  <option value="f11c" <?php if ($cat['icono_categoria'] == 'f11c') {
                                          echo ' selected ';
                                        } ?>>&#xf11c;</option>
                  <option value="f159" <?php if ($cat['icono_categoria'] == 'f159') {
                                          echo ' selected ';
                                        } ?>>&#xf159;</option>
                  <option value="f1ab" <?php if ($cat['icono_categoria'] == 'f1ab') {
                                          echo ' selected ';
                                        } ?>>&#xf1ab;</option>
                  <option value="f109" <?php if ($cat['icono_categoria'] == 'f109') {
                                          echo ' selected ';
                                        } ?>>&#xf109;</option>
                  <option value="f202" <?php if ($cat['icono_categoria'] == 'f202') {
                                          echo ' selected ';
                                        } ?>>&#xf202;</option>
                  <option value="f203" <?php if ($cat['icono_categoria'] == 'f203') {
                                          echo ' selected ';
                                        } ?>>&#xf203;</option>
                  <option value="f06c" <?php if ($cat['icono_categoria'] == 'f06c') {
                                          echo ' selected ';
                                        } ?>>&#xf06c;</option>
                  <option value="f212" <?php if ($cat['icono_categoria'] == 'f212') {
                                          echo ' selected ';
                                        } ?>>&#xf212;</option>
                  <option value="f0e3" <?php if ($cat['icono_categoria'] == 'f0e3') {
                                          echo ' selected ';
                                        } ?>>&#xf0e3;</option>
                  <option value="f094" <?php if ($cat['icono_categoria'] == 'f094') {
                                          echo ' selected ';
                                        } ?>>&#xf094;</option>
                  <option value="f149" <?php if ($cat['icono_categoria'] == 'f149') {
                                          echo ' selected ';
                                        } ?>>&#xf149;</option>
                  <option value="f148" <?php if ($cat['icono_categoria'] == 'f148') {
                                          echo ' selected ';
                                        } ?>>&#xf148;</option>
                  <option value="f1cd" <?php if ($cat['icono_categoria'] == 'f1cd') {
                                          echo ' selected ';
                                        } ?>>&#xf1cd;</option>
                  <option value="f0eb" <?php if ($cat['icono_categoria'] == 'f0eb') {
                                          echo ' selected ';
                                        } ?>>&#xf0eb;</option>
                  <option value="f201" <?php if ($cat['icono_categoria'] == 'f201') {
                                          echo ' selected ';
                                        } ?>>&#xf201;</option>
                  <option value="f0c1" <?php if ($cat['icono_categoria'] == 'f0c1') {
                                          echo ' selected ';
                                        } ?>>&#xf0c1;</option>
                  <option value="f0e1" <?php if ($cat['icono_categoria'] == 'f0e1') {
                                          echo ' selected ';
                                        } ?>>&#xf0e1;</option>
                  <option value="f08c" <?php if ($cat['icono_categoria'] == 'f08c') {
                                          echo ' selected ';
                                        } ?>>&#xf08c;</option>
                  <option value="f2b8" <?php if ($cat['icono_categoria'] == 'f2b8') {
                                          echo ' selected ';
                                        } ?>>&#xf2b8;</option>
                  <option value="f17c" <?php if ($cat['icono_categoria'] == 'f17c') {
                                          echo ' selected ';
                                        } ?>>&#xf17c;</option>
                  <option value="f03a" <?php if ($cat['icono_categoria'] == 'f03a') {
                                          echo ' selected ';
                                        } ?>>&#xf03a;</option>
                  <option value="f022" <?php if ($cat['icono_categoria'] == 'f022') {
                                          echo ' selected ';
                                        } ?>>&#xf022;</option>
                  <option value="f0cb" <?php if ($cat['icono_categoria'] == 'f0cb') {
                                          echo ' selected ';
                                        } ?>>&#xf0cb;</option>
                  <option value="f0ca" <?php if ($cat['icono_categoria'] == 'f0ca') {
                                          echo ' selected ';
                                        } ?>>&#xf0ca;</option>
                  <option value="f124" <?php if ($cat['icono_categoria'] == 'f124') {
                                          echo ' selected ';
                                        } ?>>&#xf124;</option>
                  <option value="f023" <?php if ($cat['icono_categoria'] == 'f023') {
                                          echo ' selected ';
                                        } ?>>&#xf023;</option>
                  <option value="f175" <?php if ($cat['icono_categoria'] == 'f175') {
                                          echo ' selected ';
                                        } ?>>&#xf175;</option>
                  <option value="f177" <?php if ($cat['icono_categoria'] == 'f177') {
                                          echo ' selected ';
                                        } ?>>&#xf177;</option>
                  <option value="f178" <?php if ($cat['icono_categoria'] == 'f178') {
                                          echo ' selected ';
                                        } ?>>&#xf178;</option>
                  <option value="f176" <?php if ($cat['icono_categoria'] == 'f176') {
                                          echo ' selected ';
                                        } ?>>&#xf176;</option>
                  <option value="f2a8" <?php if ($cat['icono_categoria'] == 'f2a8') {
                                          echo ' selected ';
                                        } ?>>&#xf2a8;</option>
                  <option value="f0d0" <?php if ($cat['icono_categoria'] == 'f0d0') {
                                          echo ' selected ';
                                        } ?>>&#xf0d0;</option>
                  <option value="f076" <?php if ($cat['icono_categoria'] == 'f076') {
                                          echo ' selected ';
                                        } ?>>&#xf076;</option>
                  <option value="f064" <?php if ($cat['icono_categoria'] == 'f064') {
                                          echo ' selected ';
                                        } ?>>&#xf064;</option>
                  <option value="f112" <?php if ($cat['icono_categoria'] == 'f112') {
                                          echo ' selected ';
                                        } ?>>&#xf112;</option>
                  <option value="f183" <?php if ($cat['icono_categoria'] == 'f183') {
                                          echo ' selected ';
                                        } ?>>&#xf183;</option>
                  <option value="f279" <?php if ($cat['icono_categoria'] == 'f279') {
                                          echo ' selected ';
                                        } ?>>&#xf279;</option>
                  <option value="f041" <?php if ($cat['icono_categoria'] == 'f041') {
                                          echo ' selected ';
                                        } ?>>&#xf041;</option>
                  <option value="f278" <?php if ($cat['icono_categoria'] == 'f278') {
                                          echo ' selected ';
                                        } ?>>&#xf278;</option>
                  <option value="f276" <?php if ($cat['icono_categoria'] == 'f276') {
                                          echo ' selected ';
                                        } ?>>&#xf276;</option>
                  <option value="f277" <?php if ($cat['icono_categoria'] == 'f277') {
                                          echo ' selected ';
                                        } ?>>&#xf277;</option>
                  <option value="f222" <?php if ($cat['icono_categoria'] == 'f222') {
                                          echo ' selected ';
                                        } ?>>&#xf222;</option>
                  <option value="f227" <?php if ($cat['icono_categoria'] == 'f227') {
                                          echo ' selected ';
                                        } ?>>&#xf227;</option>
                  <option value="f229" <?php if ($cat['icono_categoria'] == 'f229') {
                                          echo ' selected ';
                                        } ?>>&#xf229;</option>
                  <option value="f22b" <?php if ($cat['icono_categoria'] == 'f22b') {
                                          echo ' selected ';
                                        } ?>>&#xf22b;</option>
                  <option value="f22a" <?php if ($cat['icono_categoria'] == 'f22a') {
                                          echo ' selected ';
                                        } ?>>&#xf22a;</option>
                  <option value="f136" <?php if ($cat['icono_categoria'] == 'f136') {
                                          echo ' selected ';
                                        } ?>>&#xf136;</option>
                  <option value="f20c" <?php if ($cat['icono_categoria'] == 'f20c') {
                                          echo ' selected ';
                                        } ?>>&#xf20c;</option>
                  <option value="f23a" <?php if ($cat['icono_categoria'] == 'f23a') {
                                          echo ' selected ';
                                        } ?>>&#xf23a;</option>
                  <option value="f0fa" <?php if ($cat['icono_categoria'] == 'f0fa') {
                                          echo ' selected ';
                                        } ?>>&#xf0fa;</option>
                  <option value="f2e0" <?php if ($cat['icono_categoria'] == 'f2e0') {
                                          echo ' selected ';
                                        } ?>>&#xf2e0;</option>
                  <option value="f11a" <?php if ($cat['icono_categoria'] == 'f11a') {
                                          echo ' selected ';
                                        } ?>>&#xf11a;</option>
                  <option value="f223" <?php if ($cat['icono_categoria'] == 'f223') {
                                          echo ' selected ';
                                        } ?>>&#xf223;</option>
                  <option value="f2db" <?php if ($cat['icono_categoria'] == 'f2db') {
                                          echo ' selected ';
                                        } ?>>&#xf2db;</option>
                  <option value="f130" <?php if ($cat['icono_categoria'] == 'f130') {
                                          echo ' selected ';
                                        } ?>>&#xf130;</option>
                  <option value="f131" <?php if ($cat['icono_categoria'] == 'f131') {
                                          echo ' selected ';
                                        } ?>>&#xf131;</option>
                  <option value="f068" <?php if ($cat['icono_categoria'] == 'f068') {
                                          echo ' selected ';
                                        } ?>>&#xf068;</option>
                  <option value="f056" <?php if ($cat['icono_categoria'] == 'f056') {
                                          echo ' selected ';
                                        } ?>>&#xf056;</option>
                  <option value="f146" <?php if ($cat['icono_categoria'] == 'f146') {
                                          echo ' selected ';
                                        } ?>>&#xf146;</option>
                  <option value="f147" <?php if ($cat['icono_categoria'] == 'f147') {
                                          echo ' selected ';
                                        } ?>>&#xf147;</option>
                  <option value="f289" <?php if ($cat['icono_categoria'] == 'f289') {
                                          echo ' selected ';
                                        } ?>>&#xf289;</option>
                  <option value="f10b" <?php if ($cat['icono_categoria'] == 'f10b') {
                                          echo ' selected ';
                                        } ?>>&#xf10b;</option>
                  <option value="f10b" <?php if ($cat['icono_categoria'] == 'f10b') {
                                          echo ' selected ';
                                        } ?>>&#xf10b;</option>
                  <option value="f285" <?php if ($cat['icono_categoria'] == 'f285') {
                                          echo ' selected ';
                                        } ?>>&#xf285;</option>
                  <option value="f0d6" <?php if ($cat['icono_categoria'] == 'f0d6') {
                                          echo ' selected ';
                                        } ?>>&#xf0d6;</option>
                  <option value="f186" <?php if ($cat['icono_categoria'] == 'f186') {
                                          echo ' selected ';
                                        } ?>>&#xf186;</option>
                  <option value="f19d" <?php if ($cat['icono_categoria'] == 'f19d') {
                                          echo ' selected ';
                                        } ?>>&#xf19d;</option>
                  <option value="f21c" <?php if ($cat['icono_categoria'] == 'f21c') {
                                          echo ' selected ';
                                        } ?>>&#xf21c;</option>
                  <option value="f245" <?php if ($cat['icono_categoria'] == 'f245') {
                                          echo ' selected ';
                                        } ?>>&#xf245;</option>
                  <option value="f001" <?php if ($cat['icono_categoria'] == 'f001') {
                                          echo ' selected ';
                                        } ?>>&#xf001;</option>
                  <option value="f0c9" <?php if ($cat['icono_categoria'] == 'f0c9') {
                                          echo ' selected ';
                                        } ?>>&#xf0c9;</option>
                  <option value="f22c" <?php if ($cat['icono_categoria'] == 'f22c') {
                                          echo ' selected ';
                                        } ?>>&#xf22c;</option>
                  <option value="f1ea" <?php if ($cat['icono_categoria'] == 'f1ea') {
                                          echo ' selected ';
                                        } ?>>&#xf1ea;</option>
                  <option value="f247" <?php if ($cat['icono_categoria'] == 'f247') {
                                          echo ' selected ';
                                        } ?>>&#xf247;</option>
                  <option value="f248" <?php if ($cat['icono_categoria'] == 'f248') {
                                          echo ' selected ';
                                        } ?>>&#xf248;</option>
                  <option value="f263" <?php if ($cat['icono_categoria'] == 'f263') {
                                          echo ' selected ';
                                        } ?>>&#xf263;</option>
                  <option value="f264" <?php if ($cat['icono_categoria'] == 'f264') {
                                          echo ' selected ';
                                        } ?>>&#xf264;</option>
                  <option value="f23d" <?php if ($cat['icono_categoria'] == 'f23d') {
                                          echo ' selected ';
                                        } ?>>&#xf23d;</option>
                  <option value="f19b" <?php if ($cat['icono_categoria'] == 'f19b') {
                                          echo ' selected ';
                                        } ?>>&#xf19b;</option>
                  <option value="f26a" <?php if ($cat['icono_categoria'] == 'f26a') {
                                          echo ' selected ';
                                        } ?>>&#xf26a;</option>
                  <option value="f23c" <?php if ($cat['icono_categoria'] == 'f23c') {
                                          echo ' selected ';
                                        } ?>>&#xf23c;</option>
                  <option value="f03b" <?php if ($cat['icono_categoria'] == 'f03b') {
                                          echo ' selected ';
                                        } ?>>&#xf03b;</option>
                  <option value="f18c" <?php if ($cat['icono_categoria'] == 'f18c') {
                                          echo ' selected ';
                                        } ?>>&#xf18c;</option>
                  <option value="f1fc" <?php if ($cat['icono_categoria'] == 'f1fc') {
                                          echo ' selected ';
                                        } ?>>&#xf1fc;</option>
                  <option value="f1d8" <?php if ($cat['icono_categoria'] == 'f1d8') {
                                          echo ' selected ';
                                        } ?>>&#xf1d8;</option>
                  <option value="f1d9" <?php if ($cat['icono_categoria'] == 'f1d9') {
                                          echo ' selected ';
                                        } ?>>&#xf1d9;</option>
                  <option value="f0c6" <?php if ($cat['icono_categoria'] == 'f0c6') {
                                          echo ' selected ';
                                        } ?>>&#xf0c6;</option>
                  <option value="f1dd" <?php if ($cat['icono_categoria'] == 'f1dd') {
                                          echo ' selected ';
                                        } ?>>&#xf1dd;</option>
                  <option value="f0ea" <?php if ($cat['icono_categoria'] == 'f0ea') {
                                          echo ' selected ';
                                        } ?>>&#xf0ea;</option>
                  <option value="f04c" <?php if ($cat['icono_categoria'] == 'f04c') {
                                          echo ' selected ';
                                        } ?>>&#xf04c;</option>
                  <option value="f28b" <?php if ($cat['icono_categoria'] == 'f28b') {
                                          echo ' selected ';
                                        } ?>>&#xf28b;</option>
                  <option value="f28c" <?php if ($cat['icono_categoria'] == 'f28c') {
                                          echo ' selected ';
                                        } ?>>&#xf28c;</option>
                  <option value="f1b0" <?php if ($cat['icono_categoria'] == 'f1b0') {
                                          echo ' selected ';
                                        } ?>>&#xf1b0;</option>
                  <option value="f1ed" <?php if ($cat['icono_categoria'] == 'f1ed') {
                                          echo ' selected ';
                                        } ?>>&#xf1ed;</option>
                  <option value="f040" <?php if ($cat['icono_categoria'] == 'f040') {
                                          echo ' selected ';
                                        } ?>>&#xf040;</option>
                  <option value="f14b" <?php if ($cat['icono_categoria'] == 'f14b') {
                                          echo ' selected ';
                                        } ?>>&#xf14b;</option>
                  <option value="f044" <?php if ($cat['icono_categoria'] == 'f044') {
                                          echo ' selected ';
                                        } ?>>&#xf044;</option>
                  <option value="f295" <?php if ($cat['icono_categoria'] == 'f295') {
                                          echo ' selected ';
                                        } ?>>&#xf295;</option>
                  <option value="f095" <?php if ($cat['icono_categoria'] == 'f095') {
                                          echo ' selected ';
                                        } ?>>&#xf095;</option>
                  <option value="f098" <?php if ($cat['icono_categoria'] == 'f098') {
                                          echo ' selected ';
                                        } ?>>&#xf098;</option>
                  <option value="f03e" <?php if ($cat['icono_categoria'] == 'f03e') {
                                          echo ' selected ';
                                        } ?>>&#xf03e;</option>
                  <option value="f03e" <?php if ($cat['icono_categoria'] == 'f03e') {
                                          echo ' selected ';
                                        } ?>>&#xf03e;</option>
                  <option value="f200" <?php if ($cat['icono_categoria'] == 'f200') {
                                          echo ' selected ';
                                        } ?>>&#xf200;</option>
                  <option value="f2ae" <?php if ($cat['icono_categoria'] == 'f2ae') {
                                          echo ' selected ';
                                        } ?>>&#xf2ae;</option>
                  <option value="f1a8" <?php if ($cat['icono_categoria'] == 'f1a8') {
                                          echo ' selected ';
                                        } ?>>&#xf1a8;</option>
                  <option value="f1a7" <?php if ($cat['icono_categoria'] == 'f1a7') {
                                          echo ' selected ';
                                        } ?>>&#xf1a7;</option>
                  <option value="f0d2" <?php if ($cat['icono_categoria'] == 'f0d2') {
                                          echo ' selected ';
                                        } ?>>&#xf0d2;</option>
                  <option value="f231" <?php if ($cat['icono_categoria'] == 'f231') {
                                          echo ' selected ';
                                        } ?>>&#xf231;</option>
                  <option value="f0d3" <?php if ($cat['icono_categoria'] == 'f0d3') {
                                          echo ' selected ';
                                        } ?>>&#xf0d3;</option>
                  <option value="f072" <?php if ($cat['icono_categoria'] == 'f072') {
                                          echo ' selected ';
                                        } ?>>&#xf072;</option>
                  <option value="f04b" <?php if ($cat['icono_categoria'] == 'f04b') {
                                          echo ' selected ';
                                        } ?>>&#xf04b;</option>
                  <option value="f144" <?php if ($cat['icono_categoria'] == 'f144') {
                                          echo ' selected ';
                                        } ?>>&#xf144;</option>
                  <option value="f01d" <?php if ($cat['icono_categoria'] == 'f01d') {
                                          echo ' selected ';
                                        } ?>>&#xf01d;</option>
                  <option value="f1e6" <?php if ($cat['icono_categoria'] == 'f1e6') {
                                          echo ' selected ';
                                        } ?>>&#xf1e6;</option>
                  <option value="f067" <?php if ($cat['icono_categoria'] == 'f067') {
                                          echo ' selected ';
                                        } ?>>&#xf067;</option>
                  <option value="f055" <?php if ($cat['icono_categoria'] == 'f055') {
                                          echo ' selected ';
                                        } ?>>&#xf055;</option>
                  <option value="f0fe" <?php if ($cat['icono_categoria'] == 'f0fe') {
                                          echo ' selected ';
                                        } ?>>&#xf0fe;</option>
                  <option value="f196" <?php if ($cat['icono_categoria'] == 'f196') {
                                          echo ' selected ';
                                        } ?>>&#xf196;</option>
                  <option value="f2ce" <?php if ($cat['icono_categoria'] == 'f2ce') {
                                          echo ' selected ';
                                        } ?>>&#xf2ce;</option>
                  <option value="f011" <?php if ($cat['icono_categoria'] == 'f011') {
                                          echo ' selected ';
                                        } ?>>&#xf011;</option>
                  <option value="f02f" <?php if ($cat['icono_categoria'] == 'f02f') {
                                          echo ' selected ';
                                        } ?>>&#xf02f;</option>
                  <option value="f288" <?php if ($cat['icono_categoria'] == 'f288') {
                                          echo ' selected ';
                                        } ?>>&#xf288;</option>
                  <option value="f12e" <?php if ($cat['icono_categoria'] == 'f12e') {
                                          echo ' selected ';
                                        } ?>>&#xf12e;</option>
                  <option value="f1d6" <?php if ($cat['icono_categoria'] == 'f1d6') {
                                          echo ' selected ';
                                        } ?>>&#xf1d6;</option>
                  <option value="f029" <?php if ($cat['icono_categoria'] == 'f029') {
                                          echo ' selected ';
                                        } ?>>&#xf029;</option>
                  <option value="f128" <?php if ($cat['icono_categoria'] == 'f128') {
                                          echo ' selected ';
                                        } ?>>&#xf128;</option>
                  <option value="f059" <?php if ($cat['icono_categoria'] == 'f059') {
                                          echo ' selected ';
                                        } ?>>&#xf059;</option>
                  <option value="f29c" <?php if ($cat['icono_categoria'] == 'f29c') {
                                          echo ' selected ';
                                        } ?>>&#xf29c;</option>
                  <option value="f2c4" <?php if ($cat['icono_categoria'] == 'f2c4') {
                                          echo ' selected ';
                                        } ?>>&#xf2c4;</option>
                  <option value="f10d" <?php if ($cat['icono_categoria'] == 'f10d') {
                                          echo ' selected ';
                                        } ?>>&#xf10d;</option>
                  <option value="f10e" <?php if ($cat['icono_categoria'] == 'f10e') {
                                          echo ' selected ';
                                        } ?>>&#xf10e;</option>
                  <option value="f1d0" <?php if ($cat['icono_categoria'] == 'f1d0') {
                                          echo ' selected ';
                                        } ?>>&#xf1d0;</option>
                  <option value="f074" <?php if ($cat['icono_categoria'] == 'f074') {
                                          echo ' selected ';
                                        } ?>>&#xf074;</option>
                  <option value="f2d9" <?php if ($cat['icono_categoria'] == 'f2d9') {
                                          echo ' selected ';
                                        } ?>>&#xf2d9;</option>
                  <option value="f1d0" <?php if ($cat['icono_categoria'] == 'f1d0') {
                                          echo ' selected ';
                                        } ?>>&#xf1d0;</option>
                  <option value="f1b8" <?php if ($cat['icono_categoria'] == 'f1b8') {
                                          echo ' selected ';
                                        } ?>>&#xf1b8;</option>
                  <option value="f1a1" <?php if ($cat['icono_categoria'] == 'f1a1') {
                                          echo ' selected ';
                                        } ?>>&#xf1a1;</option>
                  <option value="f281" <?php if ($cat['icono_categoria'] == 'f281') {
                                          echo ' selected ';
                                        } ?>>&#xf281;</option>
                  <option value="f1a2" <?php if ($cat['icono_categoria'] == 'f1a2') {
                                          echo ' selected ';
                                        } ?>>&#xf1a2;</option>
                  <option value="f021" <?php if ($cat['icono_categoria'] == 'f021') {
                                          echo ' selected ';
                                        } ?>>&#xf021;</option>
                  <option value="f25d" <?php if ($cat['icono_categoria'] == 'f25d') {
                                          echo ' selected ';
                                        } ?>>&#xf25d;</option>
                  <option value="f00d" <?php if ($cat['icono_categoria'] == 'f00d') {
                                          echo ' selected ';
                                        } ?>>&#xf00d;</option>
                  <option value="f18b" <?php if ($cat['icono_categoria'] == 'f18b') {
                                          echo ' selected ';
                                        } ?>>&#xf18b;</option>
                  <option value="f0c9" <?php if ($cat['icono_categoria'] == 'f0c9') {
                                          echo ' selected ';
                                        } ?>>&#xf0c9;</option>
                  <option value="f01e" <?php if ($cat['icono_categoria'] == 'f01e') {
                                          echo ' selected ';
                                        } ?>>&#xf01e;</option>
                  <option value="f112" <?php if ($cat['icono_categoria'] == 'f112') {
                                          echo ' selected ';
                                        } ?>>&#xf112;</option>
                  <option value="f122" <?php if ($cat['icono_categoria'] == 'f122') {
                                          echo ' selected ';
                                        } ?>>&#xf122;</option>
                  <option value="f1d0" <?php if ($cat['icono_categoria'] == 'f1d0') {
                                          echo ' selected ';
                                        } ?>>&#xf1d0;</option>
                  <option value="f079" <?php if ($cat['icono_categoria'] == 'f079') {
                                          echo ' selected ';
                                        } ?>>&#xf079;</option>
                  <option value="f157" <?php if ($cat['icono_categoria'] == 'f157') {
                                          echo ' selected ';
                                        } ?>>&#xf157;</option>
                  <option value="f018" <?php if ($cat['icono_categoria'] == 'f018') {
                                          echo ' selected ';
                                        } ?>>&#xf018;</option>
                  <option value="f135" <?php if ($cat['icono_categoria'] == 'f135') {
                                          echo ' selected ';
                                        } ?>>&#xf135;</option>
                  <option value="f0e2" <?php if ($cat['icono_categoria'] == 'f0e2') {
                                          echo ' selected ';
                                        } ?>>&#xf0e2;</option>
                  <option value="f01e" <?php if ($cat['icono_categoria'] == 'f01e') {
                                          echo ' selected ';
                                        } ?>>&#xf01e;</option>
                  <option value="f158" <?php if ($cat['icono_categoria'] == 'f158') {
                                          echo ' selected ';
                                        } ?>>&#xf158;</option>
                  <option value="f09e" <?php if ($cat['icono_categoria'] == 'f09e') {
                                          echo ' selected ';
                                        } ?>>&#xf09e;</option>
                  <option value="f143" <?php if ($cat['icono_categoria'] == 'f143') {
                                          echo ' selected ';
                                        } ?>>&#xf143;</option>
                  <option value="f158" <?php if ($cat['icono_categoria'] == 'f158') {
                                          echo ' selected ';
                                        } ?>>&#xf158;</option>
                  <option value="f158" <?php if ($cat['icono_categoria'] == 'f158') {
                                          echo ' selected ';
                                        } ?>>&#xf158;</option>
                  <option value="f156" <?php if ($cat['icono_categoria'] == 'f156') {
                                          echo ' selected ';
                                        } ?>>&#xf156;</option>
                  <option value="f2cd" <?php if ($cat['icono_categoria'] == 'f2cd') {
                                          echo ' selected ';
                                        } ?>>&#xf2cd;</option>
                  <option value="f267" <?php if ($cat['icono_categoria'] == 'f267') {
                                          echo ' selected ';
                                        } ?>>&#xf267;</option>
                  <option value="f0c7" <?php if ($cat['icono_categoria'] == 'f0c7') {
                                          echo ' selected ';
                                        } ?>>&#xf0c7;</option>
                  <option value="f0c4" <?php if ($cat['icono_categoria'] == 'f0c4') {
                                          echo ' selected ';
                                        } ?>>&#xf0c4;</option>
                  <option value="f28a" <?php if ($cat['icono_categoria'] == 'f28a') {
                                          echo ' selected ';
                                        } ?>>&#xf28a;</option>
                  <option value="f002" <?php if ($cat['icono_categoria'] == 'f002') {
                                          echo ' selected ';
                                        } ?>>&#xf002;</option>
                  <option value="f010" <?php if ($cat['icono_categoria'] == 'f010') {
                                          echo ' selected ';
                                        } ?>>&#xf010;</option>
                  <option value="f00e" <?php if ($cat['icono_categoria'] == 'f00e') {
                                          echo ' selected ';
                                        } ?>>&#xf00e;</option>
                  <option value="f213" <?php if ($cat['icono_categoria'] == 'f213') {
                                          echo ' selected ';
                                        } ?>>&#xf213;</option>
                  <option value="f1d8" <?php if ($cat['icono_categoria'] == 'f1d8') {
                                          echo ' selected ';
                                        } ?>>&#xf1d8;</option>
                  <option value="f1d9" <?php if ($cat['icono_categoria'] == 'f1d9') {
                                          echo ' selected ';
                                        } ?>>&#xf1d9;</option>
                  <option value="f233" <?php if ($cat['icono_categoria'] == 'f233') {
                                          echo ' selected ';
                                        } ?>>&#xf233;</option>
                  <option value="f064" <?php if ($cat['icono_categoria'] == 'f064') {
                                          echo ' selected ';
                                        } ?>>&#xf064;</option>
                  <option value="f1e0" <?php if ($cat['icono_categoria'] == 'f1e0') {
                                          echo ' selected ';
                                        } ?>>&#xf1e0;</option>
                  <option value="f1e1" <?php if ($cat['icono_categoria'] == 'f1e1') {
                                          echo ' selected ';
                                        } ?>>&#xf1e1;</option>
                  <option value="f14d" <?php if ($cat['icono_categoria'] == 'f14d') {
                                          echo ' selected ';
                                        } ?>>&#xf14d;</option>
                  <option value="f045" <?php if ($cat['icono_categoria'] == 'f045') {
                                          echo ' selected ';
                                        } ?>>&#xf045;</option>
                  <option value="f20b" <?php if ($cat['icono_categoria'] == 'f20b') {
                                          echo ' selected ';
                                        } ?>>&#xf20b;</option>
                  <option value="f20b" <?php if ($cat['icono_categoria'] == 'f20b') {
                                          echo ' selected ';
                                        } ?>>&#xf20b;</option>
                  <option value="f132" <?php if ($cat['icono_categoria'] == 'f132') {
                                          echo ' selected ';
                                        } ?>>&#xf132;</option>
                  <option value="f21a" <?php if ($cat['icono_categoria'] == 'f21a') {
                                          echo ' selected ';
                                        } ?>>&#xf21a;</option>
                  <option value="f214" <?php if ($cat['icono_categoria'] == 'f214') {
                                          echo ' selected ';
                                        } ?>>&#xf214;</option>
                  <option value="f290" <?php if ($cat['icono_categoria'] == 'f290') {
                                          echo ' selected ';
                                        } ?>>&#xf290;</option>
                  <option value="f291" <?php if ($cat['icono_categoria'] == 'f291') {
                                          echo ' selected ';
                                        } ?>>&#xf291;</option>
                  <option value="f07a" <?php if ($cat['icono_categoria'] == 'f07a') {
                                          echo ' selected ';
                                        } ?>>&#xf07a;</option>
                  <option value="f2cc" <?php if ($cat['icono_categoria'] == 'f2cc') {
                                          echo ' selected ';
                                        } ?>>&#xf2cc;</option>
                  <option value="f090" <?php if ($cat['icono_categoria'] == 'f090') {
                                          echo ' selected ';
                                        } ?>>&#xf090;</option>
                  <option value="f2a7" <?php if ($cat['icono_categoria'] == 'f2a7') {
                                          echo ' selected ';
                                        } ?>>&#xf2a7;</option>
                  <option value="f08b" <?php if ($cat['icono_categoria'] == 'f08b') {
                                          echo ' selected ';
                                        } ?>>&#xf08b;</option>
                  <option value="f012" <?php if ($cat['icono_categoria'] == 'f012') {
                                          echo ' selected ';
                                        } ?>>&#xf012;</option>
                  <option value="f2a7" <?php if ($cat['icono_categoria'] == 'f2a7') {
                                          echo ' selected ';
                                        } ?>>&#xf2a7;</option>
                  <option value="f215" <?php if ($cat['icono_categoria'] == 'f215') {
                                          echo ' selected ';
                                        } ?>>&#xf215;</option>
                  <option value="f0e8" <?php if ($cat['icono_categoria'] == 'f0e8') {
                                          echo ' selected ';
                                        } ?>>&#xf0e8;</option>
                  <option value="f216" <?php if ($cat['icono_categoria'] == 'f216') {
                                          echo ' selected ';
                                        } ?>>&#xf216;</option>
                  <option value="f17e" <?php if ($cat['icono_categoria'] == 'f17e') {
                                          echo ' selected ';
                                        } ?>>&#xf17e;</option>
                  <option value="f198" <?php if ($cat['icono_categoria'] == 'f198') {
                                          echo ' selected ';
                                        } ?>>&#xf198;</option>
                  <option value="f1de" <?php if ($cat['icono_categoria'] == 'f1de') {
                                          echo ' selected ';
                                        } ?>>&#xf1de;</option>
                  <option value="f1e7" <?php if ($cat['icono_categoria'] == 'f1e7') {
                                          echo ' selected ';
                                        } ?>>&#xf1e7;</option>
                  <option value="f118" <?php if ($cat['icono_categoria'] == 'f118') {
                                          echo ' selected ';
                                        } ?>>&#xf118;</option>
                  <option value="f2ab" <?php if ($cat['icono_categoria'] == 'f2ab') {
                                          echo ' selected ';
                                        } ?>>&#xf2ab;</option>
                  <option value="f2ac" <?php if ($cat['icono_categoria'] == 'f2ac') {
                                          echo ' selected ';
                                        } ?>>&#xf2ac;</option>
                  <option value="f2ad" <?php if ($cat['icono_categoria'] == 'f2ad') {
                                          echo ' selected ';
                                        } ?>>&#xf2ad;</option>
                  <option value="f2dc" <?php if ($cat['icono_categoria'] == 'f2dc') {
                                          echo ' selected ';
                                        } ?>>&#xf2dc;</option>
                  <option value="f1e3" <?php if ($cat['icono_categoria'] == 'f1e3') {
                                          echo ' selected ';
                                        } ?>>&#xf1e3;</option>
                  <option value="f0dc" <?php if ($cat['icono_categoria'] == 'f0dc') {
                                          echo ' selected ';
                                        } ?>>&#xf0dc;</option>
                  <option value="f15d" <?php if ($cat['icono_categoria'] == 'f15d') {
                                          echo ' selected ';
                                        } ?>>&#xf15d;</option>
                  <option value="f15e" <?php if ($cat['icono_categoria'] == 'f15e') {
                                          echo ' selected ';
                                        } ?>>&#xf15e;</option>
                  <option value="f160" <?php if ($cat['icono_categoria'] == 'f160') {
                                          echo ' selected ';
                                        } ?>>&#xf160;</option>
                  <option value="f161" <?php if ($cat['icono_categoria'] == 'f161') {
                                          echo ' selected ';
                                        } ?>>&#xf161;</option>
                  <option value="f0de" <?php if ($cat['icono_categoria'] == 'f0de') {
                                          echo ' selected ';
                                        } ?>>&#xf0de;</option>
                  <option value="f0dd" <?php if ($cat['icono_categoria'] == 'f0dd') {
                                          echo ' selected ';
                                        } ?>>&#xf0dd;</option>
                  <option value="f0dd" <?php if ($cat['icono_categoria'] == 'f0dd') {
                                          echo ' selected ';
                                        } ?>>&#xf0dd;</option>
                  <option value="f162" <?php if ($cat['icono_categoria'] == 'f162') {
                                          echo ' selected ';
                                        } ?>>&#xf162;</option>
                  <option value="f163" <?php if ($cat['icono_categoria'] == 'f163') {
                                          echo ' selected ';
                                        } ?>>&#xf163;</option>
                  <option value="f0de" <?php if ($cat['icono_categoria'] == 'f0de') {
                                          echo ' selected ';
                                        } ?>>&#xf0de;</option>
                  <option value="f1be" <?php if ($cat['icono_categoria'] == 'f1be') {
                                          echo ' selected ';
                                        } ?>>&#xf1be;</option>
                  <option value="f197" <?php if ($cat['icono_categoria'] == 'f197') {
                                          echo ' selected ';
                                        } ?>>&#xf197;</option>
                  <option value="f110" <?php if ($cat['icono_categoria'] == 'f110') {
                                          echo ' selected ';
                                        } ?>>&#xf110;</option>
                  <option value="f1b1" <?php if ($cat['icono_categoria'] == 'f1b1') {
                                          echo ' selected ';
                                        } ?>>&#xf1b1;</option>
                  <option value="f1bc" <?php if ($cat['icono_categoria'] == 'f1bc') {
                                          echo ' selected ';
                                        } ?>>&#xf1bc;</option>
                  <option value="f0c8" <?php if ($cat['icono_categoria'] == 'f0c8') {
                                          echo ' selected ';
                                        } ?>>&#xf0c8;</option>
                  <option value="f096" <?php if ($cat['icono_categoria'] == 'f096') {
                                          echo ' selected ';
                                        } ?>>&#xf096;</option>
                  <option value="f18d" <?php if ($cat['icono_categoria'] == 'f18d') {
                                          echo ' selected ';
                                        } ?>>&#xf18d;</option>
                  <option value="f16c" <?php if ($cat['icono_categoria'] == 'f16c') {
                                          echo ' selected ';
                                        } ?>>&#xf16c;</option>
                  <option value="f005" <?php if ($cat['icono_categoria'] == 'f005') {
                                          echo ' selected ';
                                        } ?>>&#xf005;</option>
                  <option value="f089" <?php if ($cat['icono_categoria'] == 'f089') {
                                          echo ' selected ';
                                        } ?>>&#xf089;</option>
                  <option value="f123" <?php if ($cat['icono_categoria'] == 'f123') {
                                          echo ' selected ';
                                        } ?>>&#xf123;</option>
                  <option value="f123" <?php if ($cat['icono_categoria'] == 'f123') {
                                          echo ' selected ';
                                        } ?>>&#xf123;</option>
                  <option value="f123" <?php if ($cat['icono_categoria'] == 'f123') {
                                          echo ' selected ';
                                        } ?>>&#xf123;</option>
                  <option value="f006" <?php if ($cat['icono_categoria'] == 'f006') {
                                          echo ' selected ';
                                        } ?>>&#xf006;</option>
                  <option value="f1b6" <?php if ($cat['icono_categoria'] == 'f1b6') {
                                          echo ' selected ';
                                        } ?>>&#xf1b6;</option>
                  <option value="f1b7" <?php if ($cat['icono_categoria'] == 'f1b7') {
                                          echo ' selected ';
                                        } ?>>&#xf1b7;</option>
                  <option value="f048" <?php if ($cat['icono_categoria'] == 'f048') {
                                          echo ' selected ';
                                        } ?>>&#xf048;</option>
                  <option value="f051" <?php if ($cat['icono_categoria'] == 'f051') {
                                          echo ' selected ';
                                        } ?>>&#xf051;</option>
                  <option value="f0f1" <?php if ($cat['icono_categoria'] == 'f0f1') {
                                          echo ' selected ';
                                        } ?>>&#xf0f1;</option>
                  <option value="f249" <?php if ($cat['icono_categoria'] == 'f249') {
                                          echo ' selected ';
                                        } ?>>&#xf249;</option>
                  <option value="f24a" <?php if ($cat['icono_categoria'] == 'f24a') {
                                          echo ' selected ';
                                        } ?>>&#xf24a;</option>
                  <option value="f04d" <?php if ($cat['icono_categoria'] == 'f04d') {
                                          echo ' selected ';
                                        } ?>>&#xf04d;</option>
                  <option value="f28d" <?php if ($cat['icono_categoria'] == 'f28d') {
                                          echo ' selected ';
                                        } ?>>&#xf28d;</option>
                  <option value="f28e" <?php if ($cat['icono_categoria'] == 'f28e') {
                                          echo ' selected ';
                                        } ?>>&#xf28e;</option>
                  <option value="f21d" <?php if ($cat['icono_categoria'] == 'f21d') {
                                          echo ' selected ';
                                        } ?>>&#xf21d;</option>
                  <option value="f0cc" <?php if ($cat['icono_categoria'] == 'f0cc') {
                                          echo ' selected ';
                                        } ?>>&#xf0cc;</option>
                  <option value="f1a4" <?php if ($cat['icono_categoria'] == 'f1a4') {
                                          echo ' selected ';
                                        } ?>>&#xf1a4;</option>
                  <option value="f1a3" <?php if ($cat['icono_categoria'] == 'f1a3') {
                                          echo ' selected ';
                                        } ?>>&#xf1a3;</option>
                  <option value="f12c" <?php if ($cat['icono_categoria'] == 'f12c') {
                                          echo ' selected ';
                                        } ?>>&#xf12c;</option>
                  <option value="f239" <?php if ($cat['icono_categoria'] == 'f239') {
                                          echo ' selected ';
                                        } ?>>&#xf239;</option>
                  <option value="f0f2" <?php if ($cat['icono_categoria'] == 'f0f2') {
                                          echo ' selected ';
                                        } ?>>&#xf0f2;</option>
                  <option value="f185" <?php if ($cat['icono_categoria'] == 'f185') {
                                          echo ' selected ';
                                        } ?>>&#xf185;</option>
                  <option value="f2dd" <?php if ($cat['icono_categoria'] == 'f2dd') {
                                          echo ' selected ';
                                        } ?>>&#xf2dd;</option>
                  <option value="f12b" <?php if ($cat['icono_categoria'] == 'f12b') {
                                          echo ' selected ';
                                        } ?>>&#xf12b;</option>
                  <option value="f1cd" <?php if ($cat['icono_categoria'] == 'f1cd') {
                                          echo ' selected ';
                                        } ?>>&#xf1cd;</option>
                  <option value="f0ce" <?php if ($cat['icono_categoria'] == 'f0ce') {
                                          echo ' selected ';
                                        } ?>>&#xf0ce;</option>
                  <option value="f10a" <?php if ($cat['icono_categoria'] == 'f10a') {
                                          echo ' selected ';
                                        } ?>>&#xf10a;</option>
                  <option value="f0e4" <?php if ($cat['icono_categoria'] == 'f0e4') {
                                          echo ' selected ';
                                        } ?>>&#xf0e4;</option>
                  <option value="f02b" <?php if ($cat['icono_categoria'] == 'f02b') {
                                          echo ' selected ';
                                        } ?>>&#xf02b;</option>
                  <option value="f02c" <?php if ($cat['icono_categoria'] == 'f02c') {
                                          echo ' selected ';
                                        } ?>>&#xf02c;</option>
                  <option value="f0ae" <?php if ($cat['icono_categoria'] == 'f0ae') {
                                          echo ' selected ';
                                        } ?>>&#xf0ae;</option>
                  <option value="f1ba" <?php if ($cat['icono_categoria'] == 'f1ba') {
                                          echo ' selected ';
                                        } ?>>&#xf1ba;</option>
                  <option value="f2c6" <?php if ($cat['icono_categoria'] == 'f2c6') {
                                          echo ' selected ';
                                        } ?>>&#xf2c6;</option>
                  <option value="f26c" <?php if ($cat['icono_categoria'] == 'f26c') {
                                          echo ' selected ';
                                        } ?>>&#xf26c;</option>
                  <option value="f1d5" <?php if ($cat['icono_categoria'] == 'f1d5') {
                                          echo ' selected ';
                                        } ?>>&#xf1d5;</option>
                  <option value="f120" <?php if ($cat['icono_categoria'] == 'f120') {
                                          echo ' selected ';
                                        } ?>>&#xf120;</option>
                  <option value="f034" <?php if ($cat['icono_categoria'] == 'f034') {
                                          echo ' selected ';
                                        } ?>>&#xf034;</option>
                  <option value="f035" <?php if ($cat['icono_categoria'] == 'f035') {
                                          echo ' selected ';
                                        } ?>>&#xf035;</option>
                  <option value="f00a" <?php if ($cat['icono_categoria'] == 'f00a') {
                                          echo ' selected ';
                                        } ?>>&#xf00a;</option>
                  <option value="f009" <?php if ($cat['icono_categoria'] == 'f009') {
                                          echo ' selected ';
                                        } ?>>&#xf009;</option>
                  <option value="f00b" <?php if ($cat['icono_categoria'] == 'f00b') {
                                          echo ' selected ';
                                        } ?>>&#xf00b;</option>
                  <option value="f2b2" <?php if ($cat['icono_categoria'] == 'f2b2') {
                                          echo ' selected ';
                                        } ?>>&#xf2b2;</option>
                  <option value="f2c7" <?php if ($cat['icono_categoria'] == 'f2c7') {
                                          echo ' selected ';
                                        } ?>>&#xf2c7;</option>
                  <option value="f2cb" <?php if ($cat['icono_categoria'] == 'f2cb') {
                                          echo ' selected ';
                                        } ?>>&#xf2cb;</option>
                  <option value="f2ca" <?php if ($cat['icono_categoria'] == 'f2ca') {
                                          echo ' selected ';
                                        } ?>>&#xf2ca;</option>
                  <option value="f2c9" <?php if ($cat['icono_categoria'] == 'f2c9') {
                                          echo ' selected ';
                                        } ?>>&#xf2c9;</option>
                  <option value="f2c8" <?php if ($cat['icono_categoria'] == 'f2c8') {
                                          echo ' selected ';
                                        } ?>>&#xf2c8;</option>
                  <option value="f2c7" <?php if ($cat['icono_categoria'] == 'f2c7') {
                                          echo ' selected ';
                                        } ?>>&#xf2c7;</option>
                  <option value="f2cb" <?php if ($cat['icono_categoria'] == 'f2cb') {
                                          echo ' selected ';
                                        } ?>>&#xf2cb;</option>
                  <option value="f2c7" <?php if ($cat['icono_categoria'] == 'f2c7') {
                                          echo ' selected ';
                                        } ?>>&#xf2c7;</option>
                  <option value="f2c9" <?php if ($cat['icono_categoria'] == 'f2c9') {
                                          echo ' selected ';
                                        } ?>>&#xf2c9;</option>
                  <option value="f2ca" <?php if ($cat['icono_categoria'] == 'f2ca') {
                                          echo ' selected ';
                                        } ?>>&#xf2ca;</option>
                  <option value="f2c8" <?php if ($cat['icono_categoria'] == 'f2c8') {
                                          echo ' selected ';
                                        } ?>>&#xf2c8;</option>
                  <option value="f08d" <?php if ($cat['icono_categoria'] == 'f08d') {
                                          echo ' selected ';
                                        } ?>>&#xf08d;</option>
                  <option value="f165" <?php if ($cat['icono_categoria'] == 'f165') {
                                          echo ' selected ';
                                        } ?>>&#xf165;</option>
                  <option value="f088" <?php if ($cat['icono_categoria'] == 'f088') {
                                          echo ' selected ';
                                        } ?>>&#xf088;</option>
                  <option value="f087" <?php if ($cat['icono_categoria'] == 'f087') {
                                          echo ' selected ';
                                        } ?>>&#xf087;</option>
                  <option value="f164" <?php if ($cat['icono_categoria'] == 'f164') {
                                          echo ' selected ';
                                        } ?>>&#xf164;</option>
                  <option value="f145" <?php if ($cat['icono_categoria'] == 'f145') {
                                          echo ' selected ';
                                        } ?>>&#xf145;</option>
                  <option value="f00d" <?php if ($cat['icono_categoria'] == 'f00d') {
                                          echo ' selected ';
                                        } ?>>&#xf00d;</option>
                  <option value="f057" <?php if ($cat['icono_categoria'] == 'f057') {
                                          echo ' selected ';
                                        } ?>>&#xf057;</option>
                  <option value="f05c" <?php if ($cat['icono_categoria'] == 'f05c') {
                                          echo ' selected ';
                                        } ?>>&#xf05c;</option>
                  <option value="f2d3" <?php if ($cat['icono_categoria'] == 'f2d3') {
                                          echo ' selected ';
                                        } ?>>&#xf2d3;</option>
                  <option value="f2d4" <?php if ($cat['icono_categoria'] == 'f2d4') {
                                          echo ' selected ';
                                        } ?>>&#xf2d4;</option>
                  <option value="f043" <?php if ($cat['icono_categoria'] == 'f043') {
                                          echo ' selected ';
                                        } ?>>&#xf043;</option>
                  <option value="f150" <?php if ($cat['icono_categoria'] == 'f150') {
                                          echo ' selected ';
                                        } ?>>&#xf150;</option>
                  <option value="f191" <?php if ($cat['icono_categoria'] == 'f191') {
                                          echo ' selected ';
                                        } ?>>&#xf191;</option>
                  <option value="f204" <?php if ($cat['icono_categoria'] == 'f204') {
                                          echo ' selected ';
                                        } ?>>&#xf204;</option>
                  <option value="f205" <?php if ($cat['icono_categoria'] == 'f205') {
                                          echo ' selected ';
                                        } ?>>&#xf205;</option>
                  <option value="f152" <?php if ($cat['icono_categoria'] == 'f152') {
                                          echo ' selected ';
                                        } ?>>&#xf152;</option>
                  <option value="f151" <?php if ($cat['icono_categoria'] == 'f151') {
                                          echo ' selected ';
                                        } ?>>&#xf151;</option>
                  <option value="f25c" <?php if ($cat['icono_categoria'] == 'f25c') {
                                          echo ' selected ';
                                        } ?>>&#xf25c;</option>
                  <option value="f238" <?php if ($cat['icono_categoria'] == 'f238') {
                                          echo ' selected ';
                                        } ?>>&#xf238;</option>
                  <option value="f224" <?php if ($cat['icono_categoria'] == 'f224') {
                                          echo ' selected ';
                                        } ?>>&#xf224;</option>
                  <option value="f225" <?php if ($cat['icono_categoria'] == 'f225') {
                                          echo ' selected ';
                                        } ?>>&#xf225;</option>
                  <option value="f1f8" <?php if ($cat['icono_categoria'] == 'f1f8') {
                                          echo ' selected ';
                                        } ?>>&#xf1f8;</option>
                  <option value="f014" <?php if ($cat['icono_categoria'] == 'f014') {
                                          echo ' selected ';
                                        } ?>>&#xf014;</option>
                  <option value="f1bb" <?php if ($cat['icono_categoria'] == 'f1bb') {
                                          echo ' selected ';
                                        } ?>>&#xf1bb;</option>
                  <option value="f181" <?php if ($cat['icono_categoria'] == 'f181') {
                                          echo ' selected ';
                                        } ?>>&#xf181;</option>
                  <option value="f262" <?php if ($cat['icono_categoria'] == 'f262') {
                                          echo ' selected ';
                                        } ?>>&#xf262;</option>
                  <option value="f091" <?php if ($cat['icono_categoria'] == 'f091') {
                                          echo ' selected ';
                                        } ?>>&#xf091;</option>
                  <option value="f0d1" <?php if ($cat['icono_categoria'] == 'f0d1') {
                                          echo ' selected ';
                                        } ?>>&#xf0d1;</option>
                  <option value="f195" <?php if ($cat['icono_categoria'] == 'f195') {
                                          echo ' selected ';
                                        } ?>>&#xf195;</option>
                  <option value="f1e4" <?php if ($cat['icono_categoria'] == 'f1e4') {
                                          echo ' selected ';
                                        } ?>>&#xf1e4;</option>
                  <option value="f173" <?php if ($cat['icono_categoria'] == 'f173') {
                                          echo ' selected ';
                                        } ?>>&#xf173;</option>
                  <option value="f174" <?php if ($cat['icono_categoria'] == 'f174') {
                                          echo ' selected ';
                                        } ?>>&#xf174;</option>
                  <option value="f195" <?php if ($cat['icono_categoria'] == 'f195') {
                                          echo ' selected ';
                                        } ?>>&#xf195;</option>
                  <option value="f26c" <?php if ($cat['icono_categoria'] == 'f26c') {
                                          echo ' selected ';
                                        } ?>>&#xf26c;</option>
                  <option value="f1e8" <?php if ($cat['icono_categoria'] == 'f1e8') {
                                          echo ' selected ';
                                        } ?>>&#xf1e8;</option>
                  <option value="f099" <?php if ($cat['icono_categoria'] == 'f099') {
                                          echo ' selected ';
                                        } ?>>&#xf099;</option>
                  <option value="f081" <?php if ($cat['icono_categoria'] == 'f081') {
                                          echo ' selected ';
                                        } ?>>&#xf081;</option>
                  <option value="f0e9" <?php if ($cat['icono_categoria'] == 'f0e9') {
                                          echo ' selected ';
                                        } ?>>&#xf0e9;</option>
                  <option value="f0cd" <?php if ($cat['icono_categoria'] == 'f0cd') {
                                          echo ' selected ';
                                        } ?>>&#xf0cd;</option>
                  <option value="f0e2" <?php if ($cat['icono_categoria'] == 'f0e2') {
                                          echo ' selected ';
                                        } ?>>&#xf0e2;</option>
                  <option value="f29a" <?php if ($cat['icono_categoria'] == 'f29a') {
                                          echo ' selected ';
                                        } ?>>&#xf29a;</option>
                  <option value="f19c" <?php if ($cat['icono_categoria'] == 'f19c') {
                                          echo ' selected ';
                                        } ?>>&#xf19c;</option>
                  <option value="f127" <?php if ($cat['icono_categoria'] == 'f127') {
                                          echo ' selected ';
                                        } ?>>&#xf127;</option>
                  <option value="f09c" <?php if ($cat['icono_categoria'] == 'f09c') {
                                          echo ' selected ';
                                        } ?>>&#xf09c;</option>
                  <option value="f13e" <?php if ($cat['icono_categoria'] == 'f13e') {
                                          echo ' selected ';
                                        } ?>>&#xf13e;</option>
                  <option value="f0dc" <?php if ($cat['icono_categoria'] == 'f0dc') {
                                          echo ' selected ';
                                        } ?>>&#xf0dc;</option>
                  <option value="f093" <?php if ($cat['icono_categoria'] == 'f093') {
                                          echo ' selected ';
                                        } ?>>&#xf093;</option>
                  <option value="f287" <?php if ($cat['icono_categoria'] == 'f287') {
                                          echo ' selected ';
                                        } ?>>&#xf287;</option>
                  <option value="f155" <?php if ($cat['icono_categoria'] == 'f155') {
                                          echo ' selected ';
                                        } ?>>&#xf155;</option>
                  <option value="f007" <?php if ($cat['icono_categoria'] == 'f007') {
                                          echo ' selected ';
                                        } ?>>&#xf007;</option>
                  <option value="f2bd" <?php if ($cat['icono_categoria'] == 'f2bd') {
                                          echo ' selected ';
                                        } ?>>&#xf2bd;</option>
                  <option value="f2be" <?php if ($cat['icono_categoria'] == 'f2be') {
                                          echo ' selected ';
                                        } ?>>&#xf2be;</option>
                  <option value="f0f0" <?php if ($cat['icono_categoria'] == 'f0f0') {
                                          echo ' selected ';
                                        } ?>>&#xf0f0;</option>
                  <option value="f2c0" <?php if ($cat['icono_categoria'] == 'f2c0') {
                                          echo ' selected ';
                                        } ?>>&#xf2c0;</option>
                  <option value="f234" <?php if ($cat['icono_categoria'] == 'f234') {
                                          echo ' selected ';
                                        } ?>>&#xf234;</option>
                  <option value="f21b" <?php if ($cat['icono_categoria'] == 'f21b') {
                                          echo ' selected ';
                                        } ?>>&#xf21b;</option>
                  <option value="f235" <?php if ($cat['icono_categoria'] == 'f235') {
                                          echo ' selected ';
                                        } ?>>&#xf235;</option>
                  <option value="f0c0" <?php if ($cat['icono_categoria'] == 'f0c0') {
                                          echo ' selected ';
                                        } ?>>&#xf0c0;</option>
                  <option value="f2bb" <?php if ($cat['icono_categoria'] == 'f2bb') {
                                          echo ' selected ';
                                        } ?>>&#xf2bb;</option>
                  <option value="f2bc" <?php if ($cat['icono_categoria'] == 'f2bc') {
                                          echo ' selected ';
                                        } ?>>&#xf2bc;</option>
                  <option value="f221" <?php if ($cat['icono_categoria'] == 'f221') {
                                          echo ' selected ';
                                        } ?>>&#xf221;</option>
                  <option value="f226" <?php if ($cat['icono_categoria'] == 'f226') {
                                          echo ' selected ';
                                        } ?>>&#xf226;</option>
                  <option value="f228" <?php if ($cat['icono_categoria'] == 'f228') {
                                          echo ' selected ';
                                        } ?>>&#xf228;</option>
                  <option value="f237" <?php if ($cat['icono_categoria'] == 'f237') {
                                          echo ' selected ';
                                        } ?>>&#xf237;</option>
                  <option value="f2a9" <?php if ($cat['icono_categoria'] == 'f2a9') {
                                          echo ' selected ';
                                        } ?>>&#xf2a9;</option>
                  <option value="f2aa" <?php if ($cat['icono_categoria'] == 'f2aa') {
                                          echo ' selected ';
                                        } ?>>&#xf2aa;</option>
                  <option value="f03d" <?php if ($cat['icono_categoria'] == 'f03d') {
                                          echo ' selected ';
                                        } ?>>&#xf03d;</option>
                  <option value="f27d" <?php if ($cat['icono_categoria'] == 'f27d') {
                                          echo ' selected ';
                                        } ?>>&#xf27d;</option>
                  <option value="f194" <?php if ($cat['icono_categoria'] == 'f194') {
                                          echo ' selected ';
                                        } ?>>&#xf194;</option>
                  <option value="f1ca" <?php if ($cat['icono_categoria'] == 'f1ca') {
                                          echo ' selected ';
                                        } ?>>&#xf1ca;</option>
                  <option value="f189" <?php if ($cat['icono_categoria'] == 'f189') {
                                          echo ' selected ';
                                        } ?>>&#xf189;</option>
                  <option value="f2a0" <?php if ($cat['icono_categoria'] == 'f2a0') {
                                          echo ' selected ';
                                        } ?>>&#xf2a0;</option>
                  <option value="f027" <?php if ($cat['icono_categoria'] == 'f027') {
                                          echo ' selected ';
                                        } ?>>&#xf027;</option>
                  <option value="f026" <?php if ($cat['icono_categoria'] == 'f026') {
                                          echo ' selected ';
                                        } ?>>&#xf026;</option>
                  <option value="f028" <?php if ($cat['icono_categoria'] == 'f028') {
                                          echo ' selected ';
                                        } ?>>&#xf028;</option>
                  <option value="f071" <?php if ($cat['icono_categoria'] == 'f071') {
                                          echo ' selected ';
                                        } ?>>&#xf071;</option>
                  <option value="f1d7" <?php if ($cat['icono_categoria'] == 'f1d7') {
                                          echo ' selected ';
                                        } ?>>&#xf1d7;</option>
                  <option value="f18a" <?php if ($cat['icono_categoria'] == 'f18a') {
                                          echo ' selected ';
                                        } ?>>&#xf18a;</option>
                  <option value="f1d7" <?php if ($cat['icono_categoria'] == 'f1d7') {
                                          echo ' selected ';
                                        } ?>>&#xf1d7;</option>
                  <option value="f232" <?php if ($cat['icono_categoria'] == 'f232') {
                                          echo ' selected ';
                                        } ?>>&#xf232;</option>
                  <option value="f193" <?php if ($cat['icono_categoria'] == 'f193') {
                                          echo ' selected ';
                                        } ?>>&#xf193;</option>
                  <option value="f29b" <?php if ($cat['icono_categoria'] == 'f29b') {
                                          echo ' selected ';
                                        } ?>>&#xf29b;</option>
                  <option value="f1eb" <?php if ($cat['icono_categoria'] == 'f1eb') {
                                          echo ' selected ';
                                        } ?>>&#xf1eb;</option>
                  <option value="f266" <?php if ($cat['icono_categoria'] == 'f266') {
                                          echo ' selected ';
                                        } ?>>&#xf266;</option>
                  <option value="f2d3" <?php if ($cat['icono_categoria'] == 'f2d3') {
                                          echo ' selected ';
                                        } ?>>&#xf2d3;</option>
                  <option value="f2d4" <?php if ($cat['icono_categoria'] == 'f2d4') {
                                          echo ' selected ';
                                        } ?>>&#xf2d4;</option>
                  <option value="f2d0" <?php if ($cat['icono_categoria'] == 'f2d0') {
                                          echo ' selected ';
                                        } ?>>&#xf2d0;</option>
                  <option value="f2d1" <?php if ($cat['icono_categoria'] == 'f2d1') {
                                          echo ' selected ';
                                        } ?>>&#xf2d1;</option>
                  <option value="f2d2" <?php if ($cat['icono_categoria'] == 'f2d2') {
                                          echo ' selected ';
                                        } ?>>&#xf2d2;</option>
                  <option value="f17a" <?php if ($cat['icono_categoria'] == 'f17a') {
                                          echo ' selected ';
                                        } ?>>&#xf17a;</option>
                  <option value="f159" <?php if ($cat['icono_categoria'] == 'f159') {
                                          echo ' selected ';
                                        } ?>>&#xf159;</option>
                  <option value="f19a" <?php if ($cat['icono_categoria'] == 'f19a') {
                                          echo ' selected ';
                                        } ?>>&#xf19a;</option>
                  <option value="f297" <?php if ($cat['icono_categoria'] == 'f297') {
                                          echo ' selected ';
                                        } ?>>&#xf297;</option>
                  <option value="f2de" <?php if ($cat['icono_categoria'] == 'f2de') {
                                          echo ' selected ';
                                        } ?>>&#xf2de;</option>
                  <option value="f298" <?php if ($cat['icono_categoria'] == 'f298') {
                                          echo ' selected ';
                                        } ?>>&#xf298;</option>
                  <option value="f0ad" <?php if ($cat['icono_categoria'] == 'f0ad') {
                                          echo ' selected ';
                                        } ?>>&#xf0ad;</option>
                  <option value="f168" <?php if ($cat['icono_categoria'] == 'f168') {
                                          echo ' selected ';
                                        } ?>>&#xf168;</option>
                  <option value="f169" <?php if ($cat['icono_categoria'] == 'f169') {
                                          echo ' selected ';
                                        } ?>>&#xf169;</option>
                  <option value="f23b" <?php if ($cat['icono_categoria'] == 'f23b') {
                                          echo ' selected ';
                                        } ?>>&#xf23b;</option>
                  <option value="f1d4" <?php if ($cat['icono_categoria'] == 'f1d4') {
                                          echo ' selected ';
                                        } ?>>&#xf1d4;</option>
                  <option value="f19e" <?php if ($cat['icono_categoria'] == 'f19e') {
                                          echo ' selected ';
                                        } ?>>&#xf19e;</option>
                  <option value="f23b" <?php if ($cat['icono_categoria'] == 'f23b') {
                                          echo ' selected ';
                                        } ?>>&#xf23b;</option>
                  <option value="f1d4" <?php if ($cat['icono_categoria'] == 'f1d4') {
                                          echo ' selected ';
                                        } ?>>&#xf1d4;</option>
                  <option value="f1e9" <?php if ($cat['icono_categoria'] == 'f1e9') {
                                          echo ' selected ';
                                        } ?>>&#xf1e9;</option>
                  <option value="f157" <?php if ($cat['icono_categoria'] == 'f157') {
                                          echo ' selected ';
                                        } ?>>&#xf157;</option>
                  <option value="f2b1" <?php if ($cat['icono_categoria'] == 'f2b1') {
                                          echo ' selected ';
                                        } ?>>&#xf2b1;</option>
                  <option value="f167" <?php if ($cat['icono_categoria'] == 'f167') {
                                          echo ' selected ';
                                        } ?>>&#xf167;</option>
                  <option value="f16a" <?php if ($cat['icono_categoria'] == 'f16a') {
                                          echo ' selected ';
                                        } ?>>&#xf16a;</option>
                  <option value="f166" <?php if ($cat['icono_categoria'] == 'f166') {
                                          echo ' selected ';
                                        } ?>>&#xf166;</option>



                </select>
              </div>
            </div>
            <button type="submit" id="agregar_cate" class="btn btn-info waves-effect">Editar</button>
            <a href="javascript:window.history.back()" class="btn btn-danger waves-effect">Cancelar</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<script>
  function selecticon(obj) {
    var icono = $('#icono option:selected').val();
    $('#iconitos').val(icono);
  }

  $('#comercio_toma')
    .editableSelect()
    .on('select.editable-select', function(e, li) {
      $('#comercio_pago').val(li.val());
    });
</script>