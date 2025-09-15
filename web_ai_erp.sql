-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.0.30 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for web_ai_erp
CREATE DATABASE IF NOT EXISTS `web_ai_erp` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `web_ai_erp`;

-- Dumping structure for table web_ai_erp.master_brand
CREATE TABLE IF NOT EXISTS `master_brand` (
  `id_brand` int NOT NULL AUTO_INCREMENT,
  `brand_name` varchar(252) COLLATE utf8mb4_general_ci NOT NULL,
  `created_by` varchar(252) COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id_brand`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table web_ai_erp.master_brand: ~4 rows (approximately)
INSERT INTO `master_brand` (`id_brand`, `brand_name`, `created_by`, `created_at`) VALUES
	(1, 'BLACK STONE', 'Syafiq Wisnu AP', '2025-08-15 02:42:32'),
	(2, 'ROSSI', 'Syafiq Wisnu AP', '2025-08-15 02:42:38'),
	(3, 'ARIAT', 'Syafiq Wisnu AP', '2025-08-15 02:42:55'),
	(4, 'WAHANA', 'Syafiq Wisnu AP', '2025-08-19 03:34:27');

-- Dumping structure for table web_ai_erp.master_category
CREATE TABLE IF NOT EXISTS `master_category` (
  `id_category` int NOT NULL AUTO_INCREMENT,
  `category_name` varchar(252) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `created_by` varchar(252) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id_category`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;

-- Dumping data for table web_ai_erp.master_category: ~2 rows (approximately)
INSERT INTO `master_category` (`id_category`, `category_name`, `created_at`, `created_by`) VALUES
	(1, 'Material', '2025-08-14 06:33:22', 'Syafiq Wisnu AP'),
	(2, 'Barang Setengah Jadi', '2025-08-14 06:33:26', 'Syafiq Wisnu AP'),
	(3, 'Barang Jadi', '2025-08-14 06:33:29', 'Syafiq Wisnu AP');

-- Dumping structure for table web_ai_erp.master_dept
CREATE TABLE IF NOT EXISTS `master_dept` (
  `id_dept` int NOT NULL AUTO_INCREMENT,
  `kode_dept` varchar(252) COLLATE utf8mb4_general_ci NOT NULL,
  `dept_name` varchar(252) COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `created_by` varchar(252) COLLATE utf8mb4_general_ci NOT NULL,
  `delete_status` int NOT NULL DEFAULT '0',
  `delete_by` varchar(252) COLLATE utf8mb4_general_ci NOT NULL,
  `delete_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id_dept`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table web_ai_erp.master_dept: ~6 rows (approximately)
INSERT INTO `master_dept` (`id_dept`, `kode_dept`, `dept_name`, `created_at`, `created_by`, `delete_status`, `delete_by`, `delete_at`) VALUES
	(4, 'CTG', 'CUTTING', '2025-09-01 04:05:56', 's.wisnu1106@gmail.com', 0, '', NULL),
	(5, 'SWG', 'SEWING', '2025-09-01 04:06:07', 's.wisnu1106@gmail.com', 0, '', NULL),
	(6, 'SWH', 'SEMI WAREHOUSE', '2025-09-01 04:06:19', 's.wisnu1106@gmail.com', 0, '', NULL),
	(7, 'LTG', 'LASTING', '2025-09-01 04:06:35', 's.wisnu1106@gmail.com', 0, '', NULL),
	(8, 'FNG', 'FINISHING', '2025-09-01 04:06:49', 's.wisnu1106@gmail.com', 0, '', NULL),
	(9, 'PKG', 'PACKAGING', '2025-09-01 04:06:59', 's.wisnu1106@gmail.com', 0, '', NULL),
	(10, 'WHS', 'WAREHOUSE', '2025-09-01 04:07:06', 's.wisnu1106@gmail.com', 0, '', NULL);

-- Dumping structure for table web_ai_erp.master_item
CREATE TABLE IF NOT EXISTS `master_item` (
  `id_item` int NOT NULL AUTO_INCREMENT,
  `category_id` int DEFAULT NULL,
  `unit_id` int DEFAULT NULL,
  `brand_id` int DEFAULT NULL,
  `kode_item` varchar(252) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `item_name` varchar(252) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` varchar(252) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id_item`),
  KEY `category_id` (`category_id`),
  KEY `unit_id` (`unit_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1865 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table web_ai_erp.master_item: ~243 rows (approximately)
INSERT INTO `master_item` (`id_item`, `category_id`, `unit_id`, `brand_id`, `kode_item`, `item_name`, `created_at`, `created_by`) VALUES
	(1622, 3, 2, 1, 'FG-1-0001', 'Finish Goods Black Stone', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1623, 3, 2, 2, 'FG-2-0001', 'Finish Goods Rossi', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1624, 3, 2, 3, 'FG-3-0001', 'Finish Goods Ariat', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1625, 2, 2, 1, 'HFG-1-0001', 'Output Cutting Black Stone', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1626, 2, 2, 1, 'HFG-1-0002', 'Output Sewing Black Stone', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1627, 2, 2, 1, 'HFG-1-0003', 'Output Semi Black Stone', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1628, 2, 2, 1, 'HFG-1-0004', 'Output Lasting Black Stone', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1629, 2, 2, 1, 'HFG-1-0005', 'Output Finishing Black Stone', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1630, 2, 2, 1, 'HFG-1-0006', 'Output Packing Black Stone', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1631, 2, 2, 2, 'HFG-2-0001', 'Output Cutting Rossi', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1632, 2, 2, 2, 'HFG-2-0002', 'Output Sewing Rossi', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1633, 2, 2, 2, 'HFG-2-0003', 'Output Semi Rossi', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1634, 2, 2, 2, 'HFG-2-0004', 'Output Lasting Rossi', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1635, 2, 2, 2, 'HFG-2-0005', 'Output Finishing Rossi', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1636, 2, 2, 2, 'HFG-2-0006', 'Output Packing Rossi', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1637, 2, 2, 3, 'HFG-3-0001', 'Output Cutting Ariat', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1638, 2, 2, 3, 'HFG-3-0002', 'Output Sewing Ariat', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1639, 2, 2, 3, 'HFG-3-0003', 'Output Semi Ariat', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1640, 2, 2, 3, 'HFG-3-0004', 'Output Lasting Ariat', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1641, 2, 2, 3, 'HFG-3-0005', 'Output Finishing Ariat', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1642, 2, 2, 3, 'HFG-3-0006', 'Output Packing Ariat', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1643, 1, 1, 0, 'MT-0001', 'BLACK KIP (HARVEST GLORY)', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1644, 1, 11, 0, 'MT-0002', 'TEXON T-480 2.7 MM', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1645, 1, 5, 0, 'MT-0003', 'EVA 2 MM HD 35 - 40 MM', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1646, 1, 11, 0, 'MT-0004', 'TEXON G 565 (N587)', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1647, 1, 11, 0, 'MT-0005', 'TEXON RITE 1.5 MM', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1648, 1, 5, 0, 'MT-0006', 'POLYURETHANE SYNTHETIC LEATHER 0.8 - 1.0 MM (H-8080)', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1649, 1, 14, 0, 'MT-0007', 'SATIN LABEL AS/NZS 2210.5.2019 EN ISO 20347:2012 BMP 714442, 714443 OCCUPATIONAL BOOT', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1650, 1, 14, 0, 'MT-0008', 'SATIN SIZE + STYLE', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1651, 1, 14, 0, 'MT-0009', 'WEBBING TAPE ROSSI HERRINGBONE 21 MM', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1652, 1, 5, 0, 'MT-0010', 'ELASTIC 115MM', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1653, 1, 15, 0, 'MT-0011', 'TKT 20', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1654, 1, 15, 0, 'MT-0012', 'TKT 30', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1655, 1, 15, 0, 'MT-0013', 'TKT 40', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1656, 1, 9, 0, 'MT-0014', 'LEM 168 G/W', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1657, 1, 9, 0, 'MT-0015', 'LEM 7300 TF', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1658, 1, 2, 0, 'MT-0016', 'ENDURA CUPSOLE (SBR)', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1659, 1, 9, 0, 'MT-0017', 'BONDING AGENT 224 - 2', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1660, 1, 9, 0, 'MT-0018', 'LEM 5100 AB', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1661, 1, 9, 0, 'MT-0019', 'PRIMER D-PLY 008 F', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1662, 1, 9, 0, 'MT-0020', 'PRIMER D-PLY 1402', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1663, 1, 9, 0, 'MT-0021', 'PRIMER D-PLY 232', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1664, 1, 9, 0, 'MT-0022', 'D-TAC 7100', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1665, 1, 8, 0, 'MT-0023', 'UNIDUR 1001', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1666, 1, 9, 0, 'MT-0024', 'MEK', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1667, 1, 9, 0, 'MT-0025', 'HOTMELT ROD CEMENT', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1668, 1, 9, 0, 'MT-0026', 'GLUE STICK', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1669, 1, 13, 0, 'MT-0027', 'MASKING TAPE 1"', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1670, 1, 9, 0, 'MT-0028', 'PP BAG BENING/POLOS 0.03X40X45', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1671, 1, 2, 0, 'MT-0029', 'ANTISTATIC FOOTBED', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1672, 1, 14, 0, 'MT-0030', 'KERTAS DOORSLAG', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1673, 1, 6, 0, 'MT-0031', 'KERTAS DUPLEX 250 GR 21 X 29 CM', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1674, 1, 6, 0, 'MT-0032', 'KERTAS DUPLEX 500 GR 8 X 30 CM', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1675, 1, 14, 0, 'MT-0033', 'BARCODE STICKER ART. 301 (FROM TEKHAN)', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1676, 1, 16, 0, 'MT-0034', 'SWING TAG ENDURA SOLE ART. 301 (MAN) (AVERY DENNISON)', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1677, 1, 14, 0, 'MT-0035', 'CARE CARD (NEW FROM AVERY DENNISON)', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1678, 1, 14, 0, 'MT-0036', 'QUICK SNAP LOOP', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1679, 1, 14, 0, 'MT-0037', 'WRAPPING SHOE "ROSSI" PRINT 17 GR UK. 400 X 900', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1680, 1, 4, 0, 'MT-0038', 'MICROGARDE GREEN STICKER', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1681, 1, 14, 0, 'MT-0039', '6" INNER BOX (GOODBOX)', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1682, 1, 14, 0, 'MT-0040', 'ISI 5 UK.', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1683, 1, 17, 0, 'MT-0041', 'POLYPROPELINE (PP) TAPE 3"', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1684, 1, 17, 0, 'MT-0042', 'DOUBLE TAPE 6 MM', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1685, 1, 1, 0, 'MT-0043', 'MOSS BACK SUEDE (DONG-YI)', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1686, 1, 5, 0, 'MT-0044', 'POLYURETHANE SYNTHETIC LEATHER (NON WOVEN CLARINO) POT. 18 MM', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1687, 1, 6, 0, 'MT-0045', 'TEXON T-90 2.5MM BLACK + MIDSOLE EVA 5MM HD 68 - 70 (EVA CHEMICAL)', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1688, 1, 4, 0, 'MT-0046', 'EMBOSS QTR ROSSI BOOTS', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1689, 1, 14, 0, 'MT-0047', 'HEAT TRANSPAPER HOTMELT PRINT', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1690, 1, 4, 0, 'MT-0048', 'ROUND EYELET #350+WASHER', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1691, 1, 2, 0, 'MT-0049', 'RIPPLE OUTSOLE+LOGO RED (ROSSI)', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1692, 1, 15, 0, 'MT-0050', 'POLYESTER BARDED WAXY 1.2 MM (TRANCILO)', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1693, 1, 15, 0, 'MT-0051', '4 PLY COTTON', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1694, 1, 18, 0, 'MT-0052', 'STAPLES 406 J', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1695, 1, 9, 0, 'MT-0053', 'PRIMER D-PLY 007 F', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1696, 1, 16, 0, 'MT-0054', 'SWING TAG RIPPLE', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1697, 1, 16, 0, 'MT-0055', 'CARE CARD TAG', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1698, 1, 9, 0, 'MT-0056', 'LEM 5100  AU+AB', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1699, 1, 2, 0, 'MT-0057', 'ROUND POLYESTER 4 MM', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1700, 1, 14, 0, 'MT-0058', 'KERTAS DUPLEX 500 GR', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1701, 1, 14, 0, 'MT-0059', 'BARCODE LABEL FOR INNERBOX', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1702, 1, 1, 0, 'MT-0060', 'CLARET KIP (HARVEST GLORY)', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1703, 1, 6, 0, 'MT-0061', 'TEXON T-90 3 MM', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1704, 1, 4, 0, 'MT-0062', 'EMBOSS ROSSI BOOTS 4.5 X 2.5 CM', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1705, 1, 5, 0, 'MT-0063', 'SATIN TAPE 14 MM', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1706, 1, 4, 0, 'MT-0064', 'BUCKLE 2 CM', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1707, 1, 2, 0, 'MT-0065', 'GUM CREEPE OUTSOLE', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1708, 1, 6, 0, 'MT-0066', 'EVA 5 MM', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1709, 1, 15, 0, 'MT-0067', '4 PLY POLYESTER', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1710, 1, 16, 0, 'MT-0068', 'SWING TAG 666 SHEARER', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1711, 1, 2, 0, 'MT-0069', 'ROUND POLYESTER 3 MM', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1712, 1, 1, 0, 'MT-0070', 'WILD YAK (HARVEST GLORY)', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1713, 1, 1, 0, 'MT-0071', 'WILD YAK (AUSTAN)', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1714, 1, 6, 0, 'MT-0072', 'TEXON T-90 3 MM BLACK + MIDSOLE EVA 5MM HD 68 - 70 (EVA CHEMICAL)', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1715, 1, 2, 0, 'MT-0073', 'CLEATED HIKER OUTSOLE+LOGO (ROSSI)', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1716, 1, 15, 0, 'MT-0074', 'TRANCILO 1.2', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1717, 1, 16, 0, 'MT-0075', 'SWING TAG SIMPSON', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1718, 1, 1, 0, 'MT-0076', 'SUPER SUEDE', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1719, 1, 1, 0, 'MT-0077', 'PIG  SKIN', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1720, 1, 1, 0, 'MT-0078', 'PIG  SKIN  ( DIBALIK )', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1721, 1, 1, 0, 'MT-0079', 'SHEEP SKIN CURLY  NATURAL HENAN', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1722, 1, 10, 0, 'MT-0080', 'TEXTILE POLY TB CURLY 56/57" X 6MM X 550 GR / YDS CREAM', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1723, 1, 5, 0, 'MT-0081', 'INSOLE MATERIAL FELT BENECKE 1.5-1.7 MM X 54 BLACK', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1724, 1, 5, 0, 'MT-0082', 'EVA SPONGE 3MM HD 20 - 25 / 27 - 33 44" ( PT. YOOKWANG ) BLACK', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1725, 1, 5, 0, 'MT-0083', 'TEXTILE KAIN DRILL ART # 36201  58"+ STICKER UPPER TOE CUP', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1726, 1, 5, 0, 'MT-0084', 'TEXTILE KAIN DRILL ART # 36201  58"+ STICKER UPPER QUARTER', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1727, 1, 5, 0, 'MT-0085', 'TEXTILE KAIN DRILL ART # 36201  58"+ STICKER UPPER VAMP', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1728, 1, 5, 0, 'MT-0086', 'TEXTILE KAIN DRILL ART # 36201  58"+ STICKER VAMP REINFORCE', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1729, 1, 5, 0, 'MT-0087', 'FOAM DENSITY 18 THICK 4MM', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1730, 1, 5, 0, 'MT-0088', 'INSOLE MATERIAL SUPER TUFF 115 SL GREY', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1731, 1, 5, 0, 'MT-0089', 'EVA 2 MM, 44"(HARDNESS= 35-40 )', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1732, 1, 6, 0, 'MT-0090', 'INSOLE MATERIAL TEXON T - 480 A , 2 MM', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1733, 1, 11, 0, 'MT-0091', 'COUNTER MATERIAL IMPERFLEX V 25 P, 0,65-0,75 MM', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1734, 1, 11, 0, 'MT-0092', 'COUNTER MATERIAL TEXON RITE 1.5 MM', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1735, 1, 6, 0, 'MT-0093', 'SHANK BOARD PIAZA 1.5 MM', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1736, 1, 2, 0, 'MT-0094', 'SCREEN+EMBOSSED BLACKSTONE', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1737, 1, 2, 0, 'MT-0095', 'GENZA PRINT SIZE + ART  CG183+COLOR', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1738, 1, 5, 0, 'MT-0096', 'KAIN SATIN POTONG 14 MM BLACK', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1739, 1, 5, 0, 'MT-0097', 'NON WOVEN TC APP 2.2 SBY POTONG LURUS 15 MM', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1740, 1, 12, 0, 'MT-0098', 'NYLON THREADTKT 40 AQUIL 210 D3 ( 3 PLY ) UPPER', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1741, 1, 12, 0, 'MT-0099', 'NYLON THREADTKT 40 AQUIL 210 D3 ( 3 PLY ) BOTTOM UPPER', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1742, 1, 12, 0, 'MT-0100', 'NYLON THREADTKT 60 AQUIL 210 D2 ( 2 PLY ) LINING', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1743, 1, 12, 0, 'MT-0101', 'NYLON THREADTKT 60 AQUIL 210 D2 ( 2 PLY ) INSOLE', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1744, 1, 12, 0, 'MT-0102', 'NYLON THREADTKT 40 AQUIL 210 D3 ( 3 PLY ) ZIG ZAG', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1745, 1, 5, 0, 'MT-0103', 'THREAD TRANCILLO 1.2 MM', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1746, 1, 2, 0, 'MT-0104', 'OUTSOLE RUBBER CUP SOLE AW24 MENS', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1747, 1, 9, 0, 'MT-0105', 'BONDING AGENT 5100 AU', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1748, 1, 9, 0, 'MT-0106', 'LOCTITE BONDACE 7100-2', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1749, 1, 9, 0, 'MT-0107', 'LOCTITE BONDACE 224-2', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1750, 1, 9, 0, 'MT-0108', 'GLUE STICK WHITE', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1751, 1, 9, 0, 'MT-0109', 'BIO 700 G', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1752, 1, 2, 0, 'MT-0110', 'SHOE LACE ROUND COTTON WAXY  3  MM', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1753, 1, 4, 0, 'MT-0111', 'LEATHER U/P ; LEATHER + TEXTILE L/N ; RUBBER O/S', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1754, 1, 4, 0, 'MT-0112', 'PRINT BLACKSTONE', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1755, 1, 4, 0, 'MT-0113', 'BLACKSTONE  4"', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1756, 1, 4, 0, 'MT-0114', 'BLACKSTONE  4"  ISI  8', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1757, 1, 1, 0, 'MT-0115', 'SUPER SUDE', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1758, 1, 4, 0, 'MT-0116', 'STICKER STYLE ,SIZE ,COLOR  FOR INNER BOX', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1759, 1, 4, 0, 'MT-0117', 'CARTON BOX (ALL SIZE)', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1760, 1, 6, 0, 'MT-0118', 'KERTAS DUPLEX 310 GRAM', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1761, 1, 3, 0, 'MT-0119', 'LATEX CAIR 60% @ 180 KG', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1762, 1, 1, 0, 'MT-0120', 'SPLITE SUEDE UPPER 2', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1763, 1, 1, 0, 'MT-0121', 'SPLITE SUEDE UPPER 3', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1764, 1, 1, 0, 'MT-0122', 'SPLITE SUEDE TONGUE LABEL', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1765, 1, 1, 0, 'MT-0123', 'SPLITE SUEDE  UPPER', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1766, 1, 10, 0, 'MT-0124', 'RUBBER WRAP CH-A-86007  1.4 MM (ALEX BAUL TAIWAN)', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1767, 1, 1, 0, 'MT-0125', 'PIG SKIN WATER TONGUE', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1768, 1, 1, 0, 'MT-0126', 'PIG  SKIN   LINING TOP', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1769, 1, 1, 0, 'MT-0127', 'PIG  SKIN   LINING INSOLE', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1770, 1, 11, 0, 'MT-0128', 'SPIGATINO+2MMFOAM+AQUAT', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1771, 1, 5, 0, 'MT-0129', 'WATERPROOF TAPE T 6000 PTR WHITE', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1772, 1, 6, 0, 'MT-0130', 'FOAM DENSITY 30 THICK 10 MM', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1773, 1, 6, 0, 'MT-0131', 'FOAM DENSITY 30 THICK 7 MM', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1774, 1, 6, 0, 'MT-0132', 'FOAM DENSITY 30 THICK 15 MM', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1775, 1, 2, 0, 'MT-0133', 'SCREEN + EMBOSS WATERPROOF', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1776, 1, 2, 0, 'MT-0134', 'GENZA PRINT SIZE + ART CG014 +COLOR', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1777, 1, 5, 0, 'MT-0135', 'WEBBING TAPE 10 MM BOMOA', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1778, 1, 19, 0, 'MT-0136', 'PVC 3 HOLES E-0317  (NYLON SPEED LACE  E-0317 )', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1779, 1, 12, 0, 'MT-0137', 'NYLON THREADTKT 40 AQUIL 210 D3 ( 3 PLY ) UPPER 2', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1780, 1, 12, 0, 'MT-0138', 'NYLON THREADTKT 40 AQUIL 210 D3 ( 3 PLY ) UPPER 1', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1781, 1, 12, 0, 'MT-0139', 'NYLON THREADTKT 40 AQUIL 210 D3 ( 3 PLY ) UPPER 3', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1782, 1, 12, 0, 'MT-0140', 'NYLON THREAD BONDED TKT 60 ( 2 PLY ) U-9715', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1783, 1, 12, 0, 'MT-0141', 'NYLON THREADTKT 40 AQUIL 210 D3 ( 3 PLY ) WATER TONGUE', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1784, 1, 2, 0, 'MT-0142', 'RUBBER CUPSOLE VIBRAM ART CHOPPER', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1785, 1, 9, 0, 'MT-0143', 'PRIMER P 5 - 2', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1786, 1, 1, 0, 'MT-0144', 'BLAZEWAY', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1787, 1, 9, 0, 'MT-0145', 'D PLY PC-3 FOR PHYLON CLEANER', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1788, 1, 9, 0, 'MT-0146', 'BONDING AGENT 5100 AB', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1789, 1, 1, 0, 'MT-0147', 'SHEEP SKIN CURLY NATURAL HENAN (Q,T)', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1790, 1, 2, 0, 'MT-0148', 'LACE ROUND POLYESTER 4 MM WITH DOTS 2X', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1791, 1, 2, 0, 'MT-0149', 'EVA CUP INSOLE B 95 + PIG SKIN + EMBOSS BLACKSTONE', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1792, 1, 1, 0, 'MT-0150', 'SHEEP SKIN CURLY  NATURAL HENAN (INSOLE)', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1793, 1, 10, 0, 'MT-0151', 'COTTON CBA', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1794, 1, 5, 0, 'MT-0152', 'TEXTILE KAIN BLACU T/L  F 7668 + STICKER', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1795, 1, 5, 0, 'MT-0153', 'TEXTILE KAIN DRILL ART # 36201 + VAMP JOINT QUARTER (KOYO)', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1796, 1, 11, 0, 'MT-0154', 'COUNTER MATERIAL REFORM 300 NATURAL', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1797, 1, 2, 0, 'MT-0155', 'SCREEN+EMBOSS BLACKSTONE PALU', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1798, 1, 4, 0, 'MT-0156', 'GENSA PRINT  SIZE + ART  CG030 + COLOR', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1799, 1, 4, 0, 'MT-0157', 'GENZA SHEEP SKIN', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1800, 1, 5, 0, 'MT-0158', 'KAIN SATIN POTONG 14 MM BLACK', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1801, 1, 7, 0, 'MT-0159', 'EYELET ROUND # 300 GUN METAL', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1802, 1, 12, 0, 'MT-0160', 'NYLON THREADTKT 60 AQUIL 210 D2 ( 2 PLY ) ZIG ZAG,QTR', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1803, 1, 12, 0, 'MT-0161', 'NYLON THREAD TKT 40 AQUIL 210 D3 ( 3 PLY ) LINING', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1804, 1, 12, 0, 'MT-0162', 'NYLON THREAD TKT 60 AQUIL 210 D2 ( 2 PLY ) BOTTOM LINING', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1805, 1, 12, 0, 'MT-0163', 'NYLON THREAD TKT 60 AQUIL 210 D2 ( 2 PLY ) ZIG ZAG,INSOLE', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1806, 1, 2, 0, 'MT-0164', 'OUTSOLE RUBBER SUOLA TR 072 ,  NERO OCS TIPPER ROSSO SZ 40-47', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1807, 1, 2, 0, 'MT-0165', 'STEEL SHANK MAN ART. 10638  M, L, XL', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1808, 1, 11, 0, 'MT-0166', 'COUNTER MATERIAL IMPERFLEX V 45 P, 0,85-0,95 MM', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1809, 1, 9, 0, 'MT-0167', 'KP 100', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1810, 1, 9, 0, 'MT-0168', 'SILICON', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1811, 1, 4, 0, 'MT-0169', 'WAX CHRYSTAL', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1812, 1, 9, 0, 'MT-0170', 'SHOE CREAM AP-18-115', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1813, 1, 2, 0, 'MT-0171', 'LACE ROUND WAXY COTTON 4 MM', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1814, 1, 2, 0, 'MT-0172', 'AL. PI. S.R.L', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1815, 1, 2, 0, 'MT-0173', 'GENUINE LEATHER FUR + TRASPAPER SHEEP SKIN BLACKSTONE + LOGO B MEDALLION', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1816, 1, 4, 0, 'MT-0174', 'BLACKSTONE  6"', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1817, 1, 1, 0, 'MT-0175', 'SPLITE SUEDE UPPER ( LIST)', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1818, 1, 1, 0, 'MT-0176', 'SPLITE SUEDE UPPER (JOINT TOECAP)', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1819, 1, 4, 0, 'MT-0177', 'CARTON BOX BLACKSTONE 6 ISI 8', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1820, 1, 4, 0, 'MT-0178', 'CARTON BOX BLACKSTONE 6 ISI 12', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1821, 1, 4, 0, 'MT-0179', 'LEATHER U/P ; LEATHER L/N ; RUBBER O/S', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1822, 1, 5, 0, 'MT-0180', 'TEXTILE KAIN BLACU T/L F 7668 44" NATURAL', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1823, 1, 5, 0, 'MT-0181', 'TEXTILE KAIN DRILL ART # 36201  60", NATURAL', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1824, 1, 2, 0, 'MT-0182', 'EMBOSS BLACKSTONE', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1825, 1, 14, 0, 'MT-0183', 'GENSA PRINT  SIZE+ART  CG035 + COLOR', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1826, 1, 5, 0, 'MT-0184', 'SPOND BOND POTONG LURUS 14 MM BLACK', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1827, 1, 14, 0, 'MT-0185', 'ZIPPER RGTH C56-DA8W PE14BTM O', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1828, 1, 12, 0, 'MT-0186', 'NYLON THREAD TKT 40 AQUIL 210 D3 ( 3 PLY ) (BOTTOM UPPER, LINING)', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1829, 1, 12, 0, 'MT-0187', 'NYLON THREAD TKT 60 AQUIL 210 D2 ( 2 PLY ) ZIG ZAG INSOLE, ZIG ZAG LINING', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1830, 1, 4, 0, 'MT-0188', 'STAHL WAX PW-18-814', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1831, 1, 9, 0, 'MT-0189', 'AP 19119', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1832, 1, 1, 0, 'MT-0190', 'MAINSAIL NUBUCK', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1833, 1, 1, 0, 'MT-0191', 'PIG  SKIN  (Q,T)', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1834, 1, 1, 0, 'MT-0192', 'PIG SKIN (DIBALIK) (BS)', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1835, 1, 1, 0, 'MT-0193', 'PIG SKIN (INSOLE)', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1836, 1, 5, 0, 'MT-0194', 'CANVAS 6 OZ # 1724832', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1837, 1, 5, 0, 'MT-0195', 'TEXTILE KAIN BLACU T/L F 7668 44" NATURAL + STICKER (VAMP)', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1838, 1, 5, 0, 'MT-0196', 'TEXTILE KAIN BLACU T/L F 7668 44" NATURAL + STICKER (CLR)', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1839, 1, 5, 0, 'MT-0197', 'TEXTILE KAIN BLACU T/L F 7668 44" NATURAL + STICKER ( Q )', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1840, 1, 2, 0, 'MT-0198', 'SCREEN+EMBOSSED BLACKSTONE (TONGUE)', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1841, 1, 2, 0, 'MT-0199', 'SCREEN+EMBOSSED BLACKSTONE ( QUARTER )', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1842, 1, 2, 0, 'MT-0200', 'EMBOSS SIZE', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1843, 1, 2, 0, 'MT-0201', 'GENZA PRINT ART  CG179 + COLOR', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1844, 1, 5, 0, 'MT-0202', 'SPOND BOND POTONG LURUS 14 MM', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1845, 1, 7, 0, 'MT-0203', 'EYELET  ROUND # 300  + WASHER', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1846, 1, 12, 0, 'MT-0204', 'NYLON THREADTKT 40 AQUIL 210 D3 ( 3 PLY ) (ZIG ZAG NON WOVEN)', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1847, 1, 2, 0, 'MT-0205', 'OUTSOLE CUP BLACKSTONE AW24 MENS (39 - 50)', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1848, 1, 2, 0, 'MT-0206', 'SHOE LACE COTTON WAXY FLAT 8 MM', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1849, 1, 4, 0, 'MT-0207', 'CARTON BOX BLACKSTONE  4"  ISI  8', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1850, 1, 4, 0, 'MT-0208', 'CARTON BOX  BLACKSTONE  4"  ISI  12', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1851, 1, 9, 0, 'MT-0209', 'ATTACH (LASTING)  LEM D TAC- 7100-2', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1852, 1, 9, 0, 'MT-0210', 'ATTACH  TUCKBOARD+KAIN KASSA  LEM D TAC- 7100-2', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1853, 1, 9, 0, 'MT-0211', 'ATTACH (LASTING)  LEM D TAC- 7300 TF', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1854, 1, 1, 0, 'MT-0212', 'LUDLOW', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1855, 1, 1, 0, 'MT-0213', 'LINING ( Q,T )  SHEEP SKIN CURLY WOOL 7 MM', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1856, 1, 1, 0, 'MT-0214', 'LINING ( INSOLE) SHEEP SKIN CURLY WOOL 7 MM', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1857, 1, 1, 0, 'MT-0215', 'LINING ( V,Q) PIG SKIN', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1858, 1, 1, 0, 'MT-0216', 'LINING ( INSOLE ) PIG SKIN', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1859, 1, 1, 0, 'MT-0217', 'VVESOVIO SUEDE', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1860, 1, 1, 0, 'MT-0218', 'SPLITE SUEDE', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1861, 1, 1, 0, 'MT-0219', 'LINING ( E) PIG SKIN', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1862, 1, 1, 0, 'MT-0220', 'LINING ( BS ) PIG SKIN', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1863, 1, 2, 0, 'MT-0221', 'UPPER ARIAT', '2025-08-19 05:03:01', 'Syafiq Wisnu AP'),
	(1864, 1, 2, 0, 'MT-0222', 'SCREEN+EMBOSS BLACKSTONE (LOGO)', '2025-08-19 05:03:01', 'Syafiq Wisnu AP');

-- Dumping structure for table web_ai_erp.master_menu
CREATE TABLE IF NOT EXISTS `master_menu` (
  `id_menu` int NOT NULL AUTO_INCREMENT,
  `menu_name` varchar(252) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id_menu`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table web_ai_erp.master_menu: ~3 rows (approximately)
INSERT INTO `master_menu` (`id_menu`, `menu_name`) VALUES
	(1, 'Admin'),
	(2, 'User'),
	(3, 'Menu'),
	(6, 'Purchasing'),
	(7, 'Master Data'),
	(8, 'Warehouse'),
	(9, 'Production'),
	(10, 'Executive');

-- Dumping structure for table web_ai_erp.master_size
CREATE TABLE IF NOT EXISTS `master_size` (
  `id_size` int NOT NULL AUTO_INCREMENT,
  `id_brand` int NOT NULL DEFAULT '0',
  `size_name` varchar(252) COLLATE utf8mb4_general_ci NOT NULL,
  `created_by` varchar(252) COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id_size`),
  KEY `id_brand` (`id_brand`)
) ENGINE=InnoDB AUTO_INCREMENT=57 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table web_ai_erp.master_size: ~56 rows (approximately)
INSERT INTO `master_size` (`id_size`, `id_brand`, `size_name`, `created_by`, `created_at`) VALUES
	(1, 1, '36', 'Syafiq Wisnu AP', '2025-08-15 02:55:48'),
	(2, 2, '3', 'Syafiq Wisnu AP', '2025-08-15 02:58:36'),
	(3, 1, '37', 'Syafiq Wisnu AP', '2025-08-15 03:04:15'),
	(4, 1, '38', 'Syafiq Wisnu AP', '2025-08-15 03:04:21'),
	(5, 1, '39', 'Syafiq Wisnu AP', '2025-08-15 03:04:25'),
	(6, 1, '40', 'Syafiq Wisnu AP', '2025-08-15 03:04:28'),
	(7, 1, '41', 'Syafiq Wisnu AP', '2025-08-15 03:04:31'),
	(8, 1, '42', 'Syafiq Wisnu AP', '2025-08-15 03:04:35'),
	(9, 1, '43', 'Syafiq Wisnu AP', '2025-08-15 03:04:39'),
	(10, 1, '44', 'Syafiq Wisnu AP', '2025-08-15 03:04:48'),
	(11, 1, '45', 'Syafiq Wisnu AP', '2025-08-15 03:04:52'),
	(12, 1, '46', 'Syafiq Wisnu AP', '2025-08-15 03:04:55'),
	(13, 1, '47', 'Syafiq Wisnu AP', '2025-08-15 03:04:59'),
	(14, 1, '48', 'Syafiq Wisnu AP', '2025-08-15 03:05:05'),
	(15, 1, '49', 'Syafiq Wisnu AP', '2025-08-15 03:05:09'),
	(16, 1, '50', 'Syafiq Wisnu AP', '2025-08-15 03:05:12'),
	(17, 2, '3T', 'Syafiq Wisnu AP', '2025-08-15 03:05:33'),
	(18, 2, '4', 'Syafiq Wisnu AP', '2025-08-15 03:05:51'),
	(19, 2, '4T', 'Syafiq Wisnu AP', '2025-08-15 03:05:55'),
	(20, 2, '5', 'Syafiq Wisnu AP', '2025-08-15 03:05:59'),
	(21, 2, '5T', 'Syafiq Wisnu AP', '2025-08-15 03:06:03'),
	(22, 2, '6', 'Syafiq Wisnu AP', '2025-08-15 03:06:07'),
	(23, 2, '6T', 'Syafiq Wisnu AP', '2025-08-15 03:06:11'),
	(24, 2, '7', 'Syafiq Wisnu AP', '2025-08-15 03:06:15'),
	(25, 2, '7T', 'Syafiq Wisnu AP', '2025-08-15 03:06:21'),
	(26, 2, '8', 'Syafiq Wisnu AP', '2025-08-15 03:06:24'),
	(27, 2, '8T', 'Syafiq Wisnu AP', '2025-08-15 03:06:28'),
	(28, 2, '9', 'Syafiq Wisnu AP', '2025-08-15 03:06:31'),
	(29, 2, '9T', 'Syafiq Wisnu AP', '2025-08-15 03:06:40'),
	(30, 2, '10', 'Syafiq Wisnu AP', '2025-08-15 03:06:44'),
	(31, 2, '10T', 'Syafiq Wisnu AP', '2025-08-15 03:06:47'),
	(32, 2, '11', 'Syafiq Wisnu AP', '2025-08-15 03:06:55'),
	(33, 2, '11T', 'Syafiq Wisnu AP', '2025-08-15 03:06:59'),
	(34, 2, '12', 'Syafiq Wisnu AP', '2025-08-15 03:07:02'),
	(35, 2, '12T', 'Syafiq Wisnu AP', '2025-08-15 03:07:06'),
	(36, 2, '13', 'Syafiq Wisnu AP', '2025-08-15 03:07:09'),
	(37, 2, '13T', 'Syafiq Wisnu AP', '2025-08-15 03:07:13'),
	(38, 2, '14', 'Syafiq Wisnu AP', '2025-08-15 03:07:27'),
	(39, 2, '15', 'Syafiq Wisnu AP', '2025-08-15 03:07:30'),
	(40, 3, '6D', 'Syafiq Wisnu AP', '2025-08-15 03:07:51'),
	(41, 3, '6.5D', 'Syafiq Wisnu AP', '2025-08-15 03:08:00'),
	(42, 3, '7D', 'Syafiq Wisnu AP', '2025-08-15 03:08:07'),
	(43, 3, '7.5D', 'Syafiq Wisnu AP', '2025-08-15 03:08:12'),
	(44, 3, '8D', 'Syafiq Wisnu AP', '2025-08-15 03:08:16'),
	(45, 3, '8.5D', 'Syafiq Wisnu AP', '2025-08-15 03:08:23'),
	(46, 3, '9D', 'Syafiq Wisnu AP', '2025-08-15 03:08:26'),
	(47, 3, '9.5D', 'Syafiq Wisnu AP', '2025-08-15 03:08:31'),
	(48, 3, '10D', 'Syafiq Wisnu AP', '2025-08-15 03:08:41'),
	(49, 3, '10.5D', 'Syafiq Wisnu AP', '2025-08-15 03:08:49'),
	(50, 3, '11D', 'Syafiq Wisnu AP', '2025-08-15 03:08:53'),
	(51, 3, '11.5D', 'Syafiq Wisnu AP', '2025-08-15 03:09:00'),
	(52, 3, '12D', 'Syafiq Wisnu AP', '2025-08-15 03:09:04'),
	(53, 3, '13D', 'Syafiq Wisnu AP', '2025-08-15 03:09:10'),
	(54, 3, '14D', 'Syafiq Wisnu AP', '2025-08-15 03:09:15'),
	(55, 3, '15D', 'Syafiq Wisnu AP', '2025-08-15 03:09:19'),
	(56, 3, '16D', 'Syafiq Wisnu AP', '2025-08-15 03:09:24');

-- Dumping structure for table web_ai_erp.master_sub_menu
CREATE TABLE IF NOT EXISTS `master_sub_menu` (
  `id_submenu` int NOT NULL AUTO_INCREMENT,
  `id_menu` int DEFAULT NULL,
  `submenu_name` varchar(252) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `menu_url` varchar(252) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `menu_icon` varchar(252) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `menu_is_active` int DEFAULT NULL,
  PRIMARY KEY (`id_submenu`),
  KEY `id_menu` (`id_menu`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table web_ai_erp.master_sub_menu: ~19 rows (approximately)
INSERT INTO `master_sub_menu` (`id_submenu`, `id_menu`, `submenu_name`, `menu_url`, `menu_icon`, `menu_is_active`) VALUES
	(1, 1, 'Dashboard', 'admin', 'fas fa-fw fa-infinity', 1),
	(2, 2, 'My Profile', 'user', 'fas fa-fw fa-user', 1),
	(3, 2, 'Edit Profile', 'user/edit', 'fas fa-fw fa-user-edit', 1),
	(4, 3, 'Menu Management', 'menu', 'fas fa-fw fa-bars', 1),
	(5, 3, 'Sub Menu Management', 'menu/submenu', 'fas fa-fw fa-folder-minus', 1),
	(6, 6, 'Bill Of Material', 'purchasing', 'fas fa-fw fa-file-invoice-dollar', 1),
	(7, 6, 'Work Order', 'purchasing/work_order', 'fas fa-fw fa-network-wired', 1),
	(8, 6, 'Purchasing Order', 'purchasing/purchasing_order', 'fas fa-fw fa-folder-plus', 1),
	(9, 1, 'Role Management', 'admin/role', 'fas fa-fw fa-user-tie', 1),
	(10, 2, 'Change Password', 'user/changepassword', 'fas fa-fw fa-key', 1),
	(11, 1, 'User Management', 'admin/user_management', 'fas fa-fw fa-users', 1),
	(12, 7, 'Category', 'master', 'fas fa-fw fa-drumstick-bite', 1),
	(13, 7, 'Item', 'master/item', 'fas fa-fw fa-voicemail', 1),
	(14, 7, 'Unit', 'master/unit', 'fas fa-fw fa-fingerprint', 1),
	(15, 7, 'Supplier', 'master/supplier', 'fas fa-fw fa-truck', 1),
	(16, 7, 'Brand', 'master/brand', 'fas fa-fw fa-copyright', 1),
	(17, 8, 'Stock Warehouse', 'warehouse', 'fas fa-fw fa-warehouse', 1),
	(18, 8, 'Check IN', 'warehouse/checkin', 'fas fa-fw fa-check-double', 1),
	(19, 8, 'Check Out', 'warehouse/checkout', 'fas fa-fw fa-receipt', 1),
	(20, 9, 'Request Order', 'production', 'fas fa-fw fa-cart-arrow-down', 1),
	(21, 9, 'Production Output', 'Production/output', 'fas fa-fw fa-cart-plus', 1),
	(22, 7, 'Departement', 'master/dept', 'fas fa-fw fa-clipboard-list', 1),
	(23, 10, 'PPS', 'executive', 'fas fa-fw fa-clipboard', 1);

-- Dumping structure for table web_ai_erp.master_unit
CREATE TABLE IF NOT EXISTS `master_unit` (
  `id_unit` int NOT NULL AUTO_INCREMENT,
  `unit_name` varchar(252) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` varchar(252) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id_unit`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;

-- Dumping data for table web_ai_erp.master_unit: ~20 rows (approximately)
INSERT INTO `master_unit` (`id_unit`, `unit_name`, `created_at`, `created_by`) VALUES
	(1, 'FTK', '2025-08-14 06:29:57', 'Syafiq Wisnu AP'),
	(2, 'NPR', '2025-08-14 06:35:36', 'Syafiq Wisnu AP'),
	(3, 'LTR', '2025-08-14 06:40:50', 'Syafiq Wisnu AP'),
	(4, 'PCE', '2025-08-14 06:40:58', 'Syafiq Wisnu AP'),
	(5, 'MTR', '2025-08-14 06:41:06', 'Syafiq Wisnu AP'),
	(6, 'SHT', '2025-08-14 06:41:14', 'Syafiq Wisnu AP'),
	(7, 'SET', '2025-08-14 06:41:21', 'Syafiq Wisnu AP'),
	(8, 'TIN', '2025-08-14 06:41:29', 'Syafiq Wisnu AP'),
	(9, 'KGM', '2025-08-14 06:41:38', 'Syafiq Wisnu AP'),
	(10, 'YRD', '2025-08-14 06:41:47', 'Syafiq Wisnu AP'),
	(11, 'MTK', '2025-08-14 06:41:56', 'Syafiq Wisnu AP'),
	(12, 'CONE', '2025-08-14 06:42:24', 'Syafiq Wisnu AP'),
	(13, 'ROLL', '2025-08-14 06:42:31', 'Syafiq Wisnu AP'),
	(14, 'PCS', '2025-08-14 06:42:40', 'Syafiq Wisnu AP'),
	(15, 'CJ', '2025-08-14 06:42:51', 'Syafiq Wisnu AP'),
	(16, 'EA', '2025-08-14 06:43:00', 'Syafiq Wisnu AP'),
	(17, 'RO', '2025-08-14 06:43:09', 'Syafiq Wisnu AP'),
	(18, 'PAK', '2025-08-14 06:43:22', 'Syafiq Wisnu AP'),
	(19, 'PRS', '2025-08-14 06:43:31', 'Syafiq Wisnu AP'),
	(20, 'KG', '2025-08-14 06:43:38', 'Syafiq Wisnu AP');

-- Dumping structure for table web_ai_erp.master_user
CREATE TABLE IF NOT EXISTS `master_user` (
  `id_user` int NOT NULL AUTO_INCREMENT,
  `user_name` varchar(252) COLLATE utf8mb4_general_ci NOT NULL,
  `user_email` varchar(252) COLLATE utf8mb4_general_ci NOT NULL,
  `user_image` varchar(252) COLLATE utf8mb4_general_ci NOT NULL,
  `user_password` varchar(252) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `user_role_id` int NOT NULL DEFAULT '0',
  `user_is_active` int NOT NULL DEFAULT '0',
  `user_created_at` datetime NOT NULL,
  PRIMARY KEY (`id_user`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table web_ai_erp.master_user: ~3 rows (approximately)
INSERT INTO `master_user` (`id_user`, `user_name`, `user_email`, `user_image`, `user_password`, `user_role_id`, `user_is_active`, `user_created_at`) VALUES
	(4, 'USER', 'user@mail.com', 'Eun-bi.jpg', '$2y$10$Zq7pay1N4dbxcjqmj6Jy.uLwUCmfkjEuCo9/pV.ioGc6tcR6k64yy', 2, 1, '2025-08-11 04:46:31'),
	(13, 'Syafiq Wisnu AP', 's.wisnu1106@gmail.com', 'Eun-bi2.jpg', '$2y$10$W7JNoqRn8QuKJ04tubky/uENkn/hXLoZsP92v.08aiO3.Ur765yAa', 1, 1, '2025-08-13 08:06:56'),
	(14, 'wisnu', 'wisnu@gmail.com', 'default.jpg', '$2y$10$TDNV7mjaHk7eeiaT15tuAeKz5/ZfnE8yIL0AXAfS.2r7NyG5Yuuy.', 2, 1, '2025-08-13 08:32:06');

-- Dumping structure for table web_ai_erp.master_user_access_menu
CREATE TABLE IF NOT EXISTS `master_user_access_menu` (
  `id_access` int NOT NULL AUTO_INCREMENT,
  `id_role` int NOT NULL,
  `id_menu` int NOT NULL,
  PRIMARY KEY (`id_access`),
  KEY `id_role` (`id_role`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table web_ai_erp.master_user_access_menu: ~6 rows (approximately)
INSERT INTO `master_user_access_menu` (`id_access`, `id_role`, `id_menu`) VALUES
	(1, 1, 1),
	(2, 1, 2),
	(3, 2, 2),
	(4, 1, 3),
	(6, 1, 6),
	(7, 1, 7),
	(8, 1, 8),
	(9, 1, 9),
	(10, 1, 10);

-- Dumping structure for table web_ai_erp.master_user_role
CREATE TABLE IF NOT EXISTS `master_user_role` (
  `id_role` int NOT NULL AUTO_INCREMENT,
  `role_name` varchar(252) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id_role`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table web_ai_erp.master_user_role: ~2 rows (approximately)
INSERT INTO `master_user_role` (`id_role`, `role_name`) VALUES
	(1, 'Administrator'),
	(2, 'Member');

-- Dumping structure for table web_ai_erp.pr_output
CREATE TABLE IF NOT EXISTS `pr_output` (
  `id_output` int NOT NULL AUTO_INCREMENT,
  `id_ro` int NOT NULL,
  `id_wo` int NOT NULL,
  `wo_number` varchar(252) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `kode_ro` varchar(252) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `kode_item` varchar(252) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `brand_name` varchar(252) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `artcolor_name` varchar(252) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `size_name` varchar(252) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `size_qty` varchar(252) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `mis_category` varchar(252) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `mis_qty` varchar(252) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `created_by` varchar(252) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `updated_by` varchar(252) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `deleted_by` varchar(252) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id_output`)
) ENGINE=InnoDB AUTO_INCREMENT=262 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table web_ai_erp.pr_output: ~16 rows (approximately)
INSERT INTO `pr_output` (`id_output`, `id_ro`, `id_wo`, `wo_number`, `kode_ro`, `kode_item`, `brand_name`, `artcolor_name`, `size_name`, `size_qty`, `mis_category`, `mis_qty`, `created_at`, `updated_at`, `created_by`, `updated_by`, `deleted_at`, `deleted_by`) VALUES
	(231, 8, 762, 'po-002', 'CTG-WHS-007', 'HFG-1-0001', 'BLACK STONE', 'EG555 - Black', '36', '2', 'sudah lengkap', '0', '2025-09-11 04:51:25', '2025-09-11 04:58:20', 's.wisnu1106@gmail.com', 's.wisnu1106@gmail.com', NULL, NULL),
	(232, 8, 762, 'po-002', 'CTG-WHS-007', 'HFG-1-0001', 'BLACK STONE', 'EG555 - Black', '37', '2', 'sudah lengkap', '0', '2025-09-11 04:51:25', '2025-09-11 04:58:20', 's.wisnu1106@gmail.com', 's.wisnu1106@gmail.com', NULL, NULL),
	(233, 8, 762, 'po-002', 'CTG-WHS-007', 'HFG-1-0001', 'BLACK STONE', 'EG555 - Black', '38', '2', 'sudah lengkap', '0', '2025-09-11 04:51:25', '2025-09-11 04:58:20', 's.wisnu1106@gmail.com', 's.wisnu1106@gmail.com', NULL, NULL),
	(234, 8, 762, 'po-002', 'CTG-WHS-007', 'HFG-1-0001', 'BLACK STONE', 'EG555 - Black', '39', '2', 'sudah lengkap', '0', '2025-09-11 04:51:25', '2025-09-11 04:58:20', 's.wisnu1106@gmail.com', 's.wisnu1106@gmail.com', NULL, NULL),
	(235, 8, 762, 'po-002', 'CTG-WHS-007', 'HFG-1-0001', 'BLACK STONE', 'EG555 - Black', '40', '2', 'sudah lengkap', '0', '2025-09-11 04:51:25', '2025-09-11 04:58:20', 's.wisnu1106@gmail.com', 's.wisnu1106@gmail.com', NULL, NULL),
	(236, 3, 771, 'po-003', 'CTG-WHS-003', 'HFG-1-0001', 'BLACK STONE', 'EG555 - Black', '36', '2', 'sudah lengkap', '0', '2025-09-11 07:13:35', '2025-09-11 07:14:33', 's.wisnu1106@gmail.com', 's.wisnu1106@gmail.com', NULL, NULL),
	(237, 3, 771, 'po-003', 'CTG-WHS-003', 'HFG-1-0001', 'BLACK STONE', 'EG555 - Black', '37', '2', 'sudah lengkap', '0', '2025-09-11 07:13:35', '2025-09-11 07:14:33', 's.wisnu1106@gmail.com', 's.wisnu1106@gmail.com', NULL, NULL),
	(238, 3, 771, 'po-003', 'CTG-WHS-003', 'HFG-1-0001', 'BLACK STONE', 'EG555 - Black', '38', '2', 'sudah lengkap', '0', '2025-09-11 07:13:35', '2025-09-11 07:14:33', 's.wisnu1106@gmail.com', 's.wisnu1106@gmail.com', NULL, NULL),
	(239, 3, 771, 'po-003', 'CTG-WHS-003', 'HFG-1-0001', 'BLACK STONE', 'EG555 - Black', '39', '2', 'sudah lengkap', '0', '2025-09-11 07:13:35', '2025-09-11 07:14:33', 's.wisnu1106@gmail.com', 's.wisnu1106@gmail.com', NULL, NULL),
	(240, 3, 771, 'po-003', 'CTG-WHS-003', 'HFG-1-0001', 'BLACK STONE', 'EG555 - Black', '40', '2', 'sudah lengkap', '0', '2025-09-11 07:13:35', '2025-09-11 07:14:33', 's.wisnu1106@gmail.com', 's.wisnu1106@gmail.com', NULL, NULL),
	(241, 3, 771, 'po-003', 'CTG-WHS-003', 'HFG-1-0001', 'BLACK STONE', 'EG555 - Black', '41', '2', 'sudah lengkap', '0', '2025-09-11 07:13:35', '2025-09-11 07:14:33', 's.wisnu1106@gmail.com', 's.wisnu1106@gmail.com', NULL, NULL),
	(257, 8, 762, 'po-002', 'CTG-WHS-007', 'HFG-1-0005', 'BLACK STONE', 'EG555 - Black', '36', '1', 'belum produksi', '1', '2025-09-11 08:52:50', NULL, 's.wisnu1106@gmail.com', NULL, NULL, NULL),
	(258, 8, 762, 'po-002', 'CTG-WHS-007', 'HFG-1-0005', 'BLACK STONE', 'EG555 - Black', '37', '1', 'belum produksi', '1', '2025-09-11 08:52:50', NULL, 's.wisnu1106@gmail.com', NULL, NULL, NULL),
	(259, 8, 762, 'po-002', 'CTG-WHS-007', 'HFG-1-0005', 'BLACK STONE', 'EG555 - Black', '38', '1', 'belum produksi', '1', '2025-09-11 08:52:50', NULL, 's.wisnu1106@gmail.com', NULL, NULL, NULL),
	(260, 8, 762, 'po-002', 'CTG-WHS-007', 'HFG-1-0005', 'BLACK STONE', 'EG555 - Black', '39', '1', 'belum produksi', '1', '2025-09-11 08:52:50', NULL, 's.wisnu1106@gmail.com', NULL, NULL, NULL),
	(261, 8, 762, 'po-002', 'CTG-WHS-007', 'HFG-1-0005', 'BLACK STONE', 'EG555 - Black', '40', '1', 'belum produksi', '1', '2025-09-11 08:52:50', NULL, 's.wisnu1106@gmail.com', NULL, NULL, NULL);

-- Dumping structure for table web_ai_erp.pr_ro
CREATE TABLE IF NOT EXISTS `pr_ro` (
  `id_ro` int NOT NULL AUTO_INCREMENT,
  `id_wo` int NOT NULL DEFAULT '0',
  `wo_number` varchar(252) COLLATE utf8mb4_general_ci NOT NULL,
  `kode_ro` varchar(252) COLLATE utf8mb4_general_ci NOT NULL,
  `kode_item` varchar(252) COLLATE utf8mb4_general_ci NOT NULL,
  `brand_name` varchar(252) COLLATE utf8mb4_general_ci NOT NULL,
  `artcolor_name` varchar(252) COLLATE utf8mb4_general_ci NOT NULL,
  `item_name` varchar(252) COLLATE utf8mb4_general_ci NOT NULL,
  `category` varchar(252) COLLATE utf8mb4_general_ci NOT NULL,
  `unit` varchar(252) COLLATE utf8mb4_general_ci NOT NULL,
  `size_qty` varchar(252) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `ro_qty` varchar(252) COLLATE utf8mb4_general_ci NOT NULL,
  `from_dept` varchar(252) COLLATE utf8mb4_general_ci NOT NULL,
  `to_dept` varchar(252) COLLATE utf8mb4_general_ci NOT NULL,
  `status_ro` varchar(252) COLLATE utf8mb4_general_ci NOT NULL,
  `date_ro` datetime NOT NULL,
  `created_by` varchar(252) COLLATE utf8mb4_general_ci NOT NULL,
  `updated_by` varchar(252) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `delete_at` datetime DEFAULT NULL,
  `delete_by` varchar(252) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `delete_status` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_ro`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table web_ai_erp.pr_ro: ~8 rows (approximately)
INSERT INTO `pr_ro` (`id_ro`, `id_wo`, `wo_number`, `kode_ro`, `kode_item`, `brand_name`, `artcolor_name`, `item_name`, `category`, `unit`, `size_qty`, `ro_qty`, `from_dept`, `to_dept`, `status_ro`, `date_ro`, `created_by`, `updated_by`, `created_at`, `updated_at`, `delete_at`, `delete_by`, `delete_status`) VALUES
	(1, 780, 'po-004', 'CTG-WHS-001', 'MT-0001', 'BLACK STONE', 'EG555 - Black', 'BLACK KIP (HARVEST GLORY)', 'Material', 'FTK', '9', '13.5', 'CUTTING', 'WAREHOUSE', 'Produksi Sudah Lengkap', '2025-09-02 00:00:00', 's.wisnu1106@gmail.com', 's.wisnu1106@gmail.com', '2025-09-02 06:51:50', '2025-09-04 06:14:22', NULL, NULL, 0),
	(2, 789, 'po-005', 'CTG-WHS-002', 'MT-0001', 'ROSSI', 'dsdasd - sadasd', 'BLACK KIP (HARVEST GLORY)', 'Material', 'FTK', '6', '6', 'CUTTING', 'WAREHOUSE', 'menunggu dikirim', '2025-09-02 00:00:00', 's.wisnu1106@gmail.com', '', '2025-09-02 07:06:46', NULL, NULL, NULL, 0),
	(3, 771, 'po-003', 'CTG-WHS-003', 'MT-0001', 'BLACK STONE', 'EG555 - Black', 'BLACK KIP (HARVEST GLORY)', 'Material', 'FTK', '12', '18', 'CUTTING', 'WAREHOUSE', 'produksi sudah lengkap', '2025-09-02 00:00:00', 's.wisnu1106@gmail.com', 's.wisnu1106@gmail.com', '2025-09-02 08:35:09', '2025-09-11 07:14:33', NULL, NULL, 0),
	(4, 771, 'po-003', 'CTG-WHS-004', 'MT-0001', 'BLACK STONE', 'EG555 - Black', 'BLACK KIP (HARVEST GLORY)', 'Material', 'FTK', '12', '18', 'CUTTING', 'WAREHOUSE', 'menunggu dikirim', '2025-09-03 00:00:00', 's.wisnu1106@gmail.com', '', '2025-09-03 03:04:11', NULL, NULL, NULL, 0),
	(5, 771, 'po-003', 'CTG-WHS-004', 'MT-0049', 'BLACK STONE', 'EG555 - Black', 'RIPPLE OUTSOLE+LOGO RED (ROSSI)', 'Material', 'NPR', '12', '5.9988', 'CUTTING', 'WAREHOUSE', 'menunggu dikirim', '2025-09-03 00:00:00', 's.wisnu1106@gmail.com', '', '2025-09-03 03:04:11', NULL, NULL, NULL, 0),
	(6, 780, 'po-004', 'CTG-WHS-005', 'MT-0001', 'BLACK STONE', 'EG555 - Black', 'BLACK KIP (HARVEST GLORY)', 'Material', 'FTK', '11', '16.5', 'CUTTING', 'WAREHOUSE', 'produksi sudah lengkap', '2025-09-03 00:00:00', 's.wisnu1106@gmail.com', 's.wisnu1106@gmail.com', '2025-09-03 03:11:32', '2025-09-04 08:32:22', NULL, NULL, 0),
	(7, 780, 'po-004', 'CTG-WHS-006', 'MT-0001', 'BLACK STONE', 'EG555 - Black', 'BLACK KIP (HARVEST GLORY)', 'Material', 'FTK', '11', '16.5', 'CUTTING', 'WAREHOUSE', 'menunggu dikirim', '2025-09-03 00:00:00', 's.wisnu1106@gmail.com', '', '2025-09-03 03:13:28', NULL, NULL, NULL, 0),
	(8, 762, 'po-002', 'CTG-WHS-007', 'MT-0001', 'BLACK STONE', 'EG555 - Black', 'BLACK KIP (HARVEST GLORY)', 'Material', 'FTK', '10', '15', 'CUTTING', 'WAREHOUSE', 'produksi belum lengkap', '2025-09-09 00:00:00', 's.wisnu1106@gmail.com', 's.wisnu1106@gmail.com', '2025-09-09 08:18:25', '2025-09-11 08:52:50', NULL, NULL, 0);

-- Dumping structure for table web_ai_erp.purchasing_bom
CREATE TABLE IF NOT EXISTS `purchasing_bom` (
  `id_bom` int NOT NULL AUTO_INCREMENT,
  `id_fg_item` int NOT NULL DEFAULT '0',
  `id_hfg_item` int NOT NULL DEFAULT '0',
  `id_mt_item` int NOT NULL DEFAULT '0',
  `kode_bom` varchar(252) COLLATE utf8mb4_general_ci NOT NULL,
  `fg_kode_item` varchar(252) COLLATE utf8mb4_general_ci NOT NULL,
  `hfg_kode_item` varchar(252) COLLATE utf8mb4_general_ci NOT NULL,
  `mt_kode_item` varchar(252) COLLATE utf8mb4_general_ci NOT NULL,
  `fg_item_name` varchar(252) COLLATE utf8mb4_general_ci NOT NULL,
  `hfg_item_name` varchar(252) COLLATE utf8mb4_general_ci NOT NULL,
  `mt_item_name` varchar(252) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `fg_item_category` varchar(252) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `hfg_item_category` varchar(252) COLLATE utf8mb4_general_ci NOT NULL,
  `mt_item_category` varchar(252) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `fg_unit` varchar(252) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `hfg_unit` varchar(252) COLLATE utf8mb4_general_ci NOT NULL,
  `mt_unit` varchar(252) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `brand_name` varchar(252) COLLATE utf8mb4_general_ci NOT NULL,
  `artcolor_name` varchar(252) COLLATE utf8mb4_general_ci NOT NULL,
  `bom_qty` varchar(252) COLLATE utf8mb4_general_ci NOT NULL,
  `created_by` varchar(252) COLLATE utf8mb4_general_ci NOT NULL,
  `updated_by` varchar(252) COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id_bom`),
  KEY `id_fgitem` (`id_fg_item`),
  KEY `id_mt_item` (`id_mt_item`),
  KEY `id_hfg_item` (`id_hfg_item`)
) ENGINE=InnoDB AUTO_INCREMENT=105 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table web_ai_erp.purchasing_bom: ~13 rows (approximately)
INSERT INTO `purchasing_bom` (`id_bom`, `id_fg_item`, `id_hfg_item`, `id_mt_item`, `kode_bom`, `fg_kode_item`, `hfg_kode_item`, `mt_kode_item`, `fg_item_name`, `hfg_item_name`, `mt_item_name`, `fg_item_category`, `hfg_item_category`, `mt_item_category`, `fg_unit`, `hfg_unit`, `mt_unit`, `brand_name`, `artcolor_name`, `bom_qty`, `created_by`, `updated_by`, `created_at`) VALUES
	(89, 1622, 1625, 1643, 'BOM-20250821-001', 'FG-1-0001', 'HFG-1-0001', 'MT-0001', 'Finish Goods Black Stone', 'Output Cutting Black Stone', 'BLACK KIP (HARVEST GLORY)', 'Barang Jadi', 'Barang Setengah Jadi', 'Material', 'NPR', 'NPR', 'FTK', 'BLACK STONE', 'EG555 - Black', '1', 's.wisnu1106@gmail.com', 's.wisnu1106@gmail.com', '2025-08-21 04:31:37'),
	(90, 1622, 1625, 1645, 'BOM-20250821-001', 'FG-1-0001', 'HFG-1-0001', 'MT-0003', 'Finish Goods Black Stone', 'Output Cutting Black Stone', 'EVA 2 MM HD 35 - 40 MM', 'Barang Jadi', 'Barang Setengah Jadi', 'Material', 'NPR', 'NPR', 'MTR', 'BLACK STONE', 'EG555 - Black', '2', 's.wisnu1106@gmail.com', 's.wisnu1106@gmail.com', '2025-08-21 04:31:37'),
	(91, 1622, 1626, 1625, 'BOM-20250821-001', 'FG-1-0001', 'HFG-1-0002', 'HFG-1-0001', 'Finish Goods Black Stone', 'Output Sewing Black Stone', 'Output Cutting Black Stone', 'Barang Jadi', 'Barang Setengah Jadi', 'Barang Setengah Jadi', 'NPR', 'NPR', 'NPR', 'BLACK STONE', 'EG555 - Black', '1', 's.wisnu1106@gmail.com', 's.wisnu1106@gmail.com', '2025-08-21 04:31:37'),
	(93, 1623, 1631, 1643, 'BOM-20250821-002', 'FG-2-0001', 'HFG-2-0001', 'MT-0001', 'Finish Goods Rossi', 'Output Cutting Rossi', 'BLACK KIP (HARVEST GLORY)', 'Barang Jadi', 'Barang Setengah Jadi', 'Material', 'NPR', 'NPR', 'FTK', 'ROSSI', 'dsdasd - sadasd', '1', 's.wisnu1106@gmail.com', 's.wisnu1106@gmail.com', '2025-08-21 04:34:39'),
	(96, 1622, 1625, 1643, 'BOM-20250821-003', 'FG-1-0001', 'HFG-1-0001', 'MT-0001', 'Finish Goods Black Stone', 'Output Cutting Black Stone', 'BLACK KIP (HARVEST GLORY)', 'Barang Jadi', 'Barang Setengah Jadi', 'Material', 'NPR', 'NPR', 'FTK', 'BLACK STONE', 'EG555 - Black', '2.5', 's.wisnu1106@gmail.com', 's.wisnu1106@gmail.com', '2025-08-21 04:49:00'),
	(97, 1622, 1625, 1643, 'BOM-20250822-001', 'FG-1-0001', 'HFG-1-0001', 'MT-0001', 'Finish Goods Black Stone', 'Output Cutting Black Stone', 'BLACK KIP (HARVEST GLORY)', 'Barang Jadi', 'Barang Setengah Jadi', 'Material', 'NPR', 'NPR', 'FTK', 'BLACK STONE', 'EG555 - Black', '1.5', 's.wisnu1106@gmail.com', '', '2025-08-22 08:13:11'),
	(98, 1622, 1626, 1625, 'BOM-20250822-001', 'FG-1-0001', 'HFG-1-0002', 'HFG-1-0001', 'Finish Goods Black Stone', 'Output Sewing Black Stone', 'Output Cutting Black Stone', 'Barang Jadi', 'Barang Setengah Jadi', 'Barang Setengah Jadi', 'NPR', 'NPR', 'NPR', 'BLACK STONE', 'EG555 - Black', '1', 's.wisnu1106@gmail.com', '', '2025-08-22 08:13:11'),
	(99, 1622, 1626, 1649, 'BOM-20250822-001', 'FG-1-0001', 'HFG-1-0002', 'MT-0007', 'Finish Goods Black Stone', 'Output Sewing Black Stone', 'SATIN LABEL AS/NZS 2210.5.2019 EN ISO 20347:2012 BMP 714442, 714443 OCCUPATIONAL BOOT', 'Barang Jadi', 'Barang Setengah Jadi', 'Material', 'NPR', 'NPR', 'PCS', 'BLACK STONE', 'EG555 - Black', '2', 's.wisnu1106@gmail.com', '', '2025-08-22 08:13:11'),
	(100, 1622, 1627, 1626, 'BOM-20250822-001', 'FG-1-0001', 'HFG-1-0003', 'HFG-1-0002', 'Finish Goods Black Stone', 'Output Semi Black Stone', 'Output Sewing Black Stone', 'Barang Jadi', 'Barang Setengah Jadi', 'Barang Setengah Jadi', 'NPR', 'NPR', 'NPR', 'BLACK STONE', 'EG555 - Black', '1', 's.wisnu1106@gmail.com', '', '2025-08-22 08:13:11'),
	(101, 1622, 1627, 1691, 'BOM-20250822-001', 'FG-1-0001', 'HFG-1-0003', 'MT-0049', 'Finish Goods Black Stone', 'Output Semi Black Stone', 'RIPPLE OUTSOLE+LOGO RED (ROSSI)', 'Barang Jadi', 'Barang Setengah Jadi', 'Material', 'NPR', 'NPR', 'NPR', 'BLACK STONE', 'EG555 - Black', '0.4999', 's.wisnu1106@gmail.com', '', '2025-08-22 08:13:11'),
	(102, 1622, 1628, 1627, 'BOM-20250822-001', 'FG-1-0001', 'HFG-1-0004', 'HFG-1-0003', 'Finish Goods Black Stone', 'Output Lasting Black Stone', 'Output Semi Black Stone', 'Barang Jadi', 'Barang Setengah Jadi', 'Barang Setengah Jadi', 'NPR', 'NPR', 'NPR', 'BLACK STONE', 'EG555 - Black', '1', 's.wisnu1106@gmail.com', '', '2025-08-22 08:13:11'),
	(103, 1622, 1629, 1628, 'BOM-20250822-001', 'FG-1-0001', 'HFG-1-0005', 'HFG-1-0004', 'Finish Goods Black Stone', 'Output Finishing Black Stone', 'Output Lasting Black Stone', 'Barang Jadi', 'Barang Setengah Jadi', 'Barang Setengah Jadi', 'NPR', 'NPR', 'NPR', 'BLACK STONE', 'EG555 - Black', '1', 's.wisnu1106@gmail.com', '', '2025-08-22 08:13:11'),
	(104, 1622, 1630, 1629, 'BOM-20250822-001', 'FG-1-0001', 'HFG-1-0006', 'HFG-1-0005', 'Finish Goods Black Stone', 'Output Packing Black Stone', 'Output Finishing Black Stone', 'Barang Jadi', 'Barang Setengah Jadi', 'Barang Setengah Jadi', 'NPR', 'NPR', 'NPR', 'BLACK STONE', 'EG555 - Black', '1', 's.wisnu1106@gmail.com', '', '2025-08-22 08:13:11');

-- Dumping structure for table web_ai_erp.purchasing_sizerun
CREATE TABLE IF NOT EXISTS `purchasing_sizerun` (
  `id_sizerun` int NOT NULL AUTO_INCREMENT,
  `id_brand` int NOT NULL,
  `id_wo` int NOT NULL,
  `wo_number` varchar(252) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `brand_name` varchar(252) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `size_name` varchar(252) COLLATE utf8mb4_general_ci NOT NULL,
  `size_qty` varchar(252) COLLATE utf8mb4_general_ci NOT NULL,
  `created_by` varchar(252) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id_sizerun`),
  KEY `id_brand` (`id_brand`),
  KEY `id_wo` (`id_wo`)
) ENGINE=InnoDB AUTO_INCREMENT=4834 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table web_ai_erp.purchasing_sizerun: ~27 rows (approximately)
INSERT INTO `purchasing_sizerun` (`id_sizerun`, `id_brand`, `id_wo`, `wo_number`, `brand_name`, `size_name`, `size_qty`, `created_by`, `created_at`) VALUES
	(4807, 1, 753, 'po-001', 'BLACK STONE', '36', '2', 's.wisnu1106@gmail.com', '2025-08-27 04:43:40'),
	(4808, 1, 753, 'po-001', 'BLACK STONE', '37', '2', 's.wisnu1106@gmail.com', '2025-08-27 04:43:40'),
	(4809, 1, 753, 'po-001', 'BLACK STONE', '38', '2', 's.wisnu1106@gmail.com', '2025-08-27 04:43:40'),
	(4810, 1, 753, 'po-001', 'BLACK STONE', '39', '2', 's.wisnu1106@gmail.com', '2025-08-27 04:43:40'),
	(4811, 1, 753, 'po-001', 'BLACK STONE', '40', '2', 's.wisnu1106@gmail.com', '2025-08-27 04:43:40'),
	(4812, 1, 762, 'po-002', 'BLACK STONE', '36', '2', 's.wisnu1106@gmail.com', '2025-08-27 04:48:51'),
	(4813, 1, 762, 'po-002', 'BLACK STONE', '37', '2', 's.wisnu1106@gmail.com', '2025-08-27 04:48:51'),
	(4814, 1, 762, 'po-002', 'BLACK STONE', '38', '2', 's.wisnu1106@gmail.com', '2025-08-27 04:48:51'),
	(4815, 1, 762, 'po-002', 'BLACK STONE', '39', '2', 's.wisnu1106@gmail.com', '2025-08-27 04:48:51'),
	(4816, 1, 762, 'po-002', 'BLACK STONE', '40', '2', 's.wisnu1106@gmail.com', '2025-08-27 04:48:51'),
	(4817, 1, 771, 'po-003', 'BLACK STONE', '36', '2', 's.wisnu1106@gmail.com', '2025-08-27 04:52:55'),
	(4818, 1, 771, 'po-003', 'BLACK STONE', '37', '2', 's.wisnu1106@gmail.com', '2025-08-27 04:52:55'),
	(4819, 1, 771, 'po-003', 'BLACK STONE', '38', '2', 's.wisnu1106@gmail.com', '2025-08-27 04:52:55'),
	(4820, 1, 771, 'po-003', 'BLACK STONE', '39', '2', 's.wisnu1106@gmail.com', '2025-08-27 04:52:55'),
	(4821, 1, 771, 'po-003', 'BLACK STONE', '40', '2', 's.wisnu1106@gmail.com', '2025-08-27 04:52:55'),
	(4822, 1, 771, 'po-003', 'BLACK STONE', '41', '2', 's.wisnu1106@gmail.com', '2025-08-27 04:52:55'),
	(4823, 1, 780, 'po-004', 'BLACK STONE', '36', '1', 's.wisnu1106@gmail.com', '2025-08-27 04:56:09'),
	(4824, 1, 780, 'po-004', 'BLACK STONE', '37', '2', 's.wisnu1106@gmail.com', '2025-08-27 04:56:09'),
	(4825, 1, 780, 'po-004', 'BLACK STONE', '38', '2', 's.wisnu1106@gmail.com', '2025-08-27 04:56:09'),
	(4826, 1, 780, 'po-004', 'BLACK STONE', '39', '2', 's.wisnu1106@gmail.com', '2025-08-27 04:56:09'),
	(4827, 1, 780, 'po-004', 'BLACK STONE', '40', '2', 's.wisnu1106@gmail.com', '2025-08-27 04:56:09'),
	(4828, 1, 780, 'po-004', 'BLACK STONE', '41', '2', 's.wisnu1106@gmail.com', '2025-08-27 04:56:09'),
	(4829, 2, 789, 'po-005', 'ROSSI', '3', '2', 's.wisnu1106@gmail.com', '2025-08-27 06:50:50'),
	(4830, 2, 789, 'po-005', 'ROSSI', '3T', '2', 's.wisnu1106@gmail.com', '2025-08-27 06:50:50'),
	(4831, 2, 789, 'po-005', 'ROSSI', '4', '2', 's.wisnu1106@gmail.com', '2025-08-27 06:50:50'),
	(4832, 2, 789, 'po-005', 'ROSSI', '4T', '2', 's.wisnu1106@gmail.com', '2025-08-27 06:50:50'),
	(4833, 2, 789, 'po-005', 'ROSSI', '5', '2', 's.wisnu1106@gmail.com', '2025-08-27 06:50:50');

-- Dumping structure for table web_ai_erp.purchasing_wo
CREATE TABLE IF NOT EXISTS `purchasing_wo` (
  `id_wo` int NOT NULL AUTO_INCREMENT,
  `kode_bom` varchar(252) COLLATE utf8mb4_general_ci NOT NULL,
  `wo_number` varchar(252) COLLATE utf8mb4_general_ci NOT NULL,
  `fg_kode_item` varchar(252) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `hfg_kode_item` varchar(252) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `mt_kode_item` varchar(252) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `fg_item_name` varchar(252) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `hfg_item_name` varchar(252) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `mt_item_name` varchar(252) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `fg_category_name` varchar(252) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `hfg_category_name` varchar(252) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `mt_category_name` varchar(252) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `fg_unit` varchar(252) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `hfg_unit` varchar(252) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `mt_unit` varchar(252) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `brand_name` varchar(252) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `artcolor_name` varchar(252) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `bom_qty` varchar(252) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `bom_cons` varchar(252) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `wo_qty` varchar(252) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `date_of_order` date NOT NULL,
  `due_date` date NOT NULL,
  `created_by` varchar(252) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id_wo`),
  KEY `kode_bom` (`kode_bom`)
) ENGINE=InnoDB AUTO_INCREMENT=791 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table web_ai_erp.purchasing_wo: ~29 rows (approximately)
INSERT INTO `purchasing_wo` (`id_wo`, `kode_bom`, `wo_number`, `fg_kode_item`, `hfg_kode_item`, `mt_kode_item`, `fg_item_name`, `hfg_item_name`, `mt_item_name`, `fg_category_name`, `hfg_category_name`, `mt_category_name`, `fg_unit`, `hfg_unit`, `mt_unit`, `brand_name`, `artcolor_name`, `bom_qty`, `bom_cons`, `wo_qty`, `date_of_order`, `due_date`, `created_by`, `created_at`) VALUES
	(762, 'BOM-20250822-001', 'po-002', 'FG-1-0001', 'HFG-1-0001', 'MT-0001', 'Finish Goods Black Stone', 'Output Cutting Black Stone', 'BLACK KIP (HARVEST GLORY)', 'Barang Jadi', 'Barang Setengah Jadi', 'Material', 'NPR', 'NPR', 'FTK', 'BLACK STONE', 'EG555 - Black', '1.5', '89.999', '10', '2025-08-27', '2025-09-04', 's.wisnu1106@gmail.com', '2025-08-27 04:48:51'),
	(763, 'BOM-20250822-001', 'po-002', 'FG-1-0001', 'HFG-1-0001', 'MT-0001', 'Finish Goods Black Stone', 'Output Cutting Black Stone', 'BLACK KIP (HARVEST GLORY)', 'Barang Jadi', 'Barang Setengah Jadi', 'Material', 'NPR', 'NPR', 'FTK', 'BLACK STONE', 'EG555 - Black', '1.5', '15', '10', '2025-08-27', '2025-09-04', 's.wisnu1106@gmail.com', '2025-08-27 04:48:51'),
	(764, 'BOM-20250822-001', 'po-002', 'FG-1-0001', 'HFG-1-0002', 'HFG-1-0001', 'Finish Goods Black Stone', 'Output Sewing Black Stone', 'Output Cutting Black Stone', 'Barang Jadi', 'Barang Setengah Jadi', 'Barang Setengah Jadi', 'NPR', 'NPR', 'NPR', 'BLACK STONE', 'EG555 - Black', '1', '10', '10', '2025-08-27', '2025-09-04', 's.wisnu1106@gmail.com', '2025-08-27 04:48:51'),
	(765, 'BOM-20250822-001', 'po-002', 'FG-1-0001', 'HFG-1-0002', 'MT-0007', 'Finish Goods Black Stone', 'Output Sewing Black Stone', 'SATIN LABEL AS/NZS 2210.5.2019 EN ISO 20347:2012 BMP 714442, 714443 OCCUPATIONAL BOOT', 'Barang Jadi', 'Barang Setengah Jadi', 'Material', 'NPR', 'NPR', 'PCS', 'BLACK STONE', 'EG555 - Black', '2', '20', '10', '2025-08-27', '2025-09-04', 's.wisnu1106@gmail.com', '2025-08-27 04:48:51'),
	(766, 'BOM-20250822-001', 'po-002', 'FG-1-0001', 'HFG-1-0003', 'HFG-1-0002', 'Finish Goods Black Stone', 'Output Semi Black Stone', 'Output Sewing Black Stone', 'Barang Jadi', 'Barang Setengah Jadi', 'Barang Setengah Jadi', 'NPR', 'NPR', 'NPR', 'BLACK STONE', 'EG555 - Black', '1', '10', '10', '2025-08-27', '2025-09-04', 's.wisnu1106@gmail.com', '2025-08-27 04:48:51'),
	(767, 'BOM-20250822-001', 'po-002', 'FG-1-0001', 'HFG-1-0003', 'MT-0049', 'Finish Goods Black Stone', 'Output Semi Black Stone', 'RIPPLE OUTSOLE+LOGO RED (ROSSI)', 'Barang Jadi', 'Barang Setengah Jadi', 'Material', 'NPR', 'NPR', 'NPR', 'BLACK STONE', 'EG555 - Black', '0.4999', '4.999', '10', '2025-08-27', '2025-09-04', 's.wisnu1106@gmail.com', '2025-08-27 04:48:51'),
	(768, 'BOM-20250822-001', 'po-002', 'FG-1-0001', 'HFG-1-0004', 'HFG-1-0003', 'Finish Goods Black Stone', 'Output Lasting Black Stone', 'Output Semi Black Stone', 'Barang Jadi', 'Barang Setengah Jadi', 'Barang Setengah Jadi', 'NPR', 'NPR', 'NPR', 'BLACK STONE', 'EG555 - Black', '1', '10', '10', '2025-08-27', '2025-09-04', 's.wisnu1106@gmail.com', '2025-08-27 04:48:51'),
	(769, 'BOM-20250822-001', 'po-002', 'FG-1-0001', 'HFG-1-0005', 'HFG-1-0004', 'Finish Goods Black Stone', 'Output Finishing Black Stone', 'Output Lasting Black Stone', 'Barang Jadi', 'Barang Setengah Jadi', 'Barang Setengah Jadi', 'NPR', 'NPR', 'NPR', 'BLACK STONE', 'EG555 - Black', '1', '10', '10', '2025-08-27', '2025-09-04', 's.wisnu1106@gmail.com', '2025-08-27 04:48:51'),
	(770, 'BOM-20250822-001', 'po-002', 'FG-1-0001', 'HFG-1-0006', 'HFG-1-0005', 'Finish Goods Black Stone', 'Output Packing Black Stone', 'Output Finishing Black Stone', 'Barang Jadi', 'Barang Setengah Jadi', 'Barang Setengah Jadi', 'NPR', 'NPR', 'NPR', 'BLACK STONE', 'EG555 - Black', '1', '10', '10', '2025-08-27', '2025-09-04', 's.wisnu1106@gmail.com', '2025-08-27 04:48:51'),
	(771, 'BOM-20250822-001', 'po-003', 'FG-1-0001', 'HFG-1-0001', 'MT-0001', 'Finish Goods Black Stone', 'Output Cutting Black Stone', 'BLACK KIP (HARVEST GLORY)', 'Barang Jadi', 'Barang Setengah Jadi', 'Material', 'NPR', 'NPR', 'FTK', 'BLACK STONE', 'EG555 - Black', '1.5', '107.9988', '12', '2025-08-27', '2025-09-04', 's.wisnu1106@gmail.com', '2025-08-27 04:52:55'),
	(772, 'BOM-20250822-001', 'po-003', 'FG-1-0001', 'HFG-1-0001', 'MT-0001', 'Finish Goods Black Stone', 'Output Cutting Black Stone', 'BLACK KIP (HARVEST GLORY)', 'Barang Jadi', 'Barang Setengah Jadi', 'Material', 'NPR', 'NPR', 'FTK', 'BLACK STONE', 'EG555 - Black', '1.5', '18', '12', '2025-08-27', '2025-09-04', 's.wisnu1106@gmail.com', '2025-08-27 04:52:55'),
	(773, 'BOM-20250822-001', 'po-003', 'FG-1-0001', 'HFG-1-0002', 'HFG-1-0001', 'Finish Goods Black Stone', 'Output Sewing Black Stone', 'Output Cutting Black Stone', 'Barang Jadi', 'Barang Setengah Jadi', 'Barang Setengah Jadi', 'NPR', 'NPR', 'NPR', 'BLACK STONE', 'EG555 - Black', '1', '12', '12', '2025-08-27', '2025-09-04', 's.wisnu1106@gmail.com', '2025-08-27 04:52:55'),
	(774, 'BOM-20250822-001', 'po-003', 'FG-1-0001', 'HFG-1-0002', 'MT-0007', 'Finish Goods Black Stone', 'Output Sewing Black Stone', 'SATIN LABEL AS/NZS 2210.5.2019 EN ISO 20347:2012 BMP 714442, 714443 OCCUPATIONAL BOOT', 'Barang Jadi', 'Barang Setengah Jadi', 'Material', 'NPR', 'NPR', 'PCS', 'BLACK STONE', 'EG555 - Black', '2', '24', '12', '2025-08-27', '2025-09-04', 's.wisnu1106@gmail.com', '2025-08-27 04:52:55'),
	(775, 'BOM-20250822-001', 'po-003', 'FG-1-0001', 'HFG-1-0003', 'HFG-1-0002', 'Finish Goods Black Stone', 'Output Semi Black Stone', 'Output Sewing Black Stone', 'Barang Jadi', 'Barang Setengah Jadi', 'Barang Setengah Jadi', 'NPR', 'NPR', 'NPR', 'BLACK STONE', 'EG555 - Black', '1', '12', '12', '2025-08-27', '2025-09-04', 's.wisnu1106@gmail.com', '2025-08-27 04:52:55'),
	(776, 'BOM-20250822-001', 'po-003', 'FG-1-0001', 'HFG-1-0003', 'MT-0049', 'Finish Goods Black Stone', 'Output Semi Black Stone', 'RIPPLE OUTSOLE+LOGO RED (ROSSI)', 'Barang Jadi', 'Barang Setengah Jadi', 'Material', 'NPR', 'NPR', 'NPR', 'BLACK STONE', 'EG555 - Black', '0.4999', '5.9988', '12', '2025-08-27', '2025-09-04', 's.wisnu1106@gmail.com', '2025-08-27 04:52:55'),
	(777, 'BOM-20250822-001', 'po-003', 'FG-1-0001', 'HFG-1-0004', 'HFG-1-0003', 'Finish Goods Black Stone', 'Output Lasting Black Stone', 'Output Semi Black Stone', 'Barang Jadi', 'Barang Setengah Jadi', 'Barang Setengah Jadi', 'NPR', 'NPR', 'NPR', 'BLACK STONE', 'EG555 - Black', '1', '12', '12', '2025-08-27', '2025-09-04', 's.wisnu1106@gmail.com', '2025-08-27 04:52:55'),
	(778, 'BOM-20250822-001', 'po-003', 'FG-1-0001', 'HFG-1-0005', 'HFG-1-0004', 'Finish Goods Black Stone', 'Output Finishing Black Stone', 'Output Lasting Black Stone', 'Barang Jadi', 'Barang Setengah Jadi', 'Barang Setengah Jadi', 'NPR', 'NPR', 'NPR', 'BLACK STONE', 'EG555 - Black', '1', '12', '12', '2025-08-27', '2025-09-04', 's.wisnu1106@gmail.com', '2025-08-27 04:52:55'),
	(779, 'BOM-20250822-001', 'po-003', 'FG-1-0001', 'HFG-1-0006', 'HFG-1-0005', 'Finish Goods Black Stone', 'Output Packing Black Stone', 'Output Finishing Black Stone', 'Barang Jadi', 'Barang Setengah Jadi', 'Barang Setengah Jadi', 'NPR', 'NPR', 'NPR', 'BLACK STONE', 'EG555 - Black', '1', '12', '12', '2025-08-27', '2025-09-04', 's.wisnu1106@gmail.com', '2025-08-27 04:52:55'),
	(780, 'BOM-20250822-001', 'po-004', 'FG-1-0001', 'HFG-1-0001', 'MT-0001', 'Finish Goods Black Stone', 'Output Cutting Black Stone', 'BLACK KIP (HARVEST GLORY)', 'Barang Jadi', 'Barang Setengah Jadi', 'Material', 'NPR', 'NPR', 'FTK', 'BLACK STONE', 'EG555 - Black', '1.5', '98.9989', '11', '2025-08-27', '2025-08-27', 's.wisnu1106@gmail.com', '2025-08-27 04:56:09'),
	(781, 'BOM-20250822-001', 'po-004', 'FG-1-0001', 'HFG-1-0001', 'MT-0001', 'Finish Goods Black Stone', 'Output Cutting Black Stone', 'BLACK KIP (HARVEST GLORY)', 'Barang Jadi', 'Barang Setengah Jadi', 'Material', 'NPR', 'NPR', 'FTK', 'BLACK STONE', 'EG555 - Black', '1.5', '16.5', '11', '2025-08-27', '2025-08-27', 's.wisnu1106@gmail.com', '2025-08-27 04:56:09'),
	(782, 'BOM-20250822-001', 'po-004', 'FG-1-0001', 'HFG-1-0002', 'HFG-1-0001', 'Finish Goods Black Stone', 'Output Sewing Black Stone', 'Output Cutting Black Stone', 'Barang Jadi', 'Barang Setengah Jadi', 'Barang Setengah Jadi', 'NPR', 'NPR', 'NPR', 'BLACK STONE', 'EG555 - Black', '1', '11', '11', '2025-08-27', '2025-08-27', 's.wisnu1106@gmail.com', '2025-08-27 04:56:09'),
	(783, 'BOM-20250822-001', 'po-004', 'FG-1-0001', 'HFG-1-0002', 'MT-0007', 'Finish Goods Black Stone', 'Output Sewing Black Stone', 'SATIN LABEL AS/NZS 2210.5.2019 EN ISO 20347:2012 BMP 714442, 714443 OCCUPATIONAL BOOT', 'Barang Jadi', 'Barang Setengah Jadi', 'Material', 'NPR', 'NPR', 'PCS', 'BLACK STONE', 'EG555 - Black', '2', '22', '11', '2025-08-27', '2025-08-27', 's.wisnu1106@gmail.com', '2025-08-27 04:56:09'),
	(784, 'BOM-20250822-001', 'po-004', 'FG-1-0001', 'HFG-1-0003', 'HFG-1-0002', 'Finish Goods Black Stone', 'Output Semi Black Stone', 'Output Sewing Black Stone', 'Barang Jadi', 'Barang Setengah Jadi', 'Barang Setengah Jadi', 'NPR', 'NPR', 'NPR', 'BLACK STONE', 'EG555 - Black', '1', '11', '11', '2025-08-27', '2025-08-27', 's.wisnu1106@gmail.com', '2025-08-27 04:56:09'),
	(785, 'BOM-20250822-001', 'po-004', 'FG-1-0001', 'HFG-1-0003', 'MT-0049', 'Finish Goods Black Stone', 'Output Semi Black Stone', 'RIPPLE OUTSOLE+LOGO RED (ROSSI)', 'Barang Jadi', 'Barang Setengah Jadi', 'Material', 'NPR', 'NPR', 'NPR', 'BLACK STONE', 'EG555 - Black', '0.4999', '5.4989', '11', '2025-08-27', '2025-08-27', 's.wisnu1106@gmail.com', '2025-08-27 04:56:09'),
	(786, 'BOM-20250822-001', 'po-004', 'FG-1-0001', 'HFG-1-0004', 'HFG-1-0003', 'Finish Goods Black Stone', 'Output Lasting Black Stone', 'Output Semi Black Stone', 'Barang Jadi', 'Barang Setengah Jadi', 'Barang Setengah Jadi', 'NPR', 'NPR', 'NPR', 'BLACK STONE', 'EG555 - Black', '1', '11', '11', '2025-08-27', '2025-08-27', 's.wisnu1106@gmail.com', '2025-08-27 04:56:09'),
	(787, 'BOM-20250822-001', 'po-004', 'FG-1-0001', 'HFG-1-0005', 'HFG-1-0004', 'Finish Goods Black Stone', 'Output Finishing Black Stone', 'Output Lasting Black Stone', 'Barang Jadi', 'Barang Setengah Jadi', 'Barang Setengah Jadi', 'NPR', 'NPR', 'NPR', 'BLACK STONE', 'EG555 - Black', '1', '11', '11', '2025-08-27', '2025-08-27', 's.wisnu1106@gmail.com', '2025-08-27 04:56:09'),
	(788, 'BOM-20250822-001', 'po-004', 'FG-1-0001', 'HFG-1-0006', 'HFG-1-0005', 'Finish Goods Black Stone', 'Output Packing Black Stone', 'Output Finishing Black Stone', 'Barang Jadi', 'Barang Setengah Jadi', 'Barang Setengah Jadi', 'NPR', 'NPR', 'NPR', 'BLACK STONE', 'EG555 - Black', '1', '11', '11', '2025-08-27', '2025-08-27', 's.wisnu1106@gmail.com', '2025-08-27 04:56:09'),
	(789, 'BOM-20250821-002', 'po-005', 'FG-2-0001', 'HFG-2-0001', 'MT-0001', 'Finish Goods Rossi', 'Output Cutting Rossi', 'BLACK KIP (HARVEST GLORY)', 'Barang Jadi', 'Barang Setengah Jadi', 'Material', 'NPR', 'NPR', 'FTK', 'ROSSI', 'dsdasd - sadasd', '1', '10', '10', '2025-08-27', '2025-09-05', 's.wisnu1106@gmail.com', '2025-08-27 06:50:50'),
	(790, 'BOM-20250821-002', 'po-005', 'FG-2-0001', 'HFG-2-0001', 'MT-0001', 'Finish Goods Rossi', 'Output Cutting Rossi', 'BLACK KIP (HARVEST GLORY)', 'Barang Jadi', 'Barang Setengah Jadi', 'Material', 'NPR', 'NPR', 'FTK', 'ROSSI', 'dsdasd - sadasd', '1', '10', '10', '2025-08-27', '2025-09-05', 's.wisnu1106@gmail.com', '2025-08-27 06:50:50');

-- Dumping structure for table web_ai_erp.user_token
CREATE TABLE IF NOT EXISTS `user_token` (
  `id_token` int NOT NULL AUTO_INCREMENT,
  `user_email` varchar(252) COLLATE utf8mb4_general_ci NOT NULL,
  `user_token` varchar(252) COLLATE utf8mb4_general_ci NOT NULL,
  `user_token_created_at` datetime NOT NULL,
  PRIMARY KEY (`id_token`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table web_ai_erp.user_token: ~8 rows (approximately)
INSERT INTO `user_token` (`id_token`, `user_email`, `user_token`, `user_token_created_at`) VALUES
	(1, 's.wisnu1106@gmail.com', 'hSf0XTe3Bzh0usV2oLRoPOmlrHf8ir4EG3qlBqut034=', '2025-08-13 06:24:50'),
	(2, 's.wisnu1106@gmail.com', 'UETJ6bwLUHlThHs3QMZA11oMS+Ll4jKOeSLUcQwjkzc=', '2025-08-13 06:33:48'),
	(3, 's.wisnu1106@gmail.com', 'zsVSnhIEEFCzgaUa6ACzXKxFS3HOaRgDV4bN6mfvcWg=', '2025-08-13 06:35:57'),
	(4, 's.wisnu1106@gmail.com', 'BAq5KcRdCFJk4Lm0cSDlz5ajAJKbbWZxydYkPPi6U58=', '2025-08-13 06:40:54'),
	(5, 's.wisnu1106@gmail.com', 'B4Te3jfcLsG4/NBIeHrH8DC48yCL43RV+xCuQieRiV4=', '2025-08-13 06:47:57'),
	(6, 's.wisnu1106@gmail.com', 'xTsoJEwAXUhn+5M5i06iYwEG4mREMCIkPO/Nnt4hLPo=', '2025-08-13 06:50:54'),
	(7, 's.wisnu1106@gmail.com', 'Q9v/OfBImXS2EwYk/GZ9Hjc32/R9RH+OmulzjWU0vAw=', '2025-08-13 07:57:34'),
	(8, 's.wisnu1106@gmail.com', 'GslGY4xbxjn3oBZgnAbJEMRGuyqtXxmtvzxEkpXeguo=', '2025-08-13 08:00:10'),
	(9, 's.wisnu1106@gmail.com', 'VqCrZnaJZ/k69R6eo55buLgGJmC5Dt6K6Y5oWXgly+g=', '2025-08-13 08:06:56');

-- Dumping structure for table web_ai_erp.wr_sizerun
CREATE TABLE IF NOT EXISTS `wr_sizerun` (
  `id_sizesj` int NOT NULL AUTO_INCREMENT,
  `id_wo` int DEFAULT NULL,
  `id_brand` int DEFAULT NULL,
  `kode_sj` varchar(252) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kode_item` varchar(252) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `wo_number` varchar(252) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `brand_name` varchar(252) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `size_name` varchar(252) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `sizeq_qty` varchar(252) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_by` varchar(252) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id_sizesj`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table web_ai_erp.wr_sizerun: ~15 rows (approximately)
INSERT INTO `wr_sizerun` (`id_sizesj`, `id_wo`, `id_brand`, `kode_sj`, `kode_item`, `wo_number`, `brand_name`, `size_name`, `sizeq_qty`, `created_by`, `created_at`) VALUES
	(1, 754, 1, 'IN-003-2025-08-28', 'MT-0001', 'po-001', 'BLACK STONE', '36', '2', 's.wisnu1106@gmail.com', '2025-08-28 06:46:42'),
	(2, 754, 1, 'IN-003-2025-08-28', 'MT-0001', 'po-001', 'BLACK STONE', '37', '2', 's.wisnu1106@gmail.com', '2025-08-28 06:46:42'),
	(3, 754, 1, 'IN-003-2025-08-28', 'MT-0001', 'po-001', 'BLACK STONE', '38', '2', 's.wisnu1106@gmail.com', '2025-08-28 06:46:42'),
	(4, 754, 1, 'IN-003-2025-08-28', 'MT-0001', 'po-001', 'BLACK STONE', '39', '2', 's.wisnu1106@gmail.com', '2025-08-28 06:46:42'),
	(5, 754, 1, 'IN-003-2025-08-28', 'MT-0001', 'po-001', 'BLACK STONE', '40', '2', 's.wisnu1106@gmail.com', '2025-08-28 06:46:42'),
	(6, 754, 1, 'IN-003-2025-08-28', 'MT-0001', 'po-001', 'BLACK STONE', '41', '5', 's.wisnu1106@gmail.com', '2025-08-28 06:46:42'),
	(7, 789, 2, 'IN-003-2025-08-28', 'MT-0001', 'po-005', 'ROSSI', '3', '2', 's.wisnu1106@gmail.com', '2025-08-28 06:46:42'),
	(8, 789, 2, 'IN-003-2025-08-28', 'MT-0001', 'po-005', 'ROSSI', '3T', '2', 's.wisnu1106@gmail.com', '2025-08-28 06:46:42'),
	(9, 789, 2, 'IN-003-2025-08-28', 'MT-0001', 'po-005', 'ROSSI', '4', '2', 's.wisnu1106@gmail.com', '2025-08-28 06:46:42'),
	(10, 789, 2, 'IN-003-2025-08-28', 'MT-0001', 'po-005', 'ROSSI', '4T', '2', 's.wisnu1106@gmail.com', '2025-08-28 06:46:42'),
	(11, 789, 2, 'IN-003-2025-08-28', 'MT-0001', 'po-005', 'ROSSI', '5', '2', 's.wisnu1106@gmail.com', '2025-08-28 06:46:42'),
	(12, 754, 1, 'IN-004-2025-08-29', 'MT-0001', 'po-001', 'BLACK STONE', '38', '4', 's.wisnu1106@gmail.com', '2025-08-29 08:07:21'),
	(13, 754, 1, 'IN-004-2025-08-29', 'MT-0001', 'po-001', 'BLACK STONE', '39', '5', 's.wisnu1106@gmail.com', '2025-08-29 08:07:21'),
	(14, 789, 2, 'IN-004-2025-08-29', 'MT-0001', 'po-005', 'ROSSI', '3', '2', 's.wisnu1106@gmail.com', '2025-08-29 08:07:21'),
	(15, 789, 2, 'IN-004-2025-08-29', 'MT-0001', 'po-005', 'ROSSI', '3T', '2', 's.wisnu1106@gmail.com', '2025-08-29 08:07:21');

-- Dumping structure for table web_ai_erp.wr_stock
CREATE TABLE IF NOT EXISTS `wr_stock` (
  `id_sj` int NOT NULL AUTO_INCREMENT,
  `id_wo` int NOT NULL,
  `kode_sj` varchar(252) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `no_sj` varchar(252) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `from_dept` varchar(252) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `to_dept` varchar(252) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kode_bom` varchar(252) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `wo_number` varchar(252) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kode_item` varchar(252) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `category_name` varchar(252) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `unit_name` varchar(252) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `item_name` varchar(252) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `brand` varchar(252) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `artcolor` varchar(252) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `bom_cons` varchar(252) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `checkin` varchar(252) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `checkout` varchar(252) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_by` varchar(252) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `date_arrive` datetime DEFAULT NULL,
  PRIMARY KEY (`id_sj`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table web_ai_erp.wr_stock: ~18 rows (approximately)
INSERT INTO `wr_stock` (`id_sj`, `id_wo`, `kode_sj`, `no_sj`, `from_dept`, `to_dept`, `kode_bom`, `wo_number`, `kode_item`, `category_name`, `unit_name`, `item_name`, `brand`, `artcolor`, `bom_cons`, `checkin`, `checkout`, `created_by`, `created_at`, `date_arrive`) VALUES
	(6, 754, 'IN-003-2025-08-28', 'WH-AI-0004', 'SUPPLIER', 'WAREHOUSE', '', 'po-001', 'MT-0001', 'Material', 'FTK', 'BLACK KIP (HARVEST GLORY)', 'BLACK STONE', 'EG555 - Black', '15', '15', '', 's.wisnu1106@gmail.com', '2025-08-28 06:46:42', '2025-08-28 00:00:00'),
	(7, 789, 'IN-003-2025-08-28', 'WH-AI-0004', 'SUPPLIER', 'WAREHOUSE', '', 'po-005', 'MT-0001', 'Material', 'FTK', 'BLACK KIP (HARVEST GLORY)', 'ROSSI', 'dsdasd - sadasd', '10', '10', '', 's.wisnu1106@gmail.com', '2025-08-28 06:46:42', '2025-08-28 00:00:00'),
	(8, 754, 'IN-004-2025-08-29', 'WH-AI-0011', 'SUPPLIER', 'WAREHOUSE', '', 'po-001', 'MT-0001', 'Material', 'FTK', 'BLACK KIP (HARVEST GLORY)', 'BLACK STONE', 'EG555 - Black', '15', '9', '', 's.wisnu1106@gmail.com', '2025-08-29 08:07:21', '2025-08-29 00:00:00'),
	(9, 789, 'IN-004-2025-08-29', 'WH-AI-0011', 'SUPPLIER', 'WAREHOUSE', '', 'po-005', 'MT-0001', 'Material', 'FTK', 'BLACK KIP (HARVEST GLORY)', 'ROSSI', 'dsdasd - sadasd', '10', '4', '', 's.wisnu1106@gmail.com', '2025-08-29 08:07:21', '2025-08-29 00:00:00'),
	(12, 771, 'WHS-CTG-002', 'OUT-0001-20250901', 'WAREHOUSE', 'CUTTING', NULL, 'po-003', 'MT-0001', 'Material', 'FTK', 'BLACK KIP (HARVEST GLORY)', 'BLACK STONE', 'EG555 - Black', NULL, NULL, '18', 's.wisnu1106@gmail.com', '2025-09-01 09:27:01', '2025-09-01 00:00:00'),
	(13, 771, 'WHS-CTG-002', 'OUT-0001-20250901', 'WAREHOUSE', 'CUTTING', NULL, 'po-003', 'MT-0049', 'Material', 'NPR', 'RIPPLE OUTSOLE+LOGO RED (ROSSI)', 'BLACK STONE', 'EG555 - Black', NULL, NULL, '5.9988', 's.wisnu1106@gmail.com', '2025-09-01 09:27:01', '2025-09-01 00:00:00'),
	(14, 780, 'WHS-CTG-003', 'OUT-0001-20250902', 'WAREHOUSE', 'CUTTING', NULL, 'po-004', 'MT-0001', 'Material', 'FTK', 'BLACK KIP (HARVEST GLORY)', 'BLACK STONE', 'EG555 - Black', NULL, NULL, '13.5', 's.wisnu1106@gmail.com', '2025-09-02 07:06:00', '2025-09-02 00:00:00'),
	(15, 772, 'IN-005-2025-09-03', 'WH-AI-0009', 'SUPPLIER', 'WAREHOUSE', '', 'po-003', 'MT-0001', 'Material', 'FTK', 'BLACK KIP (HARVEST GLORY)', 'BLACK STONE', 'EG555 - Black', '18', '36', '', 's.wisnu1106@gmail.com', '2025-09-03 03:06:52', '2025-09-03 00:00:00'),
	(16, 776, 'IN-005-2025-09-03', 'WH-AI-0009', 'SUPPLIER', 'WAREHOUSE', '', 'po-003', 'MT-0049', 'Material', 'NPR', 'RIPPLE OUTSOLE+LOGO RED (ROSSI)', 'BLACK STONE', 'EG555 - Black', '5.9988', '7', '', 's.wisnu1106@gmail.com', '2025-09-03 03:06:52', '2025-09-03 00:00:00'),
	(17, 780, 'WHS-CTG-004', 'OUT-0001-20250903', 'WAREHOUSE', 'CUTTING', NULL, 'po-004', 'MT-0001', 'Material', 'FTK', 'BLACK KIP (HARVEST GLORY)', 'BLACK STONE', 'EG555 - Black', NULL, NULL, '16.5', 's.wisnu1106@gmail.com', '2025-09-03 03:11:43', '2025-09-03 00:00:00'),
	(18, 771, 'WHS-CTG-005', 'OUT-0001-20250904', 'WAREHOUSE', 'CUTTING', NULL, 'po-003', 'MT-0001', 'Material', 'FTK', 'BLACK KIP (HARVEST GLORY)', 'BLACK STONE', 'EG555 - Black', NULL, NULL, '18', 's.wisnu1106@gmail.com', '2025-09-04 09:09:43', '2025-09-04 00:00:00'),
	(19, 771, 'WHS-CTG-006', 'OUT-0001-20250904', 'WAREHOUSE', 'CUTTING', NULL, 'po-003', 'MT-0001', 'Material', 'FTK', 'BLACK KIP (HARVEST GLORY)', 'BLACK STONE', 'EG555 - Black', NULL, NULL, '18', 's.wisnu1106@gmail.com', '2025-09-04 09:28:40', '2025-09-04 00:00:00'),
	(20, 764, 'IN-006-2025-09-10', '12313', 'CUTTING', 'WAREHOUSE', '', 'po-002', 'HFG-1-0001', 'Barang Setengah Jadi', 'NPR', 'Output Cutting Black Stone', 'BLACK STONE', 'EG555 - Black', '10', '10', '', 's.wisnu1106@gmail.com', '2025-09-10 03:40:56', '2025-09-10 00:00:00'),
	(21, 773, 'IN-007-2025-09-11', 'Produksi', 'CUTTING', 'WAREHOUSE', '', 'po-003', 'HFG-1-0001', 'Barang Setengah Jadi', 'NPR', 'Output Cutting Black Stone', 'BLACK STONE', 'EG555 - Black', '12', '12', '', 's.wisnu1106@gmail.com', '2025-09-11 07:12:14', '2025-09-11 00:00:00'),
	(22, 766, 'IN-008-2025-09-11', 'Production', 'SEWING', 'WAREHOUSE', '', 'po-002', 'HFG-1-0002', 'Barang Setengah Jadi', 'NPR', 'Output Sewing Black Stone', 'BLACK STONE', 'EG555 - Black', '10', '10', '', 's.wisnu1106@gmail.com', '2025-09-11 08:28:17', '2025-09-11 00:00:00'),
	(25, 768, 'IN-009-2025-09-11', 'Production', 'SEMI WAREHOUSE', 'WAREHOUSE', '', 'po-002', 'HFG-1-0003', 'Barang Setengah Jadi', 'NPR', 'Output Semi Black Stone', 'BLACK STONE', 'EG555 - Black', '10', '10', '', 's.wisnu1106@gmail.com', '2025-09-11 08:30:13', '2025-09-11 00:00:00'),
	(26, 769, 'IN-010-2025-09-11', 'Production', 'LASTING', 'WAREHOUSE', '', 'po-002', 'HFG-1-0004', 'Barang Setengah Jadi', 'NPR', 'Output Lasting Black Stone', 'BLACK STONE', 'EG555 - Black', '10', '10', '', 's.wisnu1106@gmail.com', '2025-09-11 08:30:36', '2025-09-11 00:00:00'),
	(27, 770, 'IN-011-2025-09-11', 'Production', 'FINISHING', 'WAREHOUSE', '', 'po-002', 'HFG-1-0005', 'Barang Setengah Jadi', 'NPR', 'Output Finishing Black Stone', 'BLACK STONE', 'EG555 - Black', '10', '5', '', 's.wisnu1106@gmail.com', '2025-09-11 08:33:49', '2025-09-11 00:00:00');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
