USE
`cs329e_mitra_nkuang`;

CREATE TABLE `teamup_users`
(
    `id`         INT AUTO_INCREMENT,
    `first_name` VARCHAR(50)  NOT NULL,
    `last_name`  VARCHAR(50)  NOT NULL,
    `email`      VARCHAR(100) NOT NULL,
    `password`   CHAR(60)     NOT NULL,
    `created`    DATETIME     DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE (`email`)
);

CREATE TABLE `teamup_projects`
(
    `id`      INT AUTO_INCREMENT,
    `title`   VARCHAR(200)  NOT NULL,
    `content` VARCHAR(2000) NOT NULL,
    `image`   VARCHAR(200)  DEFAULT 'https://picsum.photos/id/0/285/190',
    PRIMARY KEY (`id`)
);

CREATE TABLE `teamup_user_projects`
(
    `user_id`    INT,
    `project_id` INT,
    `is_leader`  TINYINT (1) DEFAULT 0,
    FOREIGN KEY (`user_id`) REFERENCES `teamup_users` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
    FOREIGN KEY (`project_id`) REFERENCES `teamup_projects` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
    PRIMARY KEY (`user_id`, `project_id`)
);
