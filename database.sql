
-- all the SQl you should run to create the necessary tables for Nexus connect, gathered from php myadmin using the query SHOW CREATE TABLE tablename;

-- ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci , this ensures uniqueness/integrity within the database


--user table
CREATE TABLE `users` (
  `username` varchar(25) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` char(255) NOT NULL,
  `register_date` datetime NOT NULL DEFAULT current_timestamp(),
  `full_name` text NOT NULL,
  `profile_picture` varchar(255) DEFAULT 'default.jpg',
  PRIMARY KEY (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci  

--posts table
CREATE TABLE `posts` (
  `post_id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `post_type` varchar(50) DEFAULT 'text',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `image_path` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`post_id`),
  KEY `username` (`username`),
  CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`username`) REFERENCES `users` (`username`) -- foreign key
) ENGINE=InnoDB AUTO_INCREMENT=58 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci

CREATE TABLE `messages` (
    message_id INT(11) NOT NULL AUTO_INCREMENT,
    sender_id VARCHAR(255) NOT NULL,
    receiver_id VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    timestamp DATETIME DEFAULT CURRENT_TIMESTAMP,
    message_type ENUM('text') DEFAULT 'text',
    PRIMARY KEY (message_id)
);



