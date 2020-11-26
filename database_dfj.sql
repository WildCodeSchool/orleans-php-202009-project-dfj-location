CREATE DATABASE IF NOT EXISTS dfj_location;

CREATE TABLE `dfj_location`.`category` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  `image` TEXT,
  PRIMARY KEY (`id`));
  
CREATE TABLE `dfj_location`.`bike` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  `image` TEXT NOT NULL,
  `category_id` INT NOT NULL,
  `weight` FLOAT(4,2) NOT NULL,
  `maximum_charge` INT,
  `autonomy` VARCHAR(100),
  `frame_size` VARCHAR(3),
  `created_date` DATE DEFAULT NULL,
  `stock` INT NOT NULL DEFAULT 0,
  `description` TEXT NOT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT FK_CategoryBike FOREIGN KEY (category_id)
  REFERENCES category(id));
  
CREATE TABLE `dfj_location`.`reservation` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `firstname_visitor` VARCHAR(100) NOT NULL,
  `lastname_visitor` VARCHAR(100) NOT NULL,
  `phone` VARCHAR(20) NOT NULL,
   `email` VARCHAR(255) NOT NULL,
  `bike_id` INT NOT NULL,
  `number_bike` INT NOT NULL,
  `withdrawal_date` DATE NOT NULL,
  `duration_id` INT,
  `comment` TEXT,
  `is_validated` VARCHAR(10) DEFAULT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT FK_BikeReservation FOREIGN KEY (bike_id)
  REFERENCES bike(id)
  CONSTRAINT FK_DurationReservation FOREIGN KEY (duration_id)
  REFERENCES duration(id));

CREATE TABLE `dfj_location`.`duration` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`));

CREATE TABLE `dfj_location`.`price` (
  `id` int NOT NULL AUTO_INCREMENT,
  `category_id` int NOT NULL,
  `duration_id` int NOT NULL,
  `price` float(5,2) NOT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `FK_CategoryPrice` FOREIGN KEY (category_id)
  REFERENCES category(id),
  CONSTRAINT `FK_DurationPrice` FOREIGN KEY (duration_id)
  REFERENCES duration(id));
