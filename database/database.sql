--=============================================
--Base de datos: Sweet Dreams
--Sistema de gestion de pedido para pasteleria 
--=============================================

CREATE DATABASE IF NOT EXISTS sweet_dreams;
USE sweet_dreams;

--=================================================
--Creacion de tabla: Usuario
--Almacena clientes, administradores y empleados
--=================================================

CREATE TABLE IF NOT EXISTS usuarios(
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    rol ENUM("cliente", "empleado", "admin") NOT NULL DEFAULT "cliente",
    telefono VARCHAR(20),
    direccion VARCHAR(100) ,
    pregunta_seguridad VARCHAR(255),
    respuesta_seguridad VARCHAR(255),
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci ;

--==================================================================
--Creacion de tabla: Producto
--catalogo de los productos de la pasteleria
--==================================================================
CREATE TABLE IF NOT EXISTS productos(
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL,
    descripcion TEXT,
    precio DECIMAL(10,2) NOT NULL,
    stock INT NOT NULL DEFAULT 1,
    imagen VARCHAR(255) ,
    creacion_imagen TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;
--==================================================================
--Creacion de tabla: Pedido
--Pedidos realizados por los clientes
--==================================================================

CREATE TABLE IF NOT EXISTS pedidos(
    id INT AUTO_INCREMENT PRIMARY KEY,
    cliente_id INT NOT NULL,
    empleado_id INT NULL,
    fecha_pedido TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_entrega Date,
    estado ENUM("pendiente", "preparacion","listo", "entregado","cancelado") NOT NULL DEFAULT "pendiente",
    total DECIMAL(10,2) NOT NULL,
    nota text,
    FOREIGN KEY(cliente_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY(empleado_id) REFERENCES usuarios(id) ON DELETE SET NULL

)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

-- ======================================================
-- Creacion de tabla: detalle_pedido
-- Productos incluidos en cada pedido (relación N:M)
-- ======================================================

CREATE TABLE IF NOT EXISTS detalle_pedidos(
    id INT AUTO_INCREMENT PRIMARY KEY,
    pedido_id INT NOT NULL,
    producto_id INT NOT NULL,
    cantidad INT NOT NULL DEFAULT 0,
    precio_unitario DECIMAL(10,2) NOT NULL,
    subtotal DECIMAL(10,2)NOT NULL,
    FOREIGN KEY(pedido_id) REFERENCES pedidos(id) ON DELETE CASCADE,
    FOREIGN KEY(producto_id) REFERENCES productos(id) ON DELETE CASCADE
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--===================================================================
--Creaciones de indices
--====================================================================
-- Indice para filtrar usuarios por rol
CREATE INDEX idx_usuarios_rol ON usuarios(rol);

-- Indice para filtrar pedidos por estado
CREATE INDEX idx_pedidos_estado ON pedidos(estado);