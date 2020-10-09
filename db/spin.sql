create database spin;
use spin;
create table user(
  bio varchar(260),
  privacy varchar(7),
  dob date,
  user_id varchar(20),
  username varchar(20),
  password varchar(260),
  email varchar(100),
  profile_photo longblob,
  firstname varchar(20),
  lastname varchar(20),
  primary key(user_id)
);
create table post(
  post_id varchar(20),
  user_id varchar(20),
  upload_time datetime,
  caption varchar(260),
  primary key(post_id),
  foreign key(user_id) references user(user_id)
);
create table comments(
  post_id varchar(20),
  comment varchar(260),
  user_id varchar(20),
  comment_id varchar(20) PRIMARY KEY,
  foreign key(post_id) references post(post_id),
  foreign key(user_id) references user(user_id)
);
create table likes(
  post_id varchar(20),
  user_id varchar(20),
  foreign key(post_id) references post(post_id),
  foreign key(user_id) references user(user_id)
);
create table pictures(
  post_id varchar(20),
  image longblob,
  foreign key(post_id) references post(post_id)
);
create table followers(
  user_id_1 varchar(20),
  user_id_2 varchar(20),
  foreign key(user_id_1) references user(user_id),
  foreign key(user_id_2) references user(user_id)
);
create table follow_requests(
  user_id_1 varchar(20),
  user_id_2 varchar(20),
  foreign key(user_id_1) references user(user_id),
  foreign key(user_id_2) references user(user_id)
);