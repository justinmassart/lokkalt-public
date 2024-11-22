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


INSERT INTO `article_images` (`id`, `created_at`, `updated_at`, `article_id`, `image_id`) VALUES
('9c519bce-69ef-4014-bb96-da31126351aa', '2024-06-18 20:40:02', '2024-06-18 20:40:02', '9c519bca-9bcf-4a1e-82a3-e30c67cce03a', '9c519bce-6900-4c4c-bcbd-c3abb01649d2'),
('9c519bcf-f9b5-47ae-9c80-f426feb71d4d', '2024-06-18 20:40:03', '2024-06-18 20:40:03', '9c519bca-9bcf-4a1e-82a3-e30c67cce03a', '9c519bcf-f8f5-4c5b-b069-e8af4b70b337'),
('9c519bd1-628d-4ac4-bcd6-7566bb7c3259', '2024-06-18 20:40:04', '2024-06-18 20:40:04', '9c519bca-9bcf-4a1e-82a3-e30c67cce03a', '9c519bd1-61d8-482b-a1ba-c29f8dc9f8f6'),
('9c519bd2-af4b-49c0-b36c-e1c2db9c77e1', '2024-06-18 20:40:05', '2024-06-18 20:40:05', '9c519bca-9bcf-4a1e-82a3-e30c67cce03a', '9c519bd2-ae8d-403f-a4fe-6a9788b852f6'),
('9c51a1e5-fbb8-43b9-a28a-f77fac8fa9e6', '2024-06-18 20:57:04', '2024-06-18 20:57:04', '9c51a1e3-574f-4235-bd2f-cb3e71d61dcd', '9c51a1e5-fad5-441d-9179-b1f45ec2245c'),
('9c51a1e7-1990-403b-9737-3ab236eee38d', '2024-06-18 20:57:05', '2024-06-18 20:57:05', '9c51a1e3-574f-4235-bd2f-cb3e71d61dcd', '9c51a1e7-18af-4c52-a5ed-a572112c45d7');

INSERT INTO `articles` (`id`, `reference`, `is_active`, `name`, `slug`, `description`, `details`, `created_at`, `updated_at`, `category_id`, `sub_category_id`) VALUES
('9c519bca-9bcf-4a1e-82a3-e30c67cce03a', 'hZQqvPlbBnKS', 1, 'Découverte de fromages', 'decouverte-de-fromages', 'Un superbe plateau de fromages pour découvrir les productions locales ! Avec ou sans charcuterie.', '{\"Type de fromages\": \"Bleu, crémeux, chèvre\", \"Nombre de fromages\": \"5\"}', '2024-06-18 20:40:00', '2024-06-18 20:40:00', '9c4f942e-47b4-4459-8861-f9fe7fb32687', '9c4f942e-49a9-45e5-9a11-61e55d64b23e'),
('9c51a1e3-574f-4235-bd2f-cb3e71d61dcd', 'd14ey2Jv25yH', 1, 'La Chouffe', 'la-chouffe', 'Une incroyable bière blonde parfaite pour l\'été, à consommer sans modération !', '{\"Alcool\": \"7%\", \"Couleur\": \"Blonde\", \"Brasseur\": \"La Chouffe\"}', '2024-06-18 20:57:03', '2024-06-18 20:57:03', '9c4f942e-46e1-4c0d-98a3-6534dbb6ae76', '9c4f942e-4fca-4650-9f83-00843e458f7f');

INSERT INTO `franchise_owners` (`id`, `created_at`, `updated_at`, `franchise_id`, `user_id`) VALUES
('9c518acb-ae1b-4d54-8a2c-21f3ac86d52e', '2024-06-18 19:52:28', '2024-06-18 19:52:28', '9c518acb-ac6c-401e-9538-9fb1c3a12e5a', '9c518acb-ab81-4b82-9b62-a34c46aefcc4');

INSERT INTO `franchise_packs` (`id`, `is_active`, `created_at`, `updated_at`, `franchise_id`, `pack_id`) VALUES
('9c5191c5-b48a-4ce1-831e-e1d30a232a5c', 1, '2024-06-18 20:11:59', '2024-06-18 20:12:11', '9c518acb-ac6c-401e-9538-9fb1c3a12e5a', '9c4f9442-fee9-4e8f-b39a-d40bdb28ad63'),
('9c5191c5-b59a-4cd7-a9ef-325b8f55ae79', 1, '2024-06-18 20:11:59', '2024-06-18 20:12:11', '9c518acb-ac6c-401e-9538-9fb1c3a12e5a', '9c4f9442-fffb-4926-ba0e-e871c0ba7cdf');

INSERT INTO `franchise_subscriptions` (`id`, `has_paid`, `customer_id`, `subscription_id`, `payment_id`, `stripe_status`, `stripe_price`, `trial_ends_at`, `created_at`, `updated_at`, `franchise_id`) VALUES
('9c5191c5-b15e-4dc0-a429-f69e4bddeb4c', 1, 'cus_QJlvyidFu3rj1f', 'sub_1PT8NmCoxzjWcobxygCiQNLM', 'pi_3PT8NmCoxzjWcobx1kUHca1l', 'active', 2500, NULL, '2024-06-18 20:11:59', '2024-06-18 20:12:11', '9c518acb-ac6c-401e-9538-9fb1c3a12e5a');

INSERT INTO `franchises` (`id`, `verified_at`, `name`, `email`, `phone`, `VAT`, `bank_account`, `country`, `city`, `postal_code`, `address`, `stripe_customer_id`, `created_at`, `updated_at`) VALUES
('9c518acb-ac6c-401e-9538-9fb1c3a12e5a', '2024-06-18 19:52:36', 'La Taverne', 'lataverne@mail.com', '', 'BE1579423694', NULL, 'BE', 'Louveigné', '4141', 'rue du pérréon 51, 4141 Louveigné', 'cus_QJlvyidFu3rj1f', '2024-06-18 19:52:28', '2024-06-18 20:11:58');

INSERT INTO `images` (`id`, `url`, `is_main_image`, `created_at`, `updated_at`) VALUES
('9c51930b-4db7-4dda-abd7-f5eb75bfa09e', '7cf98b9f-f7c6-4fb4-9fb4-0ce1f09209a2.webp', 0, '2024-06-18 20:15:32', '2024-06-18 20:15:32'),
('9c51930c-9346-430c-8933-f86b55a60a8b', 'b217d146-ba30-4de9-b5b8-f001259084d2.webp', 0, '2024-06-18 20:15:33', '2024-06-18 20:23:15'),
('9c51930d-b8ec-466d-9f52-a2f724fc993c', '688f5908-57df-4c45-b7c8-1e8246348b6f.webp', 1, '2024-06-18 20:15:34', '2024-06-18 20:23:21'),
('9c51930f-0a4a-4fff-b94f-e71012f327ba', '6102f813-281c-4ab1-93e3-284c4e4ec200.webp', 0, '2024-06-18 20:15:35', '2024-06-18 20:23:15'),
('9c51969c-dcf1-4ffd-8968-1eb47f6f6170', 'd36c3235-fed7-47e3-ae7e-f44e49383419.webp', 0, '2024-06-18 20:25:31', '2024-06-18 20:25:31'),
('9c51969e-5330-4e98-be7c-c3c53e2772d4', 'ab6f0bfd-8be8-40f3-8cb2-5fc2283dee2a.webp', 0, '2024-06-18 20:25:32', '2024-06-18 20:25:32'),
('9c519bce-6900-4c4c-bcbd-c3abb01649d2', '0a56c0b3-aadd-425a-a383-534fc93ac604.webp', 0, '2024-06-18 20:40:02', '2024-06-18 20:40:02'),
('9c519bcf-f8f5-4c5b-b069-e8af4b70b337', '1ce2801a-9656-4367-bc78-769f7189c5c4.webp', 0, '2024-06-18 20:40:03', '2024-06-18 20:40:03'),
('9c519bd1-61d8-482b-a1ba-c29f8dc9f8f6', 'c09bcc96-bd27-42af-a38f-42f9aef1f219.webp', 0, '2024-06-18 20:40:04', '2024-06-18 20:40:04'),
('9c519bd2-ae8d-403f-a4fe-6a9788b852f6', 'cea3ddc1-741c-4938-921e-7d6a2024d75d.webp', 0, '2024-06-18 20:40:05', '2024-06-18 20:40:05'),
('9c519bd3-ddb4-45df-b512-aace068a968b', '17913266-854a-4e81-a1a5-5af86d441c69.webp', 0, '2024-06-18 20:40:06', '2024-06-18 20:40:06'),
('9c519bd4-fcbd-442c-a64e-78b6330583a8', '0b5b01e8-c15e-47b3-b54c-62520e332cf8.webp', 0, '2024-06-18 20:40:07', '2024-06-18 20:40:07'),
('9c519bd6-270c-4f2b-b038-78e297edd7a4', 'fa328e94-ac02-4f3c-b734-8ed4c17b7ba3.webp', 0, '2024-06-18 20:40:07', '2024-06-18 20:40:07'),
('9c519bd7-49cd-4745-981d-38c677ad5d94', '35da8d48-1c7c-4b1c-abe5-c22b609f462a.webp', 0, '2024-06-18 20:40:08', '2024-06-18 20:40:08'),
('9c51a1e5-fad5-441d-9179-b1f45ec2245c', '887ba171-e4e9-4ccb-aa6f-61a3e676af43.webp', 0, '2024-06-18 20:57:04', '2024-06-18 20:57:04'),
('9c51a1e7-18af-4c52-a5ed-a572112c45d7', '25912cdc-d5c6-46ec-af9f-d89dfc97938e.webp', 0, '2024-06-18 20:57:05', '2024-06-18 20:57:05'),
('9c51a1e8-0d97-49c2-966b-1d6ba9bf6c72', 'be3110c1-28c9-4306-8536-b1f55254f15d.webp', 0, '2024-06-18 20:57:06', '2024-06-18 20:57:06'),
('9c51a1e8-db5a-4389-851e-0d1eead32ea5', 'df6c7f18-bed8-43e3-8ef8-600bb25198cb.webp', 0, '2024-06-18 20:57:06', '2024-06-18 20:57:06');

INSERT INTO `shop_articles` (`id`, `hashed_id`, `created_at`, `updated_at`, `shop_id`, `article_id`, `variant_id`) VALUES
('458cf43e-baa6-4b4c-8936-54be37d93b10', '48300bb510218ffaa1deaf8390d5ee88bf987fa0595aeac2295b888c0e3eff30', '2024-06-18 20:40:01', '2024-06-18 20:40:01', '9c519309-8272-470e-929c-2411edcf5e38', '9c519bca-9bcf-4a1e-82a3-e30c67cce03a', '9c519bca-a2ec-451b-b724-aa372af945dd'),
('4a9574b1-6314-4adb-bd51-81301791628f', 'c28ae8f5b37394217bdae0c32010802bcddb254636ecd26310bc7d4237f562ef', '2024-06-18 20:40:01', '2024-06-18 20:40:01', '9c51969b-1b8e-4919-b3b5-bb35b60096f7', '9c519bca-9bcf-4a1e-82a3-e30c67cce03a', '9c519bcc-ec97-4086-a4ad-f2ea7b6de390'),
('b7c6c09a-b3b6-40d1-83d0-3c175389d484', '27208fc13e0ba360bac4238302609ac5b5c57046ec74e660124a0e9c894318be', '2024-06-18 20:57:03', '2024-06-18 20:57:03', '9c519309-8272-470e-929c-2411edcf5e38', '9c51a1e3-574f-4235-bd2f-cb3e71d61dcd', '9c51a1e4-8229-483a-a0ef-4e8e6605dbfc'),
('c5e5c86a-a9c2-4075-9dbc-f7aacc752369', '67e41d62293fb622c9352eccfc1e2f8613c4215f90ebc3ae9122b12500906720', '2024-06-18 20:40:01', '2024-06-18 20:40:01', '9c519309-8272-470e-929c-2411edcf5e38', '9c519bca-9bcf-4a1e-82a3-e30c67cce03a', '9c519bcc-ec97-4086-a4ad-f2ea7b6de390'),
('df846619-3e96-46b4-9d93-9958c7bfd892', 'd4a344b4fb8e478928683fabe5c8649d01abb2d05fab9b318ddb23a829339252', '2024-06-18 20:40:01', '2024-06-18 20:40:01', '9c51969b-1b8e-4919-b3b5-bb35b60096f7', '9c519bca-9bcf-4a1e-82a3-e30c67cce03a', '9c519bca-a2ec-451b-b724-aa372af945dd');

INSERT INTO `shop_images` (`id`, `created_at`, `updated_at`, `shop_id`, `image_id`) VALUES
('9c51930b-4fbf-41b6-b8b5-6ebeae7002d7', '2024-06-18 20:15:32', '2024-06-18 20:15:32', '9c519309-8272-470e-929c-2411edcf5e38', '9c51930b-4db7-4dda-abd7-f5eb75bfa09e'),
('9c51930c-940c-45d5-8427-1a75fa7f6f02', '2024-06-18 20:15:33', '2024-06-18 20:15:33', '9c519309-8272-470e-929c-2411edcf5e38', '9c51930c-9346-430c-8933-f86b55a60a8b'),
('9c51930d-ba45-4723-af1d-d3e8de29c8c8', '2024-06-18 20:15:34', '2024-06-18 20:15:34', '9c519309-8272-470e-929c-2411edcf5e38', '9c51930d-b8ec-466d-9f52-a2f724fc993c'),
('9c51930f-0b10-4456-a835-47263b016fa6', '2024-06-18 20:15:35', '2024-06-18 20:15:35', '9c519309-8272-470e-929c-2411edcf5e38', '9c51930f-0a4a-4fff-b94f-e71012f327ba'),
('9c51969c-ddd7-41bf-8749-53f3ed1810f6', '2024-06-18 20:25:31', '2024-06-18 20:25:31', '9c51969b-1b8e-4919-b3b5-bb35b60096f7', '9c51969c-dcf1-4ffd-8968-1eb47f6f6170'),
('9c51969e-5401-484f-92f7-4cc0c3a20909', '2024-06-18 20:25:32', '2024-06-18 20:25:32', '9c51969b-1b8e-4919-b3b5-bb35b60096f7', '9c51969e-5330-4e98-be7c-c3c53e2772d4');

INSERT INTO `shop_owners` (`id`, `created_at`, `updated_at`, `shop_id`, `user_id`) VALUES
('9c519309-8747-4b40-837b-5e0f3e8e956c', '2024-06-18 20:15:31', '2024-06-18 20:15:31', '9c519309-8272-470e-929c-2411edcf5e38', '9c518acb-ab81-4b82-9b62-a34c46aefcc4'),
('9c51969b-1d96-44e9-818b-41066cf11a23', '2024-06-18 20:25:30', '2024-06-18 20:25:30', '9c51969b-1b8e-4919-b3b5-bb35b60096f7', '9c518acb-ab81-4b82-9b62-a34c46aefcc4');

INSERT INTO `shops` (`id`, `is_active`, `name`, `email`, `phone`, `description`, `country`, `city`, `postal_code`, `address`, `VAT`, `bank_account`, `opening_hours`, `slug`, `created_at`, `updated_at`, `franchise_id`) VALUES
('9c519309-8272-470e-929c-2411edcf5e38', 1, 'La Taverne', 'lataverne@mail.com', NULL, 'La plus belle taverne du coin ! Avec nos bons produits locaux, il y en a pour tous les goûts !', 'BE', 'Louveigné', 4141, 'rue du pérréon 51, 4141 Louveigné, Belgique', 'BE1452367813', 'BE45732070871548', '{\"friday\": [{\"to\": \"18:30\", \"from\": \"08:30\"}], \"monday\": [{\"to\": \"12:30\", \"from\": \"08:30\"}], \"sunday\": [{\"to\": null, \"from\": null}], \"tuesday\": [{\"to\": \"12:30\", \"from\": \"08:30\"}, {\"to\": \"17:30\", \"from\": \"13:30\"}], \"saturday\": [{\"to\": \"12:30\", \"from\": \"10:30\"}], \"thursday\": [{\"to\": \"17:30\", \"from\": \"12:30\"}], \"wednesday\": [{\"to\": null, \"from\": null}]}', 'be-4141', '2024-06-18 20:15:31', '2024-06-19 09:49:16', '9c518acb-ac6c-401e-9538-9fb1c3a12e5a'),
('9c51969b-1b8e-4919-b3b5-bb35b60096f7', 1, 'La Taverne', 'lataverne+chaudfontaine@mail.com', NULL, 'La plus belle taverne du coin ! Avec nos bons produits locaux, il y en a pour tous les goûts ! À Chaudfontaine.', 'BE', 'Chaudfontaine', 4052, 'rue de louveigné 12, 4052 Chaudfontaine, Belgique', 'BE1654832649', 'BE45732070875134', '{\"friday\": [{\"to\": \"18:30\", \"from\": \"08:30\"}], \"monday\": [{\"to\": \"12:30\", \"from\": \"08:30\"}], \"sunday\": [{\"to\": null, \"from\": null}], \"tuesday\": [{\"to\": \"12:30\", \"from\": \"08:30\"}, {\"to\": \"17:30\", \"from\": \"13:30\"}], \"saturday\": [{\"to\": \"12:30\", \"from\": \"10:30\"}], \"thursday\": [{\"to\": \"17:30\", \"from\": \"12:30\"}], \"wednesday\": [{\"to\": null, \"from\": null}]}', 'be-4052-la-taverne', '2024-06-18 20:25:30', '2024-06-18 20:25:30', '9c518acb-ac6c-401e-9538-9fb1c3a12e5a');

INSERT INTO `stock_operations` (`id`, `stock_before`, `stock_after`, `operation`, `comment`, `created_at`, `updated_at`, `stock_id`, `user_id`) VALUES
('9c51a295-0434-425f-92aa-af4c17248cc5', 0, 50, '+50', 'Premier ajout de stock', '2024-06-18 20:58:59', '2024-06-18 20:58:59', '9c51a1e4-89e6-4b07-9ff6-9250c570cb60', '9c518acb-ab81-4b82-9b62-a34c46aefcc4'),
('9c51a2dd-5c40-40c6-a047-ae9421dd5930', 0, 20, '+20', NULL, '2024-06-18 20:59:47', '2024-06-18 20:59:47', '9c519bcc-f7ea-40fe-bda9-a5d0942ab79d', '9c518acb-ab81-4b82-9b62-a34c46aefcc4'),
('9c51a2e4-a804-4768-a323-bba01fab010b', 0, 20, '+20', NULL, '2024-06-18 20:59:51', '2024-06-18 20:59:51', '9c519bcc-f705-4f97-9b1d-a460310af072', '9c518acb-ab81-4b82-9b62-a34c46aefcc4');

INSERT INTO `stocks` (`id`, `quantity`, `status`, `limited_stock_below`, `created_at`, `updated_at`, `shop_article_id`) VALUES
('9c519bcc-f409-4049-9605-cf75cd5c5b33', 0, 'out', 5, '2024-06-18 20:40:01', '2024-06-18 20:40:01', '458cf43e-baa6-4b4c-8936-54be37d93b10'),
('9c519bcc-f536-48bd-8daf-fd210c73eaac', 0, 'out', 5, '2024-06-18 20:40:01', '2024-06-18 20:40:01', 'c5e5c86a-a9c2-4075-9dbc-f7aacc752369'),
('9c519bcc-f705-4f97-9b1d-a460310af072', 20, 'in', 5, '2024-06-18 20:40:01', '2024-06-18 20:59:51', 'df846619-3e96-46b4-9d93-9958c7bfd892'),
('9c519bcc-f7ea-40fe-bda9-a5d0942ab79d', 20, 'in', 5, '2024-06-18 20:40:01', '2024-06-18 20:59:46', '4a9574b1-6314-4adb-bd51-81301791628f'),
('9c51a1e4-89e6-4b07-9ff6-9250c570cb60', 50, 'in', 10, '2024-06-18 20:57:03', '2024-06-18 20:58:59', 'b7c6c09a-b3b6-40d1-83d0-3c175389d484');

INSERT INTO `users` (`id`, `role`, `firstname`, `lastname`, `full_name`, `slug`, `email`, `email_verified_at`, `country`, `phone`, `address`, `password`, `remember_token`, `created_at`, `updated_at`, `stripe_id`, `pm_type`, `pm_last_four`, `trial_ends_at`) VALUES
('9c518acb-ab81-4b82-9b62-a34c46aefcc4', 'seller', 'Godefroy', 'de Montmirail', 'Godefroy de Montmirail', 'godefroy-de-montmirail#A5eCBy', 'godefroy@mail.com', '2024-06-18 19:52:36', 'BE', NULL, 'Rue du Pérréon 51, Sprimont, Belgique', '$2y$12$py6nN6/PRQAo9DWRVvK0Fu1VHiSBwBx0InM9f5Ba9y2vfdb4Fn6oC', NULL, '2024-06-18 19:52:28', '2024-06-18 19:52:36', NULL, NULL, NULL, NULL),
('9c51a52c-e817-4c33-897b-28b43b400e30', 'user', 'Justin', 'Massart', 'Justin Massart', 'justin-massart#zovblQ', 'justinlokkalt@gmail.com', '2024-06-18 21:06:25', 'BE', NULL, NULL, '$2y$12$e0kf2JyErAcr3iFSmNIx/.Tmks9QZw.a5gf8wrefebQMkaGes1zii', NULL, '2024-06-18 21:06:14', '2024-06-18 21:06:25', NULL, NULL, NULL, NULL);

INSERT INTO `variant_images` (`id`, `created_at`, `updated_at`, `variant_id`, `image_id`) VALUES
('9c519bd3-de86-4d6a-a989-72fc11a15607', '2024-06-18 20:40:06', '2024-06-18 20:40:06', '9c519bcc-ec97-4086-a4ad-f2ea7b6de390', '9c519bd3-ddb4-45df-b512-aace068a968b'),
('9c519bd4-fd80-4916-b47f-bcd09e8038ee', '2024-06-18 20:40:07', '2024-06-18 20:40:07', '9c519bcc-ec97-4086-a4ad-f2ea7b6de390', '9c519bd4-fcbd-442c-a64e-78b6330583a8'),
('9c519bd6-27c4-42b2-9d2f-ecd1cb9fc694', '2024-06-18 20:40:07', '2024-06-18 20:40:07', '9c519bcc-ec97-4086-a4ad-f2ea7b6de390', '9c519bd6-270c-4f2b-b038-78e297edd7a4'),
('9c519bd7-4a90-4f13-abb8-da02dfb86995', '2024-06-18 20:40:08', '2024-06-18 20:40:08', '9c519bcc-ec97-4086-a4ad-f2ea7b6de390', '9c519bd7-49cd-4745-981d-38c677ad5d94'),
('9c51a1e8-0e6d-449f-b336-af0136600b9a', '2024-06-18 20:57:06', '2024-06-18 20:57:06', '9c51a1e4-8229-483a-a0ef-4e8e6605dbfc', '9c51a1e8-0d97-49c2-966b-1d6ba9bf6c72'),
('9c51a1e8-dc38-4048-bdc2-cddeff5002e0', '2024-06-18 20:57:06', '2024-06-18 20:57:06', '9c51a1e4-8229-483a-a0ef-4e8e6605dbfc', '9c51a1e8-db5a-4389-851e-0d1eead32ea5');

INSERT INTO `variant_prices` (`id`, `price`, `currency`, `per`, `created_at`, `updated_at`, `variant_id`) VALUES
('9c519bca-a4f9-415c-8d09-6b3cfe31bc2c', 10.00, 'EUR', 'unit', '2024-06-18 20:40:00', '2024-06-18 20:40:00', '9c519bca-a2ec-451b-b724-aa372af945dd'),
('9c519bcc-f1bd-423a-b6bb-ec1b1a2ee84b', 15.00, 'EUR', 'unit', '2024-06-18 20:40:01', '2024-06-18 20:40:01', '9c519bcc-ec97-4086-a4ad-f2ea7b6de390'),
('9c51a1e4-86dc-4b1c-ab3c-18f59764b386', 1.80, 'EUR', 'unit', '2024-06-18 20:57:03', '2024-06-18 20:57:03', '9c51a1e4-8229-483a-a0ef-4e8e6605dbfc');

INSERT INTO `variants` (`id`, `reference`, `name`, `slug`, `description`, `details`, `is_visible`, `created_at`, `updated_at`, `article_id`) VALUES
('9c519bca-a2ec-451b-b724-aa372af945dd', 'Q4qvzfE2kdUE', 'Sans charcuterie', 'sans-charcuterie', 'Un superbe plateau de fromages pour découvrir les productions locales ! Sans charcuterie.', '{\"Charcuterie\": \"Non\"}', 1, '2024-06-18 20:40:00', '2024-06-18 20:40:00', '9c519bca-9bcf-4a1e-82a3-e30c67cce03a'),
('9c519bcc-ec97-4086-a4ad-f2ea7b6de390', 'dAqN6nYGk9FB', 'Avec charcuterie', 'avec-charcuterie', 'Un superbe plateau de fromages pour découvrir les productions locales ! Avec charcuterie.', '{\"Charcuterie\": \"Oui\"}', 1, '2024-06-18 20:40:01', '2024-06-18 20:40:01', '9c519bca-9bcf-4a1e-82a3-e30c67cce03a'),
('9c51a1e4-8229-483a-a0ef-4e8e6605dbfc', 'MARRIYpbVmSJ', '33cl', '33cl', NULL, '{\"Volume\": \"33cl\"}', 1, '2024-06-18 20:57:03', '2024-06-18 20:57:03', '9c51a1e3-574f-4235-bd2f-cb3e71d61dcd');



/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
