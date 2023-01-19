/*
	Author	: Yuto Uogata
	Date	: September 23, 2022
	File	: 1_cms_users.sql
*/


CREATE EXTENSION IF NOT EXISTS pgcrypto;


-- - - - - - - - - - - - - - - - - - - - - - - - - - - - - cms_users table - - - - - - - - - - - - - - - - - - - - - - - - - - - -
-- Drop tables if it exists already
DROP TABLE IF EXISTS cms_users;

-- CREATE cms_users table
CREATE TABLE cms_users(
	id VARCHAR (20) NOT NULL PRIMARY KEY,
	email VARCHAR(255) UNIQUE,
	first_name VARCHAR(128) NOT NULL,
	last_name VARCHAR(128) NOT NULL,
	password VARCHAR(255) NOT NULL,
	created_time TIMESTAMP NOT NULL,
	last_access TIMESTAMP,
	phone_extension INT NOT NULL,
	user_type VARCHAR(2) NOT NULL
);


-- Inserting into cms_users table
INSERT INTO cms_users (id, email, first_name, last_name, password, created_time, last_access, phone_extension, user_type) VALUES
(
	'jdoe101',
	'jdoe@company.com',
	'John',
	'Doe',
	crypt('somepassword', gen_salt('bf')),
	'2018-01-01',
	'2020-01-28',
	001,
	'A'	
);

INSERT INTO cms_users (id, email, first_name, last_name, password, created_time, last_access, phone_extension, user_type) VALUES
(
	'yuto102',
	'yuto102@company.com',
	'Yuto',
	'Uogata',
	crypt('pswd125', gen_salt('bf')),
	'2020-01-01',
	'2022-08-28',
	001,
	'A'	
);

INSERT INTO cms_users (id, email, first_name, last_name, password, created_time, last_access, phone_extension, user_type) VALUES
(
	'sales1',
	'sales1@company.com',
	'Peter',
	'Galwey',
	crypt('sales_password1', gen_salt('bf')),
	'2018-01-01',
	'2022-09-23',
	002,
	'S'	
);

INSERT INTO cms_users (id, email, first_name, last_name, password, created_time, last_access, phone_extension, user_type) VALUES
(
	'sales2',
	'sales2@company.com',
	'Eric',
	'Forcina',
	crypt('sales_password2', gen_salt('bf')),
	'2020-01-01',
	'2022-10-13',
	003,
	'S'	
);
-- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - 












