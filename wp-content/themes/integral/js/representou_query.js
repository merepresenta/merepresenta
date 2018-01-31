var ViewDadosFiltrados,ViewObject,ViewPaginacao;ViewPaginacao=function(){function e(){this.pPaginacao=jQuery("#paginacao")}return e.prototype.desenhaPainelPaginas=function(e,t,a){var i,o;return o=jQuery('<nav aria-label="Navegacion">'),0,i=jQuery("<ul>",{class:"pagination"}),Array.apply(null,{length:Math.ceil(e.length/t)}).map(Number.call,Number).forEach(function(t){var o,r,s;return s=t+1,r=jQuery("<li>"),(o=jQuery("<a>",{text:s,href:"#",class:"pagina-"+s})).on("click",function(){return a(s,e.slice(12*t,12*s))}),o.appendTo(r),r.appendTo(i)}),i.appendTo(o),this.pPaginacao.html(o)},e.prototype.marcaPagina=function(e){return jQuery(".pagination>li>a").removeClass("active"),jQuery(".pagina-"+e).addClass("active")},e.prototype.clear=function(){return this.pPaginacao.html("")},e}(),ViewDadosFiltrados=function(){function e(e,t){this.siteUrl=e,this.urlTema=t,this.pDadosFiltrados=jQuery("#dados_filtrados")}return e.prototype.clear=function(){return this.pDadosFiltrados.html("")},e.prototype.desenhaTabelaDados=function(e){var t,a;return a='<div class="row">',t=1,jQuery(e).each(function(e,i){var o,r;return o="",r="",null!=i.situacao_eleitoral&&(o="eleito por média"===i.situacao_eleitoral.toLowerCase()||"eleito por qp"===i.situacao_eleitoral.toLowerCase()?" eleito":"",r="eleito por média"===i.situacao_eleitoral.toLowerCase()||"eleito por qp"===i.situacao_eleitoral.toLowerCase()?"Eleito":i.situacao_eleitoral.toLowerCase()),a+='<div class="col-md-4">\n  <div class="panel panel-default'+o+'">\n    <a target="_blank" href="'+this.siteUrl+"/politicos/?cand_id="+i.id_candidatura+'">\n      <div class="panel-body">',i.fb_id?a+="<img src='//graph.facebook.com/v2.6/"+i.fb_id+"/picture?type=large' class='img-responsive img-rounded' alt='"+i.nome_candidato+"' title='"+i.nome_candidato+"'>":a+="<img src='"+this.urlTema+"/images/default-profile.jpg' class='img-responsive img-circle' alt='"+i.nome_candidato+"' title='"+i.nome_candidato+"'>",a+='      <h4 class="title-name-politic">'+i.nome_urna+'</h4>\n      <ul class="list-unstyled">\n        <li>'+i.sigla_partido.toUpperCase()+"</li>\n        <li><b>"+i.nome_cidade.toUpperCase()+"/"+i.sigla_estado.toUpperCase()+"</b></li>\n        <li><b>Votos:</b>"+i.votos_recebidos.toLowerCase()+'</li>\n        <li class="data_situacao_cadastral"><b>'+r+"</b></li>\n      </ul>\n    </div>\n  </a>\n</div>",a+=t%3==0?'</div></div><div class="row">':"</div>",t++}.bind(this)),a+="</div>",this.pDadosFiltrados.html(a)},e}(),ViewObject=function(){function e(e,t){this.siteUrl=e,this.urlTema=t,this.viewPaginacao=new ViewPaginacao,this.viewDadosFiltrados=new ViewDadosFiltrados(this.siteUrl,this.urlTema),this.pBody=jQuery("body"),this.cResultado=jQuery("#resultado"),this.pBotoes=jQuery("#botoes"),this.pUf=jQuery("#filtro_estado"),this.pPartidos=jQuery("#filtro_partido"),this.pGeneros=jQuery("#filtro_genero"),this.pCores=jQuery("#filtro_cor"),this.pSituacoes=jQuery("#filtro_sit_eleitoral"),this.pSpinner=jQuery("#spinner-home"),this.pPnlCities=jQuery("#cidades-escolhidas"),this.pBusca=jQuery("#filtro-cidade-escolha")}return e.prototype.desenhaDadosFiltradosVazio=function(){return this.pBody.addClass("resposta-vazia")},e.prototype._desenhaLinkDownload=function(e){var t;return(t=jQuery("<a>",{text:"download",href:"#",class:"btn btn-default"})).on("click",function(){return e()}),t},e.prototype.desenhaDadosFiltrados=function(e,t,a){return this.viewDadosFiltrados.desenhaTabelaDados(e.data),e.ids&&this.viewPaginacao.desenhaPainelPaginas(e.ids,Number(e.pagination),a),this.pBotoes.html(this._desenhaLinkDownload(t)),this.viewPaginacao.marcaPagina(1)},e.prototype.redrawPoliticians=function(e,t){return this.viewDadosFiltrados.desenhaTabelaDados(t),this.viewPaginacao.marcaPagina(e)},e.prototype._desmarcaAlvo=function(e,t){return jQuery(e).on("click",function(){return jQuery(e+":checked").size()>0?jQuery(t).attr("checked",!1):jQuery(t).attr("checked",!0)}),jQuery(t).on("click",function(){if(jQuery(t).attr("checked"))return jQuery(e+":checked").attr("checked",!1)})},e.prototype.criaFuncoesDesmarque=function(){return this._desmarcaAlvo(".chk-estado","#filtro_estado .check-all"),this._desmarcaAlvo(".chk-partido","#filtro_partido .check-all"),this._desmarcaAlvo(".chk-genero","#filtro_genero .check-all"),this._desmarcaAlvo(".chk-cor","#filtro_cor .check-all")},e.prototype._createHeader=function(e){return jQuery("<h3>",{text:e,class:"frm-label"})},e.prototype._atualizaFiltroEstados=function(e){var t,a,i,o;for(this.pUf.html(this._createHeader("Estados")),t=jQuery("<ul>",{class:"list-unstyled list-inline"}),i=0,o=e.length;i<o;i++)a=e[i],this.pUf.append(t.append(this._criaElemento("estado_"+a.sigla,"chk-estado",a.sigla,a.sigla)));return this.pUf.append(t.append(this._criaElemento("estado_todos","check-all",null,"Todos")))},e.prototype._atualizaFiltroPartidos=function(e){var t,a,i,o;for(this.pPartidos.html(this._createHeader("Partidos")),t=jQuery("<ul>",{class:"list-unstyled list-inline"}),i=0,o=e.length;i<o;i++)a=e[i],this.pPartidos.append(t.append(this._criaElemento("partido_"+a.sigla,"chk-partido",a.id,a.sigla)));return this.pPartidos.append(t.append(this._criaElemento("partido_todos","check-all",null,"Todos")))},e.prototype._criaElemento=function(e,t,a,i){var o,r;return o=jQuery("<li>"),(r=jQuery("<label>")).append(jQuery("<input>",{type:"checkbox",value:a,id:e,class:t})),r.append(jQuery("<span>",{text:i})),o.append(r),o},e.prototype._linkRacaGenero=function(e){return jQuery("<a>",{href:"/genero-e-raca/"}).append(e)},e.prototype._atualizaFiltroGeneros=function(e){var t,a,i,o,r;for(this.pGeneros.html(this._linkRacaGenero(this._createHeader("Gênero"))),t=jQuery("<ul>",{class:"list-unstyled list-inline"}),i=0,o=(r=e.filter(function(e){return""!==e.genero.trim()})).length;i<o;i++)a=r[i],this.pGeneros.append(t.append(this._criaElemento("genero_"+a.genero,"chk-genero",a.genero,a.genero)));return this.pGeneros.append(t.append(this._criaElemento("genero_todos","check-all",null,"Todos")))},e.prototype._atualizaFiltroRaca=function(e){var t,a,i,o,r;for(this.pCores.html(this._linkRacaGenero(this._createHeader("Raça"))),t=jQuery("<ul>",{class:"list-unstyled list-inline"}),i=0,o=(r=e.filter(function(e){return""!==e.cor_tse.trim()})).length;i<o;i++)a=r[i],this.pCores.append(t.append(this._criaElemento("cutis_"+a.cor_tse,"chk-cor",a.cor_tse,a.cor_tse)));return this.pCores.append(t.append(this._criaElemento("cutis_todos","check-all",null,"Todas")))},e.prototype._atualizaFiltroSituacoesEleitorais=function(e){var t,a,i,o,r,s,n,l;for(this.pSituacoes.html(this._createHeader("Situações Eleitorais")),a=jQuery("<ul>",{class:"list-unstyled list-inline"}),l=[],o=0,s=(n=e.filter(function(e){return null!==e.situacao_eleitoral&&""!==e.situacao_eleitoral.trim()})).length;o<s;o++)i=n[o],t=jQuery("<li>"),(r=jQuery("<label>")).append(jQuery("<input>",{type:"checkbox",value:i.situacao_eleitoral,id:"situacao_"+i.situacao_eleitoral.replace(/\s/g,"_"),class:"chk-sit-eleit"})),r.append(i.situacao_eleitoral),t.append(r),l.push(this.pSituacoes.append(a.append(t)));return l},e.prototype.atualizaFiltros=function(e){return this._atualizaFiltroEstados(e.estados),this._atualizaFiltroPartidos(e.partidos),this._atualizaFiltroGeneros(e.generos),this._atualizaFiltroRaca(e.cores),this._atualizaFiltroSituacoesEleitorais(e.situacoes_eleitorais),this.criaFuncoesDesmarque()},e.prototype._marcaEstados=function(e){if(null==e&&(e=[]),e.forEach(function(e){return jQuery("#estado_"+e).attr("checked","checked")}),0===e.length)return jQuery("#estado_todos").attr("checked","checked")},e.prototype._marcaPartidos=function(e){if(null==e&&(e=[]),e.forEach(function(e){return jQuery(".chk-partido[value='"+e+"']").attr("checked","checked")}),0===e.length)return jQuery("#partido_todos").attr("checked","checked")},e.prototype._marcaGenero=function(e){if(null==e&&(e=[]),e.forEach(function(e){return jQuery(".chk-genero[value="+e+"]").attr("checked","checked")}),0===e.length)return jQuery("#genero_todos").attr("checked","checked")},e.prototype._marcaRaca=function(e){if(null==e&&(e=[]),e.forEach(function(e){return jQuery("#cutis_"+e).attr("checked","checked")}),0===e.length)return jQuery("#cutis_todos").attr("checked","checked")},e.prototype._marcaSituacoesEleitorais=function(e){return null==e&&(e=[]),e.forEach(function(e){return jQuery("#situacao_"+e.replace(/\s/g,"_")).attr("checked","checked")})},e.prototype.marcaFiltro=function(e){return this._marcaEstados(e.sigla_estado),this._marcaPartidos(e.id_partido),this._marcaGenero(e.genero),this._marcaRaca(e.cor_tse),this._marcaSituacoesEleitorais(e.situacao_eleitoral),null},e.prototype.reformatScreen=function(){return jQuery("#filtros").removeClass("col-md-12").addClass("col-md-4"),this.cResultado.removeClass("col-md-12").addClass("col-md-8"),jQuery(".doble").children().removeClass("col-md-6").addClass("col-md-12"),this.pBody.addClass("resposta"),this.pBody.removeClass("resposta-vazia")},e.prototype.scrollToTop=function(){return jQuery("html, body").animate({scrollTop:this.cResultado.offset().top},1500)},e.prototype.updateFormFieldsForDownload=function(e){var t,a,i,o;t=jQuery("#download-files"),jQuery("#download-files input").remove(),i=[];for(a in e)o=e[a],i.push(jQuery("<input>",{type:"hidden",name:a,value:JSON.stringify(o)}).appendTo(t));return i},e.prototype.startSearch=function(e){return null==e&&(e=!0),this.pBody.removeClass("resposta-vazia"),this.viewDadosFiltrados.clear(),e&&this.viewPaginacao.clear(),this.pBotoes.html(""),this.pSpinner.removeClass("invisible")},e.prototype.completeSearch=function(){return this.pSpinner.addClass("invisible")},e.prototype._addSelectedCity=function(e,t){var a;return a=jQuery("<label>",{text:t}).appendTo(this.pPnlCities),jQuery("<input>",{type:"checkbox",checked:"checked",cid_id:e,class:"chk-cidade"}).appendTo(a).on("click",function(e){return jQuery(e.currentTarget).parent().remove()})},e.prototype._selecionaCidade=function(e,t){return t.item&&(this._addSelectedCity(t.item.value,t.item.label),this.pBusca.val(""),this.pBusca.focus()),!1},e.prototype.configuraAutoComplete=function(e){return this.pBusca.autocomplete({source:e,minLength:2,focus:function(e,t){return!1},change:this._selecionaCidade.bind(this),select:this._selecionaCidade.bind(this)})},e.prototype.configuraBotaoFiltro=function(e){return jQuery("#bt_filtro").on("click",e)},e}();