use wordpress;

create or replace view all_data
as
    select 
              c.id as id_candidatura
            , cid.id as id_cidade
            , par.id as id_partido
            , est.id as id_estado
            , c.nome_urna as nome_urna
            , p.nome as nome_candidato
            , cid.nome as nome_cidade
            , est.sigla as sigla_estado
            , par.numero as numero_partido
            , par.nota as nota_partido
            , par.sigla as sigla_partido
            , ( 
              select 
                coli.nome 
                from Coligacao_Partido CP 
                        inner join Coligacao coli
                        on 
                  (CP.coligacao_id = coli.id)
                where   coli.unidade_eleitoral_id = c.unidade_eleitoral_id and
                    CP.partido_id = c.partido_id 
            ) as nome_coligacao
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
            , c.votacao as votos_recebidos
            , p.cor_tse as cor_tse
            , if (p.genero_autodeclarado is null, p.genero_tse, p.genero_autodeclarado) as genero
            , c.situacao_candidatura as situacao_candidatura
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
            on c.partido_id = par.id;

create or replace view politico
as
    select
            c.id as candidatura_id
            , c.nome_urna as nome_urna
            , par.sigla as sigla_partido
            , par.nota as nota_partido
            , cid.nome as cidade_eleicao
            , est.sigla as uf_eleicao
            , p.minibio as minibio
            , p.fb_id as fb_id
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
            on c.partido_id = par.id;