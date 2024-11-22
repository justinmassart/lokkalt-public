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

INSERT INTO `categories` (`id`, `name`, `image`, `slug`, `created_at`, `updated_at`) VALUES
('9c4f942e-46e1-4c0d-98a3-6534dbb6ae76', 'alcohol', 'img/categories/category.jpg', 'alcohol', '2024-06-17 20:27:04', '2024-06-17 20:27:04'),
('9c4f942e-4724-4f16-8ea2-6d10abc73074', 'beauty-and-care', 'img/categories/category.jpg', 'beauty-and-care', '2024-06-17 20:27:04', '2024-06-17 20:27:04'),
('9c4f942e-4740-4a35-99ef-391a6806af82', 'books', 'img/categories/category.jpg', 'books', '2024-06-17 20:27:04', '2024-06-17 20:27:04'),
('9c4f942e-4759-4edf-a21e-1bf88c3fc40e', 'computers', 'img/categories/category.jpg', 'computers', '2024-06-17 20:27:04', '2024-06-17 20:27:04'),
('9c4f942e-4770-45c4-a0ff-bc5530ad344b', 'customized-products', 'img/categories/category.jpg', 'customized-products', '2024-06-17 20:27:04', '2024-06-17 20:27:04'),
('9c4f942e-4787-491e-b4e5-8d1db1efbdc0', 'decorations', 'img/categories/category.jpg', 'decorations', '2024-06-17 20:27:04', '2024-06-17 20:27:04'),
('9c4f942e-479d-4992-9a80-6242d8ab9ff9', 'electronics', 'img/categories/category.jpg', 'electronics', '2024-06-17 20:27:04', '2024-06-17 20:27:04'),
('9c4f942e-47b4-4459-8861-f9fe7fb32687', 'food', 'img/categories/category.jpg', 'food', '2024-06-17 20:27:04', '2024-06-17 20:27:04'),
('9c4f942e-47cb-4a6c-b7c8-3d30826941d7', 'home-and-garden', 'img/categories/category.jpg', 'home-and-garden', '2024-06-17 20:27:04', '2024-06-17 20:27:04'),
('9c4f942e-47e1-4ddb-ac41-00cfd0b67337', 'jewelry', 'img/categories/category.jpg', 'jewelry', '2024-06-17 20:27:04', '2024-06-17 20:27:04'),
('9c4f942e-47f8-4a0c-85e8-38a31e7da28a', 'sports-and-leisure', 'img/categories/category.jpg', 'sports-and-leisure', '2024-06-17 20:27:04', '2024-06-17 20:27:04'),
('9c4f942e-480e-468f-8b0c-3e9a9d80a483', 'toys', 'img/categories/category.jpg', 'toys', '2024-06-17 20:27:04', '2024-06-17 20:27:04'),
('9c4f942e-4824-49ef-a630-0f43e255e337', 'video-games', 'img/categories/category.jpg', 'video-games', '2024-06-17 20:27:04', '2024-06-17 20:27:04');

INSERT INTO `sub_categories` (`id`, `name`, `image`, `slug`, `created_at`, `updated_at`, `category_id`) VALUES
('9c4f942e-48f8-42c1-962f-da258a660e26', 'fruits', 'img/categories/category.jpg', 'fruits', '2024-06-17 20:27:04', '2024-06-17 20:27:04', '9c4f942e-47b4-4459-8861-f9fe7fb32687'),
('9c4f942e-4961-4b20-b222-592233b53392', 'vegetables', 'img/categories/category.jpg', 'vegetables', '2024-06-17 20:27:04', '2024-06-17 20:27:04', '9c4f942e-47b4-4459-8861-f9fe7fb32687'),
('9c4f942e-49a9-45e5-9a11-61e55d64b23e', 'dairy', 'img/categories/category.jpg', 'dairy', '2024-06-17 20:27:04', '2024-06-17 20:27:04', '9c4f942e-47b4-4459-8861-f9fe7fb32687'),
('9c4f942e-49f2-4653-9251-4801be4a28dc', 'skincare', 'img/categories/category.jpg', 'skincare', '2024-06-17 20:27:04', '2024-06-17 20:27:04', '9c4f942e-4724-4f16-8ea2-6d10abc73074'),
('9c4f942e-4a25-497a-8ee1-a5b91d116e1b', 'haircare', 'img/categories/category.jpg', 'haircare', '2024-06-17 20:27:04', '2024-06-17 20:27:04', '9c4f942e-4724-4f16-8ea2-6d10abc73074'),
('9c4f942e-4a56-467c-9b12-5db2805eb9fc', 'makeup', 'img/categories/category.jpg', 'makeup', '2024-06-17 20:27:04', '2024-06-17 20:27:04', '9c4f942e-4724-4f16-8ea2-6d10abc73074'),
('9c4f942e-4a7f-4a61-89cf-b298f64c5113', 'necklaces', 'img/categories/category.jpg', 'necklaces', '2024-06-17 20:27:04', '2024-06-17 20:27:04', '9c4f942e-47e1-4ddb-ac41-00cfd0b67337'),
('9c4f942e-4aa7-4f95-b93d-c19734056c65', 'earrings', 'img/categories/category.jpg', 'earrings', '2024-06-17 20:27:04', '2024-06-17 20:27:04', '9c4f942e-47e1-4ddb-ac41-00cfd0b67337'),
('9c4f942e-4acf-4c9d-84bc-3327a9675882', 'bracelets', 'img/categories/category.jpg', 'bracelets', '2024-06-17 20:27:04', '2024-06-17 20:27:04', '9c4f942e-47e1-4ddb-ac41-00cfd0b67337'),
('9c4f942e-4af5-4f2c-9cc9-224af983acb8', 'paintings', 'img/categories/category.jpg', 'paintings', '2024-06-17 20:27:04', '2024-06-17 20:27:04', '9c4f942e-4787-491e-b4e5-8d1db1efbdc0'),
('9c4f942e-4b33-4c2f-b3da-e75281562f62', 'vases', 'img/categories/category.jpg', 'vases', '2024-06-17 20:27:04', '2024-06-17 20:27:04', '9c4f942e-4787-491e-b4e5-8d1db1efbdc0'),
('9c4f942e-4b75-47cc-aaeb-9d2a9321b14b', 'candles', 'img/categories/category.jpg', 'candles', '2024-06-17 20:27:04', '2024-06-17 20:27:04', '9c4f942e-4787-491e-b4e5-8d1db1efbdc0'),
('9c4f942e-4b9e-45ba-8007-8d082ad790da', 'smartphones', 'img/categories/category.jpg', 'smartphones', '2024-06-17 20:27:04', '2024-06-17 20:27:04', '9c4f942e-479d-4992-9a80-6242d8ab9ff9'),
('9c4f942e-4bc7-4160-86ef-f5661d451af0', 'laptops', 'img/categories/category.jpg', 'laptops', '2024-06-17 20:27:04', '2024-06-17 20:27:04', '9c4f942e-479d-4992-9a80-6242d8ab9ff9'),
('9c4f942e-4bef-4e9f-9e95-4e167f4fd9c5', 'headphones', 'img/categories/category.jpg', 'headphones', '2024-06-17 20:27:04', '2024-06-17 20:27:04', '9c4f942e-479d-4992-9a80-6242d8ab9ff9'),
('9c4f942e-4c17-400b-a1ca-6379e6a26518', 'desktops', 'img/categories/category.jpg', 'desktops', '2024-06-17 20:27:04', '2024-06-17 20:27:04', '9c4f942e-4759-4edf-a21e-1bf88c3fc40e'),
('9c4f942e-4c3d-461d-a19c-1fba8c435329', 'laptops', 'img/categories/category.jpg', 'laptops', '2024-06-17 20:27:04', '2024-06-17 20:27:04', '9c4f942e-4759-4edf-a21e-1bf88c3fc40e'),
('9c4f942e-4c63-4586-ac25-25627b0c12b5', 'accessories', 'img/categories/category.jpg', 'accessories', '2024-06-17 20:27:04', '2024-06-17 20:27:04', '9c4f942e-4759-4edf-a21e-1bf88c3fc40e'),
('9c4f942e-4c8b-4546-bf93-872c285034ba', 'action', 'img/categories/category.jpg', 'action', '2024-06-17 20:27:04', '2024-06-17 20:27:04', '9c4f942e-4824-49ef-a630-0f43e255e337'),
('9c4f942e-4cb2-40b6-9a04-5245af7c5ae1', 'adventure', 'img/categories/category.jpg', 'adventure', '2024-06-17 20:27:04', '2024-06-17 20:27:04', '9c4f942e-4824-49ef-a630-0f43e255e337'),
('9c4f942e-4cd9-4575-a1a9-a10a25f984a7', 'puzzle', 'img/categories/category.jpg', 'puzzle', '2024-06-17 20:27:04', '2024-06-17 20:27:04', '9c4f942e-4824-49ef-a630-0f43e255e337'),
('9c4f942e-4cff-417b-8e75-b9e527e8bde6', 'dolls', 'img/categories/category.jpg', 'dolls', '2024-06-17 20:27:04', '2024-06-17 20:27:04', '9c4f942e-480e-468f-8b0c-3e9a9d80a483'),
('9c4f942e-4d25-4d16-88fb-041ad350d25e', 'cars', 'img/categories/category.jpg', 'cars', '2024-06-17 20:27:04', '2024-06-17 20:27:04', '9c4f942e-480e-468f-8b0c-3e9a9d80a483'),
('9c4f942e-4d4a-4375-ad93-5a63e55f34f9', 'building-blocks', 'img/categories/category.jpg', 'building-blocks', '2024-06-17 20:27:04', '2024-06-17 20:27:04', '9c4f942e-480e-468f-8b0c-3e9a9d80a483'),
('9c4f942e-4d71-4424-8f40-7dd837be9738', 'fiction', 'img/categories/category.jpg', 'fiction', '2024-06-17 20:27:04', '2024-06-17 20:27:04', '9c4f942e-4740-4a35-99ef-391a6806af82'),
('9c4f942e-4d96-483c-992b-980b7ed07945', 'non-fiction', 'img/categories/category.jpg', 'non-fiction', '2024-06-17 20:27:04', '2024-06-17 20:27:04', '9c4f942e-4740-4a35-99ef-391a6806af82'),
('9c4f942e-4dc9-46d7-a104-da14937af1af', 'poetry', 'img/categories/category.jpg', 'poetry', '2024-06-17 20:27:04', '2024-06-17 20:27:04', '9c4f942e-4740-4a35-99ef-391a6806af82'),
('9c4f942e-4df4-4978-b737-c5b097658392', 'furniture', 'img/categories/category.jpg', 'furniture', '2024-06-17 20:27:04', '2024-06-17 20:27:04', '9c4f942e-47cb-4a6c-b7c8-3d30826941d7'),
('9c4f942e-4e1f-444f-86ea-1e8790fc76e8', 'plants', 'img/categories/category.jpg', 'plants', '2024-06-17 20:27:04', '2024-06-17 20:27:04', '9c4f942e-47cb-4a6c-b7c8-3d30826941d7'),
('9c4f942e-4e46-4043-813c-2d7a2111ff54', 'outdoor-decor', 'img/categories/category.jpg', 'outdoor-decor', '2024-06-17 20:27:04', '2024-06-17 20:27:04', '9c4f942e-47cb-4a6c-b7c8-3d30826941d7'),
('9c4f942e-4e6b-47f0-bcbd-26925b1d2c67', 'personalized-gifts', 'img/categories/category.jpg', 'personalized-gifts', '2024-06-17 20:27:04', '2024-06-17 20:27:04', '9c4f942e-4770-45c4-a0ff-bc5530ad344b'),
('9c4f942e-4e90-4d71-bbd7-f16e38a89e20', 'custom-apparel', 'img/categories/category.jpg', 'custom-apparel', '2024-06-17 20:27:04', '2024-06-17 20:27:04', '9c4f942e-4770-45c4-a0ff-bc5530ad344b'),
('9c4f942e-4ebd-4c1d-b128-88811b56a655', 'engraved-items', 'img/categories/category.jpg', 'engraved-items', '2024-06-17 20:27:04', '2024-06-17 20:27:04', '9c4f942e-4770-45c4-a0ff-bc5530ad344b'),
('9c4f942e-4ef6-4388-aee7-8fdd0c739371', 'outdoor-gear', 'img/categories/category.jpg', 'outdoor-gear', '2024-06-17 20:27:04', '2024-06-17 20:27:04', '9c4f942e-47f8-4a0c-85e8-38a31e7da28a'),
('9c4f942e-4f2c-496b-8be4-5a84d269b801', 'fitness-equipment', 'img/categories/category.jpg', 'fitness-equipment', '2024-06-17 20:27:04', '2024-06-17 20:27:04', '9c4f942e-47f8-4a0c-85e8-38a31e7da28a'),
('9c4f942e-4f5b-49fb-8f1f-3634a32dff61', 'camping-supplies', 'img/categories/category.jpg', 'camping-supplies', '2024-06-17 20:27:04', '2024-06-17 20:27:04', '9c4f942e-47f8-4a0c-85e8-38a31e7da28a'),
('9c4f942e-4f8d-4e7c-923c-64223cb2ef01', 'wine', 'img/categories/category.jpg', 'wine', '2024-06-17 20:27:04', '2024-06-17 20:27:04', '9c4f942e-46e1-4c0d-98a3-6534dbb6ae76'),
('9c4f942e-4fca-4650-9f83-00843e458f7f', 'beer', 'img/categories/category.jpg', 'beer', '2024-06-17 20:27:04', '2024-06-17 20:27:04', '9c4f942e-46e1-4c0d-98a3-6534dbb6ae76'),
('9c4f942e-5008-437b-95b3-8bf5e21efddc', 'spirits', 'img/categories/category.jpg', 'spirits', '2024-06-17 20:27:04', '2024-06-17 20:27:04', '9c4f942e-46e1-4c0d-98a3-6534dbb6ae76');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
