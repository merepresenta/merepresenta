class RequestInformations
  constructor: (@viewObject, @quantidadePagina) ->
    @pQuery = null

  # Faz o download dos dados contidos em @pQquery
  downloadAllData: ->
    @viewObject.updateFormFieldsForDownload @pQuery
    jQuery('#download-files').submit()

  # Pesquisa apenas os dados do político, sem realizar a query novamente
  _queryPoliticiansInfo: (pageNumber, dados) ->
    @viewObject.startSearch()
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
          @viewObject.desenhaDadosFiltrados resultado, @downloadAllData.bind(this), @_queryPoliticiansInfo.bind this
          if  typeof(resultado.filter_data) != 'undefined'
            @viewObject.atualizaFiltros resultado.filter_data
            @viewObject.marcaFiltro resultado.query
            @pQuery = resultado.query;
        else
          @viewObject.desenhaDadosFiltradosVazio ""
        @viewObject.scrollToTop()
      ).bind(this)


#################################################################################################


viewObject = new ViewObject siteUrl
viewObject.criaFuncoesDesmarque()

requester = new RequestInformations(viewObject, 12)
jQuery('#bt_filtro').on 'click', ()->
  requester.queryNewInfo()

cBusca = jQuery("#filtro-cidade-escolha")
cBtnCity = jQuery "#btn-add-city"
cBtnFiltro = jQuery "#bt_filtro"
cPnlCities = jQuery "#cidades-escolhidas"
cBtnCity.prop "disabled", true

cBusca.autocomplete
  source: ( request, response ) ->
    dadosCidades = () ->
      dados = 
        nome: request.term

      dados.pautas = query.pautas.join(',') if (requester.pQuery && typeof(requester.pQuery.pautas) != 'undefined')
      dados

    jQuery.ajax 
      url: "/api/v1/cidades.php"
      method: "get"
      accept: "application/json"
      contentType: "application/json; charset=utf-8"
      dataType: "json"
      data: dadosCidades()
      success: ( data ) ->
        response( data.map((valor) ->
          label: "#{valor.nome_cidade}, #{valor.uf}"
          value: valor.id
        ))

  minLength: 2,
  focus: (event,ui) -> false
  change: (event, ui) ->
    if ui.item
      lbl = jQuery("<label>", {text: ui.item.label}).appendTo cPnlCities
      checkbox = jQuery("<input>", 
        type: "checkbox"
        checked: "checked"
        cid_id: ui.item.value
        class: "chk-cidade"
      ).appendTo lbl 
      checkbox.on "click", mataCheckbox
    cBusca.val ""
    false

  select: ( event, ui ) ->
    cBusca.val ui.item.label
    cBusca.prop "cid_id", ui.item.value
    cBtnCity.prop "disabled", false
    false

mataCheckbox = (event) ->
  jQuery(event.currentTarget).parent().remove()

cBtnCity.on "click", () ->
  lbl = jQuery("<label>", 
    text: cBusca.val()
  ).appendTo cPnlCities
  checkbox = jQuery("<input>", 
    type: "checkbox"
    checked: "checked"
    cid_id: cBusca.prop "cid_id"
    class: "chk-cidade"
  ).appendTo lbl
  checkbox.on "click", mataCheckbox
  cBusca.prop "cid_id", null
  cBusca.val ""
  cBtnCity.prop "disabled", true

