--
-- PostgreSQL database dump
--

-- Dumped from database version 9.5.2
-- Dumped by pg_dump version 9.5.2

-- Started on 2016-05-24 13:42:38 AMT

SET statement_timeout = 0;
SET lock_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SET check_function_bodies = false;
SET client_min_messages = warning;
SET row_security = off;

--
-- TOC entry 1 (class 3079 OID 12365)
-- Name: plpgsql; Type: EXTENSION; Schema: -; Owner: 
--

CREATE EXTENSION IF NOT EXISTS plpgsql WITH SCHEMA pg_catalog;


--
-- TOC entry 2373 (class 0 OID 0)
-- Dependencies: 1
-- Name: EXTENSION plpgsql; Type: COMMENT; Schema: -; Owner: 
--

COMMENT ON EXTENSION plpgsql IS 'PL/pgSQL procedural language';


SET search_path = public, pg_catalog;

--
-- TOC entry 228 (class 1255 OID 17344)
-- Name: compravalortotal(integer); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION compravalortotal(p_compraid integer) RETURNS double precision
    LANGUAGE plpgsql
    AS $$
DECLARE
    v_total double precision;

BEGIN

    SELECT
           SUM(E.quantidade * E.valorunitario) INTO v_total
      FROM estoquemovimento E
INNER JOIN compra C
        ON C.compraid = E.compraid
       AND C.compraid = p_compraid
INNER JOIN produto P
        ON P.produtoid = E.produtoid;
    
RETURN v_total;

END;
$$;


ALTER FUNCTION public.compravalortotal(p_compraid integer) OWNER TO postgres;

--
-- TOC entry 2374 (class 0 OID 0)
-- Dependencies: 228
-- Name: FUNCTION compravalortotal(p_compraid integer); Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON FUNCTION compravalortotal(p_compraid integer) IS 'Calcula o valor total de uma compra';


--
-- TOC entry 229 (class 1255 OID 17345)
-- Name: estoqueatual(integer); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION estoqueatual(p_produtoid integer) RETURNS double precision
    LANGUAGE plpgsql
    AS $$
DECLARE
    v_total double precision;

BEGIN

SELECT 
       ((SELECT CASE 
                    WHEN
                        SUM(EME.quantidade) IS NULL THEN 0
                    ELSE
                        SUM(EME.quantidade)
                END
           FROM estoquemovimento EME
          WHERE EME.operacao = 1
            AND EME.produtoid = P.produtoid) -
        (SELECT CASE 
                    WHEN
                        SUM(EMS.quantidade) IS NULL THEN 0
                    ELSE
                        SUM(EMS.quantidade) 
                END
           FROM estoquemovimento EMS
          WHERE EMS.operacao = 2
            AND EMS.produtoid = P.produtoid)) INTO v_total
  FROM produto P
 WHERE P.produtoid = p_produtoid;

RETURN v_total;

END;
$$;


ALTER FUNCTION public.estoqueatual(p_produtoid integer) OWNER TO postgres;

--
-- TOC entry 230 (class 1255 OID 17346)
-- Name: requisicaovalortotal(integer); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION requisicaovalortotal(p_requisicaoid integer) RETURNS double precision
    LANGUAGE plpgsql
    AS $$
DECLARE
    v_total double precision;

BEGIN

    SELECT
           SUM(E.quantidade * E.valorunitario) INTO v_total
      FROM estoquemovimento E
INNER JOIN requisicao R
        ON R.requisicaoid = E.requisicaoid
       AND R.requisicaoid = p_requisicaoid
INNER JOIN produto P
        ON P.produtoid = E.produtoid;
    
RETURN v_total;

END;
$$;


ALTER FUNCTION public.requisicaovalortotal(p_requisicaoid integer) OWNER TO postgres;

--
-- TOC entry 2375 (class 0 OID 0)
-- Dependencies: 230
-- Name: FUNCTION requisicaovalortotal(p_requisicaoid integer); Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON FUNCTION requisicaovalortotal(p_requisicaoid integer) IS 'Calcula o valor total de uma requisicao';


--
-- TOC entry 243 (class 1255 OID 17347)
-- Name: valormedio(integer); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION valormedio(p_produtoid integer) RETURNS double precision
    LANGUAGE plpgsql
    AS $$
DECLARE
    v_media double precision;

BEGIN

SELECT AVG(valorunitario) INTO v_media
  FROM estoquemovimento 
 WHERE operacao = 1 
   AND produtoid = p_produtoid;

RETURN v_media;

END;
$$;


ALTER FUNCTION public.valormedio(p_produtoid integer) OWNER TO postgres;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- TOC entry 181 (class 1259 OID 17348)
-- Name: categoria; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE categoria (
    categoriaid integer NOT NULL,
    nome character varying(100)
);


ALTER TABLE categoria OWNER TO postgres;

--
-- TOC entry 182 (class 1259 OID 17351)
-- Name: categoria_categoriaid_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE categoria_categoriaid_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE categoria_categoriaid_seq OWNER TO postgres;

--
-- TOC entry 2376 (class 0 OID 0)
-- Dependencies: 182
-- Name: categoria_categoriaid_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE categoria_categoriaid_seq OWNED BY categoria.categoriaid;


--
-- TOC entry 183 (class 1259 OID 17353)
-- Name: cidade; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE cidade (
    cidadeid integer NOT NULL,
    nome character varying(50),
    uf character varying(2),
    cep character varying(10)
);


ALTER TABLE cidade OWNER TO postgres;

--
-- TOC entry 2377 (class 0 OID 0)
-- Dependencies: 183
-- Name: COLUMN cidade.cidadeid; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN cidade.cidadeid IS 'código da cidade';


--
-- TOC entry 2378 (class 0 OID 0)
-- Dependencies: 183
-- Name: COLUMN cidade.nome; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN cidade.nome IS 'nome da cidade';


--
-- TOC entry 2379 (class 0 OID 0)
-- Dependencies: 183
-- Name: COLUMN cidade.uf; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN cidade.uf IS 'estado onde a cidade se encontra';


--
-- TOC entry 2380 (class 0 OID 0)
-- Dependencies: 183
-- Name: COLUMN cidade.cep; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN cidade.cep IS 'cep da cidade';


--
-- TOC entry 184 (class 1259 OID 17356)
-- Name: cidade_cidadeid_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE cidade_cidadeid_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE cidade_cidadeid_seq OWNER TO postgres;

--
-- TOC entry 2381 (class 0 OID 0)
-- Dependencies: 184
-- Name: cidade_cidadeid_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE cidade_cidadeid_seq OWNED BY cidade.cidadeid;


--
-- TOC entry 185 (class 1259 OID 17358)
-- Name: compra; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE compra (
    compraid integer NOT NULL,
    fornecedorid integer,
    emissao date,
    aprovacao date,
    entrega date,
    situacao integer DEFAULT 1,
    reprovacaotxt text,
    reprovacao date
);


ALTER TABLE compra OWNER TO postgres;

--
-- TOC entry 2382 (class 0 OID 0)
-- Dependencies: 185
-- Name: COLUMN compra.fornecedorid; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN compra.fornecedorid IS 'Código do fornecedor';


--
-- TOC entry 2383 (class 0 OID 0)
-- Dependencies: 185
-- Name: COLUMN compra.emissao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN compra.emissao IS 'Data da emissão';


--
-- TOC entry 2384 (class 0 OID 0)
-- Dependencies: 185
-- Name: COLUMN compra.aprovacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN compra.aprovacao IS 'data da aprovação';


--
-- TOC entry 2385 (class 0 OID 0)
-- Dependencies: 185
-- Name: COLUMN compra.entrega; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN compra.entrega IS 'data da entrega';


--
-- TOC entry 2386 (class 0 OID 0)
-- Dependencies: 185
-- Name: COLUMN compra.situacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN compra.situacao IS '1= aberta; 2= aprovada; 3= entregue; 4= reprovada; 5= cancelada.';


--
-- TOC entry 2387 (class 0 OID 0)
-- Dependencies: 185
-- Name: COLUMN compra.reprovacaotxt; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN compra.reprovacaotxt IS 'Motivo da reprovação da compra';


--
-- TOC entry 2388 (class 0 OID 0)
-- Dependencies: 185
-- Name: COLUMN compra.reprovacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN compra.reprovacao IS 'data da reprovação da compra';


--
-- TOC entry 186 (class 1259 OID 17365)
-- Name: compra_compraid_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE compra_compraid_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE compra_compraid_seq OWNER TO postgres;

--
-- TOC entry 2389 (class 0 OID 0)
-- Dependencies: 186
-- Name: compra_compraid_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE compra_compraid_seq OWNED BY compra.compraid;


--
-- TOC entry 187 (class 1259 OID 17367)
-- Name: departamento; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE departamento (
    departamentoid integer NOT NULL,
    localid integer,
    nome character varying(50)
);


ALTER TABLE departamento OWNER TO postgres;

--
-- TOC entry 188 (class 1259 OID 17370)
-- Name: departamento_departamentoid_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE departamento_departamentoid_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE departamento_departamentoid_seq OWNER TO postgres;

--
-- TOC entry 2390 (class 0 OID 0)
-- Dependencies: 188
-- Name: departamento_departamentoid_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE departamento_departamentoid_seq OWNED BY departamento.departamentoid;


--
-- TOC entry 189 (class 1259 OID 17372)
-- Name: estoquemovimento; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE estoquemovimento (
    estoquemovimentoid integer NOT NULL,
    produtoid integer,
    requisicaoid integer,
    operacao integer,
    quantidade double precision,
    compraid integer,
    valorunitario double precision,
    data date
);


ALTER TABLE estoquemovimento OWNER TO postgres;

--
-- TOC entry 2391 (class 0 OID 0)
-- Dependencies: 189
-- Name: COLUMN estoquemovimento.estoquemovimentoid; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN estoquemovimento.estoquemovimentoid IS 'código do movimento do estoque';


--
-- TOC entry 2392 (class 0 OID 0)
-- Dependencies: 189
-- Name: COLUMN estoquemovimento.produtoid; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN estoquemovimento.produtoid IS 'código do produto';


--
-- TOC entry 2393 (class 0 OID 0)
-- Dependencies: 189
-- Name: COLUMN estoquemovimento.requisicaoid; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN estoquemovimento.requisicaoid IS 'código da requisição';


--
-- TOC entry 2394 (class 0 OID 0)
-- Dependencies: 189
-- Name: COLUMN estoquemovimento.operacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN estoquemovimento.operacao IS '0= aberto na requisição; 1= entrada; 2= saida';


--
-- TOC entry 2395 (class 0 OID 0)
-- Dependencies: 189
-- Name: COLUMN estoquemovimento.quantidade; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN estoquemovimento.quantidade IS 'quantidade de produto no movimento do estoque';


--
-- TOC entry 2396 (class 0 OID 0)
-- Dependencies: 189
-- Name: COLUMN estoquemovimento.compraid; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN estoquemovimento.compraid IS 'código da compra';


--
-- TOC entry 2397 (class 0 OID 0)
-- Dependencies: 189
-- Name: COLUMN estoquemovimento.valorunitario; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN estoquemovimento.valorunitario IS 'Valor unitário do item do movimento';


--
-- TOC entry 190 (class 1259 OID 17375)
-- Name: estoquemovimento_estoquemovimentoid_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE estoquemovimento_estoquemovimentoid_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE estoquemovimento_estoquemovimentoid_seq OWNER TO postgres;

--
-- TOC entry 2398 (class 0 OID 0)
-- Dependencies: 190
-- Name: estoquemovimento_estoquemovimentoid_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE estoquemovimento_estoquemovimentoid_seq OWNED BY estoquemovimento.estoquemovimentoid;


--
-- TOC entry 191 (class 1259 OID 17377)
-- Name: fornecedor; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE fornecedor (
    fornecedorid integer NOT NULL,
    fornecedorgrupoid integer,
    razao character varying(50),
    fantazia character varying(50),
    endereco character varying(50),
    numero character varying(10),
    bairro character varying(50),
    cidadeid integer,
    cnpj_cpf character varying(20),
    inscricao_rg character varying(20),
    telefone character varying(20),
    contato character varying(50),
    datacadastro date,
    observacao character varying(200),
    ativo integer,
    email character varying(100)
);


ALTER TABLE fornecedor OWNER TO postgres;

--
-- TOC entry 2399 (class 0 OID 0)
-- Dependencies: 191
-- Name: COLUMN fornecedor.fornecedorid; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN fornecedor.fornecedorid IS 'código do fornecedor';


--
-- TOC entry 2400 (class 0 OID 0)
-- Dependencies: 191
-- Name: COLUMN fornecedor.fornecedorgrupoid; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN fornecedor.fornecedorgrupoid IS 'código do grupo do fornecedor';


--
-- TOC entry 2401 (class 0 OID 0)
-- Dependencies: 191
-- Name: COLUMN fornecedor.razao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN fornecedor.razao IS 'razão social do fornecedor';


--
-- TOC entry 2402 (class 0 OID 0)
-- Dependencies: 191
-- Name: COLUMN fornecedor.fantazia; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN fornecedor.fantazia IS 'nome fantazia do fornecedor';


--
-- TOC entry 2403 (class 0 OID 0)
-- Dependencies: 191
-- Name: COLUMN fornecedor.endereco; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN fornecedor.endereco IS 'endereço do fornecedor';


--
-- TOC entry 2404 (class 0 OID 0)
-- Dependencies: 191
-- Name: COLUMN fornecedor.numero; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN fornecedor.numero IS 'número do fornecedor';


--
-- TOC entry 2405 (class 0 OID 0)
-- Dependencies: 191
-- Name: COLUMN fornecedor.bairro; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN fornecedor.bairro IS 'bairro do fornecedor';


--
-- TOC entry 2406 (class 0 OID 0)
-- Dependencies: 191
-- Name: COLUMN fornecedor.cidadeid; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN fornecedor.cidadeid IS 'código da cidade';


--
-- TOC entry 2407 (class 0 OID 0)
-- Dependencies: 191
-- Name: COLUMN fornecedor.cnpj_cpf; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN fornecedor.cnpj_cpf IS 'cnpj ou cpf do fornecedor';


--
-- TOC entry 2408 (class 0 OID 0)
-- Dependencies: 191
-- Name: COLUMN fornecedor.inscricao_rg; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN fornecedor.inscricao_rg IS 'inscrição estadual ou RG do fornecedor';


--
-- TOC entry 2409 (class 0 OID 0)
-- Dependencies: 191
-- Name: COLUMN fornecedor.telefone; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN fornecedor.telefone IS 'telefone do fornecedor';


--
-- TOC entry 2410 (class 0 OID 0)
-- Dependencies: 191
-- Name: COLUMN fornecedor.contato; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN fornecedor.contato IS 'nome da pessoa pra entrar em contato pelo telefone';


--
-- TOC entry 2411 (class 0 OID 0)
-- Dependencies: 191
-- Name: COLUMN fornecedor.datacadastro; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN fornecedor.datacadastro IS 'data do cadastro do fornecedor';


--
-- TOC entry 2412 (class 0 OID 0)
-- Dependencies: 191
-- Name: COLUMN fornecedor.observacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN fornecedor.observacao IS 'obersavação sobre o fornecedor';


--
-- TOC entry 2413 (class 0 OID 0)
-- Dependencies: 191
-- Name: COLUMN fornecedor.ativo; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN fornecedor.ativo IS '1= ativo; 0= inativo';


--
-- TOC entry 2414 (class 0 OID 0)
-- Dependencies: 191
-- Name: COLUMN fornecedor.email; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN fornecedor.email IS 'email do fornecedor';


--
-- TOC entry 192 (class 1259 OID 17383)
-- Name: fornecedor_fornecedorid_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE fornecedor_fornecedorid_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE fornecedor_fornecedorid_seq OWNER TO postgres;

--
-- TOC entry 2415 (class 0 OID 0)
-- Dependencies: 192
-- Name: fornecedor_fornecedorid_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE fornecedor_fornecedorid_seq OWNED BY fornecedor.fornecedorid;


--
-- TOC entry 193 (class 1259 OID 17385)
-- Name: fornecedorgrupo; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE fornecedorgrupo (
    fornecedorgrupoid integer NOT NULL,
    nome character varying(50)
);


ALTER TABLE fornecedorgrupo OWNER TO postgres;

--
-- TOC entry 2416 (class 0 OID 0)
-- Dependencies: 193
-- Name: COLUMN fornecedorgrupo.fornecedorgrupoid; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN fornecedorgrupo.fornecedorgrupoid IS 'código do grupo de fornecedor';


--
-- TOC entry 2417 (class 0 OID 0)
-- Dependencies: 193
-- Name: COLUMN fornecedorgrupo.nome; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN fornecedorgrupo.nome IS 'nome do grupo de fornecedores';


--
-- TOC entry 194 (class 1259 OID 17388)
-- Name: fornecedorgrupo_fornecedorgrupoid_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE fornecedorgrupo_fornecedorgrupoid_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE fornecedorgrupo_fornecedorgrupoid_seq OWNER TO postgres;

--
-- TOC entry 2418 (class 0 OID 0)
-- Dependencies: 194
-- Name: fornecedorgrupo_fornecedorgrupoid_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE fornecedorgrupo_fornecedorgrupoid_seq OWNED BY fornecedorgrupo.fornecedorgrupoid;


--
-- TOC entry 195 (class 1259 OID 17390)
-- Name: fornecedorproduto; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE fornecedorproduto (
    fornecedorid integer NOT NULL,
    produtoid integer NOT NULL
);


ALTER TABLE fornecedorproduto OWNER TO postgres;

--
-- TOC entry 2419 (class 0 OID 0)
-- Dependencies: 195
-- Name: COLUMN fornecedorproduto.fornecedorid; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN fornecedorproduto.fornecedorid IS 'código do fornecedor';


--
-- TOC entry 2420 (class 0 OID 0)
-- Dependencies: 195
-- Name: COLUMN fornecedorproduto.produtoid; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN fornecedorproduto.produtoid IS 'código do produto';


--
-- TOC entry 196 (class 1259 OID 17393)
-- Name: local; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE local (
    localid integer NOT NULL,
    nome character varying(50)
);


ALTER TABLE local OWNER TO postgres;

--
-- TOC entry 197 (class 1259 OID 17396)
-- Name: local_localid_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE local_localid_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE local_localid_seq OWNER TO postgres;

--
-- TOC entry 2421 (class 0 OID 0)
-- Dependencies: 197
-- Name: local_localid_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE local_localid_seq OWNED BY local.localid;


--
-- TOC entry 198 (class 1259 OID 17398)
-- Name: log; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE log (
    logid integer NOT NULL,
    usuarioid integer,
    descricao text,
    data timestamp without time zone,
    cidadeid integer
);


ALTER TABLE log OWNER TO postgres;

--
-- TOC entry 2422 (class 0 OID 0)
-- Dependencies: 198
-- Name: COLUMN log.usuarioid; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN log.usuarioid IS 'código do usuário';


--
-- TOC entry 2423 (class 0 OID 0)
-- Dependencies: 198
-- Name: COLUMN log.descricao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN log.descricao IS 'descrição do log';


--
-- TOC entry 2424 (class 0 OID 0)
-- Dependencies: 198
-- Name: COLUMN log.data; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN log.data IS 'data  e hora do log';


--
-- TOC entry 199 (class 1259 OID 17404)
-- Name: log_logid_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE log_logid_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE log_logid_seq OWNER TO postgres;

--
-- TOC entry 2425 (class 0 OID 0)
-- Dependencies: 199
-- Name: log_logid_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE log_logid_seq OWNED BY log.logid;


--
-- TOC entry 200 (class 1259 OID 17406)
-- Name: marca; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE marca (
    marcaid integer NOT NULL,
    nome character varying
);


ALTER TABLE marca OWNER TO postgres;

--
-- TOC entry 201 (class 1259 OID 17412)
-- Name: marca_marcaid_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE marca_marcaid_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE marca_marcaid_seq OWNER TO postgres;

--
-- TOC entry 2426 (class 0 OID 0)
-- Dependencies: 201
-- Name: marca_marcaid_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE marca_marcaid_seq OWNED BY marca.marcaid;


--
-- TOC entry 202 (class 1259 OID 17414)
-- Name: patrimonio; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE patrimonio (
    fornecedorid integer,
    patrimonioid integer NOT NULL,
    serie character varying(40),
    datacompra date,
    notafiscal integer,
    fimgarantia date,
    valor double precision,
    dataimplantacao date,
    estadoconservacao character varying(20),
    obs text,
    departamentoid integer,
    produtoid integer
);


ALTER TABLE patrimonio OWNER TO postgres;

--
-- TOC entry 2427 (class 0 OID 0)
-- Dependencies: 202
-- Name: COLUMN patrimonio.serie; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN patrimonio.serie IS '
';


--
-- TOC entry 203 (class 1259 OID 17420)
-- Name: produto; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE produto (
    produtoid integer NOT NULL,
    produtosubgrupoid integer,
    nome character varying(100),
    und character varying(10),
    customedio double precision,
    codigobarras integer,
    validade date,
    observacoes character varying(200),
    ativo integer DEFAULT 0,
    estoqueminimo double precision,
    estoquemaximo double precision,
    estoqueatual double precision,
    produtogrupoid integer,
    marcaid integer
);


ALTER TABLE produto OWNER TO postgres;

--
-- TOC entry 2428 (class 0 OID 0)
-- Dependencies: 203
-- Name: COLUMN produto.produtoid; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN produto.produtoid IS 'código do produto';


--
-- TOC entry 2429 (class 0 OID 0)
-- Dependencies: 203
-- Name: COLUMN produto.produtosubgrupoid; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN produto.produtosubgrupoid IS 'código do subgrupo do produto';


--
-- TOC entry 2430 (class 0 OID 0)
-- Dependencies: 203
-- Name: COLUMN produto.nome; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN produto.nome IS 'nome do produto';


--
-- TOC entry 2431 (class 0 OID 0)
-- Dependencies: 203
-- Name: COLUMN produto.und; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN produto.und IS 'unidade de medida do produto';


--
-- TOC entry 2432 (class 0 OID 0)
-- Dependencies: 203
-- Name: COLUMN produto.customedio; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN produto.customedio IS 'custo médio do produto';


--
-- TOC entry 2433 (class 0 OID 0)
-- Dependencies: 203
-- Name: COLUMN produto.codigobarras; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN produto.codigobarras IS 'código de barras do produto';


--
-- TOC entry 2434 (class 0 OID 0)
-- Dependencies: 203
-- Name: COLUMN produto.validade; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN produto.validade IS 'validade do produto';


--
-- TOC entry 2435 (class 0 OID 0)
-- Dependencies: 203
-- Name: COLUMN produto.observacoes; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN produto.observacoes IS 'obervações sobre o produto';


--
-- TOC entry 2436 (class 0 OID 0)
-- Dependencies: 203
-- Name: COLUMN produto.ativo; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN produto.ativo IS '1= ativo; 0= inativo';


--
-- TOC entry 2437 (class 0 OID 0)
-- Dependencies: 203
-- Name: COLUMN produto.estoqueminimo; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN produto.estoqueminimo IS 'estoque minimo do produto';


--
-- TOC entry 2438 (class 0 OID 0)
-- Dependencies: 203
-- Name: COLUMN produto.estoquemaximo; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN produto.estoquemaximo IS 'estoque máximo do produto';


--
-- TOC entry 2439 (class 0 OID 0)
-- Dependencies: 203
-- Name: COLUMN produto.estoqueatual; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN produto.estoqueatual IS 'estoque atual do produto';


--
-- TOC entry 2440 (class 0 OID 0)
-- Dependencies: 203
-- Name: COLUMN produto.produtogrupoid; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN produto.produtogrupoid IS 'Código do grupo de produtos';


--
-- TOC entry 204 (class 1259 OID 17424)
-- Name: produto_produtoid_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE produto_produtoid_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE produto_produtoid_seq OWNER TO postgres;

--
-- TOC entry 2441 (class 0 OID 0)
-- Dependencies: 204
-- Name: produto_produtoid_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE produto_produtoid_seq OWNED BY produto.produtoid;


--
-- TOC entry 205 (class 1259 OID 17426)
-- Name: produtogrupo; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE produtogrupo (
    produtogrupoid integer NOT NULL,
    nome character varying(100)
);


ALTER TABLE produtogrupo OWNER TO postgres;

--
-- TOC entry 2442 (class 0 OID 0)
-- Dependencies: 205
-- Name: COLUMN produtogrupo.produtogrupoid; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN produtogrupo.produtogrupoid IS 'código do grupo de produto';


--
-- TOC entry 2443 (class 0 OID 0)
-- Dependencies: 205
-- Name: COLUMN produtogrupo.nome; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN produtogrupo.nome IS 'nome do grupo de produto';


--
-- TOC entry 206 (class 1259 OID 17429)
-- Name: produtogrupo_produtogrupoid_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE produtogrupo_produtogrupoid_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE produtogrupo_produtogrupoid_seq OWNER TO postgres;

--
-- TOC entry 2444 (class 0 OID 0)
-- Dependencies: 206
-- Name: produtogrupo_produtogrupoid_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE produtogrupo_produtogrupoid_seq OWNED BY produtogrupo.produtogrupoid;


--
-- TOC entry 207 (class 1259 OID 17431)
-- Name: produtosubgrupo; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE produtosubgrupo (
    produtosubgrupoid integer NOT NULL,
    produtogrupoid integer,
    nome character varying(100)
);


ALTER TABLE produtosubgrupo OWNER TO postgres;

--
-- TOC entry 2445 (class 0 OID 0)
-- Dependencies: 207
-- Name: COLUMN produtosubgrupo.produtosubgrupoid; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN produtosubgrupo.produtosubgrupoid IS 'código do subgrupo de produto';


--
-- TOC entry 2446 (class 0 OID 0)
-- Dependencies: 207
-- Name: COLUMN produtosubgrupo.produtogrupoid; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN produtosubgrupo.produtogrupoid IS 'código do grupo de produto';


--
-- TOC entry 2447 (class 0 OID 0)
-- Dependencies: 207
-- Name: COLUMN produtosubgrupo.nome; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN produtosubgrupo.nome IS 'nome do subgrupo de produto';


--
-- TOC entry 208 (class 1259 OID 17434)
-- Name: produtosubgrupo_produtosubgrupoid_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE produtosubgrupo_produtosubgrupoid_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE produtosubgrupo_produtosubgrupoid_seq OWNER TO postgres;

--
-- TOC entry 2448 (class 0 OID 0)
-- Dependencies: 208
-- Name: produtosubgrupo_produtosubgrupoid_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE produtosubgrupo_produtosubgrupoid_seq OWNED BY produtosubgrupo.produtosubgrupoid;


--
-- TOC entry 209 (class 1259 OID 17436)
-- Name: requisicao; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE requisicao (
    requisicaoid integer NOT NULL,
    usuarioid integer,
    emissao date,
    aprovacao date,
    entrega date,
    situacao integer,
    reprovacaotxt text,
    reprovacao date
);


ALTER TABLE requisicao OWNER TO postgres;

--
-- TOC entry 2449 (class 0 OID 0)
-- Dependencies: 209
-- Name: COLUMN requisicao.requisicaoid; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN requisicao.requisicaoid IS 'código da requisição';


--
-- TOC entry 2450 (class 0 OID 0)
-- Dependencies: 209
-- Name: COLUMN requisicao.usuarioid; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN requisicao.usuarioid IS 'código usuário/requisitante';


--
-- TOC entry 2451 (class 0 OID 0)
-- Dependencies: 209
-- Name: COLUMN requisicao.emissao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN requisicao.emissao IS 'data da emissão da requisição';


--
-- TOC entry 2452 (class 0 OID 0)
-- Dependencies: 209
-- Name: COLUMN requisicao.aprovacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN requisicao.aprovacao IS 'data da aprovação da requisição';


--
-- TOC entry 2453 (class 0 OID 0)
-- Dependencies: 209
-- Name: COLUMN requisicao.entrega; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN requisicao.entrega IS 'data da entrega da requisição';


--
-- TOC entry 2454 (class 0 OID 0)
-- Dependencies: 209
-- Name: COLUMN requisicao.situacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN requisicao.situacao IS '1= aberta; 2= aprovada; 3= entregue; 4= reprovada; 5= cancelada.';


--
-- TOC entry 2455 (class 0 OID 0)
-- Dependencies: 209
-- Name: COLUMN requisicao.reprovacaotxt; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN requisicao.reprovacaotxt IS 'motivo da reprovação';


--
-- TOC entry 2456 (class 0 OID 0)
-- Dependencies: 209
-- Name: COLUMN requisicao.reprovacao; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN requisicao.reprovacao IS 'data da reprovacao';


--
-- TOC entry 210 (class 1259 OID 17442)
-- Name: requisicao_requisicaoid_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE requisicao_requisicaoid_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE requisicao_requisicaoid_seq OWNER TO postgres;

--
-- TOC entry 2457 (class 0 OID 0)
-- Dependencies: 210
-- Name: requisicao_requisicaoid_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE requisicao_requisicaoid_seq OWNED BY requisicao.requisicaoid;


--
-- TOC entry 211 (class 1259 OID 17444)
-- Name: rone_auditoria; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE rone_auditoria (
    usuario_login character varying(20) NOT NULL,
    ip character varying(60),
    classe character varying(60),
    data timestamp without time zone,
    funcao character(3),
    historico text,
    auditoria_id integer NOT NULL
);


ALTER TABLE rone_auditoria OWNER TO postgres;

--
-- TOC entry 212 (class 1259 OID 17450)
-- Name: rone_auditoria_auditoria_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE rone_auditoria_auditoria_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE rone_auditoria_auditoria_id_seq OWNER TO postgres;

--
-- TOC entry 2458 (class 0 OID 0)
-- Dependencies: 212
-- Name: rone_auditoria_auditoria_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE rone_auditoria_auditoria_id_seq OWNED BY rone_auditoria.auditoria_id;


--
-- TOC entry 213 (class 1259 OID 17452)
-- Name: rone_favoritos; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE rone_favoritos (
    usuario_id integer NOT NULL,
    programa character varying(120) NOT NULL
);


ALTER TABLE rone_favoritos OWNER TO postgres;

--
-- TOC entry 214 (class 1259 OID 17455)
-- Name: rone_grupo; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE rone_grupo (
    grupo_id integer NOT NULL,
    grupo_nome character varying(200)
);


ALTER TABLE rone_grupo OWNER TO postgres;

--
-- TOC entry 215 (class 1259 OID 17458)
-- Name: rone_grupo_grupo_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE rone_grupo_grupo_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE rone_grupo_grupo_id_seq OWNER TO postgres;

--
-- TOC entry 2459 (class 0 OID 0)
-- Dependencies: 215
-- Name: rone_grupo_grupo_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE rone_grupo_grupo_id_seq OWNED BY rone_grupo.grupo_id;


--
-- TOC entry 216 (class 1259 OID 17460)
-- Name: rone_grupousuario; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE rone_grupousuario (
    usuario_id integer NOT NULL,
    grupo_id integer NOT NULL
);


ALTER TABLE rone_grupousuario OWNER TO postgres;

--
-- TOC entry 217 (class 1259 OID 17463)
-- Name: rone_historico; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE rone_historico (
    usuario_id integer NOT NULL,
    programa character varying(120) NOT NULL,
    acessos integer DEFAULT 1
);


ALTER TABLE rone_historico OWNER TO postgres;

--
-- TOC entry 218 (class 1259 OID 17467)
-- Name: rone_menu; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE rone_menu (
    menu_desc character varying(30) NOT NULL,
    menu_prog character varying(120) DEFAULT '<menu>'::character varying NOT NULL,
    menu_icon character varying(120),
    menu_pai integer DEFAULT 0,
    menu_id integer NOT NULL,
    menu_ordem integer DEFAULT 9999
);


ALTER TABLE rone_menu OWNER TO postgres;

--
-- TOC entry 219 (class 1259 OID 17473)
-- Name: rone_menu_menu_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE rone_menu_menu_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE rone_menu_menu_id_seq OWNER TO postgres;

--
-- TOC entry 2460 (class 0 OID 0)
-- Dependencies: 219
-- Name: rone_menu_menu_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE rone_menu_menu_id_seq OWNED BY rone_menu.menu_id;


--
-- TOC entry 220 (class 1259 OID 17475)
-- Name: rone_permissao; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE rone_permissao (
    usuario_id integer DEFAULT 0 NOT NULL,
    menu_id integer NOT NULL,
    permissao_modo character(3) DEFAULT 'NEG'::bpchar
);


ALTER TABLE rone_permissao OWNER TO postgres;

--
-- TOC entry 221 (class 1259 OID 17480)
-- Name: rone_tab_teste; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE rone_tab_teste (
    codigo integer DEFAULT 0 NOT NULL,
    descricao character varying(20),
    valor double precision
);


ALTER TABLE rone_tab_teste OWNER TO postgres;

--
-- TOC entry 222 (class 1259 OID 17484)
-- Name: rone_usuario; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE rone_usuario (
    usuario_nome character varying(60) NOT NULL,
    usuario_login character varying(20) NOT NULL,
    usuario_senha character varying(30) NOT NULL,
    usuario_email character varying(120),
    usuario_ativo integer DEFAULT 1,
    usuario_id integer NOT NULL
);


ALTER TABLE rone_usuario OWNER TO postgres;

--
-- TOC entry 223 (class 1259 OID 17488)
-- Name: rone_usuario_usuario_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE rone_usuario_usuario_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE rone_usuario_usuario_id_seq OWNER TO postgres;

--
-- TOC entry 2461 (class 0 OID 0)
-- Dependencies: 223
-- Name: rone_usuario_usuario_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE rone_usuario_usuario_id_seq OWNED BY rone_usuario.usuario_id;


--
-- TOC entry 224 (class 1259 OID 17490)
-- Name: tema; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE tema (
    temaid integer NOT NULL,
    nome character varying(50),
    link character varying(100),
    img character varying(100)
);


ALTER TABLE tema OWNER TO postgres;

--
-- TOC entry 225 (class 1259 OID 17493)
-- Name: tema_temaid_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE tema_temaid_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE tema_temaid_seq OWNER TO postgres;

--
-- TOC entry 2462 (class 0 OID 0)
-- Dependencies: 225
-- Name: tema_temaid_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE tema_temaid_seq OWNED BY tema.temaid;


--
-- TOC entry 226 (class 1259 OID 17495)
-- Name: usuario; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE usuario (
    usuarioid integer NOT NULL,
    nome character varying(50),
    login character varying(8),
    senha character varying(8),
    ativo integer DEFAULT 0,
    email character varying(50),
    tipousuario integer,
    departamentoid integer,
    temaid integer DEFAULT 1
);


ALTER TABLE usuario OWNER TO postgres;

--
-- TOC entry 2463 (class 0 OID 0)
-- Dependencies: 226
-- Name: COLUMN usuario.usuarioid; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN usuario.usuarioid IS 'Código do usuário';


--
-- TOC entry 2464 (class 0 OID 0)
-- Dependencies: 226
-- Name: COLUMN usuario.nome; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN usuario.nome IS 'Nome do usuário';


--
-- TOC entry 2465 (class 0 OID 0)
-- Dependencies: 226
-- Name: COLUMN usuario.login; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN usuario.login IS 'login do usuário';


--
-- TOC entry 2466 (class 0 OID 0)
-- Dependencies: 226
-- Name: COLUMN usuario.senha; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN usuario.senha IS 'senha do usuário';


--
-- TOC entry 2467 (class 0 OID 0)
-- Dependencies: 226
-- Name: COLUMN usuario.ativo; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN usuario.ativo IS '0 = inativo; 1 = ativo';


--
-- TOC entry 2468 (class 0 OID 0)
-- Dependencies: 226
-- Name: COLUMN usuario.email; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN usuario.email IS 'email do usuário';


--
-- TOC entry 2469 (class 0 OID 0)
-- Dependencies: 226
-- Name: COLUMN usuario.tipousuario; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN usuario.tipousuario IS 'Almoxarife/Administrador = 1; Requisitante = 2';


--
-- TOC entry 2470 (class 0 OID 0)
-- Dependencies: 226
-- Name: COLUMN usuario.departamentoid; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN usuario.departamentoid IS 'código do departamento';


--
-- TOC entry 227 (class 1259 OID 17500)
-- Name: usuario_usuarioid_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE usuario_usuarioid_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE usuario_usuarioid_seq OWNER TO postgres;

--
-- TOC entry 2471 (class 0 OID 0)
-- Dependencies: 227
-- Name: usuario_usuarioid_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE usuario_usuarioid_seq OWNED BY usuario.usuarioid;


--
-- TOC entry 2142 (class 2604 OID 17502)
-- Name: categoriaid; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY categoria ALTER COLUMN categoriaid SET DEFAULT nextval('categoria_categoriaid_seq'::regclass);


--
-- TOC entry 2143 (class 2604 OID 17503)
-- Name: cidadeid; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY cidade ALTER COLUMN cidadeid SET DEFAULT nextval('cidade_cidadeid_seq'::regclass);


--
-- TOC entry 2145 (class 2604 OID 17504)
-- Name: compraid; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY compra ALTER COLUMN compraid SET DEFAULT nextval('compra_compraid_seq'::regclass);


--
-- TOC entry 2146 (class 2604 OID 17505)
-- Name: departamentoid; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY departamento ALTER COLUMN departamentoid SET DEFAULT nextval('departamento_departamentoid_seq'::regclass);


--
-- TOC entry 2147 (class 2604 OID 17506)
-- Name: estoquemovimentoid; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY estoquemovimento ALTER COLUMN estoquemovimentoid SET DEFAULT nextval('estoquemovimento_estoquemovimentoid_seq'::regclass);


--
-- TOC entry 2148 (class 2604 OID 17507)
-- Name: fornecedorid; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY fornecedor ALTER COLUMN fornecedorid SET DEFAULT nextval('fornecedor_fornecedorid_seq'::regclass);


--
-- TOC entry 2149 (class 2604 OID 17508)
-- Name: fornecedorgrupoid; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY fornecedorgrupo ALTER COLUMN fornecedorgrupoid SET DEFAULT nextval('fornecedorgrupo_fornecedorgrupoid_seq'::regclass);


--
-- TOC entry 2150 (class 2604 OID 17509)
-- Name: localid; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY local ALTER COLUMN localid SET DEFAULT nextval('local_localid_seq'::regclass);


--
-- TOC entry 2151 (class 2604 OID 17510)
-- Name: logid; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY log ALTER COLUMN logid SET DEFAULT nextval('log_logid_seq'::regclass);


--
-- TOC entry 2152 (class 2604 OID 17511)
-- Name: marcaid; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY marca ALTER COLUMN marcaid SET DEFAULT nextval('marca_marcaid_seq'::regclass);


--
-- TOC entry 2154 (class 2604 OID 17512)
-- Name: produtoid; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY produto ALTER COLUMN produtoid SET DEFAULT nextval('produto_produtoid_seq'::regclass);


--
-- TOC entry 2155 (class 2604 OID 17513)
-- Name: produtogrupoid; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY produtogrupo ALTER COLUMN produtogrupoid SET DEFAULT nextval('produtogrupo_produtogrupoid_seq'::regclass);


--
-- TOC entry 2156 (class 2604 OID 17514)
-- Name: produtosubgrupoid; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY produtosubgrupo ALTER COLUMN produtosubgrupoid SET DEFAULT nextval('produtosubgrupo_produtosubgrupoid_seq'::regclass);


--
-- TOC entry 2157 (class 2604 OID 17515)
-- Name: requisicaoid; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY requisicao ALTER COLUMN requisicaoid SET DEFAULT nextval('requisicao_requisicaoid_seq'::regclass);


--
-- TOC entry 2158 (class 2604 OID 17516)
-- Name: auditoria_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY rone_auditoria ALTER COLUMN auditoria_id SET DEFAULT nextval('rone_auditoria_auditoria_id_seq'::regclass);


--
-- TOC entry 2159 (class 2604 OID 17517)
-- Name: grupo_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY rone_grupo ALTER COLUMN grupo_id SET DEFAULT nextval('rone_grupo_grupo_id_seq'::regclass);


--
-- TOC entry 2164 (class 2604 OID 17518)
-- Name: menu_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY rone_menu ALTER COLUMN menu_id SET DEFAULT nextval('rone_menu_menu_id_seq'::regclass);


--
-- TOC entry 2169 (class 2604 OID 17519)
-- Name: usuario_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY rone_usuario ALTER COLUMN usuario_id SET DEFAULT nextval('rone_usuario_usuario_id_seq'::regclass);


--
-- TOC entry 2170 (class 2604 OID 17520)
-- Name: temaid; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY tema ALTER COLUMN temaid SET DEFAULT nextval('tema_temaid_seq'::regclass);


--
-- TOC entry 2173 (class 2604 OID 17521)
-- Name: usuarioid; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY usuario ALTER COLUMN usuarioid SET DEFAULT nextval('usuario_usuarioid_seq'::regclass);


--
-- TOC entry 2207 (class 2606 OID 17523)
-- Name: auditoria_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY rone_auditoria
    ADD CONSTRAINT auditoria_pkey PRIMARY KEY (auditoria_id);


--
-- TOC entry 2175 (class 2606 OID 17525)
-- Name: categoriaid_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY categoria
    ADD CONSTRAINT categoriaid_pkey PRIMARY KEY (categoriaid);


--
-- TOC entry 2221 (class 2606 OID 17527)
-- Name: chv_login; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY rone_usuario
    ADD CONSTRAINT chv_login UNIQUE (usuario_login);


--
-- TOC entry 2177 (class 2606 OID 17529)
-- Name: cidade_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY cidade
    ADD CONSTRAINT cidade_pkey PRIMARY KEY (cidadeid);


--
-- TOC entry 2179 (class 2606 OID 17531)
-- Name: compra_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY compra
    ADD CONSTRAINT compra_pkey PRIMARY KEY (compraid);


--
-- TOC entry 2181 (class 2606 OID 17533)
-- Name: departamento_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY departamento
    ADD CONSTRAINT departamento_pkey PRIMARY KEY (departamentoid);


--
-- TOC entry 2183 (class 2606 OID 17535)
-- Name: estoquemovimento_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY estoquemovimento
    ADD CONSTRAINT estoquemovimento_pkey PRIMARY KEY (estoquemovimentoid);


--
-- TOC entry 2209 (class 2606 OID 17537)
-- Name: favoritos_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY rone_favoritos
    ADD CONSTRAINT favoritos_pkey PRIMARY KEY (usuario_id, programa);


--
-- TOC entry 2185 (class 2606 OID 17539)
-- Name: fornecedor_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY fornecedor
    ADD CONSTRAINT fornecedor_pkey PRIMARY KEY (fornecedorid);


--
-- TOC entry 2189 (class 2606 OID 17541)
-- Name: fornecedorproduto_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY fornecedorproduto
    ADD CONSTRAINT fornecedorproduto_pkey PRIMARY KEY (fornecedorid, produtoid);


--
-- TOC entry 2211 (class 2606 OID 17543)
-- Name: grupo_id_fkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY rone_grupo
    ADD CONSTRAINT grupo_id_fkey PRIMARY KEY (grupo_id);


--
-- TOC entry 2187 (class 2606 OID 17545)
-- Name: grupofornecedor_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY fornecedorgrupo
    ADD CONSTRAINT grupofornecedor_pkey PRIMARY KEY (fornecedorgrupoid);


--
-- TOC entry 2213 (class 2606 OID 17547)
-- Name: historico_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY rone_historico
    ADD CONSTRAINT historico_pkey PRIMARY KEY (usuario_id, programa);


--
-- TOC entry 2191 (class 2606 OID 17549)
-- Name: local_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY local
    ADD CONSTRAINT local_pkey PRIMARY KEY (localid);


--
-- TOC entry 2193 (class 2606 OID 17551)
-- Name: log_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY log
    ADD CONSTRAINT log_pkey PRIMARY KEY (logid);


--
-- TOC entry 2195 (class 2606 OID 17553)
-- Name: marca_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY marca
    ADD CONSTRAINT marca_pkey PRIMARY KEY (marcaid);


--
-- TOC entry 2215 (class 2606 OID 17555)
-- Name: menu_id_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY rone_menu
    ADD CONSTRAINT menu_id_pkey PRIMARY KEY (menu_id);


--
-- TOC entry 2197 (class 2606 OID 17557)
-- Name: patrimonio_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY patrimonio
    ADD CONSTRAINT patrimonio_pkey PRIMARY KEY (patrimonioid);


--
-- TOC entry 2217 (class 2606 OID 17559)
-- Name: permissao_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY rone_permissao
    ADD CONSTRAINT permissao_pkey PRIMARY KEY (usuario_id, menu_id);


--
-- TOC entry 2199 (class 2606 OID 17561)
-- Name: produto_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY produto
    ADD CONSTRAINT produto_pkey PRIMARY KEY (produtoid);


--
-- TOC entry 2201 (class 2606 OID 17563)
-- Name: produtogrupo_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY produtogrupo
    ADD CONSTRAINT produtogrupo_pkey PRIMARY KEY (produtogrupoid);


--
-- TOC entry 2203 (class 2606 OID 17565)
-- Name: produtosubgrupo_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY produtosubgrupo
    ADD CONSTRAINT produtosubgrupo_pkey PRIMARY KEY (produtosubgrupoid);


--
-- TOC entry 2205 (class 2606 OID 17567)
-- Name: requisicao_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY requisicao
    ADD CONSTRAINT requisicao_pkey PRIMARY KEY (requisicaoid);


--
-- TOC entry 2223 (class 2606 OID 17569)
-- Name: rone_usuario_id; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY rone_usuario
    ADD CONSTRAINT rone_usuario_id PRIMARY KEY (usuario_id);


--
-- TOC entry 2219 (class 2606 OID 17571)
-- Name: tab_teste_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY rone_tab_teste
    ADD CONSTRAINT tab_teste_pkey PRIMARY KEY (codigo);


--
-- TOC entry 2225 (class 2606 OID 17573)
-- Name: temaid_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY tema
    ADD CONSTRAINT temaid_pkey PRIMARY KEY (temaid);


--
-- TOC entry 2227 (class 2606 OID 17575)
-- Name: usuario_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY usuario
    ADD CONSTRAINT usuario_pkey PRIMARY KEY (usuarioid);


--
-- TOC entry 2233 (class 2606 OID 17581)
-- Name: cidade_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY fornecedor
    ADD CONSTRAINT cidade_fkey FOREIGN KEY (cidadeid) REFERENCES cidade(cidadeid);


--
-- TOC entry 2237 (class 2606 OID 17586)
-- Name: cidade_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY log
    ADD CONSTRAINT cidade_fkey FOREIGN KEY (cidadeid) REFERENCES cidade(cidadeid);


--
-- TOC entry 2230 (class 2606 OID 17591)
-- Name: compra_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY estoquemovimento
    ADD CONSTRAINT compra_fkey FOREIGN KEY (compraid) REFERENCES compra(compraid) ON DELETE CASCADE;


--
-- TOC entry 2250 (class 2606 OID 17596)
-- Name: departamento_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY usuario
    ADD CONSTRAINT departamento_fkey FOREIGN KEY (departamentoid) REFERENCES departamento(departamentoid);


--
-- TOC entry 2239 (class 2606 OID 17601)
-- Name: departamento_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY patrimonio
    ADD CONSTRAINT departamento_fkey FOREIGN KEY (departamentoid) REFERENCES departamento(departamentoid);


--
-- TOC entry 2235 (class 2606 OID 17606)
-- Name: fornecedor_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY fornecedorproduto
    ADD CONSTRAINT fornecedor_fkey FOREIGN KEY (fornecedorid) REFERENCES fornecedor(fornecedorid);


--
-- TOC entry 2228 (class 2606 OID 17611)
-- Name: fornecedor_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY compra
    ADD CONSTRAINT fornecedor_fkey FOREIGN KEY (fornecedorid) REFERENCES fornecedor(fornecedorid);


--
-- TOC entry 2234 (class 2606 OID 17616)
-- Name: fornecedorgrupo_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY fornecedor
    ADD CONSTRAINT fornecedorgrupo_fkey FOREIGN KEY (fornecedorgrupoid) REFERENCES fornecedorgrupo(fornecedorgrupoid);


--
-- TOC entry 2240 (class 2606 OID 17621)
-- Name: fornecedorid_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY patrimonio
    ADD CONSTRAINT fornecedorid_fkey FOREIGN KEY (fornecedorid) REFERENCES fornecedor(fornecedorid);


--
-- TOC entry 2247 (class 2606 OID 17626)
-- Name: grupoid_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY rone_grupousuario
    ADD CONSTRAINT grupoid_fkey FOREIGN KEY (grupo_id) REFERENCES rone_grupo(grupo_id);


--
-- TOC entry 2229 (class 2606 OID 17631)
-- Name: local_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY departamento
    ADD CONSTRAINT local_fkey FOREIGN KEY (localid) REFERENCES local(localid);


--
-- TOC entry 2244 (class 2606 OID 17698)
-- Name: marcaid_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY produto
    ADD CONSTRAINT marcaid_fkey FOREIGN KEY (marcaid) REFERENCES marca(marcaid);


--
-- TOC entry 2249 (class 2606 OID 17641)
-- Name: permissao_menu_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY rone_permissao
    ADD CONSTRAINT permissao_menu_id_fkey FOREIGN KEY (menu_id) REFERENCES rone_menu(menu_id) ON DELETE CASCADE;


--
-- TOC entry 2236 (class 2606 OID 17646)
-- Name: produto_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY fornecedorproduto
    ADD CONSTRAINT produto_fkey FOREIGN KEY (produtoid) REFERENCES produto(produtoid);


--
-- TOC entry 2231 (class 2606 OID 17651)
-- Name: produto_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY estoquemovimento
    ADD CONSTRAINT produto_fkey FOREIGN KEY (produtoid) REFERENCES produto(produtoid);


--
-- TOC entry 2245 (class 2606 OID 17656)
-- Name: produtogrupo_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY produtosubgrupo
    ADD CONSTRAINT produtogrupo_fkey FOREIGN KEY (produtogrupoid) REFERENCES produtogrupo(produtogrupoid);


--
-- TOC entry 2242 (class 2606 OID 17661)
-- Name: produtogrupo_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY produto
    ADD CONSTRAINT produtogrupo_fkey FOREIGN KEY (produtogrupoid) REFERENCES produtogrupo(produtogrupoid);


--
-- TOC entry 2241 (class 2606 OID 17703)
-- Name: produtoid_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY patrimonio
    ADD CONSTRAINT produtoid_fkey FOREIGN KEY (produtoid) REFERENCES produto(produtoid);


--
-- TOC entry 2243 (class 2606 OID 17666)
-- Name: produtosubgurpo_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY produto
    ADD CONSTRAINT produtosubgurpo_fkey FOREIGN KEY (produtosubgrupoid) REFERENCES produtosubgrupo(produtosubgrupoid);


--
-- TOC entry 2232 (class 2606 OID 17671)
-- Name: requisicao_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY estoquemovimento
    ADD CONSTRAINT requisicao_fkey FOREIGN KEY (requisicaoid) REFERENCES requisicao(requisicaoid) ON DELETE CASCADE;


--
-- TOC entry 2251 (class 2606 OID 17676)
-- Name: temaid_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY usuario
    ADD CONSTRAINT temaid_fkey FOREIGN KEY (temaid) REFERENCES tema(temaid);


--
-- TOC entry 2238 (class 2606 OID 17681)
-- Name: usuario_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY log
    ADD CONSTRAINT usuario_fkey FOREIGN KEY (usuarioid) REFERENCES usuario(usuarioid);


--
-- TOC entry 2246 (class 2606 OID 17686)
-- Name: usuario_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY requisicao
    ADD CONSTRAINT usuario_fkey FOREIGN KEY (usuarioid) REFERENCES usuario(usuarioid);


--
-- TOC entry 2248 (class 2606 OID 17691)
-- Name: usuarioid_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY rone_grupousuario
    ADD CONSTRAINT usuarioid_fkey FOREIGN KEY (usuario_id) REFERENCES rone_usuario(usuario_id);


--
-- TOC entry 2372 (class 0 OID 0)
-- Dependencies: 7
-- Name: public; Type: ACL; Schema: -; Owner: postgres
--

REVOKE ALL ON SCHEMA public FROM PUBLIC;
REVOKE ALL ON SCHEMA public FROM postgres;
GRANT ALL ON SCHEMA public TO postgres;
GRANT ALL ON SCHEMA public TO PUBLIC;


-- Completed on 2016-05-24 13:42:38 AMT

--
-- PostgreSQL database dump complete
--

