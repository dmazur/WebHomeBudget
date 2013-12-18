CREATE TABLE "Categories" (
    id_category integer NOT NULL,
    author integer NOT NULL,
    name character varying(32) NOT NULL
);

ALTER TABLE public."Categories" OWNER TO admin;

COMMENT ON COLUMN "Categories".author IS 'id_user of the author';

CREATE SEQUENCE "Categories_id_category_seq"
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public."Categories_id_category_seq" OWNER TO admin;

ALTER SEQUENCE "Categories_id_category_seq" OWNED BY "Categories".id_category;

CREATE TABLE "Users" (
    id_user integer NOT NULL,
    login character varying(255),
    pass character varying(32),
    email character varying(255)
);


ALTER TABLE public."Users" OWNER TO admin;

CREATE SEQUENCE "User_id_user_seq"
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public."User_id_user_seq" OWNER TO admin;

ALTER SEQUENCE "User_id_user_seq" OWNED BY "Users".id_user;

ALTER TABLE ONLY "Categories" ALTER COLUMN id_category SET DEFAULT nextval('"Categories_id_category_seq"'::regclass);

ALTER TABLE ONLY "Users" ALTER COLUMN id_user SET DEFAULT nextval('"User_id_user_seq"'::regclass);

ALTER TABLE ONLY "Categories"
    ADD CONSTRAINT "Categories_pkey" PRIMARY KEY (id_category);

ALTER TABLE ONLY "Users"
    ADD CONSTRAINT "User_pkey" PRIMARY KEY (id_user);

ALTER TABLE ONLY "Users"
    ADD CONSTRAINT "Users_login_key" UNIQUE (login);

ALTER TABLE ONLY "Categories"
    ADD CONSTRAINT "Categories_author_fkey" FOREIGN KEY (author) REFERENCES "Users"(id_user) ON DELETE CASCADE;
