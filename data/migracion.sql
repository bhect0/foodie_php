CREATE TABLE receta (
    id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(60) NOT NULL,
    descripcion VARCHAR(100) NOT NULL,
    foto VARCHAR(50) NOT NULL,
    pasos VARCHAR(1000),
    ingredientes VARCHAR(1000),
    tiempo_estimado VARCHAR(30),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
