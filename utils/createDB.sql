CREATE TABLE "Categories" (
    id_category integer NOT NULL,
    author integer NOT NULL,
    name character varying(32) NOT NULL
);

COMMENT ON COLUMN "Categories".author IS 'id_user of the author';

CREATE SEQUENCE "Categories_id_category_seq"
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;

ALTER SEQUENCE "Categories_id_category_seq" OWNED BY "Categories".id_category;

CREATE TABLE "Users" (
    id_user integer NOT NULL,
    login character varying(255),
    pass character varying(32),
    email character varying(255)
);

CREATE SEQUENCE "User_id_user_seq"
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


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

-- Add Bills table

CREATE TABLE "Bills" (
    id_bill integer NOT NULL,
    description character varying(1024),
    value real NOT NULL,
    category integer NOT NULL
);

ALTER TABLE public."Bills" OWNER TO admin;

COMMENT ON COLUMN "Bills".category IS 'category id';

CREATE SEQUENCE "Bills_id_bill_seq"
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;

ALTER TABLE public."Bills_id_bill_seq" OWNER TO admin;

ALTER SEQUENCE "Bills_id_bill_seq" OWNED BY "Bills".id_bill;

ALTER TABLE ONLY "Bills" ALTER COLUMN id_bill SET DEFAULT nextval('"Bills_id_bill_seq"'::regclass);

ALTER TABLE ONLY "Bills"
    ADD CONSTRAINT "Bills_pkey" PRIMARY KEY (id_bill);

ALTER TABLE ONLY "Bills"
    ADD CONSTRAINT "Bills_category_fkey" FOREIGN KEY (category) REFERENCES "Categories"(id_category) ON DELETE CASCADE;

-- Add Cyclic

CREATE TABLE "Cyclic" (
    id_cyclic integer NOT NULL,
    description character varying(1024),
    value real NOT NULL,
    category integer NOT NULL,
    "when" date NOT NULL,
    "from" date NOT NULL,
    "to" date NOT NULL
);

ALTER TABLE public."Cyclic" OWNER TO admin;

COMMENT ON COLUMN "Cyclic".category IS 'category id';

CREATE SEQUENCE "Cyclic_id_cyclic_seq"
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;

ALTER TABLE public."Cyclic_id_cyclic_seq" OWNER TO admin;

ALTER SEQUENCE "Cyclic_id_cyclic_seq" OWNED BY "Cyclic".id_cyclic;

ALTER TABLE ONLY "Cyclic" ALTER COLUMN id_cyclic SET DEFAULT nextval('"Cyclic_id_cyclic_seq"'::regclass);

ALTER TABLE ONLY "Cyclic"
    ADD CONSTRAINT "Cyclic_pkey" PRIMARY KEY (id_cyclic);

ALTER TABLE ONLY "Cyclic"
    ADD CONSTRAINT "Cyclic_category_fkey" FOREIGN KEY (category) REFERENCES "Categories"(id_category) ON DELETE CASCADE;