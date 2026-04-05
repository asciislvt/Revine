create table if not exists videos
(
    id          int auto_increment
        primary key,
    video_id    varchar(12)                                          null,
    title       varchar(255) default 'My New Video :D'               not null,
    description varchar(256) default 'New video uploaded to REVINE!' not null,
    uploaded_on date         default curdate()                       not null,
    status      enum('uploaded', 'processing', 'ready') default 'uploaded'           not null
);

create table if not exists users
(
    id       int auto_increment
        primary key,
    username varchar(255) not null,
    password varchar(255) not null,
    video_id int          null,
    foreign key (video_id) references videos (id),
);

