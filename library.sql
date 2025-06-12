-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 22, 2024 at 09:09 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `library`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `full_name`, `email`, `password`) VALUES
(1, 'Darshnee Sunderraj', 'darshneesunderraj@gmail.com', '$2y$10$FMgiI.cFbdg.No0K.orXWe7mQhkUo3weTNnwgg8/qXoU1u9R3/wCu'),
(2, 'libisha', 'libisha@gmail.com ', 'Libisha');

-- --------------------------------------------------------

--
-- Table structure for table `authors`
--

CREATE TABLE `authors` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `authors`
--

INSERT INTO `authors` (`id`, `name`) VALUES
(1, 'Holly Jackson'),
(2, 'Colleen Hoover'),
(3, 'J. K. Rowling'),
(4, 'James Clear'),
(5, 'Robert Kiyosaki');

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

CREATE TABLE `books` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `author_id` int(11) NOT NULL,
  `description` text NOT NULL,
  `category_id` int(11) NOT NULL,
  `cover` varchar(255) NOT NULL,
  `file` varchar(255) NOT NULL,
  `author` varchar(255) DEFAULT NULL,
  `genre` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`id`, `title`, `author_id`, `description`, `category_id`, `cover`, `file`, `author`, `genre`) VALUES
(1, 'A Good Girl\'s Guide to Murder', 1, 'Pip Fitz-Amobi, a brilliant woman, is unsure whether schoolgirl Andie Bell was killed by her lover Sal Singh five years ago; they must see how far they will go to protect Pip from learning the truth if Sal Singh turns out not to be the murderer.', 4, 'good_girls_guide_to_murder.jpeg', 'A_Good_Girls_Guide_to_Murder_Holly_Jackson.pdf', NULL, NULL),
(2, 'Harry Potter and the Goblet of Fire', 3, 'It is the fourth novel in the Harry Potter series. In this thrilling installment, Harry finds himself unexpectedly chosen as a competitor in the dangerous Triwizard Tournament. As he navigates through various challenges and uncovers dark secrets, he must also face the return of the dark wizard Voldemort.', 8, 'Harry-Potter-and-the-Goblet-of-Fire.jpg', 'Harry-Potter-and-the-Goblet-of-Fire-by-J. K-Rowling.pdf', NULL, NULL),
(3, 'Atomic-Habits', 4, 'Atomic Habits by James Clear is a comprehensive, practical guide on how to change your habits and get 1% better every day. Using a framework called the Four Laws of Behavior Change, Atomic Habits teaches readers a simple set of rules for creating good habits and breaking bad ones.', 1, '6714326b844290.24093787.jpg', '6714326b848772.47365740.pdf', NULL, NULL),
(4, 'Harry-Potter-and-the-Half-Blood-Prince', 3, 'Dumbledore and Harry Potter learn more about Voldemort\'s past and his rise to power. Meanwhile, Harry stumbles upon an old potions textbook belonging to a person calling himself the Half-Blood Prince.', 8, '671432ee36e5f5.94905020.jpg', '671432ee371964.53058271.pdf', NULL, NULL),
(5, 'Harry Potter and the Philosopher\'s Stone', 3, 'Harry Potter, an eleven-year-old orphan, discovers that he is a wizard and is invited to study at Hogwarts. Even as he escapes a dreary life and enters a world of magic, he finds trouble awaiting him.', 8, '6714333e249ea1.80188429.jpeg', '6714333e24b477.64127841.pdf', NULL, NULL),
(6, ' Rich Dad Poor Dad for Teens: The Secrets about Money', 5, 'Rich Dad Poor Dad is about Robert Kiyosaki (author) and his two dads—his real father (poor dad) and the father of his best friend (rich dad)—and the ways in which both men shaped his thoughts about money and investing. The book explodes the myth that you do not need to earn a high income to become rich.', 1, '671433e1b8a767.99227186.jpg', '671433e1b8be88.27877196.pdf', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`) VALUES
(1, 'Self-help book'),
(2, 'Literary fiction'),
(3, 'Young Adult'),
(4, 'Mystery Thriller'),
(5, 'Fiction'),
(6, 'Non-Fiction'),
(7, 'Science Fiction'),
(8, 'Fantasy'),
(9, 'Children\'s Film'),
(10, 'Supernatural'),
(11, 'Supernatural');

-- --------------------------------------------------------

--
-- Table structure for table `downloads`
--

CREATE TABLE `downloads` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `book_id` int(11) NOT NULL,
  `download_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `downloads`
--

INSERT INTO `downloads` (`id`, `user_id`, `book_id`, `download_date`) VALUES
(3, 7, 4, '2024-10-20 15:08:27'),
(4, 2, 6, '2024-10-20 20:32:49'),
(5, 2, 4, '2024-10-20 20:33:01'),
(6, 2, 5, '2024-10-21 08:20:20'),
(7, 2, 1, '2024-10-21 17:14:09'),
(8, 14, 1, '2024-10-21 22:41:38'),
(9, 14, 3, '2024-10-21 22:41:53'),
(10, 9, 5, '2024-10-21 22:50:49'),
(11, 9, 3, '2024-10-21 22:51:12');

-- --------------------------------------------------------

--
-- Table structure for table `follows`
--

CREATE TABLE `follows` (
  `id` int(11) NOT NULL,
  `follower_user_id` int(11) DEFAULT NULL,
  `followed_user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `follow_requests`
--

CREATE TABLE `follow_requests` (
  `id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `receiver_id` int(11) NOT NULL,
  `status` enum('pending','accepted','declined') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `follow_requests`
--

INSERT INTO `follow_requests` (`id`, `sender_id`, `receiver_id`, `status`, `created_at`) VALUES
(1, 2, 7, 'accepted', '2024-10-21 15:36:46'),
(2, 7, 2, 'accepted', '2024-10-21 15:39:25'),
(3, 2, 7, 'pending', '2024-10-21 15:39:35'),
(4, 9, 2, 'accepted', '2024-10-21 23:04:33'),
(5, 2, 9, 'accepted', '2024-10-21 23:20:32');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `auth_method` enum('gmail','register') NOT NULL DEFAULT 'register',
  `interests` varchar(255) DEFAULT NULL,
  `genres` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `username`, `email`, `password`, `avatar`, `auth_method`, `interests`, `genres`) VALUES
(2, 'Darshnee Sunderraj', 'darshneesunderraj', 'darshneesunderraj@gmail.com', '$2y$10$WFQSuYHCkrDEf2Gn1as/p.Oe7nk1LXyw1fcBaW1orjYh1TRTk3Kpi', '6714cb0bc278e9.40433294.jpg', 'register', 'books books books', 'Fantasy , Fiction'),
(7, 'Libisha', 'libi_sha', 'libisha@gmail.com', '$2y$10$P1U8CQkzg8sAiuFpNnQPUuhzf18C7ph38XZaDTQljhfuRnlaJxi8i', '67151cb7c3e934.05578685.jfif', 'register', 'anywhere with books', 'Fantasy , Fiction'),
(9, 'Hency', 'hency', 'hency@gmail.com', '$2y$10$9dWHHgnfAVQ6VLJVHUX5p.fN8QeNmjBkqWnekbcWcpk8/SXgCngrO', '6716dd1a7e13d2.40836269.jpg', 'register', 'uni.', 'Non-Fiction , Fantasy'),
(10, 'Sindhuja R', 'sindhur', 'sindhuja@gmail.com', '$2y$10$Gy.XFYxPOq.Pcw7vV7I42Oa6vPrg0dda.zqqnxyGettIgZr3gwg/S', NULL, 'register', NULL, NULL),
(11, 'Greethika R', 'greeths', 'greethikareddy@gmail.com', '$2y$10$DMwhpFnEcZ67bmGRGpKXjehoCpAh7BkYcr7fp55MrwXL0EP2e1ViG', NULL, 'register', NULL, NULL),
(12, 'Swadithya M', 'swathy', 'sarojs@gmail.com', '$2y$10$hS.YW88SxtidAgzgdUuazO8Oz.UxRlIiV3gIbkFIJc3GHusFrUVdS', NULL, 'register', NULL, NULL),
(13, 'Jebina', 'jebinaw', 'jebina@gmail.com', '$2y$10$gaJ.2OVTz3r2M137fS5AGusdjnryPdHyLFE38Ef4nAMHbbdkDzJO2', NULL, 'register', NULL, NULL),
(14, 'Nisha K', 'nisha', 'nisha@gmail.com', '$2y$10$XpQFX/k.EH2ofiYZS1G8duBm1o.UkbNe0X2HDMRcLYy6hYVY2it9G', '6716d899dd6f05.86957465.jpg', 'register', 'just reading', 'Fantasy , Supernatural');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `authors`
--
ALTER TABLE `authors`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `downloads`
--
ALTER TABLE `downloads`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `book_id` (`book_id`);

--
-- Indexes for table `follows`
--
ALTER TABLE `follows`
  ADD PRIMARY KEY (`id`),
  ADD KEY `follower_user_id` (`follower_user_id`),
  ADD KEY `followed_user_id` (`followed_user_id`);

--
-- Indexes for table `follow_requests`
--
ALTER TABLE `follow_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sender_id` (`sender_id`),
  ADD KEY `receiver_id` (`receiver_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `authors`
--
ALTER TABLE `authors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `books`
--
ALTER TABLE `books`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `downloads`
--
ALTER TABLE `downloads`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `follows`
--
ALTER TABLE `follows`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `follow_requests`
--
ALTER TABLE `follow_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `downloads`
--
ALTER TABLE `downloads`
  ADD CONSTRAINT `downloads_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `downloads_ibfk_2` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `follows`
--
ALTER TABLE `follows`
  ADD CONSTRAINT `follows_ibfk_1` FOREIGN KEY (`follower_user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `follows_ibfk_2` FOREIGN KEY (`followed_user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `follow_requests`
--
ALTER TABLE `follow_requests`
  ADD CONSTRAINT `follow_requests_ibfk_1` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `follow_requests_ibfk_2` FOREIGN KEY (`receiver_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
