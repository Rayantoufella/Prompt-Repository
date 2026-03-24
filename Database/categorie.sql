CREATE DATABASE IF NOT EXISTS prompt_repo

use prompt_repo;
create table categorie(
    id int auto_increment primary key , 
    name VARCHAR(255) not null, 
    description VARCHAR(255) not null
)