-- Create 'users' table
CREATE TABLE `users` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `emri` VARCHAR(255) NOT NULL,
    `username` VARCHAR(255) NOT NULL,
    `email` VARCHAR(255) NOT NULL,
    `password` VARCHAR(255) NOT NULL,
    `profile_image` VARCHAR(255) NOT NULL,
    `role` ENUM('admin', 'user') NOT NULL DEFAULT 'user',
    PRIMARY KEY (`id`)
);

-- Create 'places' table
CREATE TABLE `places` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `place_name` VARCHAR(255) NOT NULL,
    `place_desc` VARCHAR(255) NOT NULL,
    `place_image` VARCHAR(255) NOT NULL,
    `visitors` INT NOT NULL,
    PRIMARY KEY (`id`)
);

-- Create 'booking' table
CREATE TABLE `booking` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `user_id` INT NOT NULL,
    `place_id` INT NOT NULL,
    `nr_tickets` INT NOT NULL,
    `date` DATE NOT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`place_id`) REFERENCES `places`(`id`) ON DELETE CASCADE
);
