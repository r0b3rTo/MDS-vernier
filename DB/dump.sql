--
-- PostgreSQL database dump for EVALUACION, ENCUESTA, PERSONA_ENCUESTA
--

--
-- Name: encuesta; Type: TABLE; Schema: public; Owner: root; Tablespace: 
--

CREATE TABLE encuesta (
    id_car integer,
    id_encuesta_ls text,
    estado boolean
);


ALTER TABLE public.encuesta OWNER TO root;

SET default_with_oids = false;

--
-- Name: evaluacion_id_seq; Type: SEQUENCE; Schema: public; Owner: root
--

CREATE SEQUENCE evaluacion_id_seq
    START WITH 0
    INCREMENT BY 1
    MINVALUE 0
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.evaluacion_id_seq OWNER TO root;

--
-- Name: evaluacion; Type: TABLE; Schema: public; Owner: root; Tablespace: 
--

CREATE TABLE evaluacion (
    id integer DEFAULT nextval('evaluacion_id_seq'::regclass) NOT NULL,
    periodo text,
    fecha_ini character varying(10),
    fecha_fin character varying(10),
    actual boolean
);


ALTER TABLE public.evaluacion OWNER TO root;

--
-- Name: persona_encuesta; Type: TABLE; Schema: public; Owner: root; Tablespace: 
--

CREATE TABLE persona_encuesta (
    id_car integer,
    tipo text,
    token_ls text,
    estado text,
    id_encuesta_ls integer,
    id_encuestado integer,
    id_evaluado integer,
    actual boolean,
    periodo text,
    ip text DEFAULT '127.0.0.1'::text NOT NULL,
    fecha character varying(16) DEFAULT '00/00/0000.00:00'::character varying NOT NULL
);


ALTER TABLE public.persona_encuesta OWNER TO root;


--
-- PostgreSQL database dump complete
--

