
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
  /* Painel com dados de pigmentação da raça */
  var pCores = jQuery("#filtro_cor");
  /* Painel com dados de situações eleitorais */
  var pSituacoes = jQuery("#filtro_sit_eleitoral");


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
    var data = '';
    var cnt = 1;
      data = '<div class="row">';
    jQuery(dados).each(function(idx,r) {
      data += '<div class="col-md-4">';
      var type_css_eleito = ((r["situacao_eleitoral"].toLowerCase() === 'eleito por média') || (r["situacao_eleitoral"].toLowerCase() === 'eleito por qp')) ? ' eleito' : '';
      var type_txt_eleito = ((r["situacao_eleitoral"].toLowerCase() === 'eleito por média') || (r["situacao_eleitoral"].toLowerCase() === 'eleito por qp')) ? 'Eleito' : r["situacao_eleitoral"].toLowerCase();
      data += '<div class="panel panel-default'+ type_css_eleito +'">';
      data += '<div class="panel-body">';
          if(!!r["fb_id"])
            data += '<img src="//graph.facebook.com/v2.6/'+r["fb_id"]+'/picture?type=large" class="img-responsive img-rounded" alt="'+r["nome_candidato"]+'" title="'+r["nome_candidato"]+'">';
          else
            data += '<img src="/wp-content/themes/integral/images/default-profile.jpg" class="img-responsive img-circle" alt="'+r["nome_candidato"]+'" title="'+r["nome_candidato"]+'">';
          data += '<h4 class="title-name-politic"><a href="' + siteUrl + '/politicos/?cand_id='+r["id_candidatura"]+'">'+r["nome_candidato"]+'</a></h4>';
          data += '<ul class="list-unstyled">';
          data += '<li><b>Raça:</b> '+r["cor_tse"].toLowerCase()+'</li>';
          data += '<li><b>Genero:</b> '+r["genero"].toLowerCase()+'</li>';
          data += '<li><b>Sigla Estado:</b> '+r["sigla_estado"].toLowerCase()+'</li>';
          data += '<li><b>Cidade:</b> '+r["nome_cidade"].toUpperCase()+'</li>';
          data += '<li><b>Sigla partido:</b> '+r["sigla_partido"].toUpperCase()+'</li>';
          data += '<li><b>Votos recebidos:</b> '+r["votos_recebidos"].toLowerCase()+'</li>';
          data += '<li><b>Situacao candidatura:</b> '+r["situacao_candidatura"].toLowerCase()+'</li>';
          data += '<li><b>Situacao eleitoral:</b> '+type_txt_eleito+'</li>';
          data += '</ul>';
      //data += '</div>';
      //data += '</div>';
      data += '</div>';
      data += '</div>';

      if(cnt % 3 === 0)
        data += '</div></div><div class="row">';
      else
        data += '</div>';
      cnt++;
    });
    data += '</div>';

    return data;
  }

  /**
   * Desenha os links de paginação (Painel de dados)
   * @param pagination Dados de paginação
   * @return Painel contendo os links
   */
  classe._desenhaPainelPaginas = function(pagination) {
    var painel = jQuery('<nav aria-label="Navegacion">'),
        paginaAtual = (pagination.first / pagination.quantity)+1,
        page_list_ul = jQuery('<ul>', {class:"pagination"});

    Array.apply(null, {length: Math.ceil(pagination.count / pagination.quantity)}).
      map(Number.call, Number).
      forEach(function(rec){
        var pagina = rec + 1;
        var le_class = (pagina == paginaAtual) ? "active" : "";
        var page_list_li = jQuery('<li>',{class: le_class});
        if (pagina == paginaAtual)
          c = jQuery("<a>", {text: pagina});
        else {
          c = jQuery("<a>", {text: pagina, href: '#'});
          c.on("click", function(){
            requisitaDados(pagina);
          });
        }
        c.appendTo(page_list_li);
        page_list_li.appendTo(page_list_ul);
      });

    page_list_ul.appendTo(painel);

    return painel;
  }

  /**
   * Desenha o link de download
   * @return Painel contendo o link
   */
  classe._desenhaLinkDownload = function() {
    var lnkDownload = jQuery("<a>", {text: "download", href: "#", class:"btn btn-default"});
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
   * @param partidos Dados de partido
   */
  classe._atualizaFiltroPartidos = function (partidos) {
    pPartidos.html(this._createHeader("Partidos"));
    container_ul = jQuery('<ul>',{class:"list-unstyled list-inline"});
    partidos.forEach(function(elemento) {
      var container_li = jQuery("<li>");
      var lbl = jQuery("<label>");
      lbl.append( jQuery("<input>", {type:"checkbox", value: elemento.id, id: "partido_"+elemento.sigla, class: 'chk-partido' } ) );
      lbl.append(elemento.sigla);
      container_li.append(lbl);
      pPartidos.append(container_ul.append(container_li));
    });
  };

  classe._linkRacaGenero = function (titulo) {
    var link = jQuery("<a>", { href: '/genero-e-raca/' });
    link.append(titulo);
    return link;
  }

  /**
   * Redesenha o filtro de generos
   * @param generos Dados de genero
   */
  classe._atualizaFiltroGeneros = function(generos) {
    pGeneros.html(this._linkRacaGenero(this._createHeader("Gêneros")));

    container_ul = jQuery('<ul>',{class:"list-unstyled list-inline"});

    generos.forEach(function(elemento) {
      var container_li = jQuery("<li>");
      var lbl = jQuery("<label>");
      lbl.append( jQuery("<input>", {type:"checkbox", value: elemento.genero, id: "genero_"+elemento.genero, class: 'chk-genero' } ) );
      lbl.append(elemento.genero);
      container_li.append(lbl);
      pGeneros.append(container_ul.append(container_li));
    });

  };

  /**
   * Redesenha o filtro de pigmentação da raça
   * @param cores Dados de pigmentação da raça
   */
  classe._atualizaFiltroCores = function(cores) {
    pCores.html(this._linkRacaGenero(this._createHeader("Raça")));
    container_ul = jQuery('<ul>',{class:"list-unstyled list-inline"});
    cores.filter(function(c){ return c.cor_tse.trim() != '' }).forEach(function(elemento) {
      var container_li = jQuery("<li>");
      var lbl = jQuery("<label>");
      lbl.append( jQuery("<input>", {type:"checkbox", value: elemento.cor_tse, id: "cutis_"+elemento.cor_tse, class: 'chk-cor' } ) );
      lbl.append(elemento.cor_tse);
      container_li.append(lbl);
      pCores.append(container_ul.append(container_li));
    });
  };

  /**
   * Redesenha o filtro de situações eleitorais
   * @param situacoes Dados de situações eleitorais
   */
  classe._atualizaFiltroSituacoesEleitorais = function(situacoes) {
    pSituacoes.html(this._createHeader("Situações Eleitorais"));
    container_ul = jQuery('<ul>',{class:"list-unstyled list-inline"});
    situacoes.filter(function(c){ return c.situacao_eleitoral != null && c.situacao_eleitoral.trim() != '' }).forEach(function(elemento) {
      var container_li = jQuery("<li>");
      var lbl = jQuery("<label>");
      lbl.append( jQuery("<input>", {type:"checkbox", value: elemento.situacao_eleitoral, id: "situacao_"+elemento.situacao_eleitoral.replace(/\s/g, '_'), class: 'chk-sit-eleit' } ) );
      lbl.append(elemento.situacao_eleitoral);
      container_li.append(lbl);
      pSituacoes.append(container_ul.append(container_li));
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
    this._atualizaFiltroSituacoesEleitorais(filtros.situacoes_eleitorais);
  }

  /**
   * Marca os checkboxes correspondentes as siglas passadas
   * @param sigla_estados lista de siglas
   */
  classe._marcaEstados = function(sigla_estados) {
    sigla_estados.forEach(function(estado) {
      jQuery('#estado_' + estado).attr('checked','checked');
    });
  }

  /**
   * Marca os checkboxes correspondentes aos partidos passados
   * @param id_partidos lista de partidos
   */
  classe._marcaPartidos = function(id_partidos) {
    id_partidos.forEach(function(id) {
      jQuery('.chk-partido[value='+id+']').attr('checked','checked');
    });
  }

  /**
   * Marca os itens correspondentes aos generos passados
   * @param generos generos
   */
  classe._marcaGenero = function(generos) {
    generos.forEach(function(genero) {
      jQuery('.chk-genero[value='+genero+']').attr('checked','checked');
    });
  }

  /**
   * Marca os checkboxes correspondentes às cores passadas
   * @param cores_tse lista de cores
   */
  classe._marcaCutis = function(cores_tse) {
    cores_tse.forEach(function(cutis) {
      jQuery('#cutis_' + cutis).attr('checked','checked');
    });
  }

  /**
   * Marca os checkboxes correspondentes às situacoes eleitorais passadas
   * @param situacoes_eleitorais lista de situacoes eleitorais
   */
  classe._marcaSituacoesEleitorais = function(situacoes_eleitorais) {
    situacoes_eleitorais.forEach(function(situacao_eleitoral) {
      jQuery('#situacao_' + situacao_eleitoral.replace(/\s/g, '_')).attr('checked','checked');
    });
  }

  /**
   * Marca as opções de acordo com o demonstrado na query realizada
   * @param query Query usada para fazer a requição, contém os dados a serem marcados
   */
  classe.marcaFiltro = function( query ) {
    if (typeof(query.sigla_estado) != 'undefined')
      this._marcaEstados(query.sigla_estado);
    if (typeof(query.id_partido) != 'undefined')
      this._marcaPartidos(query.id_partido);
    if (typeof(query.genero) != 'undefined')
      this._marcaGenero(query.genero);
    if (typeof(query.cor_tse) != 'undefined')
      this._marcaCutis(query.cor_tse);
    if (typeof(query.situacao_eleitoral) != 'undefined')
      this._marcaSituacoesEleitorais(query.situacao_eleitoral);
  }
  return classe;
}
