-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 17, 2024 at 02:18 PM
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
-- Database: `workforce_management`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `GetWorkerTasks` (`worker_id_param` VARCHAR(10))   BEGIN
    SELECT 
        t.task_id,
        t.task_name,
        t.start_date,
        t.due_date,
        f.farm_name,
        f.farm_location
    FROM 
        task t
    INNER JOIN 
        farm f ON t.farm_id = f.farm_id
    WHERE 
        t.worker_id = worker_id_param;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `farm`
--

CREATE TABLE `farm` (
  `farm_id` varchar(10) NOT NULL,
  `farm_name` varchar(20) DEFAULT NULL,
  `farm_location` varchar(20) DEFAULT NULL,
  `farm_crop` varchar(20) DEFAULT NULL,
  `farmer_id` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `farm`
--

INSERT INTO `farm` (`farm_id`, `farm_name`, `farm_location`, `farm_crop`, `farmer_id`) VALUES
('babli03', 'Babli', 'badagannur', 'Areca', 'anwesh03'),
('giri01', 'girifarm', 'bettampady', 'pepper', 'girish01'),
('krishna03', 'krishna farms', 'eeshwaramangala', 'areca', 'anwesh03'),
('patte22', 'pattefarm', 'patte badagannur', 'pepper', 'harish22');

-- --------------------------------------------------------

--
-- Table structure for table `farmer`
--

CREATE TABLE `farmer` (
  `farmer_id` varchar(10) NOT NULL,
  `farmer_name` varchar(20) DEFAULT NULL,
  `phone_no` bigint(20) DEFAULT NULL,
  `password` char(20) DEFAULT NULL,
  `address` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `farmer`
--

INSERT INTO `farmer` (`farmer_id`, `farmer_name`, `phone_no`, `password`, `address`) VALUES
('anwesh03', 'anwesh', 9686543974, 'anwesh03', 'Badagannur, Puttur'),
('girish01', 'girish', 7845854785, 'girish01', 'bettampady'),
('harish22', 'harish', 8989988998, 'harish22', 'patte house, puttur');

--
-- Triggers `farmer`
--
DELIMITER $$
CREATE TRIGGER `before_insert_farmer` BEFORE INSERT ON `farmer` FOR EACH ROW BEGIN
    DECLARE phone_no_length INT;
    SET phone_no_length = CHAR_LENGTH(NEW.phone_no);
    IF NOT (phone_no_length = 10 AND NEW.phone_no REGEXP '^[0-9]+$') THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Invalid phone number format';
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `feedback_id` varchar(10) NOT NULL,
  `farmer_id` varchar(10) DEFAULT NULL,
  `worker_id` varchar(10) DEFAULT NULL,
  `task_id` varchar(10) DEFAULT NULL,
  `rating` int(11) DEFAULT NULL,
  `comment` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `feedback`
--

INSERT INTO `feedback` (`feedback_id`, `farmer_id`, `worker_id`, `task_id`, `rating`, `comment`) VALUES
('RH1n0eQp', 'anwesh03', 'babu01', 'e6c4e9', 5, 'good at work'),
('Wj793MtM', 'anwesh03', 'babu01', '1814d3', 5, 'Fast and good at work');

-- --------------------------------------------------------

--
-- Table structure for table `task`
--

CREATE TABLE `task` (
  `task_id` varchar(10) NOT NULL,
  `task_name` varchar(20) DEFAULT NULL,
  `farm_id` varchar(10) DEFAULT NULL,
  `worker_id` varchar(10) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `due_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `task`
--

INSERT INTO `task` (`task_id`, `task_name`, `farm_id`, `worker_id`, `start_date`, `due_date`) VALUES
('1814d3', 'areca pick', 'babli03', 'babu01', '2024-03-22', '2024-03-22'),
('a67e09', 'areca pick', 'babli03', 'babu01', '2024-03-29', '2024-03-29'),
('e6c4e9', 'areca pick', 'babli03', 'babu01', '2024-03-23', '2024-03-23'),
('f2f9c3', 'areca pick', 'babli03', 'babu01', '2024-03-24', '2024-03-24'),
('f74edd', 'areca pick', 'babli03', 'babu01', '2024-03-24', '2024-03-25');

-- --------------------------------------------------------

--
-- Table structure for table `task_confirm`
--

CREATE TABLE `task_confirm` (
  `task_id` varchar(10) NOT NULL,
  `task_name` varchar(20) DEFAULT NULL,
  `farm_id` varchar(10) DEFAULT NULL,
  `worker_id` varchar(10) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `due_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `task_confirm`
--

INSERT INTO `task_confirm` (`task_id`, `task_name`, `farm_id`, `worker_id`, `start_date`, `due_date`) VALUES
('ffef0f', 'fdsg', 'Babli', 'Babu12', '2024-02-27', '2024-02-28'),
('71d137', 'asdas', 'Babli', 'Babu12', '2024-02-27', '2024-02-28'),
('98ad00', 'fgh', 'Babli', 'Babu12', '2024-02-27', '2024-02-28'),
('eacfb2', 'dsf', 'anwesh', 'amshb', '2024-02-27', '0221-02-12'),
('d6aef3', 'hgjj', 'anwesh', 'amshb', '2024-02-28', '4443-04-23'),
('297ea6', 'awed', 'anwesh', 'bn', '2024-02-29', '1222-11-12'),
('837151', 'areca pick', 'babli03', 'babu01', '2024-04-02', '2024-04-03'),
('cabd78', 'areca pick', 'giri01', 'babu01', '2024-03-26', '2024-03-28');

-- --------------------------------------------------------

--
-- Table structure for table `worker`
--

CREATE TABLE `worker` (
  `worker_id` varchar(10) NOT NULL,
  `worker_name` varchar(20) DEFAULT NULL,
  `ph_num` bigint(20) DEFAULT NULL,
  `password` char(20) DEFAULT NULL,
  `skill` varchar(20) DEFAULT NULL,
  `salary` int(11) DEFAULT NULL,
  `avail_no_workers` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `worker`
--

INSERT INTO `worker` (`worker_id`, `worker_name`, `ph_num`, `password`, `skill`, `salary`, `avail_no_workers`) VALUES
('babu01', 'babu', 8696745687, 'babu01', 'areca picking', 400, 2),
('nila02', 'nilappa', 8585524556, 'nila02', 'pepper climb', 800, 1),
('suresh03', 'suresh', 9564522235, 'suresh03', 'areca spray', 1200, 1);

--
-- Triggers `worker`
--
DELIMITER $$
CREATE TRIGGER `before_insert_worker` BEFORE INSERT ON `worker` FOR EACH ROW BEGIN
    DECLARE ph_num_length INT;
    SET ph_num_length = CHAR_LENGTH(NEW.ph_num);
    IF NOT (ph_num_length = 10 AND NEW.ph_num REGEXP '^[0-9]+$') THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Invalid phone number format';
    END IF;
END
$$
DELIMITER ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `farm`
--
ALTER TABLE `farm`
  ADD PRIMARY KEY (`farm_id`),
  ADD KEY `farmer_id` (`farmer_id`);

--
-- Indexes for table `farmer`
--
ALTER TABLE `farmer`
  ADD PRIMARY KEY (`farmer_id`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`feedback_id`),
  ADD KEY `farmer_id` (`farmer_id`),
  ADD KEY `worker_id` (`worker_id`),
  ADD KEY `task_id` (`task_id`);

--
-- Indexes for table `task`
--
ALTER TABLE `task`
  ADD PRIMARY KEY (`task_id`),
  ADD KEY `farm_id` (`farm_id`),
  ADD KEY `worker_id` (`worker_id`);

--
-- Indexes for table `worker`
--
ALTER TABLE `worker`
  ADD PRIMARY KEY (`worker_id`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `farm`
--
ALTER TABLE `farm`
  ADD CONSTRAINT `farm_ibfk_1` FOREIGN KEY (`farmer_id`) REFERENCES `farmer` (`farmer_id`) ON DELETE CASCADE;

--
-- Constraints for table `feedback`
--
ALTER TABLE `feedback`
  ADD CONSTRAINT `feedback_ibfk_1` FOREIGN KEY (`farmer_id`) REFERENCES `farmer` (`farmer_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `feedback_ibfk_2` FOREIGN KEY (`worker_id`) REFERENCES `worker` (`worker_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `feedback_ibfk_3` FOREIGN KEY (`task_id`) REFERENCES `task` (`task_id`) ON DELETE CASCADE;

--
-- Constraints for table `task`
--
ALTER TABLE `task`
  ADD CONSTRAINT `task_ibfk_1` FOREIGN KEY (`farm_id`) REFERENCES `farm` (`farm_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `task_ibfk_2` FOREIGN KEY (`worker_id`) REFERENCES `worker` (`worker_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
