-- phpMyAdmin SQL Dump
-- version 4.1.6
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Sep 19, 2014 at 01:26 AM
-- Server version: 5.6.16
-- PHP Version: 5.5.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `blog`
--

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE IF NOT EXISTS `posts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category` varchar(100) NOT NULL,
  `title` varchar(500) NOT NULL,
  `description` varchar(500) NOT NULL,
  `tags` varchar(1000) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `folder_path` varchar(1000) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=40 ;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `category`, `title`, `description`, `tags`, `created`, `folder_path`) VALUES
(22, 'blog', 'Toucan Cal', 'this is the toucan can he is amazingly kewl', 'bmx, captain, cal', '2014-07-27 22:37:58', '/posts/blogs/2013'),
(30, 'video', 'hgfhfg', 'tfhfgh', 'hfghfg', '2014-08-12 01:46:23', '/posts/projects'),
(31, 'video', 'Ell Cal', 'dsada', 'asdas', '2014-08-12 04:27:08', '/posts/blogs/2013'),
(32, 'project', 'parot cal', 'bbbb parrot pirate arrg', 'ddddddd', '2014-08-12 04:42:35', '/posts/blogs/2014'),
(33, 'blog', 'aftr cancel edit', 'asdd', 'asdasdasd', '2014-08-12 04:44:07', '/posts/blogs/2014'),
(34, 'blog', 'Diggies', 'The Dogs Are Out', 'Where are my dogs', '2014-08-13 01:17:25', '/posts/blogs/2013'),
(35, 'blog', 'tt', 'tt', 'tt', '2014-08-13 01:59:27', '/posts/projects'),
(37, 'video', 'Test Save 111', 'asdas d', 'dasdasd', '2014-08-15 01:25:55', '/posts/blogs'),
(38, 'video', 'Toucan Same ole same ole', 'This si the toucan sam doin what he does best', '', '2014-08-15 04:05:32', '/posts/blogs/2013'),
(39, 'video', 'dszd', 'asdasfd', '', '2014-08-31 18:55:48', '/posts/videos');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
