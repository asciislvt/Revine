create table videos
(
    id          int auto_increment
        primary key,
    title       varchar(255) default 'My New Video :D'               not null,
    description varchar(255) default 'New video uploaded to REVINE!' not null,
    video_id    varchar(12)                                          null,
    uploaded_on date         default curdate()                       not null
);

create table users
(
    id       int auto_increment
        primary key,
    username varchar(255) not null,
    password varchar(255) not null,
    video_id int          null,
    foreign key (video_id) references videos (id),
);

