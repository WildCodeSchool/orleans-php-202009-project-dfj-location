CREATE DATABASE IF NOT EXISTS dfj_location;

CREATE TABLE `dfj_location`.`category` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  `image` TEXT NOT NULL,
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
  `created_date` DATE NOT NULL,
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
  `duration` VARCHAR(100) NOT NULL,
  `comment` TEXT,
  `is_validated` BOOL DEFAULT 0,
  PRIMARY KEY (`id`),
  CONSTRAINT FK_BikeReservation FOREIGN KEY (bike_id)
  REFERENCES bike(id));
