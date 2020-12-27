## Задание

#### База данных
user
 - таблица пользователей, можно расширять как угодно

post
 - посты, можете расширять таблицу как угодно

user_mute
 - пользователь может заблокировать другого пользователя, 
 	при этом посты этого пользователя скрываются от того, кто заблокировал его
 - так же можно расширять таблицу как угодно
 
 Суть задачи
 - Нужно достать 50 постов с учетом сортировок (рандом, старые, новые)
 - Применить кэширование
 - Ключ кэша не может содержать ID пользователя, который получает список постов
 - Результат не должен содержать посты заблокированных пользователей
 
 
 Для выполнения задачи нужно использовать laravel, так же любые технологии кэширования и т.д.
 
#### Начальная структура базы данных
 
 ````
 create table post
 (
 	id int auto_increment,
 	user_id int not null,
 	title varchar(255) not null,
 	body text null,
 	created_at int default 0 null,
 	updated_at int default 0 null,
 	constraint post_pk
 		primary key (id)
 );
 
 
 create table user_mute
 (
 	user_id int not null,
 	mute_id int not null,
 	expired_at int default 0 null,
 	constraint user_mute_pk
 		primary key (user_id, mute_id)
 );
 
 create table user
 (
 	id int not null,
 	username int not null,
 	created_at int default 0 null,
 	updated_at int default 0 null,
 	constraint user_pk
 		primary key (id)
 );
 
 alter table post
 	add constraint post_user_id_fk
 		foreign key (user_id) references user (id);
 		
 alter table user_mute
 	add constraint user_mute_user_id_fk
 		foreign key (user_id) references user (id);
 		
 alter table user_mute
 	add constraint user_mute_user_id_fk_2
 		foreign key (mute_id) references user (id);
 ````
 
 ## Запуск проекта
 
 `$ docker-compose up -d`
 
 ## Просмотр проекта
 
 [http://localhost](http://localhost)
