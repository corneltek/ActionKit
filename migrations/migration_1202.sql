--- add hide column to category
alter table product_categories add column hide boolean default false;
alter table product_categories add column identity varchar(32);
insert into product_categories ( identity, name, lang , hide ) values ( 'agency_product', '代理產品' , 'zh_TW' , false );
insert into product_categories ( identity, name, lang , hide ) values ( 'agency_product', 'Agency Product' , 'en' , false );
