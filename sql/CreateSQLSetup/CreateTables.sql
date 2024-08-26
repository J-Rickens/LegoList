CREATE TABLE users (
    user_id int NOT NULL AUTO_INCREMENT,
    username varchar(255) NOT NULL UNIQUE,
    email varchar(255) NOT NULL UNIQUE,
    pwd varchar(255) NOT NULL,
    PRIMARY KEY (user_id)
) ENGINE = InnoDB;

CREATE TABLE user_data (
    user_id int NOT NULL,
    name varchar(255) NOT NULL,
    date_created timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (user_id),
    FOREIGN KEY (user_id) REFERENCES users(user_id)
) ENGINE = InnoDB;

CREATE TABLE legolists (
    list_id int NOT NULL AUTO_INCREMENT,
    owner_id int NOT NULL,
    list_name varchar(255) NOT NULL DEFAULT 'Wishlist',
    is_public boolean NOT NULL DEFAULT 1,
    date_created timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    date_modified timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (list_id),
    FOREIGN KEY (owner_id) REFERENCES users(user_id)
) ENGINE = InnoDB;

CREATE TABLE images (
    img_id int NOT NULL AUTO_INCREMENT,
    img_file varchar(255) NOT NULL,
    PRIMARY KEY (img_id)
) ENGINE = InnoDB;

CREATE TABLE legos (
    lego_id int NOT NULL,
    lego_name varchar(255),
    lego_collection varchar(255),
    piece_count int NOT NULL,
    lego_cost float(2),
    img_id int,
    date_uploaded timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (lego_id),
    FOREIGN KEY (img_id) REFERENCES images(img_id)
) ENGINE = InnoDB;

CREATE TABLE legolist_lego_c (
    list_id int NOT NULL,
    lego_id int NOT NULL,
    date_added timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT pk_legolist_lego_c PRIMARY KEY (list_id, lego_id),
    FOREIGN KEY (list_id) REFERENCES legolists(list_id),
    FOREIGN KEY (lego_id) REFERENCES legos(lego_id)
) ENGINE = InnoDB;