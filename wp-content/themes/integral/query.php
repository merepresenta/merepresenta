<?php
require_once("ambiente.php");
$ambiente = new Ambiente();
$queryRunner = $ambiente->queryRunner();
$ufs = $queryRunner->get_results("select sigla from Estado order by sigla");
$partidos = $queryRunner->get_results("select id, sigla from Partido order by sigla");
$pautas = $queryRunner->get_results("select id, texto from Pergunta order by id");
$generos = $queryRunner->get_results("select distinct genero_tse from Pessoa where genero_tse <> '' order by genero_tse");
$cores = $queryRunner->get_results("select distinct cor_tse from Pessoa where cor_tse <> '' order by cor_tse");
$situacoesEleitorais = $queryRunner->get_results("select distinct situacao_eleitoral from Candidatura where situacao_eleitoral <> '' order by situacao_eleitoral");
?>
<form id="download-files" action="<?= get_template_directory_uri() ?>/download.php" method="post" ></form>
<main>
<div id="spinner-home" class="invisible">
  <div class="box">
    <div id="spinner"></div>
    <span>Carregando...</span>
  </div>
</div>
<div class="container">
  <h1 class="page-header">Representou</h1>
  <p> Escolha as pautas importantes para você ou para sua entidade e clique em "Filtrar" para mostrar candidatas e
  candidatos de 2016 que responderam Sim para essas questões!</p>
  <div class="row">
    <div id="filtros" class="col-md-12">
      <div id="dados_menu">
        <div class="row doble">
          <div class="col-md-6">
            <h3>Pautas</h3>
            <ul class="list-unstyled">
            <?php
            foreach (array_slice($pautas, 0, 7) as $pauta) {
            ?>
              <li><label><input type="checkbox" value="<?= $pauta->id ?>" id="pauta_<?= $pauta->id ?>" class="chk-pauta"> <?= $pauta->texto ?></label></li>
            <?php
            }
            ?>
            </ul>
          </div>
          <div class="col-md-6">
            <ul class="list-unstyled">
            <?php
            foreach (array_slice($pautas, 8, 14) as $pauta) {
            ?>
              <li><label><input type="checkbox" value="<?= $pauta->id ?>" id="pauta_<?= $pauta->id ?>" class="chk-pauta"> <?= $pauta->texto ?></label></li>
            <?php
            }
            ?>
            </ul>
          </div>
        </div>
        <div class="row doble">
          <div class="col-md-6" id="filtro_estado">
            <h3>Estado</h3>
            <ul class="list-unstyled list-inline">
              <?php foreach ($ufs as $estado) { ?>
              <li>
                <label>
                  <input type="checkbox" value="<?= $estado->sigla ?>" id="estado_<?= $estado->sigla ?>" class="chk_estado"> <?= $estado->sigla ?>
                </label>
              </li>
              <?php } ?>
            </ul>
          </div>
          <div class="col-md-6" id="filtro_cidade">
            <h3>Cidade</h3>
            <div class="input-group">
              <input type="text" name="cidade" id="filtro-cidade-escolha" class="form-control" placeholder="Ej: Recife">
              <span class="input-group-btn">
                <button id="btn-add-city" class="btn" type="button">+</button>
              </span>
            </div>
            <div id="cidades-escolhidas"></div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12" id="filtro_partido">
            <h3>Partidos</h3>
            <ul class="list-unstyled list-inline">
              <?php foreach ($partidos as $partido) { ?>
              <li>
                <label>
                  <input type="checkbox" value="<?= $partido->id ?>" id="partido_<?= $partido->sigla ?>" class="chk-partido"> <?= $partido->sigla ?>
                </label>
              </li>
              <?php } ?>
            </ul>
          </div>
        </div>
        <div class="row doble">
          <div class="col-md-6" id="filtro_genero">
            <h3><a href="/genero-e-raca/">Gêneros</a></h3>
            <ul class="list-unstyled list-inline">
              <?php foreach ($generos as $genero) { ?>
                <li><label><input type="checkbox" value="<?= $genero->genero_tse ?>" id="genero_<?= $genero->genero_tse ?>" class="chk-genero"> <?= $genero->genero_tse ?></label></li>
              <?php } ?>
            </ul>
          </div>
          <div class="col-md-6" id="filtro_cor">
            <h3><a href="/genero-e-raca/">Raça</a></h3>
            <ul class="list-unstyled list-inline">
              <?php foreach ($cores as $cor) { ?>
              <li>
                <label>
                  <input type="checkbox" value="<?= $cor->cor_tse ?>" id="cutis_<?= $cor->cor_tse ?>" class="chk-cor"> <?= $cor->cor_tse ?>
                </label>
              </li>
              <?php } ?>
            </ul>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <button id="bt_filtro" class="btn btn-primary btn-lg btn-block" role="button">Filtro</button>
          </div>
        </div>
      </div>
    </div>
    <div id="resultado" class="col-md-12">
      <div id="dados_filtrados">
        <p>Seus resultados vão aparecer aqui...</p>
      </div>
    </div>
  </div>
</div>
</main>

<script type='text/javascript' src="<?= get_template_directory_uri() ?>/js/representou_query.js"></script>
<script>
var siteUrl = "<?= site_url() ?>";

jQuery(function ($) {
  var checkList = $('.dropdown-check-list');
  checkList.on('click', 'span.anchor', function(event){
    var element = $(this).parent();
    if ( element.hasClass('visible') ) {
      element.removeClass('visible');
    } else {
      element.addClass('visible');
    }
  });
});

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
        jQuery("#filtros").removeClass("col-md-12").addClass("col-md-4");
        jQuery("#resultado").removeClass("col-md-12").addClass("col-md-8");
        jQuery(".doble").children().removeClass("col-md-6").addClass("col-md-12");
        viewObject.desenhaDadosFiltrados(resultado);
        if (typeof(resultado.filter_data) != 'undefined') {
          viewObject.atualizaFiltros(resultado.filter_data);
          viewObject.marcaFiltro(resultado.query);
          query = resultado.query;
        }
      } else {
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

  var genero = jQuery(".chk-genero:checked").map(function(i,obj){return obj.value}).toArray();
  if (genero.length>0) query.genero = genero;

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
      jQuery.ajax({
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
          }));//data.map
        }
      });//jQuery.ajax
    },//source
    minLength: 2,
    focus: function(event,ui){
      return false;
    },
    change: function(event, ui){
      return false;
    },
    select: function( event, ui ){
      cBusca.val(ui.item.label);
      cBusca.prop("cid_id", ui.item.value);
      cBtnCity.prop("disabled", false);
      return false;
    }
  });

  var mataCheckbox = function(event){
    jQuery(event.currentTarget).parent().remove();
  };

  cBtnCity.on("click", function(){
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
