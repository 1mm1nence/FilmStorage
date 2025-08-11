CREATE TABLE actors (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    surname VARCHAR(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE film_actor (
    film_id INT UNSIGNED NOT NULL,
    actor_id INT UNSIGNED NOT NULL,
    PRIMARY KEY (film_id, actor_id),
    CONSTRAINT fk_film_actor_film FOREIGN KEY (film_id)
        REFERENCES films(id) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_film_actor_actor FOREIGN KEY (actor_id)
        REFERENCES actors(id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;