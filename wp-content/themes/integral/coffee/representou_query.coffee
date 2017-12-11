class ViewObject
  constructor: (@siteUrl) ->
    # Painel que conterá a tabela com dados filtrados
    @pDadosFiltrados = jQuery("#dados_filtrados")
    # Painel de dados de Estado
    @pUf = jQuery("#filtro_estado")
    # Painel de dados de partido
    @pPartidos = jQuery("#filtro_partido")
    # Painel com dados de gênero
    @pGeneros = jQuery("#filtro_genero")
    # Painel com dados de pigmentação da raça
    @pCores = jQuery("#filtro_cor")
    # Painel com dados de situações eleitorais
    @pSituacoes = jQuery("#filtro_sit_eleitoral")

  # Apresenta mensagem no painel de dados (no lugar da tabela de dados filtrados)
  # @param mensagem Mensagem a ser apresentada
  desenhaDadosFiltradosVazio: (mensagem) ->
    saida = jQuery "<h3>", {text: mensagem}
    @pDadosFiltrados.html saida

  # Desenha os dados na tabela
  # @param dados dados a serem apresentados
  # @return Painel contendo os dados
  _desenhaTabelaDados: (dados) ->
    data = '<div class="row">';
    cnt = 1;
    jQuery(dados).each (idx,r) ->
      type_css_eleito = ''
      type_txt_eleito = ''
      if r["situacao_eleitoral"]?
        type_css_eleito = if ((r["situacao_eleitoral"].toLowerCase() == 'eleito por média') || (r["situacao_eleitoral"].toLowerCase() == 'eleito por qp')) then ' eleito' else ''
        type_txt_eleito = if ((r["situacao_eleitoral"].toLowerCase() == 'eleito por média') || (r["situacao_eleitoral"].toLowerCase() == 'eleito por qp')) then 'Eleito' else r["situacao_eleitoral"].toLowerCase()

      data += """
        <div class="col-md-4">
          <div class="panel panel-default#{type_css_eleito}">
            <a target="_blank" href="#{siteUrl}/politicos/?cand_id=#{r['id_candidatura']}">
              <div class="panel-body">"""
      if(!!r["fb_id"])
        data += "<img src='//graph.facebook.com/v2.6/#{r["fb_id"]}/picture?type=large' class='img-responsive img-rounded' alt='#{r["nome_candidato"]}' title='#{r["nome_candidato"]}'>"
      else
        data += "<img src='/wp-content/themes/integral/images/default-profile.jpg' class='img-responsive img-circle' alt='#{r["nome_candidato"]}' title='#{r["nome_candidato"]}'>"
      data +=  """
                <h4 class="title-name-politic">#{r['nome_urna']}</h4>
                <ul class="list-unstyled">
                  <li>#{r['sigla_partido'].toUpperCase()}</li>
                  <li><b>#{r['nome_cidade'].toUpperCase()}/#{r['sigla_estado'].toUpperCase()}</b></li>
                  <li><b>Votos:</b>#{r['votos_recebidos'].toLowerCase()}</li>
                  <li class="data_situacao_cadastral"><b>#{type_txt_eleito}</b></li>
                </ul>
              </div>
            </a>
          </div>"""

      if(cnt % 3 == 0)
        data += """</div></div><div class="row">"""
      else
        data += '</div>'
      cnt++;
    data += '</div>'


  # Desenha os links de paginação (Painel de dados)
  # @param pagination Dados de paginação
  # @return Painel contendo os links
  _desenhaPainelPaginas: (pagination) ->
    painel = jQuery '<nav aria-label="Navegacion">'
    paginaAtual = (pagination.first / pagination.quantity)+1
    page_list_ul = jQuery('<ul>', {class:"pagination"})

    Array.apply(null, {length: Math.ceil(pagination.count / pagination.quantity)})
      .map(Number.call, Number).
      forEach (rec) ->
        pagina = rec + 1
        le_class =  if pagina == paginaAtual then "active" else ""
        page_list_li = jQuery('<li>',{class: le_class})
        if pagina == paginaAtual
          c = jQuery "<a>", {text: pagina}
        else
          c = jQuery "<a>", {text: pagina, href: '#'}
          c.on "click", () ->
            requisitaDados pagina
        c.appendTo page_list_li
        page_list_li.appendTo page_list_ul
    page_list_ul.appendTo painel
    painel


  # Desenha o link de download
  # @return Painel contendo o link
  _desenhaLinkDownload: () ->
    lnkDownload = jQuery("<a>", {text: "download", href: "#", class:"btn btn-default"})
    lnkDownload.on "click", downloadAllData


  # Desenha no painel de dados os dados de tabela, links de paginação e link de download.
  # @param resultado Resultado retornado na pesquisa
  # @return Painel contendo o link
  desenhaDadosFiltrados: (resultado) ->
    @pDadosFiltrados.html this._desenhaTabelaDados(resultado.data)
    @pDadosFiltrados.append this._desenhaPainelPaginas(resultado.pagination)
    @pDadosFiltrados.append this._desenhaLinkDownload()


  _desmarcaAlvo: (selecao, alvo) ->
    jQuery(selecao).on 'click', () ->
      if (jQuery("#{selecao}:checked").size() > 0)
        jQuery(alvo).attr('checked',false)
      else
        jQuery(alvo).attr('checked',true)
    
    jQuery(alvo).on 'click', () ->
      jQuery("#{selecao}:checked").attr 'checked', false if(jQuery(alvo).attr 'checked')


  criaFuncoesDesmarque: () ->
    this._desmarcaAlvo ".chk-pauta", "#filtro_pauta .check-all"
    this._desmarcaAlvo ".chk-estado", "#filtro_estado .check-all"
    this._desmarcaAlvo ".chk-partido", "#filtro_partido .check-all"
    this._desmarcaAlvo ".chk-genero", "#filtro_genero .check-all"
    this._desmarcaAlvo ".chk-cor", "#filtro_cor .check-all"


  # Cria header
  _createHeader: (title)->
    jQuery "<h3>", { text:title, class: 'frm-label' }

  # Redesenha o filtro de estados
  # @param estados Dados de estado
  _atualizaFiltroEstados: (estados) ->
    @pUf.html this._createHeader("Estados")
    container_ul = jQuery '<ul>',{class:"list-unstyled list-inline"}
    for elemento in estados
      @pUf.append container_ul.append(this._criaElemento  "estado_"+elemento.sigla, 'chk-estado', elemento.sigla, elemento.sigla)
    @pUf.append container_ul.append(this._criaElemento  "estado_todos", 'check-all', null, "Todos")

  # Redesenha o filtro de partidos
  # @param partidos Dados de partido
  _atualizaFiltroPartidos: (partidos) ->
    @pPartidos.html this._createHeader("Partidos")
    container_ul = jQuery '<ul>',{class:"list-unstyled list-inline"}
    for elemento in partidos
      @pPartidos.append container_ul.append(this._criaElemento "partido_"+elemento.sigla, 'chk-partido', elemento.id, elemento.sigla)
    @pPartidos.append container_ul.append(this._criaElemento "partido_todos", 'check-all', null, 'Todos')


  # Cria os elementos que seguem o padrão
  # @param chkId id do checkbox
  # @param chkClass class do checkbox
  # @param chkValue Valor do checkbox
  # @param chkLabel Label do checkbox
  _criaElemento: (chkId, chkClass, chkValue, chkLabel) ->
    baseLI = jQuery "<li>"
    lbl = jQuery "<label>"
    lbl.append jQuery("<input>", { type: "checkbox", value: chkValue, id: chkId, class: chkClass } )
    lbl.append jQuery("<span>", { text: chkLabel } )
    baseLI.append lbl
    baseLI

  # Cria link tanto para página que descreve raça e gênero
  # @param titulo Texto que aparecerá no link
  _linkRacaGenero: (titulo) ->
    link = jQuery "<a>", { href: '/genero-e-raca/' }
    link.append titulo 

  # Redesenha o filtro de generos
  # @param generos Dados de genero
  _atualizaFiltroGeneros: (generos) ->
    @pGeneros.html this._linkRacaGenero(this._createHeader("Gênero"))
    container_ul = jQuery '<ul>',{class:"list-unstyled list-inline"}
    for elemento in (generos.filter (c) -> c.genero.trim() != '')
      @pGeneros.append container_ul.append(this._criaElemento "genero_"+elemento.genero, 'chk-genero', elemento.genero, elemento.genero)
    @pGeneros.append container_ul.append(this._criaElemento "genero_todos", 'check-all', null, "Todos")

  # Redesenha o filtro de pigmentação da raça
  # @param cores Dados de pigmentação da raça
  _atualizaFiltroRaca: (cores) ->
    @pCores.html this._linkRacaGenero(this._createHeader("Raça"))
    container_ul = jQuery '<ul>',{class:"list-unstyled list-inline"}
    for elemento in cores.filter((c) -> c.cor_tse.trim() != '')
      @pCores.append container_ul.append(this._criaElemento "cutis_"+elemento.cor_tse, 'chk-cor', elemento.cor_tse, elemento.cor_tse)
    @pCores.append container_ul.append(this._criaElemento "cutis_todos", 'check-all', null, "Todas")

  # Redesenha o filtro de situações eleitorais
  # @param situacoes Dados de situações eleitorais
  _atualizaFiltroSituacoesEleitorais: (situacoes) ->
    @pSituacoes.html this._createHeader("Situações Eleitorais")
    container_ul = jQuery '<ul>',{class:"list-unstyled list-inline"}
    for elemento in situacoes.filter((c) -> c.situacao_eleitoral != null && c.situacao_eleitoral.trim() != '')
      container_li = jQuery "<li>"
      lbl = jQuery "<label>"
      lbl.append jQuery("<input>", {type:"checkbox", value: elemento.situacao_eleitoral, id: "situacao_"+elemento.situacao_eleitoral.replace(/\s/g, '_'), class: 'chk-sit-eleit' } )
      lbl.append elemento.situacao_eleitoral
      container_li.append lbl
      @pSituacoes.append container_ul.append(container_li)

  # Atualiza os filtros de acordo com os dados passados
  # @param filtros Dados dos filtros a serem apresentados
  atualizaFiltros: (filtros) ->
    this._atualizaFiltroEstados(filtros.estados);
    this._atualizaFiltroPartidos(filtros.partidos);
    this._atualizaFiltroGeneros(filtros.generos);
    this._atualizaFiltroRaca(filtros.cores);
    this._atualizaFiltroSituacoesEleitorais(filtros.situacoes_eleitorais);
    this.criaFuncoesDesmarque()


   # Marca os checkboxes correspondentes as siglas passadas
   # @param sigla_estados lista de siglas
  _marcaEstados: (sigla_estados = []) ->
    sigla_estados.forEach (estado) ->
      jQuery("#estado_#{estado}").attr 'checked', 'checked'
    jQuery('#estado_todos').attr 'checked', 'checked' if sigla_estados.length == 0

  # Marca os checkboxes correspondentes aos partidos passados
  # @param id_partidos lista de partidos
  _marcaPartidos: (id_partidos = []) ->
    id_partidos.forEach (id) ->
      jQuery(".chk-partido[value='#{id}']").attr 'checked', 'checked'
    jQuery('#partido_todos').attr 'checked', 'checked' if id_partidos.length == 0

  # Marca os itens correspondentes aos generos passados
  # @param generos generos
  _marcaGenero: (generos = []) ->
    generos.forEach (genero) ->
      jQuery(".chk-genero[value=#{genero}]").attr 'checked', 'checked'
    jQuery('#genero_todos').attr 'checked', 'checked' if generos.length == 0

  # Marca os checkboxes correspondentes às cores passadas
  # @param cores_tse lista de cores
  _marcaRaca: (cores_tse = []) ->
    cores_tse.forEach (cutis) ->
      jQuery('#cutis_' + cutis).attr 'checked', 'checked'
    jQuery('#cutis_todos').attr 'checked', 'checked' if cores_tse.length == 0

  # Marca os checkboxes correspondentes às situacoes eleitorais passadas
  # @param situacoes_eleitorais lista de situacoes eleitorais
  _marcaSituacoesEleitorais: (situacoes_eleitorais = []) ->
    situacoes_eleitorais.forEach (situacao_eleitoral) ->
      jQuery("#situacao_#{situacao_eleitoral.replace(/\s/g, '_')}").attr 'checked', 'checked'

  # Marca as opções de acordo com o demonstrado na query realizada
  # @param query Query usada para fazer a requição, contém os dados a serem marcados
  marcaFiltro: ( query ) ->
    this._marcaEstados(query.sigla_estado)
    this._marcaPartidos(query.id_partido)
    this._marcaGenero(query.genero)
    this._marcaRaca(query.cor_tse)
    this._marcaSituacoesEleitorais(query.situacao_eleitoral)
    null
