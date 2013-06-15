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

INSERT INTO ORGANIZACION VALUES ('0','0','Sin asignar','0','','');

CREATE TABLE FAMILIA_CARGO(
    id              SERIAL      PRIMARY KEY,
    nombre          VARCHAR(50) NOT NULL,
    descripcion     TEXT
);

INSERT INTO FAMILIA_CARGO VALUES ('0', 'Sin asignar', '');
INSERT INTO FAMILIA_CARGO VALUES ('1', 'GERENCIAL', '');
INSERT INTO FAMILIA_CARGO VALUES ('2', 'SUPERVISORIO', '');
INSERT INTO FAMILIA_CARGO VALUES ('3', 'ADMINISTRATIVO PROFESIONAL', '');
INSERT INTO FAMILIA_CARGO VALUES ('4', 'ADMINISTRATIVO NO PROFESIONAL', '');
INSERT INTO FAMILIA_CARGO VALUES ('5', 'OBREROS', '');

CREATE TABLE CARGO(
    id              SERIAL      PRIMARY KEY,
    id_org          INTEGER     REFERENCES ORGANIZACION ON DELETE CASCADE,
    id_fam          INTEGER     REFERENCES FAMILIA_CARGO ON DELETE CASCADE,
    codigo          VARCHAR(50) NOT NULL,
    codtno          VARCHAR(50) ,
    codgra          VARCHAR(50) ,
    nombre          VARCHAR(50) NOT NULL,
    clave           BOOLEAN,
    descripcion     TEXT,
    funciones       TEXT
);

INSERT INTO CARGO VALUES ('0', '0', '0' , '', '', '', 'Sin asignar', '0', '', '');

CREATE TABLE FAMILIA_ROL(
    id              SERIAL      PRIMARY KEY,
    nombre          VARCHAR(50) NOT NULL,
    descripcion     TEXT
);

INSERT INTO FAMILIA_ROL VALUES ('0', 'Sin asignar', '');

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

INSERT INTO ROL VALUES ('0', '0', '0' , '', 'Sin asignar', '0', '', '');

CREATE TABLE PERSONA(
    id              SERIAL      PRIMARY KEY,
    nombre          VARCHAR(50) NOT NULL,
    apellido        VARCHAR(50) NOT NULL,
    cedula          VARCHAR(50) NOT NULL,
    sexo            CHAR(1),
    fecha_nac       VARCHAR(10),
    direccion       TEXT,
    telefono        VARCHAR(15),
    email           VARCHAR(50)
);

INSERT INTO PERSONA VALUES ('0', 'Sin asignar', '' , '' , '' , '' , '' , '' );

CREATE TABLE USUARIO(
    id              SERIAL      PRIMARY KEY,
    username        VARCHAR(50) NOT NULL
);

CREATE TABLE PERSONA_CARGO(
    id_per          INTEGER     REFERENCES PERSONA ON DELETE CASCADE,
    id_car          INTEGER     REFERENCES CARGO ON DELETE CASCADE,
    fecha           VARCHAR(10),
    observacion     TEXT
);

CREATE TABLE PERSONA_ROL(
    id_per          INTEGER     REFERENCES PERSONA ON DELETE CASCADE,
    id_rol          INTEGER     REFERENCES ROL ON DELETE CASCADE,
    fecha           VARCHAR(10),
    observacion     TEXT
);

CREATE TABLE EVALUADOR(
    id_per          INTEGER     REFERENCES PERSONA ON DELETE CASCADE,
    id_eva          INTEGER     REFERENCES PERSONA ON DELETE CASCADE,      
    observacion     TEXT
);

CREATE TABLE JEFE(
    id_per          INTEGER     REFERENCES PERSONA ON DELETE CASCADE,
    id_jef          INTEGER     REFERENCES PERSONA ON DELETE CASCADE      
);

CREATE TABLE SUPERVISOR(
    id_per          INTEGER     REFERENCES PERSONA ON DELETE CASCADE,
    id_sup          INTEGER     REFERENCES PERSONA ON DELETE CASCADE,     
    observacion     TEXT
);

CREATE TABLE CORREO(
    id_per          INTEGER      REFERENCES PERSONA ON DELETE CASCADE,
    destino         VARCHAR(50)  NOT NULL,
    asunto          VARCHAR(200) NOT NULL,
    mensaje         TEXT         NOT NULL
);

CREATE TABLE ERROR(
    id_error        SERIAL      PRIMARY KEY,
    mensaje         TEXT         NOT NULL
);