CREATE TABLE `USERS` (
  `id` int(11) NOT NULL COMMENT 'id de l''utilisateur',
  `email` varchar(64) COLLATE utf8_unicode_ci NOT NULL COMMENT 'E-mail',
  `name` tinytext COLLATE utf8_unicode_ci NOT NULL COMMENT 'Pseudonyme',
  `password` varchar(128) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Mot de passe haché',
  `firstName` tinytext COLLATE utf8_unicode_ci NOT NULL COMMENT 'Prénom',
  `lastName` tinytext COLLATE utf8_unicode_ci NOT NULL COMMENT 'Nom de famille',
  `createdAt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Date de création',
  `updatedAt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Date de dernière modification'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `USERS`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

ALTER TABLE `USERS`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id de l''utilisateur';


CREATE TABLE `FORGOTREQUEST` (
  `userId` int(11) NOT NULL,
  `token` varchar(32) NOT NULL,
  `expire` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Toutes les requêtes de changements de mot de passe';

ALTER TABLE `FORGOTREQUEST`
  ADD PRIMARY KEY (`token`),
  ADD UNIQUE KEY `userId` (`userId`);

ALTER TABLE `FORGOTREQUEST`
  ADD CONSTRAINT `user-forgotrequests` FOREIGN KEY (`userId`) REFERENCES `USERS` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;


CREATE TABLE `APPS` (
  `id` VARCHAR(16) NOT NULL,
  `name` TINYTEXT NOT NULL,
  `url` TINYTEXT NOT NULL,
  `returnUrl` TINYTEXT NOT NULL,
  `icon` TINYTEXT NULL DEFAULT NULL,
  `background` TINYTEXT NULL DEFAULT NULL
) ENGINE = InnoDB;

ALTER TABLE `APPS`
  ADD PRIMARY KEY (`id`);


CREATE TABLE `TOKENS` (
  `token` VARCHAR(16) NOT NULL ,
  `user` INT NOT NULL ,
  `app` VARCHAR(16) NOT NULL,
  `date` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE = InnoDB;

ALTER TABLE `TOKENS`
  ADD PRIMARY KEY (`token`),
  ADD UNIQUE `token` (`app`, `user`);

ALTER TABLE `TOKENS`
  ADD CONSTRAINT `user-tokens` FOREIGN KEY (`user`) REFERENCES `USERS`(`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `app-tokens` FOREIGN KEY (`app`) REFERENCES `APPS`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;