drop database if exists game;
create database game;
use game;
CREATE TABLE if not exists `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(125) NOT NULL,
  `last_name` varchar(125) NOT NULL,
  `email` varchar(255) NOT NULL,
  `created_date` int(11) NOT NULL COMMENT 'unix timestamp',
  `is_active` varchar(3) NOT NULL COMMENT 'yes or no',
  `is_admin` varchar(3) not null COMMENT 'yes or no',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;

INSERT INTO `users` (`id`, `first_name`, `last_name`, `email`, `created_date`, `is_active`,`is_admin`) VALUES
(5, 'First Name', 'Last name', 'first@last.com', 0, '0','0');

CREATE TABLE if not exists `customers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(125) NOT NULL,
  `last_name` varchar(125) NOT NULL,
  `created_date` int(11) NOT NULL COMMENT 'unix timestamp',
  `cardid` varchar(255),
  `checkin` int(11) COMMENT 'unix timestamp',
  `checkout` int(11) COMMENT 'unix timestamp',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;


insert into `customers`(`first_name`,`last_name`,`created_date`,`cardid`,`checkin`,`checkout`) VALUES
	('cust1','cust1',0,0,0,0);


