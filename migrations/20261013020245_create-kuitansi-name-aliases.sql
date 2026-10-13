CREATE TABLE public.kuitansi_name_aliases (
    id serial PRIMARY KEY,
    status_karyawan character varying(32) NOT NULL,
    alias_key character varying(255) NOT NULL,
    alias_name character varying(255) NOT NULL,
    karyawan_id bigint NOT NULL,
    created_by bigint,
    created_at timestamp without time zone,
    updated_at timestamp without time zone,
    CONSTRAINT kuitansi_name_aliases_status_alias_unique UNIQUE (status_karyawan, alias_key)
);

-- Confirmed by the SDM admin: "KOESPRAPTINI RIA" in the kuitansi sheet is
-- pensioner KOESPRATINI RIA (active record id 973).
INSERT INTO public.kuitansi_name_aliases (status_karyawan, alias_key, alias_name, karyawan_id, created_at, updated_at)
VALUES ('pensiunan', 'KOESPRAPTINIRIA', 'KOESPRAPTINI RIA', 973, now(), now());
