PRAGMA foreign_keys = ON;

CREATE TABLE IF NOT EXISTS categorias (
    id     INTEGER PRIMARY KEY AUTOINCREMENT,
    nombre TEXT NOT NULL UNIQUE
);

CREATE TABLE IF NOT EXISTS perfumes (
    id           INTEGER PRIMARY KEY AUTOINCREMENT,
    nombre       TEXT NOT NULL,
    marca        TEXT NOT NULL,
    descripcion  TEXT,
    notas        TEXT,
    imagen       TEXT,
    categoria_id INTEGER,
    activo       INTEGER NOT NULL DEFAULT 1,
    creado_en    TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (categoria_id) REFERENCES categorias(id) ON DELETE SET NULL
);

CREATE TABLE IF NOT EXISTS presentaciones (
    id         INTEGER PRIMARY KEY AUTOINCREMENT,
    perfume_id INTEGER NOT NULL,
    ml         INTEGER NOT NULL CHECK (ml IN (3, 5, 10)),
    precio     REAL    NOT NULL CHECK (precio >= 0),
    stock      INTEGER NOT NULL DEFAULT 0 CHECK (stock >= 0),
    activo     INTEGER NOT NULL DEFAULT 1,
    FOREIGN KEY (perfume_id) REFERENCES perfumes(id) ON DELETE CASCADE,
    UNIQUE (perfume_id, ml)
);

CREATE TABLE IF NOT EXISTS clientes (
    id        INTEGER PRIMARY KEY AUTOINCREMENT,
    nombre    TEXT NOT NULL,
    email     TEXT UNIQUE,
    telefono  TEXT,
    direccion TEXT,
    creado_en TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS pedidos (
    id         INTEGER PRIMARY KEY AUTOINCREMENT,
    cliente_id INTEGER NOT NULL,
    fecha      TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
    estado     TEXT NOT NULL DEFAULT 'pendiente'
               CHECK (estado IN ('pendiente','pagado','enviado','entregado','cancelado')),
    total      REAL NOT NULL DEFAULT 0,
    FOREIGN KEY (cliente_id) REFERENCES clientes(id) ON DELETE RESTRICT
);

CREATE TABLE IF NOT EXISTS detalle_pedido (
    id              INTEGER PRIMARY KEY AUTOINCREMENT,
    pedido_id       INTEGER NOT NULL,
    presentacion_id INTEGER NOT NULL,
    cantidad        INTEGER NOT NULL CHECK (cantidad > 0),
    precio_unitario REAL    NOT NULL CHECK (precio_unitario >= 0),
    subtotal        REAL    NOT NULL,
    FOREIGN KEY (pedido_id)       REFERENCES pedidos(id)        ON DELETE CASCADE,
    FOREIGN KEY (presentacion_id) REFERENCES presentaciones(id) ON DELETE RESTRICT

CREATE TABLE IF NOT EXISTS usuarios (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nombre TEXT NOT NULL UNIQUE,
    correo TEXT,
    contrasena TEXT NOT NULL,
    creado_en TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP
);
);

CREATE INDEX IF NOT EXISTS idx_perfumes_marca         ON perfumes(marca);
CREATE INDEX IF NOT EXISTS idx_perfumes_categoria     ON perfumes(categoria_id);
CREATE INDEX IF NOT EXISTS idx_presentaciones_perfume ON presentaciones(perfume_id);