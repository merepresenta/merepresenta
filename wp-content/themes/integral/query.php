<?php
require_once("ambiente.php");
$ambiente = new Ambiente();
$queryRunner = $ambiente->queryRunner();
$ufs = $queryRunner->get_results("select sigla from Estado order by sigla");
$partidos = $queryRunner->get_results("select id, sigla from Partido order by sigla");
$pautas = $queryRunner->get_results("select id, texto_pesquisa from Pergunta order by id");
$generos = $queryRunner->get_results("select distinct genero_tse from Pessoa where genero_tse <> '' order by genero_tse");
$cores = $queryRunner->get_results("select distinct cor_tse from Pessoa where cor_tse <> '' order by cor_tse");
$situacoesEleitorais = $queryRunner->get_results("select distinct situacao_eleitoral from Candidatura where situacao_eleitoral <> '' order by situacao_eleitoral");
?>
<div class="spacer"></div>
<form id="download-files" action="<?= get_template_directory_uri() ?>/download.php" method="post" ></form>
<main>
<div id="spinner-home" class="invisible">
  <div class="box">
    <div id="spinner"></div>
    <span>Carregando...</span>
  </div>
</div>
<div class="container corpo pesquisa">
  <h2 class="entry-title"><?php the_title(); ?></h2>
  <p class="pesquisa">Escolha pautas importantes para você ou sua entidade e clique em <span class="enfase">pesquisar</span> para mostrar candidatas e candidatos de 2016 que disseram apoiar essas questões.</p>
  <p class="resposta">Clicando nos links das candidaturas você vê como a pessoa se posicionou em nossa plataforma. Você também pode filtrar esses resultados por pauta, partido, estado, cidade, gênero ou raça.</p>
  <div class="row">
    <div id="filtros" class="col-md-12">
      <div id="dados_menu">
        <div id="filtro_pauta" class="row doble">
          <h3 class="col-md-12 frm-label">Pautas</h3>
          <div class="col-md-6">
            <ul class="list-unstyled">
            <?php
            foreach (array_slice($pautas, 0, 7) as $pauta) {
            ?>
              <li><label><input type="checkbox" value="<?= $pauta->id ?>" id="pauta_<?= $pauta->id ?>" class="chk-pauta"><span><?= $pauta->texto_pesquisa ?></span></label></li>
            <?php
            }
            ?>
            </ul>
          </div>
          <div class="col-md-6">
            <ul class="list-unstyled">
            <?php
            foreach (array_slice($pautas, 7, 14) as $pauta) {
            ?>
              <li><label><input type="checkbox" value="<?= $pauta->id ?>" id="pauta_<?= $pauta->id ?>" class="chk-pauta"><span><?= $pauta->texto_pesquisa ?></span></label></li>
            <?php
            }
            ?>
            </ul>
          </div>
        </div>
        <div id="filtro_estado">
          <h3 class="frm-label">Estado</h3>
          <ul class="list-unstyled list-inline">
            <?php foreach ($ufs as $estado) { ?>
            <li>
              <label>
                <input type="checkbox" value="<?= $estado->sigla ?>" id="estado_<?= $estado->sigla ?>" class="chk-estado">
                <span><?= $estado->sigla ?></span>
              </label>
            </li>
            <?php } ?>
            <li>
              <label>
                <input type="checkbox" class="check-all" checked><span>Todos</span>
              </label>
            </li>
          </ul>
        </div>
        <div id="filtro_cidade">
          <label for="filtro-cidade-escolha" class="frm-label">Cidade (opcional)</label >
          <input type="text" name="cidade" id="filtro-cidade-escolha" class="form-control" placeholder="Ex: Recife">
          <div id="cidades-escolhidas"></div>
        </div>
        <div class="row">
          <div class="col-md-12" id="filtro_partido">
            <h3 class="frm-label">Partidos</h3>
            <ul class="list-unstyled list-inline">
              <?php foreach ($partidos as $partido) { ?>
              <li>
                <label>
                  <input type="checkbox" value="<?= $partido->id ?>" id="partido_<?= $partido->sigla ?>" class="chk-partido"><span><?= $partido->sigla ?></span>
                </label>
              </li>
              <?php } ?>
              <li>
                <label>
                  <input type="checkbox" class="check-all" checked><span>Todos</span>
                </label>
              </li>
            </ul>
          </div>
        </div>
        <div class="row doble">
          <div class="col-md-6" id="filtro_genero">
            <h3 class="frm-label"><a href="/genero-e-raca/">Gênero</a></h3>
            <ul class="list-unstyled list-inline">
              <?php foreach ($generos as $genero) { ?>
                <li><label><input type="checkbox" value="<?= $genero->genero_tse ?>" id="genero_<?= $genero->genero_tse ?>" class="chk-genero"><span><?= $genero->genero_tse ?></span></label></li>
              <?php } ?>
              <li>
                <label>
                  <input type="checkbox" class="check-all" checked><span>Todos</span>
                </label>
              </li>
            </ul>
          </div>
          <div class="col-md-6" id="filtro_cor">
            <h3 class="frm-label"><a href="/genero-e-raca/">Raça</a></h3>
            <ul class="list-unstyled list-inline">
              <?php foreach ($cores as $cor) { ?>
              <li>
                <label>
                  <input type="checkbox" value="<?= $cor->cor_tse ?>" id="cutis_<?= $cor->cor_tse ?>" class="chk-cor"><span><?= $cor->cor_tse ?></span>
                </label>
              </li>
              <?php } ?>
              <li>
                <label>
                  <input type="checkbox" class="check-all" checked><span>Todas</span>
                </label>
              </li>
            </ul>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12 div-botao">
            <button id="bt_filtro" class="btn btn-lg btn-block" role="button">Pesquisar</button>
          </div>
        </div>
      </div>
    </div>
    <div id="resultado" class="col-md-12">
      <div id="dados">
        <div id="dados_filtrados"></div>
        <div id="paginacao"></div>
        <div id="botoes"></div>
      </div>
      <div class="resposta-em-branco">
        <img src="<?= get_template_directory_uri() ?>/images/sadface.svg" alt="Carinha triste">
        <p class="alerta"><strong>São 890 perfis cadastrados em 24 estados e 244 cidades. Infelizmente, não temos nenhum com os critérios selecionados. Tente outras combinações ou contribua para o #MeRepresenta crescer na sua região!</strong></p>
        <a href="/construa-com-a-gente" class="btn">Construa com a Gente</a>
      </div>
    </div>
  </div>
</div>
</main>

<script>
  var siteUrl = "<?= site_url() ?>";
  var temaUrl = "<?= get_template_directory_uri() ?>";
</script>

<script type='text/javascript' src="<?= get_template_directory_uri() ?>/js/representou_query.js"></script>
<script type='text/javascript' src="<?= get_template_directory_uri() ?>/js/quemterepresenta.js"></script>
