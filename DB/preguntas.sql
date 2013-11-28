
--
-- Name: pregunta; Type: TABLE; Schema: public; Owner: root; Tablespace: 
--

CREATE TABLE pregunta (
    id_encuesta_ls integer,
    id_pregunta integer NOT NULL,
    id_pregunta_root integer,
    titulo text,
    peso integer,
    seccion text
);


ALTER TABLE public.pregunta OWNER TO root;

--
-- Name: PREGUNTA_pkey; Type: CONSTRAINT; Schema: public; Owner: root; Tablespace: 
--

ALTER TABLE ONLY pregunta
    ADD CONSTRAINT "PREGUNTA_pkey" PRIMARY KEY (id_pregunta);