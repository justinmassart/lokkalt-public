-- -------------------------------------------------------------
-- TablePlus 4.6.0(406)
--
-- https://tableplus.com/
--
-- Database: lokkalt
-- Generation Time: 2024-06-18 23:06:50.4280
-- -------------------------------------------------------------


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

INSERT INTO `pack_features` (`id`, `name`, `created_at`, `updated_at`, `pack_id`) VALUES
('9c4f9442-ff32-4bfd-ba98-5e2df1609b61', 'visible_shop', '2024-06-17 20:27:17', '2024-06-17 20:27:17', '9c4f9442-fee9-4e8f-b39a-d40bdb28ad63'),
('9c4f9442-ff4e-4871-ae0f-4bd5d926c582', 'articles_management', '2024-06-17 20:27:17', '2024-06-17 20:27:17', '9c4f9442-fee9-4e8f-b39a-d40bdb28ad63'),
('9c4f9442-ff66-4575-afd8-d8cdfa19a06e', 'online_orders', '2024-06-17 20:27:17', '2024-06-17 20:27:17', '9c4f9442-fee9-4e8f-b39a-d40bdb28ad63'),
('9c4f9442-ff7e-4dac-b829-8597c7dc4836', 'take_away_management', '2024-06-17 20:27:17', '2024-06-17 20:27:17', '9c4f9442-fee9-4e8f-b39a-d40bdb28ad63'),
('9c4f9442-ff95-409f-bdb7-5c4790391513', 'stocks_management', '2024-06-17 20:27:17', '2024-06-17 20:27:17', '9c4f9442-fee9-4e8f-b39a-d40bdb28ad63'),
('9c4f9442-ffaa-410a-b918-84a6527723c1', 'stocks_alerts', '2024-06-17 20:27:17', '2024-06-17 20:27:17', '9c4f9442-fee9-4e8f-b39a-d40bdb28ad63'),
('9c4f9443-0014-4152-8bcf-d0f04d83e9fa', 'shop_monitoring', '2024-06-17 20:27:17', '2024-06-17 20:27:17', '9c4f9442-fffb-4926-ba0e-e871c0ba7cdf');

INSERT INTO `pack_prices` (`id`, `price`, `country`, `stripe_id`, `currency`, `created_at`, `updated_at`, `pack_id`) VALUES
('9c4f9442-ffc9-41a3-b26f-02f4219058ea', 20.00, 'BE', 'price_1PRb4jCoxzjWcobxJKyiQq9U', 'EUR', '2024-06-17 20:27:17', '2024-06-17 20:27:17', '9c4f9442-fee9-4e8f-b39a-d40bdb28ad63'),
('9c4f9442-ffe3-4f60-87d5-29976d741d1a', 25.00, 'LU', 'price_1PRb5dCoxzjWcobxXE0wZuxz', 'EUR', '2024-06-17 20:27:17', '2024-06-17 20:27:17', '9c4f9442-fee9-4e8f-b39a-d40bdb28ad63'),
('9c4f9443-002a-4acd-8afb-a16b9211e9e3', 5.00, 'BE', 'price_1PRb56CoxzjWcobxRbJpiZyL', 'EUR', '2024-06-17 20:27:17', '2024-06-17 20:27:17', '9c4f9442-fffb-4926-ba0e-e871c0ba7cdf'),
('9c4f9443-0041-408e-9352-8f2a3475d8ed', 5.00, 'LU', 'price_1PRb5zCoxzjWcobx4vli3bsD', 'EUR', '2024-06-17 20:27:17', '2024-06-17 20:27:17', '9c4f9442-fffb-4926-ba0e-e871c0ba7cdf');

INSERT INTO `packs` (`id`, `is_active`, `name`, `created_at`, `updated_at`) VALUES
('9c4f9442-fee9-4e8f-b39a-d40bdb28ad63', 1, 'base', '2024-06-17 20:27:17', '2024-06-17 20:27:17'),
('9c4f9442-fffb-4926-ba0e-e871c0ba7cdf', 1, 'monitoring', '2024-06-17 20:27:17', '2024-06-17 20:27:17');


/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
