<!doctype html>
<?php $css_home = get_template_directory_uri() . "/css/" ?>
<html>
  <header>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?=$css_home?>reset.css">
    <title><?php bloginfo('name') ?></title>
    <?php wp_head(); ?>
  </header>

  <?php 
    $args = array('theme_location' => 'header_menu');
    wp_nav_menu($args);
  ?>
  <body>
