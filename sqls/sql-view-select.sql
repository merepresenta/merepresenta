create or replace view all_data
as
select 
    convert(c.nome_urna USING utf8) as nome_urna
    , convert(c.numero_candidato USING utf8) as numero_candidato
    , convert(c.situacao_candidatura USING utf8) as situacao_candidatura
    , convert(c.votacao USING utf8) as votacao
    , convert(c.situacao_eleitoral USING utf8) as situacao_eleitoral
    , convert(c.sequencial_candidato USING utf8) as sequencial_candidato
    , convert(p.nome USING utf8) as nome
    , convert(p.minibio USING utf8) as minibio
    , convert(p.genero_tse USING utf8) as genero_tse
    , convert(p.genero_autodeclarado USING utf8) as genero_autodeclarado
    , convert(p.email USING utf8) as email
    , convert(p.data_nascimento USING utf8) as data_nascimento
    , convert(p.cor_tse USING utf8) as cor_tse
    , convert(e.ano  USING utf8) as ano_eleicao
    , convert(e.unidade_eleitoral_type USING utf8) as unidade_eleitoral_type
    , convert(par.nome  USING utf8) as nome_partido
    , convert(par.sigla  USING utf8) as sigla_partido
    , convert(par.numero  USING utf8) as numero_partido
    , convert(par.nota  USING utf8) as nota_partido
    , convert(cid.codigo_tse  USING utf8) as codigo_tse_cidade
    , convert(cid.nome  USING utf8) as nome_cidade
    , convert(est.nome  USING utf8) as nome_estado
    , convert(est.sigla  USING utf8) as sigla_estado
    , convert(( 
      select 
        coli.nome 
        from merepresenta.Coligacao_Partido CP 
                inner join merepresenta.Coligacao coli
                on 
          (CP.coligacao_id = coli.id)
        where   coli.unidade_eleitoral_id = c.unidade_eleitoral_id and
            CP.partido_id = c.partido_id 
   ) USING utf8) as nome_coligacao
    , (select r.resposta from Resposta r where pergunta_id = 1 and r.pessoa_id = c.pessoa_id) as resposta_1
    , (select r.resposta from Resposta r where pergunta_id = 2 and r.pessoa_id = c.pessoa_id) as resposta_2
    , (select r.resposta from Resposta r where pergunta_id = 3 and r.pessoa_id = c.pessoa_id) as resposta_3
    , (select r.resposta from Resposta r where pergunta_id = 4 and r.pessoa_id = c.pessoa_id) as resposta_4
    , (select r.resposta from Resposta r where pergunta_id = 5 and r.pessoa_id = c.pessoa_id) as resposta_5
    , (select r.resposta from Resposta r where pergunta_id = 6 and r.pessoa_id = c.pessoa_id) as resposta_6
    , (select r.resposta from Resposta r where pergunta_id = 7 and r.pessoa_id = c.pessoa_id) as resposta_7
    , (select r.resposta from Resposta r where pergunta_id = 8 and r.pessoa_id = c.pessoa_id) as resposta_8
    , (select r.resposta from Resposta r where pergunta_id = 9 and r.pessoa_id = c.pessoa_id) as resposta_9
    , (select r.resposta from Resposta r where pergunta_id = 10 and r.pessoa_id = c.pessoa_id) as resposta_10
    , (select r.resposta from Resposta r where pergunta_id = 11 and r.pessoa_id = c.pessoa_id) as resposta_11
    , (select r.resposta from Resposta r where pergunta_id = 12 and r.pessoa_id = c.pessoa_id) as resposta_12
    , (select r.resposta from Resposta r where pergunta_id = 13 and r.pessoa_id = c.pessoa_id) as resposta_13
    , (select r.resposta from Resposta r where pergunta_id = 14 and r.pessoa_id = c.pessoa_id) as resposta_14
    from Candidatura c
    inner join Pessoa p
    on c.pessoa_id = p.id
    inner join Eleicao e
    on c.eleicao_id = e.id
    inner join Cidade cid
        on c.unidade_eleitoral_id = cid.id
  left join Estado est
        on cid.estado_id = est.id
  inner join Partido par
    on c.partido_id = par.id
