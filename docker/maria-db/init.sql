use videosite;

create table categories
(
    id            int auto_increment
        primary key,
    category_name varchar(100) null,
    constraint categories_pk_2
        unique (category_name)
);

INSERT INTO videosite.categories (category_name) VALUES ('Uncategorized');
INSERT INTO videosite.categories (category_name) VALUES ('Gaming');
INSERT INTO videosite.categories (category_name) VALUES ('Music');
INSERT INTO videosite.categories (category_name) VALUES ('Comedy');
INSERT INTO videosite.categories (category_name) VALUES ('Education');

create table users
(
    id            int auto_increment
        primary key,
    username      varchar(255) not null,
    password_hash varchar(255) not null,
    email         varchar(255) not null
);

create table followers
(
    user_id        int not null
        primary key,
    following_user int not null,
    constraint followers_users_id_fk
        foreign key (user_id) references users (id),
    constraint followers_users_id_fk_2
        foreign key (following_user) references users (id)
);

create table profiles
(
    user_id int                     not null
        primary key,
    bio     varchar(256) default '' null,
    tagline varchar(128) default '' null,
    constraint profiles_users_id_fk
        foreign key (user_id) references users (id)
);

create table videos
(
    id          int auto_increment
        primary key,
    video_id    varchar(16)                                                                                null,
    title       varchar(255)                                       default 'My New Video :D'               not null,
    description varchar(1000)                                      default 'New video uploaded to REVINE!' null,
    uploaded_on date                                               default curdate()                       not null,
    status      enum ('uploaded', 'processing', 'ready', 'failed') default 'uploaded'                      not null,
    uploaded_by int                                                                                        null,
    category    int                                                default 1                               not null,
    constraint videos_pk
        unique (video_id),
    constraint videos_categories_id_fk
        foreign key (category) references categories (id),
    constraint videos_users_id_fk
        foreign key (uploaded_by) references users (id)
);

create table comments
(
    id             int auto_increment
        primary key,
    parent_comment int                    null,
    video_id       varchar(16)            null,
    comment        varchar(1000)          null,
    user_id        int                    not null,
    comment_date   date default curdate() null,
    constraint comments_comments_id_fk
        foreign key (parent_comment) references comments (id),
    constraint comments_users_id_fk
        foreign key (user_id) references users (id),
    constraint comments_videos_video_id_fk
        foreign key (video_id) references videos (video_id)
);

create table likes
(
    id       int auto_increment
        primary key,
    video_id varchar(16)                  not null,
    user_id  int                          not null,
    liked_at date       default curdate() not null,
    is_like  tinyint(1) default 1         not null,
    constraint likes_users_id_fk
        foreign key (user_id) references users (id),
    constraint likes_videos_video_id_fk
        foreign key (video_id) references videos (video_id)
);

create table views
(
    user_id   int                    not null
        primary key,
    video_id  varchar(16)            not null,
    view_date date default curdate() null,
    constraint views_users_id_fk
        foreign key (user_id) references users (id),
    constraint views_videos_video_id_fk
        foreign key (video_id) references videos (video_id)
);

