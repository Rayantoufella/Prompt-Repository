CREATE DATABASE IF NOT EXISTS prompt_repo

use prompt_repo;

CREATE table prompt(
    id int auto_increment primary key,
    title VARCHAR(255) not null,
    context VARCHAR(255) not null,
    user_id int,
    foreign key (user_id) references users(id),
    categorie_id int,
    Foreign Key (categorie_id) REFERENCES categorie(id)
)
