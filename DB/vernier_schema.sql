/* 
    Creacion BD sistema Vernier
    Ultima modificacion 22-05-13
*/

CREATE TABLE ORGANIZACION(
    id              SERIAL      PRIMARY KEY,
    idsup           INTEGER     REFERENCES ORGANIZACION ON DELETE CASCADE,
    nombre          VARCHAR(50) NOT NULL,
    codigo          VARCHAR(50),
    descripcion     TEXT,
    observacion     TEXT
);

INSERT INTO ORGANIZACION VALUES ('0','0','Universidad Simon Bolivar','0','','');

CREATE TABLE FAMILIA_CARGO(
    id              SERIAL      PRIMARY KEY,
    nombre          VARCHAR(50) NOT NULL,
    descripcion     TEXT
);

CREATE TABLE CARGO(
    id              SERIAL      PRIMARY KEY,
    id_org          INTEGER     REFERENCES ORGANIZACION ON DELETE CASCADE,
    id_fam          INTEGER     REFERENCES FAMILIA_CARGO ON DELETE CASCADE,
    codigo          VARCHAR(50) NOT NULL,
    nombre          VARCHAR(50) NOT NULL,
    clave           BOOLEAN,
    descripcion     TEXT,
    funciones       TEXT
);

CREATE TABLE FAMILIA_ROL(
    id              SERIAL      PRIMARY KEY,
    nombre          VARCHAR(50) NOT NULL,
    descripcion     TEXT
);

CREATE TABLE ROL(
    id              SERIAL      PRIMARY KEY,
    id_org          INTEGER     REFERENCES ORGANIZACION ON DELETE CASCADE,
    id_fam          INTEGER     REFERENCES FAMILIA_ROL ON DELETE CASCADE,
    codigo          VARCHAR(50) NOT NULL,
    nombre          VARCHAR(50) NOT NULL,
    clave           BOOLEAN,
    descripcion     TEXT,
    funciones       TEXT
);

CREATE TABLE PERSONA(
    id              SERIAL      PRIMARY KEY,
    nombre          VARCHAR(50) NOT NULL,
    cedula          VARCHAR(50) NOT NULL,
    sexo            CHAR(1),
    fecha_nac       DATE,
    direccion       TEXT,
    telefono        VARCHAR(15),
    email           VARCHAR(50)
);

CREATE TABLE USUARIO(
    id              SERIAL      PRIMARY KEY,
    username        VARCHAR(50) NOT NULL
);

CREATE TABLE PERSONA_CARGO(
    id_per          INTEGER     REFERENCES PERSONA ON DELETE CASCADE,
    id_car          INTEGER     REFERENCES CARGO ON DELETE CASCADE,
    fecha           DATE
);

CREATE TABLE PERSONA_ROL(
    id_per          INTEGER     REFERENCES PERSONA ON DELETE CASCADE,
    id_rol          INTEGER     REFERENCES ROL ON DELETE CASCADE,
    fecha           DATE
);

CREATE TABLE EVALUADOR(
    id_per          INTEGER     REFERENCES PERSONA ON DELETE CASCADE,
    id_eva          INTEGER     REFERENCES PERSONA ON DELETE CASCADE      
);

CREATE TABLE JEFE(
    id_per          INTEGER     REFERENCES PERSONA ON DELETE CASCADE,
    id_jef          INTEGER     REFERENCES PERSONA ON DELETE CASCADE      
);

CREATE TABLE SUPERVISOR(
    id_per          INTEGER     REFERENCES PERSONA ON DELETE CASCADE,
    id_sup          INTEGER     REFERENCES PERSONA ON DELETE CASCADE     
);

CREATE TABLE CORREO(
    id_per          INTEGER      REFERENCES PERSONA ON DELETE CASCADE,
    destino         VARCHAR(50)  NOT NULL,
    asunto          VARCHAR(200) NOT NULL,
    mensaje         TEXT         NOT NULL
);