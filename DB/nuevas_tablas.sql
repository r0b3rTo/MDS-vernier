/* Nuevas tablas. Revisar*/

CREATE TABLE PERSONA_SUPERVISOR(
    id_per          INTEGER     REFERENCES PERSONA ON DELETE CASCADE,
    id_sup          INTEGER,
    actual          BOOLEAN,
    fecha_ini       VARCHAR(10),
    fecha_fin       VARCHAR(10),
    observacion     TEXT
);

CREATE TABLE PERSONA_EVALUADOR(
    id_per          INTEGER     REFERENCES PERSONA ON DELETE CASCADE,
    id_eva          INTEGER,
    actual          BOOLEAN,
    fecha_ini       VARCHAR(10),
    fecha_fin       VARCHAR(10),
    observacion     TEXT
);