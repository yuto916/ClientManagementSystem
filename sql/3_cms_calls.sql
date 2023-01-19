/*
	Author	: Yuto Uogata
	Date	: October 14, 2022
	File	: cms_calls.sql
*/


-- - - - - - - - - - - - - - - - - - - - - - - - - - - - - calls table - - - - - - - - - - - - - - - - - - - - - - - - - - - 
DROP TABLE IF EXISTS cms_calls;

CREATE TABLE cms_calls(
	id SERIAL PRIMARY KEY,
	client_id INT NOT NULL,
	created_at TIMESTAMP NOT NULL,

	FOREIGN KEY (client_id) references cms_clients(client_id)
);


INSERT INTO cms_calls (client_id, created_at) VALUES
(
	10001,
	'2020-01-01 16:24:12'
);

INSERT INTO cms_calls (client_id, created_at) VALUES
(
	10001,
	'2020-03-12 17:24:42'
);

INSERT INTO cms_calls (client_id, created_at) VALUES
(
	10001,
	'2020-04-01 10:24:44'
);

INSERT INTO cms_calls (client_id, created_at) VALUES
(
	10002,
	'2020-04-17 14:46:23'
);
-- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - 
