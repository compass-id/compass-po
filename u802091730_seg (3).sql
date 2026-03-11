-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Mar 10, 2026 at 10:52 PM
-- Server version: 8.0.30
-- PHP Version: 8.5.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `u802091730_seg`
--

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

CREATE TABLE `books` (
  `id` int NOT NULL,
  `title` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `isbn` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `category` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `cefr` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `cover_image` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `base_price` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `stock` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_featured` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `early_bird_price` decimal(10,2) DEFAULT '0.00',
  `early_bird_end_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`id`, `title`, `isbn`, `category`, `cefr`, `cover_image`, `base_price`, `stock`, `is_featured`, `early_bird_price`, `early_bird_end_date`) VALUES
(1, 'The Three Little Pigs SB', '9781599666402', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2025-05/9781599666402.jpg', '75600', '1000', '1', 0.00, NULL),
(2, 'The Three Little Pigs WB', '9781599667003', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2025-05/9781599667003.jpg', '32400', '1000', '1', 0.00, NULL),
(3, 'The Cooking Pot SB', '9788984466050', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2025-05/9788984466050.jpg', '64800', '1000', '1', 0.00, NULL),
(4, 'The Cooking Pot WB', '9788984467385', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2025-05/9788984467385.jpg', '37800', '1000', '1', 0.00, NULL),
(5, 'Listening Express 2', '9781613527603', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2025-03/9781613527603.PNG', '172800', '1000', '1', 0.00, NULL),
(6, 'Listening Express 1', '9781613527597', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2025-03/9781613527597.PNG', '172800', '1000', '1', 0.00, NULL),
(7, 'New Frontiers 1(WB+BIGBOX)', '9781640152175', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781640152175.png', '86400', '1000', '1', 0.00, NULL),
(8, 'New Frontiers 2(WB+BIGBOX)', '9781640152182', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781640152182.png', '86400', '1000', '1', 0.00, NULL),
(9, 'New Frontiers 3(WB+BIGBOX)', '9781640152199', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781640152199.png', '86400', '1000', '1', 0.00, NULL),
(10, 'New Frontiers 4(WB+BIGBOX)', '9781640152205', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781640152205.png', '86400', '1000', '1', 0.00, NULL),
(11, 'New Frontiers 5(WB+BIGBOX)', '9781640152212', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781640152212.png', '86400', '1000', '1', 0.00, NULL),
(12, 'New Frontiers 6(WB+CD)', '9781640152229', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781640152229.png', '86400', '1000', '1', 0.00, NULL),
(13, 'Very Easy Reading 4th 1 WB', '9781640152298', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781640152298.png', '32400', '1000', '1', 0.00, NULL),
(14, 'Very Easy Reading 4th 2 WB', '9781640152304', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781640152304.png', '32400', '1000', '1', 0.00, NULL),
(15, 'Very Easy Reading 4th 3 WB', '9781640152311', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781640152311.png', '32400', '1000', '1', 0.00, NULL),
(16, 'Very Easy Reading 4th 4 WB', '9781640152328', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781640152328.png', '32400', '1000', '1', 0.00, NULL),
(17, 'Fast Phonics (SB+CD)', '9781640153127', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781640153127.png', '172800', '1000', '1', 0.00, NULL),
(18, 'Fast Phonics WB', '9781640153134', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781640153134.png', '54000', '1000', '1', 0.00, NULL),
(19, '2000 Core English Words 1', '9781640153417', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781640153417.png', '172800', '1000', '1', 0.00, NULL),
(20, '2000 Core English Words 2', '9781640153424', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781640153424.png', '172800', '1000', '1', 0.00, NULL),
(21, '2000 Core English Words 3', '9781640153431', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781640153431.png', '172800', '1000', '1', 0.00, NULL),
(22, '2000 Core English Words 4', '9781640153448', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781640153448.png', '172800', '1000', '1', 0.00, NULL),
(23, '1000 Basic English Words 1<New Cover>', '9781640153721', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781640153721.png', '162000', '1000', '1', 0.00, NULL),
(24, '1000 Basic English Words 2<New Cover>', '9781640153738', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781640153738.png', '162000', '1000', '1', 0.00, NULL),
(25, '1000 Basic English Words 3<New Cover>', '9781640153745', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781640153745.png', '162000', '1000', '1', 0.00, NULL),
(26, '1000 Basic English Words 4<New Cover>', '9781640153752', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781640153752.png', '162000', '1000', '1', 0.00, NULL),
(27, 'Integrate Listening & Speaking Basic 1(SB+CD+BIGBOX)', '9781640153769', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781640153769.png', '194400', '1000', '1', 0.00, NULL),
(28, 'Integrate Listening & Speaking Basic 2(SB+CD+BIGBOX)', '9781640153776', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781640153776.png', '194400', '1000', '1', 0.00, NULL),
(29, 'Integrate Listening & Speaking Basic 3(SB+CD+BIGBOX)', '9781640153783', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781640153783.png', '194400', '1000', '1', 0.00, NULL),
(30, 'Integrate Listening & Speaking Basic 4(SB+CD+BIGBOX)', '9781640153790', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781640153790.png', '194400', '1000', '1', 0.00, NULL),
(31, 'Building Skills for Listening 1 (SB+BIGBOX)', '9781640153806', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781640153806.png', '172800', '1000', '1', 0.00, NULL),
(32, 'Building Skills for Listening 2 (SB+BIGBOX)', '9781640153813', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781640153813.png', '172800', '1000', '1', 0.00, NULL),
(33, 'Building Skills for Listening 3 (SB+BIGBOX)', '9781640153820', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781640153820.png', '172800', '1000', '1', 0.00, NULL),
(34, 'Expanding Skills for Listening 1 (SB+BIGBOX)', '9781640153837', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781640153837.png', '172800', '1000', '1', 0.00, NULL),
(35, 'Expanding Skills for Listening 2 (SB+BIGBOX)', '9781640153844', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781640153844.png', '172800', '1000', '1', 0.00, NULL),
(36, 'Expanding Skills for Listening 3 (SB+BIGBOX)', '9781640153851', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781640153851.png', '172800', '1000', '1', 0.00, NULL),
(37, 'Activating Skills for Listening 1 (SB+BIGBOX)', '9781640153868', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781640153868.png', '172800', '1000', '1', 0.00, NULL),
(38, 'Activating Skills for Listening 2 (SB+BIGBOX)', '9781640153875', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781640153875.png', '172800', '1000', '1', 0.00, NULL),
(39, 'Activating Skills for Listening 3 (SB+BIGBOX)', '9781640153882', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781640153882.png', '172800', '1000', '1', 0.00, NULL),
(40, 'Writing Framework (Sentence) 1 (SB+BIGBOX)', '9781640153950', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781640153950.png', '162000', '1000', '1', 0.00, NULL),
(41, 'Writing Framework (Sentence) 2 (SB+BIGBOX)', '9781640153967', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781640153967.png', '162000', '1000', '1', 0.00, NULL),
(42, 'Writing Framework (Sentence) 3 (SB+BIGBOX)', '9781640153974', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781640153974.png', '162000', '1000', '1', 0.00, NULL),
(43, 'Blueprint British English 1 (SB+BIGBOX)', '9781640154438', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781640154438.png', '205200', '1000', '1', 0.00, NULL),
(44, 'Blueprint British English 2 (SB+BIGBOX)', '9781640154445', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781640154445.png', '205200', '1000', '1', 0.00, NULL),
(45, 'Blueprint British English 3 (SB+BIGBOX)', '9781640154452', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781640154452.png', '205200', '1000', '1', 0.00, NULL),
(46, 'Blueprint British English 4 (SB+BIGBOX)', '9781640154469', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781640154469.png', '205200', '1000', '1', 0.00, NULL),
(47, 'Blueprint British English 5 (SB+BIGBOX)', '9781640154476', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781640154476.png', '205200', '1000', '1', 0.00, NULL),
(48, 'Blueprint British English 6 (SB+BIGBOX)', '9781640154483', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781640154483.png', '205200', '1000', '1', 0.00, NULL),
(49, 'Blueprint British English 7 (SB+BIGBOX)', '9781640154490', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781640154490.png', '205200', '1000', '1', 0.00, NULL),
(50, 'Blueprint British English 1 (WB+MP3)', '9781640154506', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781640154506.png', '86400', '1000', '1', 0.00, NULL),
(51, 'Blueprint British English 2 (WB+MP3)', '9781640154513', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781640154513.png', '86400', '1000', '1', 0.00, NULL),
(52, 'Blueprint British English 3 (WB+MP3)', '9781640154520', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781640154520.png', '86400', '1000', '1', 0.00, NULL),
(53, 'Blueprint British English 4 (WB+MP3)', '9781640154537', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781640154537.png', '86400', '1000', '1', 0.00, NULL),
(54, 'Blueprint British English 5 (WB+MP3)', '9781640154544', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781640154544.png', '86400', '1000', '1', 0.00, NULL),
(55, 'Blueprint British English 6 (WB+MP3)', '9781640154551', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781640154551.png', '86400', '1000', '1', 0.00, NULL),
(56, 'Blueprint British English 7 (WB+MP3)', '9781640154568', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781640154568.png', '86400', '1000', '1', 0.00, NULL),
(57, 'New Frontiers British English 1 (SB+BIGBOX)', '9781640154735', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781640154735.png', '205200', '1000', '1', 0.00, NULL),
(58, 'New Frontiers British English 2 (SB+BIGBOX)', '9781640154742', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781640154742.png', '205200', '1000', '1', 0.00, NULL),
(59, 'New Frontiers British English 3 (SB+BIGBOX)', '9781640154759', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781640154759.png', '205200', '1000', '1', 0.00, NULL),
(60, 'New Frontiers British English 1 (WB+BIGBOX)', '9781640154766', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781640154766.png', '86400', '1000', '1', 0.00, NULL),
(61, 'New Frontiers British English 2 (WB+BIGBOX)', '9781640154773', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781640154773.png', '86400', '1000', '1', 0.00, NULL),
(62, 'New Frontiers British English 3 (WB+BIGBOX)', '9781640154780', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781640154780.png', '86400', '1000', '1', 0.00, NULL),
(63, 'Discovering Skills for Listening 1 (SB+BIGBOX)', '9781640155466', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781640155466.png', '172800', '1000', '1', 0.00, NULL),
(64, 'Discovering Skills for Listening 2 (SB+BIGBOX)', '9781640155473', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781640155473.png', '172800', '1000', '1', 0.00, NULL),
(65, 'Discovering Skills for Listening 3 (SB+BIGBOX)', '9781640155480', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781640155480.png', '172800', '1000', '1', 0.00, NULL),
(66, 'Integrate Listening & Speaking Building 1(SB+CD+BIGBOX)', '9781640155497', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781640155497.png', '194400', '1000', '1', 0.00, NULL),
(67, 'Integrate Listening & Speaking Building 2(SB+CD+BIGBOX)', '9781640155503', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781640155503.png', '194400', '1000', '1', 0.00, NULL),
(68, 'Integrate Listening & Speaking Building 3(SB+CD+BIGBOX)', '9781640155510', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781640155510.png', '194400', '1000', '1', 0.00, NULL),
(69, 'Integrate Listening & Speaking Building 4(SB+CD+BIGBOX)', '9781640155527', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781640155527.png', '194400', '1000', '1', 0.00, NULL),
(70, 'New Frontiers British English 4 (SB+BIGBOX)', '9781640155640', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781640155640.png', '205200', '1000', '1', 0.00, NULL),
(71, 'New Frontiers British English 5 (SB+BIGBOX)', '9781640155657', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781640155657.png', '205200', '1000', '1', 0.00, NULL),
(72, 'New Frontiers British English 6 (SB+BIGBOX)', '9781640155664', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781640155664.png', '205200', '1000', '1', 0.00, NULL),
(73, 'New Frontiers British English 4 (WB+BIGBOX)', '9781640155671', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781640155671.png', '86400', '1000', '1', 0.00, NULL),
(74, 'New Frontiers British English 5 (WB+BIGBOX)', '9781640155688', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781640155688.png', '86400', '1000', '1', 0.00, NULL),
(75, 'New Frontiers British English 6 (WB+BIGBOX)', '9781640155695', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781640155695.png', '86400', '1000', '1', 0.00, NULL),
(76, 'Writing Framework (Paragraph) 1 (SB+BIGBOX)', '9781640156166', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781640156166.png', '162000', '1000', '1', 0.00, NULL),
(77, 'Writing Framework (Paragraph) 2 (SB+BIGBOX)', '9781640156173', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781640156173.png', '162000', '1000', '1', 0.00, NULL),
(78, 'Writing Framework (Paragraph) 3 (SB+BIGBOX)', '9781640156180', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781640156180.png', '162000', '1000', '1', 0.00, NULL),
(79, 'Writing Framework (Essay) 1 (SB+BIGBOX)', '9781640156197', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781640156197.png', '162000', '1000', '1', 0.00, NULL),
(80, 'Writing Framework (Essay) 2 (SB+BIGBOX)', '9781640156203', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781640156203.png', '162000', '1000', '1', 0.00, NULL),
(81, 'Writing Framework (Essay) 3 (SB+BIGBOX)', '9781640156210', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781640156210.png', '162000', '1000', '1', 0.00, NULL),
(82, 'Sounds Great 2nd 1 (SB+BIGBOX)', '9781640156487', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781640156487.png', '162000', '1000', '1', 0.00, NULL),
(83, 'Sounds Great 2nd 2 (SB+BIGBOX)', '9781640156494', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781640156494.png', '162000', '1000', '1', 0.00, NULL),
(84, 'Sounds Great 2nd 3 (SB+BIGBOX)', '9781640156500', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781640156500.png', '162000', '1000', '1', 0.00, NULL),
(85, 'Sounds Great 2nd 4 (SB+BIGBOX)', '9781640156517', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781640156517.png', '162000', '1000', '1', 0.00, NULL),
(86, 'Sounds Great 2nd 5 (SB+BIGBOX)', '9781640156524', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781640156524.png', '162000', '1000', '1', 0.00, NULL),
(87, 'Sounds Great 2nd 1 Workbook (WB+BIGBOX)', '9781640156531', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781640156531.png', '54000', '1000', '1', 0.00, NULL),
(88, 'Sounds Great 2nd 2 Workbook (WB+BIGBOX)', '9781640156548', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781640156548.png', '54000', '1000', '1', 0.00, NULL),
(89, 'Sounds Great 2nd 3 Workbook (WB+BIGBOX)', '9781640156555', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781640156555.png', '54000', '1000', '1', 0.00, NULL),
(90, 'Sounds Great 2nd 4 Workbook (WB+BIGBOX)', '9781640156562', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781640156562.png', '54000', '1000', '1', 0.00, NULL),
(91, 'Sounds Great 2nd 5 Workbook (WB+BIGBOX)', '9781640156579', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781640156579.png', '54000', '1000', '1', 0.00, NULL),
(92, 'Sounds Great Readers 2nd Edition 1', '9781640157323', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781640157323.png', '81000', '1000', '1', 0.00, NULL),
(93, 'Sounds Great Readers 2nd Edition 2', '9781640157330', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781640157330.png', '81000', '1000', '1', 0.00, NULL),
(94, 'Sounds Great Readers 2nd Edition 3', '9781640157347', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781640157347.png', '81000', '1000', '1', 0.00, NULL),
(95, 'Sounds Great Readers 2nd Edition 4', '9781640157354', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781640157354.png', '81000', '1000', '1', 0.00, NULL),
(96, 'Sounds Great Readers 2nd Edition 5', '9781640157361', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781640157361.png', '81000', '1000', '1', 0.00, NULL),
(97, 'STEAM Reading Beginner 1', '9781640157378', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781640157378.png', '172800', '1000', '1', 0.00, NULL),
(98, 'STEAM Reading Beginner 2', '9781640157385', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781640157385.png', '172800', '1000', '1', 0.00, NULL),
(99, 'STEAM Reading Beginner 3', '9781640157392', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781640157392.png', '172800', '1000', '1', 0.00, NULL),
(100, 'Sounds Great 2nd 1Set(SB+WB+SGR)', '9781640158429', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781640158429.png', '264600', '1000', '1', 0.00, NULL),
(101, 'Reading for the Real World 4/e Intro', '9781640158436', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781640158436.png', '205200', '1000', '1', 0.00, NULL),
(102, 'Reading for the Real World 4/e 1', '9781640158443', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781640158443.png', '205200', '1000', '1', 0.00, NULL),
(103, 'Reading for the Real World 4/e 2', '9781640158450', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781640158450.png', '205200', '1000', '1', 0.00, NULL),
(104, 'Reading for the Real World 4/e 3', '9781640158467', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781640158467.png', '205200', '1000', '1', 0.00, NULL),
(105, 'Sounds Great 2nd 2Set(SB+WB+SGR)', '9781640158474', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781640158474.png', '264600', '1000', '1', 0.00, NULL),
(106, 'Sounds Great 2nd 3Set(SB+WB+SGR)', '9781640158481', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781640158481.png', '264600', '1000', '1', 0.00, NULL),
(107, 'Sounds Great 2nd 4Set(SB+WB+SGR)', '9781640158498', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781640158498.png', '264600', '1000', '1', 0.00, NULL),
(108, 'Sounds Great 2nd 5Set(SB+WB+SGR)', '9781640158504', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781640158504.png', '264600', '1000', '1', 0.00, NULL),
(109, 'STEAM Reading Elementary 1', '9781640158757', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781640158757.png', '172800', '1000', '1', 0.00, NULL),
(110, 'STEAM Reading Elementary 2', '9781640158764', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781640158764.png', '172800', '1000', '1', 0.00, NULL),
(111, 'STEAM Reading Elementary 3', '9781640158771', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781640158771.png', '172800', '1000', '1', 0.00, NULL),
(112, 'STEAM Reading High Elementary 1', '9781640158917', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781640158917.png', '172800', '1000', '1', 0.00, NULL),
(113, 'STEAM Reading High Elementary 2', '9781640158924', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781640158924.png', '172800', '1000', '1', 0.00, NULL),
(114, 'STEAM Reading High Elementary 3', '9781640158931', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781640158931.png', '172800', '1000', '1', 0.00, NULL),
(115, 'Splash! 1 Student Book', '9781640158979', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781640158979.png', '216000', '1000', '1', 0.00, NULL),
(116, 'Splash! 2 Student Book', '9781640158986', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781640158986.png', '216000', '1000', '1', 0.00, NULL),
(117, 'Splash! 3 Student Book', '9781640158993', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781640158993.png', '216000', '1000', '1', 0.00, NULL),
(118, 'Splash! 1 Activity Book', '9781640159006', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781640159006.png', '129600', '1000', '1', 0.00, NULL),
(119, 'Splash! 2 Activity Book', '9781640159013', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781640159013.png', '129600', '1000', '1', 0.00, NULL),
(120, 'Splash! 3 Activity Book', '9781640159020', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781640159020.png', '129600', '1000', '1', 0.00, NULL),
(121, 'On Point 1 Second Edition', '9781685910792', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781685910792.png', '210600', '1000', '1', 0.00, NULL),
(122, 'On Point 2 Second Edition', '9781685910808', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781685910808.png', '210600', '1000', '1', 0.00, NULL),
(123, 'On Point 3 Second Edition', '9781685910815', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781685910815.png', '210600', '1000', '1', 0.00, NULL),
(124, 'Vivid Reading with Fiction Starter 1', '9781685911669', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781685911669.png', '172800', '1000', '1', 0.00, NULL),
(125, 'Vivid Reading with Fiction Starter 2', '9781685911676', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781685911676.png', '172800', '1000', '1', 0.00, NULL),
(126, 'Vivid Reading with Fiction Starter 3', '9781685911683', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781685911683.png', '172800', '1000', '1', 0.00, NULL),
(127, 'Vivid Reading with Fiction Basic 1', '9781685911690', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781685911690.png', '172800', '1000', '1', 0.00, NULL),
(128, 'Vivid Reading with Fiction Basic 2', '9781685911706', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781685911706.png', '172800', '1000', '1', 0.00, NULL),
(129, 'Vivid Reading with Fiction Basic 3', '9781685911713', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781685911713.png', '172800', '1000', '1', 0.00, NULL),
(130, 'Vivid Reading with Fiction and Nonfiction Plus 1', '9781685911720', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781685911720.png', '172800', '1000', '1', 0.00, NULL),
(131, 'Vivid Reading with Fiction and Nonfiction Plus 2', '9781685911737', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781685911737.png', '172800', '1000', '1', 0.00, NULL),
(132, 'Vivid Reading with Fiction and Nonfiction Plus 3', '9781685911744', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781685911744.png', '172800', '1000', '1', 0.00, NULL),
(133, 'Vivid Reading with Fiction and Nonfiction Master 1', '9781685911751', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781685911751.png', '172800', '1000', '1', 0.00, NULL),
(134, 'Vivid Reading with Fiction and Nonfiction Master 2', '9781685911768', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781685911768.png', '172800', '1000', '1', 0.00, NULL),
(135, 'Vivid Reading with Fiction and Nonfiction Master 3', '9781685911775', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781685911775.png', '172800', '1000', '1', 0.00, NULL),
(136, 'Boost English 1 SB', '9781685912758', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781685912758.png', '172800', '1000', '1', 0.00, NULL),
(137, 'Boost English 1 WB', '9781685912765', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781685912765.png', '86400', '1000', '1', 0.00, NULL),
(138, 'Boost English 2 SB', '9781685912772', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781685912772.png', '172800', '1000', '1', 0.00, NULL),
(139, 'Boost English 2 WB', '9781685912789', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781685912789.png', '86400', '1000', '1', 0.00, NULL),
(140, 'Boost English 3 SB', '9781685912796', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781685912796.png', '172800', '1000', '1', 0.00, NULL),
(141, 'Boost English 3 WB', '9781685912802', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781685912802.png', '86400', '1000', '1', 0.00, NULL),
(142, 'Boost English 4 SB', '9781685912819', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781685912819.png', '172800', '1000', '1', 0.00, NULL),
(143, 'Boost English 4 WB', '9781685912826', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781685912826.png', '86400', '1000', '1', 0.00, NULL),
(144, 'Boost English 5 SB', '9781685912833', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781685912833.png', '172800', '1000', '1', 0.00, NULL),
(145, 'Boost English 5 WB', '9781685912840', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781685912840.png', '86400', '1000', '1', 0.00, NULL),
(146, 'Sounds Great 2nd 6 (SB+BIGBOX)', '9781685913250', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781685913250.png', '162000', '1000', '1', 0.00, NULL),
(147, 'Sounds Great 2nd 6 Workbook (WB+BIGBOX)', '9781685913267', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781685913267.png', '54000', '1000', '1', 0.00, NULL),
(148, 'Rhythm Grammar Basic 1 Student Book', '9781685913342', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781685913342.png', '140400', '1000', '1', 0.00, NULL),
(149, 'Rhythm Grammar Basic 2 Student Book', '9781685913359', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781685913359.png', '140400', '1000', '1', 0.00, NULL),
(150, 'Rhythm Grammar Basic 3 Student Book', '9781685913366', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781685913366.png', '140400', '1000', '1', 0.00, NULL),
(151, 'Rhythm Grammar Intermediate 1 Student Book', '9781685913373', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781685913373.png', '140400', '1000', '1', 0.00, NULL),
(152, 'Rhythm Grammar Intermediate 2 Student Book', '9781685913380', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781685913380.png', '140400', '1000', '1', 0.00, NULL),
(153, 'Rhythm Grammar Intermediate 3 Student Book', '9781685913397', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781685913397.png', '140400', '1000', '1', 0.00, NULL),
(154, 'Building Skills for the TOEFL iBT 3rd Ed. Listening', '9781685913472', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781685913472.png', '248400', '1000', '1', 0.00, NULL),
(155, 'Building Skills for the TOEFL iBT 3rd Ed. Reading', '9781685913489', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781685913489.png', '205200', '1000', '1', 0.00, NULL),
(156, 'Building Skills for the TOEFL iBT 3rd Ed. Speaking', '9781685913496', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781685913496.png', '205200', '1000', '1', 0.00, NULL),
(157, 'Building Skills for the TOEFL iBT 3rd Ed. Writing', '9781685913502', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781685913502.png', '205200', '1000', '1', 0.00, NULL),
(158, 'Developing Skills for the TOEFL iBT 3rd Ed. Listening', '9781685913526', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781685913526.png', '248400', '1000', '1', 0.00, NULL),
(159, 'Developing Skills for the TOEFL iBT 3rd Ed. Reading', '9781685913533', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781685913533.png', '205200', '1000', '1', 0.00, NULL),
(160, 'Developing Skills for the TOEFL iBT 3rd Ed. Speaking', '9781685913540', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781685913540.png', '205200', '1000', '1', 0.00, NULL),
(161, 'Developing Skills for the TOEFL iBT 3rd Ed. Writing', '9781685913557', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781685913557.png', '205200', '1000', '1', 0.00, NULL),
(162, 'Mastering Skills for the TOEFL iBT 3rd Ed. Listening', '9781685913571', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781685913571.png', '248400', '1000', '1', 0.00, NULL),
(163, 'Mastering Skills for the TOEFL iBT 3rd Ed. Reading', '9781685913588', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781685913588.png', '205200', '1000', '1', 0.00, NULL),
(164, 'Mastering Skills for the TOEFL iBT 3rd Ed. Speaking', '9781685913595', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781685913595.png', '205200', '1000', '1', 0.00, NULL),
(165, 'Mastering Skills for the TOEFL iBT 3rd Ed. Writing', '9781685913601', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781685913601.png', '205200', '1000', '1', 0.00, NULL),
(166, 'Sounds Great Readers 2nd Edition 6', '9781685914356', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781685914356.png', '81000', '1000', '1', 0.00, NULL),
(167, 'Boost English 6 SB', '9781685914448', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781685914448.png', '172800', '1000', '1', 0.00, NULL),
(168, 'Boost English 6 WB', '9781685914455', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781685914455.png', '86400', '1000', '1', 0.00, NULL),
(169, 'Sounds Great 2nd 6Set(SB+WB+SGR)', '9781685914837', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781685914837.png', '264600', '1000', '1', 0.00, NULL),
(170, 'Future Literacy 30 - 1', '9781685916565', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781685916565.png', '172800', '1000', '1', 0.00, NULL),
(171, 'Future Literacy 30 - 2', '9781685916572', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781685916572.png', '172800', '1000', '1', 0.00, NULL),
(172, 'Future Literacy 30 - 3', '9781685916589', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781685916589.png', '172800', '1000', '1', 0.00, NULL),
(173, 'Future Literacy 50 - 1', '9781685916596', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781685916596.png', '172800', '1000', '1', 0.00, NULL),
(174, 'Future Literacy 50 - 2', '9781685916602', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781685916602.png', '172800', '1000', '1', 0.00, NULL),
(175, 'Future Literacy 50 - 3', '9781685916619', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781685916619.png', '172800', '1000', '1', 0.00, NULL),
(176, 'Future Literacy 80 - 1', '9781685916626', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781685916626.png', '172800', '1000', '1', 0.00, NULL),
(177, 'Future Literacy 80 - 2', '9781685916633', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781685916633.png', '172800', '1000', '1', 0.00, NULL),
(178, 'Future Literacy 80 - 3', '9781685916640', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781685916640.png', '172800', '1000', '1', 0.00, NULL),
(179, 'Future Literacy 100 - 1', '9781685916657', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781685916657.png', '172800', '1000', '1', 0.00, NULL),
(180, 'Future Literacy 100 - 2', '9781685916664', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781685916664.png', '172800', '1000', '1', 0.00, NULL),
(181, 'Future Literacy 100 - 3', '9781685916671', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781685916671.png', '172800', '1000', '1', 0.00, NULL),
(182, '한국어 메이트 1', '9781685917432', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781685917432.png', '237600', '1000', '1', 0.00, NULL),
(183, '한국어 메이트 2', '9781685917449', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781685917449.png', '237600', '1000', '1', 0.00, NULL),
(184, '한국어 메이트 3', '9781685917456', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781685917456.png', '237600', '1000', '1', 0.00, NULL),
(185, '한국어 메이트 4', '9781685917463', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781685917463.png', '237600', '1000', '1', 0.00, NULL),
(186, 'Sounds Fun 1 (Book+CD)', '9781932222692', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781932222692.png', '162000', '1000', '1', 0.00, NULL),
(187, 'Sounds Fun 2 (Book+CD)', '9781932222708', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781932222708.png', '162000', '1000', '1', 0.00, NULL),
(188, 'Sounds Fun 3 (Book+CD)', '9781932222715', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781932222715.png', '162000', '1000', '1', 0.00, NULL),
(189, 'Sounds Fun 4 (Book+2CDs)', '9781932222722', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781932222722.png', '162000', '1000', '1', 0.00, NULL),
(190, 'First Nonfiction Reading 1 (SB+MP3)', '9781945387227', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781945387227.png', '172800', '1000', '1', 0.00, NULL),
(191, 'First Nonfiction Reading 2 (SB+MP3)', '9781945387234', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781945387234.png', '172800', '1000', '1', 0.00, NULL),
(192, 'First Nonfiction Reading 3 (SB+MP3)', '9781945387241', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781945387241.png', '172800', '1000', '1', 0.00, NULL),
(193, 'Jungle Phonics 1 (SB+BIGBOX)', '9781945387319', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781945387319.png', '151200', '1000', '1', 0.00, NULL),
(194, 'Jungle Phonics 2 (SB+BIGBOX)', '9781945387326', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781945387326.png', '151200', '1000', '1', 0.00, NULL),
(195, 'Jungle Phonics 3 (SB+BIGBOX)', '9781945387333', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781945387333.png', '151200', '1000', '1', 0.00, NULL),
(196, 'Jungle Phonics 4 (SB+BIGBOX)', '9781945387340', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781945387340.png', '151200', '1000', '1', 0.00, NULL),
(197, 'Jungle Phonics 1 Workbook', '9781945387449', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781945387449.png', '54000', '1000', '1', 0.00, NULL),
(198, 'Jungle Phonics 2 Workbook', '9781945387456', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781945387456.png', '54000', '1000', '1', 0.00, NULL),
(199, 'Jungle Phonics 3 Workbook', '9781945387463', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781945387463.png', '54000', '1000', '1', 0.00, NULL),
(200, 'Jungle Phonics 4 Workbook', '9781945387470', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781945387470.png', '54000', '1000', '1', 0.00, NULL),
(201, 'Jazz English 3rd Book 1(SB+MP3)', '9788966978588', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9788966978588.png', '194400', '1000', '1', 0.00, NULL),
(202, 'Jazz English 3rd 1 Workbook and Solo Practice', '9788966978595', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9788966978595.png', '75600', '1000', '1', 0.00, NULL),
(203, 'Jazz English 3rd Book 2(SB+MP3)', '9788966978601', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9788966978601.png', '194400', '1000', '1', 0.00, NULL),
(204, 'Jazz English 3rd 1 Workbook and Solo Practice', '9788966978618', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9788966978618.png', '86400', '1000', '1', 0.00, NULL),
(205, 'Rhythm Grammar Basic 1 Practice Book', '9791162374405', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9791162374405.png', '97200', '1000', '1', 0.00, NULL),
(206, 'Rhythm Grammar Basic 2 Practice Book', '9791162374412', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9791162374412.png', '97200', '1000', '1', 0.00, NULL),
(207, 'Rhythm Grammar Basic 3 Practice Book', '9791162374429', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9791162374429.png', '97200', '1000', '1', 0.00, NULL),
(208, 'Rhythm Grammar Intermediate 1 Practice Book', '9791162374436', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9791162374436.png', '97200', '1000', '1', 0.00, NULL),
(209, 'Rhythm Grammar Intermediate 2 Practice Book', '9791162374443', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9791162374443.png', '97200', '1000', '1', 0.00, NULL),
(210, 'Rhythm Grammar Intermediate 3 Practice Book', '9791162374450', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9791162374450.png', '97200', '1000', '1', 0.00, NULL),
(211, '수능 트레이닝 (유형편)', '9791162374511', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9791162374511.png', '151200', '1000', '1', 0.00, NULL),
(212, '21st Century Communication 2nd Student Book 1(Spark)', '9798214059259', 'Cengage Learning', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9798214059259.png', '345600', '1000', '1', 0.00, NULL),
(213, '21st Century Communication 2nd Student Book 2(Spark)', '9798214059266', 'Cengage Learning', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9798214059266.png', '345600', '1000', '1', 0.00, NULL),
(214, '21st Century Communication 2nd Student Book 3(Spark)', '9798214059273', 'Cengage Learning', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9798214059273.png', '345600', '1000', '1', 0.00, NULL),
(215, '21st Century Communication 2nd Student Book 4(Spark)', '9798214059280', 'Cengage Learning', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9798214059280.png', '345600', '1000', '1', 0.00, NULL),
(216, 'Future Literacy 150 1', '9798893460933', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9798893460933.png', '172800', '1000', '1', 0.00, NULL),
(217, 'Future Literacy 150 2', '9798893460940', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9798893460940.png', '172800', '1000', '1', 0.00, NULL),
(218, 'Future Literacy 150 3', '9798893460957', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9798893460957.png', '172800', '1000', '1', 0.00, NULL),
(219, 'Future Literacy 200 1', '9798893460964', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9798893460964.png', '172800', '1000', '1', 0.00, NULL),
(220, 'Future Literacy 200 2', '9798893460971', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9798893460971.png', '172800', '1000', '1', 0.00, NULL),
(221, 'Future Literacy 200 3', '9798893460988', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9798893460988.png', '172800', '1000', '1', 0.00, NULL),
(222, 'Future Literacy 250 1', '9798893460995', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9798893460995.png', '172800', '1000', '1', 0.00, NULL),
(223, 'Future Literacy 250 2', '9798893461008', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9798893461008.png', '172800', '1000', '1', 0.00, NULL),
(224, 'Future Literacy 250 3', '9798893461015', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9798893461015.png', '172800', '1000', '1', 0.00, NULL),
(225, 'Rise and Shine American Level 6 Workbook with eBook', '9781292398853', 'Pearson Education ESL', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781292398853.png', '194400', '1000', '1', 0.00, NULL),
(226, 'Rise and Shine American Level 1 Student\'s Book with eBook and Digital Activities', '9781292417370', 'Pearson Education ESL', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781292417370.png', '259200', '1000', '1', 0.00, NULL),
(227, 'Rise and Shine American Level 2 Student\'s Book with eBook and Digital Activities', '9781292417394', 'Pearson Education ESL', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781292417394.png', '259200', '1000', '1', 0.00, NULL),
(228, 'Rise and Shine American Level 3 Student\'s Book with eBook and Digital Activities', '9781292417400', 'Pearson Education ESL', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781292417400.png', '259200', '1000', '1', 0.00, NULL),
(229, 'Rise and Shine American Level 4 Student\'s Book with eBook and Digital Activities', '9781292417417', 'Pearson Education ESL', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781292417417.png', '259200', '1000', '1', 0.00, NULL),
(230, 'Rise and Shine American Level 5 Student\'s Book with eBook and Digital Activities', '9781292417424', 'Pearson Education ESL', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781292417424.png', '259200', '1000', '1', 0.00, NULL),
(231, 'Rise and Shine American Level 6 Student\'s Book with eBook and Digital Activities', '9781292417431', 'Pearson Education ESL', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781292417431.png', '259200', '1000', '1', 0.00, NULL),
(232, '21st Century Reading Student Book L1', '9781305264595', 'Cengage Learning', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781305264595.png', '345600', '1000', '1', 0.00, NULL),
(233, '21st Century Reading Student Book L2', '9781305265707', 'Cengage Learning', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781305265707.png', '345600', '1000', '1', 0.00, NULL),
(234, '21st Century Reading Student Book L3', '9781305265714', 'Cengage Learning', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781305265714.png', '345600', '1000', '1', 0.00, NULL),
(235, '21st Century Reading Student Book L4', '9781305265721', 'Cengage Learning', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781305265721.png', '345600', '1000', '1', 0.00, NULL),
(236, 'Grammar for Great Writing Book A SB', '9781337115834', 'Cengage Learning', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781337115834.png', '345600', '1000', '1', 0.00, NULL),
(237, 'Grammar for Great Writing Book B SB', '9781337118606', 'Cengage Learning', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781337118606.png', '345600', '1000', '1', 0.00, NULL),
(238, 'Grammar for Great Writing Book C SB', '9781337118613', 'Cengage Learning', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781337118613.png', '345600', '1000', '1', 0.00, NULL),
(239, 'Reading Builder 1 (SB+CD)', '9781599660127', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781599660127.png', '172800', '1000', '1', 0.00, NULL),
(240, 'Reading Builder 2 (SB+CD)', '9781599660134', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781599660134.png', '172800', '1000', '1', 0.00, NULL),
(241, 'Reading Builder 3 (SB+CD)', '9781599660141', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781599660141.png', '172800', '1000', '1', 0.00, NULL),
(242, 'Essay Writing for Beginners 1 (SB+CD)', '9781599660424', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781599660424.png', '172800', '1000', '1', 0.00, NULL),
(243, 'Essay Writing for Beginners 2 Student\'s Book', '9781599660431', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781599660431.png', '172800', '1000', '1', 0.00, NULL),
(244, 'Listening Practice Through Dictation 1 (SB+CD)', '9781599661049', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781599661049.png', '183600', '1000', '1', 0.00, NULL),
(245, 'Listening Practice Through Dictation 2 (SB+CD)', '9781599661056', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781599661056.png', '183600', '1000', '1', 0.00, NULL),
(246, 'Listening Practice Through Dictation 3 (SB+CD)', '9781599661063', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781599661063.png', '183600', '1000', '1', 0.00, NULL),
(247, 'Listening Practice Through Dictation 4 (SB+CD)', '9781599661070', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781599661070.png', '183600', '1000', '1', 0.00, NULL),
(248, 'Reading Time 1(SB+CD)', '9781599661445', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781599661445.png', '172800', '1000', '1', 0.00, NULL),
(249, 'Reading Time 2(SB+CD)', '9781599661452', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781599661452.png', '172800', '1000', '1', 0.00, NULL),
(250, 'Reading Time 3(SB+CD)', '9781599661469', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781599661469.png', '172800', '1000', '1', 0.00, NULL),
(251, 'Basic TOEFL iBT Listening 1 (SB+2CDS)', '9781599661513', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781599661513.png', '172800', '1000', '1', 0.00, NULL),
(252, 'Basic TOEFL IBT Speaking 1 (SB+1CD)', '9781599661520', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781599661520.png', '151200', '1000', '1', 0.00, NULL),
(253, 'Basic TOEFL IBT Reading 1 Student\'s Book', '9781599661537', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781599661537.png', '140400', '1000', '1', 0.00, NULL),
(254, 'Basic TOEFL IBT Writing 1 (SB+CD)', '9781599661544', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781599661544.png', '151200', '1000', '1', 0.00, NULL),
(255, 'Basic TOEFL IBT Listening 2 (SB+3CDs)', '9781599661551', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781599661551.png', '194400', '1000', '1', 0.00, NULL);
INSERT INTO `books` (`id`, `title`, `isbn`, `category`, `cefr`, `cover_image`, `base_price`, `stock`, `is_featured`, `early_bird_price`, `early_bird_end_date`) VALUES
(256, 'Basic TOEFL IBT Speaking 2 (SB+1CD)', '9781599661568', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781599661568.png', '151200', '1000', '1', 0.00, NULL),
(257, 'Basic TOEFL IBT Reading 2 Student\'s Book', '9781599661575', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781599661575.png', '140400', '1000', '1', 0.00, NULL),
(258, 'Basic TOEFL IBT Writing 2 (SB+CD)', '9781599661582', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781599661582.png', '151200', '1000', '1', 0.00, NULL),
(259, 'Basic TOEFL IBT Listening 3 (SB+3CDs)', '9781599661599', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781599661599.png', '216000', '1000', '1', 0.00, NULL),
(260, 'Basic TOEFL IBT Speaking 3 (SB+2CD)', '9781599661605', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781599661605.png', '172800', '1000', '1', 0.00, NULL),
(261, 'Basic TOEFL IBT Reading 3 Student\'s Book', '9781599661612', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781599661612.png', '140400', '1000', '1', 0.00, NULL),
(262, 'Basic TOEFL IBT Writing 3 (SB+CD)', '9781599661629', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781599661629.png', '151200', '1000', '1', 0.00, NULL),
(263, 'Communicate 1 (SB+CD)', '9781599661766', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781599661766.png', '183600', '1000', '1', 0.00, NULL),
(264, 'Communicate 2 (SB+CD)', '9781599661797', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781599661797.png', '183600', '1000', '1', 0.00, NULL),
(265, 'Motivate 1 (SB+CD)', '9781599661827', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781599661827.png', '183600', '1000', '1', 0.00, NULL),
(266, 'Motivate 2 (SB+CD)', '9781599661858', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781599661858.png', '183600', '1000', '1', 0.00, NULL),
(267, 'Writing Bright 1 SB', '9781599661940', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781599661940.png', '162000', '1000', '1', 0.00, NULL),
(268, 'Writing Bright 2 SB', '9781599661971', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781599661971.png', '162000', '1000', '1', 0.00, NULL),
(269, 'Writing Bright 3 SB', '9781599662008', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781599662008.png', '162000', '1000', '1', 0.00, NULL),
(270, 'Listening to the News;Voice of America 1 (SB+MP3)', '9781599662091', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781599662091.png', '172800', '1000', '1', 0.00, NULL),
(271, 'Listening to the News;Voice of America 2 (SB+MP3)', '9781599662121', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781599662121.png', '172800', '1000', '1', 0.00, NULL),
(272, 'Listening to the News;Voice of America 3 (SB+MP3)', '9781599662152', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781599662152.png', '172800', '1000', '1', 0.00, NULL),
(273, 'Very Easy Writing 1 (SB+CD)', '9781599662244', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781599662244.png', '162000', '1000', '1', 0.00, NULL),
(274, 'Very Easy Writing 2 (SB+CD)', '9781599662275', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781599662275.png', '162000', '1000', '1', 0.00, NULL),
(275, 'Very Easy Writing 3 (SB+CD)', '9781599662305', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781599662305.png', '162000', '1000', '1', 0.00, NULL),
(276, 'Writing Starter 2nd 1 Student\'s Book', '9781599662336', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781599662336.png', '162000', '1000', '1', 0.00, NULL),
(277, 'Writing Starter 2nd 2 Student\'s Book', '9781599662367', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781599662367.png', '162000', '1000', '1', 0.00, NULL),
(278, 'Writing Starter 2nd 3 Student\'s Book', '9781599662398', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781599662398.png', '162000', '1000', '1', 0.00, NULL),
(279, 'Communicate Workbook 1', '9781599662459', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781599662459.png', '86400', '1000', '1', 0.00, NULL),
(280, 'Communicate Workbook 2', '9781599662480', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781599662480.png', '86400', '1000', '1', 0.00, NULL),
(281, 'Motivate Workbook 1', '9781599662510', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781599662510.png', '86400', '1000', '1', 0.00, NULL),
(282, 'Motivate Workbook 2', '9781599662541', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781599662541.png', '86400', '1000', '1', 0.00, NULL),
(283, 'Reading The World Now 1 (SB+MP3)', '9781599662572', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781599662572.png', '194400', '1000', '1', 0.00, NULL),
(284, 'Reading The World Now 2 (SB+MP3)', '9781599662602', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781599662602.png', '194400', '1000', '1', 0.00, NULL),
(285, 'Reading The World Now 3 (SB+MP3)', '9781599662633', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781599662633.png', '194400', '1000', '1', 0.00, NULL),
(286, 'Write On 1 Student\'s Book', '9781599662879', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781599662879.png', '162000', '1000', '1', 0.00, NULL),
(287, 'Write On 2 Student\'s Book', '9781599662909', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781599662909.png', '162000', '1000', '1', 0.00, NULL),
(288, 'Write On 3 Student\'s Book', '9781599662930', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781599662930.png', '162000', '1000', '1', 0.00, NULL),
(289, 'Active English Grammar 1 2nd 1 Book', '9781599662961', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781599662961.png', '172800', '1000', '1', 0.00, NULL),
(290, 'Active English Grammar 2 2nd 2 Book', '9781599662992', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781599662992.png', '172800', '1000', '1', 0.00, NULL),
(291, 'Active English Grammar 3 2nd 3 Book', '9781599663029', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781599663029.png', '172800', '1000', '1', 0.00, NULL),
(292, 'Active English Grammar 4 2nd 4 Book', '9781599663050', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781599663050.png', '172800', '1000', '1', 0.00, NULL),
(293, 'Active English Grammar 5 2nd 5 Book', '9781599663081', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781599663081.png', '172800', '1000', '1', 0.00, NULL),
(294, 'Active English Grammar 6 2nd 6 Book', '9781599663111', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781599663111.png', '172800', '1000', '1', 0.00, NULL),
(295, 'Listening Success 1(SB+MP3)', '9781599663968', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781599663968.png', '172800', '1000', '1', 0.00, NULL),
(296, 'Listening Success 2(SB+MP3)', '9781599663975', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781599663975.png', '172800', '1000', '1', 0.00, NULL),
(297, 'Listening Success 3(SB+MP3)', '9781599663982', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781599663982.png', '172800', '1000', '1', 0.00, NULL),
(298, 'Listening Success 4(SB+MP3)', '9781599663999', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781599663999.png', '172800', '1000', '1', 0.00, NULL),
(299, 'Listening Success 5(SB+MP3)', '9781599664002', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781599664002.png', '172800', '1000', '1', 0.00, NULL),
(300, 'Just Speak Up 1 (SB+MP3)', '9781599664163', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781599664163.png', '183600', '1000', '1', 0.00, NULL),
(301, 'Just Speak Up 2 (SB+MP3)', '9781599664170', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781599664170.png', '183600', '1000', '1', 0.00, NULL),
(302, 'Just Speak Up 3 (SB+MP3)', '9781599664187', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781599664187.png', '183600', '1000', '1', 0.00, NULL),
(303, 'Listening Time 1(SB+MP3)', '9781599664231', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781599664231.png', '162000', '1000', '1', 0.00, NULL),
(304, 'Listening Time 2(SB+MP3)', '9781599664248', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781599664248.png', '162000', '1000', '1', 0.00, NULL),
(305, 'Listening Time 3(SB+MP3)', '9781599664255', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781599664255.png', '162000', '1000', '1', 0.00, NULL),
(306, 'Target Listening 1(SB+MP3)', '9781599664972', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781599664972.png', '183600', '1000', '1', 0.00, NULL),
(307, 'Target Listening Practice Tests1 (SB+MP3)', '9781599664989', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781599664989.png', '183600', '1000', '1', 0.00, NULL),
(308, 'Target Listening 2(SB+MP3)', '9781599664996', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781599664996.png', '183600', '1000', '1', 0.00, NULL),
(309, 'Target Listening Practice Tests2 (SB+MP3)', '9781599665009', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781599665009.png', '183600', '1000', '1', 0.00, NULL),
(310, 'Target Listening Practice Tests3 (SB+MP3)', '9781599665016', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781599665016.png', '183600', '1000', '1', 0.00, NULL),
(311, 'Target Listening Practice Tests4 (SB+MP3)', '9781599665023', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781599665023.png', '183600', '1000', '1', 0.00, NULL),
(312, 'Reading Lamp 1 With CD(SB+CD)', '9781599665092', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781599665092.png', '172800', '1000', '1', 0.00, NULL),
(313, 'Reading Lamp 2 With CD(SB+CD)', '9781599665108', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781599665108.png', '172800', '1000', '1', 0.00, NULL),
(314, 'Reading Lamp 3 With CD(SB+CD)', '9781599665115', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781599665115.png', '172800', '1000', '1', 0.00, NULL),
(315, 'Reading Table 1 (SB+CD)', '9781599665122', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781599665122.png', '172800', '1000', '1', 0.00, NULL),
(316, 'Reading Table 2 (SB+CD)', '9781599665139', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781599665139.png', '172800', '1000', '1', 0.00, NULL),
(317, 'Reading Table 3 (SB+CD)', '9781599665146', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781599665146.png', '172800', '1000', '1', 0.00, NULL),
(318, 'Reading Challenge 2nd 1 (SB+CD)', '9781599665290', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781599665290.png', '172800', '1000', '1', 0.00, NULL),
(319, 'Reading Challenge 2nd 2 (SB+CD)', '9781599665306', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781599665306.png', '172800', '1000', '1', 0.00, NULL),
(320, 'Reading Challenge 2nd 3 (SB+CD)', '9781599665313', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781599665313.png', '172800', '1000', '1', 0.00, NULL),
(321, 'Grammar Starter 1 Student\'s book', '9781599665351', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781599665351.png', '151200', '1000', '1', 0.00, NULL),
(322, 'Grammar Starter 2 Student\'s book', '9781599665368', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781599665368.png', '151200', '1000', '1', 0.00, NULL),
(323, 'Grammar Starter 3 Student\'s book', '9781599665375', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781599665375.png', '151200', '1000', '1', 0.00, NULL),
(324, 'Grammar Starter 1 Workbook', '9781599665382', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781599665382.png', '43200', '1000', '1', 0.00, NULL),
(325, 'Grammar Starter 2 Workbook', '9781599665399', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781599665399.png', '43200', '1000', '1', 0.00, NULL),
(326, 'Grammar Starter 3 Workbook', '9781599665405', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781599665405.png', '43200', '1000', '1', 0.00, NULL),
(327, 'Speaking Tutor 1A (SB+CD)', '9781599665436', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781599665436.png', '183600', '1000', '1', 0.00, NULL),
(328, 'Speaking Tutor 1B (SB+CD)', '9781599665443', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781599665443.png', '183600', '1000', '1', 0.00, NULL),
(329, 'Speaking Tutor 2A (SB+CD)', '9781599665450', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781599665450.png', '183600', '1000', '1', 0.00, NULL),
(330, 'Speaking Tutor 2B (SB+CD)', '9781599665467', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781599665467.png', '183600', '1000', '1', 0.00, NULL),
(331, 'Speaking Tutor 3A (SB+BIGBOX)', '9781599665474', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781599665474.png', '183600', '1000', '1', 0.00, NULL),
(332, 'Speaking Tutor 3B (SB+CD)', '9781599665481', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781599665481.png', '183600', '1000', '1', 0.00, NULL),
(333, 'Writing Tutor 1A Student\'s Book', '9781599665498', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781599665498.png', '162000', '1000', '1', 0.00, NULL),
(334, 'Writing Tutor 1B Student\'s Book', '9781599665504', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781599665504.png', '162000', '1000', '1', 0.00, NULL),
(335, 'Writing Tutor 2A Student\'s Book', '9781599665511', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781599665511.png', '162000', '1000', '1', 0.00, NULL),
(336, 'Writing Tutor 2B Student\'s Book', '9781599665528', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781599665528.png', '162000', '1000', '1', 0.00, NULL),
(337, 'Writing Tutor 3A (SB+CD)', '9781599665535', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781599665535.png', '172800', '1000', '1', 0.00, NULL),
(338, 'Writing Tutor 3B (SB+CD)', '9781599665542', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781599665542.png', '172800', '1000', '1', 0.00, NULL),
(339, 'Grammar Success 1 Student\'s book', '9781599665610', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781599665610.png', '151200', '1000', '1', 0.00, NULL),
(340, 'Grammar Success 2 Student\'s book', '9781599665627', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781599665627.png', '151200', '1000', '1', 0.00, NULL),
(341, 'Grammar Success 3 Student\'s book', '9781599665634', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781599665634.png', '151200', '1000', '1', 0.00, NULL),
(342, 'Grammar Success 1 Workbook', '9781599665641', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781599665641.png', '43200', '1000', '1', 0.00, NULL),
(343, 'Grammar Success 2 Workbook', '9781599665658', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781599665658.png', '43200', '1000', '1', 0.00, NULL),
(344, 'Grammar Success 3 Workbook', '9781599665665', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781599665665.png', '43200', '1000', '1', 0.00, NULL),
(345, 'Speaking by Speaking (SB+MP3)', '9781599665719', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781599665719.png', '183600', '1000', '1', 0.00, NULL),
(346, 'Hot Topics 1 (SB+MP3)', '9781599665948', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781599665948.png', '183600', '1000', '1', 0.00, NULL),
(347, 'Hot Topics 2 (SB+MP3)', '9781599665955', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781599665955.png', '183600', '1000', '1', 0.00, NULL),
(348, 'Listening Jump 1(SB+MP3)', '9781599665979', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781599665979.png', '172800', '1000', '1', 0.00, NULL),
(349, 'Listening Jump 2(SB+MP3)', '9781599665986', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781599665986.png', '172800', '1000', '1', 0.00, NULL),
(350, 'Listening Jump 3(SB+MP3)', '9781599665993', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781599665993.png', '172800', '1000', '1', 0.00, NULL),
(351, 'Reading Success 2nd 1(SB+BIGBOX)', '9781599666006', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781599666006.png', '172800', '1000', '1', 0.00, NULL),
(352, 'Reading Success 2nd 2(SB+BIGBOX)', '9781599666013', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781599666013.png', '172800', '1000', '1', 0.00, NULL),
(353, 'Reading Success 2nd 3(SB+BIGBOX)', '9781599666020', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781599666020.png', '172800', '1000', '1', 0.00, NULL),
(354, 'Reading Success 2nd 4(SB+BIGBOX)', '9781599666037', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781599666037.png', '172800', '1000', '1', 0.00, NULL),
(355, 'Reading Success 2nd 5(SB+BIGBOX)', '9781599666044', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781599666044.png', '172800', '1000', '1', 0.00, NULL),
(356, 'Reading Success 2nd 6(SB+BIGBOX)', '9781599666051', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781599666051.png', '172800', '1000', '1', 0.00, NULL),
(357, 'Reading Shelf 1 (SB+CD)', '9781599666068', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781599666068.png', '172800', '1000', '1', 0.00, NULL),
(358, 'Reading Shelf 2 (SB+CD)', '9781599666075', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781599666075.png', '172800', '1000', '1', 0.00, NULL),
(359, 'Reading Shelf 3 (SB+CD)', '9781599666082', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781599666082.png', '172800', '1000', '1', 0.00, NULL),
(360, 'Reading Discovery 1(SB+MP3)', '9781599666150', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781599666150.png', '172800', '1000', '1', 0.00, NULL),
(361, 'Reading Discovery 2(SB+MP3)', '9781599666167', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781599666167.png', '172800', '1000', '1', 0.00, NULL),
(362, 'Reading Discovery 3(SB+MP3)', '9781599666174', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781599666174.png', '172800', '1000', '1', 0.00, NULL),
(363, 'Speaking Time 1(SB+CD)', '9781599666181', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781599666181.png', '172800', '1000', '1', 0.00, NULL),
(364, 'Speaking Time 2(SB+CD)', '9781599666198', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781599666198.png', '172800', '1000', '1', 0.00, NULL),
(365, 'Speaking Time 3(SB+CD)', '9781599666204', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781599666204.png', '172800', '1000', '1', 0.00, NULL),
(366, 'Reading Jump 1 With CD(SB+WB)', '9781599666266', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781599666266.png', '172800', '1000', '1', 0.00, NULL),
(367, 'Reading Jump 2 With CD(SB+WB)', '9781599666273', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781599666273.png', '172800', '1000', '1', 0.00, NULL),
(368, 'Reading Jump 3 With CD(SB+WB)', '9781599666280', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781599666280.png', '172800', '1000', '1', 0.00, NULL),
(369, 'Reading Jump Plus 1 (SB+CD)', '9781599666297', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781599666297.png', '172800', '1000', '1', 0.00, NULL),
(370, 'Reading Jump Plus 2 (SB+CD)', '9781599666303', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781599666303.png', '172800', '1000', '1', 0.00, NULL),
(371, 'Reading Jump Plus 3 (SB+CD)', '9781599666310', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781599666310.png', '172800', '1000', '1', 0.00, NULL),
(372, 'Real Easy Reading 2nd 1 (SB+CD)', '9781613524473', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781613524473.png', '172800', '1000', '1', 0.00, NULL),
(373, 'Real Easy Reading 2nd 2 (SB+BIGBOX)', '9781613524480', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781613524480.png', '172800', '1000', '1', 0.00, NULL),
(374, 'Real Easy Reading 2nd 3 (SB+BIGBOX)', '9781613524497', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781613524497.png', '172800', '1000', '1', 0.00, NULL),
(375, 'Issues Now in the News 3rd (SB+MP3)', '9781613524558', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781613524558.png', '194400', '1000', '1', 0.00, NULL),
(376, 'Guided Writing Plus 1 (With Practice book)', '9781613524640', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781613524640.png', '162000', '1000', '1', 0.00, NULL),
(377, 'Guided Writing Plus 2 (With Practice book)', '9781613524657', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781613524657.png', '162000', '1000', '1', 0.00, NULL),
(378, 'Guided Writing Plus 3 (With Practice book)', '9781613524664', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781613524664.png', '162000', '1000', '1', 0.00, NULL),
(379, 'Guided Writing 1 Student\'s Book', '9781613524671', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781613524671.png', '162000', '1000', '1', 0.00, NULL),
(380, 'Guided Writing 2 Student\'s Book', '9781613524688', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781613524688.png', '162000', '1000', '1', 0.00, NULL),
(381, 'Guided Writing 3 Student\'s Book', '9781613524695', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781613524695.png', '162000', '1000', '1', 0.00, NULL),
(382, 'Listening Starter 2nd 1 (SB+MP3)', '9781613525067', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781613525067.png', '162000', '1000', '1', 0.00, NULL),
(383, 'Listening Starter 2nd 2 (SB+MP3)', '9781613525074', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781613525074.png', '162000', '1000', '1', 0.00, NULL),
(384, 'Listening Starter 2nd 3 (SB+MP3)', '9781613525081', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781613525081.png', '162000', '1000', '1', 0.00, NULL),
(385, 'Reading Starter 3rd 1 (SB+BIGBOX)', '9781613525579', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781613525579.png', '172800', '1000', '1', 0.00, NULL),
(386, 'Reading Starter 3rd 2 (SB+BIGBOX)', '9781613525586', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781613525586.png', '172800', '1000', '1', 0.00, NULL),
(387, 'Reading Starter 3rd 3 (SB+BIGBOX)', '9781613525593', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781613525593.png', '172800', '1000', '1', 0.00, NULL),
(388, 'CORE Nonfiction Reading 1', '9781613527405', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781613527405.png', '172800', '1000', '1', 0.00, NULL),
(389, 'CORE Nonfiction Reading 2', '9781613527412', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781613527412.png', '172800', '1000', '1', 0.00, NULL),
(390, 'CORE Nonfiction Reading 3', '9781613527429', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781613527429.png', '172800', '1000', '1', 0.00, NULL),
(391, 'ABC Adventures Starter (SB+Hybrid CD)', '9781613527443', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781613527443.png', '162000', '1000', '1', 0.00, NULL),
(392, 'ABC Adventures 1(SB+Hybrid CD)', '9781613527450', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781613527450.png', '162000', '1000', '1', 0.00, NULL),
(393, 'ABC Adventures 2 (SB+Hybrid CD)', '9781613527467', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781613527467.png', '162000', '1000', '1', 0.00, NULL),
(394, 'More School Subject Readings 2nd 1 (SB+Hybrid CD)', '9781613527481', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781613527481.png', '172800', '1000', '1', 0.00, NULL),
(395, 'More School Subject Readings 2nd 2 (SB+Hybrid CD)', '9781613527498', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781613527498.png', '172800', '1000', '1', 0.00, NULL),
(396, 'More School Subject Readings 2nd 3 (SB+Hybrid CD)', '9781613527504', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781613527504.png', '172800', '1000', '1', 0.00, NULL),
(397, 'School Subject Readings 2nd 1(SB+Hybrid CD)', '9781613527528', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781613527528.png', '172800', '1000', '1', 0.00, NULL),
(398, 'School Subject Readings 2nd 2 (SB+Hybrid CD)', '9781613527535', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781613527535.png', '172800', '1000', '1', 0.00, NULL),
(399, 'School Subject Readings 2nd 3 (SB+Hybrid CD)', '9781613527542', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781613527542.png', '172800', '1000', '1', 0.00, NULL),
(400, 'Listening Express 3 (SB+Hybrid CD)', '9781613527610', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781613527610.png', '172800', '1000', '1', 0.00, NULL),
(401, 'Compass Club Treehouse 1 (SB+MP3)', '9781613527948', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781613527948.png', '216000', '1000', '1', 0.00, NULL),
(402, 'Compass Club Treehouse 1 (AB+CD)', '9781613527955', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781613527955.png', '151200', '1000', '1', 0.00, NULL),
(403, 'Compass Club Treehouse 2 (SB+MP3)', '9781613527962', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781613527962.png', '216000', '1000', '1', 0.00, NULL),
(404, 'Compass Club Treehouse 2 (AB+CD)', '9781613527979', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781613527979.png', '151200', '1000', '1', 0.00, NULL),
(405, 'Compass Club Treehouse 3 (SB+BIGBOX)', '9781613527986', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781613527986.png', '216000', '1000', '1', 0.00, NULL),
(406, 'Compass Club Treehouse 3 (AB+CD)', '9781613527993', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781613527993.png', '151200', '1000', '1', 0.00, NULL),
(407, 'Grammar Planet 1 SB+WB+BIGBOX', '9781613528013', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781613528013.png', '162000', '1000', '1', 0.00, NULL),
(408, 'Grammar Planet 2 SB+WB+BIGBOX', '9781613528020', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781613528020.png', '162000', '1000', '1', 0.00, NULL),
(409, 'Grammar Planet 3 SB+WB+BIGBOX', '9781613528037', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781613528037.png', '162000', '1000', '1', 0.00, NULL),
(410, 'Grammar Galaxy 1 SB+WB+BIGBOX', '9781613528051', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781613528051.png', '162000', '1000', '1', 0.00, NULL),
(411, 'Grammar Galaxy 2 SB+WB+BIGBOX', '9781613528068', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781613528068.png', '162000', '1000', '1', 0.00, NULL),
(412, 'Grammar Galaxy 3 SB+WB+BIGBOX', '9781613528075', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781613528075.png', '162000', '1000', '1', 0.00, NULL),
(413, 'TOEIC Upgrade(SB+CD)', '9781613528280', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781613528280.png', '172800', '1000', '1', 0.00, NULL),
(414, 'Hang Out 1 (SB+BIGBOX)', '9781613528372', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781613528372.png', '172800', '1000', '1', 0.00, NULL),
(415, 'Hang Out 2 (SB+BIGBOX)', '9781613528389', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781613528389.png', '172800', '1000', '1', 0.00, NULL),
(416, 'Hang Out 3 (SB+BIGBOX)', '9781613528396', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781613528396.png', '172800', '1000', '1', 0.00, NULL),
(417, 'Hang Out 4 (SB+BIGBOX)', '9781613528402', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781613528402.png', '172800', '1000', '1', 0.00, NULL),
(418, 'Hang Out 5 (SB+BIGBOX)', '9781613528419', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781613528419.png', '172800', '1000', '1', 0.00, NULL),
(419, 'Hang Out 6 (SB+BIGBOX)', '9781613528426', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781613528426.png', '172800', '1000', '1', 0.00, NULL),
(420, 'Hang Out 1 (WB+BIGBOX)', '9781613528433', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781613528433.png', '86400', '1000', '1', 0.00, NULL),
(421, 'Hang Out 2 (WB+BIGBOX)', '9781613528440', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781613528440.png', '86400', '1000', '1', 0.00, NULL),
(422, 'Hang Out 3 (WB+BIGBOX)', '9781613528457', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781613528457.png', '86400', '1000', '1', 0.00, NULL),
(423, 'Hang Out 4 (WB+BIGBOX)', '9781613528464', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781613528464.png', '86400', '1000', '1', 0.00, NULL),
(424, 'Hang Out 5 (WB+BIGBOX)', '9781613528471', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781613528471.png', '86400', '1000', '1', 0.00, NULL),
(425, 'Hang Out 6 (WB+BIGBOX)', '9781613528488', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781613528488.png', '86400', '1000', '1', 0.00, NULL),
(426, 'Integrate Reading & Writing Basic 1(SB+CD)', '9781613529324', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781613529324.png', '194400', '1000', '1', 0.00, NULL),
(427, 'Integrate Reading & Writing Basic 2(SB+CD)', '9781613529331', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781613529331.png', '194400', '1000', '1', 0.00, NULL),
(428, 'Integrate Reading & Writing Basic 3(SB+BIGBOX)', '9781613529348', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781613529348.png', '194400', '1000', '1', 0.00, NULL),
(429, 'Integrate Reading & Writing Basic 4(SB+CD)', '9781613529355', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781613529355.png', '194400', '1000', '1', 0.00, NULL),
(430, 'Integrate Reading & Writing Building 1(SB+CD)', '9781613529362', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781613529362.png', '194400', '1000', '1', 0.00, NULL),
(431, 'Integrate Reading & Writing Building 2(SB+CD)', '9781613529379', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781613529379.png', '194400', '1000', '1', 0.00, NULL),
(432, 'Integrate Reading & Writing Building 3(SB+CD)', '9781613529386', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781613529386.png', '194400', '1000', '1', 0.00, NULL),
(433, 'Integrate Reading & Writing Building 4(SB+CD)', '9781613529393', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781613529393.png', '194400', '1000', '1', 0.00, NULL),
(434, 'Reading for Speed and Fluency (2nd Edition) 1 [SB]', '9781640150676', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781640150676.png', '172800', '1000', '1', 0.00, NULL),
(435, 'Reading for Speed and Fluency (2nd Edition) 2 [SB]', '9781640150683', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781640150683.png', '172800', '1000', '1', 0.00, NULL),
(436, 'Reading for Speed and Fluency (2nd Edition) 3 [SB]', '9781640150690', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781640150690.png', '172800', '1000', '1', 0.00, NULL),
(437, 'Reading for Speed and Fluency (2nd Edition) 4 [SB]', '9781640150706', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781640150706.png', '172800', '1000', '1', 0.00, NULL),
(438, 'Taking the TOEIC 1 Second Edition: Skills and Strategies(SB+MP3)', '9781640150713', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781640150713.png', '183600', '1000', '1', 0.00, NULL),
(439, 'Taking the TOEIC 2 Second Edition: Skills and Strategies(SB+MP3)', '9781640150720', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781640150720.png', '183600', '1000', '1', 0.00, NULL),
(440, 'Short Articles for Reading Comprehension 2nd Edition 1', '9781640150874', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781640150874.png', '172800', '1000', '1', 0.00, NULL),
(441, 'Short Articles for Reading Comprehension 2nd Edition 2', '9781640150881', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781640150881.png', '172800', '1000', '1', 0.00, NULL),
(442, 'Short Articles for Reading Comprehension 2nd Edition 3', '9781640150898', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781640150898.png', '172800', '1000', '1', 0.00, NULL),
(443, 'Hang Out Starter (SB+BIGBOX)', '9781640150904', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781640150904.png', '172800', '1000', '1', 0.00, NULL),
(444, 'Hang Out Starter (WB+BIGBOX)', '9781640150911', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781640150911.png', '86400', '1000', '1', 0.00, NULL),
(445, 'Writing Basics 1: Core Vocabulary and Grammar for Writing', '9781640150959', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781640150959.png', '162000', '1000', '1', 0.00, NULL),
(446, 'Writing Basics 2: Core Vocabulary and Grammar for Writing', '9781640150966', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781640150966.png', '162000', '1000', '1', 0.00, NULL),
(447, 'Writing Step by Step 1: A Vocabulary and Skill Builder', '9781640150973', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781640150973.png', '162000', '1000', '1', 0.00, NULL),
(448, 'Writing Step by Step 2: A Vocabulary and Skill Builder with CD', '9781640150980', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781640150980.png', '162000', '1000', '1', 0.00, NULL),
(449, 'Developing Listening Skills 3rd 1SB(SB+MP3)', '9781640151123', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781640151123.png', '183600', '1000', '1', 0.00, NULL),
(450, 'Developing Listening Skills 3rd 2SB(SB+MP3)', '9781640151130', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781640151130.png', '183600', '1000', '1', 0.00, NULL),
(451, 'Developing Listening Skills 3rd 3SB(SB+MP3)', '9781640151147', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781640151147.png', '183600', '1000', '1', 0.00, NULL),
(452, 'Big Show 1 (SB+CD)', '9781640151246', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781640151246.png', '172800', '1000', '1', 0.00, NULL),
(453, 'Big Show 2 (SB+BIGBOX)', '9781640151253', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781640151253.png', '172800', '1000', '1', 0.00, NULL),
(454, 'Big Show 3 (SB+CD)', '9781640151260', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781640151260.png', '172800', '1000', '1', 0.00, NULL),
(455, 'Big Show 1 Workbook', '9781640151277', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781640151277.png', '86400', '1000', '1', 0.00, NULL),
(456, 'Big Show 2 Workbook', '9781640151284', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781640151284.png', '86400', '1000', '1', 0.00, NULL),
(457, 'Big Show 3 Workbook', '9781640151291', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781640151291.png', '86400', '1000', '1', 0.00, NULL),
(458, '4000 Essential English Words 2nd 1 (SB+Sticker)', '9781640151338', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781640151338.png', '183600', '1000', '1', 0.00, NULL),
(459, '4000 Essential English Words 2nd 2 (SB+BIGBOX)', '9781640151345', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781640151345.png', '183600', '1000', '1', 0.00, NULL),
(460, '4000 Essential English Words 2nd 3 (SB+BIGBOX)', '9781640151352', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781640151352.png', '183600', '1000', '1', 0.00, NULL),
(461, '4000 Essential English Words 2nd 4 (SB+Sticker)', '9781640151369', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781640151369.png', '183600', '1000', '1', 0.00, NULL),
(462, '4000 Essential English Words 2nd 5 (SB+Sticker)', '9781640151376', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781640151376.png', '183600', '1000', '1', 0.00, NULL),
(463, '4000 Essential English Words 2nd 6 (SB+Sticker)', '9781640151383', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781640151383.png', '183600', '1000', '1', 0.00, NULL),
(464, 'Big Show 4 (SB+CD)', '9781640151390', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781640151390.png', '172800', '1000', '1', 0.00, NULL),
(465, 'Big Show 5 (SB+CD)', '9781640151406', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781640151406.png', '172800', '1000', '1', 0.00, NULL),
(466, 'Big Show 6 (SB+CD)', '9781640151413', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781640151413.png', '172800', '1000', '1', 0.00, NULL),
(467, 'Big Show 4 Workbook', '9781640151420', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781640151420.png', '86400', '1000', '1', 0.00, NULL),
(468, 'Big Show 5 Workbook', '9781640151437', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781640151437.png', '86400', '1000', '1', 0.00, NULL),
(469, 'Big Show 6 Workbook', '9781640151444', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2025-03/12321.PNG', '86400', '1000', '1', 0.00, NULL),
(470, 'Reading Future Starter 1 (SB+CD)', '9781640151789', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781640151789.png', '172800', '1000', '1', 0.00, NULL),
(471, 'Reading Future Starter 2 (SB+BIGBOX)', '9781640151796', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781640151796.png', '172800', '1000', '1', 0.00, NULL),
(472, 'Reading Future Starter 3 (SB+CD)', '9781640151802', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781640151802.png', '172800', '1000', '1', 0.00, NULL),
(473, 'Reading Future Dream 1 (SB+CD)', '9781640151819', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781640151819.png', '172800', '1000', '1', 0.00, NULL),
(474, 'Reading Future Dream 2 (SB+BIGBOX)', '9781640151826', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781640151826.png', '172800', '1000', '1', 0.00, NULL),
(475, 'Reading Future Dream 3 (SB+CD)', '9781640151833', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781640151833.png', '172800', '1000', '1', 0.00, NULL),
(476, 'Reading Future Discover 1 (SB+CD)', '9781640151840', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781640151840.png', '172800', '1000', '1', 0.00, NULL),
(477, 'Reading Future Discover 2 (SB+BIGBOX)', '9781640151857', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781640151857.png', '172800', '1000', '1', 0.00, NULL),
(478, 'Reading Future Discover 3 (SB+CD)', '9781640151864', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781640151864.png', '172800', '1000', '1', 0.00, NULL),
(479, 'Reading Future Develop 1 (SB+CD)', '9781640151871', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781640151871.png', '172800', '1000', '1', 0.00, NULL),
(480, 'Reading Future Develop 2 (SB+CD)', '9781640151888', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781640151888.png', '172800', '1000', '1', 0.00, NULL),
(481, 'Reading Future Develop 3 (SB+CD)', '9781640151895', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781640151895.png', '172800', '1000', '1', 0.00, NULL),
(482, 'Very Easy TOEIC 3rd 1', '9781640151901', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781640151901.png', '183600', '1000', '1', 0.00, NULL),
(483, 'Very Easy TOEIC 3rd 2', '9781640151918', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781640151918.png', '183600', '1000', '1', 0.00, NULL),
(484, 'Super Easy Reading 3rd 1(SB+Mp3)', '9781640151925', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781640151925.png', '151200', '1000', '1', 0.00, NULL),
(485, 'Super Easy Reading 3rd 2(SB+Mp3)', '9781640151932', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781640151932.png', '151200', '1000', '1', 0.00, NULL),
(486, 'Super Easy Reading 3rd 3(SB+Mp3)', '9781640151949', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781640151949.png', '151200', '1000', '1', 0.00, NULL),
(487, 'VERY EASY READING 4th 1 (SB+CD)', '9781640151956', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781640151956.png', '151200', '1000', '1', 0.00, NULL),
(488, 'VERY EASY READING 4th 2 (SB+CD)', '9781640151963', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781640151963.png', '151200', '1000', '1', 0.00, NULL),
(489, 'VERY EASY READING 4th 3 (SB+CD)', '9781640151970', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781640151970.png', '151200', '1000', '1', 0.00, NULL),
(490, 'VERY EASY READING 4th 4 (SB+CD)', '9781640151987', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781640151987.png', '151200', '1000', '1', 0.00, NULL),
(491, 'Reading Future Connect 1 New(SB+CD)', '9781640151994', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781640151994.png', '172800', '1000', '1', 0.00, NULL),
(492, 'Reading Future Connect 2 New(SB+CD)', '9781640152007', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781640152007.png', '172800', '1000', '1', 0.00, NULL),
(493, 'Reading Future Connect 3 New(SB+CD)', '9781640152014', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781640152014.png', '172800', '1000', '1', 0.00, NULL),
(494, 'Reading Future Change 1 New(SB+CD)', '9781640152021', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781640152021.png', '172800', '1000', '1', 0.00, NULL),
(495, 'Reading Future Change 2 New(SB+CD)', '9781640152038', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781640152038.png', '172800', '1000', '1', 0.00, NULL),
(496, 'Reading Future Change 3 New(SB+CD)', '9781640152045', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781640152045.png', '172800', '1000', '1', 0.00, NULL),
(497, 'Reading Future Create 1 New(SB+CD)', '9781640152052', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781640152052.png', '172800', '1000', '1', 0.00, NULL),
(498, 'Reading Future Create 2 New(SB+CD)', '9781640152069', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781640152069.png', '172800', '1000', '1', 0.00, NULL),
(499, 'Reading Future Create 3 New(SB+CD)', '9781640152076', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781640152076.png', '172800', '1000', '1', 0.00, NULL),
(500, 'Super Easy Reading 3rd 1 WB', '9781640152083', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781640152083.png', '32400', '1000', '1', 0.00, NULL),
(501, 'Super Easy Reading 3rd 2 WB', '9781640152090', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781640152090.png', '32400', '1000', '1', 0.00, NULL),
(502, 'Super Easy Reading 3rd 3 WB', '9781640152106', 'Compass Publishing', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781640152106.png', '32400', '1000', '1', 0.00, NULL),
(503, 'New Frontiers 1(SB+BIGBOX)', '9781640152113', 'Pearson Education ESL', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781640152113.png', '194400', '1000', '1', 0.00, NULL),
(504, 'New Frontiers 2(SB+CD)', '9781640152120', 'Pearson Education ESL', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781640152120.png', '194400', '1000', '1', 0.00, NULL),
(505, 'New Frontiers 3(SB+BIGBOX)', '9781640152137', 'Pearson Education ESL', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781640152137.png', '194400', '1000', '1', 0.00, NULL),
(506, 'New Frontiers 4(SB+BIGBOX)', '9781640152144', 'Pearson Education ESL', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781640152144.png', '194400', '1000', '1', 0.00, NULL),
(507, 'New Frontiers 5(SB+BIGBOX)', '9781640152151', 'Pearson Education ESL', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781640152151.png', '194400', '1000', '1', 0.00, NULL),
(508, 'New Frontiers 6(SB+CD)', '9781640152168', 'Pearson Education ESL', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781640152168.png', '194400', '1000', '1', 0.00, NULL),
(509, 'AZAR UNDERSTANDING USING ENG GRM (5E/INT): SB+MEL', '9780134275260', 'Pearson Education ESL', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9780134275260.png', '572400', '1000', '1', 0.00, NULL),
(510, 'AZAR UNDERSTANDING USING ENG GRM (5E) WB+AK', '9780134275444', 'Pearson Education ESL', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9780134275444.png', '410400', '1000', '1', 0.00, NULL),
(511, 'AZAR FUNDAMENTALS OF ENGLISH GRAMMAR (5E) WB + AK', '9780135159460', 'Pearson Education ESL', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9780135159460.png', '378000', '1000', '1', 0.00, NULL),
(512, 'NORTHSTAR (5E) L&S 4: SB+ PEP+ MYLAB', '9780135226940', 'Pearson Education ESL', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9780135226940.png', '324000', '1000', '1', 0.00, NULL),
(513, 'NORTHSTAR (5E) L&S 3: SB+ PEP+ MYLAB', '9780135226957', 'Pearson Education ESL', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9780135226957.png', '324000', '1000', '1', 0.00, NULL);
INSERT INTO `books` (`id`, `title`, `isbn`, `category`, `cefr`, `cover_image`, `base_price`, `stock`, `is_featured`, `early_bird_price`, `early_bird_end_date`) VALUES
(514, 'NORTHSTAR (5E) L&S 2: SB+ PEP+ MYLAB', '9780135226964', 'Pearson Education ESL', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9780135226964.png', '324000', '1000', '1', 0.00, NULL),
(515, 'NORTHSTAR (4E) L&S 1: SB+ PEP+ MYLAB', '9780135226971', 'Pearson Education ESL', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9780135226971.png', '324000', '1000', '1', 0.00, NULL),
(516, 'NORTHSTAR (5E) R&W 4: SB+ PEP+ MYLAB', '9780135226988', 'Pearson Education ESL', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9780135226988.png', '324000', '1000', '1', 0.00, NULL),
(517, 'NORTHSTAR (5E) R&W 3: SB+ PEP+ MYLAB', '9780135226995', 'Pearson Education ESL', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9780135226995.png', '324000', '1000', '1', 0.00, NULL),
(518, 'NORTHSTAR (5E) R&W 2: SB+ PEP+ MYLAB', '9780135227008', 'Pearson Education ESL', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9780135227008.png', '324000', '1000', '1', 0.00, NULL),
(519, 'NORTHSTAR (4E) R&W 1: SB+ PEP+ MYLAB', '9780135227015', 'Pearson Education ESL', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9780135227015.png', '324000', '1000', '1', 0.00, NULL),
(520, 'NEW CORNERSTONE GRADE 1 A/B SE W/EBOOK(SOFT COVER)', '9780135231944', 'Pearson Education ESL', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9780135231944.png', '464400', '1000', '1', 0.00, NULL),
(521, 'NEW CORNERSTONE GRADE 5 WKBK', '9780135234600', 'Pearson Education ESL', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9780135234600.png', '270000', '1000', '1', 0.00, NULL),
(522, 'NEW CORNERSTONE GRADE 4 WKBK', '9780135234617', 'Pearson Education ESL', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9780135234617.png', '270000', '1000', '1', 0.00, NULL),
(523, 'NEW CORNERSTONE GRADE 3 WKBK', '9780135234631', 'Pearson Education ESL', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9780135234631.png', '270000', '1000', '1', 0.00, NULL),
(524, 'NEW CORNERSTONE GRADE 2 WKBK', '9780135234662', 'Pearson Education ESL', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9780135234662.png', '270000', '1000', '1', 0.00, NULL),
(525, 'NEW CORNERSTONE GRADE 1 WKBK', '9780135244678', 'Pearson Education ESL', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9780135244678.png', '270000', '1000', '1', 0.00, NULL),
(526, 'AZAR FUNDAMENTALS OF ENGLISH GRAMMAR (5E):SB+MEL', '9780136534457', 'Pearson Education ESL', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9780136534457.png', '594000', '1000', '1', 0.00, NULL),
(527, 'AZAR BASIC ENGLISH GRAMMAR (5E): WB+AK', '9780136726173', 'Pearson Education ESL', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9780136726173.png', '680400', '1000', '1', 0.00, NULL),
(528, 'Connectivity SB w/APP & Online Practice (blended) Foundations', '9780136833314', 'Pearson Education ESL', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9780136833314.png', '324000', '1000', '1', 0.00, NULL),
(529, 'Connectivity SB w/APP & Online Practice (blended) Level 1', '9780136833543', 'Pearson Education ESL', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9780136833543.png', '324000', '1000', '1', 0.00, NULL),
(530, 'Connectivity SB w/APP & Online Practice (blended) Level 2', '9780136834472', 'Pearson Education ESL', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9780136834472.png', '324000', '1000', '1', 0.00, NULL),
(531, 'Connectivity SB w/APP & Online Practice (blended) Level 3', '9780136834670', 'Pearson Education ESL', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9780136834670.png', '324000', '1000', '1', 0.00, NULL),
(532, 'Connectivity SB w/APP & Online Practice (blended) Level 4', '9780137463862', 'Pearson Education ESL', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9780137463862.png', '324000', '1000', '1', 0.00, NULL),
(533, 'Connectivity SB w/APP & Online Practice (blended) Level 5', '9780137463961', 'Pearson Education ESL', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9780137463961.png', '324000', '1000', '1', 0.00, NULL),
(534, 'AZAR BASIC ENGLISH GRAMMAR (5E): SB+MEL', '9780137565467', 'Pearson Education ESL', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9780137565467.png', '788400', '1000', '1', 0.00, NULL),
(535, 'Great Writing 5E Foundations with Spark', '9798214502311', 'Cengage Learning', '', 'https://8izg4bob10557.edge.naverncp.com/book/2025-08/20250826131557uwposxgraxk.jpg', '345600', '1000', '1', 0.00, NULL),
(536, 'Great Writing 5E 1 with Spark', '9798214502328', 'Cengage Learning', '', 'https://8izg4bob10557.edge.naverncp.com/book/2025-08/20250826131327h4kzv024pki.jpg', '345600', '1000', '1', 0.00, NULL),
(537, 'Great Writing 5E 2 with Spark', '9798214502335', 'Cengage Learning', '', 'https://8izg4bob10557.edge.naverncp.com/book/2025-08/202508261310364hnt2zbak0l.jpg', '345600', '1000', '1', 0.00, NULL),
(538, 'Great Writing 5E 3 with Spark', '9798214502342', 'Cengage Learning', '', 'https://8izg4bob10557.edge.naverncp.com/book/2025-08/20250826130926tvcbbp1qvfh.jpg', '345600', '1000', '1', 0.00, NULL),
(539, 'Great Writing 5E 4 with Spark', '9798214502359', 'Cengage Learning', '', 'https://8izg4bob10557.edge.naverncp.com/book/2025-08/20250826130759103uaptrrjo.jpg', '345600', '1000', '1', 0.00, NULL),
(540, 'Great Writing 5E 5 with Spark', '9798214502366', 'Cengage Learning', '', 'https://8izg4bob10557.edge.naverncp.com/book/2025-08/20250826130632tktg2mdxnhq.jpg', '345600', '1000', '1', 0.00, NULL),
(541, 'REFLECT R/W + OLP/EBK STICKER 1', '9780357980606', 'Pearson Education ESL', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9780357980606.png', '324000', '1000', '1', 0.00, NULL),
(542, 'REFLECT R/W + OLP/EBK STICKER 2', '9780357980613', 'Pearson Education ESL', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9780357980613.png', '324000', '1000', '1', 0.00, NULL),
(543, 'REFLECT R/W + OLP/EBK STICKER 3', '9780357980620', 'Pearson Education ESL', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9780357980620.png', '324000', '1000', '1', 0.00, NULL),
(544, 'REFLECT R/W + OLP/EBK STICKER 4', '9780357980637', 'Pearson Education ESL', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9780357980637.png', '324000', '1000', '1', 0.00, NULL),
(545, 'REFLECT R/W + OLP/EBK STICKER 5', '9780357980644', 'Pearson Education ESL', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9780357980644.png', '324000', '1000', '1', 0.00, NULL),
(546, 'REFLECT L/S + OLP/EBK STICKER 1', '9780357980842', 'Pearson Education ESL', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9780357980842.png', '324000', '1000', '1', 0.00, NULL),
(547, 'REFLECT L/S + OLP/EBK STICKER 2', '9780357980859', 'Pearson Education ESL', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9780357980859.png', '324000', '1000', '1', 0.00, NULL),
(548, 'REFLECT L/S + OLP/EBK STICKER 3', '9780357980866', 'Pearson Education ESL', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9780357980866.png', '324000', '1000', '1', 0.00, NULL),
(549, 'REFLECT L/S + OLP/EBK STICKER 4', '9780357980873', 'Pearson Education ESL', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9780357980873.png', '324000', '1000', '1', 0.00, NULL),
(550, 'REFLECT L/S + OLP/EBK STICKER 5', '9780357980880', 'Pearson Education ESL', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9780357980880.png', '324000', '1000', '1', 0.00, NULL),
(551, 'PK3: MARVELS SWASHBUCKLING SPIDER', '9781292205786', 'Cengage Learning', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781292205786.png', '92880', '1000', '1', 0.00, NULL),
(552, 'PK3: MARVELS CALL FOR BACKUP', '9781292205960', 'Cengage Learning', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781292205960.png', '92880', '1000', '1', 0.00, NULL),
(553, 'PK2: MARVELS STORY SPIDERMAN', '9781292206004', 'Cengage Learning', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781292206004.png', '87480', '1000', '1', 0.00, NULL),
(554, 'PK2: MARVELS FREAKY THOR DAY', '9781292206226', 'Cengage Learning', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781292206226.png', '87480', '1000', '1', 0.00, NULL),
(555, 'PLPR2: MARVELS AVENGERS (MP3 PK)', '9781292208169', 'Cengage Learning', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781292208169.png', '140400', '1000', '1', 0.00, NULL),
(556, 'PLPR3: MARVELS CAPTAIN AMRCA: CIVIL WAR (MP3 PK)', '9781292208190', 'Cengage Learning', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781292208190.png', '140400', '1000', '1', 0.00, NULL),
(557, 'PLPR3: MARVELS THOR (MP3 PK)', '9781292208206', 'Cengage Learning', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781292208206.png', '140400', '1000', '1', 0.00, NULL),
(558, 'PLPR4: MARVELS GUARDIANS (MP3 PK)', '9781292208220', 'Cengage Learning', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781292208220.png', '162000', '1000', '1', 0.00, NULL),
(559, 'PLPR3: MARVELS AVENGERS: AGE ULTRON (MP3 PK)', '9781292239521', 'Cengage Learning', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781292239521.png', '140400', '1000', '1', 0.00, NULL),
(560, 'PLPR4: MARVELS GUARDIANS VOL 2 (MP3 PK)', '9781292240756', 'Cengage Learning', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781292240756.png', '162000', '1000', '1', 0.00, NULL),
(561, 'Roadmap A1 SB w OP, DR & App Pk', '9781292271941', 'Pearson Education ESL', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781292271941.png', '324000', '1000', '1', 0.00, NULL),
(562, 'Roadmap C1-C2 Student\'s Book & Interactive eBook with Online Practice, Digital Resources & App', '9781292391311', 'Pearson Education ESL', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781292391311.png', '324000', '1000', '1', 0.00, NULL),
(563, 'Roadmap A2+ Student\'s Book & Interactive eBook with Online Practice, Digital Resources & App', '9781292393025', 'Pearson Education ESL', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781292393025.png', '324000', '1000', '1', 0.00, NULL),
(564, 'Roadmap A2 Student\'s Book & Interactive eBook with Online Practice, Digital Resources & App', '9781292393070', 'Pearson Education ESL', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781292393070.png', '324000', '1000', '1', 0.00, NULL),
(565, 'Roadmap B1 Student\'s Book & Interactive eBook with Online Practice, Digital Resources & App', '9781292393087', 'Pearson Education ESL', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781292393087.png', '324000', '1000', '1', 0.00, NULL),
(566, 'Roadmap B1+ Student\'s Book & Interactive eBook with Online Practice, Digital Resources & App', '9781292393100', 'Pearson Education ESL', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781292393100.png', '324000', '1000', '1', 0.00, NULL),
(567, 'Roadmap B2 Student\'s Book & Interactive eBook with Online Practice, Digital Resources & App', '9781292393124', 'Pearson Education ESL', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781292393124.png', '324000', '1000', '1', 0.00, NULL),
(568, 'Roadmap B2+ Student\'s Book & Interactive eBook with Online Practice, Digital Resources & App', '9781292393148', 'Pearson Education ESL', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781292393148.png', '324000', '1000', '1', 0.00, NULL),
(569, 'Rise and Shine American Level 1 Workbook with eBook', '9781292398792', 'Pearson Education ESL', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781292398792.png', '194400', '1000', '1', 0.00, NULL),
(570, 'Rise and Shine American Level 2 Workbook with eBook', '9781292398815', 'Pearson Education ESL', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781292398815.png', '194400', '1000', '1', 0.00, NULL),
(571, 'Rise and Shine American Level 3 Workbook with eBook', '9781292398822', 'Pearson Education ESL', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781292398822.png', '194400', '1000', '1', 0.00, NULL),
(572, 'Rise and Shine American Level 4 Workbook with eBook', '9781292398839', 'Pearson Education ESL', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781292398839.png', '194400', '1000', '1', 0.00, NULL),
(573, 'Rise and Shine American Level 5 Workbook with eBook', '9781292398846', 'Pearson Education ESL', '', 'https://8izg4bob10557.edge.naverncp.com/book/2024-12/9781292398846.png', '194400', '1000', '1', 0.00, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `interest_forms`
--

CREATE TABLE `interest_forms` (
  `id` int NOT NULL,
  `school_name` varchar(150) DEFAULT NULL,
  `participant_name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `contact` varchar(100) DEFAULT NULL,
  `position` varchar(100) DEFAULT NULL,
  `student_count` int DEFAULT NULL,
  `programs` text,
  `visit_consent` varchar(20) DEFAULT NULL,
  `interest_level` varchar(100) DEFAULT NULL,
  `status` enum('new','contacted') DEFAULT 'new',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `interest_forms`
--

INSERT INTO `interest_forms` (`id`, `school_name`, `participant_name`, `email`, `phone`, `city`, `contact`, `position`, `student_count`, `programs`, `visit_consent`, `interest_level`, `status`, `created_at`) VALUES
(1, 'SMA 1 Jakarta', 'Budi', NULL, NULL, 'Jakarta Selatan', NULL, NULL, 500, '[\"Odyssey\"]', NULL, NULL, 'new', '2026-02-08 11:58:58'),
(2, 'SMP 5 Bandung', 'Siti', NULL, NULL, 'Bandung', NULL, NULL, 200, '[\"Boost English\"]', NULL, NULL, 'new', '2026-02-08 11:58:58'),
(3, 'SD Al-Azhar', 'Ahmad', NULL, NULL, 'Jakarta Selatan', NULL, NULL, 300, '[\"Tree House\"]', NULL, NULL, 'new', '2026-02-08 11:58:58'),
(4, 'Surabaya Inter', 'John', NULL, NULL, 'Surabaya', NULL, NULL, 150, '[\"New Frontiers\"]', NULL, NULL, 'new', '2026-02-08 11:58:58'),
(5, 'Bali Public', 'Wayan', NULL, NULL, 'Denpasar', NULL, NULL, 100, '[\"Big Show\"]', NULL, NULL, 'new', '2026-02-08 11:58:58'),
(6, 'Jakarta High', 'Dewi', NULL, NULL, 'Jakarta Selatan', NULL, NULL, 600, '[\"Odyssey\"]', NULL, NULL, 'new', '2026-02-08 11:58:58'),
(7, 'Compass HQ', 'Admin Staff', 'admin@compass.com', '0812-3456-7890', 'Sukabumi', NULL, 'Engineer', 100, '[\"Lainnya\"]', 'Ya', 'Tertarik', 'new', '2026-02-08 22:05:50');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int NOT NULL,
  `reference_id` varchar(20) DEFAULT NULL,
  `user_id` int DEFAULT NULL,
  `shipping_city` varchar(100) DEFAULT NULL,
  `shipping_snapshot` text,
  `status` varchar(50) DEFAULT NULL,
  `payment_status` text,
  `tier_level` int DEFAULT '1',
  `is_early_bird` tinyint(1) DEFAULT '0',
  `total_amount` decimal(15,2) DEFAULT NULL,
  `paid_amount` decimal(15,2) DEFAULT '0.00',
  `payment_proof` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `shipping_status` text,
  `tracking_number` varchar(100) DEFAULT NULL,
  `estimated_delivery` date DEFAULT NULL,
  `confirmed_arrival` datetime DEFAULT NULL,
  `arrival_proof` varchar(255) DEFAULT NULL,
  `arrival_date` datetime DEFAULT NULL,
  `shipment_status` text,
  `next_payment_due` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `reference_id`, `user_id`, `shipping_city`, `shipping_snapshot`, `status`, `payment_status`, `tier_level`, `is_early_bird`, `total_amount`, `paid_amount`, `payment_proof`, `created_at`, `shipping_status`, `tracking_number`, `estimated_delivery`, `confirmed_arrival`, `arrival_proof`, `arrival_date`, `shipment_status`, `next_payment_due`) VALUES
(1, 'ORD-2026-000001', 2, 'Jakarta Selatan', '{\"est_arrival\": \"2026-02-13\"}', 'rejected', 'unpaid', 1, 0, 450000.00, 0.00, NULL, '2026-02-03 11:58:58', 'cancelled', NULL, NULL, NULL, NULL, NULL, 'processing', NULL),
(2, 'ORD-2026-000002', 3, 'Jakarta Selatan', '{\"est_arrival\": \"2026-02-13\"}', 'rejected', 'unpaid', 3, 1, 24000000.00, 0.00, NULL, '2026-02-06 11:58:58', 'cancelled', NULL, NULL, NULL, NULL, NULL, 'processing', NULL),
(3, 'ORD-2026-000003', 2, 'Jakarta Selatan', '{\"est_arrival\": \"2026-02-13\"}', 'rejected', 'unpaid', 2, 1, 2700000.00, 0.00, NULL, '2026-01-29 11:58:58', 'cancelled', NULL, NULL, NULL, NULL, NULL, 'processing', NULL),
(4, 'ORD-2026-000004', 5, 'Jakarta Selatan', '{\"est_arrival\": \"2026-02-13\"}', 'confirmed', 'partial', 3, 1, 22500000.00, 400000.00, 'proof_4_1770553604.jpeg', '2026-02-08 12:23:34', 'delivering', NULL, '2026-02-10', NULL, NULL, NULL, 'packing', NULL),
(5, 'ORD-2026-000005', 1, 'Jakarta Selatan', '{\"id\": 1, \"user_id\": 1, \"label\": \"Main Address\", \"recipient_name\": \"Admin\", \"phone\": \"08123456789\", \"address_line\": \"Headquarters\", \"city\": \"Jakarta Selatan\", \"postal_code\": null, \"is_default\": 1, \"created_at\": \"2026-02-08 20:31:39\", \"est_arrival\": \"2026-02-13\"}', 'cancelled', 'unpaid', 1, 1, 285000.00, 0.00, NULL, '2026-02-08 14:18:17', 'cancelled', NULL, NULL, NULL, NULL, NULL, 'processing', NULL),
(12, NULL, 1, 'Sukabumi', '{\"id\": 6, \"city\": \"Sukabumi\", \"label\": \"SMK Islam Penguji\", \"phone\": \"085174448002\", \"user_id\": 1, \"recipient\": \"Unknown\", \"created_at\": \"2026-02-08 14:31:36\", \"is_default\": 1, \"est_arrival\": \"2026-03-06\", \"postal_code\": \"43141\", \"address_line\": \"Jl. Pelabuhan II, Gg. Nakula No. 76\\nRT. 002/RW. 001, Kel. Tipar, Kec. Citamiang\", \"recipient_name\": \"Aldi Alfiandi\"}', 'confirmed', 'partial', 1, 0, 107730000.00, 100000.00, NULL, '2026-02-12 22:22:08', 'delivering', NULL, NULL, NULL, NULL, NULL, 'processing', NULL),
(13, NULL, 6, 'Sukabumi', '{\"id\": 7, \"city\": \"Sukabumi\", \"label\": \"PT SEG\", \"phone\": \"085174448002\", \"user_id\": 6, \"recipient\": \"Aldi Alfiandi\", \"created_at\": \"2026-03-04 00:20:08\", \"is_default\": 0, \"est_arrival\": \"2026-03-06\", \"postal_code\": \"43141\", \"address_line\": \"Jl. Pelabuhan II, Gg. Nakula No. 76\\r\\nRT. 002/RW. 001, Kel. Tipar, Kec. Citamiang\", \"recipient_name\": null}', 'confirmed', 'partial', 1, 0, 4155300.00, 692550.00, NULL, '2026-03-03 17:20:39', 'delivering', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(14, NULL, 7, 'Sukabumi', '{\"id\": 8, \"city\": \"Sukabumi\", \"label\": \"PT SEG\", \"phone\": \"085174448002\", \"user_id\": 7, \"recipient\": \"Aldi Alfiandi\", \"created_at\": \"2026-03-04 03:33:22\", \"is_default\": 0, \"est_arrival\": \"2026-03-06\", \"postal_code\": \"43141\", \"address_line\": \"Jl. Pelabuhan II, Gg. Nakula No. 76\\r\\nRT. 002/RW. 001, Kel. Tipar, Kec. Citamiang\", \"recipient_name\": null}', 'confirmed', 'partial', 1, 0, 2770200.00, 230850.00, NULL, '2026-03-03 20:33:36', 'shipping', NULL, NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int NOT NULL,
  `order_id` int DEFAULT NULL,
  `book_id` int DEFAULT NULL,
  `quantity` int DEFAULT NULL,
  `unit_price` decimal(10,2) DEFAULT NULL,
  `total_price` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `book_id`, `quantity`, `unit_price`, `total_price`) VALUES
(1, 1, 1, 3, 150000.00, 450000.00),
(2, 2, 3, 200, 120000.00, 24000000.00),
(3, 3, 5, 20, 135000.00, 2700000.00),
(4, 4, 1, 200, 112500.00, 22500000.00),
(5, 5, 7, 2, 142500.00, 285000.00),
(6, 6, 7, 1, 142500.00, 142500.00),
(7, 7, 7, 1, 142500.00, 142500.00),
(8, 7, 3, 1, 142500.00, 142500.00),
(9, 7, 4, 1, 142500.00, 142500.00),
(10, 7, 5, 1, 142500.00, 142500.00),
(11, 8, 7, 1, 142500.00, 142500.00),
(12, 8, 3, 1, 142500.00, 142500.00),
(13, 9, 7, 250, 112500.00, 28125000.00),
(14, 10, 3, 10, 142500.00, 1425000.00),
(15, 10, 4, 11, 142500.00, 1567500.00),
(16, 10, 5, 12, 142500.00, 1710000.00),
(17, 10, 7, 15, 142500.00, 2137500.00),
(18, 11, 7, 10, 142500.00, 1425000.00),
(19, 11, 0, 2, 37800.00, 75600.00),
(20, 12, 23, 500, 153900.00, 76950000.00),
(21, 12, 24, 500, 153900.00, 76950000.00),
(22, 13, 23, 10, 153900.00, 1539000.00),
(23, 13, 24, 10, 153900.00, 1539000.00),
(24, 13, 25, 10, 153900.00, 1539000.00),
(25, 14, 23, 10, 153900.00, 1539000.00),
(26, 14, 24, 10, 153900.00, 1539000.00);

-- --------------------------------------------------------

--
-- Table structure for table `order_payments`
--

CREATE TABLE `order_payments` (
  `id` int NOT NULL,
  `order_id` int NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `proof_image` varchar(255) NOT NULL,
  `status` enum('pending','verified','rejected') DEFAULT 'pending',
  `payment_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `admin_note` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `order_payments`
--

INSERT INTO `order_payments` (`id`, `order_id`, `amount`, `proof_image`, `status`, `payment_date`, `admin_note`) VALUES
(572, 6, 2500.00, 'proof_6_1770590908.jpg', 'verified', '2026-02-08 22:48:28', NULL),
(573, 8, 285000.00, 'proof_8_1770594650.jpg', 'verified', '2026-02-08 23:50:50', NULL),
(574, 9, 125000.00, 'proof_9_1770596213.jpg', 'verified', '2026-02-09 00:16:53', NULL),
(575, 11, 1500600.00, 'proof_11_1770929983.jpg', 'verified', '2026-02-12 20:59:43', NULL),
(576, 12, 100000.00, 'proof_12_1770935084.jpg', 'verified', '2026-02-12 22:24:44', NULL),
(577, 13, 55300.00, 'proof_13_1772559329.jpg', 'verified', '2026-03-03 17:35:29', NULL),
(578, 13, 346275.00, 'proof_13_1772560094.jpg', 'verified', '2026-03-03 17:48:14', NULL),
(579, 13, 290975.00, 'proof_13_1772560825.jpg', 'verified', '2026-03-03 18:00:25', NULL),
(580, 14, 230850.00, 'proof_14_1772570098.jpg', 'verified', '2026-03-03 20:34:58', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `system_settings`
--

CREATE TABLE `system_settings` (
  `setting_key` varchar(50) NOT NULL,
  `setting_value` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `system_settings`
--

INSERT INTO `system_settings` (`setting_key`, `setting_value`) VALUES
('benefits_list', '[\"Native School Visit\"]'),
('csrf_token', '5c3112982e26639bede6c8c9a929806f0418635de83129848113943fae31324b'),
('discount_tier_2', '10'),
('discount_tier_3', '20'),
('early_bird_deadline', '2026-04-30'),
('moq_tier_2', '20'),
('moq_tier_3', '200'),
('programs_list', '[\"Tree House for kindergarten (KB/TK)\",\"Boost English for Primary (SD/MI)\",\"Odyssey (SMP-SMA/MTS/MA)\",\"Digital Literacy Reading Ocean Plus (TK-SMA)\"]');

-- --------------------------------------------------------

--
-- Table structure for table `tickets`
--

CREATE TABLE `tickets` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `subject` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `status` enum('open','closed') DEFAULT 'open',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `ai_reply` text,
  `admin_reply` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tickets`
--

INSERT INTO `tickets` (`id`, `user_id`, `subject`, `message`, `status`, `created_at`, `ai_reply`, `admin_reply`) VALUES
(1, 1, 'Order Delay Inquiry', 'I ordered 2 weeks ago, where is my book?', 'open', '2026-02-08 20:08:02', NULL, NULL),
(2, 1, 'Wrong Item Received', 'I got the wrong ISBN.', 'closed', '2026-02-08 20:08:02', NULL, NULL),
(3, 1, 'Wrong Price', 'I got wrong items with wrong price', 'open', '2026-02-10 10:31:22', 'Thank you for contacting us. Our AI system is currently offline, but a human agent has been notified and will review your ticket regarding \'Wrong Price\' shortly. Please check your Order History for status updates.', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tier_rules`
--

CREATE TABLE `tier_rules` (
  `id` int NOT NULL,
  `name` varchar(50) NOT NULL,
  `min_qty` int NOT NULL,
  `discount_percent` decimal(5,2) NOT NULL,
  `color` varchar(20) DEFAULT 'gray'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tier_rules`
--

INSERT INTO `tier_rules` (`id`, `name`, `min_qty`, `discount_percent`, `color`) VALUES
(1, 'Retail', 0, 0.00, 'gray'),
(2, 'Class Set', 20, 10.00, 'blue'),
(3, 'School License', 100, 20.00, 'green'),
(4, 'District Partner', 500, 30.00, 'gold');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` int NOT NULL,
  `order_id` int NOT NULL,
  `user_id` int NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `proof_image` varchar(255) DEFAULT NULL,
  `status` enum('pending','verified','rejected') DEFAULT 'pending',
  `admin_note` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `note` varchar(100) DEFAULT 'Installment'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id`, `order_id`, `user_id`, `amount`, `proof_image`, `status`, `admin_note`, `created_at`, `note`) VALUES
(1, 4, 5, 200000.00, 'pay_4_1770554600.jpeg', 'verified', NULL, '2026-02-08 12:43:20', 'Installment'),
(2, 4, 5, 200000.00, 'pay_4_1770555520.jpeg', 'verified', NULL, '2026-02-08 12:58:40', 'Installment');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('public','school','admin') DEFAULT 'public',
  `position` varchar(100) DEFAULT NULL,
  `institution` varchar(150) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text,
  `is_banned` tinyint(1) DEFAULT '0',
  `is_approved` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `position`, `institution`, `city`, `phone`, `address`, `is_banned`, `is_approved`, `created_at`) VALUES
(1, 'Admin', 'admin@compass.com', '$2y$12$z2epecELzAsm.Qg2grFF6u4ocEqsyDxR66Nl0fcTIRzwUGScPUEdG', 'admin', 'Staff', 'PT SEG', NULL, '08123456789', 'Headquarters', 0, 1, '2026-02-08 11:58:58'),
(2, 'Budi Santoso', 'budi@school.id', '$2y$12$ZrhfeA4XikDqGOSUNxC6H.gyL4KUOUM4Qe4hM4yiYwP1VwlIb2aNa', 'school', NULL, NULL, NULL, '08129999888', 'SMA Negeri 1 Jakarta', 0, 1, '2026-02-08 11:58:58'),
(3, 'Siti Aminah', 'siti@gmail.com', '$2y$12$ZrhfeA4XikDqGOSUNxC6H.gyL4KUOUM4Qe4hM4yiYwP1VwlIb2aNa', 'public', NULL, NULL, NULL, '08127777666', 'Private Tutor, Bandung', 0, 1, '2026-02-08 11:58:58'),
(4, 'John Doe', 'john@intl.sch.id', '$2y$12$ZrhfeA4XikDqGOSUNxC6H.gyL4KUOUM4Qe4hM4yiYwP1VwlIb2aNa', 'school', NULL, NULL, NULL, '08125555444', 'Inter School Surabaya', 0, 1, '2026-02-08 11:58:58'),
(5, 'Abu Bakar', 'bakar@ceg.com', '$2y$12$z2epecELzAsm.Qg2grFF6u4ocEqsyDxR66Nl0fcTIRzwUGScPUEdG', 'public', NULL, NULL, NULL, NULL, 'No address on file', 0, 1, '2026-02-08 12:10:22'),
(6, 'Aldi Alfiandi', 'aldialfiandi2708@gmail.com', '$2y$12$JOT8aR9pvc5IV6QClr3ySeviSgIEJt9xEhnooUoF8HufsikfnkdgS', 'public', 'Staff', 'PT SEG', 'Sukabumi', NULL, NULL, 0, 1, '2026-03-03 17:19:11'),
(7, 'Aldi Alfiandi', 'aldyalfiandi2708@gmail.com', '$2y$12$B0C.OwZqyO7waEaMk.2XluQMA4WSJZ0ldRgVkg5I20qOn7F78QLMG', 'public', 'Staff', 'PT SEG', 'Sukabumi', '085174448002', NULL, 0, 1, '2026-03-03 19:29:19');

-- --------------------------------------------------------

--
-- Table structure for table `user_addresses`
--

CREATE TABLE `user_addresses` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `label` varchar(50) DEFAULT 'School',
  `recipient` varchar(100) DEFAULT '',
  `recipient_name` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address_line` text,
  `city` varchar(100) DEFAULT NULL,
  `postal_code` varchar(10) DEFAULT NULL,
  `is_default` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `user_addresses`
--

INSERT INTO `user_addresses` (`id`, `user_id`, `label`, `recipient`, `recipient_name`, `phone`, `address_line`, `city`, `postal_code`, `is_default`, `created_at`) VALUES
(2, 2, 'Main Address', 'Unknown', 'Budi Santoso', '08129999888', 'SMA Negeri 1 Jakarta', 'Jakarta Selatan', NULL, 1, '2026-02-08 13:31:39'),
(3, 3, 'Main Address', 'Unknown', 'Siti Aminah', '08127777666', 'Private Tutor, Bandung', 'Jakarta Selatan', NULL, 1, '2026-02-08 13:31:39'),
(4, 4, 'Main Address', 'Unknown', 'John Doe', '08125555444', 'Inter School Surabaya', 'Jakarta Selatan', NULL, 1, '2026-02-08 13:31:39'),
(6, 1, 'SMK Islam Penguji', 'Unknown', 'Aldi Alfiandi', '085174448002', 'Jl. Pelabuhan II, Gg. Nakula No. 76\nRT. 002/RW. 001, Kel. Tipar, Kec. Citamiang', 'Sukabumi', '43141', 1, '2026-02-08 14:31:36'),
(7, 6, 'PT SEG', 'Aldi Alfiandi', NULL, '085174448002', 'Jl. Pelabuhan II, Gg. Nakula No. 76\r\nRT. 002/RW. 001, Kel. Tipar, Kec. Citamiang', 'Sukabumi', '43141', 0, '2026-03-03 17:20:08'),
(8, 7, 'PT SEG', 'Aldi Alfiandi', NULL, '085174448002', 'Jl. Pelabuhan II, Gg. Nakula No. 76\r\nRT. 002/RW. 001, Kel. Tipar, Kec. Citamiang', 'Sukabumi', '43141', 0, '2026-03-03 20:33:22');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `interest_forms`
--
ALTER TABLE `interest_forms`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_ref_id` (`reference_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_payments`
--
ALTER TABLE `order_payments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `system_settings`
--
ALTER TABLE `system_settings`
  ADD PRIMARY KEY (`setting_key`);

--
-- Indexes for table `tickets`
--
ALTER TABLE `tickets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `tier_rules`
--
ALTER TABLE `tier_rules`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `user_addresses`
--
ALTER TABLE `user_addresses`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `books`
--
ALTER TABLE `books`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=574;

--
-- AUTO_INCREMENT for table `interest_forms`
--
ALTER TABLE `interest_forms`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `order_payments`
--
ALTER TABLE `order_payments`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=581;

--
-- AUTO_INCREMENT for table `tickets`
--
ALTER TABLE `tickets`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tier_rules`
--
ALTER TABLE `tier_rules`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `user_addresses`
--
ALTER TABLE `user_addresses`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tickets`
--
ALTER TABLE `tickets`
  ADD CONSTRAINT `tickets_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
