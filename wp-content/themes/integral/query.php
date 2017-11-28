<?php
  require_once("ambiente.php");
  $ambiente = new Ambiente();

  $queryRunner = $ambiente->queryRunner();

  $ufs = $queryRunner->get_results("select sigla from Estado order by sigla");
  $partidos = $queryRunner->get_results("select id, sigla from Partido order by sigla");
  $pautas = $queryRunner->get_results("select id, texto from Pergunta order by id");
  $generos = $queryRunner->get_results("select distinct genero_tse from Pessoa order by genero_tse");
  $cores = $queryRunner->get_results("select distinct cor_tse from Pessoa where cor_tse <> '' order by cor_tse");
  $situacoesEleitorais = $queryRunner->get_results("select distinct situacao_eleitoral from Candidatura where situacao_eleitoral <> '' order by situacao_eleitoral");
?>

<form id="download-files" action="<?= get_template_directory_uri() ?>/download.php" method="post" >
</form>

<main>
  <div id="spinner-home"  class="invisible">
    <div class="box">
      <div id="spinner"></div>
      <span>Carregando...</span>
    </div>

  </div>
  <div class="container">
    <h1 class="page-header">Representou</h1>
    <p> Escolha as pautas importantes para você ou para sua entidade e clique em "Filtrar" para mostrar candidatas e
    candidatos de 2016 que responderam Sim para essas questões!</p>
    <div id="dados_menu" class="row">
      <div class="col-md-4">
        <h3>Pautas</h3>
        <select name="filtro_pautas" id="filtro_pautas">
          <?php foreach ($pautas as $pauta) { ?>
            <option value="<?= $pauta->id ?>"><?= $pauta->texto ?></option>
          <?php } ?>
        </select>

        <p>Se você quiser, use os filtros abaixo para selecionar candidatas e candidatos mais especificamente...</p>

        <!-- -->
        <ul class="nav nav-tabs" role="tablist">
          <li role="presentation" class="active"><a href="#filtro_estado" aria-controls="filtro_estado" role="tab" data-toggle="tab">Estados</a></li>
          <li role="presentation"><a href="#filtro_cidade" aria-controls="filtro_cidade" role="tab" data-toggle="tab">Cidades</a></li>
          <li role="presentation"><a href="#filtro_partido" aria-controls="filtro_partido" role="tab" data-toggle="tab">Partidos</a></li>
          <li role="presentation"><a href="#filtro_genero" aria-controls="filtro_genero" role="tab" data-toggle="tab">Gêneros</a></li>
          <li role="presentation"><a href="#filtro_cor" aria-controls="filtro_cor" role="tab" data-toggle="tab">Cútis</a></li>
          <li role="presentation"><a href="#filtro_sit_eleitoral" aria-controls="filtro_sit_eleitoral" role="tab" data-toggle="tab">Situação Eleitoral</a></li>
        </ul>
        <div class="tab-content">
          <div role="tabpanel" class="tab-pane active" id="filtro_estado">
            <h3>Estados</h3>
            <select name="filtro_uf" id="filtro_uf">
            <?php foreach ($ufs as $estado) { ?>
              <option value="<?= $estado->sigla ?>"><?= $estado->sigla ?></option>
            <?php } ?>
            </select>
          </div>
          <div role="tabpanel" class="tab-pane" id="filtro_cidade">
            <h3>Cidades</h3>
            <input type="text" name="cidade" id="filtro-cidade-escolha" />
            <button id="btn-add-city" class="btn">+</button>
            <div id="cidades-escolhidas"></div>
          </div>
          <div role="tabpanel" class="tab-pane" id="filtro_partido">
            <h3>Partidos</h3>
            <ul class="list-unstyled">
            <?php foreach ($partidos as $partido) { ?>
              <li><input type="checkbox" id="partido_<?= $partido->id ?>" value="<?= $partido->id ?>"><?= $partido->sigla ?></li>
            <?php } ?>
            </ul>
          </div>
          <div role="tabpanel" class="tab-pane" id="filtro_genero">
            <h3>Gêneros</h3>
            <ul id="sel_genero" class="sel-genero">
              <?php foreach ($generos as $genero) { ?>
                <li><input type="checkbox" id="<?= $genero->genero_tse ?>" value="<?= $genero->genero_tse ?>"><?= $genero->genero_tse ?></li>
              <?php } ?>
            </ul>
          </div>
          <div role="tabpanel" class="tab-pane" id="filtro_cor">
            <h3>Cútis</h3>
            <ul class="list-unstyled">
            <?php foreach ($cores as $cor) { ?>
              <li>
                <input type="checkbox" value="<?= $cor->cor_tse ?>" id="cutis_<?= $cor->cor_tse ?>" class="chk-cor"> <?= $cor->cor_tse ?>
              </li>
            <?php } ?>
            </ul>
          </div>
          <div role="tabpanel" class="tab-pane" id="filtro_sit_eleitoral">
            <h3>Situação Eleitoral</h3>
            <ul class="list-unstyled">
            <?php foreach ($situacoesEleitorais as $sit) { ?>
              <li>
                <input type="checkbox" value="<?= $sit->situacao_eleitoral ?>" id="situacao_<?= str_replace(' ', '_', $sit->situacao_eleitoral) ?>" class="chk-sit-eleit"> <?= $sit->situacao_eleitoral ?>
              </li>
            <?php } ?>
            </ul>
          </div>
        </div>
        <!-- -->
        <button id="bt_filtro" class="btn btn-default" role="button">Filtro</button>
      </div>
      <div class="col-md-8">
        <div id="dados_filtrados">
            Seus resultados vão aparecer aqui...
        </div>
      </div>
    </div>
  </div>
</main>

<script type="text/javascript">
  var siteUrl = "<?= site_url() ?>";
</script>

<script type='text/javascript' src="<?= get_template_directory_uri() ?>/js/representou_query.js"></script>

<script type="text/javascript">
  var query = null;
  var quantidade_pagina = 10;
  var spinner = jQuery("#spinner-home");
  var necessitaRevisaoFiltros = false;
  var viewObject = ViewObject(siteUrl);

  var downloadAllData = function() {
    var frm = jQuery("#download-files");
    jQuery("#download-files input").remove();
    Object.keys(query).forEach(function(field,y) {
      var o = jQuery("<input>", {type: "hidden", name: field,value: JSON.stringify(query[field])}).appendTo(frm);
    });
    frm.submit();
  };

  var requisitaDados = function(inicial) {
    spinner.removeClass("invisible");
    jQuery.ajax({
      url: "/api/v1/merepresenta.php",
      data: JSON.stringify({
        query: query,
        limites: {primeiro: (inicial - 1) * quantidade_pagina , quantidade: quantidade_pagina},
        revisaoFiltros: (inicial > 1) ? false : necessitaRevisaoFiltros
      }),
      dataType: "json",
      contentType: "application/json; charset=utf-8",
      type: "post",
      complete: function() {
        spinner.addClass("invisible");
      },
      success: function(resultado) {
        if(resultado.data.length > 0) {
          viewObject.desenhaDadosFiltrados(resultado);
          if (typeof(resultado.filter_data) != 'undefined') {
            viewObject.atualizaFiltros(resultado.filter_data);
            viewObject.marcaFiltro(resultado.query);
            query = resultado.query;
          }
        }
        else {
          viewObject.desenhaDadosFiltradosVazio("Sem dados ligados à requisição");
        }
      }
    });
  }

  var configuraQuery = function() {
    var oldPautas = ((! query)||(typeof(query.pautas) == "undefined")) ? [] : query.pautas;
    query =  { };

    var estados = jQuery(".chk_estado:checked").map(function(i,obj){return obj.value}).toArray();
    if (estados.length>0) query.sigla_estado = estados;

    var cidades = jQuery(".chk-cidade:checked").map(function(i,obj){return jQuery(obj).attr("cid_id")}).toArray();
    if (cidades.length>0) query.id_cidade = cidades;

    var partidos = jQuery(".chk-partido:checked").map(function(i,obj){return obj.value}).toArray();
    if (partidos.length>0) query.id_partido = partidos;

    var pautas = jQuery(".chk-pauta:checked").map(function(i,obj){return obj.value}).toArray();
    if (pautas.length>0) query.pautas = pautas;
    necessitaRevisaoFiltros = !(typeof(oldPautas) == typeof(query.pautas) && oldPautas.length==query.pautas.length && oldPautas.every(function(v,i) { return v === query.pautas[i]}));

    var genero = jQuery("#sel_genero").val();
    if (genero!='') query.genero = genero;

    var cores = jQuery(".chk-cor:checked").map(function(i,obj){return obj.value}).toArray();
    if (cores.length>0) query.cor_tse = cores;

    var situacoesEleitorais = jQuery(".chk-sit-eleit:checked").map(function(i,obj){return obj.value}).toArray();
    if (situacoesEleitorais.length>0) query.situacao_eleitoral = situacoesEleitorais;
  };

  jQuery(window).on("load",function(){
    var cBusca = jQuery("#filtro-cidade-escolha");
    var cBtnCity = jQuery("#btn-add-city");
    var cBtnFiltro = jQuery("#bt_filtro");
    var cPnlCities = jQuery("#cidades-escolhidas");

    cBtnCity.prop("disabled", true);
    cBusca.autocomplete({
      source: function( request, response ) {
        function dadosCidades() {
          var dados = { nome: request.term };
          if (query && typeof(query.pautas) != 'undefined')
            dados.pautas = query.pautas.join(',');
          return dados;
        }

        jQuery.ajax( {
          url: "/api/v1/cidades.php",
          method: "get",
          accept: "application/json",
          contentType: "application/json; charset=utf-8",
          dataType: "json",
          data: dadosCidades(),
          success: function( data ) {
            response( data.map(function(valor){
              return {
                label: valor.nome_cidade + ', ' + valor.uf,
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
      jQuery(event.currentTarget).parent().remove();
    };

    cBtnCity.on("click", function() {
      var lbl = jQuery("<label>", {text: cBusca.val()}).appendTo(cPnlCities);
      var checkbox = jQuery("<input>", {type: "checkbox", checked: "checked", cid_id: cBusca.prop("cid_id"), class: "chk-cidade"}).appendTo(lbl);

      checkbox.on("click", mataCheckbox);
      cBusca.prop("cid_id", null);
      cBusca.val("");
      cBtnCity.prop("disabled", true);
    });

    cBtnFiltro.on("click", function() {
      configuraQuery();
      requisitaDados(1);
    });
  });
</script>
