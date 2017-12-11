class ViewObject
  constructor: (@siteUrl) ->
    # Painel que conterá a tabela com dados filtrados
    @pDadosFiltrados = jQuery("#dados_filtrados")
    # Painel de dados de Estado
    @pUf = jQuery("#filtro_uf")
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
      data += '<div class="col-md-4">';
      type_css_eleito = ((r["situacao_eleitoral"].toLowerCase() == 'eleito por média') || (r["situacao_eleitoral"].toLowerCase() == 'eleito por qp')) ? ' eleito' : ''
      type_txt_eleito = ((r["situacao_eleitoral"].toLowerCase() == 'eleito por média') || (r["situacao_eleitoral"].toLowerCase() == 'eleito por qp')) ? 'Eleito' : r["situacao_eleitoral"].toLowerCase()
      data += "<div class='panel panel-default#{type_css_eleito}'>"
      data += "<a target='_blank' href='#{siteUrl}/politicos/?cand_id=#{r['id_candidatura']}'>"
      data += '<div class="panel-body">';
      if(!!r["fb_id"])
        data += "<img src='//graph.facebook.com/v2.6/#{r["fb_id"]}/picture?type=large' class='img-responsive img-rounded' alt='#{r["nome_candidato"]}' title='#{r["nome_candidato"]}'>"
      else
        data += "<img src='/wp-content/themes/integral/images/default-profile.jpg' class='img-responsive img-circle' alt='#{r["nome_candidato"]}' title='#{r["nome_candidato"]}'>"
      data +=  """<h4 class="title-name-politic">#{r['nome_urna']}</h4>
                  <ul class="list-unstyled">
                    <li>#{r['sigla_partido'].toUpperCase()}</li>
                    <li><b>#{r['nome_cidade'].toUpperCase()}/#{r['sigla_estado'].toUpperCase()}</b></li>
                    <li><b>Votos:</b>#{r['votos_recebidos'].toLowerCase()}</li>
                    <li><b>Situacao eleitoral:</b>#{type_txt_eleito}</li>
                  </ul>
                </div>
              </a>
            </div>"""

      if(cnt % 3 == 0)
        data += """</div></div><div class="row">"""
      else
        data += '</div>'
      cnt++;
    data += '</div>';


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


  # Cria header
  _createHeader: (title)->
    jQuery "<h3>", { text:title }

  # Redesenha o filtro de estados
  # @param estados Dados de estado
  _atualizaFiltroEstados: (estados) ->
    pUf.html this._createHeader("Estados")
    estados.forEach (elemento) ->
      lbl = jQuery "<label>"
      lbl.append jQuery("<input>", {type:"checkbox", value: elemento.sigla, id: "estado_"+elemento.sigla, class: 'chk_estado' } )
      lbl.append elemento.sigla
      @pUf.append lbl

  # Redesenha o filtro de partidos
  # @param partidos Dados de partido
  _atualizaFiltroPartidos: (partidos) ->
    @pPartidos.html this._createHeader("Partidos")
    container_ul = jQuery '<ul>',{class:"list-unstyled list-inline"}
    partidos.forEach (elemento) ->
      container_li = jQuery "<li>"
      lbl = jQuery "<label>"
      lbl.append( jQuery "<input>", {type:"checkbox", value: elemento.id, id: "partido_"+elemento.sigla, class: 'chk-partido' } )
      lbl.append(jQuery "<span>", {text:elemento.sigla})
      container_li.append lbl
      @pPartidos.append container_ul.append(container_li)

  # Cria link tanto para página que descreve raça e gênero
  # @param titulo Texto que aparecerá no link
  _linkRacaGenero: (titulo) ->
    link = jQuery "<a>", { href: '/genero-e-raca/' }
    link.append titulo 

  # Redesenha o filtro de generos
  # @param generos Dados de genero
  _atualizaFiltroGeneros: (generos) ->
    @pGeneros.html this._linkRacaGenero(this._createHeader("Gênero*"))
    container_ul = jQuery '<ul>',{class:"list-unstyled list-inline"}

    generos.filter (c) ->
      c.genero.trim() != ''
    .forEach (elemento) ->
      container_li = jQuery "<li>"
    lbl = jQuery "<label>"
    lbl.append  jQuery("<input>", {type:"checkbox", value: elemento.genero, id: "genero_"+elemento.genero, class: 'chk-genero' } )
    lbl.append jQuery("<span>", {text:elemento.genero})
    container_li.append lbl
    @pGeneros.append container_ul.append(container_li)

  # Redesenha o filtro de pigmentação da raça
  # @param cores Dados de pigmentação da raça
  _atualizaFiltroCores: (cores) ->
    @pCores.html this._linkRacaGenero(this._createHeader("Raça*"))
    container_ul = jQuery '<ul>',{class:"list-unstyled list-inline"}
    cores.filter (c) ->
      c.cor_tse.trim() != ''
    .forEach (elemento) ->
      container_li = jQuery "<li>"
      lbl = jQuery "<label>"
      lbl.append jQuery("<input>", {type:"checkbox", value: elemento.cor_tse, id: "cutis_"+elemento.cor_tse, class: 'chk-cor' } )
      lbl.append jQuery("<span>", {text:elemento.cor_tse})
      container_li.append lbl 
      @pCores.append container_ul.append(container_li)

  # Redesenha o filtro de situações eleitorais
  # @param situacoes Dados de situações eleitorais
  _atualizaFiltroSituacoesEleitorais: (situacoes) ->
    @pSituacoes.html this._createHeader("Situações Eleitorais")
    container_ul = jQuery '<ul>',{class:"list-unstyled list-inline"}
    situacoes.filter (c) ->
      c.situacao_eleitoral != null && c.situacao_eleitoral.trim() != '' 
    .forEach (elemento) ->
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
    this._atualizaFiltroCores(filtros.cores);
    this._atualizaFiltroSituacoesEleitorais(filtros.situacoes_eleitorais);

   # Marca os checkboxes correspondentes as siglas passadas
   # @param sigla_estados lista de siglas
  _marcaEstados: (sigla_estados) ->
    sigla_estados.forEach (estado) ->
      jQuery("#estado_#{estado}").attr 'checked', 'checked'

  # Marca os checkboxes correspondentes aos partidos passados
  # @param id_partidos lista de partidos
  _marcaPartidos: (id_partidos) ->
    id_partidos.forEach (id) ->
      jQuery(".chk-partido[value='#{id}']").attr 'checked', 'checked'

  # Marca os itens correspondentes aos generos passados
  # @param generos generos
  _marcaGenero: (generos) ->
    generos.forEach (genero) ->
      jQuery(".chk-genero[value=#{genero}]").attr 'checked', 'checked'

  # Marca os checkboxes correspondentes às cores passadas
  # @param cores_tse lista de cores
  _marcaCutis: (cores_tse) ->
    cores_tse.forEach (cutis) ->
      jQuery('#cutis_' + cutis).attr 'checked', 'checked'

  # Marca os checkboxes correspondentes às situacoes eleitorais passadas
  # @param situacoes_eleitorais lista de situacoes eleitorais
  _marcaSituacoesEleitorais: (situacoes_eleitorais) ->
    situacoes_eleitorais.forEach (situacao_eleitoral) ->
      jQuery("#situacao_#{situacao_eleitoral.replace(/\s/g, '_')}").attr 'checked', 'checked'

  # Marca as opções de acordo com o demonstrado na query realizada
  # @param query Query usada para fazer a requição, contém os dados a serem marcados
  marcaFiltro: ( query ) ->
    if (typeof(query.sigla_estado) != 'undefined')
      this._marcaEstados(query.sigla_estado)
    if (typeof(query.id_partido) != 'undefined')
      this._marcaPartidos(query.id_partido)
    if (typeof(query.genero) != 'undefined')
      this._marcaGenero(query.genero)
    if (typeof(query.cor_tse) != 'undefined')
      this._marcaCutis(query.cor_tse)
    if (typeof(query.situacao_eleitoral) != 'undefined')
      this._marcaSituacoesEleitorais(query.situacao_eleitoral)
    classe
