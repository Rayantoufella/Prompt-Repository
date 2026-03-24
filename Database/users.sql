CREATE DATABASE IF NOT EXISTS prompt_repo

use prompt_repo;

create table users (
    id int AUTO_INCREMENT primary key,
    username varchar(255) not null,
    email varchar(255) not null,
    password VARCHAR(255) not null,
    role ENUM('admin', 'user') not null
    
)
