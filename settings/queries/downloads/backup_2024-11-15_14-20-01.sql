CREATE TABLE `accounts_type` (
  `acc_id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `acc_name` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `acc_description` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `creation_date` date NOT NULL,
  `status` int NOT NULL DEFAULT '1',
  `debit` enum('1','0') COLLATE utf8mb4_general_ci NOT NULL DEFAULT '1',
  PRIMARY KEY (`acc_id`),
  UNIQUE KEY `unq_acc_key` (`acc_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO accounts_type VALUES('2a3896307f9c7892c86e75d92de6c4596c348fcd7a4523d740cf06734db7','Expenses','Expense','2023-12-21','1','1');
INSERT INTO accounts_type VALUES('d38d478e4e7f3ef4d1d579ab729b6d42b8d81a801afeacf2631c5c159ed4','Revenue','Revenue','2023-12-21','1','0');


CREATE TABLE `category` (
  `category_id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `category_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` date NOT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `isActive` int NOT NULL DEFAULT '1',
  PRIMARY KEY (`category_id`),
  UNIQUE KEY `unq_category_name` (`category_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO category VALUES('44e0d0c915200a46cccf9c340a37ee045378731d263f56ed1db110e76e7e','TukTuk Category','2023-12-23','2024-07-09 03:43:53','1');
INSERT INTO category VALUES('7c457017543bb55fd4fa1688257a6eedf2b6159424ecc91d1165b475fa51','9L','2024-11-14','','1');
INSERT INTO category VALUES('a3834342986795ada70d27d588021411907d0257196a6149f85c2054125e','Lorries and Heavy Trucks','2024-07-09','2024-07-09 04:27:09','1');
INSERT INTO category VALUES('c9207862d48953a94a97d9d004fd2bf06e28ae2b7c2dffc309146cb646a0','4 Wheelers Category','2023-12-21','2024-07-09 03:42:09','1');


CREATE TABLE `charts_of_accounts` (
  `charts_of_accounts_id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `revenue_name` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `revenue_description` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `creation_date` date NOT NULL,
  `acc_type` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `isActive` int NOT NULL DEFAULT '1',
  PRIMARY KEY (`charts_of_accounts_id`),
  UNIQUE KEY `unq_name` (`revenue_name`),
  UNIQUE KEY `unq_revenue_key` (`revenue_name`,`acc_type`),
  KEY `account_id_fpk` (`acc_type`),
  CONSTRAINT `fpk_account_type` FOREIGN KEY (`acc_type`) REFERENCES `accounts_type` (`acc_id`) ON DELETE SET NULL ON UPDATE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO charts_of_accounts VALUES('673bb6e663d90dbdc63af77aa943a3585f8ac151301c15ed9f4c7bf629f3','Govt Expenses','Govt Expenses','2023-12-25','2a3896307f9c7892c86e75d92de6c4596c348fcd7a4523d740cf06734db7','1');
INSERT INTO charts_of_accounts VALUES('ba3bed9cb8180b6caab41a5abcfd7788eb1699defe9732ff649864dc7814','Daily Revenue','Daily Operations Revenue','2023-12-21','d38d478e4e7f3ef4d1d579ab729b6d42b8d81a801afeacf2631c5c159ed4','1');
INSERT INTO charts_of_accounts VALUES('d5ccfe65a30d9ca01fa9e0b9e213676e2646763a38aac724572c0ff59181','Fuel Expense','Rent Expense','2023-12-23','2a3896307f9c7892c86e75d92de6c4596c348fcd7a4523d740cf06734db7','1');
INSERT INTO charts_of_accounts VALUES('f3dd6025b683a939b96865ac61464d814ecc3c19c520b4b0028f2ae539a0','County Licenses','County Licenses','2023-12-24','2a3896307f9c7892c86e75d92de6c4596c348fcd7a4523d740cf06734db7','1');
INSERT INTO charts_of_accounts VALUES('f63a87e8e08f4b8bc5e1afac6300be1d0b6b49247a251afc71ae81f5885b','Other Incomes','Other Income Acc. ','2023-12-31','d38d478e4e7f3ef4d1d579ab729b6d42b8d81a801afeacf2631c5c159ed4','1');


CREATE TABLE `charts_of_accounts_settings` (
  `id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `min_amount` int NOT NULL,
  `max_amount` int NOT NULL,
  `created_at` date NOT NULL,
  `charts_of_accounts` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  KEY `fpk_charts_of_accounts` (`charts_of_accounts`),
  CONSTRAINT `fpk_charts_of_accounts` FOREIGN KEY (`charts_of_accounts`) REFERENCES `charts_of_accounts` (`charts_of_accounts_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO charts_of_accounts_settings VALUES('1','2000','10000','2023-12-29','ba3bed9cb8180b6caab41a5abcfd7788eb1699defe9732ff649864dc7814');


CREATE TABLE `conductors` (
  `conductor_id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `first_name` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `second_name` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `last_name` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `phone_number` varchar(10) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `isActive` int NOT NULL DEFAULT '1',
  `created_at` date NOT NULL,
  PRIMARY KEY (`conductor_id`),
  UNIQUE KEY `unq_coductor` (`phone_number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO conductors VALUES('080b4b8fe0e796438e16ea0a7458088f61808ce69f2f524f38df07c78a4b','Zakariya','Laban','Abdi','0702213163','1','2024-05-12');
INSERT INTO conductors VALUES('59057fa1ffd35e83e52cc449821f5d6cf350c1b63f67296430c6e7ea7cc9','Hassan ','Conductor','Wetu','0718596573','1','2024-05-10');
INSERT INTO conductors VALUES('82c58578f593249c852d3be675caffa3db1338686bbf1ac19f4fd9adb94d','YUSUF','ABDI','NOOR','0718596572','1','2024-05-27');


CREATE TABLE `customers` (
  `id` int NOT NULL,
  `name` text CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish_ci NOT NULL,
  `idDocument` int NOT NULL,
  `email` text CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish_ci NOT NULL,
  `phone` text CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish_ci NOT NULL,
  `address` text CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish_ci NOT NULL,
  `birthdate` date NOT NULL,
  `purchases` int NOT NULL,
  `lastPurchase` datetime NOT NULL,
  `registerDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish_ci;

INSERT INTO customers VALUES('1','David Cullison','123456','davidc@mail.com','(555)567-9999','27 Joseph Street','1986-01-05','15','2018-12-03 00:01:21','2022-12-10 13:41:42');
INSERT INTO customers VALUES('2','Mary Yaeger','121212','maryy@mail.com','(555) 789-9045','71 Highland Drive','1983-06-22','3','2022-12-08 12:20:28','2022-12-10 13:41:27');
INSERT INTO customers VALUES('3','Robert Zimmerman','122458','robert@mail.com','(305) 455-6677','27 Joseph Street','1989-04-12','0','2022-12-08 12:18:43','2022-12-10 13:40:27');
INSERT INTO customers VALUES('4','Randall Williams','103698','randalw@mail.com','(305) 256-6541','31 Romines Mill Road','1989-08-15','5','2022-12-10 08:42:36','2022-12-10 13:42:36');
INSERT INTO customers VALUES('6','Christine Moore','852100','christine@mail.com','(785) 458-7888','44 Down Lane','1990-10-16','36','2022-12-07 13:17:31','2022-12-08 18:11:56');
INSERT INTO customers VALUES('7','Nicole Young','100254','nicole@mail.com','(101) 222-1145','44 Sycamore Fork Road','1989-12-12','4','2022-12-10 08:38:47','2022-12-10 13:38:47');
INSERT INTO customers VALUES('8','Grace Moore','178500','gracem@mail.com','(100) 124-5896','39 Cambridge Drive','1990-12-07','7','2022-12-10 12:40:02','2022-12-10 17:40:02');
INSERT INTO customers VALUES('9','Reed Campbell','178500','reedc@mail.com','(100) 245-7866','87 Lang Avenue','1988-04-16','18','2022-12-10 08:43:42','2022-12-10 13:43:42');
INSERT INTO customers VALUES('10','Lynn','101014','lynn@mail.com','(100) 145-8966','90 Roosevelt Road','1992-02-22','0','0000-00-00 00:00:00','2022-12-10 17:12:55');
INSERT INTO customers VALUES('11','Will Williams','100147','williams@mail.com','(774) 145-8888','114 Test Address','1985-04-19','13','2022-12-10 12:35:52','2022-12-10 17:35:52');


CREATE TABLE `owner` (
  `owner_id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `first_name` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `second_name` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `last_name` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `phone_number` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` date NOT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `isActive` int NOT NULL DEFAULT '1',
  PRIMARY KEY (`owner_id`),
  UNIQUE KEY `unq_owner_name` (`first_name`,`second_name`,`last_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO owner VALUES('23462011b28fe8d57b0ae6e449254bec1b8544f9fdda50b526eb642227ea','Jamael ','Salim','Silaha','0712131415','2024-11-12','','1');
INSERT INTO owner VALUES('a74a0dad79931c8fc8332c2f649a17dcba655b0aabeaf2877fb6824b6f7b','HASSAN','ABDUL','HAKIM','0702213163','2024-01-02','','1');
INSERT INTO owner VALUES('b7702dcc1bbfce9bb8de027e114cab75ea6be62477b2809895f563db8783','Jamal ','Mohammad','Jalal','0718596573','2024-06-04','','1');
INSERT INTO owner VALUES('c0fcb6ae1340e3f2c6bc9293eefb3ef012eee515f40c39953918b663cb41','Salim','Juma','Silaha','0718596573','2023-12-21','','1');
INSERT INTO owner VALUES('feac022ac94feda5013c1c0cceeb19fcccf1e7c906f2ecb2d3d7a1a7bb8d','Nuru','Ali','Mbarak','0705403140','2024-06-04','','1');


CREATE TABLE `products` (
  `id` int NOT NULL,
  `idCategory` int NOT NULL,
  `code` text CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish_ci NOT NULL,
  `description` text CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish_ci NOT NULL,
  `image` text CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish_ci NOT NULL,
  `stock` int NOT NULL,
  `buyingPrice` float NOT NULL,
  `sellingPrice` float NOT NULL,
  `sales` int NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish_ci;

INSERT INTO products VALUES('18','2','201','Product Sample One','views/img/products/default/anonymous.png','10','56','78','20','2022-12-08 17:23:41');
INSERT INTO products VALUES('25','3','301','Product Sample Two','views/img/products/default/anonymous.png','18','144','185','23','2022-12-08 17:20:28');
INSERT INTO products VALUES('36','4','401','Product Sample Three','views/img/products/default/anonymous.png','55','98','125','22','2022-12-10 13:42:36');
INSERT INTO products VALUES('44','5','501','Product Sample Four','views/img/products/default/anonymous.png','8','350','490','21','2022-12-08 17:23:27');
INSERT INTO products VALUES('61','7','518','Test Product','views/img/products/518/204.jpg','19','20','28','41','2022-12-07 18:19:13');
INSERT INTO products VALUES('62','4','519','Product Sample Five','views/img/products/default/anonymous.png','95','120','156','0','2022-12-10 17:12:55');
INSERT INTO products VALUES('63','7','520','Product Sample Six','views/img/products/default/anonymous.png','53','70','98','0','2022-12-10 17:12:55');
INSERT INTO products VALUES('64','1','521','Product Sample Seven','views/img/products/default/anonymous.png','32','50','70','0','2022-12-08 17:31:25');
INSERT INTO products VALUES('65','3','522','Product Sample Eight','views/img/products/default/anonymous.png','5','100','140','5','2022-12-10 16:53:02');
INSERT INTO products VALUES('66','4','523','Product Sample Nine','views/img/products/default/anonymous.png','37','25','35','23','2022-12-10 17:35:52');
INSERT INTO products VALUES('67','5','524','Product Sample Ten','views/img/products/default/anonymous.png','65','65','91','6','2022-12-10 13:43:42');
INSERT INTO products VALUES('68','4','525','Product Sample Eleven','views/img/products/default/anonymous.png','16','120','168','10','2022-12-10 17:40:02');


CREATE TABLE `sales` (
  `id` int NOT NULL,
  `code` int NOT NULL,
  `idCustomer` int NOT NULL,
  `idSeller` int NOT NULL,
  `products` text CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish_ci NOT NULL,
  `tax` int NOT NULL,
  `netPrice` float NOT NULL,
  `totalPrice` float NOT NULL,
  `paymentMethod` text CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish_ci NOT NULL,
  `saledate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish_ci ROW_FORMAT=COMPACT;

INSERT INTO sales VALUES('9','10001','2','2','[{\"id\":\"25\",\"description\":\"Product Sample Two\",\"quantity\":\"3\",\"stock\":\"29\",\"price\":\"185\",\"totalPrice\":\"555\"}]','17','555','572','cash','2018-12-04 00:53:28');
INSERT INTO sales VALUES('11','10002','3','1','[{\"id\":\"44\",\"description\":\"Product Sample Four\",\"quantity\":\"4\",\"stock\":\"16\",\"price\":\"490\",\"totalPrice\":\"1960\"},{\"id\":\"36\",\"description\":\"Product Sample Three\",\"quantity\":\"6\",\"stock\":\"14\",\"price\":\"125\",\"totalPrice\":\"750\"}]','0','2710','2710','cash','2018-12-05 06:30:28');
INSERT INTO sales VALUES('12','10003','3','1','[{\"id\":\"44\",\"description\":\"Product Sample Four\",\"quantity\":\"1\",\"stock\":\"2\",\"price\":\"490\",\"totalPrice\":\"490\"},{\"id\":\"36\",\"description\":\"Product Sample Three\",\"quantity\":\"1\",\"stock\":\"8\",\"price\":\"125\",\"totalPrice\":\"125\"},{\"id\":\"25\",\"description\":\"Product Sample Two\",\"quantity\":\"1\",\"stock\":\"23\",\"price\":\"185\",\"totalPrice\":\"185\"},{\"id\":\"18\",\"description\":\"Product Sample One\",\"quantity\":\"2\",\"stock\":\"114\",\"price\":\"78\",\"totalPrice\":\"156\"}]','48','956','1004','cash','2019-04-09 22:59:10');
INSERT INTO sales VALUES('14','10005','6','1','[{\"id\":\"61\",\"description\":\"Test Product\",\"quantity\":\"9\",\"stock\":\"31\",\"price\":\"28\",\"totalPrice\":\"252\"},{\"id\":\"44\",\"description\":\"Product Sample Four\",\"quantity\":\"3\",\"stock\":\"3\",\"price\":\"490\",\"totalPrice\":\"1470\"},{\"id\":\"36\",\"description\":\"Product Sample Three\",\"quantity\":\"5\",\"stock\":\"3\",\"price\":\"125\",\"totalPrice\":\"625\"}]','117','2347','2464','cash','2020-02-26 05:34:45');
INSERT INTO sales VALUES('15','10006','6','1','[{\"id\":\"61\",\"description\":\"Test Product\",\"quantity\":\"17\",\"stock\":\"19\",\"price\":\"28\",\"totalPrice\":\"476\"},{\"id\":\"25\",\"description\":\"Product Sample Two\",\"quantity\":\"2\",\"stock\":\"1\",\"price\":\"185\",\"totalPrice\":\"370\"}]','25','846','871','cash','2021-01-05 15:36:20');
INSERT INTO sales VALUES('17','10008','4','1','[{\"id\":\"67\",\"description\":\"Product Sample Ten\",\"quantity\":\"2\",\"stock\":\"69\",\"price\":\"91\",\"totalPrice\":\"182\"}]','0','182','182','cash','2021-09-28 05:18:53');
INSERT INTO sales VALUES('18','10009','7','1','[{\"id\":\"66\",\"description\":\"Product Sample Nine\",\"quantity\":\"3\",\"stock\":\"57\",\"price\":\"35\",\"totalPrice\":\"105\"},{\"id\":\"65\",\"description\":\"Product Sample Eight\",\"quantity\":\"1\",\"stock\":\"40\",\"price\":\"140\",\"totalPrice\":\"140\"}]','5','245','250','cash','2022-02-13 23:58:09');
INSERT INTO sales VALUES('19','10010','4','1','[{\"id\":\"36\",\"description\":\"Product Sample Three\",\"quantity\":\"3\",\"stock\":\"55\",\"price\":\"125\",\"totalPrice\":\"375\"}]','4','375','379','cash','2022-06-29 03:42:37');
INSERT INTO sales VALUES('20','10011','9','1','[{\"id\":\"67\",\"description\":\"Product Sample Ten\",\"quantity\":\"4\",\"stock\":\"65\",\"price\":\"91\",\"totalPrice\":\"364\"},{\"id\":\"66\",\"description\":\"Product Sample Nine\",\"quantity\":\"10\",\"stock\":\"47\",\"price\":\"35\",\"totalPrice\":\"350\"},{\"id\":\"65\",\"description\":\"Product Sample Eight\",\"quantity\":\"4\",\"stock\":\"36\",\"price\":\"140\",\"totalPrice\":\"560\"}]','64','1274','1338','CC-110101458966','2022-09-20 13:43:42');
INSERT INTO sales VALUES('21','10012','11','1','[{\"id\":\"68\",\"description\":\"Product Sample Eleven\",\"quantity\":\"3\",\"stock\":\"23\",\"price\":\"168\",\"totalPrice\":\"504\"},{\"id\":\"66\",\"description\":\"Product Sample Nine\",\"quantity\":\"10\",\"stock\":\"37\",\"price\":\"35\",\"totalPrice\":\"350\"}]','68','854','922','CC-100000147850','2022-12-10 17:35:52');
INSERT INTO sales VALUES('22','10013','8','2','[{\"id\":\"68\",\"description\":\"Product Sample Eleven\",\"quantity\":\"7\",\"stock\":\"16\",\"price\":\"168\",\"totalPrice\":\"1176\"}]','0','1176','1176','cash','2022-12-10 17:40:02');


CREATE TABLE `settings` (
  `company_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `postal_address` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `type_of_currency` varchar(4) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `leaseDuration` int NOT NULL DEFAULT '2'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO settings VALUES('','','KES','14');


CREATE TABLE `testing_users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb3;

INSERT INTO testing_users VALUES('5','Salim','sali@sali.com');
INSERT INTO testing_users VALUES('6','Juma','juma@juma.com');
INSERT INTO testing_users VALUES('7','Silaha','sila@sila.com');
INSERT INTO testing_users VALUES('8','Ahmed','ahmed@ahmed.com');
INSERT INTO testing_users VALUES('9','Hassan',' hassan@gmail.com');
INSERT INTO testing_users VALUES('10','Omar','omar@omar.com');
INSERT INTO testing_users VALUES('11','Hassan','hassan@gmail.com');


CREATE TABLE `users` (
  `id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `username` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `first_name` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `second_name` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `last_name` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `email_address` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `isActive` int NOT NULL DEFAULT '1',
  `created_at` date NOT NULL,
  PRIMARY KEY (`username`,`email_address`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO users VALUES('ea1a6036112cfbc3f2c0a35a275f4d8eff2ac153e8dcf8c8ebd7083cf5fd','abdul-hakim','Abdul ','Hakim','Alamin','abdulhakim@gmail.com','$2y$10$CNx0F0/LhIKGVOez0gq/puQGffEQy480M4pYROr3Fr.4xMR0jJ.3G','1','2023-12-25');
INSERT INTO users VALUES('1','salo','salim','juma','silaha','salimjjuma@gmail.com','$2y$10$tDbNYkKfzGmYhaPY.Ow0Pe/UBd9tT63xikeAdE0.Y7rb2L1Ehw7Nu','1','2023-12-21');


CREATE TABLE `vehicle_company` (
  `vehicle_company_id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `company_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` date NOT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `isActive` int NOT NULL DEFAULT '1',
  PRIMARY KEY (`vehicle_company_id`),
  UNIQUE KEY `unq_category_name` (`company_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO vehicle_company VALUES('74c199f648582debf33240c75d44b1a37e80ff1d14754358229aa2da0617','Mercedez Benz','2023-12-25','','1');
INSERT INTO vehicle_company VALUES('ed1bc63622f02392b8d43245833a3e82d75c142762f4c62013ca5206a1cd','Toyota','2023-12-21','','1');


CREATE TABLE `vehicle_driver_conductors` (
  `vehicle_driver_conductors` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `vehicle_id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `driver_id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `conductor_id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `leaseStartDate` date NOT NULL,
  `leaseExpiryDate` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`vehicle_driver_conductors`),
  UNIQUE KEY `unq_vehicle_driver_conductors` (`vehicle_id`,`driver_id`,`conductor_id`,`leaseStartDate`),
  UNIQUE KEY `vehicle_driver_conductors` (`vehicle_driver_conductors`),
  UNIQUE KEY `unique_driver_per_leaseDate` (`driver_id`,`leaseStartDate`),
  KEY `idx_driver_id` (`driver_id`),
  KEY `idx_driver_conductor_for_vehicle` (`vehicle_driver_conductors`) USING BTREE,
  KEY `idx_fpk_conductors_id_` (`conductor_id`) USING BTREE,
  CONSTRAINT `fpk_conductors_id_` FOREIGN KEY (`conductor_id`) REFERENCES `conductors` (`conductor_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fpk_driver_id_` FOREIGN KEY (`driver_id`) REFERENCES `vehicle_drivers` (`vehicle_driver_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fpk_vehicle_id_` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`vehicle_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO vehicle_driver_conductors VALUES('2ba0fa9d3a751cd777eaee39674c7545a5d3941d572e30e50dcba511924c','7d2f1a120592aad54ead5a7e441dafcb186edd78282648b83a0a004c0af9','895f7ea6567d645fb74fc6c1d6243d072e6515eb318d8937c748dc75bf35','82c58578f593249c852d3be675caffa3db1338686bbf1ac19f4fd9adb94d','2024-06-05','','2024-06-04 12:53:44','');
INSERT INTO vehicle_driver_conductors VALUES('3974ed365d32fafd0c0d1d8d61bb1f8d9454e4e540557d10a8cdec435ea9','7c6467ede8cd9ebeb199cb46f4fb4dc54dc9dedd803d5da7762644ead496','027c0beda3b24f1e3eba22a71e8c38ef94171b415cd28ee054cad03705e9','080b4b8fe0e796438e16ea0a7458088f61808ce69f2f524f38df07c78a4b','2024-05-08','2024-05-29','2024-05-29 04:07:43','2024-05-29 04:07:49');
INSERT INTO vehicle_driver_conductors VALUES('418c1794db55ca3b0a9ea526cd9c728e824fa650e4ed70c0d2519b51df94','7c6467ede8cd9ebeb199cb46f4fb4dc54dc9dedd803d5da7762644ead496','7e9fc4802ce5cf814eeb9477eba0d974437d52038475f7f2677fe67cecef','82c58578f593249c852d3be675caffa3db1338686bbf1ac19f4fd9adb94d','2024-06-05','2024-07-13','2024-06-04 12:42:43','2024-07-13 11:04:17');
INSERT INTO vehicle_driver_conductors VALUES('60e9df77d89a590d6b74828985df9ec9a4fa02086af1e3cf761c480443c4','1ab9f70ce8c91093c01a1ce70947c139ebc7fd34f6ab0ea5feb4c1d6fb7c','bc0ffae1f1eaac85cd92dfb53a5d992446153ab284c31f80f3664542a7c3','59057fa1ffd35e83e52cc449821f5d6cf350c1b63f67296430c6e7ea7cc9','2024-06-05','2024-06-05','2024-06-05 18:51:32','2024-06-05 18:53:03');
INSERT INTO vehicle_driver_conductors VALUES('92788f8e373e8de74530432f1106c6f333f36856f29146ef3e6b964a2581','a378f1963cabcaba03af0d9e36a7c59b6d254213d0e9d65f4ba227ba84b5','7e9fc4802ce5cf814eeb9477eba0d974437d52038475f7f2677fe67cecef','080b4b8fe0e796438e16ea0a7458088f61808ce69f2f524f38df07c78a4b','2024-05-16','2024-05-29','2024-05-29 04:06:36','2024-05-29 04:06:42');
INSERT INTO vehicle_driver_conductors VALUES('b4bb4d35ed2737eb8601854263aea24a6e75d507f1c488ac81ea375e8fb1','e2cc262c791bed34c2097e5f2fc64b8d02c6a2a08324093fd779fbcd25be','895f7ea6567d645fb74fc6c1d6243d072e6515eb318d8937c748dc75bf35','82c58578f593249c852d3be675caffa3db1338686bbf1ac19f4fd9adb94d','2024-05-29','2024-05-30','2024-05-27 03:56:48','2024-05-30 14:18:41');
INSERT INTO vehicle_driver_conductors VALUES('b93216b82bba1e560c47392b06afe684c2312735536cf79f7b69e92a15b7','a378f1963cabcaba03af0d9e36a7c59b6d254213d0e9d65f4ba227ba84b5','895f7ea6567d645fb74fc6c1d6243d072e6515eb318d8937c748dc75bf35','82c58578f593249c852d3be675caffa3db1338686bbf1ac19f4fd9adb94d','2024-05-14','2024-05-28','2024-05-28 05:51:29','2024-05-28 05:51:46');
INSERT INTO vehicle_driver_conductors VALUES('c5bce4052e7fb6049c1bddbfcf87ee09ba4f9cae3e89fbd762d55256010b','a378f1963cabcaba03af0d9e36a7c59b6d254213d0e9d65f4ba227ba84b5','7e9fc4802ce5cf814eeb9477eba0d974437d52038475f7f2677fe67cecef','59057fa1ffd35e83e52cc449821f5d6cf350c1b63f67296430c6e7ea7cc9','2024-05-23','2024-05-29','2024-05-29 03:58:06','2024-05-29 03:58:15');
INSERT INTO vehicle_driver_conductors VALUES('dc697630e85621fea7bc5639028bf4fc36750bf5f3cc085e261d2c602e04','e2cc262c791bed34c2097e5f2fc64b8d02c6a2a08324093fd779fbcd25be','8a0da0af3ec8c5603e40ffa1b3a5d41c531d78a0190ad3563ac96312e6ba','080b4b8fe0e796438e16ea0a7458088f61808ce69f2f524f38df07c78a4b','2024-06-05','','2024-06-04 14:12:24','');
INSERT INTO vehicle_driver_conductors VALUES('e1fc026879961054ca3c48f77c749c98862995d9fbe7050d5b2b975fb5eb','7c6467ede8cd9ebeb199cb46f4fb4dc54dc9dedd803d5da7762644ead496','7e9fc4802ce5cf814eeb9477eba0d974437d52038475f7f2677fe67cecef','080b4b8fe0e796438e16ea0a7458088f61808ce69f2f524f38df07c78a4b','2024-05-01','2024-05-29','2024-05-29 04:08:45','2024-05-29 04:09:18');


CREATE TABLE `vehicle_drivers` (
  `vehicle_driver_id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `drivers_license_no` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `first_name` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `second_name` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `last_name` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `phone_number` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `isActive` int NOT NULL DEFAULT '1',
  `created_at` date NOT NULL,
  PRIMARY KEY (`vehicle_driver_id`),
  UNIQUE KEY `unq_coductor` (`drivers_license_no`,`phone_number`) USING BTREE,
  UNIQUE KEY `drivers_license_no` (`drivers_license_no`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO vehicle_drivers VALUES('027c0beda3b24f1e3eba22a71e8c38ef94171b415cd28ee054cad03705e9','KAP9021001','Salim','Driver','Wetu','0799908880','1','2024-05-10');
INSERT INTO vehicle_drivers VALUES('7e9fc4802ce5cf814eeb9477eba0d974437d52038475f7f2677fe67cecef','KL09L0K','Abdul ','Hakim','Alamin','0728160405','1','2024-05-10');
INSERT INTO vehicle_drivers VALUES('895f7ea6567d645fb74fc6c1d6243d072e6515eb318d8937c748dc75bf35','A32479807K','KASSIM','MOHD','SALIM','0721617492','1','2024-05-27');
INSERT INTO vehicle_drivers VALUES('8a0da0af3ec8c5603e40ffa1b3a5d41c531d78a0190ad3563ac96312e6ba','DL93454','ALI ','HAMOUD','AWADH','0711475318','1','2024-06-04');
INSERT INTO vehicle_drivers VALUES('bc0ffae1f1eaac85cd92dfb53a5d992446153ab284c31f80f3664542a7c3','DL0909/24','Ali ','Mahmoud','Ali','0728160405','1','2024-05-12');


CREATE TABLE `vehicle_expenses` (
  `id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `vehicle_id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `expense_id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `entry_incurred` decimal(10,2) DEFAULT NULL,
  `entry_date` date NOT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`vehicle_id`,`expense_id`,`entry_date`),
  KEY `fpk_expenses_id_` (`expense_id`),
  CONSTRAINT `fpk_expenses_id_` FOREIGN KEY (`expense_id`) REFERENCES `charts_of_accounts` (`charts_of_accounts_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fpk_vehicle_id_for_expense_` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`vehicle_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO vehicle_expenses VALUES('d024f99036fdb7ead9f54c63d2e13a708dd1a3881f20204890edc3d9ee39','12877e96476bf785ae13ae5e68119f9f942b0430e6605ad5da0901865a12','673bb6e663d90dbdc63af77aa943a3585f8ac151301c15ed9f4c7bf629f3','200.00','2024-07-06','');
INSERT INTO vehicle_expenses VALUES('b8595fb6264ba8b9451dc5f03b99b789f003b9ab28be9edf9803a92f87f8','1ab9f70ce8c91093c01a1ce70947c139ebc7fd34f6ab0ea5feb4c1d6fb7c','f3dd6025b683a939b96865ac61464d814ecc3c19c520b4b0028f2ae539a0','500.00','2024-06-05','');
INSERT INTO vehicle_expenses VALUES('860d958b2040ce50569a4942661dff7dc5501ec6fee5a02eb01adc13df1f','7c6467ede8cd9ebeb199cb46f4fb4dc54dc9dedd803d5da7762644ead496','673bb6e663d90dbdc63af77aa943a3585f8ac151301c15ed9f4c7bf629f3','500.00','2024-01-04','');
INSERT INTO vehicle_expenses VALUES('3984ccfdf5928a2c6782e8ae427e13ba2e3b441efbfbafcff222d3347436','7c6467ede8cd9ebeb199cb46f4fb4dc54dc9dedd803d5da7762644ead496','d5ccfe65a30d9ca01fa9e0b9e213676e2646763a38aac724572c0ff59181','500.00','2023-11-30','');
INSERT INTO vehicle_expenses VALUES('81e46bffe695995d83f50b4d6034199e172087044c67149047f7834dbcfd','7c6467ede8cd9ebeb199cb46f4fb4dc54dc9dedd803d5da7762644ead496','d5ccfe65a30d9ca01fa9e0b9e213676e2646763a38aac724572c0ff59181','8000.00','2023-12-21','');
INSERT INTO vehicle_expenses VALUES('8094410576b97a8dbafdbb1f30eee2597de42cfe8f072452a7e3b3ffbb9a','7c6467ede8cd9ebeb199cb46f4fb4dc54dc9dedd803d5da7762644ead496','d5ccfe65a30d9ca01fa9e0b9e213676e2646763a38aac724572c0ff59181','900.00','2023-12-23','');
INSERT INTO vehicle_expenses VALUES('38d6164ffca5682ad3e7c02c2836d47f8bf8b842c63dd5f13c61033576fd','7c6467ede8cd9ebeb199cb46f4fb4dc54dc9dedd803d5da7762644ead496','d5ccfe65a30d9ca01fa9e0b9e213676e2646763a38aac724572c0ff59181','677.00','2023-12-30','');
INSERT INTO vehicle_expenses VALUES('482feec2e611d068860c7189e8d73a3f49058dd19ed4629fced0b1b93862','7c6467ede8cd9ebeb199cb46f4fb4dc54dc9dedd803d5da7762644ead496','d5ccfe65a30d9ca01fa9e0b9e213676e2646763a38aac724572c0ff59181','200.00','2024-02-01','');
INSERT INTO vehicle_expenses VALUES('fe4a1c1965fe7dd3fde9b1512b7e94a116d4960e65bf274e7725fbb59840','7c6467ede8cd9ebeb199cb46f4fb4dc54dc9dedd803d5da7762644ead496','f3dd6025b683a939b96865ac61464d814ecc3c19c520b4b0028f2ae539a0','1000.00','2023-12-25','');
INSERT INTO vehicle_expenses VALUES('1f896799d4db37799ddac9dbf84000ba606c7862c42c5b8fc218f1e708d3','7c6467ede8cd9ebeb199cb46f4fb4dc54dc9dedd803d5da7762644ead496','f3dd6025b683a939b96865ac61464d814ecc3c19c520b4b0028f2ae539a0','1000.00','2024-01-01','');
INSERT INTO vehicle_expenses VALUES('776a3b3987185adebb39a2f0bb3fef9a351b9d12c4c39a3f3b2d51a949fe','7d2f1a120592aad54ead5a7e441dafcb186edd78282648b83a0a004c0af9','673bb6e663d90dbdc63af77aa943a3585f8ac151301c15ed9f4c7bf629f3','1200.00','2024-05-05','');
INSERT INTO vehicle_expenses VALUES('238da90f0f1bb12d6b4e63fecb7eff68b17df786caa03d59eedcdde6239b','7d2f1a120592aad54ead5a7e441dafcb186edd78282648b83a0a004c0af9','673bb6e663d90dbdc63af77aa943a3585f8ac151301c15ed9f4c7bf629f3','100.00','2024-07-23','');
INSERT INTO vehicle_expenses VALUES('4f19cf7944cd2be6116863f9cbb2beeaae5a0c039e28505911f89740dc19','7d2f1a120592aad54ead5a7e441dafcb186edd78282648b83a0a004c0af9','d5ccfe65a30d9ca01fa9e0b9e213676e2646763a38aac724572c0ff59181','200.00','2024-07-23','');
INSERT INTO vehicle_expenses VALUES('2ecff703ad597af5aad1cb08514eecd3854e3584f32885021f8d0cc57597','a378f1963cabcaba03af0d9e36a7c59b6d254213d0e9d65f4ba227ba84b5','673bb6e663d90dbdc63af77aa943a3585f8ac151301c15ed9f4c7bf629f3','190.00','2023-12-31','');
INSERT INTO vehicle_expenses VALUES('f9f255eceffcd1df0e203a12e5b538c8c4ce4975ea3e9ec7bc487a380410','a378f1963cabcaba03af0d9e36a7c59b6d254213d0e9d65f4ba227ba84b5','673bb6e663d90dbdc63af77aa943a3585f8ac151301c15ed9f4c7bf629f3','1000.00','2024-01-11','');
INSERT INTO vehicle_expenses VALUES('40e07904a4a0119e35f970b209828e759555f25eafecc37beaaefd656468','a378f1963cabcaba03af0d9e36a7c59b6d254213d0e9d65f4ba227ba84b5','d5ccfe65a30d9ca01fa9e0b9e213676e2646763a38aac724572c0ff59181','0.00','2023-12-23','2024-06-04 05:45:08');
INSERT INTO vehicle_expenses VALUES('e4bef298a364cf4e6428d7ff8de10c964e9f422b1af2131fcaea44c5b15d','a378f1963cabcaba03af0d9e36a7c59b6d254213d0e9d65f4ba227ba84b5','d5ccfe65a30d9ca01fa9e0b9e213676e2646763a38aac724572c0ff59181','1999.00','2023-12-30','');
INSERT INTO vehicle_expenses VALUES('6753b33a021f0801758027b1794b2403220642e96c323be79e226e4dc148','a378f1963cabcaba03af0d9e36a7c59b6d254213d0e9d65f4ba227ba84b5','d5ccfe65a30d9ca01fa9e0b9e213676e2646763a38aac724572c0ff59181','100.00','2024-01-04','');
INSERT INTO vehicle_expenses VALUES('718d0d0572a45717a27520f70d7eb2f5cea92c5635aa9c08887458f8c0fd','e2cc262c791bed34c2097e5f2fc64b8d02c6a2a08324093fd779fbcd25be','673bb6e663d90dbdc63af77aa943a3585f8ac151301c15ed9f4c7bf629f3','200.00','2023-11-30','');
INSERT INTO vehicle_expenses VALUES('ca00d5605e51b99deaa431900cc31902a85a8926d0f1e8fa35ac55f6618e','e2cc262c791bed34c2097e5f2fc64b8d02c6a2a08324093fd779fbcd25be','673bb6e663d90dbdc63af77aa943a3585f8ac151301c15ed9f4c7bf629f3','10.00','2023-12-25','2024-06-04 04:12:57');
INSERT INTO vehicle_expenses VALUES('0b402621897b4869d00e2c4c0e67cc7a16db2b159afad41e2d6c11a4e30c','e2cc262c791bed34c2097e5f2fc64b8d02c6a2a08324093fd779fbcd25be','673bb6e663d90dbdc63af77aa943a3585f8ac151301c15ed9f4c7bf629f3','200.00','2024-02-01','');
INSERT INTO vehicle_expenses VALUES('a16944b599f5fc3a710104d5a9c67e9c62a418885874be01cb0c709d0970','e2cc262c791bed34c2097e5f2fc64b8d02c6a2a08324093fd779fbcd25be','d5ccfe65a30d9ca01fa9e0b9e213676e2646763a38aac724572c0ff59181','2000.00','2024-01-02','');
INSERT INTO vehicle_expenses VALUES('35e66367727f89662eb3d12df2442ac291624dc6f2ac42f8a31dd9d6aa2f','e2cc262c791bed34c2097e5f2fc64b8d02c6a2a08324093fd779fbcd25be','d5ccfe65a30d9ca01fa9e0b9e213676e2646763a38aac724572c0ff59181','900.00','2024-06-01','');
INSERT INTO vehicle_expenses VALUES('b328267371fb87fabbe787dffd03f845b6e97976615fc535edfe6d307821','e2cc262c791bed34c2097e5f2fc64b8d02c6a2a08324093fd779fbcd25be','f3dd6025b683a939b96865ac61464d814ecc3c19c520b4b0028f2ae539a0','1000.00','2023-01-01','');
INSERT INTO vehicle_expenses VALUES('96f2b05f8257f892011308662c497f450c62bd2716b7160a3a80b28b4555','e2cc262c791bed34c2097e5f2fc64b8d02c6a2a08324093fd779fbcd25be','f3dd6025b683a939b96865ac61464d814ecc3c19c520b4b0028f2ae539a0','1000.00','2023-12-01','');
INSERT INTO vehicle_expenses VALUES('ea49be0abe376d9cf515bd4b73ffeea046da63ddb398012973be83b92c80','e2cc262c791bed34c2097e5f2fc64b8d02c6a2a08324093fd779fbcd25be','f3dd6025b683a939b96865ac61464d814ecc3c19c520b4b0028f2ae539a0','1000.00','2024-05-16','');


CREATE TABLE `vehicle_owners` (
  `vehicle_id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `owner_id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `date_of_ownership` date NOT NULL,
  `end_date_of_ownership` date DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `isActive` int NOT NULL DEFAULT '1',
  PRIMARY KEY (`vehicle_id`,`owner_id`),
  UNIQUE KEY `unq_registration_owner_id` (`vehicle_id`,`owner_id`),
  KEY `vehicle_id_fpk` (`vehicle_id`),
  KEY `owner_id_fpk` (`owner_id`),
  CONSTRAINT `fpk_owner_id_` FOREIGN KEY (`owner_id`) REFERENCES `owner` (`owner_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fpk_vehicle_id` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`vehicle_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO vehicle_owners VALUES('7c6467ede8cd9ebeb199cb46f4fb4dc54dc9dedd803d5da7762644ead496','c0fcb6ae1340e3f2c6bc9293eefb3ef012eee515f40c39953918b663cb41','2023-12-21','','','1');
INSERT INTO vehicle_owners VALUES('a378f1963cabcaba03af0d9e36a7c59b6d254213d0e9d65f4ba227ba84b5','c0fcb6ae1340e3f2c6bc9293eefb3ef012eee515f40c39953918b663cb41','2023-12-25','','','1');
INSERT INTO vehicle_owners VALUES('e2cc262c791bed34c2097e5f2fc64b8d02c6a2a08324093fd779fbcd25be','a74a0dad79931c8fc8332c2f649a17dcba655b0aabeaf2877fb6824b6f7b','2024-01-02','2024-05-12','2024-05-12 00:00:00','0');


CREATE TABLE `vehicle_revenue` (
  `id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `vehicle_id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `revenue_id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `revenue_generated` decimal(10,2) NOT NULL,
  `entry_date` date NOT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`vehicle_id`,`revenue_id`,`entry_date`,`revenue_generated`),
  KEY `fpk_revenue_id` (`revenue_id`),
  CONSTRAINT `fpk_revenue_id` FOREIGN KEY (`revenue_id`) REFERENCES `charts_of_accounts` (`charts_of_accounts_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fpk_vehicles_id_` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`vehicle_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO vehicle_revenue VALUES('6aa686f1d966c23e75e6907917ce0d13bc18e24c04ff734c970b7636b6c4','12877e96476bf785ae13ae5e68119f9f942b0430e6605ad5da0901865a12','ba3bed9cb8180b6caab41a5abcfd7788eb1699defe9732ff649864dc7814','200.00','2024-07-06','');
INSERT INTO vehicle_revenue VALUES('c89781d0d4d3e049e0e26123f039ab40020f87e218e0c14ac0fc68beb241','1ab9f70ce8c91093c01a1ce70947c139ebc7fd34f6ab0ea5feb4c1d6fb7c','ba3bed9cb8180b6caab41a5abcfd7788eb1699defe9732ff649864dc7814','1000.00','2024-06-05','');
INSERT INTO vehicle_revenue VALUES('$2y$10$hv0ZeOpmmWuUiINl3BQDmOsN05kQH9Vuk5CYnhhRO/V4oTJq7Yp7C','22ba45384d8b578081c626ee35133b213e06aafd42869d3d6f1bdcff150d','ba3bed9cb8180b6caab41a5abcfd7788eb1699defe9732ff649864dc7814','0.00','2024-10-01','');
INSERT INTO vehicle_revenue VALUES('04b40f2c-a277-11ef-ae8c-0242ac120003','22ba45384d8b578081c626ee35133b213e06aafd42869d3d6f1bdcff150d','ba3bed9cb8180b6caab41a5abcfd7788eb1699defe9732ff649864dc7814','6000.00','2024-10-02','');
INSERT INTO vehicle_revenue VALUES('04b423d2-a277-11ef-ae8c-0242ac120003','22ba45384d8b578081c626ee35133b213e06aafd42869d3d6f1bdcff150d','ba3bed9cb8180b6caab41a5abcfd7788eb1699defe9732ff649864dc7814','0.00','2024-10-03','');
INSERT INTO vehicle_revenue VALUES('04b4289e-a277-11ef-ae8c-0242ac120003','22ba45384d8b578081c626ee35133b213e06aafd42869d3d6f1bdcff150d','ba3bed9cb8180b6caab41a5abcfd7788eb1699defe9732ff649864dc7814','7000.00','2024-10-04','');
INSERT INTO vehicle_revenue VALUES('04b42c20-a277-11ef-ae8c-0242ac120003','22ba45384d8b578081c626ee35133b213e06aafd42869d3d6f1bdcff150d','ba3bed9cb8180b6caab41a5abcfd7788eb1699defe9732ff649864dc7814','2000.00','2024-10-05','');
INSERT INTO vehicle_revenue VALUES('04b43004-a277-11ef-ae8c-0242ac120003','22ba45384d8b578081c626ee35133b213e06aafd42869d3d6f1bdcff150d','ba3bed9cb8180b6caab41a5abcfd7788eb1699defe9732ff649864dc7814','7000.00','2024-10-06','');
INSERT INTO vehicle_revenue VALUES('04b432dc-a277-11ef-ae8c-0242ac120003','22ba45384d8b578081c626ee35133b213e06aafd42869d3d6f1bdcff150d','ba3bed9cb8180b6caab41a5abcfd7788eb1699defe9732ff649864dc7814','7000.00','2024-10-07','');
INSERT INTO vehicle_revenue VALUES('e986e330-a278-11ef-ae8c-0242ac120003','22ba45384d8b578081c626ee35133b213e06aafd42869d3d6f1bdcff150d','ba3bed9cb8180b6caab41a5abcfd7788eb1699defe9732ff649864dc7814','5000.00','2024-10-08','');
INSERT INTO vehicle_revenue VALUES('e98700a4-a278-11ef-ae8c-0242ac120003','22ba45384d8b578081c626ee35133b213e06aafd42869d3d6f1bdcff150d','ba3bed9cb8180b6caab41a5abcfd7788eb1699defe9732ff649864dc7814','6000.00','2024-10-09','');
INSERT INTO vehicle_revenue VALUES('e9870630-a278-11ef-ae8c-0242ac120003','22ba45384d8b578081c626ee35133b213e06aafd42869d3d6f1bdcff150d','ba3bed9cb8180b6caab41a5abcfd7788eb1699defe9732ff649864dc7814','6000.00','2024-10-10','');
INSERT INTO vehicle_revenue VALUES('e98709db-a278-11ef-ae8c-0242ac120003','22ba45384d8b578081c626ee35133b213e06aafd42869d3d6f1bdcff150d','ba3bed9cb8180b6caab41a5abcfd7788eb1699defe9732ff649864dc7814','6000.00','2024-10-11','');
INSERT INTO vehicle_revenue VALUES('e9870d74-a278-11ef-ae8c-0242ac120003','22ba45384d8b578081c626ee35133b213e06aafd42869d3d6f1bdcff150d','ba3bed9cb8180b6caab41a5abcfd7788eb1699defe9732ff649864dc7814','6500.00','2024-10-12','');
INSERT INTO vehicle_revenue VALUES('e98711cd-a278-11ef-ae8c-0242ac120003','22ba45384d8b578081c626ee35133b213e06aafd42869d3d6f1bdcff150d','ba3bed9cb8180b6caab41a5abcfd7788eb1699defe9732ff649864dc7814','7000.00','2024-10-13','');
INSERT INTO vehicle_revenue VALUES('e987181d-a278-11ef-ae8c-0242ac120003','22ba45384d8b578081c626ee35133b213e06aafd42869d3d6f1bdcff150d','ba3bed9cb8180b6caab41a5abcfd7788eb1699defe9732ff649864dc7814','7500.00','2024-10-14','');
INSERT INTO vehicle_revenue VALUES('e9871bde-a278-11ef-ae8c-0242ac120003','22ba45384d8b578081c626ee35133b213e06aafd42869d3d6f1bdcff150d','ba3bed9cb8180b6caab41a5abcfd7788eb1699defe9732ff649864dc7814','3500.00','2024-10-15','');
INSERT INTO vehicle_revenue VALUES('e9872509-a278-11ef-ae8c-0242ac120003','22ba45384d8b578081c626ee35133b213e06aafd42869d3d6f1bdcff150d','ba3bed9cb8180b6caab41a5abcfd7788eb1699defe9732ff649864dc7814','5700.00','2024-10-16','');
INSERT INTO vehicle_revenue VALUES('e9872855-a278-11ef-ae8c-0242ac120003','22ba45384d8b578081c626ee35133b213e06aafd42869d3d6f1bdcff150d','ba3bed9cb8180b6caab41a5abcfd7788eb1699defe9732ff649864dc7814','5300.00','2024-10-17','');
INSERT INTO vehicle_revenue VALUES('e9872ba3-a278-11ef-ae8c-0242ac120003','22ba45384d8b578081c626ee35133b213e06aafd42869d3d6f1bdcff150d','ba3bed9cb8180b6caab41a5abcfd7788eb1699defe9732ff649864dc7814','6500.00','2024-10-18','');
INSERT INTO vehicle_revenue VALUES('e9872f01-a278-11ef-ae8c-0242ac120003','22ba45384d8b578081c626ee35133b213e06aafd42869d3d6f1bdcff150d','ba3bed9cb8180b6caab41a5abcfd7788eb1699defe9732ff649864dc7814','6500.00','2024-10-19','');
INSERT INTO vehicle_revenue VALUES('e9873c44-a278-11ef-ae8c-0242ac120003','22ba45384d8b578081c626ee35133b213e06aafd42869d3d6f1bdcff150d','ba3bed9cb8180b6caab41a5abcfd7788eb1699defe9732ff649864dc7814','6500.00','2024-10-20','');
INSERT INTO vehicle_revenue VALUES('e987418c-a278-11ef-ae8c-0242ac120003','22ba45384d8b578081c626ee35133b213e06aafd42869d3d6f1bdcff150d','ba3bed9cb8180b6caab41a5abcfd7788eb1699defe9732ff649864dc7814','6000.00','2024-10-21','');
INSERT INTO vehicle_revenue VALUES('e98744c9-a278-11ef-ae8c-0242ac120003','22ba45384d8b578081c626ee35133b213e06aafd42869d3d6f1bdcff150d','ba3bed9cb8180b6caab41a5abcfd7788eb1699defe9732ff649864dc7814','5000.00','2024-10-22','');
INSERT INTO vehicle_revenue VALUES('e98747ea-a278-11ef-ae8c-0242ac120003','22ba45384d8b578081c626ee35133b213e06aafd42869d3d6f1bdcff150d','ba3bed9cb8180b6caab41a5abcfd7788eb1699defe9732ff649864dc7814','5500.00','2024-10-23','');
INSERT INTO vehicle_revenue VALUES('e9874b17-a278-11ef-ae8c-0242ac120003','22ba45384d8b578081c626ee35133b213e06aafd42869d3d6f1bdcff150d','ba3bed9cb8180b6caab41a5abcfd7788eb1699defe9732ff649864dc7814','5500.00','2024-10-24','');
INSERT INTO vehicle_revenue VALUES('e9874e3b-a278-11ef-ae8c-0242ac120003','22ba45384d8b578081c626ee35133b213e06aafd42869d3d6f1bdcff150d','ba3bed9cb8180b6caab41a5abcfd7788eb1699defe9732ff649864dc7814','6500.00','2024-10-25','');
INSERT INTO vehicle_revenue VALUES('e987515b-a278-11ef-ae8c-0242ac120003','22ba45384d8b578081c626ee35133b213e06aafd42869d3d6f1bdcff150d','ba3bed9cb8180b6caab41a5abcfd7788eb1699defe9732ff649864dc7814','5500.00','2024-10-26','');
INSERT INTO vehicle_revenue VALUES('e9875477-a278-11ef-ae8c-0242ac120003','22ba45384d8b578081c626ee35133b213e06aafd42869d3d6f1bdcff150d','ba3bed9cb8180b6caab41a5abcfd7788eb1699defe9732ff649864dc7814','6000.00','2024-10-27','');
INSERT INTO vehicle_revenue VALUES('e9875acc-a278-11ef-ae8c-0242ac120003','22ba45384d8b578081c626ee35133b213e06aafd42869d3d6f1bdcff150d','ba3bed9cb8180b6caab41a5abcfd7788eb1699defe9732ff649864dc7814','6500.00','2024-10-28','');
INSERT INTO vehicle_revenue VALUES('e9875f5b-a278-11ef-ae8c-0242ac120003','22ba45384d8b578081c626ee35133b213e06aafd42869d3d6f1bdcff150d','ba3bed9cb8180b6caab41a5abcfd7788eb1699defe9732ff649864dc7814','5000.00','2024-10-29','');
INSERT INTO vehicle_revenue VALUES('e98764c7-a278-11ef-ae8c-0242ac120003','22ba45384d8b578081c626ee35133b213e06aafd42869d3d6f1bdcff150d','ba3bed9cb8180b6caab41a5abcfd7788eb1699defe9732ff649864dc7814','7000.00','2024-10-30','');
INSERT INTO vehicle_revenue VALUES('e9876875-a278-11ef-ae8c-0242ac120003','22ba45384d8b578081c626ee35133b213e06aafd42869d3d6f1bdcff150d','ba3bed9cb8180b6caab41a5abcfd7788eb1699defe9732ff649864dc7814','6000.00','2024-10-31','');
INSERT INTO vehicle_revenue VALUES('e9876bbd-a278-11ef-ae8c-0242ac120003','22ba45384d8b578081c626ee35133b213e06aafd42869d3d6f1bdcff150d','ba3bed9cb8180b6caab41a5abcfd7788eb1699defe9732ff649864dc7814','4800.00','2024-11-01','');
INSERT INTO vehicle_revenue VALUES('8328d91be7cd7676722be851fc7b4819393de5e4fa25c69514645a48639f','7c6467ede8cd9ebeb199cb46f4fb4dc54dc9dedd803d5da7762644ead496','ba3bed9cb8180b6caab41a5abcfd7788eb1699defe9732ff649864dc7814','900.00','2023-11-30','');
INSERT INTO vehicle_revenue VALUES('334bafdc4f5b55e1f7f3ed492d56e536e96c174e4aaa93d4dd8a290c8b24','7c6467ede8cd9ebeb199cb46f4fb4dc54dc9dedd803d5da7762644ead496','ba3bed9cb8180b6caab41a5abcfd7788eb1699defe9732ff649864dc7814','9000.00','2023-12-21','');
INSERT INTO vehicle_revenue VALUES('264e35772d79ab2e65abff0cd9054fe86e61b6b06420af04fff1c205eb0b','7c6467ede8cd9ebeb199cb46f4fb4dc54dc9dedd803d5da7762644ead496','ba3bed9cb8180b6caab41a5abcfd7788eb1699defe9732ff649864dc7814','9000.00','2023-12-23','');
INSERT INTO vehicle_revenue VALUES('7277c23b690dfce07771f9ccb38953487b801459d560c5a8902891be6dad','7c6467ede8cd9ebeb199cb46f4fb4dc54dc9dedd803d5da7762644ead496','ba3bed9cb8180b6caab41a5abcfd7788eb1699defe9732ff649864dc7814','1200.00','2023-12-25','');
INSERT INTO vehicle_revenue VALUES('9e9380faaf1382d091557c6841451c6fffbf81b041471e1fc8cdc7d51f9b','7c6467ede8cd9ebeb199cb46f4fb4dc54dc9dedd803d5da7762644ead496','ba3bed9cb8180b6caab41a5abcfd7788eb1699defe9732ff649864dc7814','400.00','2023-12-30','');
INSERT INTO vehicle_revenue VALUES('65cd8d30703549a74ef76401db1df733a5ca9f4ac0fda2da0eca779eec02','7c6467ede8cd9ebeb199cb46f4fb4dc54dc9dedd803d5da7762644ead496','ba3bed9cb8180b6caab41a5abcfd7788eb1699defe9732ff649864dc7814','2000.00','2024-01-01','');
INSERT INTO vehicle_revenue VALUES('d0723d784b0f19aead8f918b1d1144385c32cf0f0026fb1c16e91d3abe22','7c6467ede8cd9ebeb199cb46f4fb4dc54dc9dedd803d5da7762644ead496','ba3bed9cb8180b6caab41a5abcfd7788eb1699defe9732ff649864dc7814','900.00','2024-01-04','');
INSERT INTO vehicle_revenue VALUES('03bfc10c2a146a4f210e4bf26e3f37e5e811819fea277a50e62aa310ab50','7c6467ede8cd9ebeb199cb46f4fb4dc54dc9dedd803d5da7762644ead496','ba3bed9cb8180b6caab41a5abcfd7788eb1699defe9732ff649864dc7814','1000.00','2024-02-01','');
INSERT INTO vehicle_revenue VALUES('703a31e9b5b0d30b8335410e119108f0ce79c9fa0bff5d5be97ccedb8ac7','7d2f1a120592aad54ead5a7e441dafcb186edd78282648b83a0a004c0af9','ba3bed9cb8180b6caab41a5abcfd7788eb1699defe9732ff649864dc7814','2000.00','2024-05-01','');
INSERT INTO vehicle_revenue VALUES('248597206becac5e8521ee1e1de54c3a6a0306c14e07bed16a3ff9ce935b','7d2f1a120592aad54ead5a7e441dafcb186edd78282648b83a0a004c0af9','ba3bed9cb8180b6caab41a5abcfd7788eb1699defe9732ff649864dc7814','1000.00','2024-07-02','');
INSERT INTO vehicle_revenue VALUES('5f1444626b140e24d4121b16f21c2f05286492dcfa480bc9d83ed4e22bf6','7d2f1a120592aad54ead5a7e441dafcb186edd78282648b83a0a004c0af9','ba3bed9cb8180b6caab41a5abcfd7788eb1699defe9732ff649864dc7814','1000.00','2024-07-23','');
INSERT INTO vehicle_revenue VALUES('4897761d1dbe1d8497e66f08a4577cfabadf02bc0e413655f51cc6795abf','7d2f1a120592aad54ead5a7e441dafcb186edd78282648b83a0a004c0af9','ba3bed9cb8180b6caab41a5abcfd7788eb1699defe9732ff649864dc7814','2000.00','2024-07-24','');
INSERT INTO vehicle_revenue VALUES('d5494f18e2a0a40e4eb2954e1df64369583633b0324098726301583593dd','a378f1963cabcaba03af0d9e36a7c59b6d254213d0e9d65f4ba227ba84b5','ba3bed9cb8180b6caab41a5abcfd7788eb1699defe9732ff649864dc7814','1000.00','2023-12-23','');
INSERT INTO vehicle_revenue VALUES('328be696d7d5738bf99be19c09e23f8b0d816bb4fb26b663c90bac3d8350','a378f1963cabcaba03af0d9e36a7c59b6d254213d0e9d65f4ba227ba84b5','ba3bed9cb8180b6caab41a5abcfd7788eb1699defe9732ff649864dc7814','2000.00','2024-01-04','');
INSERT INTO vehicle_revenue VALUES('283eb1e33863c3d52a47790b1a72459811c7ec98be105dd0afcb76e09c4d','a378f1963cabcaba03af0d9e36a7c59b6d254213d0e9d65f4ba227ba84b5','ba3bed9cb8180b6caab41a5abcfd7788eb1699defe9732ff649864dc7814','40.00','2024-11-07','');
INSERT INTO vehicle_revenue VALUES('5f550f1733f0399a853d1c89a3ac4176beb4cd106b6e207047f1acea18ea','a378f1963cabcaba03af0d9e36a7c59b6d254213d0e9d65f4ba227ba84b5','ba3bed9cb8180b6caab41a5abcfd7788eb1699defe9732ff649864dc7814','20.00','2024-11-08','');
INSERT INTO vehicle_revenue VALUES('53d19bb4ed6313c6ad3b3b6ca805c6d5bb12243f74b15e7353f94ba0432e','e2cc262c791bed34c2097e5f2fc64b8d02c6a2a08324093fd779fbcd25be','ba3bed9cb8180b6caab41a5abcfd7788eb1699defe9732ff649864dc7814','12000.00','2023-01-01','');
INSERT INTO vehicle_revenue VALUES('5fc16a6e3a84ce4e207324461d68d1733d0d43950dc5da2326ff21bbc9d7','e2cc262c791bed34c2097e5f2fc64b8d02c6a2a08324093fd779fbcd25be','ba3bed9cb8180b6caab41a5abcfd7788eb1699defe9732ff649864dc7814','6000.00','2023-11-30','');
INSERT INTO vehicle_revenue VALUES('96bd454ce620fb943e6849f078521a039f78815cb3dd7dfa02289cc9c652','e2cc262c791bed34c2097e5f2fc64b8d02c6a2a08324093fd779fbcd25be','ba3bed9cb8180b6caab41a5abcfd7788eb1699defe9732ff649864dc7814','8000.00','2023-12-01','');
INSERT INTO vehicle_revenue VALUES('3517880f8590087b63e7938b700e7e4beb9761c47f27ad92fce90b4abf49','e2cc262c791bed34c2097e5f2fc64b8d02c6a2a08324093fd779fbcd25be','ba3bed9cb8180b6caab41a5abcfd7788eb1699defe9732ff649864dc7814','2500.00','2024-01-02','');
INSERT INTO vehicle_revenue VALUES('596d338a826081296e8e5cf0b68f69d780f3cf0248fb561b4dda70a3054a','e2cc262c791bed34c2097e5f2fc64b8d02c6a2a08324093fd779fbcd25be','ba3bed9cb8180b6caab41a5abcfd7788eb1699defe9732ff649864dc7814','200.00','2024-02-01','');
INSERT INTO vehicle_revenue VALUES('fa12b2c3719a81f0b158666746dda029986d8fc4b1fab0f91b0b65cc5837','e2cc262c791bed34c2097e5f2fc64b8d02c6a2a08324093fd779fbcd25be','ba3bed9cb8180b6caab41a5abcfd7788eb1699defe9732ff649864dc7814','1200.00','2024-05-16','');
INSERT INTO vehicle_revenue VALUES('4ce06ec2e37cb0663c91285d96a93638e9aec85ba5ab7c51215e341371e1','e2cc262c791bed34c2097e5f2fc64b8d02c6a2a08324093fd779fbcd25be','ba3bed9cb8180b6caab41a5abcfd7788eb1699defe9732ff649864dc7814','2000.00','2024-06-02','');
INSERT INTO vehicle_revenue VALUES('4e528ee683dde062867a885e724f518bbe20009670abb4935ce66cfb8463','e2cc262c791bed34c2097e5f2fc64b8d02c6a2a08324093fd779fbcd25be','ba3bed9cb8180b6caab41a5abcfd7788eb1699defe9732ff649864dc7814','1000.00','2024-07-01','');
INSERT INTO vehicle_revenue VALUES('bd49af62f01b15dd299c7fbf1728e7f375643f943d40e55c4686b5340994','e2cc262c791bed34c2097e5f2fc64b8d02c6a2a08324093fd779fbcd25be','f63a87e8e08f4b8bc5e1afac6300be1d0b6b49247a251afc71ae81f5885b','1250.00','2023-01-01','');
INSERT INTO vehicle_revenue VALUES('e5d43752cb5885c01e14a19c048e424492852ec586a515c1d2a188875608','e2cc262c791bed34c2097e5f2fc64b8d02c6a2a08324093fd779fbcd25be','f63a87e8e08f4b8bc5e1afac6300be1d0b6b49247a251afc71ae81f5885b','1200.00','2023-12-25','2024-06-04 04:08:03');


CREATE TABLE `vehicles` (
  `vehicle_id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `vehicle_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `registration_number` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `category` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `vehicle_company` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` date NOT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `isActive` int NOT NULL DEFAULT '1',
  PRIMARY KEY (`vehicle_id`),
  UNIQUE KEY `unq_registration_number` (`registration_number`),
  UNIQUE KEY `unq_registration_owner_category` (`registration_number`,`category`),
  KEY `fpk_vehicle_company` (`vehicle_company`),
  KEY `category_fpk_id` (`category`),
  CONSTRAINT `fpk_category_id` FOREIGN KEY (`category`) REFERENCES `category` (`category_id`) ON DELETE SET NULL ON UPDATE SET NULL,
  CONSTRAINT `fpk_vehicle_company` FOREIGN KEY (`vehicle_company`) REFERENCES `vehicle_company` (`vehicle_company_id`) ON DELETE SET NULL ON UPDATE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO vehicles VALUES('12877e96476bf785ae13ae5e68119f9f942b0430e6605ad5da0901865a12','Test','TEST','44e0d0c915200a46cccf9c340a37ee045378731d263f56ed1db110e76e7e','ed1bc63622f02392b8d43245833a3e82d75c142762f4c62013ca5206a1cd','2024-07-06','2024-07-06 11:14:07','1');
INSERT INTO vehicles VALUES('1ab9f70ce8c91093c01a1ce70947c139ebc7fd34f6ab0ea5feb4c1d6fb7c','Double Cabin','KDK 809L','c9207862d48953a94a97d9d004fd2bf06e28ae2b7c2dffc309146cb646a0','74c199f648582debf33240c75d44b1a37e80ff1d14754358229aa2da0617','2024-06-05','','1');
INSERT INTO vehicles VALUES('22ba45384d8b578081c626ee35133b213e06aafd42869d3d6f1bdcff150d','KCZ 319W','KCZ 319W','7c457017543bb55fd4fa1688257a6eedf2b6159424ecc91d1165b475fa51','ed1bc63622f02392b8d43245833a3e82d75c142762f4c62013ca5206a1cd','2024-11-14','2024-11-14 11:18:35','1');
INSERT INTO vehicles VALUES('7c6467ede8cd9ebeb199cb46f4fb4dc54dc9dedd803d5da7762644ead496','Piagio 9L Hiace ','KAD 773D','44e0d0c915200a46cccf9c340a37ee045378731d263f56ed1db110e76e7e','ed1bc63622f02392b8d43245833a3e82d75c142762f4c62013ca5206a1cd','2023-12-21','2023-12-23 20:33:55','1');
INSERT INTO vehicles VALUES('7d2f1a120592aad54ead5a7e441dafcb186edd78282648b83a0a004c0af9','9l','KDN 605K','c9207862d48953a94a97d9d004fd2bf06e28ae2b7c2dffc309146cb646a0','ed1bc63622f02392b8d43245833a3e82d75c142762f4c62013ca5206a1cd','2024-02-06','2024-05-27 03:37:27','1');
INSERT INTO vehicles VALUES('9c2e849d63b9971e9629ca970acfdd54df748c3be7790a5a6b93d194779b','Mercedez Benz MR570','KBA 909L','','74c199f648582debf33240c75d44b1a37e80ff1d14754358229aa2da0617','2024-07-09','','1');
INSERT INTO vehicles VALUES('a378f1963cabcaba03af0d9e36a7c59b6d254213d0e9d65f4ba227ba84b5','Mistubishi 7L Double Cabin','KAA 553H','44e0d0c915200a46cccf9c340a37ee045378731d263f56ed1db110e76e7e','ed1bc63622f02392b8d43245833a3e82d75c142762f4c62013ca5206a1cd','2023-12-23','2024-01-02 16:01:01','1');
INSERT INTO vehicles VALUES('e2cc262c791bed34c2097e5f2fc64b8d02c6a2a08324093fd779fbcd25be','Mercedez S-Class','KDD 777K','c9207862d48953a94a97d9d004fd2bf06e28ae2b7c2dffc309146cb646a0','74c199f648582debf33240c75d44b1a37e80ff1d14754358229aa2da0617','2023-12-25','','1');


