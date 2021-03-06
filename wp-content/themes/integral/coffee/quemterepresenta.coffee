class RequestInformations
  constructor: (@viewObject, @quantidadePagina) ->
    @pQuery = null

  # Faz o download dos dados contidos em @pQquery
  downloadAllData: ->
    @viewObject.updateFormFieldsForDownload @pQuery
    jQuery('#download-files').submit()

  # Pesquisa apenas os dados do político, sem realizar a query novamente
  _queryPoliticiansInfo: (pageNumber, dados) ->
    @viewObject.startSearch(false)
    jQuery.ajax
      url: "/api/v1/politicos.php"
      data: JSON.stringify
        ids_politicos: dados
        pagina: pageNumber
      dataType: "json"
      contentType: "application/json; charset=utf-8"
      type: "post"
      complete: (() -> @viewObject.completeSearch()).bind(this)
      success: ((resultado) ->
        @viewObject.redrawPoliticians resultado.pagina, resultado.dados if resultado.dados.length > 0
      ).bind(this)


  # Preenche o parâmetro pQuery
  _createRequestQuery: ->
    @pQuery = { }
    estados = jQuery(".chk-estado:checked").map((i,obj) -> obj.value ).toArray()
    @pQuery.sigla_estado = estados if  estados.length > 0

    cidades = jQuery(".chk-cidade:checked").map((i,obj) -> jQuery(obj).attr "cid_id" ).toArray()
    @pQuery.id_cidade = cidades if  cidades.length > 0

    partidos = jQuery(".chk-partido:checked").map((i,obj) -> obj.value).toArray()
    @pQuery.id_partido = partidos if partidos.length > 0

    pautas = jQuery(".chk-pauta:checked").map((i,obj) -> obj.value).toArray()
    @pQuery.pautas = pautas if pautas.length > 0

    genero = jQuery(".chk-genero:checked").map((i,obj) -> obj.value).toArray()
    @pQuery.genero = genero if genero.length > 0

    cores = jQuery(".chk-cor:checked").map((i,obj) -> obj.value).toArray()
    @pQuery.cor_tse = cores if cores.length > 0

    situacoesEleitorais = jQuery(".chk-sit-eleit:checked").map((i,obj) -> obj.value).toArray()
    @pQuery.situacao_eleitoral = situacoesEleitorais if situacoesEleitorais.length > 0


  # Busca Inicial dos dados
  queryNewInfo: ->
    @_createRequestQuery()
    @viewObject.startSearch()
    jQuery.ajax
      url: "/api/v1/indices.php"
      data: JSON.stringify
        query: @pQuery
        limites:
          quantidade: @quantidadePagina
      dataType: "json"
      contentType: "application/json; charset=utf-8"
      type: "post"
      complete: (() -> @viewObject.completeSearch()).bind(this)
      success: ((resultado) ->
        @viewObject.reformatScreen()

        if resultado.data and resultado.data.length > 0
          @viewObject.desenhaDadosFiltrados(resultado, @downloadAllData.bind(this), @_queryPoliticiansInfo.bind this)
          if  typeof(resultado.filter_data) != 'undefined'
            @viewObject.atualizaFiltros resultado.filter_data
            @viewObject.marcaFiltro resultado.query
            @pQuery = resultado.query;
        else
          @viewObject.desenhaDadosFiltradosVazio ""
        @viewObject.scrollToTop()
      ).bind(this)


  buscaDadosCidade: (termo, sucesso) ->
    dadosCidades = () ->
      dados = 
        nome: termo

      dados.pautas = @pQuery.pautas.join(',') if (@pQuery && typeof(@pQuery.pautas) != 'undefined')
      dados

    jQuery.ajax 
      url: "/api/v1/cidades.php"
      method: "get"
      accept: "application/json"
      contentType: "application/json; charset=utf-8"
      dataType: "json"
      data: dadosCidades()
      success: sucesso

#################################################################################################

viewObject = new ViewObject siteUrl, temaUrl
requester = new RequestInformations(viewObject, 12)

viewObject.criaFuncoesDesmarque()
viewObject.configuraAutoComplete ( request, response ) ->
  requester.buscaDadosCidade request.term, ( data ) ->
    response( data.map((valor) ->
      label: "#{valor.nome_cidade}, #{valor.uf}"
      value: valor.id
    ))
viewObject.configuraBotaoFiltro ()->
  requester.queryNewInfo()

