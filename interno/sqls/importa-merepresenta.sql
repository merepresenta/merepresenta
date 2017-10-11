/*
  Informações:
  
  Estado: 
     ->  Nome do Estado deve ser alterado manualmente, não existe na tabela original
     
  Cidade:
     -> codigo_tse e codigo_ibge não preenchidos
     
  Pessoa:
     -> Se data de nascimento não for preenchida, é preenchido com 21/04/1500


  merepresenta2016.cities: Grafia diferente para Açu/RN no banco de dados do TSE, por isso estamos alterando para a grafia local: Assu     
*/
update merepresenta2016.cities set name='Assu' where id =2400208;


/** 
 * Criação de tabela auxiliar de cidades (tse) para aumentar a velocidade de algumas queries
 */
drop table if exists tse.cidade;

create table tse.cidade (
  siglaUE varchar(255) not null,
  descricaoUE varchar(255) not null,
  siglaUF char(2) not null,
  primary key (siglaUE)
);

insert into tse.cidade (siglaUE, descricaoUE, siglaUF) 
  select distinct 
        c.siglaUE
      , c.descricaoUE
      , c.siglaUF
    from tse.tse_candidato c;

create index idx_cidade_descricao_e_uf on tse.cidade(descricaoUE,siglaUF);


/**
 * Preenchimento da tabela de estados
 */
insert into merepresenta.Estado (sigla, nome)
	select distinct 
        state
      , state
    from merepresenta2016.cities;


/**
 * Preenchimento da tabela de cidade
 */
INSERT INTO merepresenta.Cidade (nome, estado_id, old_id, codigo_tse, codigo_ibge)
  select 
  		  c.name as nome
      , (select e.id from merepresenta.Estado e where e.sigla = c.state ) as estado_id
      , c.id as old_id
      , cid.siglaUE as codigo_tse
      , c.id as codigo_ibge
		from merepresenta2016.cities c
    inner join tse.cidade cid
       on cid.descricaoUE = upper(c.name)
      and cid.siglaUF = c.state;


/**
 * Prenchimento das perguntas utilizadas
 */
INSERT INTO merepresenta.Pergunta (id, texto)
    select
          id
        , text
      from merepresenta2016.questions;


/**
 * Importação das pessoas 
 */
INSERT INTO merepresenta.Pessoa(id, nome, email, cor_tse, genero_tse, genero_autodeclarado, data_nascimento, minibio)
  select 
        u.id
      , if (c.name is null, u.name, c.name) as nome
      , if (c.email is null, u.email, c.email) as email
      , if (tsec.descricaoCorRaca is null, '', tsec.descricaoCorRaca) as cor_tse
      , if (tsec.descricaoSexo is null, '', tsec.descricaoSexo) as genero_tse
      , case c.male
          when true then 'MASCULINO'
          when false then 'FEMININO'
          else ''
        end as genero_autodeclarado
      , if (c.born_at is null, STR_TO_DATE('21/04/1500', '%d/%m/%Y'), c.born_at) as data_nascimento
      , if (c.bio is null, '', c.bio) as minibio
    from merepresenta2016.users u
    inner join merepresenta2016.candidates c
       on c.id = u.id
    inner join merepresenta2016.cities cit
      on cit.id = c.city_id
    left join tse.tse_candidato tsec
      on  (tsec.numeroCandidato = c.number)
      and (tsec.descricaoUE = upper(cit.name))
      and (tsec.siglaUF = cit.state)
      and (tsec.codSitTotTurno>0)
      and (tsec.codigoSituacaoCandidatura <> 7)
    where 
          (c.finished_at is not null)
      and (length(c.number) = 5);


/**
 * Cadastramento da eleição municipal de 2016
 */
INSERT INTO merepresenta.Eleicao (id, ano, unidade_eleitoral_type)
  VALUES (1, 2016, "Vereador");


/**
 * Cadastro das respostas dos candidatos
 */
INSERT INTO merepresenta.Resposta (pessoa_id, pergunta_id, resposta)
	select
        a.responder_id
      , a.question_id 
      , substring(a.short_answer,1,1)
  	from merepresenta2016.answers a
  	inner join merepresenta2016.candidates c
  		 on a.responder_id = c.id 
      and a.responder_type = 'Candidate'
    where c.finished_at is not null
      and (length(c.number) = 5);


/**
 * Cadastro de partidos
 */
INSERT INTO merepresenta.Partido (id, nome, sigla, numero, nota)
  select 
        p.id as id
      , p.symbol as nome
      , p.symbol as sigla
      , p.number as numero
      , p.score * 100 as nota
    from merepresenta2016.parties p;


/**
 * Cadastro das coligações
 */
INSERT INTO merepresenta.Coligacao (id, nome, unidade_eleitoral_id)
  select
        id
      , name as nome
      , 1 as unidade_eleitoral_id
    from merepresenta2016.unions;


/**
 * Ligações entre coligações e partidos participantes
 */
INSERT INTO merepresenta.Coligacao_Partido(partido_id, coligacao_id)
  select
        pu.party_id as partido_id
      , pu.union_id as coligacao_id
    from merepresenta2016.parties_unions pu;


/**
 * Cadastro das candidaturas
 */
INSERT INTO merepresenta.Candidatura(pessoa_id, eleicao_id, partido_id, nome_urna, numero_candidato, unidade_eleitoral_id, sequencial_candidato)
  select
        c.id as pessoa_id
      , 1 as eleicao_id
      , c.party_id as partido_id
      , if (c.nickname is null, c.name, c.nickname) as nome_urna
      , if(c.number is null, 0, c.number) as numero_candidato
      , (select cid.id from merepresenta.Cidade cid where cid.old_id = c.city_id) as unidade_eleitoral_id
      , c.id
    from merepresenta2016.candidates c
    where 
          ( c.finished_at is not null )
      and ( length(c.number) = 5 );
