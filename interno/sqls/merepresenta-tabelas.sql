use merepresenta;

drop table if exists Resposta;
drop table if exists Pergunta;
drop table if exists Candidatura;
drop table if exists Pessoa;
drop table if exists Eleicao;
drop table if exists Coligacao_Partido;
drop table if exists Partido;
drop table if exists Coligacao;
drop table if exists Cidade;
drop table if exists Estado;

create table Estado (
   id int not null auto_increment,
   nome varchar(200) not null,
   sigla char(2) not null,
   old_id int,
   primary key (id),
   unique(sigla),
   unique(nome)
) charset=utf8 COLLATE=utf8_general_ci;
create index ixEstado on Estado(sigla);

create table Cidade (
  id int not null auto_increment,
  nome varchar(200) not null,
  estado_id int not null,
  codigo_tse int not null,
  codigo_ibge varchar(20) not null,
  old_id int,
  primary key (id),
  foreign key(estado_id) references Estado (id)
) charset=utf8 COLLATE=utf8_general_ci;


create table Partido (
  id int not null auto_increment,
  nome varchar(200) not null,
  sigla varchar(10) not null,
  numero int not null,
  nota decimal,
  primary key(id)
) charset=utf8 COLLATE=utf8_general_ci;


create table Coligacao (
  id int not null auto_increment,
  nome varchar(200) not null,
  unidade_eleitoral_id int,  
  nota decimal,
  primary key(id)
) charset=utf8 COLLATE=utf8_general_ci;


create table Coligacao_Partido (
  id int not null auto_increment,
  partido_id int not null,
  coligacao_id int,
  primary key(id),
  foreign key(partido_id) references Partido(id),
  foreign key(coligacao_id) references Coligacao(id)
) charset=utf8 COLLATE=utf8_general_ci;


create table Eleicao (
  id int not null auto_increment,
  ano int not null,
  unidade_eleitoral_type varchar(200) not null,
  primary key(id)
) charset=utf8 COLLATE=utf8_general_ci;


create table Pessoa (
  id int not null auto_increment,
  nome varchar(200) not null,
  cor_tse varchar(45) not null,
  genero_tse varchar(45) not null,
  genero_autodeclarado varchar(45) not null,
  data_nascimento date not null,
  minibio text not null,
  email varchar(200) not null,
  fb_id varchar(200),
  primary key(id)
) charset=utf8 COLLATE=utf8_general_ci;


create table Candidatura (
  id int not null auto_increment,
  pessoa_id int not null,
  eleicao_id int not null,
  partido_id int not null,
  nome_urna varchar(200)  not null,
  numero_candidato int not null,
  unidade_eleitoral_id int not null,
  situacao_candidatura varchar(45),
  votacao int,
  situacao_eleitoral varchar(45),
  sequencial_candidato bigint not null,
  primary key(id),
  foreign key(pessoa_id) references Pessoa(id),
  foreign key(eleicao_id) references Eleicao(id),
  foreign key(partido_id) references Partido(id)
) charset=utf8 COLLATE=utf8_general_ci;


create table Pergunta (
  id int not null auto_increment,
  texto text,
  primary key(id)
) charset=utf8 COLLATE=utf8_general_ci;


create table Resposta (
  id int not null auto_increment,
  pessoa_id int not null,
  pergunta_id int not null,
  resposta varchar(1),
  primary key(id),
  foreign key(pessoa_id) references Pessoa(id),
  foreign key(pergunta_id) references Pergunta(id)
) charset=utf8 COLLATE=utf8_general_ci;
