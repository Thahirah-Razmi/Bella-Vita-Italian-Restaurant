-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Mar 11, 2025 at 09:42 AM
-- Server version: 8.3.0
-- PHP Version: 8.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bella_vita_italian_restaurant`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

DROP TABLE IF EXISTS `admin`;
CREATE TABLE IF NOT EXISTS `admin` (
  `admin_id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `admin_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(15) DEFAULT NULL,
  PRIMARY KEY (`admin_id`),
  KEY `fk_user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`admin_id`, `user_id`, `admin_name`, `email`, `username`, `password`, `phone`) VALUES
(1, 1, 'Thahirah', 'thahirah@gmail.com', 'Thahirah', 'Hello@123', '0760139886'),
(2, 2, 'Tharindu', 'tharindu@gmail.com', 'cupcake', 'cupcake@369', '0740368506'),
(4, 18, 'Emily', 'emily@gmail.com', 'Emily', 'Emily@123', '0379540268');

-- --------------------------------------------------------

--
-- Table structure for table `contact_messages`
--

DROP TABLE IF EXISTS `contact_messages`;
CREATE TABLE IF NOT EXISTS `contact_messages` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone_number` varchar(20) NOT NULL,
  `message` text NOT NULL,
  `response` text,
  `status` enum('Pending','Resolved') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'Pending',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_contact_customer` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `contact_messages`
--

INSERT INTO `contact_messages` (`id`, `name`, `email`, `phone_number`, `message`, `response`, `status`, `created_at`) VALUES
(1, 'Thaiba', 'thaiba@gmail.com', '0112726194', 'Can I reserve a table for 4 people this Friday?', 'Yes, you can! Please visit our reservation page.', 'Resolved', '2025-03-03 10:57:05'),
(2, 'Thaiba', 'thaiba@gmail.com', '0112726194', 'Do you offer vegan options?', 'Yes! We offer a variety of delicious vegan options on our menu. From fresh salads and hearty pasta dishes to plant-based pizzas and desserts, we have something for everyone. If you have any specific dietary requirements, feel free to ask our staff—we’d be happy to assist!', 'Resolved', '2025-03-03 11:03:10'),
(3, 'Thaiba', 'thaiba@gmail.com', '0112726194', 'Is there a discount for first-time customers?', 'Yes! We love welcoming new guests, and to make your first visit even more special, we offer a 10% discount for first-time customers. Just let our staff know when you place your order! We look forward to serving you.', 'Resolved', '2025-03-03 14:19:28'),
(4, 'Thaiba', 'thaiba@gmail.com', '0112726194', 'What are your opening hours?', NULL, 'Pending', '2025-03-08 08:55:04'),
(5, 'Thaiba', 'thaiba@gmail.com', '0112726194', 'Are you open on the weekends?', NULL, 'Pending', '2025-03-10 17:10:08');

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

DROP TABLE IF EXISTS `customers`;
CREATE TABLE IF NOT EXISTS `customers` (
  `customer_id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `customer_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `birthday` date DEFAULT NULL,
  `loyalty_points` int DEFAULT '0',
  PRIMARY KEY (`customer_id`),
  UNIQUE KEY `unique_customer_email` (`email`),
  KEY `fk_customer_user` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`customer_id`, `user_id`, `customer_name`, `email`, `username`, `password`, `phone`, `birthday`, `loyalty_points`) VALUES
(1, 3, 'Thaiba Razmi', 'thaiba@gmail.com', 'Thaiba', 'thai@123', '0112726194', '2008-04-06', 40),
(2, 5, 'John Doe', 'john.doe@gmail.com', 'johndoe', 'john@123', '0712345678', '1990-01-15', 0),
(3, 6, 'Jane Smith', 'jane.smith@gmail.com', 'janesmith', 'jane@456', '0723456789', '1985-05-20', 10),
(4, 7, 'Alice Brown', 'alice.brown@gmail.com', 'aliceb', 'abc@6789', '0734567890', '1992-07-12', 0),
(5, 8, 'Bob Johnson', 'bob.johnson@gmail.com', 'bobj', 'bj@43210', '0745678901', '1988-11-25', 0),
(6, 9, 'Charlie Davis', 'charlie.davis@gmail.com', 'charlied', 'davis@654', '0756789012', '1995-03-30', 0),
(7, 10, 'Diana Evans', 'diana.evans@gmail.com', 'dianae', 'evans@987', '0767890123', '1991-09-18', 110),
(8, 11, 'Evan Harris', 'evan.harris@gmail.com', 'evanh', 'harris@123', '0778901234', '1987-12-10', 0),
(9, 12, 'Fiona Clark', 'fiona.clark@gmail.com', 'fionac', 'fiona@456', '0789012345', '1993-06-22', 0),
(10, 13, 'George Lewis', 'george.lewis@gmail.com', 'georgel', 'lewis@789', '0790123456', '1989-04-05', 0),
(11, 14, 'Hannah Walker', 'hannah.walker@gmail.com', 'hannahw', 'hannah@321', '0701234567', '1994-08-14', 210),
(13, 16, 'Test', 'test@gmail.com', 'Test', 'test@123', '0197321095', '2000-07-18', 0),
(14, 19, 'Sam', 'sam@gmail.com', 'Sam', 'Sam@12345', '0198762048', '1995-11-21', 0);

-- --------------------------------------------------------

--
-- Table structure for table `menu`
--

DROP TABLE IF EXISTS `menu`;
CREATE TABLE IF NOT EXISTS `menu` (
  `menu_id` int NOT NULL AUTO_INCREMENT,
  `dish_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `category` enum('Starters','Mains','Desserts','Beverages') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'Starters',
  `price` decimal(10,2) NOT NULL,
  `availability` enum('Available','Not Available') DEFAULT 'Available',
  `image_path` varchar(255) NOT NULL,
  PRIMARY KEY (`menu_id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `menu`
--

INSERT INTO `menu` (`menu_id`, `dish_name`, `description`, `category`, `price`, `availability`, `image_path`) VALUES
(1, 'Bruschetta', 'Toasted bread topped with tomatoes, fresh basil, and olive oil.', 'Starters', 2915.35, 'Available', 'menu/Bruschetta.jpg'),
(2, 'Caprese Salad', 'Fresh mozzarella, ripe tomatoes, basil leaves, and balsamic glaze.', 'Starters', 3646.35, 'Available', 'menu/Caprese Salad.jpg'),
(3, 'Fried Calamari', 'Crispy fried squid served with a side of marinara sauce.', 'Starters', 4743.35, 'Available', 'menu/Fried Calamari.jpg'),
(4, 'Antipasto Platter', 'A selection of Italian cured meats, cheeses, olives, and marinated vegetables.', 'Starters', 5493.35, 'Available', 'menu/Antipasto Platter.jpg'),
(5, 'Lasagna', 'Layered pasta, rich meat sauce, béchamel, and mozzarella cheese.', 'Mains', 6208.35, 'Available', 'menu/Lasagna.jpg'),
(6, 'Margherita Pizza', 'Tomato sauce, mozzarella cheese, and fresh basil leaves.', 'Mains', 5493.35, 'Available', 'menu/Margherita Pizza.jpg'),
(7, 'Penne Arrabbiata', 'Penne pasta in a spicy tomato and garlic sauce with red chili flakes.', 'Mains', 5104.65, 'Available', 'menu/Penne Arrabbiata.jpg'),
(8, 'Chicken Parmesan', 'Breaded chicken cutlet topped with marinara sauce and melted mozzarella cheese, served with spaghetti.', 'Mains', 6573.35, 'Available', 'menu/Chicken Parmesan.jpg'),
(9, 'Tiramisu', 'Coffee-flavored dessert with layers of mascarpone cheese and cocoa powder.', 'Desserts', 2548.35, 'Available', 'menu/Tiramisu.jpg'),
(10, 'Panna Cotta', 'Creamy vanilla pudding topped with a berry compote.', 'Desserts', 2188.65, 'Available', 'menu/Panna Cotta.jpg'),
(11, 'Cannoli', 'Crispy pastry shells filled with sweet ricotta cheese and chocolate chips.', 'Desserts', 1821.65, 'Available', 'menu/Cannoli.jpg'),
(12, 'Gelato', 'Italian-style ice cream available in various flavors: vanilla, chocolate, pistachio, and strawberry.', 'Desserts', 1821.65, 'Available', 'menu/Gelato.png'),
(13, 'Espresso', 'Strong and rich Italian coffee.', 'Beverages', 912.50, 'Available', 'menu/Espresso.jpg'),
(14, 'Cappuccino', 'Espresso with steamed milk and a layer of foam on top.', 'Beverages', 1277.50, 'Available', 'menu/Cappuccino.jpeg'),
(15, 'Limoncello', 'Sweet and tangy Italian lemon liqueur.', 'Beverages', 2915.35, 'Available', 'menu/Limoncello.jpg'),
(16, 'Italian Soda', 'Carbonated water with flavored syrup (strawberry, peach, or lemon).', 'Beverages', 1642.50, 'Available', 'menu/Italian Soda.png');

-- --------------------------------------------------------

--
-- Table structure for table `reservations`
--

DROP TABLE IF EXISTS `reservations`;
CREATE TABLE IF NOT EXISTS `reservations` (
  `reservation_id` int NOT NULL AUTO_INCREMENT,
  `customer_id` int NOT NULL,
  `table_id` int DEFAULT NULL,
  `room_id` int DEFAULT NULL,
  `reservation_date` date NOT NULL,
  `reservation_time` time NOT NULL,
  `number_of_guests` int NOT NULL,
  `special_requests` text,
  `status` enum('Pending','Confirmed','Cancelled') DEFAULT 'Pending',
  PRIMARY KEY (`reservation_id`),
  KEY `fk_reservation_customer` (`customer_id`),
  KEY `fk_reservation_table` (`table_id`),
  KEY `fk_reservation_room` (`room_id`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `reservations`
--

INSERT INTO `reservations` (`reservation_id`, `customer_id`, `table_id`, `room_id`, `reservation_date`, `reservation_time`, `number_of_guests`, `special_requests`, `status`) VALUES
(1, 3, 4, 2, '2025-03-10', '18:00:00', 5, 'None', 'Pending'),
(2, 2, 1, 3, '2025-03-15', '19:30:00', 3, 'Window seat preferred', 'Confirmed'),
(3, 5, 6, 1, '2025-03-18', '20:00:00', 5, 'Celebrating birthday, need cake', 'Pending'),
(4, 9, 10, 2, '2025-03-22', '18:45:00', 4, 'Vegetarian options required', 'Confirmed'),
(5, 7, 3, 4, '2025-03-25', '21:00:00', 2, 'Anniversary dinner, quiet spot preferred', 'Pending'),
(6, 4, 8, 3, '2025-03-30', '19:00:00', 6, 'High chair for baby needed', 'Confirmed'),
(7, 1, 10, 3, '2025-03-20', '19:30:00', 10, 'None', 'Pending'),
(8, 1, 6, 4, '2025-03-24', '16:20:00', 5, 'None', 'Pending'),
(10, 7, 1, 2, '2025-04-01', '18:00:00', 4, 'None', 'Pending'),
(11, 7, 3, 3, '2025-04-05', '19:30:00', 6, 'Birthday celebration', 'Confirmed'),
(12, 7, 4, 4, '2025-04-10', '20:00:00', 3, 'Window seat preferred', 'Pending'),
(13, 7, 2, 1, '2025-04-12', '17:45:00', 5, 'High chair needed', 'Confirmed'),
(14, 7, 6, 2, '2025-04-15', '19:15:00', 8, 'Anniversary dinner, quiet spot', 'Pending'),
(15, 7, 7, 3, '2025-04-18', '20:30:00', 4, 'None', 'Confirmed'),
(16, 7, 5, 4, '2025-04-22', '18:45:00', 2, 'Vegetarian options', 'Pending'),
(17, 10, 9, 1, '2025-04-25', '21:00:00', 7, 'Celebrating promotion', 'Confirmed'),
(22, 7, 10, 3, '2025-05-02', '18:30:00', 6, 'Romantic dinner setup', 'Confirmed'),
(23, 7, 4, 2, '2025-04-28', '18:30:00', 5, 'Special dessert.', 'Pending'),
(26, 11, 3, 3, '2025-03-20', '13:00:00', 2, 'Romantic dinner setup.', 'Pending'),
(29, 1, 3, 2, '2025-03-09', '13:50:00', 4, 'No', 'Pending');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

DROP TABLE IF EXISTS `reviews`;
CREATE TABLE IF NOT EXISTS `reviews` (
  `review_id` int NOT NULL AUTO_INCREMENT,
  `customer_id` int NOT NULL,
  `review_text` text,
  `rating` int DEFAULT NULL,
  PRIMARY KEY (`review_id`),
  KEY `fk_review_customer` (`customer_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`review_id`, `customer_id`, `review_text`, `rating`) VALUES
(1, 1, 'The food was amazing! The pasta was cooked perfectly, and the service was excellent. Highly recommend!', 5),
(2, 2, 'Great atmosphere and friendly staff. The pizza was delicious, and the dessert was the perfect ending to the meal.', 4),
(3, 3, 'I had a wonderful dining experience. The pasta was fresh and flavorful. Will definitely come back!', 5),
(4, 4, 'The service was a bit slow, but the food made up for it. The tiramisu was the best I’ve ever had!', 4);

-- --------------------------------------------------------

--
-- Table structure for table `rooms`
--

DROP TABLE IF EXISTS `rooms`;
CREATE TABLE IF NOT EXISTS `rooms` (
  `room_id` int NOT NULL AUTO_INCREMENT,
  `room_type` enum('Chef''s Counter','Main Dining Area','Private Dining Room','Terrace Lounge') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `capacity` int NOT NULL,
  `status` enum('Available','Occupied','Reserved') DEFAULT 'Available',
  PRIMARY KEY (`room_id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `rooms`
--

INSERT INTO `rooms` (`room_id`, `room_type`, `capacity`, `status`) VALUES
(1, 'Chef\'s Counter', 5, 'Available'),
(2, 'Main Dining Area', 20, 'Available'),
(3, 'Private Dining Room', 20, 'Available'),
(4, 'Terrace Lounge', 20, 'Available');

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

DROP TABLE IF EXISTS `staff`;
CREATE TABLE IF NOT EXISTS `staff` (
  `staff_id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `staff_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `shift` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`staff_id`),
  KEY `fk_staff_user` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `staff`
--

INSERT INTO `staff` (`staff_id`, `user_id`, `staff_name`, `email`, `username`, `password`, `phone`, `shift`) VALUES
(1, 4, 'Saad', 'saad@gmail.com', 'Saad', 'test@123', '0779557057', 'Morning'),
(2, 20, 'Tharani', 'tharani@gmail.com', 'Tharani', 'Tharani@123', '0773593459', 'Afternoon');

-- --------------------------------------------------------

--
-- Table structure for table `tables`
--

DROP TABLE IF EXISTS `tables`;
CREATE TABLE IF NOT EXISTS `tables` (
  `table_id` int NOT NULL AUTO_INCREMENT,
  `table_number` int NOT NULL,
  `table_type` enum('Standard','VIP','Outdoor') NOT NULL,
  `capacity` int NOT NULL,
  `status` enum('Available','Occupied','Reserved') DEFAULT 'Available',
  PRIMARY KEY (`table_id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tables`
--

INSERT INTO `tables` (`table_id`, `table_number`, `table_type`, `capacity`, `status`) VALUES
(1, 1, 'Standard', 4, 'Available'),
(2, 2, 'Standard', 5, 'Available'),
(3, 3, 'VIP', 4, 'Available'),
(4, 4, 'Outdoor', 6, 'Available'),
(5, 5, 'Standard', 6, 'Available'),
(6, 6, 'VIP', 5, 'Available'),
(7, 7, 'Outdoor', 5, 'Available'),
(8, 8, 'Standard', 5, 'Available'),
(9, 9, 'VIP', 6, 'Available'),
(10, 10, 'Outdoor', 4, 'Available'),
(11, 11, 'VIP', 5, 'Available'),
(12, 12, 'Outdoor', 5, 'Available');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `user_id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `email` varchar(100) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `role` enum('Admin','Staff','Customer') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `name`, `email`, `username`, `password`, `phone`, `role`) VALUES
(1, 'Thahirah', 'thahirah@gmail.com', 'Thahirah', 'Hello@123', '0760139886', 'Admin'),
(2, 'Tharindu', 'tharindu@gmail.com', 'cupcake', 'cupcake@369', '0740368506', 'Admin'),
(3, 'Thaiba Razmi', 'thaiba@gmail.com', 'Thaiba', 'thai@123', '0112726194', 'Customer'),
(4, 'Saad', 'saad@gmail.com', 'Saad', 'test@123', '0779557057', 'Staff'),
(5, 'John Doe', 'john.doe@gmail.com', 'johndoe', 'john@123', '0712345678', 'Customer'),
(6, 'Jane Smith', 'jane.smith@gmail.com', 'janesmith', 'jane@456', '0723456789', 'Customer'),
(7, 'Alice Brown', 'alice.brown@gmail.com', 'aliceb', 'abc@6789', '0734567890', 'Customer'),
(8, 'Bob Johnson', 'bob.johnson@gmail.com', 'bobj', 'bj@43210', '0745678901', 'Customer'),
(9, 'Charlie Davis', 'charlie.davis@gmail.com', 'charlied', 'davis@654', '0756789012', 'Customer'),
(10, 'Diana Evans', 'diana.evans@gmail.com', 'dianae', 'evans@987', '0767890123', 'Customer'),
(11, 'Evan Harris', 'evan.harris@gmail.com', 'evanh', 'harris@123', '0778901234', 'Customer'),
(12, 'Fiona Clark', 'fiona.clark@gmail.com', 'fionac', 'fiona@456', '0789012345', 'Customer'),
(13, 'George Lewis', 'george.lewis@gmail.com', 'georgel', 'lewis@789', '0790123456', 'Customer'),
(14, 'Hannah Walker', 'hannah.walker@gmail.com', 'hannahw', 'hannah@321', '0701234567', 'Customer'),
(16, 'Test', 'test@gmail.com', 'Test', 'test@123', '0197321095', 'Customer'),
(18, 'Emily', 'emily@gmail.com', 'Emily', 'Emily@123', '0379540268', 'Admin'),
(19, 'Sam', 'sam@gmail.com', 'Sam', 'Sam@12345', '0198762048', 'Customer'),
(20, 'Tharani', 'tharani@gmail.com', 'Tharani', 'Tharani@123', '0773593459', 'Staff');

--
-- Triggers `users`
--
DROP TRIGGER IF EXISTS `update_admin_user`;
DELIMITER $$
CREATE TRIGGER `update_admin_user` AFTER UPDATE ON `users` FOR EACH ROW BEGIN
    UPDATE admin 
    SET admin_name = NEW.name, email = NEW.email, username = NEW.username, phone = NEW.phone
    WHERE user_id = NEW.user_id;
END
$$
DELIMITER ;
DROP TRIGGER IF EXISTS `update_customer_user`;
DELIMITER $$
CREATE TRIGGER `update_customer_user` AFTER INSERT ON `users` FOR EACH ROW BEGIN
    UPDATE customers 
    SET customer_name = NEW.name, email = NEW.email, username = NEW.username, phone = NEW.phone
    WHERE user_id = NEW.user_id;
END
$$
DELIMITER ;
DROP TRIGGER IF EXISTS `update_staff_user`;
DELIMITER $$
CREATE TRIGGER `update_staff_user` AFTER UPDATE ON `users` FOR EACH ROW BEGIN
    UPDATE staff 
    SET staff_name = NEW.name, email = NEW.email, username = NEW.username, phone = NEW.phone
    WHERE user_id = NEW.user_id;
END
$$
DELIMITER ;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admin`
--
ALTER TABLE `admin`
  ADD CONSTRAINT `fk_admin_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD CONSTRAINT `fk_contact_customer` FOREIGN KEY (`email`) REFERENCES `customers` (`email`) ON DELETE CASCADE;

--
-- Constraints for table `customers`
--
ALTER TABLE `customers`
  ADD CONSTRAINT `fk_customer_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `reservations`
--
ALTER TABLE `reservations`
  ADD CONSTRAINT `fk_reservation_customer` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`customer_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_reservation_room` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`room_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_reservation_table` FOREIGN KEY (`table_id`) REFERENCES `tables` (`table_id`) ON DELETE SET NULL;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `fk_review_customer` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`customer_id`) ON DELETE CASCADE;

--
-- Constraints for table `staff`
--
ALTER TABLE `staff`
  ADD CONSTRAINT `fk_staff_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
