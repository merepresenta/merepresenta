merepresenta_query = { "sigla_estado": "RS" };

/**
 * Função que busca dados no servidor para atualizar na tela
 */
var buscaDados = function() {
  jQuery.ajax({
    url: "/api/v1/merepresenta.php",
    data: JSON.stringify({ 
      query: merepresenta_query,
      limites: {primeiro: 1, ultimo: 10}
    }),
    dataType: "json",
    type: "post",
    success: function(result) {
      var tabela = null;
      if(result.length > 0) {
        var keys = Object.keys(result[0]);
        
        tabela = jQuery("<table>", {class: "tabela-dados table table-striped"});
        var h = jQuery("<thead>").appendTo(tabela);
        var tr = jQuery("<tr>").appendTo(h);
        jQuery(keys).each(function(idx, key) {
          if( key != "minibio")
            jQuery("<th>", {text: key}).appendTo(tr);          
        })
        
        var tbody = jQuery("<tbody>").appendTo(tabela);
        jQuery(result).each(function(idx,r) {
          var linha = jQuery("<tr>").appendTo(tbody);
          jQuery(keys).each(function(idx, key) {
            if( key != "minibio")
              jQuery("<td>", {text: r[key]}).appendTo(linha);          
          });
        });
      }
      jQuery("#dados_filtrados").html(tabela);
    }
  });
};