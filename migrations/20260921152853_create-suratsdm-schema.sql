--
-- PostgreSQL database dump
--


-- Dumped from database version 15.19 (Debian 15.19-1.pgdg13+2)
-- Dumped by pg_dump version 15.19 (Debian 15.19-1.pgdg13+2)


--
-- Name: public; Type: SCHEMA; Schema: -; Owner: -
--



--
-- Name: SCHEMA public; Type: COMMENT; Schema: -; Owner: -
--



--
-- Name: karyawans_status_karyawan; Type: TYPE; Schema: public; Owner: -
--

CREATE TYPE public.karyawans_status_karyawan AS ENUM (
    'karyawan_tetap',
    'pensiunan'
);




--
-- Name: form_jaminans; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.form_jaminans (
    id bigint NOT NULL,
    created_at timestamp without time zone,
    updated_at timestamp without time zone,
    nomor_surat character varying(255),
    jenis_surat character varying(255),
    id_karyawan bigint,
    id_jenis_pemeriksaan bigint,
    nama_pasien character varying(255),
    hubungan_keluarga character varying(255),
    id_rumah_sakit bigint,
    biaya_rumah_sakit bigint,
    status_pengajuan character varying(255),
    status_email boolean,
    file_pdf character varying(255),
    rangking bigint DEFAULT '1'::bigint NOT NULL,
    is_rejected boolean DEFAULT false NOT NULL
);


--
-- Name: form_jaminans_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.form_jaminans_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: form_jaminans_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.form_jaminans_id_seq OWNED BY public.form_jaminans.id;


--
-- Name: history_records; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.history_records (
    id bigint NOT NULL,
    karyawan_id bigint,
    riwayat_penyakit text,
    jenis_pengobatan text,
    riwayat_obat text,
    resume_medis text,
    created_at timestamp without time zone,
    updated_at timestamp without time zone
);


--
-- Name: history_records_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.history_records_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: history_records_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.history_records_id_seq OWNED BY public.history_records.id;


--
-- Name: instansis; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.instansis (
    id bigint NOT NULL,
    created_at timestamp without time zone,
    updated_at timestamp without time zone,
    nama_instansi character varying(255),
    keterangan text
);


--
-- Name: instansis_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.instansis_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: instansis_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.instansis_id_seq OWNED BY public.instansis.id;


--
-- Name: jenis_pemeriksaans; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.jenis_pemeriksaans (
    id bigint NOT NULL,
    created_at timestamp without time zone,
    updated_at timestamp without time zone,
    jenis_pemeriksaan character varying(255),
    keterangan text
);


--
-- Name: jenis_pemeriksaans_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.jenis_pemeriksaans_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: jenis_pemeriksaans_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.jenis_pemeriksaans_id_seq OWNED BY public.jenis_pemeriksaans.id;


--
-- Name: karyawans; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.karyawans (
    id bigint NOT NULL,
    created_at timestamp without time zone,
    updated_at timestamp without time zone,
    deleted_at timestamp without time zone,
    nama_karyawan character varying(255),
    nid character varying(255),
    jabatan character varying(255),
    jenjang_jabatan character varying(255),
    alamat text,
    tanggal_lahir date,
    istri character varying(255),
    anak_1 character varying(255),
    anak_2 character varying(255),
    anak_3 character varying(255),
    status_karyawan public.karyawans_status_karyawan,
    id_kelas_rawat_inap bigint NOT NULL,
    tgl_lahir_istri date,
    tgl_lahir_anak_1 date,
    tgl_lahir_anak_2 date,
    tgl_lahir_anak_3 date,
    email character varying(50),
    tanggal_masuk_karyawan date
);


--
-- Name: karyawans_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.karyawans_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: karyawans_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.karyawans_id_seq OWNED BY public.karyawans.id;


--
-- Name: kelas_rawat_inaps; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.kelas_rawat_inaps (
    id bigint NOT NULL,
    created_at timestamp without time zone,
    updated_at timestamp without time zone,
    jenis_kelas character varying(255),
    harga bigint
);


--
-- Name: kelas_rawat_inaps_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.kelas_rawat_inaps_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: kelas_rawat_inaps_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.kelas_rawat_inaps_id_seq OWNED BY public.kelas_rawat_inaps.id;


--
-- Name: kuitansis; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.kuitansis (
    id bigint NOT NULL,
    karyawan_id bigint NOT NULL,
    created_by bigint,
    nama_pasien character varying(255) NOT NULL,
    hubungan_keluarga character varying(255) DEFAULT 'Ybs'::character varying NOT NULL,
    id_rumah_sakit bigint,
    nominal bigint,
    tanggal_kuitansi date,
    diagnosa text,
    foto_path character varying(255),
    status character varying(255) DEFAULT 'Diajukan'::character varying NOT NULL,
    catatan text,
    created_at timestamp without time zone,
    updated_at timestamp without time zone
);


--
-- Name: kuitansis_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.kuitansis_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: kuitansis_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.kuitansis_id_seq OWNED BY public.kuitansis.id;


--
-- Name: migrations; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.migrations (
    id bigint NOT NULL,
    migration character varying(255) NOT NULL,
    batch bigint NOT NULL
);


--
-- Name: migrations_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.migrations_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: migrations_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.migrations_id_seq OWNED BY public.migrations.id;


--
-- Name: monitoring_tagihans; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.monitoring_tagihans (
    id bigint NOT NULL,
    created_at timestamp without time zone,
    updated_at timestamp without time zone,
    id_form_jaminan bigint,
    tanggal_tagihan date,
    no_tagihan character varying(255),
    jumlah bigint NOT NULL,
    tanggal_pembayaran date,
    tanggal_realisasi_perawatan date,
    tanggal_realisasi_perawatan_akhir date,
    keterangan text,
    status_pembayaran character varying(255)
);


--
-- Name: monitoring_tagihans_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.monitoring_tagihans_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: monitoring_tagihans_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.monitoring_tagihans_id_seq OWNED BY public.monitoring_tagihans.id;


--
-- Name: password_resets; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.password_resets (
    email character varying(255) NOT NULL,
    token character varying(255) NOT NULL,
    created_at timestamp without time zone
);


--
-- Name: rumah_sakits; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.rumah_sakits (
    id bigint NOT NULL,
    created_at timestamp without time zone,
    updated_at timestamp without time zone,
    nama_rumah_sakit character varying(255),
    alamat text,
    no_telpon character varying(255),
    email character varying(255)
);


--
-- Name: rumah_sakits_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.rumah_sakits_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: rumah_sakits_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.rumah_sakits_id_seq OWNED BY public.rumah_sakits.id;


--
-- Name: surat_keterangans; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.surat_keterangans (
    id bigint NOT NULL,
    karyawan_id bigint,
    nomor_surat character varying(255),
    sifat character varying(255),
    penerima character varying(255),
    alamat_penerima text,
    keperluan text,
    rangking bigint DEFAULT '1'::bigint,
    status character varying(255),
    file character varying(255),
    created_at timestamp without time zone,
    updated_at timestamp without time zone,
    tanggal_masuk_karyawan date,
    is_rejected boolean DEFAULT false NOT NULL
);


--
-- Name: surat_keterangans_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.surat_keterangans_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: surat_keterangans_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.surat_keterangans_id_seq OWNED BY public.surat_keterangans.id;


--
-- Name: users; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.users (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    email character varying(255) NOT NULL,
    password character varying(255) NOT NULL,
    role character varying(10),
    file_ttd character varying(100),
    remember_token character varying(100),
    created_at timestamp without time zone,
    updated_at timestamp without time zone,
    nid character varying(255),
    jabatan character varying(255)
);


--
-- Name: users_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.users_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: users_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.users_id_seq OWNED BY public.users.id;


--
-- Name: visa_keluargas; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.visa_keluargas (
    id bigint NOT NULL,
    visa_id bigint NOT NULL,
    nama character varying(255),
    hubungan character varying(255),
    nomor_passport character varying(255),
    created_at timestamp without time zone,
    updated_at timestamp without time zone
);


--
-- Name: visa_keluargas_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.visa_keluargas_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: visa_keluargas_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.visa_keluargas_id_seq OWNED BY public.visa_keluargas.id;


--
-- Name: visas; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.visas (
    id bigint NOT NULL,
    karyawan_id bigint,
    nomor_surat character varying(255),
    jenis character varying(255),
    tujuan character varying(255),
    alamat text,
    tanggal_mulai date,
    tanggal_selesai date,
    negara_tujuan character varying(255),
    keperluan character varying(255),
    rangking bigint DEFAULT '1'::bigint,
    status character varying(255),
    file character varying(255),
    created_at timestamp without time zone,
    updated_at timestamp without time zone,
    is_rejected bigint DEFAULT '0'::bigint NOT NULL
);


--
-- Name: visas_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.visas_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: visas_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.visas_id_seq OWNED BY public.visas.id;


--
-- Name: form_jaminans id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.form_jaminans ALTER COLUMN id SET DEFAULT nextval('public.form_jaminans_id_seq'::regclass);


--
-- Name: history_records id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.history_records ALTER COLUMN id SET DEFAULT nextval('public.history_records_id_seq'::regclass);


--
-- Name: instansis id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.instansis ALTER COLUMN id SET DEFAULT nextval('public.instansis_id_seq'::regclass);


--
-- Name: jenis_pemeriksaans id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.jenis_pemeriksaans ALTER COLUMN id SET DEFAULT nextval('public.jenis_pemeriksaans_id_seq'::regclass);


--
-- Name: karyawans id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.karyawans ALTER COLUMN id SET DEFAULT nextval('public.karyawans_id_seq'::regclass);


--
-- Name: kelas_rawat_inaps id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.kelas_rawat_inaps ALTER COLUMN id SET DEFAULT nextval('public.kelas_rawat_inaps_id_seq'::regclass);


--
-- Name: kuitansis id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.kuitansis ALTER COLUMN id SET DEFAULT nextval('public.kuitansis_id_seq'::regclass);


--
-- Name: migrations id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.migrations ALTER COLUMN id SET DEFAULT nextval('public.migrations_id_seq'::regclass);


--
-- Name: monitoring_tagihans id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.monitoring_tagihans ALTER COLUMN id SET DEFAULT nextval('public.monitoring_tagihans_id_seq'::regclass);


--
-- Name: rumah_sakits id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.rumah_sakits ALTER COLUMN id SET DEFAULT nextval('public.rumah_sakits_id_seq'::regclass);


--
-- Name: surat_keterangans id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.surat_keterangans ALTER COLUMN id SET DEFAULT nextval('public.surat_keterangans_id_seq'::regclass);


--
-- Name: users id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.users ALTER COLUMN id SET DEFAULT nextval('public.users_id_seq'::regclass);


--
-- Name: visa_keluargas id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.visa_keluargas ALTER COLUMN id SET DEFAULT nextval('public.visa_keluargas_id_seq'::regclass);


--
-- Name: visas id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.visas ALTER COLUMN id SET DEFAULT nextval('public.visas_id_seq'::regclass);


--
-- Name: form_jaminans idx_17012_primary; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.form_jaminans
    ADD CONSTRAINT idx_17012_primary PRIMARY KEY (id);


--
-- Name: history_records idx_17021_primary; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.history_records
    ADD CONSTRAINT idx_17021_primary PRIMARY KEY (id);


--
-- Name: instansis idx_17028_primary; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.instansis
    ADD CONSTRAINT idx_17028_primary PRIMARY KEY (id);


--
-- Name: jenis_pemeriksaans idx_17035_primary; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.jenis_pemeriksaans
    ADD CONSTRAINT idx_17035_primary PRIMARY KEY (id);


--
-- Name: karyawans idx_17042_primary; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.karyawans
    ADD CONSTRAINT idx_17042_primary PRIMARY KEY (id);


--
-- Name: kelas_rawat_inaps idx_17049_primary; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.kelas_rawat_inaps
    ADD CONSTRAINT idx_17049_primary PRIMARY KEY (id);


--
-- Name: kuitansis idx_17054_primary; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.kuitansis
    ADD CONSTRAINT idx_17054_primary PRIMARY KEY (id);


--
-- Name: migrations idx_17063_primary; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.migrations
    ADD CONSTRAINT idx_17063_primary PRIMARY KEY (id);


--
-- Name: monitoring_tagihans idx_17068_primary; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.monitoring_tagihans
    ADD CONSTRAINT idx_17068_primary PRIMARY KEY (id);


--
-- Name: rumah_sakits idx_17080_primary; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.rumah_sakits
    ADD CONSTRAINT idx_17080_primary PRIMARY KEY (id);


--
-- Name: surat_keterangans idx_17087_primary; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.surat_keterangans
    ADD CONSTRAINT idx_17087_primary PRIMARY KEY (id);


--
-- Name: users idx_17096_primary; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT idx_17096_primary PRIMARY KEY (id);


--
-- Name: visas idx_17103_primary; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.visas
    ADD CONSTRAINT idx_17103_primary PRIMARY KEY (id);


--
-- Name: visa_keluargas idx_17112_primary; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.visa_keluargas
    ADD CONSTRAINT idx_17112_primary PRIMARY KEY (id);


--
-- Name: idx_17054_kuitansis_karyawan_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX idx_17054_kuitansis_karyawan_id_index ON public.kuitansis USING btree (karyawan_id);


--
-- Name: idx_17054_kuitansis_status_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX idx_17054_kuitansis_status_index ON public.kuitansis USING btree (status);


--
-- Name: idx_17074_password_resets_email_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX idx_17074_password_resets_email_index ON public.password_resets USING btree (email);


--
-- Name: idx_17096_users_email_unique; Type: INDEX; Schema: public; Owner: -
--

CREATE UNIQUE INDEX idx_17096_users_email_unique ON public.users USING btree (email);


--
-- PostgreSQL database dump complete
--


