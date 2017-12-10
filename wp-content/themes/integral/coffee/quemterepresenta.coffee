desmarcaAlvo = (selecao, alvo) ->
  jQuery(selecao).on 'click', () ->
    if (jQuery("#{selecao}:checked").size() > 0)
      jQuery(alvo).attr('checked',false)
    else
      jQuery(alvo).attr('checked',true)
  
  jQuery(alvo).on 'click', () ->
    jQuery("#{selecao}:checked").attr 'checked', false if(jQuery(alvo).attr 'checked')

desmarcaAlvo ".chk-pauta", "#filtro_pauta .check-all"
desmarcaAlvo ".chk_estado", "#filtro_estado .check-all"
desmarcaAlvo ".chk-partido", "#filtro_partido .check-all"
desmarcaAlvo ".chk-genero", "#filtro_genero .check-all"
desmarcaAlvo ".chk-cor", "#filtro_cor .check-all"
