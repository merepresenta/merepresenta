atualizaChecksTelaEmail = () ->
  jQuery('.page-id-93 form label>input[type=checkbox]+input').prop 'disabled', 'true'
  jQuery('.page-id-93 form label>input[type=checkbox]:checked+input').prop 'disabled', ''

jQuery('.page-id-93 form label>input[type=checkbox]').on 'click', () -> atualizaChecksTelaEmail()

atualizaChecksTelaEmail()
