/*
	Author: Yuto Uogata
	Date: October 14, 2022
	File: 2_cms_clients.sql
*/


-- - - - - - - - - - - - - - - - - - - - - - - - - - - - - clients table - - - - - - - - - - - - - - - - - - - - - - - - - - - 
DROP TABLE IF EXISTS cms_clients;

CREATE TABLE cms_clients(
	client_id INT NOT NULL PRIMARY KEY,
	salesperson_id VARCHAR(20) NOT NULL,
	first_name VARCHAR(128) NOT NULL,
	last_name VARCHAR(128) NOT NULL,
	email VARCHAR(255) UNIQUE,
	phone_number BIGINT NOT NULL,
	phone_extension INT NULL,
	logo_path VARCHAR(128) NULL,

	FOREIGN KEY (salesperson_id) REFERENCES cms_users(id)
);


INSERT INTO cms_clients (client_id, salesperson_id, first_name, last_name, email, phone_number, phone_extension) VALUES
(
	10000,
	'sales1',
	'Will',
	'Gerber',
	'wgerber@gmail.com',
	4165671456,
	null
);

INSERT INTO cms_clients (client_id, salesperson_id, first_name, last_name, email, phone_number, phone_extension) VALUES
(
	10001,
	'sales1',
	'Brad',
	'Linn',
	'blinn@gmail.com',
	6133456667,
	001
);

INSERT INTO cms_clients (client_id, salesperson_id, first_name, last_name, email, phone_number, phone_extension) VALUES
(
	10002,
	'sales1',
	'Tom',
	'Minow',
	'tominow@yahoo.com',
	6762345556,
	null
);

INSERT INTO cms_clients (client_id, salesperson_id, first_name, last_name, email, phone_number, phone_extension) VALUES
(
	10003,
	'sales2',
	'Bill',
	'Gates',
	'billgates@gatesfoundation.org',
	4533358893,
	null
);

INSERT INTO cms_clients (client_id, salesperson_id, first_name, last_name, email, phone_number, phone_extension, logo_path) VALUES
(
	10004,
	'sales2',
	'Elon',
	'Bezos',
	'elon@gmail.com',
	3418903841,
	null,
	'./file_uploads/10004_Elon_logo.jpeg'
);

INSERT INTO cms_clients (client_id, salesperson_id, first_name, last_name, email, phone_number, phone_extension, logo_path) VALUES
(
	10005,
	'sales1',
	'Tobey',
	'Woods',
	'tobeywoods@gmail.com',
	2367892237,
	null,
	'./file_uploads/10005_Tobey_logo.jpeg'
);
-- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -  - - - 