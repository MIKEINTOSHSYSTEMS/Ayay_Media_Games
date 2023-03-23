-- phpMyAdmin SQL Dump
-- version 4.9.11
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Mar 23, 2023 at 03:26 PM
-- Server version: 10.5.19-MariaDB-cll-lve
-- PHP Version: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ayaygames`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` smallint(5) UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `slug` varchar(30) NOT NULL,
  `description` mediumtext CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `meta_description` mediumtext CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `slug`, `description`, `meta_description`) VALUES
(1, 'Coloring Book', 'coloring-book', '', ''),
(2, 'Puzzle', 'puzzle', '', ''),
(3, 'Story Book', 'story-book', '', ''),
(4, 'Math Games', 'math-games', '', ''),
(5, 'Memory Game', 'memory-game', '', ''),
(6, 'Arcade', 'arcade', '', ''),
(7, 'Casuals', 'casuals', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `cat_links`
--

CREATE TABLE `cat_links` (
  `id` smallint(5) UNSIGNED NOT NULL,
  `gameid` smallint(5) UNSIGNED NOT NULL,
  `categoryid` smallint(5) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `cat_links`
--

INSERT INTO `cat_links` (`id`, `gameid`, `categoryid`) VALUES
(1, 1, 1),
(2, 2, 2),
(3, 3, 4),
(4, 4, 5),
(5, 5, 3),
(6, 6, 2),
(7, 6, 3),
(8, 7, 6),
(9, 7, 7),
(10, 8, 2),
(11, 9, 1),
(12, 10, 7),
(13, 10, 2),
(14, 11, 7),
(15, 11, 2),
(16, 12, 7),
(17, 12, 5),
(18, 12, 2),
(19, 13, 7),
(20, 13, 2),
(21, 14, 6),
(22, 15, 6),
(23, 15, 7),
(24, 16, 7),
(25, 16, 5),
(26, 17, 6),
(27, 17, 7),
(28, 17, 4),
(29, 17, 5),
(30, 17, 2);

-- --------------------------------------------------------

--
-- Table structure for table `collections`
--

CREATE TABLE `collections` (
  `id` smallint(5) UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `data` mediumtext CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `collections`
--

INSERT INTO `collections` (`id`, `name`, `data`) VALUES
(1, 'Ayay Media Games', '1,2,3,4,5');

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` int(10) UNSIGNED NOT NULL,
  `game_id` int(10) NOT NULL,
  `parent_id` int(10) UNSIGNED DEFAULT NULL,
  `comment` varchar(200) NOT NULL,
  `sender_id` int(40) NOT NULL,
  `sender_username` varchar(20) NOT NULL,
  `created_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `games`
--

CREATE TABLE `games` (
  `id` smallint(5) UNSIGNED NOT NULL,
  `createddate` date NOT NULL,
  `title` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `description` mediumtext CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `instructions` mediumtext CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `category` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `source` text NOT NULL,
  `thumb_1` varchar(255) NOT NULL,
  `thumb_2` varchar(255) NOT NULL,
  `thumb_small` varchar(255) NOT NULL,
  `url` text NOT NULL,
  `width` text NOT NULL,
  `height` text NOT NULL,
  `tags` text NOT NULL,
  `views` int(11) NOT NULL,
  `upvote` int(11) NOT NULL,
  `downvote` int(11) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `data` mediumtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `games`
--

INSERT INTO `games` (`id`, `createddate`, `title`, `description`, `instructions`, `category`, `source`, `thumb_1`, `thumb_2`, `thumb_small`, `url`, `width`, `height`, `tags`, `views`, `upvote`, `downvote`, `slug`, `data`) VALUES
(1, '2023-03-06', 'Ayay Coloring Book', 'Desc', 'How to ', 'Coloring Book', 'remote', '/images/gamethumbs/ayay_coloring_book.jpeg', '/images/gamethumbs/ayay_coloring_book.jpeg', '', '/src/games/ayay_coloringbook/color-book.html', '1280', '900', '', 23, 3, 1, 'ayay-coloring-book', ''),
(2, '2023-03-06', 'Ayay Jigsaw Puzzle', 'Desc', 'Inst', 'Puzzle', 'remote', '/images/gamethumbs/ayay_jigsaw_1.jpeg', '/images/gamethumbs/ayay_jigsaw_1.jpeg', '', '/src/games/ayay_jigsaw/index.html', '2880', '2880', '', 30, 1, 0, 'ayay-jigsaw-puzzle', ''),
(3, '2023-03-06', 'Ayay Math Game', 'Desc', 'Inst', 'Math Games', 'remote', '/images/gamethumbs/mathgame.jpeg', '/images/gamethumbs/mathgame.jpeg', '', '/src/games/ayay_mathgame/index.html', '1280', '900', '', 26, 1, 0, 'ayay-math-game', ''),
(4, '2023-03-06', 'Ayay Flip Memory Game', 'Desc', 'Inst', 'Memory Game', 'remote', '/images/gamethumbs/ayay_flip_memory.jpeg', '/images/gamethumbs/ayay_flip_memory.jpeg', '', '/src/games/ayay_memory_flip/index.html', '1280', '720', '', 25, 1, 0, 'ayay-flip-memory-game', ''),
(5, '2023-03-06', ' áŒ¥áŠ•á‰¸áˆ‰ áŠ¥áŠ“ áŠ¤áˆŠá‹ | Ayay Story Book', 'áŒ¥áŠ•á‰¸áˆ‰ áŠ¥áŠ“ áŠ¤áˆŠá‹á¢', 'Tap on the screen of left and right to go to next or previous pages.', 'Story Book', 'remote', '/images/gamethumbs/ayay-story-book.jpeg', '/images/gamethumbs/ayay-story-book.jpeg', '/thumbs/ayay-story-book-ayay-story-book-ayay_storybook_small.jpeg', '/src/games/ayay_storybook/index.html', '1280', '720', '', 90, 3, 0, 'ayay-story-book', ''),
(6, '2023-03-06', 'Ayay Quiz Story Books', 'Desc', 'Inst', 'Puzzle,Story Book', 'remote', '/images/gamethumbs/ayaystories.jpeg', '/images/gamethumbs/ayaystories.jpeg', '', '/src/games/ayay_storybooks/index.html', '1280', '720', '', 22, 1, 0, 'ayay-quiz-story-books', ''),
(7, '2023-03-07', 'Frog Arcade', 'Desc', 'Inst', 'Arcade,Casuals', 'remote', '/images/gamethumbs/ayayfrog.jpeg', '/images/gamethumbs/ayayfrog.jpeg', '', '/src/games/ayay_arcadefrog/index.html', '1280', '720', '', 16, 1, 0, 'frog-arcade', ''),
(8, '2023-03-11', 'Ayay Jigsaw 2', 'Desc', 'Inst', 'Puzzle', 'remote', '/images/gamethumbs/ayay_jigsaw_2.jpeg', '/images/gamethumbs/ayay_jigsaw_2.jpeg', '', '/src/games/ayay_jigsaw_2/index.html', '1280', '1280', '', 44, 1, 0, 'ayay-jigsaw-2', ''),
(9, '2023-03-17', 'Ayay Coloring Book 2', 'Desc', 'Inst', 'Coloring Book', 'remote', '/images/gamethumbs/ayay_coloring_book2.jpeg', '/images/gamethumbs/ayay_coloring_book2.jpeg', '', '/src/games/ayay_coloring_book/index.html', '1280', '1280', '', 48, 1, 1, 'ayay-coloring-book-2', ''),
(10, '2023-03-22', 'Ayay Word Scramble', 'Desc', 'Inst', 'Casuals,Puzzle', 'remote', '/images/gamethumbs/ayaywordscramble.png', '/images/gamethumbs/ayaywordscramble.png', '', '/src/games/ayay_word_scramble/index.html', '1280', '720', '', 3, 0, 0, 'ayay-word-scramble', ''),
(11, '2023-03-22', 'Ayay Word Guess', 'Desc', 'Inst', 'Casuals,Puzzle', 'remote', '/images/gamethumbs/ayayguessword.png', '/images/gamethumbs/ayayguessword.png', '', '/src/games/ayay_word_guess/index.html', '1280', '720', '', 2, 0, 0, 'ayay-word-guess', ''),
(12, '2023-03-22', 'Ayay Visual Pattern Memory', 'Desc', 'Inst', 'Casuals,Memory Game,Puzzle', 'remote', '/images/gamethumbs/ayayvisualpatternmemorygame.png', '/images/gamethumbs/ayayvisualpatternmemorygame.png', '', '/src/games/ayay_visual_memory/index.html', '1280', '1280', '', 5, 0, 0, 'ayay-visual-pattern-memory', ''),
(13, '2023-03-22', 'Ayay Tower Builder', 'Desc', 'Inst', 'Casuals,Puzzle', 'remote', '/images/gamethumbs/ayaytowerbuilding.png', '/images/gamethumbs/ayaytowerbuilding.png', '', '/src/games/ayay_tower/index.html', '1280', '2400', '', 5, 0, 0, 'ayay-tower-builder', ''),
(14, '2023-03-22', 'Ayay Speed Tap', 'Desc', 'Inst', 'Arcade', 'remote', '/images/gamethumbs/ayayspeedtap.png', '/images/gamethumbs/ayayspeedtap.png', '', '/src/games/ayay_speedtap/index.html', '1280', '900', '', 5, 0, 0, 'ayay-speed-tap', ''),
(15, '2023-03-22', 'Ayay HangMan', 'Desc', 'Inst', 'Arcade,Casuals', 'remote', '/images/gamethumbs/ayayhangman.png', '/images/gamethumbs/ayayhangman.png', '', '/src/games/ayay_hangman/index.html', '1280', '2560', '', 6, 0, 0, 'ayay-hangman', ''),
(16, '2023-03-22', 'Ayay Fruit Memory', 'Desc', 'Inst', 'Casuals,Memory Game', 'remote', '/images/gamethumbs/ayayfruitbrainmemory.png', '/images/gamethumbs/ayayfruitbrainmemory.png', '', '/src/games/ayay_fruit_memory/index.html', '1280', '2400', '', 7, 0, 0, 'ayay-fruit-memory', ''),
(17, '2023-03-22', 'Ayay\'s Cube', 'Desc', 'Inst', 'Arcade,Casuals,Math Games,Memory Game,Puzzle', 'remote', '/images/gamethumbs/ayayscube.png', '/images/gamethumbs/ayayscube.png', '', '/src/games/ayay_cube/index.html', '1280', '720', '', 2, 0, 0, 'ayays-cube', '');

-- --------------------------------------------------------

--
-- Table structure for table `loginlogs`
--

CREATE TABLE `loginlogs` (
  `id` smallint(5) UNSIGNED NOT NULL,
  `IpAddress` varbinary(16) NOT NULL,
  `TryTime` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `login_history`
--

CREATE TABLE `login_history` (
  `id` smallint(5) UNSIGNED NOT NULL,
  `ip` varbinary(16) NOT NULL,
  `data` mediumtext CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `login_history`
--

INSERT INTO `login_history` (`id`, `ip`, `data`) VALUES
(1, 0x3139362e3138382e35352e313935, '{\"username\":\"ayaymediagames\",\"password\":\"mik****\",\"date\":\"2023-03-06 14:11:37\",\"status\":\"success\",\"agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/110.0.0.0 Safari\\/537.36 Edg\\/110.0.1587.63\",\"country\":\"United Kingdom\",\"city\":\"\"}'),
(2, 0x3139372e3135362e3130332e3433, '{\"username\":\"ayaymediagames\",\"password\":\"***\",\"date\":\"2023-03-06 15:40:13\",\"status\":\"success\",\"agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/110.0.0.0 Safari\\/537.36 Edg\\/110.0.1587.63\",\"country\":\"United Kingdom\",\"city\":\"\"}'),
(3, 0x3139362e3138382e33342e323134, '{\"username\":\"ayaymediagames\",\"password\":\"***\",\"date\":\"2023-03-06 20:16:46\",\"status\":\"success\",\"agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/110.0.0.0 Safari\\/537.36 Edg\\/110.0.1587.63\",\"country\":\"United Kingdom\",\"city\":\"\"}'),
(4, 0x3139362e3138382e33342e323134, '{\"username\":\"ayaymediagames\",\"password\":\"***\",\"date\":\"2023-03-06 21:59:58\",\"status\":\"success\",\"agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/110.0.0.0 Safari\\/537.36 Edg\\/110.0.1587.63\",\"country\":\"United Kingdom\",\"city\":\"\"}'),
(5, 0x3139372e3135362e3130332e3433, '{\"username\":\"ayaymediagames\",\"password\":\"***\",\"date\":\"2023-03-07 15:34:46\",\"status\":\"success\",\"agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/110.0.0.0 Safari\\/537.36 Edg\\/110.0.1587.63\",\"country\":\"United Kingdom\",\"city\":\"\"}'),
(6, 0x3139372e3135362e3130332e3433, '{\"username\":\"ayaymediagames\",\"password\":\"***\",\"date\":\"2023-03-07 17:27:06\",\"status\":\"success\",\"agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/110.0.0.0 Safari\\/537.36 Edg\\/110.0.1587.63\",\"country\":\"United Kingdom\",\"city\":\"\"}'),
(7, 0x3139372e3135362e39352e313131, '{\"username\":\"ayaymediagames\",\"password\":\"***\",\"date\":\"2023-03-10 18:23:45\",\"status\":\"success\",\"agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/110.0.0.0 Safari\\/537.36 Edg\\/110.0.1587.63\",\"country\":\"United Kingdom\",\"city\":\"\"}'),
(8, 0x3139372e3135362e39352e313131, '{\"username\":\"ayaymediagames\",\"password\":\"***\",\"date\":\"2023-03-11 10:26:11\",\"status\":\"success\",\"agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/110.0.0.0 Safari\\/537.36 Edg\\/110.0.1587.63\",\"country\":\"United Kingdom\",\"city\":\"\"}'),
(9, 0x3139372e3135362e39352e313131, '{\"username\":\"ayaymediagames\",\"password\":\"***\",\"date\":\"2023-03-11 11:28:16\",\"status\":\"success\",\"agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/110.0.0.0 Safari\\/537.36 Edg\\/110.0.1587.63\",\"country\":\"United Kingdom\",\"city\":\"\"}'),
(10, 0x3139362e3139312e3232312e313232, '{\"username\":\"ayaymediagames\",\"password\":\"***\",\"date\":\"2023-03-17 14:30:25\",\"status\":\"success\",\"agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/111.0.0.0 Safari\\/537.36 Edg\\/111.0.1661.41\",\"country\":\"United Kingdom\",\"city\":\"\"}'),
(11, 0x3139362e3139312e3232312e313232, '{\"username\":\"ayaymediagames\",\"password\":\"***\",\"date\":\"2023-03-17 17:05:17\",\"status\":\"success\",\"agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/111.0.0.0 Safari\\/537.36 Edg\\/111.0.1661.41\",\"country\":\"United Kingdom\",\"city\":\"\"}'),
(12, 0x3139362e3138382e33342e323134, '{\"username\":\"ayaymediagames\",\"password\":\"***\",\"date\":\"2023-03-17 18:26:26\",\"status\":\"success\",\"agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/111.0.0.0 Safari\\/537.36 Edg\\/111.0.1661.41\",\"country\":\"United Kingdom\",\"city\":\"\"}'),
(13, 0x3139362e3139302e36302e3234, '{\"username\":\"ayaymediagames\",\"password\":\"***\",\"date\":\"2023-03-22 12:16:51\",\"status\":\"success\",\"agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/111.0.0.0 Safari\\/537.36 Edg\\/111.0.1661.44\",\"country\":\"United Kingdom\",\"city\":\"\"}');

-- --------------------------------------------------------

--
-- Table structure for table `options`
--

CREATE TABLE `options` (
  `id` smallint(5) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `value` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `options`
--

INSERT INTO `options` (`id`, `name`, `value`) VALUES
(1, 'site_title', 'Ayay Media Games'),
(2, 'site_description', 'Play AyayMedia Games'),
(3, 'meta_description', 'Play Ayay Games for Free'),
(4, 'site_logo', 'images/ayay-logo-web-01.png'),
(5, 'theme_name', 'default'),
(6, 'import_thumb', 'true'),
(7, 'small_thumb', 'false'),
(8, 'custom_slug', 'false'),
(9, 'pretty_url', 'true'),
(10, 'url_protocol', 'https://'),
(11, 'purchase_code', ''),
(12, 'language', 'en'),
(13, 'comments', 'true'),
(14, 'upload_avatar', 'true'),
(15, 'user_register', 'true');

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

CREATE TABLE `pages` (
  `id` smallint(5) UNSIGNED NOT NULL,
  `createddate` date NOT NULL,
  `title` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `slug` varchar(255) NOT NULL,
  `content` mediumtext CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `pages`
--

INSERT INTO `pages` (`id`, `createddate`, `title`, `slug`, `content`) VALUES
(1, '2023-03-06', 'About Us', 'about-us', 'About Ayay Media Games ');

-- --------------------------------------------------------

--
-- Table structure for table `statistics`
--

CREATE TABLE `statistics` (
  `id` int(11) UNSIGNED NOT NULL,
  `created_date` date DEFAULT NULL,
  `page_views` varchar(255) DEFAULT NULL,
  `unique_visitor` varchar(255) DEFAULT NULL,
  `data` mediumtext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `statistics`
--

INSERT INTO `statistics` (`id`, `created_date`, `page_views`, `unique_visitor`, `data`) VALUES
(1, '2023-03-06', '337', '10', '{\"browser\":{\"Edge-110.0.1587.63\":4,\"Firefox-109.0\":1,\"Edge-107.0.1418.62\":1,\"Chrome-108.0.0.0\":4},\"os\":{\"Windows-10\":4,\"Ubuntu-undefined\":1,\"Android-10\":3,\"Linux-x86_64\":2},\"language\":{\"en-GB\":4,\"en-US\":6},\"refferer\":{\"direct\":10},\"screen_size\":{\"2195x1235\":3,\"1366x768\":1,\"432x936\":5,\"1920x1080\":1},\"country\":{\"United Kingdom\":10},\"city\":{\"\":10},\"device_vendor\":{\"HTC\":3}}'),
(2, '2023-03-07', '50', '11', '{\"browser\":{\"Chrome-110.0.0.0\":2,\"Edge-110.0.1587.63\":9},\"os\":{\"Android-9\":2,\"Windows-10\":9},\"device_vendor\":{\"Samsung\":2},\"language\":{\"en-US\":8,\"en-GB\":3},\"refferer\":{\"direct\":11},\"screen_size\":{\"360x740\":2,\"1536x864\":6,\"2195x1235\":2,\"1920x1080\":1},\"country\":{\"United Kingdom\":11},\"city\":{\"\":11}}'),
(3, '2023-03-08', '10', '2', '{\"browser\":{\"Chrome-110.0.0.0\":2},\"os\":{\"Windows-10\":2},\"language\":{\"en-US\":2},\"refferer\":{\"direct\":2},\"screen_size\":{\"1200x800\":2},\"country\":{\"United Kingdom\":2},\"city\":{\"\":2}}'),
(4, '2023-03-09', '1', '1', '{\"browser\":{\"Edge-110.0.1587.63\":1},\"os\":{\"Windows-10\":1},\"language\":{\"en-GB\":1},\"refferer\":{\"direct\":1},\"screen_size\":{\"1920x1080\":1},\"country\":{\"United Kingdom\":1},\"city\":{\"\":1}}'),
(5, '2023-03-10', '58', '5', '{\"browser\":{\"Edge-110.0.1587.63\":3,\"Chrome-110.0.0.0\":1,\"Edge-17.17134\":1},\"os\":{\"Windows-10\":4,\"Mac OS-10.15.7\":1},\"language\":{\"en-GB\":3,\"am\":1,\"en-US\":1},\"refferer\":{\"direct\":5},\"screen_size\":{\"2195x1235\":3,\"1440x900\":1,\"1138x640\":1},\"country\":{\"United Kingdom\":5},\"city\":{\"\":5}}'),
(6, '2023-03-11', '31', '2', '{\"browser\":{\"Edge-110.0.1587.63\":2},\"os\":{\"Windows-10\":2},\"language\":{\"en-GB\":2},\"refferer\":{\"direct\":2},\"screen_size\":{\"2195x1235\":2},\"country\":{\"United Kingdom\":2},\"city\":{\"\":2}}'),
(7, '2023-03-13', '21', '9', '{\"browser\":{\"Edge-110.0.1587.69\":8,\"Chrome-110.0.0.0\":1},\"os\":{\"Windows-10\":8,\"Mac OS-10.15.7\":1},\"language\":{\"en-GB\":3,\"en-US\":5,\"am\":1},\"refferer\":{\"direct\":9},\"screen_size\":{\"2195x1235\":3,\"1536x864\":5,\"1440x900\":1},\"country\":{\"United Kingdom\":9},\"city\":{\"\":9}}'),
(8, '2023-03-14', '3', '2', '{\"browser\":{\"Edge-110.0.1587.69\":2},\"os\":{\"Windows-10\":2},\"language\":{\"en-GB\":2},\"refferer\":{\"direct\":2},\"screen_size\":{\"2195x1235\":2},\"country\":{\"United Kingdom\":2},\"city\":{\"\":2}}'),
(9, '2023-03-15', '2', '2', '{\"browser\":{\"Edge-110.0.1587.69\":2},\"os\":{\"Windows-10\":2},\"language\":{\"en-GB\":2},\"refferer\":{\"direct\":2},\"screen_size\":{\"2195x1235\":2},\"country\":{\"United Kingdom\":2},\"city\":{\"\":2}}'),
(10, '2023-03-17', '114', '3', '{\"browser\":{\"Edge-111.0.1661.41\":3},\"os\":{\"Windows-10\":3},\"language\":{\"en-GB\":3},\"refferer\":{\"direct\":3},\"screen_size\":{\"2195x1235\":2,\"1920x1080\":1},\"country\":{\"United Kingdom\":3},\"city\":{\"\":3}}'),
(11, '2023-03-18', '3', '3', '{\"browser\":{\"Edge-111.0.1661.41\":2,\"Chrome-110.0.0.0\":1},\"os\":{\"Windows-10\":2,\"Mac OS-10.15.7\":1},\"language\":{\"en-GB\":2,\"am\":1},\"refferer\":{\"direct\":3},\"screen_size\":{\"2195x1235\":1,\"1440x900\":1,\"1920x1080\":1},\"country\":{\"United Kingdom\":3},\"city\":{\"\":3}}'),
(12, '2023-03-19', '2', '2', '{\"browser\":{\"Chrome-110.0.0.0\":2},\"os\":{\"Mac OS-10.15.7\":2},\"language\":{\"am\":2},\"refferer\":{\"direct\":2},\"screen_size\":{\"1440x900\":2},\"country\":{\"United Kingdom\":2},\"city\":{\"\":2}}'),
(13, '2023-03-20', '6', '3', '{\"browser\":{\"Chrome-111.0.0.0\":2,\"Edge-111.0.1661.44\":1},\"os\":{\"Windows-10\":3},\"language\":{\"en-US\":2,\"en-GB\":1},\"refferer\":{\"direct\":3},\"screen_size\":{\"1366x768\":2,\"1920x1080\":1},\"country\":{\"United Kingdom\":3},\"city\":{\"\":3}}'),
(14, '2023-03-21', '2', '2', '{\"browser\":{\"Edge-111.0.1661.44\":2},\"os\":{\"Windows-10\":2},\"language\":{\"en-GB\":2},\"refferer\":{\"direct\":2},\"screen_size\":{\"1920x1080\":2},\"country\":{\"United Kingdom\":2},\"city\":{\"\":2}}'),
(15, '2023-03-22', '85', '3', '{\"browser\":{\"Edge-111.0.1661.44\":2,\"WebKit-534\":1},\"os\":{\"Windows-10\":2,\"Windows-7\":1},\"language\":{\"en-GB\":2,\"en-US\":1},\"refferer\":{\"direct\":3},\"screen_size\":{\"2195x1235\":2,\"800x600\":1},\"country\":{\"United Kingdom\":3},\"city\":{\"\":3}}'),
(16, '2023-03-23', '5', '3', '{\"browser\":{\"Edge-111.0.1661.44\":3},\"os\":{\"Windows-10\":3},\"language\":{\"en-GB\":3},\"refferer\":{\"direct\":3},\"screen_size\":{\"2195x1235\":3},\"country\":{\"United Kingdom\":3},\"city\":{\"\":3}}');

-- --------------------------------------------------------

--
-- Table structure for table `stats_ip_address`
--

CREATE TABLE `stats_ip_address` (
  `id` int(11) UNSIGNED NOT NULL,
  `ip_address` varchar(255) DEFAULT NULL,
  `created_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `stats_ip_address`
--

INSERT INTO `stats_ip_address` (`id`, `ip_address`, `created_date`) VALUES
(46, '196.188.55.155', '2023-03-23'),
(47, '197.156.107.90', '2023-03-23');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` smallint(5) UNSIGNED NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` text NOT NULL,
  `role` varchar(255) NOT NULL,
  `join_date` date DEFAULT NULL,
  `birth_date` date DEFAULT NULL,
  `gender` varchar(15) DEFAULT NULL,
  `data` text DEFAULT NULL,
  `email` varchar(40) DEFAULT NULL,
  `bio` varchar(180) DEFAULT NULL,
  `xp` varchar(180) DEFAULT '0',
  `avatar` varchar(180) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`, `join_date`, `birth_date`, `gender`, `data`, `email`, `bio`, `xp`, `avatar`) VALUES
(1, 'ayaymediagames', '$2y$10$4ABHPb3SUYqLTy87G9cOHO7pWIqyYw.EZ5oTKcLH.6HKRjhNzX/5W', 'admin', NULL, NULL, NULL, '{\"likes\":[\"2\",\"3\",\"4\",\"5\",\"1\",\"8\"]}', NULL, NULL, '70', '0'),
(2, 'michael', '$2y$10$WQsngph9GAJedP8pfRvAhujr9dHwYfwwuuqPh0qfYTDdfGSlj3RgG', 'user', '2023-03-06', '1990-07-09', 'male', '{\"likes\":[\"1\"]}', 'michaelktd@gmail.com', NULL, '10', '13');

-- --------------------------------------------------------

--
-- Table structure for table `votelogs`
--

CREATE TABLE `votelogs` (
  `id` smallint(5) UNSIGNED NOT NULL,
  `game_id` smallint(5) UNSIGNED NOT NULL,
  `ip` varbinary(16) NOT NULL,
  `action` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `votelogs`
--

INSERT INTO `votelogs` (`id`, `game_id`, `ip`, `action`) VALUES
(1, 2, 0x3139372e3135362e3130332e3433, 'upvote'),
(2, 3, 0x3139372e3135362e3130332e3433, 'upvote'),
(3, 1, 0x3139372e3135362e3130332e3433, 'downvote'),
(4, 1, 0x3139372e3135362e3130332e3433, 'upvote'),
(5, 4, 0x3139372e3135362e3130332e3433, 'upvote'),
(6, 5, 0x3139372e3135362e3130332e3433, 'upvote'),
(7, 1, 0x3139362e3139302e36302e313838, 'upvote'),
(8, 5, 0x37322e35322e38372e3535, 'upvote'),
(9, 5, 0x36352e34392e36382e3535, 'upvote'),
(10, 7, 0x3139372e3135362e39352e313131, 'upvote'),
(11, 6, 0x3139372e3135362e39352e313131, 'upvote'),
(12, 1, 0x3139362e3139312e3232312e313232, 'upvote'),
(13, 8, 0x3139362e3139312e3232312e313232, 'upvote'),
(14, 9, 0x3139362e3139312e3232312e313232, 'upvote'),
(15, 9, 0x3139362e3138382e33342e323134, 'downvote');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cat_links`
--
ALTER TABLE `cat_links`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `collections`
--
ALTER TABLE `collections`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `parent_id` (`parent_id`);

--
-- Indexes for table `games`
--
ALTER TABLE `games`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `loginlogs`
--
ALTER TABLE `loginlogs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `login_history`
--
ALTER TABLE `login_history`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `options`
--
ALTER TABLE `options`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pages`
--
ALTER TABLE `pages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `statistics`
--
ALTER TABLE `statistics`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stats_ip_address`
--
ALTER TABLE `stats_ip_address`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `votelogs`
--
ALTER TABLE `votelogs`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `cat_links`
--
ALTER TABLE `cat_links`
  MODIFY `id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `collections`
--
ALTER TABLE `collections`
  MODIFY `id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `games`
--
ALTER TABLE `games`
  MODIFY `id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `loginlogs`
--
ALTER TABLE `loginlogs`
  MODIFY `id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `login_history`
--
ALTER TABLE `login_history`
  MODIFY `id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `options`
--
ALTER TABLE `options`
  MODIFY `id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `pages`
--
ALTER TABLE `pages`
  MODIFY `id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `statistics`
--
ALTER TABLE `statistics`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `stats_ip_address`
--
ALTER TABLE `stats_ip_address`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `votelogs`
--
ALTER TABLE `votelogs`
  MODIFY `id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`parent_id`) REFERENCES `comments` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
