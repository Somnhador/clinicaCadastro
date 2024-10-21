create database clinica;
use clinica;

create table pacientes(
id int auto_increment primary key,
nome varchar(255) not null,
data_nascimento date not null,
email varchar(255) unique not null,
telefone varchar(11) not null,
endereco varchar(255) not null,
sexo varchar(10) not null
);