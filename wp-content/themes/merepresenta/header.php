<!doctype html>
<?php 
  require_once "ambiente.php";
  $ambiente = new Ambiente();

  $template_uri = get_template_directory_uri();
  $js_home = "$template_uri/js/";
?>
<html>
  <header>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?=$ambiente->generateCssURI('reset.css')?>">
    <link rel="stylesheet" href="<?=$ambiente->generateCssURI('jquery-ui.css')?>">
    <link rel="stylesheet" href="<?=$ambiente->generateCssURI('bootstrap.min.css')?>">
    <script src="<?=$ambiente->generateJsURI('jquery-3.2.1.min.js')?>"></script>
    <script src="<?=$ambiente->generateJsURI('jquery-ui.js')?>"></script>
<!--     <script src="<?=$ambiente->generateJsURI('popper.js')?>"></script>
    <script src="<?=$ambiente->generateJsURI('bootstrap.min.js')?>"></script>
 -->    <title><?php bloginfo('name') ?></title>
    <?php wp_head(); ?>
  </header>

  <?php 
    $args = array('theme_location' => 'header_menu');
    wp_nav_menu($args);
  ?>
  <body>
