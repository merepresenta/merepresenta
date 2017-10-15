<!doctype html>
<?php 
  $template_uri = get_template_directory_uri();
  $css_home = "$template_uri/css/";
  $js_home = "$template_uri/js/";
?>
<html>
  <header>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?=$css_home?>reset.css">
    <link rel="stylesheet" href="<?=$css_home?>jquery-ui.css">
    <link rel="stylesheet" href="<?=$css_home?>bootstrap.min.css">
    <script src="<?=$js_home?>jquery-3.2.1.min.js"></script>
    <script src="<?=$js_home?>jquery-ui.js"></script>
<!--     <script src="<?=$js_home?>popper.js"></script>
    <script src="<?=$js_home?>bootstrap.min.js"></script> -->
    <title><?php bloginfo('name') ?></title>
    <?php wp_head(); ?>
  </header>

  <?php 
    $args = array('theme_location' => 'header_menu');
    wp_nav_menu($args);
  ?>
  <body>
