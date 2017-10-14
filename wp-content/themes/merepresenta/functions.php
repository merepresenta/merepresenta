<?php 
function register_menu() {
  register_nav_menu('header_menu', 'main-menu');
}

add_action('init', 'register_menu');
?>