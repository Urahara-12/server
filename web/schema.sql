drop table if exists users;
drop table if exists depts;

create table if not exists users (
    id serial primary key
    , name varchar(20) not null
    , email varchar(20) unique not null
    , password varchar(1000) not null
    , registration_date date not null
);

create table if not exists depts (
    id serial primary key
    , name varchar(20) not null
    , description varchar(255) not null
);
