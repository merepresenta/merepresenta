<?php
  global $wpdb;

  
  $ufs = $wpdb->get_results("select sigla from Estado order by sigla");
  $partidos = $wpdb->get_results("select id, sigla from Partido order by sigla");
  $pautas = $wpdb->get_results("select id, texto from Pergunta order by id");
  $generos = $wpdb->get_results("select distinct genero_tse from Pessoa order by genero_tse");
  $cores = $wpdb->get_results("select distinct cor_tse from Pessoa order by cor_tse");
?>
<div class="container">
  <div id="dados_menu">
    <div id="filtro_uf">
      <h3>Estado:</h3>
      <?php foreach ($ufs as $estado) { ?>
        <label>
          <input type="checkbox" value="<?= $estado->sigla ?>" id="estado_<?= $estado->sigla ?>" class="chk-estado"> <?= $estado->sigla ?>
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
          <input type="checkbox" value="<?= $partido->id ?>" id="partido_<?= $partido->sigla ?>"> <?= $partido->sigla ?>
        </label>
      <?php } ?>
    </div>
    <div id="filtro_pautas">
      <h3>Pautas</h3>
      <?php foreach ($pautas as $pauta) { ?>
        <label>
          <input type="checkbox" value="<?= $pauta->id ?>" id="pauta_<?= $pauta->id ?>"> <?= $pauta->texto ?>
        </label>
      <?php } ?>
    </div>
    <div id="filtro_genero">
      <h3>Genero</h3>
      <select name="" id="">
        <?php foreach ($generos as $genero) { ?>
          <label>
            <option value="<?= $genero->genero_tse ?>"><?= $genero->genero_tse ?></option>
          </label>
        <?php } ?>
      </select>
    </div>
    <div id="filtro_cor">
      <h3>Cor</h3>
      <select name="" id="">
        <?php foreach ($cores as $cor) { ?>
          <option value="<?= $cor->cor_tse ?>"><?= $cor->cor_tse ?></option>
        <?php } ?>        
      </select>
    </div>
    <div id="filtro_nota_partido">
      <h3>Nota do Partido</h3>
      <label for="nota_partido_inicio">Nota mínima</label>
      <input type="text" id="nota_partido_inicio" />
      <label for="nota_partido_fim">Nota máxima</label>
      <input type="text" id="nota_partido_fim" />
    </div>
    <button id="bt_filtro">Filtro</button>
  </div>

  <div id="dados_filtrados" style="overflow-x: scroll; width: 50%">

  </div>
</div>

<script>
  $(window).on("load",function(){
    var cBusca = $("#filtro-cidade-escolha");
    var cBtnCity = $("#btn-add-city");
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
      var checkbox = $("<input>", {type: "checkbox", checked: "checked", cid_id: cBusca.prop("cid_id")}).appendTo(lbl);

      checkbox.on("click", mataCheckbox);
      cBusca.prop("cid_id", null);
      cBusca.val("");
      cBtnCity.prop("disabled", true);


    });
  });
</script>
