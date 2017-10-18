<?php
/*
Plugin name: MeRepresenta
Plugin URI: http://plugin.merepresenta.org.br
Description: Plugin para apresentação do dados de candidadtos do #MeRepresenta
Author: Luiz Alberoni da Silva
Author URI: https://github.com/luizalbsilva
Version: 1.0
License: GPL2
 
{Plugin Name} is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.
 
{Plugin Name} is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
 
You should have received a copy of the GNU General Public License
along with {Plugin Name}. If not, see {License URI}.
*/

function parameter_queryvars( $qvars )
{
  $qvars[] = 'ch_pol';
  return $qvars;
}
add_filter('query_vars', 'parameter_queryvars' );
?>
