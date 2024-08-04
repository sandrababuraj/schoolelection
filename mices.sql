CREATE TABLE positions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL
);

CREATE TABLE candidates (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    position_id INT,
    FOREIGN KEY (position_id) REFERENCES positions(id)
);
