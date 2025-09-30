-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Sep 29, 2025 at 08:21 AM
-- Server version: 8.0.30
-- PHP Version: 8.3.20

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `doc`
--

-- --------------------------------------------------------

--
-- Table structure for table `device`
--

CREATE TABLE `device` (
  `id_device` int NOT NULL,
  `name` varchar(30) NOT NULL DEFAULT '',
  `group_dev` varchar(30) NOT NULL DEFAULT '',
  `status` varchar(30) NOT NULL DEFAULT '',
  `kode` varchar(4) DEFAULT '-'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `device`
--

INSERT INTO `device` (`id_device`, `name`, `group_dev`, `status`, `kode`) VALUES
(1, 'New Remocon', 'Opto', 'Aktif', '-'),
(2, 'PI', 'Opto', 'Aktif', '-'),
(3, 'PC300N', 'Opto', 'Aktif', '-'),
(4, 'PC400 N', 'Opto', 'Aktif', '-'),
(5, 'PT-GL', 'Opto', 'Aktif', '-'),
(6, 'SC-63', 'Opto', 'Aktif', '-'),
(7, 'SC-63 Matrix', 'Opto', 'Aktif', '-'),
(8, 'DDS', 'Opto', 'Aktif', '-'),
(9, 'Smoke Sensor', 'Opto', 'Aktif', '-'),
(10, 'SOP 4 PIN', 'Opto', 'Aktif', '-'),
(11, 'SSR 400', 'Opto', 'Aktif', '-'),
(12, 'Smoke Sensor GP3Y7', 'Opto', 'Aktif', '-'),
(13, 'SIP SSR', 'Opto', 'Aktif', '-'),
(14, 'Bulb Lamp E-14', 'Opto', 'Aktif', '-'),
(15, 'Proximity Sensor', 'Opto', 'Aktif', '-'),
(16, 'Bulb Lamp E-27', 'Opto', 'Aktif', '-'),
(17, 'Street Lamp', 'Opto', 'Aktif', '-'),
(18, 'TO-220', 'Opto', 'Aktif', '-'),
(19, 'LED', 'LED', 'Aktif', '-'),
(20, 'New LED', 'LED', 'Aktif', '-'),
(21, 'Back Light LED', 'LED', 'Aktif', 'BL'),
(22, 'Megazeni', 'LED', 'Aktif', 'MZ'),
(23, 'Petitzeni', 'LED', 'Aktif', 'PZ'),
(24, 'Kozeni', 'LED', 'Aktif', 'KZ'),
(25, 'Kakuzeni', 'LED', 'Aktif', 'KK'),
(26, 'Gigazeni', 'LED', 'Aktif', 'GZ'),
(27, 'Torazeni', 'LED', 'Aktif', 'TZ'),
(28, 'Zenigata LED', 'LED', 'Aktif', '-'),
(29, 'NT Zeni', 'LED', 'Aktif', '-'),
(30, 'HL 4.8t', 'Laser', 'Aktif', '-'),
(31, 'SL 5.6', 'Laser', 'Aktif', '-'),
(32, 'SL 3.3', 'Laser', 'Aktif', '-'),
(33, 'FL 1.8t', 'Laser', 'Aktif', '-'),
(34, 'Chip Making', 'Laser', 'Aktif', '-'),
(36, 'Test', 'Laser', 'Aktif', '-'),
(38, 'Tube Lamp', 'Opto', 'Aktif', '-'),
(39, 'Showcase LED Lamp', 'Opto', 'Aktif', '-'),
(40, 'PC817G', 'Opto', 'Aktif', '-'),
(41, 'CoS RED', 'Laser', 'Aktif', '-'),
(42, 'SMALL-LESS KOZENI', 'LED', 'Aktif', ''),
(43, 'SMALL-LESS MEGAZENI', 'LED', 'Aktif', ''),
(44, 'High Bay Lamp', 'Opto', 'Aktif', '-'),
(45, 'GENERAL SMALL-LESS', 'LED', 'Aktif', '-'),
(46, 'Blue Laser', 'Laser', 'Aktif', '-'),
(47, 'General Laser', 'Laser', 'Aktif', '-'),
(48, 'Green Laser', 'Laser', 'Aktif', '-'),
(49, 'SL 3.8', 'Laser', 'Aktif', '-'),
(50, 'SL 9.0', 'Laser', 'Aktif', '-'),
(51, 'General Single Laser', 'Laser', 'Aktif', '-'),
(52, 'General PC', 'Opto', 'Aktif', '-'),
(53, 'Masker', 'Masker', 'Aktif', '-'),
(54, 'General OPTO', 'Opto', 'Aktif', '-');

-- --------------------------------------------------------

--
-- Table structure for table `distribusi`
--

CREATE TABLE `distribusi` (
  `id_dis` int NOT NULL,
  `no_drf` int NOT NULL DEFAULT '0',
  `pic` varchar(50) NOT NULL DEFAULT '0',
  `give` int NOT NULL DEFAULT '0',
  `date_give` varchar(20) NOT NULL DEFAULT '-',
  `location` varchar(35) NOT NULL DEFAULT '-',
  `receiver` varchar(40) NOT NULL DEFAULT '0',
  `retrieve` varchar(30) NOT NULL DEFAULT '-',
  `retrieve_from` varchar(40) NOT NULL DEFAULT '',
  `retrieve_date` varchar(30) NOT NULL DEFAULT '-',
  `remarks` varchar(255) NOT NULL DEFAULT '-'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `docu`
--

CREATE TABLE `docu` (
  `no_drf` int NOT NULL,
  `user_id` varchar(30) NOT NULL DEFAULT '',
  `uploader_name` varchar(100) DEFAULT NULL,
  `email` varchar(60) NOT NULL DEFAULT '',
  `dept` varchar(30) NOT NULL DEFAULT '',
  `original_dept` varchar(50) DEFAULT NULL,
  `no_doc` varchar(50) NOT NULL DEFAULT '',
  `no_rev` int NOT NULL DEFAULT '0',
  `rev_to` varchar(20) NOT NULL DEFAULT '',
  `doc_type` varchar(25) NOT NULL DEFAULT '',
  `section` varchar(50) NOT NULL DEFAULT '',
  `original_section` varchar(50) DEFAULT NULL,
  `device` varchar(50) NOT NULL DEFAULT '',
  `process` varchar(95) NOT NULL DEFAULT '',
  `title` varchar(225) NOT NULL DEFAULT '',
  `descript` text,
  `iso` int DEFAULT '0',
  `seqtrain` int DEFAULT '0',
  `dirtrain` int DEFAULT '0',
  `file` varchar(255) NOT NULL DEFAULT '',
  `history` text NOT NULL,
  `status` varchar(10) NOT NULL DEFAULT 'review',
  `tgl_upload` varchar(20) NOT NULL DEFAULT '0000-00-00',
  `category` varchar(15) DEFAULT NULL,
  `final` varchar(15) NOT NULL DEFAULT '-',
  `file_asli` varchar(150) NOT NULL DEFAULT '-',
  `reminder` int NOT NULL DEFAULT '0',
  `sos_file` varchar(255) DEFAULT '',
  `sos_uploaded_by` varchar(100) DEFAULT '',
  `sos_upload_date` datetime DEFAULT NULL,
  `sos_notes` text
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `hasil`
--

CREATE TABLE `hasil` (
  `parameter` varchar(15) NOT NULL DEFAULT '',
  `nilai` int NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `hasil`
--

INSERT INTO `hasil` (`parameter`, `nilai`) VALUES
('iya', 10),
('tidak', 15);

-- --------------------------------------------------------

--
-- Table structure for table `proc`
--

CREATE TABLE `proc` (
  `id_proc` varchar(15) NOT NULL DEFAULT '',
  `nama_proc` varchar(35) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `proc`
--

INSERT INTO `proc` (`id_proc`, `nama_proc`) VALUES
('BA000', 'INPUT'),
('BA001', 'CTEST RANK'),
('BA003', 'REPEL COAT'),
('BA005', 'PRINT INS'),
('BA010', 'DIE BOND'),
('BA020', 'PASTE CURE'),
('BA030', 'WIRE BOND'),
('BA040', 'DS ATTACH'),
('BA050', 'WB QC'),
('BA055', 'WBQC GATE'),
('BA060', 'MOLDING A'),
('BA065', 'PHOSPOR SET'),
('BA070', 'MOLD A CUR'),
('BA073', 'PLASMA CLN'),
('BA075', 'PLASMA INS'),
('BA077', 'MOLD A INS'),
('BA080', 'DS REMOVE'),
('BA090', 'MOLDING B'),
('BA100', 'MOLD B CUR'),
('BA110', 'SUB S'),
('BA120', 'VISUAL INS'),
('BA130', 'WASHING'),
('BA140', 'CHAR TEST'),
('BA142', 'C TEST OUT'),
('BA143', 'TAPING NG'),
('BA145', 'LOT MAKING'),
('BA150', 'TAPING'),
('BA160', 'TAPING QC'),
('BA165', 'TAPING QC2'),
('BA170', 'PACKING'),
('BA200', 'BL SHIPOUT'),
('GZ000', 'GZ)INPUT'),
('GZ010', 'GZ)DIE BONDING'),
('GZ015', 'GZ)DIE BONDING Cure'),
('GZ020', 'GZ)DIE BONDING ZENER'),
('GZ025', 'GZ)DIE BONDING ZENER Cure'),
('GZ030', 'GZ)WIRE BONDING'),
('GZ040', 'GZ)WIRE BONDING QC'),
('GZ050', 'GZ)DAMRING'),
('GZ055', 'GZ)DAMRING Cure'),
('GZ060', 'GZ)MOLDING'),
('GZ065', 'GZ)MOLDING Cure'),
('GZ070', 'GZ)CONNECTOR MOUNTING'),
('GZ075', 'GZ)CONNECTOR Cure'),
('GZ080', 'GZ)CHARACTERISTIC TEST'),
('GZ090', 'GZ)VISUAL INSPECTION'),
('GZ120', 'GZ)LOT MAKING'),
('GZ190', 'GZ)PACKING'),
('GZ200', 'GZ)SHIPOUT'),
('KK000', 'KK)INPUT'),
('KK010', 'KK)DIE BOND'),
('KK020', 'KK)PASTE CURE'),
('KK030', 'KK)WIRE BOND'),
('KK040', 'KK)WB QC'),
('KK050', 'KK)DAMRING'),
('KK055', 'KK)DAMRING CURE'),
('KK060', 'KK)MOLD'),
('KK065', 'KK)MOLD CURE'),
('KK070', 'KK)PCB BREAK'),
('KK073', 'KK)VISUAL INS 1'),
('KK075', 'KK)HOLDER ASSEMBLY'),
('KK080', 'KK)CHAR TEST'),
('KK090', 'KK)VISUAL INS'),
('KK120', 'KK)LOT MAKING'),
('KK190', 'KK)PACKING'),
('KK200', 'KK)BL SHIPOUT'),
('KZ000', 'KZ)INPUT'),
('KZ010', 'KZ)DIE BOND'),
('KZ020', 'KZ)PASTE CURE'),
('KZ030', 'KZ)WIRE BOND'),
('KZ040', 'KZ)WB QC'),
('KZ050', 'KZ)DAMRING'),
('KZ055', 'KZ)DAMRING CURE'),
('KZ060', 'KZ)MOLD'),
('KZ065', 'KZ)MOLD CURE'),
('KZ070', 'KZ)SUB S'),
('KZ080', 'KZ)CHAR TEST'),
('KZ090', 'KZ)VISUAL INS'),
('KZ120', 'KZ)LOT MAKING'),
('KZ190', 'KZ)PACKING'),
('KZ200', 'KZ)BL SHIPOUT'),
('MZ000', 'MZ)INPUT'),
('MZ010', 'MZ)DIE BOND'),
('MZ020', 'MZ)PASTE CURE'),
('MZ030', 'MZ)WIRE BOND'),
('MZ040', 'MZ)WB QC'),
('MZ050', 'MZ)DAMRING'),
('MZ055', 'MZ)DAMRING CURE'),
('MZ060', 'MZ)MOLD'),
('MZ065', 'MZ)MOLD CURE'),
('MZ070', 'MZ)PCB BREAK'),
('MZ080', 'MZ)CHAR TEST'),
('MZ090', 'MZ)VISUAL INS'),
('MZ120', 'MZ)LOT MAKING'),
('MZ190', 'MZ)PACKING'),
('MZ200', 'MZ)BL SHIPOUT'),
('NT000', 'NT)INPUT'),
('NT010', 'NT)DIE BOND'),
('NT020', 'NT)PASTE CURE'),
('NT030', 'NT)WIRE BOND'),
('NT040', 'NT)WB QC'),
('NT050', 'NT)DAMRING'),
('NT055', 'NT)DAMRING CURE'),
('NT060', 'NT)MOLD1'),
('NT065', 'NT)MOLD CURE1'),
('NT066', 'NT)MOLD2'),
('NT067', 'NT)MOLD CURE2'),
('NT070', 'NT)SUB S'),
('NT080', 'NT)CHAR TEST'),
('NT090', 'NT)VISUAL INS'),
('NT120', 'NT)LOT MAKING'),
('NT190', 'NT)PACKING'),
('NT200', 'NT)BL SHIPOUT'),
('PZ000', 'PZ)INPUT'),
('PZ010', 'PZ)DIE BOND'),
('PZ020', 'PZ)PASTE CURE'),
('PZ030', 'PZ)WIRE BOND'),
('PZ040', 'PZ)WB QC'),
('PZ050', 'PZ)DAMRING'),
('PZ055', 'PZ)DAMRING CURE'),
('PZ060', 'PZ)MOLD'),
('PZ065', 'PZ)MOLD CURE'),
('PZ070', 'PZ)CHAR TEST'),
('PZ080', 'PZ)SUB S'),
('PZ090', 'PZ)VISUAL INS'),
('PZ120', 'PZ)LOT MAKING'),
('PZ190', 'PZ)PACKING'),
('PZ200', 'PZ)BL SHIPOUT'),
('TZ000', 'TZ)INPUT'),
('TZ010', 'TZ)DIE BOND'),
('TZ020', 'TZ)PASTE CURE'),
('TZ030', 'TZ)WIRE BOND'),
('TZ040', 'TZ)WB QC'),
('TZ050', 'TZ)DAMRING'),
('TZ055', 'TZ)DAMRING CURE'),
('TZ060', 'TZ)MOLD1'),
('TZ065', 'TZ)MOLD CURE1'),
('TZ066', 'TZ)MOLD2'),
('TZ067', 'TZ)MOLD CURE2'),
('TZ070', 'TZ)SUB S'),
('TZ080', 'TZ)CHAR TEST'),
('TZ090', 'TZ)VISUAL INS'),
('TZ120', 'TZ)LOT MAKING'),
('TZ190', 'TZ)PACKING'),
('TZ200', 'TZ)BL SHIPOUT');

-- --------------------------------------------------------

--
-- Table structure for table `process`
--

CREATE TABLE `process` (
  `id_proc` int NOT NULL,
  `proc_name` varchar(255) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `process`
--

INSERT INTO `process` (`id_proc`, `proc_name`) VALUES
(1, 'Wire Bonding WQ'),
(2, 'Wire Bonding QC'),
(3, 'Wire Bonding And  Wire Bonding QC'),
(4, 'Wire Bonding'),
(5, 'Welding'),
(6, 'Washing'),
(7, 'Visual Inspection and Electrical Test'),
(8, 'Visual Inspection'),
(9, 'Visual & Marking'),
(10, 'Visual'),
(11, 'Viso'),
(12, 'VDE Packing Label'),
(13, 'UV Resin Hardening'),
(14, 'Transistor'),
(15, 'Transfer Molding Work Standard'),
(16, 'Transfer Molding'),
(17, 'TR Die Bonding'),
(18, 'Tiebar Cutting'),
(19, 'Tie Bar Cut'),
(21, 'Temporary Packing'),
(22, 'Temporary'),
(23, 'Temp. Packing'),
(24, 'TC Die Bonding'),
(25, 'Taping Packing Work Standard'),
(26, 'Taping Packing'),
(27, 'Taping'),
(28, 'Superposition'),
(29, 'Stem Setting B'),
(30, 'Stem setting A'),
(31, 'Stem Set&Lead Correct'),
(32, 'Stem Set A'),
(33, 'Soldering Inspection'),
(34, 'Solder Potting'),
(35, 'Solder Plating'),
(36, 'Solder Dipping'),
(37, 'Sleeve Packing Standard'),
(38, 'Silver Paste Hardening'),
(39, 'Silver Paste Hard'),
(40, 'Shield Case Soldering'),
(42, 'Resin Poting'),
(43, 'Resin Hardening'),
(44, 'Resin Cutting'),
(45, 'Removing of Remained Resin'),
(46, 'Remove Hologram'),
(47, 'Remove Bari'),
(48, 'Reflow Standard'),
(49, 'Reflow'),
(50, 'Record of Revision'),
(51, 'Random Test'),
(52, 'PWB Cut'),
(53, 'Process Flowchart SOT-23-5 series'),
(54, 'Precoating'),
(55, 'Precoat'),
(56, 'Plating Inspection'),
(57, 'Plating'),
(58, 'PIN Die Bonding'),
(59, 'PD Die Bonding'),
(60, 'PCB Cutting'),
(61, 'PC357N*J007series'),
(62, 'PC355N*J0007series'),
(63, 'Parts Mounting'),
(64, 'Partial Discharge Test (Non Destructive)'),
(65, 'Partial Destructive Test'),
(66, 'Part Mounting Drawing'),
(67, 'Packing Label'),
(68, 'Packing And Temperature Packing'),
(69, 'Packing (Taping)'),
(70, 'Packing'),
(71, 'Outgoing Inspection'),
(72, 'Outgoing Insp.General QC'),
(73, 'Open Check'),
(74, 'MSP Die Bonding'),
(75, 'MS Die Bonding'),
(76, 'Molding'),
(77, 'Mirror Die Bonding'),
(78, 'Marking Drawing'),
(79, 'Marking'),
(80, 'Manual'),
(81, 'Leak Testing'),
(82, 'Lead Cutting And Form'),
(83, 'Lead Cutting'),
(84, 'Lead Cut'),
(85, 'LD PD Die Bonding And DB QC'),
(86, 'LD PD Die Bonding'),
(87, 'LD Die Bonding2'),
(88, 'LD Die Bonding1'),
(89, 'LD Die Bonding'),
(90, 'LD D/B 2'),
(91, 'LD D/B 1'),
(92, 'Labeling'),
(93, 'Insp.Standard'),
(94, 'Insert Element'),
(95, 'Indium Printing'),
(96, 'Incoming Inspection'),
(97, 'IC Die Bonding'),
(98, 'Holofixing'),
(99, 'Holo Fixing'),
(100, 'Heat Cycle,High Temperature Test,High Temperature Leaving Work Standard'),
(101, 'General Appearance Inspection'),
(102, 'General'),
(103, 'Gauge inspection'),
(104, 'Gate Cutting'),
(105, 'Frame'),
(106, 'Forming Lot'),
(107, 'Forming'),
(108, 'First Tie Bar Resin Cut'),
(109, 'First Tie Bar Cutting Standard'),
(110, 'Final Testing'),
(111, 'Final Test'),
(112, 'External Inspection'),
(113, 'E-Test'),
(114, 'Equipped With Parts'),
(115, 'Equipment Maintenance'),
(116, 'Equiped With Parts'),
(117, 'Electro Plating QC'),
(118, 'Electro Plating'),
(119, 'Electrical Testing'),
(120, 'Electrical Test'),
(121, 'Electrical Inspection'),
(122, 'Electric Characteristic Testing'),
(123, 'Electric'),
(124, 'E. Test'),
(125, 'E - Test'),
(126, 'Die/Wire Bonding'),
(127, 'Die/ Wire Bond QC'),
(128, 'Die, Wire Bonding'),
(129, 'Die, Wire'),
(130, 'Die Bonding2'),
(131, 'Die Bonding1'),
(132, 'Die Bonding Common Work Instruction Sheet'),
(133, 'Die Bonding 2'),
(134, 'Die Bonding 1'),
(135, 'Die Bonding'),
(136, 'Device Soldering'),
(137, 'Cutting/Forming'),
(138, 'Cutting'),
(139, 'Curing, Wire Bonding'),
(140, 'Craddle Cut Work Standard'),
(141, 'Cover of Wok Instruction'),
(142, 'Chip Mounting'),
(143, 'Chart. Testing'),
(144, 'Manual Test'),
(145, 'Characteristic Test'),
(146, 'Characteristic Insp'),
(148, 'Char.Testing'),
(149, 'Casing, Testing'),
(150, 'Casing'),
(151, 'Case Assembly'),
(152, 'Cap Set'),
(153, 'Cap Seal'),
(154, 'Calibration'),
(155, 'Burn-in 1'),
(156, 'Burn In'),
(157, 'Bonding,Wire'),
(158, 'Bonding QC'),
(159, 'Auto System'),
(160, 'Attach Tape'),
(161, 'Appearance Inspection'),
(162, 'All Process'),
(163, 'AGL'),
(164, 'Ag Paste Hard'),
(165, '2nd Transfer Mold'),
(166, '2nd Transfer'),
(167, '2nd Tie Bar Cutting'),
(168, '1st Transfer Mold'),
(169, '1st Tie Bar Cutting'),
(174, 'Hot Test'),
(175, 'Curing Oven'),
(176, 'Heat Cycle'),
(177, 'Forming-Viso'),
(178, '2nd Transfer Molding'),
(179, '1st Transfer Molding'),
(180, 'Viso Forming'),
(181, 'Flash Writing'),
(182, 'Drying'),
(183, 'Product Separation'),
(184, 'Baking C'),
(185, 'Baking A'),
(186, 'Baking B'),
(187, 'Zener Diode Die Bonding'),
(188, 'Apply moisture proof resin'),
(189, 'Outer packing'),
(190, 'Assembly'),
(191, 'cleaning'),
(192, 'Rework'),
(194, 'Metal Sheet Soldering'),
(195, 'Molding curing'),
(196, 'Curing paste'),
(197, 'Oven hardening'),
(198, 'Tie Bar Cutting and Forming'),
(199, 'Unit separator'),
(200, 'PCB Inspection'),
(201, 'Soldering'),
(202, 'Reflow Soldering'),
(203, 'Wire Bonding and Checking Machine'),
(204, 'Airblow and Inspection'),
(205, 'E-Test 2'),
(206, 'Lead Formimg'),
(207, 'Substrate Separation'),
(208, 'Molding Cure A'),
(209, 'Molding Cure B'),
(210, 'Dam Sheet Remove'),
(211, 'Electrical test - Marking'),
(212, 'Counting'),
(213, 'Predipping'),
(214, 'Shipping'),
(215, 'Lead Gauge'),
(216, 'Dam Sheet Attach'),
(217, 'PCB Cleaning'),
(218, 'Airblow PCB'),
(219, 'Chamber & PCB Connection'),
(220, 'Heat Welding'),
(221, 'Air Blow Chamber'),
(222, 'US Welding'),
(223, 'Chamber Inspection (Pre-test)'),
(224, 'Assembly Chamber'),
(225, 'E-Test-1'),
(226, 'X-Ray'),
(227, 'Hardening'),
(228, 'Assembly 1'),
(229, 'Assembly 2'),
(234, 'Vacuum Inspection'),
(235, 'Aging and E-Test-3'),
(236, 'E-Test -3'),
(237, 'LD Bar Aligment QC'),
(238, 'LD Alignment'),
(239, 'LD Bar Scribe'),
(240, 'LD Bar Brake'),
(241, 'Expand'),
(242, 'Reverse'),
(243, 'LD Chip Test'),
(244, 'Accept Inspection'),
(245, 'Counter Chip CCL'),
(246, 'Counter Chip CCA'),
(247, 'Chip Tester'),
(248, 'Appearance Inspection 1'),
(249, 'Appearance Inspection 2'),
(250, 'VF Wave Test'),
(251, 'Blower'),
(252, 'Molding A'),
(253, 'Molding B'),
(254, 'Air Blow Cap'),
(255, 'Hot Temperatur Keeping'),
(256, 'UV Irradiation'),
(257, 'Material Control'),
(258, 'Plasma Cleaning'),
(259, 'Printing Inspection'),
(260, 'Printing'),
(261, 'Damring'),
(262, 'Damring cure'),
(263, 'Plasma Cleaning'),
(264, 'Plasma Cleaning'),
(265, 'Plasma Cleaning'),
(266, 'Die Bond Cure'),
(267, 'Taping QC'),
(269, 'Lot Making'),
(270, 'Burn-in CD'),
(271, 'Burn-in DVD'),
(272, 'Zener Diode Die Bonding Cure'),
(273, 'Connector Mounting'),
(274, 'Precedence Test'),
(275, 'Dicing'),
(276, 'UV Attach'),
(277, 'Aging'),
(278, 'Driver Test'),
(279, 'Pre-Curing Molding'),
(280, 'Damring Dispense'),
(281, 'Temporarily Curing'),
(282, 'Runner Cutting & Hardening'),
(283, 'Laser Marking'),
(284, 'UV Sheet Adhesive'),
(285, 'UV Irradiation, Device Release & Drying'),
(286, 'Device Inserting'),
(287, 'Visual Inspection & Packing'),
(288, ' LDSM'),
(289, ' LDSM QC'),
(291, 'All laser'),
(293, 'Helium Gas');

-- --------------------------------------------------------

--
-- Table structure for table `rel_doc`
--

CREATE TABLE `rel_doc` (
  `id` int NOT NULL,
  `no_drf` int NOT NULL DEFAULT '0',
  `no_doc` varchar(70) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `rev_doc`
--

CREATE TABLE `rev_doc` (
  `id` int NOT NULL,
  `id_doc` int NOT NULL DEFAULT '0',
  `nrp` varchar(15) NOT NULL DEFAULT '',
  `reviewer_name` varchar(100) DEFAULT NULL,
  `reviewer_section` varchar(100) DEFAULT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'Review',
  `tgl_approve` varchar(35) NOT NULL DEFAULT '-',
  `reason` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `section`
--

CREATE TABLE `section` (
  `id_section` varchar(255) NOT NULL DEFAULT '',
  `sect_name` varchar(255) NOT NULL DEFAULT '',
  `section_dept` varchar(255) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `section`
--

INSERT INTO `section` (`id_section`, `sect_name`, `section_dept`) VALUES
('Accounting', 'Accounting Section', 'Administration'),
('BMG', 'Business Management', 'Administration'),
('DCU', 'DCU', 'Quality Control'),
('Engineering', 'Engineering Section', 'Engineering'),
('Exim Control', 'Exim Control', 'Administration'),
('FCS Group', 'FCS Group', 'Engineering'),
('GA and Personnel', 'GA and Personnel Section', 'Administration'),
('Job Innovation', 'Job Innovation', 'Job Innovation'),
('MIS', 'Management Information System', 'Administration'),
('President Director', 'President Director', ''),
('Product Innovation', 'Product Innovation', 'Engineering'),
('Product Warehouse Control', 'Product Warehouse Control', 'Administration'),
('Production', 'Production Section', 'Production'),
('Production Sales Control', 'Production Sales Control Section', 'Administration'),
('Purchasing', 'Purchasing', 'Administration'),
('Purchasing and MC', 'Purchasing and MC Section', 'Administration'),
('QA Section', 'QA Section', 'Quality Control'),
('QC', 'QC', 'Quality Control'),
('R & D', 'Research and Development', 'Administration'),
('SHE', 'SHE Secretariat', 'Administration'),
('Maintenance', 'Maintenance Section', 'Production Engineering');

-- --------------------------------------------------------

--
-- Table structure for table `user2`
--

CREATE TABLE `user2` (
  `id` int NOT NULL,
  `username` varchar(15) NOT NULL DEFAULT '',
  `name` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '',
  `email` varchar(150) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '',
  `password` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '',
  `section` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '',
  `state` varchar(30) NOT NULL DEFAULT '',
  `level` int NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `user2`
--

INSERT INTO `user2` (`id`, `username`, `name`, `email`, `password`, `section`, `state`, `level`) VALUES
(27, '000098', 'dio', 'dio', 'dio', 'EXIM', 'Admin', 2),
(28, '000099', 'user', 'user', 'user', 'Management Information System', 'Originator', 1),
(30, 'admin', 'admin', 'admin@ugm.ac.id', 'admin', 'Management Information System', 'Admin', 3);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `username` varchar(255) NOT NULL DEFAULT '',
  `name` varchar(255) NOT NULL DEFAULT '',
  `email` varchar(255) NOT NULL DEFAULT '',
  `password` varchar(255) NOT NULL DEFAULT '',
  `section` varchar(255) NOT NULL DEFAULT '',
  `state` varchar(15) NOT NULL DEFAULT '',
  `level` int NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `name`, `email`, `password`, `section`, `state`, `level`) VALUES
(1, '000808', 'Wahyudi', 'wahyudi@ssi.sharp-world.com', '000808', 'Engineering Section', 'Originator', 3),
(2, '001332', 'Evy Setiawan', 'wawan@ssi.sharp-world.com', '001332', 'Management Information System', 'inactive', 3),
(3, 'admin_mis', 'Administrator', 'ssi_mis@ssi.sharp-world.com', 'asdf123!', 'Management Information System', 'Admin', 3),
(4, '000642', 'Suhenda', 'suhenda@ssi.sharp-world.com', '000642', 'Production Section', 'Approver', 5),
(5, '003800', 'Mohammad Soegiharto', 'm_soegi@ssi.sharp-world.com', '003800', 'GA and Personnel Section', 'resign', 3),
(7, '000385', 'Jaenal A', 'jaenal@ssi.sharp-world.com', '000385', 'Product Warehouse Control', 'Approver', 8),
(8, '000684', 'M. Erwin', 'erwin@ssi.sharp-world.com', '000684', 'Production Section', 'Approver', 6),
(9, '003364', 'Ocky Galih Pratama', 'ocky@ssi.sharp-world.com', '003364', 'Management Information System', 'Originator', 7),
(10, '002176', 'Hani Puspitawati', 'hani_p@ssi.sharp-world.com', '002176', 'Engineering Section', 'Originator', 5),
(12, '001946', 'Iwan Martanto', 'iwan@ssi.sharp-world.com', '001946', 'Product Innovation', 'Approver', 5),
(13, '000817', 'Ismi Retno', 'ismi@ssi.sharp-world.com', '000817', 'Production Section', 'Originator', 7),
(14, '001947', 'Gatot Christyana', 'gatotc@ssi.sharp-world.com', '001947', 'Production Section', 'Approver', 3),
(15, '001182', 'Diki Z', 'diki@ssi.sharp-world.com', '001182', 'Purchasing and MC Section', 'Originator', 4),
(16, '000045', 'Vica Andrianto', 'vica@ssi.sharp-world.com', '000045', 'Engineering Section', 'Approver', 3),
(17, '000816', 'Rena Adiyati', 'rena@ssi.sharp-world.com', '000816', 'QC', 'Originator', 5),
(18, '001840', 'Ani Juni Astuti', 'anijuni@ssi.sharp-world.com', '001840', 'QC', 'Originator', 6),
(19, '002187', 'Tri Pambudi', 'pambudi@ssi.sharp-world.com', '002187', 'GA and Personnel Section', 'Approver', 3),
(20, '003429', 'M. Yusup AL', 'yusup_al@ssi.sharp-world.com', '003429', 'Management Information System', 'Originator', 8),
(21, 'gzbs024130', 'H. Deguchi', 'deguchi@ssi.sharp-world.com', 'gzbs024130', 'Engineering Section', 'Approver', 1),
(22, '003830', 'Ahmad Mudasir', 'mudasir_a@ssi.sharp-world.com', '003830', 'Engineering Section', 'Approver', 5),
(23, '000658', 'Sugiyani', 'hrdc_eko@ssi.sharp-world.com', '000658', 'Business Management', 'Approver', 7),
(25, '000918', 'Dian Novita', 'novita@ssi.sharp-world.com', '000918', 'Production Section', 'Inactive', 4),
(27, '000412', 'Supriyadi', 'yadi@ssi.sharp-world.com', '000412', 'QC', 'Originator', 2),
(28, '001427', 'Abdul Manan', 'manan@ssi.sharp-world.com', '001427', 'Exim Control', 'Approver', 7),
(29, '000664', 'Endah Karyani', 'endah@ssi.sharp-world.com', '000664', 'Business Management', 'Originator', 6),
(30, '003115', 'Henis', 'rnd@ssi.sharp-world.com', '003115', 'Research and Development', 'Approver', 9),
(33, '000424', 'Dedi Eka Putra', 'dedi_ep@ssi.sharp-world.com', '000424', 'Production Sales Control Section', 'Approver', 3),
(35, '000322', 'Dedet Mardani', 'dedet@ssi.sharp-world.com', '000322', 'Maintenance Section', 'Approver', 3),
(36, '000050', 'Alen Novari', 'alen@ssi.sharp-world.com', '000050', 'Engineering Section', 'Approver', 5),
(37, '000053', 'Afvandri Tamsin', 'afvandri@ssi.sharp-world.com', '000053', 'Engineering Section', 'Approver', 3),
(39, '000318', 'Andry Bayu', 'andry@ssi.sharp-world.com', '000318', 'Purchasing and MC Section', 'resign', 5),
(40, '000066', 'Hendry Haryadi', 'hendry@ssi.sharp-world.com', '000066', 'Purchasing and MC Section', 'Approver', 3),
(41, '003826', 'Syamsul Rijal', 'rijal@ssi.sharp-world.com', '003826', 'GA and Personnel Section', 'Approver', 3),
(42, '000033', 'Irvan Aldi', 'Irvan@ssi.sharp-world.com', '000033', 'Business Management', 'Approver', 3),
(43, '003828', 'Akhmad Yunianto', 'akhmad@ssi.sharp-world.com', '003828', 'GA and Personnel Section', 'Approver', 3),
(44, '002158', 'Primandi Baskoro', 'primandi@ssi.sharp-world.com', '002158', 'Research and Development', 'Approver', 5),
(45, '000032', 'Ridwan Walangadi', 'ridwan@ssi.sharp-world.com', '000032', 'Production Section', 'Approver', 1),
(46, '000229', 'Solehudin', 'soleh@ssi.sharp-world.com', '000229', 'Production Section', 'Approver', 4),
(47, '002109', 'Dodi Syafriadi', 'dodi_s@ssi.sharp-world.com', '002109', 'Job Innovation', 'Originator', 4),
(48, '000068', 'Yarnes Amir', 'yarnes@ssi.sharp-world.com', '000068', 'Production Section', 'Approver', 6),
(49, '000039', 'M. Iqbal', 'iqbal@ssi.sharp-world.com', '000039', 'Research and Development', 'Approver', 3),
(50, '000043', 'Kosnurdin', 'nurdin@ssi.sharp-world.com', '000043', 'QA Section', 'Approver', 2),
(51, '000931', 'Heru Pamungkas', 'heru@ssi.sharp-world.com', '000931', 'FCS Group', 'Originator', 7),
(52, '002162', 'Rahmat Priyo Utomo', 'rahmat@ssi.sharp-world.com', '002162', 'Accounting Section', 'Approver', 3),
(53, '002243', 'Wiguna', 'wigunamu@ssi.sharp-world.com', '002243', 'Business Management', 'Approver', 7),
(54, '000622', 'Latifah', 'qc_calib@ssi.sharp-world.com', '000622', 'QC', 'Originator', 2),
(55, '000000', 'Common User', 'ikhsandio@ssi.sharp-world.com', '000000', 'Common User', '4', 3),
(56, 'admin', 'admin DC', 'nurdin@ssi.sharp-world.com', 'admin', 'QA Section', 'Admin', 3),
(58, '000065', 'Raja Lambok', 'raja@ssi.sharp-world.com', '000065', 'FCS Group', 'Approver', 5),
(59, 'gzbs026522', 'Miki Tohru', 'ikhsandio@ssi.sharp-world.com', 'gzbs026522', 'Production Sales Control', '6', 3),
(60, '002231', 'Risharwanto', 'ris@ssi.sharp-world.com', '002231', 'Purchasing and MC Section', 'Approver', 7),
(62, 'gzbs118202', 'Yukiro Kita', 'kita@ssi.sharp-world.com', 'gzbs118202', 'QA Section', 'Approver', 3),
(63, '002223', 'Risya Oktaviara', 'risya@ssi.sharp-world.com', '002223', 'Business Management', 'Originator', 6),
(64, '003122', 'Ucu Nurjanah', 'project3@ssi.sharp-world.com', '222013', 'QA Section', 'Admin', 8),
(65, '001752', 'Nur sarihah', 'nur_s@ssi.sharp-world.com', '001752', 'Production Section', 'Originator', 7),
(66, '000434', 'Zinatur Rahmah', 'zinatur@ssi.sharp-world.com', '000434', 'Engineering Section', 'Originator', 4),
(67, '000967', 'Joko Harjono', 'joko_h@ssi.sharp-world.com', '000967', 'Research and Development', 'Approver', 6),
(68, '000077', 'Endang Supriyadi', 'endangs@ssi.sharp-world.com', '000077', 'Engineering Section', 'Approver', 4),
(69, 'Rini', 'Sunarini', 'ikhsandio@ssi.sharp-world.com', '000366', 'Accounting Section', '4', 3),
(71, '000386', 'Sutarmo', 'sutarmo@ssi.sharp-world.com', '000386', 'FCS Group', 'Originator', 7),
(72, '002138', 'Suhanto', 'suhanto@ssi.shrap-world.com', '100791', 'Purchasing and MC Section', 'Approver', 7),
(73, '001240', 'Hendi', 'hendi@ssi.sharp-world.com', '001240', 'Purchasing and MC Section', 'Approver', 7),
(74, '002190', 'Hamdana', 'hamdana@ssi.sharp-world.com', '002190', 'Purchasing and MC Section', 'Originator', 6),
(76, '003425', 'Fadlun Haryanto', 'fadlun@ssi.sharp-world.com', '003425', 'Engineering Section', 'Originator', 3),
(77, '000015', 'Ida Astika', 'ida_ga@ssi.sharp-world.com', '000015', 'GA and Personnel Section', 'Originator', 7),
(80, '001068', 'Palapa JW', 'qc_inc@ssi.sharp-world.com', '001068', 'QC', 'Originator', 7),
(81, '000583', 'Rustam Indrajat', 'rustam@ssi.sharp-world.com', '000583', 'Engineering Section', 'Approver', 6),
(82, '001891', 'M. Ahdiat', 'ahdiat@ssi.sharp-world.com', '001891', 'Purchasing and MC Section', 'Originator', 8),
(84, '001984', 'Ono', 'ono@ssi.sharp-world.com', '001984', 'Research and Development', 'Approver', 5),
(85, '002113', 'Aprilliani Nurqolbi', 'chip_lsr@ssi.sharp-world.com', '002113', 'Production Section', 'Originator', 3),
(86, '001966', 'Jejen Jaelani', 'jejen@ssi.sharp-world.com', '001966', 'Purchasing and MC Section', 'Originator', 6),
(87, '002221', 'Andri Dwi Laksono', 'andri_dl@ssi.sharp-world.com', '002221', 'Engineering Section', 'Approver', 5),
(88, '002185', 'Indhi Primanuar K', 'indhi@ssi.sharp-world.com', '002185', 'Engineering Section', 'Originator', 3),
(89, '003501', 'Wulan Suci Romadhona', 'wulan@ssi.sharp-world.com', '003501', 'Engineering Section', 'resign', 7),
(90, '000566', 'M. Hilaluddin', 'hilal@ssi-sharp-world.com', '000566', 'Product Innovation', 'Approver', 5),
(91, '000460', 'Wardi', 'wardi@ssi.sharp-world.com', '000460', 'Production Section', 'Originator', 7),
(94, '000428', 'M. Nurudin', 'mnurudin@ssi.sharp-world.com', '000428', 'Product Warehouse Control', 'Approver', 5),
(95, 'gzbs036479', 'Motoshi Omori', 'ikhsandio@ssi.sharp-world.com', 'gzbs036479', 'President Director', '5', 3),
(97, '000686', 'Wahyudin', 'wahyu@ssi.sharp-world.com', '000686', 'Production Section', 'Approver', 6),
(98, 'gzbs103181', 'Takuya Takatahara', 'takatahara@ssi.sharp-world.com', 'gzbs103181', 'Accounting Section', 'Approver', 3),
(99, '000763', 'Lilis Suharyati', 'lilis@ssi.sharp-world.com', '000763', 'Production Section', 'resign', 7),
(101, '002125', 'Diah Otista Ningrum', 'diah@ssi.sharp-world.com', '002125', 'Production Sales Control Section', 'Originator', 4),
(102, '002247', 'Ratna Lidya', 'ratna@ssi.sharp-world.com', '002247', 'Research and Development', 'Originator', 7),
(103, '002240', 'Azhari Karim', 'azhari@ssi.sharp-world.com', '002240', 'QC', 'Originator', 3),
(104, '003798', 'Bayu Pradityo', 'bayu_p@ssi.sharp-world.com', '003798', 'Engineering Section', 'Originator', 3),
(105, '003799', 'Iqbal Nurasyied P', 'iqbal_n@ssi.sharp-world.com', '003799', 'Engineering Section', 'Originator', 2),
(106, '000452', 'Suranto', 'suranto@ssi.sharp-world.com', '000452', 'Production Section', 'Approver', 5),
(107, '003817', 'Astya Septarini', 'astya@ssi.sharp-world.com', '003817', 'Engineering Section', 'Originator', 2),
(108, '000116', 'Ijang Heri', 'ijang@ssi.sharp-world.com', '000116', 'Production Section', 'Approver', 5),
(109, '003721', 'ikhsandio', 'ikhsandio@ssi.sharp-world.com', '123456', 'Management Information System', 'Admin', 3),
(110, '001855', 'Puryanti', 'puryanti@ssi.sharp.world.com', '001855', 'Production Section', 'PIC', 8),
(112, 'gzbs126063', ' Nori Takenaka', 'takenaka@ssi.sharp-world.com', 'gzbs126063', 'Purchasing and MC Section', 'Approver', 3),
(113, '003827', 'Syamsul Ma’arif', 'syamsul@ssi.sharp-world.com', '003827', 'Engineering Section', 'Originator', 8),
(114, '000516', 'Gatot Wibowo', 'bowo@ssi.sharp-world.com', '000516', 'Production Section', 'Originator', 7),
(115, '002062', 'Fahmi', '-', '002062', '-', 'Resign', 9),
(116, '003367', 'Fredy Utomo', 'fredy@ssi.sharp-world.com', '003367', 'Purchasing and MC Section', 'Originator', 0),
(117, '000814', 'Yudhie S.', 'yudhie@ssi.sharp-world.com', '000814', 'Production Section', 'Originator', 5),
(118, '002425', 'Dhendy Indra Kusuma', 'dhendy@ssi.sharp-world.com', '002425', 'Engineering Section', 'Approver', 3),
(119, '000628', 'Riza F.', 'riza@ssi.sharp-world.com', '000628', 'Production Section', 'Approver', 4),
(120, '003917', 'Indah Raraswati', 'raras@ssi.sharp-world.com', '003917', 'Engineering Section', 'Originator', 4),
(121, '002798', 'Cicih', 'qa01@ssi.sharp-world.com', '002798', 'QA Section', 'Admin', 8),
(122, 'husni', 'husni', 'husni@ssi.sharp-world.com', '003961', 'Management Information System', 'originator', 2),
(123, '100892', 'Fukuta Kazuhiko', 'fukuta@ssi.sharp-world.com', '100892', 'QA Section', 'Approver', 3),
(124, '003639', 'Dewi Intan', 'dewi@ssi.sharp-world.com', '003639', 'QC', 'Originator', 6),
(125, '002236', 'Vitria', 'indhi@ssi.sharp-world.com', '002236', 'Engineering Section', 'Originator', 3),
(126, '002150', 'Ade', 'husni@ssi.sharp-world.com', '002150', 'Management Information System', 'originator', 2),
(127, '001072', 'project2', 'project2@ssi.sharp-world.com', '1072', 'inactive', 'resign', 0),
(128, '001169', 'qrcc', 'qrcc@ssi.sharp-world.com', '1169', 'inactive', 'resign', 0),
(129, '002237', 'yelly', 'yelly@ssi.sharp-world.com', '2237', 'inactive', 'resign', 0),
(130, '003486', 'project2', 'project2@ssi.sharp-world.com', '3486', 'inactive', 'resign', 0),
(131, '000423', 'ani', 'ani@ssi.sharp-world.com', '423', 'inactive', 'resign', 0),
(132, '002022', 'agus_sb', 'agus_sb@ssi.sharp-world.com', '2022', 'inactive', 'resign', 0),
(133, '000696', 'hrdc_it', 'hrdc_it@ssi.sharp-world.com', '696', 'inactive', 'resign', 0),
(134, '001982', 'pipit', 'pipit@ssi.sharp-world.com', '1982', 'inactive', 'resign', 0),
(135, '000024', 'tati', 'tati@ssi.sharp-world.com', '24', 'inactive', 'resign', 0),
(136, '002175', 'arief', 'arief@ssi.sharp-world.com', '2175', 'inactive', 'resign', 0),
(137, 'mis03', 'aris', 'aris@ssi.sharp-world.com', 'mis03', 'inactive', 'resign', 0),
(138, '002423', 'anisa', 'anisa@ssi.sharp-world.com', '2423', 'inactive', 'resign', 0),
(139, '002197', 'erlis', 'erlis@ssi.sharp-world.com', '2197', 'inactive', 'resign', 0),
(140, '002200', 'hrd_ssi', 'hrd_ssi@ssi.sharp-world.com', '2200', 'inactive', 'resign', 0),
(141, '00mis03', 'aris', 'aris@ssi.sharp-world.com', '00mis03', 'inactive', 'resign', 0),
(142, '003363', 'ani_m', 'ani_m@ssi.sharp-world.com', '3363', 'inactive', 'resign', 0),
(143, '002224', 'fitrady', 'fitrady@ssi.sharp-world.com', '2224', 'inactive', 'resign', 0),
(144, '001309', 'dian', 'dian@ssi.sharp-world.com', '1309', 'inactive', 'resign', 0),
(145, '003500', 'rahmawati', 'rahmawati@ssi.sharp-world.com', '3500', 'inactive', 'resign', 0),
(146, '000035', 'temmy', 'temmy@ssi.sharp-world.com', '35', 'inactive', 'resign', 0),
(147, '001945', 'danang', 'danang@ssi.sharp-world.com', '1945', 'inactive', 'resign', 0),
(148, '000037', 'dian_a', 'dian_a@ssi.sharp-world.com', '37', 'inactive', 'resign', 0),
(149, '001062', 'robi_p', 'robi_p@ssi.sharp-world.com', '1062', 'inactive', 'resign', 0),
(150, '002183', 'asri', 'asri@ssi.sharp-world.com', '2183', 'inactive', 'resign', 0),
(151, '002260', 'Dona Alatiful Chobir', 'mc_rnd@ssi.sharp-world.com', '002260', 'Engineering Section', 'Originator', 8),
(152, '003808', 'Dwi Aprilia', 'dwi_aprilia@ssi.sharp-world.com', '003808', 'Accounting Section', 'Originator', 4),
(153, '000366', 'Sunarini', 'rini@ssi.sharp-world.com', '000366', 'Accounting Section', 'Originator', 4),
(154, '003763', 'Adi Andriawan', 'adi@ssi.sharp-world.com', '003763', 'Accounting Section', 'Originator', 4),
(155, '003918', 'Dwi W', 'psn02@ssi.sharp-world.com', '003918', 'GA and Personnel Section', 'Originator', 2),
(156, '000384', 'Hesty F.', 'hesty@ssi.sharp-world.com', '000384', 'GA and Personnel Section', 'Originator', 2),
(157, '000445', 'Dahlan', 'dahlan@ssi.sharp-world.com', '000445', 'GA and Personnel Section', 'Originator', 2),
(158, '003903', 'Pangaribawa M.', 'pangaribawa@ssi.sharp-world.com', '003903', 'GA and Personnel Section', 'Originator', 2),
(159, '003366', 'Mokhammad Yusuf', 'yusuf@ssi.sharp-world.com', '300590aG', 'Management Information System', 'Approver', 3),
(160, '004418', 'Zaenal A.', 'zaenal_a@ssi.sharp-world.com', '004418', 'FCS Section', 'Originator', 4),
(161, '001955', 'Dony Subiyantoro', 'dony@ssi.sharp-world.com', '001955', 'Engineering Section', 'Originator', 3),
(162, '001773', 'Budi Basuki', 'budi_b@ssi.sharp-world.com', 'asdf123!', 'Production Section', 'Approver', 5),
(163, '002047', 'Puput Kurniawan', 'puput@ssi.sharp-world.com', 'asdf123!', 'QA Section', 'Approver', 5);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `device`
--
ALTER TABLE `device`
  ADD PRIMARY KEY (`id_device`);

--
-- Indexes for table `distribusi`
--
ALTER TABLE `distribusi`
  ADD PRIMARY KEY (`id_dis`);

--
-- Indexes for table `docu`
--
ALTER TABLE `docu`
  ADD PRIMARY KEY (`no_drf`);

--
-- Indexes for table `proc`
--
ALTER TABLE `proc`
  ADD PRIMARY KEY (`id_proc`);

--
-- Indexes for table `process`
--
ALTER TABLE `process`
  ADD PRIMARY KEY (`id_proc`);

--
-- Indexes for table `rel_doc`
--
ALTER TABLE `rel_doc`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `rev_doc`
--
ALTER TABLE `rev_doc`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `section`
--
ALTER TABLE `section`
  ADD PRIMARY KEY (`id_section`);

--
-- Indexes for table `user2`
--
ALTER TABLE `user2`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `section_id` (`section`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `device`
--
ALTER TABLE `device`
  MODIFY `id_device` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT for table `distribusi`
--
ALTER TABLE `distribusi`
  MODIFY `id_dis` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `docu`
--
ALTER TABLE `docu`
  MODIFY `no_drf` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `process`
--
ALTER TABLE `process`
  MODIFY `id_proc` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=294;

--
-- AUTO_INCREMENT for table `rel_doc`
--
ALTER TABLE `rel_doc`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rev_doc`
--
ALTER TABLE `rev_doc`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user2`
--
ALTER TABLE `user2`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=164;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
