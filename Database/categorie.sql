CREATE DATABASE IF NOT EXISTS prompt_repo;

USE prompt_repo;

CREATE TABLE categorie(
    id INT AUTO_INCREMENT PRIMARY KEY, 
    name VARCHAR(255) NOT NULL, 
    description VARCHAR(255) NOT NULL
);

INSERT INTO categorie (name, description) VALUES ('IA', 'Prompts related to artificial intelligence and machine learning');
INSERT INTO categorie (name, description) VALUES ('Dev mobile', 'Prompts related to mobile application development and programming');
INSERT INTO categorie (name, description) VALUES ('Data', 'Prompts related to data analysis, data science, and big data');
INSERT INTO categorie (name, description) VALUES ('Dev web', 'Prompts related to web development, front-end and back-end programming');