-- Crear base de datos
CREATE DATABASE IF NOT EXISTS tienda_db;
USE tienda_db;

-- =======================
-- Tabla: tamaños
-- =======================
CREATE TABLE tamanos (
    tamano_id INT AUTO_INCREMENT PRIMARY KEY
);

-- =======================
-- Tabla: categorías
-- =======================
CREATE TABLE categorias (
    nombrecategoria VARCHAR(50) PRIMARY KEY
);

-- =======================
-- Tabla: roles
-- =======================
CREATE TABLE roles (
    id_rol INT AUTO_INCREMENT PRIMARY KEY,
    rolename VARCHAR(50) NOT NULL,
    currentusers INT DEFAULT 0,
    status BOOLEAN DEFAULT TRUE
);

-- =======================
-- Tabla: usuarios
-- =======================
CREATE TABLE usuarios (
    userid INT AUTO_INCREMENT PRIMARY KEY,
    profilescreen VARCHAR(255),
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(20) NOT NULL CHECK (CHAR_LENGTH(password) BETWEEN 1 AND 8),
    role INT,
    status BOOLEAN DEFAULT TRUE,
    archived BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (role) REFERENCES roles(id_rol)
);

-- =======================
-- Tabla: producto
-- =======================
CREATE TABLE producto (
    idp INT AUTO_INCREMENT PRIMARY KEY,
    namep VARCHAR(20) NOT NULL,
    precio INT NOT NULL CHECK (precio >= 0),
    categoria VARCHAR(50),
    sabor INT,
    status BOOLEAN DEFAULT TRUE,
    FOREIGN KEY (categoria) REFERENCES categorias(nombrecategoria),
    FOREIGN KEY (sabor) REFERENCES tamanos(tamano_id)
);

-- =======================
-- Tabla: reseña
-- =======================
CREATE TABLE resena (
    idr INT AUTO_INCREMENT PRIMARY KEY,
    userid INT,
    username VARCHAR(50),
    comentario TEXT,
    producto INT,
    estrellas INT CHECK (estrellas BETWEEN 0 AND 5),
    FOREIGN KEY (userid) REFERENCES usuarios(userid),
    FOREIGN KEY (username) REFERENCES usuarios(username),
    FOREIGN KEY (producto) REFERENCES producto(idp)
);

-- =======================
-- Tabla: auditlogs
-- =======================
CREATE TABLE auditlogs (
    id_au INT AUTO_INCREMENT PRIMARY KEY,
    descripcion VARCHAR(255),
    username VARCHAR(50),
    FOREIGN KEY (username) REFERENCES usuarios(username)
);