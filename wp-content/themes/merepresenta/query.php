<?php
  require_once("ambiente.php");
  $ambiente = new Ambiente();
  
  $queryRunner = $ambiente->queryRunner();

  $ufs = $queryRunner->get_results("select sigla from Estado order by sigla");
  $partidos = $queryRunner->get_results("select id, sigla from Partido order by sigla");
  $pautas = $queryRunner->get_results("select id, texto from Pergunta order by id");
  $generos = $queryRunner->get_results("select distinct genero_tse from Pessoa order by genero_tse");
  $cores = $queryRunner->get_results("select distinct cor_tse from Pessoa order by cor_tse");
?>

<form id="download-files" action="<?= get_template_directory_uri() ?>/download.php" method="post" >
</form>

<div class="container">
  <div id="dados_menu">
    <div id="filtro_uf">
      <h3>Estado:</h3>
      <?php foreach ($ufs as $estado) { ?>
        <label>
          <input type="checkbox" value="<?= $estado->sigla ?>" id="estado_<?= $estado->sigla ?>" class="chk_estado"> <?= $estado->sigla ?>
        </label>
      <?php } ?>
    </div>
    <div id="filtro_cidade">
      <h3>Cidades:</h3>
      <input type="text" name="cidade" id="filtro-cidade-escolha" />
      <button id="btn-add-city">+</button>
      <div id="cidades-escolhidas">
      </div>
    </div>
    <div id="filtro_partido">
      <h3>Partidos:</h3>
      <?php foreach ($partidos as $partido) { ?>
        <label>
          <input type="checkbox" value="<?= $partido->id ?>" id="partido_<?= $partido->sigla ?>" class="chk-partido"> <?= $partido->sigla ?>
        </label>
      <?php } ?>
    </div>
    <div id="filtro_pautas">
      <h3>Pautas</h3>
      <?php foreach ($pautas as $pauta) { ?>
        <label>
          <input type="checkbox" value="<?= $pauta->id ?>" id="pauta_<?= $pauta->id ?>" class="chk-pauta"> <?= $pauta->texto ?>
        </label>
      <?php } ?>
    </div>
    <div id="filtro_genero">
      <h3>Genero</h3>
      <select name="" id="sel-genero">
        <?php foreach ($generos as $genero) { ?>
          <label>
            <option value="<?= $genero->genero_tse ?>"><?= $genero->genero_tse ?></option>
          </label>
        <?php } ?>
      </select>
    </div>
    <div id="filtro_cor">
      <h3>Cor</h3>
      <select name="" id="sel-cor">
        <?php foreach ($cores as $cor) { ?>
          <option value="<?= $cor->cor_tse ?>"><?= $cor->cor_tse ?></option>
        <?php } ?>        
      </select>
    </div>
    <div id="filtro_nota_partido">
      <h3>Nota do Partido</h3>
      <label for="nota_partido_inicio">Nota mínima</label>
      <input type="number" min="0" max="100" id="nota_partido_inicio" />
      <label for="nota_partido_fim">Nota máxima</label>
      <input type="number" min="0" max="100" id="nota_partido_fim" />
    </div>
    <button id="bt_filtro">Filtro</button>
  </div>

  <div id="dados_filtrados" style="overflow-x: scroll; width: 50%">

  </div>
</div>

<script>
  var query = null;
  var quantidade_pagina = 10;
  var cDadosFiltrados = $("#dados_filtrados");

  var downloadAllData = function() {
    var frm = $("#download-files");
    $("#download-files input").remove();
    Object.keys(query).forEach(function(field,y) {
      var o = $("<input>", {type: "hidden", name: field,value: JSON.stringify(query[field])}).appendTo(frm);
    });
    frm.submit();
  };

  var requisita_dados = function(inicial) {
    $.ajax({
      url: "/api/v1/merepresenta.php",
      data: JSON.stringify({ 
        query: query,
        limites: {primeiro: (inicial - 1) * quantidade_pagina , quantidade: quantidade_pagina}
      }),
      dataType: "json",
      type: "post",
      success: function(resultado) {
        var saida = null;
        var result = resultado.data;
        if(result.length > 0) {
          var keys = Object.keys(result[0]);
          
          saida = $("<table>", {class: "tabela-dados table table-striped"});
          var h = $("<thead>").appendTo(saida);
          var tr = $("<tr>").appendTo(h);
          $(keys).each(function(idx, value) {
            if( value != "minibio")
              $("<th>", {text: value}).appendTo(tr);          
          })
          
          var tbody = $("<tbody>").appendTo(saida);
          $(result).each(function(idx,r) {
            var linha = $("<tr>").appendTo(tbody);
            $(keys).each(function(idx, value) {
              if( value != "minibio")
                $("<td>", {text: r[value]}).appendTo(linha);          
            });
          });
          var painel = $("<div>"),
              paginaAtual = (resultado.pagination.first / resultado.pagination.quantity)+1;

          Array.apply(null, {length: Math.ceil(resultado.pagination.count / resultado.pagination.quantity)}).
            map(Number.call, Number).
            forEach(function(rec){
              var pagina = rec + 1;
              if (pagina == paginaAtual)
                c = $("<div>", {text: pagina, class: 'lnk-paginacao'});
              else {
                c = $("<a>", {text: pagina, href: '#', class: 'lnk-paginacao'});
                c.on("click", function(){
                  requisita_dados(pagina);
                });
              }

              c.appendTo(painel);
              return c;
            });

          var lnkDownload = $("<a>", {text: "download", href: "#"});
          lnkDownload.on("click", downloadAllData);
          cDadosFiltrados.html(saida);
          cDadosFiltrados.append(painel);
          cDadosFiltrados.append(lnkDownload);
        } else {
          saida = $("<h3>", {text: "Sem dados ligados à requisição"});
          cDadosFiltrados.html(saida);
        }
      }
    });
  }

  var configura_query = function() {
    query =  { };

    var estados = $(".chk_estado:checked").map(function(i,obj){return obj.value}).toArray();
    if (estados.length>0) query.sigla_estado = estados;

    var cidades = $(".chk-cidade:checked").map(function(i,obj){return $(obj).attr("cid_id")}).toArray();
    if (cidades.length>0) query.id_cidade = cidades;

    var partidos = $(".chk-partido:checked").map(function(i,obj){return obj.value}).toArray();
    if (partidos.length>0) query.id_partido = partidos;

    var pautas = $(".chk-pauta:checked").map(function(i,obj){return obj.value}).toArray();
    if (pautas.length>0) query.pautas = pautas;

    var genero = $("#sel-genero").val();
    if (genero!='') query.genero = genero;

    var cor = $("#sel-cor").val();
    if (cor!='') query.cor_tse = cor;

    var cNotaInicial = $("#nota_partido_inicio"),
        cNotaFinal = $("#nota_partido_fim");
    var nota_partido = [parseInt(cNotaInicial.val()),
                        parseInt(cNotaFinal.val())];
    if ((!isNaN(nota_partido[0]))  || (!isNaN(nota_partido[1])) ) {
      if (isNaN(nota_partido[0])) { 
        nota_partido[0] = 0;
        cNotaInicial.val(0);
      }
      if (isNaN(nota_partido[1])) {
        nota_partido[1] = 100;
        cNotaFinal.val(100);
      }
      query.nota_partido = nota_partido;
    }
  };

  $(window).on("load",function(){
    var cBusca = $("#filtro-cidade-escolha");
    var cBtnCity = $("#btn-add-city");
    var cBtnFiltro = $("#bt_filtro");
    var cPnlCities = $("#cidades-escolhidas");

    cBtnCity.prop("disabled", true);
    cBusca.autocomplete({
      source: function( request, response ) {
        $.ajax( {
          url: "/api/v1/cidades.php",
          method: "get",
          accept: "application/json",
          dataType: "json",
          data: {
            nome: request.term
          },
          success: function( data ) {
            response( data.map(function(valor){
              return {
                label: valor.nome_cidade,
                value: valor.id
              };
            }) );
          }
        } );
      },
      minLength: 2,
      focus: function(event,ui){
        return false;
      },
      change: function(event, ui) {
        return false;
      },
      select: function( event, ui ) {
        cBusca.val(ui.item.label);
        cBusca.prop("cid_id", ui.item.value);
        cBtnCity.prop("disabled", false);
        return false;
      }
    } );

    var mataCheckbox = function(event) {
      $(event.currentTarget).parent().remove();
    };

    cBtnCity.on("click", function() {
      var lbl = $("<label>", {text: cBusca.val()}).appendTo(cPnlCities);
      var checkbox = $("<input>", {type: "checkbox", checked: "checked", cid_id: cBusca.prop("cid_id"), class: "chk-cidade"}).appendTo(lbl);

      checkbox.on("click", mataCheckbox);
      cBusca.prop("cid_id", null);
      cBusca.val("");
      cBtnCity.prop("disabled", true);
    });

    cBtnFiltro.on("click", function() {
      configura_query();
      requisita_dados(1);
    });
  });
</script>
