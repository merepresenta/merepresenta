var merepresenta_query = function() {
  var retorno =  { };
  sigla_estado = jQuery("input:checked[name='sigla_estado']").map(function(k,v){return v.value;}).toArray().join(',');
  if (sigla_estado != '')
    retorno.sigla_estado = sigla_estado;
  sigla_partido = jQuery("input:checked[name='sigla_partido']").map(function(k,v){return v.value;}).toArray().join(',');
  if (sigla_partido != '')
    retorno.sigla_partido = sigla_partido;
  return retorno;
};

var carregaPartidos = function() {
  jQuery.ajax({
    url: "/api/v1/partidos.php",
    success: function(result) {
      var div = jQuery("#filtro_partido");
      div.html('<h3>Partidos</h3>');
      jQuery(result).each(function(key, value) {
        var d = jQuery("<div>").appendTo(div);
        jQuery("<input>", {name: "sigla_partido", class: "reload-data", value: value.sigla_partido, type: "checkbox", id: 'partido_' + value.sigla_partido}).appendTo(d);
        jQuery("<label>", {for: 'partido_' + value.sigla_partido, text: value.sigla_partido}).appendTo(d);
      });
    }
  });
};

var carregaEstados = function() {
  jQuery.ajax({
    url: "/api/v1/estados.php",
    success: function(result) {
      var div = jQuery("#filtro_uf");
      div.html('<h3>Estados</h3>');
      jQuery(result).each(function(key, value) {
        var d = jQuery("<div>").appendTo(div);
        jQuery("<input>", {name: "sigla_estado", class: "reload-data", value: value.sigla_estado, type: "checkbox", id: 'estado_' + value.sigla_estado}).appendTo(d);
        jQuery("<label>", {for: 'estado_' + value.sigla_estado, text: value.sigla_estado}).appendTo(d);
      });
    }
  });  
};

/**
 * Função que busca dados no servidor para atualizar na tela
 */
var buscaDados = function() {
  jQuery.ajax({
    url: "/api/v1/merepresenta.php",
    data: JSON.stringify({ 
      query: merepresenta_query(),
      limites: {primeiro: 1, ultimo: 10}
    }),
    dataType: "json",
    type: "post",
    success: function(result) {
      var saida = null;
      if(result.length > 0) {
        var keys = Object.keys(result[0]);
        
        saida = jQuery("<table>", {class: "tabela-dados table table-striped"});
        var h = jQuery("<thead>").appendTo(saida);
        var tr = jQuery("<tr>").appendTo(h);
        jQuery(keys).each(function(idx, value) {
          if( value != "minibio")
            jQuery("<th>", {text: value}).appendTo(tr);          
        })
        
        var tbody = jQuery("<tbody>").appendTo(saida);
        jQuery(result).each(function(idx,r) {
          var linha = jQuery("<tr>").appendTo(tbody);
          jQuery(keys).each(function(idx, value) {
            if( value != "minibio")
              jQuery("<td>", {text: r[value]}).appendTo(linha);          
          });
        });
      } else {
        saida = jQuery("<h3>", {text: "Sem dados ligados à requisição"});
      }
      jQuery("#dados_filtrados").html(saida);
    }
  });
};

jQuery(window).load( function() {
  carregaPartidos();
  carregaEstados();
  jQuery("#bt_filtro").click(function(){
    buscaDados();
  });
})