alter table products add column updated_on timestamp;
alter table news add column updated_on timestamp;
alter table events add column updated_on timestamp;
alter table events add column created_on timestamp;
alter table product_categories add column updated_on timestamp;
alter table banner_categories add column place_holder varchar(32);
alter table banner_categories add column lang varchar(12);
CREATE TABLE pages( 
	id integer primary key auto_increment,
	ident varchar(120),
	title varchar(512),
	content text,
	lang varchar(12),
	created_by integer,
	created_on timestamp
);
CREATE TABLE inquiry_items( 
	id integer primary key auto_increment,
	data text
);
