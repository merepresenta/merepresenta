/*
  Lista os emails que enviaram mensagens para a plataforma. 
*/
select  distinct
    substring(mensagem, Locate('Email: ', mensagem) + 7, Locate('WhatsApp', mensagem)-Locate('Email: ', mensagem)-7) as Email
  , substring(mensagem, Locate('Meu nome: ', mensagem) + 10, Locate('Sobrenome', mensagem)-Locate('Meu nome: ', mensagem)-10) as Nome
  , substring(mensagem, Locate('Sobrenome: ', mensagem) + 11, Locate('Email', mensagem)-Locate('Sobrenome: ', mensagem)-11) as Sobrenome
, id
  from Mensagens
  where id<> 35
