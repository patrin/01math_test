-- phpMyAdmin SQL Dump
-- version 4.9.7
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Dec 16, 2021 at 10:56 AM
-- Server version: 5.7.32
-- PHP Version: 7.4.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `01math_test_db`
--

--
-- Dumping data for table `promocode`
--

INSERT INTO `promocode` (`id`, `promocode`, `plus_days`, `available_from`, `available_to`, `fixed_date`, `registration_only`) VALUES
(1, 'FREE', NULL, '2021-12-01 00:00:00', '2021-12-31 00:00:00', '2022-01-15 23:59:59', 0);

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `fullname`, `email`, `password`, `paid_to`) VALUES
(1, 'John Doe', 'johndoe@example.com', '$2y$10$KuWBuY81dSEMe49/ILYco.jZr6.5W3OQq7m0O37Dke7YrtfE3fRzi', '2021-12-22 21:54:34'),
(2, 'Jane Doe', 'janedoe@example.com', '$2y$10$fQy7r52gAt/dLup9HxM5n.XprAS7zUWkWevLMLdbXM/2Z0qlIFdXG', '2022-10-30 20:50:00');

--
-- Dumping data for table `user_promocode`
--

INSERT INTO `user_promocode` (`user_id`, `promocode_id`) VALUES
(1, 1);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
