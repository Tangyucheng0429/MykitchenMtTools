CREATE DATABASE mykitchenmytools_db;

USE mykitchenmytools_db;

CREATE TABLE admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL
);

INSERT INTO admins (username, password) VALUES ('admin', '1234');