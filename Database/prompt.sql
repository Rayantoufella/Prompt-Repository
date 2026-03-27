CREATE DATABASE IF NOT EXISTS prompt_repo;

USE prompt_repo;

CREATE TABLE prompt(
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    context VARCHAR(255) NOT NULL,
    user_id INT,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

ALTER TABLE prompt ADD COLUMN categorie_id INT;
ALTER TABLE prompt ADD FOREIGN KEY (categorie_id) REFERENCES categorie(id);


INSERT INTO prompt (title, categorie_id, context) VALUES ('Explain Machine Learning', 1, 'Explain the concept of machine learning in simple terms with examples');
INSERT INTO prompt (title, categorie_id, context) VALUES ('AI Ethics Discussion', 1, 'Discuss the ethical implications of artificial intelligence in society');
INSERT INTO prompt (title, categorie_id, context) VALUES ('Neural Networks Basics', 1, 'Explain how neural networks work and their applications');

-- Insert sample prompts for Dev mobile category (id=2)
INSERT INTO prompt (title, categorie_id, context) VALUES ('Flutter App Development', 2, 'How to build a cross-platform mobile app using Flutter');
INSERT INTO prompt (title, categorie_id, context) VALUES ('React Native Tutorial', 2, 'Complete guide to building iOS and Android apps with React Native');
INSERT INTO prompt (title, categorie_id, context) VALUES ('Mobile Performance Optimization', 2, 'Best practices for optimizing mobile app performance and battery usage');

-- Insert sample prompts for Data category (id=3)
INSERT INTO prompt (title, categorie_id, context) VALUES ('Data Analysis with Python', 3, 'Learn data analysis using pandas and NumPy libraries');
INSERT INTO prompt (title, categorie_id, context) VALUES ('Big Data Processing', 3, 'Introduction to Apache Spark and distributed data processing');
INSERT INTO prompt (title, categorie_id, context) VALUES ('Statistical Methods', 3, 'Essential statistical methods for data science and analytics');

-- Insert sample prompts for Dev web category (id=4)
INSERT INTO prompt (title, categorie_id, context) VALUES ('Modern CSS Techniques', 4, 'Learn advanced CSS features like Grid, Flexbox, and animations');
INSERT INTO prompt (title, categorie_id, context) VALUES ('RESTful API Design', 4, 'Best practices for designing and building RESTful APIs');
INSERT INTO prompt (title, categorie_id, context) VALUES ('JavaScript Async Patterns', 4, 'Master async/await, promises, and callback patterns in JavaScript');
