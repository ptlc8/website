CREATE TABLE `USERS` (
  `id` int(11) NOT NULL COMMENT 'id de l''utilisateur',
  `email` varchar(64) COLLATE utf8_unicode_ci NOT NULL COMMENT 'E-mail de l''utilisateur',
  `name` tinytext COLLATE utf8_unicode_ci NOT NULL COMMENT 'nom de l''utilisateur',
  `password` varchar(128) COLLATE utf8_unicode_ci NOT NULL COMMENT 'mot de passe de l''utilisateur',
  `firstName` tinytext COLLATE utf8_unicode_ci NOT NULL COMMENT 'Prénom',
  `lastName` tinytext COLLATE utf8_unicode_ci NOT NULL COMMENT 'Nom de famille'
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
  ADD CONSTRAINT `user-tokens` FOREIGN KEY (`user`) REFERENCES `USERS`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;