/* Object de view */
function ViewObject(siteUrl) {
  var classe = { };

  /* Painel que conterá a tabela com dados filtrados */
  var pDadosFiltrados = jQuery("#dados_filtrados");
  /* Painel de dados de Estado */
  var pUf = jQuery("#filtro_uf");
  /* Painel de dados de partido */
  var pPartidos = jQuery("#filtro_partido");
  /* Painel com dados de gênero */
  var pGeneros = jQuery("#filtro_genero");
  /* Painel com dados de pigmentação da cútis */
  var pCores = jQuery("#filtro_cor");

  /** 
   * Apresenta mensagem no painel de dados (no lugar da tabela de dados filtrados) 
   * @param mensagem Mensagem a ser apresentada
   */
  classe.desenhaDadosFiltradosVazio = function(mensagem) {
    saida = jQuery("<h3>", {text: mensagem});
    pDadosFiltrados.html(saida);
  };

  /** 
   * Desenha os dados na tabela
   * @param dados dados a serem apresentados
   * @return Painel contendo os dados
   */
  classe._desenhaTabelaDados = function(dados){
    var saida = null;
    var keys = Object.keys(dados[0]);

    saida = jQuery("<table>", {class: "tabela-dados table table-striped"});
    var h = jQuery("<thead>").appendTo(saida);
    var tr = jQuery("<tr>").appendTo(h);
    jQuery("<th>", {text: 'Link'}).appendTo(tr);          
    jQuery(keys).each(function(idx, value) {
      if( value != "id_candidatura")
        jQuery("<th>", {text: value}).appendTo(tr);          
    })

    var tbody = jQuery("<tbody>").appendTo(saida);
    jQuery(dados).each(function(idx,r) {
      var linha = jQuery("<tr>").appendTo(tbody);

      var col = jQuery("<td>").appendTo(linha);
      jQuery("<a>",{text: 'visite', href: siteUrl + '/politicos/?cand_id='+r['id_candidatura']}).appendTo(col);

      jQuery(keys).each(function(idx, value) {
        if( value != "id_candidatura")
          jQuery("<td>", {text: r[value]}).appendTo(linha);          
      });
    });

    return saida;
  }


  /** 
   * Desenha os links de paginação (Painel de dados)
   * @param pagination Dados de paginação
   * @return Painel contendo os links
   */
  classe._desenhaPainelPaginas = function(pagination) {
    var painel = jQuery("<div>"),
        paginaAtual = (pagination.first / pagination.quantity)+1;

    Array.apply(null, {length: Math.ceil(pagination.count / pagination.quantity)}).
      map(Number.call, Number).
      forEach(function(rec){
        var pagina = rec + 1;
        if (pagina == paginaAtual)
          c = jQuery("<div>", {text: pagina, class: 'lnk-paginacao'});
        else {
          c = jQuery("<a>", {text: pagina, href: '#', class: 'lnk-paginacao'});
          c.on("click", function(){
            requisitaDados(pagina);
          });
        }

        c.appendTo(painel);
      });    
    return painel;
  }

  /** 
   * Desenha o link de download
   * @return Painel contendo o link
   */
  classe._desenhaLinkDownload = function() {
    var lnkDownload = jQuery("<a>", {text: "download", href: "#"});
    lnkDownload.on("click", downloadAllData);    
    return lnkDownload;
  };

  /** 
   * Desenha no painel de dados os dados de tabela, links de paginação e link de download.
   * @param resultado Resultado retornado na pesquisa
   * @return Painel contendo o link
   */
  classe.desenhaDadosFiltrados = function(resultado) {
    pDadosFiltrados.html(this._desenhaTabelaDados(resultado.data));
    pDadosFiltrados.append(this._desenhaPainelPaginas(resultado.pagination));
    pDadosFiltrados.append(this._desenhaLinkDownload());
  };
  

  classe._createHeader = function(title) {
    return jQuery("<h3>", { text:title } );
  };

  /**
   * Redesenha o filtro de estados
   * @param estados Dados de estado
   */
  classe._atualizaFiltroEstados = function(estados) {
    pUf.html(this._createHeader("Estados"));
    estados.forEach(function(elemento) {
      var lbl = jQuery("<label>");
      lbl.append( jQuery("<input>", {type:"checkbox", value: elemento.sigla, id: "estado_"+elemento.sigla, class: 'chk_estado' } ) );
      lbl.append(elemento.sigla);
      pUf.append(lbl);
    });
  };

  /**
   * Redesenha o filtro de partidos
   * @param estados Dados de partido
   */
  classe._atualizaFiltroPartidos = function (partidos) {
    pPartidos.html(this._createHeader("Partidos"));
    partidos.forEach(function(elemento) {
      var lbl = jQuery("<label>");
      lbl.append( jQuery("<input>", {type:"checkbox", value: elemento.id, id: "partido_"+elemento.sigla, class: 'chk-partido' } ) );
      lbl.append(elemento.sigla);
      pPartidos.append(lbl);
    });
  };

  /**
   * Redesenha o filtro de generos
   * @param estados Dados de genero
   */
  classe._atualizaFiltroGeneros = function (generos) {
    pGeneros.html(this._createHeader("Gêneros"));

    if (generos.filter(function(g) { return g.genero.trim() == ''}).length == 0)
      generos.splice(0,0,{genero: ''});
    var sel = jQuery('<select>', {class: 'sel-genero', id:'sel_genero'});
    pGeneros.append(sel);
    generos.forEach(function(elemento){
      sel.append(jQuery("<option>", {text: elemento.genero, value: elemento.genero}));
    });
  };

  /**
   * Redesenha o filtro de pigmentação da cútis
   * @param estados Dados de pigmentação da cútis
   */
  classe._atualizaFiltroCores = function (cores) {
    pCores.html(this._createHeader("Cútis"));
    cores.filter(function(c){ return c.cor_tse.trim() != '' }).forEach(function(elemento) {
      var lbl = jQuery("<label>");
      lbl.append( jQuery("<input>", {type:"checkbox", value: elemento.cor_tse, id: "cutis_"+elemento.cor_tse, class: 'chk-cor' } ) );
      lbl.append(elemento.cor_tse);
      pCores.append(lbl);
    });
  };

  /**
   * Atualiza os filtros de acordo com os dados passados
   * @param filtros Dados dos filtros a serem apresentados
   */
  classe.atualizaFiltros = function(filtros) {
    this._atualizaFiltroEstados(filtros.estados);
    this._atualizaFiltroPartidos(filtros.partidos);
    this._atualizaFiltroGeneros(filtros.generos);
    this._atualizaFiltroCores(filtros.cores);
  }

  classe._marcaEstados = function(sigla_estados) {
    sigla_estados.forEach(function(estado) {
      jQuery('#estado_' + estado).attr('checked','checked');
    });
  }

  classe._marcaPartidos = function(id_partidos) {
    id_partidos.forEach(function(id) {
      jQuery('.chk-partido[value='+id+']').attr('checked','checked');
    });
  }

  classe._marcaGenero = function(genero) {
    jQuery('.sel-genero > option[value="' + genero+'"]').attr('selected','selected');
  }

  classe._marcaCutis = function(cores_tse) {
    cores_tse.forEach(function(cutis) {
      jQuery('#cutis_' + cutis).attr('checked','checked');
    });
  }

  classe.marcaFiltro = function( query ) {
    if (typeof(query.sigla_estado) != 'undefined')
      this._marcaEstados(query.sigla_estado);
    if (typeof(query.id_partido) != 'undefined')
      this._marcaPartidos(query.id_partido);
    if (typeof(query.genero) != 'undefined')
      this._marcaGenero(query.genero);
    if (typeof(query.cor_tse) != 'undefined')
      this._marcaCutis(query.cor_tse);
  }
  return classe;
}