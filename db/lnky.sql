-- phpMyAdmin SQL Dump
-- version 4.5.2
-- http://www.phpmyadmin.net
--
-- Host: localhost:3307
-- Generation Time: Feb 26, 2017 at 04:07 PM
-- Server version: 10.1.9-MariaDB-log
-- PHP Version: 5.6.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `lnky`
--
CREATE DATABASE IF NOT EXISTS `lnky` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `lnky`;

-- --------------------------------------------------------

--
-- Table structure for table `api_keys`
--

CREATE TABLE `api_keys` (
  `id` int(11) NOT NULL,
  `user` int(11) NOT NULL,
  `domain` varchar(30) NOT NULL,
  `api_key` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `click_details`
--

CREATE TABLE `click_details` (
  `id` int(11) NOT NULL,
  `link` varchar(15) NOT NULL,
  `country` varchar(255) NOT NULL,
  `timestamp` datetime NOT NULL,
  `ip` varchar(128) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `countries`
--

CREATE TABLE `countries` (
  `country` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `domains`
--

CREATE TABLE `domains` (
  `domain` varchar(30) NOT NULL,
  `url` varchar(255) NOT NULL,
  `api_url` varchar(255) NOT NULL,
  `ref_link` varchar(255) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `links`
--

CREATE TABLE `links` (
  `link` varchar(15) NOT NULL,
  `user` int(11) NOT NULL,
  `real_url` text NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `pay_rates`
--

CREATE TABLE `pay_rates` (
  `id` int(11) NOT NULL,
  `domain` varchar(30) NOT NULL,
  `country` varchar(255) NOT NULL,
  `rate` decimal(3,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `short_links`
--

CREATE TABLE `short_links` (
  `id` int(11) NOT NULL,
  `link` varchar(15) NOT NULL,
  `domain` varchar(30) NOT NULL,
  `extension` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `site_settings`
--

CREATE TABLE `site_settings` (
  `id` int(11) NOT NULL,
  `pay_rate` int(3) NOT NULL,
  `acceptable_pay_range` int(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `display_name` varchar(25) NOT NULL DEFAULT 'User',
  `password` varchar(64) NOT NULL,
  `clef_id` bigint(20) NOT NULL,
  `verified` tinyint(1) NOT NULL DEFAULT '0',
  `logout_time` int(20) NOT NULL,
  `until_pay` int(3) NOT NULL,
  `admin` tinyint(1) NOT NULL DEFAULT '0',
  `banned` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `api_keys`
--
ALTER TABLE `api_keys`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user` (`user`),
  ADD KEY `domain` (`domain`);

--
-- Indexes for table `click_details`
--
ALTER TABLE `click_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `link` (`link`),
  ADD KEY `country` (`country`);

--
-- Indexes for table `countries`
--
ALTER TABLE `countries`
  ADD PRIMARY KEY (`country`);

--
-- Indexes for table `domains`
--
ALTER TABLE `domains`
  ADD PRIMARY KEY (`domain`);

--
-- Indexes for table `links`
--
ALTER TABLE `links`
  ADD PRIMARY KEY (`link`),
  ADD KEY `user` (`user`);

--
-- Indexes for table `pay_rates`
--
ALTER TABLE `pay_rates`
  ADD PRIMARY KEY (`id`),
  ADD KEY `domain` (`domain`),
  ADD KEY `country` (`country`);

--
-- Indexes for table `short_links`
--
ALTER TABLE `short_links`
  ADD PRIMARY KEY (`id`),
  ADD KEY `link` (`link`),
  ADD KEY `domain` (`domain`);

--
-- Indexes for table `site_settings`
--
ALTER TABLE `site_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `api_keys`
--
ALTER TABLE `api_keys`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `click_details`
--
ALTER TABLE `click_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `pay_rates`
--
ALTER TABLE `pay_rates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `short_links`
--
ALTER TABLE `short_links`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `site_settings`
--
ALTER TABLE `site_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `api_keys`
--
ALTER TABLE `api_keys`
  ADD CONSTRAINT `domain_fk` FOREIGN KEY (`domain`) REFERENCES `domains` (`domain`),
  ADD CONSTRAINT `user_fk` FOREIGN KEY (`user`) REFERENCES `users` (`id`);

--
-- Constraints for table `click_details`
--
ALTER TABLE `click_details`
  ADD CONSTRAINT `click_country_fk` FOREIGN KEY (`country`) REFERENCES `countries` (`country`),
  ADD CONSTRAINT `click_link_fk` FOREIGN KEY (`link`) REFERENCES `links` (`link`);

--
-- Constraints for table `links`
--
ALTER TABLE `links`
  ADD CONSTRAINT `user_links_fk` FOREIGN KEY (`user`) REFERENCES `users` (`id`);

--
-- Constraints for table `pay_rates`
--
ALTER TABLE `pay_rates`
  ADD CONSTRAINT `pr_countries_fk` FOREIGN KEY (`country`) REFERENCES `countries` (`country`),
  ADD CONSTRAINT `pr_domain_fk` FOREIGN KEY (`domain`) REFERENCES `domains` (`domain`);

--
-- Constraints for table `short_links`
--
ALTER TABLE `short_links`
  ADD CONSTRAINT `short_link_domain_fk` FOREIGN KEY (`domain`) REFERENCES `domains` (`domain`),
  ADD CONSTRAINT `short_link_link_fk` FOREIGN KEY (`link`) REFERENCES `links` (`link`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
