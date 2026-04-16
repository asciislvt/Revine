create table users
(
    id            int auto_increment
        primary key,
    username      varchar(255) not null,
    password_hash varchar(255) not null,
    email         varchar(255) not null
);

create table videos
(
    id          int auto_increment
        primary key,
    video_id    varchar(16)                                                                      null,
    title       varchar(255)                             default 'My New Video :D'               not null,
    description varchar(1000)                            default 'New video uploaded to REVINE!' not null,
    uploaded_on date                                     default curdate()                       not null,
    status      enum ('uploaded', 'processing', 'ready') default 'uploaded'                      not null,
    uploaded_by int                                                                              null,
    constraint videos_users_id_fk
        foreign key (uploaded_by) references users (id)
);


