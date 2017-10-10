/*
  Informações:
  
  Estado: 
     ->  Nome do Estado deve ser alterado manualmente, não existe na tabela original
     
  Cidade:
     -> codigo_tse e codigo_ibge não preenchidos
     
  Pessoa:
     -> Se data de nascimento não for preenchida, é preenchido com 21/04/1500
     -> cor_tse e genero_tse estão preenchidas em banco ("")
     -> minibio, se não preenchido, encontra-se em branco ("")
     
*/

insert into merepresenta.Estado (sigla, nome)
	select distinct state, state from merepresenta2016.cities;
    
INSERT INTO merepresenta.Cidade (nome, estado_id, old_id, codigo_tse, codigo_ibge)
    select 
		c.name, (select e.id from merepresenta.Estado e where e.sigla = c.state ) as estado_id, c.id, 0 as codigo_tse, '' as codigo_ibge
		from merepresenta2016.cities c;

INSERT INTO merepresenta.Pergunta (id, texto)
    select id, text from merepresenta2016.questions;
    
INSERT INTO merepresenta.Pessoa(id, nome, email, cor_tse, genero_tse, genero_autodeclarado, data_nascimento, minibio)
   select 
		u.id, if (c.name is null, u.name, c.name) as nome, if (c.email is null, u.email, c.email), 
        '' as cor_tse, '' as genero_tse, 
        if (c.male is null, '', c.male), if (c.born_at is null, STR_TO_DATE('21/04/1500', '%d/%m/%Y'), c.born_at) as data_nascimento, if (c.bio is null, '', c.bio)
        from merepresenta2016.users u
        left join merepresenta2016.candidates c
           on c.id = u.id;

INSERT INTO merepresenta.Eleicao (id, ano, unidade_eleitoral_type)
VALUES (1, 2016, "Vereador");

INSERT INTO merepresenta.Resposta (pessoa_id, pergunta_id, resposta)
	select
	  a.responder_id,
      a.question_id, 
      substring(a.short_answer,1,1)
	from merepresenta2016.answers a
	inner join merepresenta2016.candidates c
		on a.responder_id = c.id and a.responder_type = 'Candidate';
        
INSERT INTO merepresenta.Partido (id, nome, sigla, numero, nota)
   select id, symbol, symbol, number, score * 100 from merepresenta2016.parties;

INSERT INTO merepresenta.Coligacao (id, nome, unidade_eleitoral_id)
select id, name, 1 as unidade_eleitoral_id from merepresenta2016.unions;

INSERT INTO merepresenta.Coligacao_Partido(partido_id,coligacao_id)
select pu.party_id, pu.union_id from merepresenta2016.parties_unions pu;

INSERT INTO merepresenta.Partido(id, nome, sigla, numero, nota)
VALUES (1, 'Partido do nada', 'PN', 0, 0);

INSERT INTO merepresenta.Candidatura(pessoa_id, eleicao_id, partido_id, nome_urna, numero_candidato, unidade_eleitoral_id, sequencial_candidato)
  select
    c.id as pessoa_id, 
    1 as eleicao_id,
	if (c.party_id is null, 1, c.party_id) as partido_id, if (c.nickname is null, c.name, c.nickname) as nome_urna, 
    if(c.number is null, 0, c.number) as numero_candidato, 
    (select cid.id from merepresenta.Cidade cid where cid.old_id = c.city_id) as unidade_eleitoral_id,
    c.id
  from merepresenta2016.candidates c
  where c.city_id is not null
