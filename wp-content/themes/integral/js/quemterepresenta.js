var desmarcaAlvo;(desmarcaAlvo=function(c,e){return jQuery(c).on("click",function(){return jQuery(c+":checked").size()>0?jQuery(e).attr("checked",!1):jQuery(e).attr("checked",!0)}),jQuery(e).on("click",function(){if(jQuery(e).attr("checked"))return jQuery(c+":checked").attr("checked",!1)})})(".chk-pauta","#filtro_pauta .check-all"),desmarcaAlvo(".chk_estado","#filtro_estado .check-all"),desmarcaAlvo(".chk-partido","#filtro_partido .check-all"),desmarcaAlvo(".chk-genero","#filtro_genero .check-all"),desmarcaAlvo(".chk-cor","#filtro_cor .check-all");