-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Апр 11 2023 г., 16:10
-- Версия сервера: 8.0.30
-- Версия PHP: 8.1.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `clearfield_test`
--

-- --------------------------------------------------------

--
-- Структура таблицы `country`
--

CREATE TABLE `country` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `country`
--

INSERT INTO `country` (`id`, `title`, `phone_code`, `created_at`, `updated_at`) VALUES
(1, 'Croatia', '+45', '2023-04-11 12:59:51', '2023-04-11 12:59:51'),
(2, 'Ghana', '+84', '2023-04-11 12:59:51', '2023-04-11 12:59:51'),
(3, 'Guyana', '+54', '2023-04-11 12:59:51', '2023-04-11 12:59:51'),
(4, 'Sri Lanka', '+24', '2023-04-11 12:59:51', '2023-04-11 12:59:51'),
(5, 'Sri Lanka', '+31', '2023-04-11 12:59:51', '2023-04-11 12:59:51'),
(6, 'Swaziland', '+42', '2023-04-11 12:59:51', '2023-04-11 12:59:51'),
(7, 'Iraq', '+53', '2023-04-11 12:59:51', '2023-04-11 12:59:51'),
(8, 'Nicaragua', '+56', '2023-04-11 12:59:51', '2023-04-11 12:59:51'),
(9, 'Malawi', '+20', '2023-04-11 12:59:51', '2023-04-11 12:59:51'),
(10, 'Uruguay', '+57', '2023-04-11 12:59:51', '2023-04-11 12:59:51'),
(11, 'Australia', '+34', '2023-04-11 12:59:51', '2023-04-11 12:59:51'),
(12, 'Finland', '+44', '2023-04-11 12:59:51', '2023-04-11 12:59:51'),
(13, 'Luxembourg', '+15', '2023-04-11 12:59:51', '2023-04-11 12:59:51'),
(14, 'Suriname', '+79', '2023-04-11 12:59:51', '2023-04-11 12:59:51'),
(15, 'Slovakia', '+59', '2023-04-11 12:59:51', '2023-04-11 12:59:51'),
(16, 'Zambia', '+98', '2023-04-11 12:59:51', '2023-04-11 12:59:51'),
(17, 'France', '+55', '2023-04-11 12:59:51', '2023-04-11 12:59:51'),
(18, 'Austria', '+80', '2023-04-11 12:59:51', '2023-04-11 12:59:51'),
(19, 'Canada', '+51', '2023-04-11 12:59:51', '2023-04-11 12:59:51'),
(20, 'Ukraine', '+37', '2023-04-11 12:59:51', '2023-04-11 12:59:51'),
(21, 'Guatemala', '+17', '2023-04-11 12:59:51', '2023-04-11 12:59:51'),
(22, 'Guyana', '+28', '2023-04-11 12:59:51', '2023-04-11 12:59:51'),
(23, 'Swaziland', '+11', '2023-04-11 12:59:51', '2023-04-11 12:59:51'),
(24, 'Denmark', '+23', '2023-04-11 12:59:51', '2023-04-11 12:59:51'),
(25, 'Bangladesh', '+62', '2023-04-11 12:59:51', '2023-04-11 12:59:51'),
(26, 'Denmark', '+10', '2023-04-11 12:59:51', '2023-04-11 12:59:51'),
(27, 'Mongolia', '+47', '2023-04-11 12:59:51', '2023-04-11 12:59:51'),
(28, 'Italy', '+21', '2023-04-11 12:59:51', '2023-04-11 12:59:51'),
(29, 'Albania', '+65', '2023-04-11 12:59:51', '2023-04-11 12:59:51'),
(30, 'Sierra Leone', '+69', '2023-04-11 12:59:51', '2023-04-11 12:59:51'),
(31, 'Saudi Arabia', '+27', '2023-04-11 12:59:51', '2023-04-11 12:59:51'),
(32, 'Bangladesh', '+40', '2023-04-11 12:59:51', '2023-04-11 12:59:51'),
(33, 'Moldova', '+38', '2023-04-11 12:59:51', '2023-04-11 12:59:51'),
(34, 'Botswana', '+86', '2023-04-11 12:59:51', '2023-04-11 12:59:51'),
(35, 'Nigeria', '+46', '2023-04-11 12:59:51', '2023-04-11 12:59:51'),
(36, 'New Zealand', '+71', '2023-04-11 12:59:51', '2023-04-11 12:59:51'),
(37, 'Tunisia', '+36', '2023-04-11 12:59:51', '2023-04-11 12:59:51'),
(38, 'Bahrain', '+14', '2023-04-11 12:59:51', '2023-04-11 12:59:51'),
(39, 'Brazil', '+78', '2023-04-11 12:59:51', '2023-04-11 12:59:51'),
(40, 'Italy', '+25', '2023-04-11 12:59:51', '2023-04-11 12:59:51'),
(41, 'Panama', '+60', '2023-04-11 12:59:51', '2023-04-11 12:59:51'),
(42, 'Uruguay', '+66', '2023-04-11 12:59:51', '2023-04-11 12:59:51'),
(43, 'Israel', '+95', '2023-04-11 12:59:51', '2023-04-11 12:59:51'),
(44, 'Afghanistan', '+50', '2023-04-11 12:59:51', '2023-04-11 12:59:51'),
(45, 'Argentina', '+32', '2023-04-11 12:59:51', '2023-04-11 12:59:51'),
(46, 'Netherlands', '+68', '2023-04-11 12:59:51', '2023-04-11 12:59:51'),
(47, 'Cuba', '+88', '2023-04-11 12:59:51', '2023-04-11 12:59:51'),
(48, 'Fiji', '+94', '2023-04-11 12:59:51', '2023-04-11 12:59:51'),
(49, 'Malawi', '+16', '2023-04-11 12:59:51', '2023-04-11 12:59:51'),
(50, 'Malaysia', '+87', '2023-04-11 12:59:51', '2023-04-11 12:59:51');

-- --------------------------------------------------------

--
-- Структура таблицы `doctype`
--

CREATE TABLE `doctype` (
  `id` bigint UNSIGNED NOT NULL,
  `docname` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `doctype`
--

INSERT INTO `doctype` (`id`, `docname`, `created_at`, `updated_at`) VALUES
(1, 'Quia at doloribus quia praesentium.', '2023-04-11 12:59:51', '2023-04-11 12:59:51'),
(2, 'Nesciunt cum.', '2023-04-11 12:59:51', '2023-04-11 12:59:51'),
(3, 'Laudantium alias id iusto.', '2023-04-11 12:59:51', '2023-04-11 12:59:51'),
(4, 'Molestiae labore natus cumque.', '2023-04-11 12:59:51', '2023-04-11 12:59:51'),
(5, 'Blanditiis et nostrum.', '2023-04-11 12:59:51', '2023-04-11 12:59:51'),
(6, 'Fugit ad tempora ut exercitationem.', '2023-04-11 12:59:51', '2023-04-11 12:59:51'),
(7, 'Voluptas cumque velit voluptas qui.', '2023-04-11 12:59:51', '2023-04-11 12:59:51'),
(8, 'Illum maxime et.', '2023-04-11 12:59:51', '2023-04-11 12:59:51'),
(9, 'Eveniet atque aut.', '2023-04-11 12:59:51', '2023-04-11 12:59:51'),
(10, 'Recusandae voluptatibus ab officia.', '2023-04-11 12:59:51', '2023-04-11 12:59:51'),
(11, 'Iste sequi assumenda reiciendis.', '2023-04-11 12:59:51', '2023-04-11 12:59:51'),
(12, 'Suscipit rerum.', '2023-04-11 12:59:51', '2023-04-11 12:59:51'),
(13, 'At adipisci id tempore qui sit.', '2023-04-11 12:59:51', '2023-04-11 12:59:51'),
(14, 'Animi consequuntur omnis accusantium.', '2023-04-11 12:59:51', '2023-04-11 12:59:51'),
(15, 'Odio voluptatem ut vero dolore odit.', '2023-04-11 12:59:51', '2023-04-11 12:59:51'),
(16, 'Accusamus vero non fugiat earum.', '2023-04-11 12:59:51', '2023-04-11 12:59:51'),
(17, 'Ad ut quaerat explicabo vel quia unde.', '2023-04-11 12:59:51', '2023-04-11 12:59:51'),
(18, 'Quibusdam sequi ut eum.', '2023-04-11 12:59:51', '2023-04-11 12:59:51'),
(19, 'Aut sapiente eius.', '2023-04-11 12:59:51', '2023-04-11 12:59:51'),
(20, 'Minus sit nesciunt doloribus ab.', '2023-04-11 12:59:51', '2023-04-11 12:59:51'),
(21, 'Est a.', '2023-04-11 12:59:51', '2023-04-11 12:59:51'),
(22, 'Culpa deleniti et sunt sed a deserunt.', '2023-04-11 12:59:51', '2023-04-11 12:59:51'),
(23, 'Cumque voluptatem quo molestiae.', '2023-04-11 12:59:51', '2023-04-11 12:59:51'),
(24, 'Autem doloremque.', '2023-04-11 12:59:51', '2023-04-11 12:59:51'),
(25, 'Consequatur cum mollitia rerum.', '2023-04-11 12:59:51', '2023-04-11 12:59:51'),
(26, 'Porro at sed.', '2023-04-11 12:59:51', '2023-04-11 12:59:51'),
(27, 'Numquam deserunt incidunt est id ut.', '2023-04-11 12:59:51', '2023-04-11 12:59:51'),
(28, 'Deleniti dolorum culpa molestiae.', '2023-04-11 12:59:51', '2023-04-11 12:59:51'),
(29, 'Dolores animi ex sit.', '2023-04-11 12:59:51', '2023-04-11 12:59:51'),
(30, 'Optio deleniti unde veniam corporis.', '2023-04-11 12:59:51', '2023-04-11 12:59:51'),
(31, 'Inventore eveniet dolores obcaecati.', '2023-04-11 12:59:51', '2023-04-11 12:59:51'),
(32, 'Soluta et autem.', '2023-04-11 12:59:51', '2023-04-11 12:59:51'),
(33, 'Vel beatae unde voluptatibus.', '2023-04-11 12:59:51', '2023-04-11 12:59:51'),
(34, 'Aut error quaerat est odio iste in.', '2023-04-11 12:59:51', '2023-04-11 12:59:51'),
(35, 'Eos ex aliquam ea natus quo magni ea.', '2023-04-11 12:59:51', '2023-04-11 12:59:51'),
(36, 'Optio qui praesentium quia.', '2023-04-11 12:59:51', '2023-04-11 12:59:51'),
(37, 'Sapiente et vel et sed reprehenderit.', '2023-04-11 12:59:51', '2023-04-11 12:59:51'),
(38, 'Vitae aut officiis quasi porro.', '2023-04-11 12:59:51', '2023-04-11 12:59:51'),
(39, 'Consequatur voluptate eius mollitia.', '2023-04-11 12:59:51', '2023-04-11 12:59:51'),
(40, 'Similique veniam nisi error.', '2023-04-11 12:59:51', '2023-04-11 12:59:51'),
(41, 'Ipsum quia.', '2023-04-11 12:59:51', '2023-04-11 12:59:51'),
(42, 'Tempora consequatur et consequatur.', '2023-04-11 12:59:51', '2023-04-11 12:59:51'),
(43, 'Qui nam est.', '2023-04-11 12:59:51', '2023-04-11 12:59:51'),
(44, 'Incidunt neque obcaecati.', '2023-04-11 12:59:51', '2023-04-11 12:59:51'),
(45, 'Ea facere.', '2023-04-11 12:59:51', '2023-04-11 12:59:51'),
(46, 'Et id cum consequatur fugit ex.', '2023-04-11 12:59:51', '2023-04-11 12:59:51'),
(47, 'Cumque dolor alias sit voluptate.', '2023-04-11 12:59:51', '2023-04-11 12:59:51'),
(48, 'Quo magnam totam sit asperiores qui.', '2023-04-11 12:59:51', '2023-04-11 12:59:51'),
(49, 'Ratione voluptatem aut.', '2023-04-11 12:59:51', '2023-04-11 12:59:51'),
(50, 'Dolorem nisi reiciendis quam est ut.', '2023-04-11 12:59:51', '2023-04-11 12:59:51');

-- --------------------------------------------------------

--
-- Структура таблицы `documents`
--

CREATE TABLE `documents` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `doctype_id` bigint UNSIGNED NOT NULL,
  `field1` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `field2` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `field3` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `documents`
--

INSERT INTO `documents` (`id`, `user_id`, `doctype_id`, `field1`, `field2`, `field3`, `created_at`, `updated_at`) VALUES
(1, 7, 22, 'DT 686493862 IL', 'Unde enim repellat.', 'kastxacbbt', '2023-04-11 12:59:51', '2023-04-11 12:59:51'),
(2, 28, 1, 'CB 232050715 ES', 'Aspernatur;', NULL, '2023-04-11 12:59:51', '2023-04-11 12:59:51'),
(3, 27, 27, 'CM 904221761 BR', 'Tempora;\r\n', 'pq', '2023-04-11 12:59:51', '2023-04-11 12:59:51'),
(4, 45, 22, 'CQ 987895463 US', 'Modi inventore.\r\n', 'kb', '2023-04-11 12:59:51', '2023-04-11 12:59:51'),
(5, 1, 39, 'PV 143365730 SE', 'Vitae et odit.\r\n', 'glrb', '2023-04-11 12:59:51', '2023-04-11 12:59:51'),
(6, 28, 30, 'PQ 629469370 US', 'Et veniam error.', 'dmrnqgrmls', '2023-04-11 12:59:51', '2023-04-11 12:59:51'),
(7, 21, 46, 'DP 815536812 IT', 'Quas provident et.', 'yzql', '2023-04-11 12:59:51', '2023-04-11 12:59:51'),
(8, 23, 38, 'AT 407102402 IL', 'Ut eum dolorem.\r\n', 'j', '2023-04-11 12:59:51', '2023-04-11 12:59:51'),
(9, 42, 17, 'ZM 232050729 SE', 'Cum explicabo.\r\n', 'bzadmff', '2023-04-11 12:59:51', '2023-04-11 12:59:51'),
(10, 11, 46, 'LM 143365743 GB', 'Omnis at.\r\n', 'owgrf', '2023-04-11 12:59:51', '2023-04-11 12:59:51'),
(11, 4, 48, 'NN 904221775 CA', 'Odio maxime earum.', 'bad', '2023-04-11 12:59:51', '2023-04-11 12:59:51'),
(12, 37, 16, 'VV 775178859 IT', 'Aliquid dolore.', 'syjlcnh', '2023-04-11 12:59:51', '2023-04-11 12:59:51'),
(13, 8, 3, 'DT 540784398 DK', 'Amet aut sed.\r\n', 'hlerfbe', '2023-04-11 12:59:51', '2023-04-11 12:59:51'),
(14, 21, 10, 'NU 232050732 US', 'Omnis doloribus.\r\n', 'xfcl', '2023-04-11 12:59:51', '2023-04-11 12:59:51'),
(15, 42, 43, 'CT 143365757 DK', 'Natus qui et.\r\nEos.', 'v', '2023-04-11 12:59:51', '2023-04-11 12:59:51'),
(16, 3, 47, 'NJ 686493876 BE', 'Expedita amet.\r\n', 'uiidjkikdj', '2023-04-11 12:59:51', '2023-04-11 12:59:51'),
(17, 5, 23, 'ZB 318417427 AU', 'Vitae.\r\n', NULL, '2023-04-11 12:59:51', '2023-04-11 12:59:51'),
(18, 24, 8, 'DH 775178862 AT', 'Corporis iste.\r\n', 'aim', '2023-04-11 12:59:51', '2023-04-11 12:59:51'),
(19, 33, 32, 'NY 629469383 US', 'Iste quo ut.\r\n', 'x', '2023-04-11 12:59:51', '2023-04-11 12:59:51'),
(20, 33, 45, 'CG 540784407 DE', 'Quibusdam omnis.\r\n', 'ipho', '2023-04-11 12:59:51', '2023-04-11 12:59:51'),
(21, 4, 1, 'PC 686493880 GB', NULL, NULL, '2023-04-11 12:59:51', '2023-04-11 12:59:51'),
(22, 32, 36, 'PK 407102416 US', 'Quia et dolorem.\r\n', 'e', '2023-04-11 12:59:51', '2023-04-11 12:59:51'),
(23, 22, 8, 'EY 815536826 BR', 'Quae sit error.', 'zg', '2023-04-11 12:59:51', '2023-04-11 12:59:51'),
(24, 23, 12, 'CA 629469397 DK', 'Ut ea sapiente.', 'apidwtkv', '2023-04-11 12:59:51', '2023-04-11 12:59:51'),
(25, 47, 24, NULL, 'Voluptatem;\r\n', 'k', '2023-04-11 12:59:51', '2023-04-11 12:59:51'),
(26, 13, 27, 'VH 540784415 ES', 'Adipisci tempore.', 'naszb', '2023-04-11 12:59:51', '2023-04-11 12:59:51'),
(27, 41, 34, 'BW 629469406 IL', 'Dignissimos veniam.', 'ho', '2023-04-11 12:59:51', '2023-04-11 12:59:51'),
(28, 40, 15, 'VZ 540784424 BR', 'Veritatis est.\r\n', 'qxyth', '2023-04-11 12:59:51', '2023-04-11 12:59:51'),
(29, 37, 12, 'PQ 904221789 SE', 'Ut fugiat labore.', 'jbrckd', '2023-04-11 12:59:51', '2023-04-11 12:59:51'),
(30, 47, 21, 'PC 629469410 DK', 'Impedit quam.', 'gkzl', '2023-04-11 12:59:51', '2023-04-11 12:59:51'),
(31, 13, 2, 'AP 540784438 SE', 'Nobis et natus.', 'jrglrl', '2023-04-11 12:59:51', '2023-04-11 12:59:51'),
(32, 46, 44, 'VM 815536830 AU', 'Provident.\r\n', 'avuctwlp', '2023-04-11 12:59:51', '2023-04-11 12:59:51'),
(33, 19, 17, 'VT 775178876 GR', 'Consequatur sint.\r\n', 'agjzflwm', '2023-04-11 12:59:51', '2023-04-11 12:59:51'),
(34, 28, 9, 'PI 318417435 DE', 'Ipsam quasi.\r\n', 'gypeypjyd', '2023-04-11 12:59:51', '2023-04-11 12:59:51'),
(35, 19, 7, 'LX 904221792 US', 'Tenetur.\r\n', 'qyz', '2023-04-11 12:59:51', '2023-04-11 12:59:51'),
(36, 19, 34, 'ZF 407102420 SE', 'Expedita.\r\n', 'owiuebcu', '2023-04-11 12:59:51', '2023-04-11 12:59:51'),
(37, 4, 44, 'ZW 318417444 IT', 'Modi aliquid.\r\n', 'jckohvoah', '2023-04-11 12:59:51', '2023-04-11 12:59:51'),
(38, 20, 40, 'DF 629469423 GR', 'Provident unde.\r\n', 'apkpiggja', '2023-04-11 12:59:51', '2023-04-11 12:59:51'),
(39, 43, 6, 'CP 232050746 DK', 'Necessitatibus.\r\n', 'hjmobb', '2023-04-11 12:59:51', '2023-04-11 12:59:51'),
(40, 1, 34, 'BF 407102433 DK', 'Voluptas ea.\r\n', 'ae', '2023-04-11 12:59:51', '2023-04-11 12:59:51'),
(41, 20, 25, 'PK 686493893 VN', 'Nihil quia dolore.', 'icldhxjtvv', '2023-04-11 12:59:51', '2023-04-11 12:59:51'),
(42, 17, 14, NULL, 'Tempore sed.', 'jrfk', '2023-04-11 12:59:51', '2023-04-11 12:59:51'),
(43, 12, 9, 'VG 815536843 IT', 'Saepe voluptatem.\r\n', 'sjkgarv', '2023-04-11 12:59:51', '2023-04-11 12:59:51'),
(44, 15, 50, 'PI 143365765 SE', NULL, 'vk', '2023-04-11 12:59:51', '2023-04-11 12:59:51'),
(45, 4, 3, 'ZV 775178880 AU', 'Qui omnis vero.\r\n', 'ikltbb', '2023-04-11 12:59:51', '2023-04-11 12:59:51'),
(46, 47, 35, 'EC 686493902 DK', 'Suscipit quas.', 'zqvxqjdzc', '2023-04-11 12:59:51', '2023-04-11 12:59:51'),
(47, 8, 47, 'DO 540784441 ES', 'Consequuntur.', 'twsmhlby', '2023-04-11 12:59:51', '2023-04-11 12:59:51'),
(48, 48, 47, 'UL 629469437 SE', 'Aut iusto vel.', 'yaabfatelr', '2023-04-11 12:59:51', '2023-04-11 12:59:51'),
(49, 2, 8, NULL, NULL, 'uspzyr', '2023-04-11 12:59:51', '2023-04-11 12:59:51'),
(50, 17, 25, 'EK 540784455 GR', 'Ut eius omnis.\r\n', 'rouizejj', '2023-04-11 12:59:51', '2023-04-11 12:59:51');

-- --------------------------------------------------------

--
-- Структура таблицы `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(12, '2014_10_12_000000_create_users_table', 1),
(13, '2014_10_12_100000_create_password_reset_tokens_table', 1),
(14, '2019_08_19_000000_create_failed_jobs_table', 1),
(15, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(16, '2023_04_11_111027_create_country_table', 1),
(17, '2023_04_11_111845_create_doctype_table', 1),
(18, '2023_04_11_111950_create_documents_table', 1),
(19, '2023_04_11_112531_add_country_users_table', 1),
(20, '2023_04_11_124340_add_country_users_table', 1);

-- --------------------------------------------------------

--
-- Структура таблицы `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `country_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `phone`, `remember_token`, `created_at`, `updated_at`, `country_id`) VALUES
(1, 'Kory Burton', 'KoryBurton@nowhere.com', NULL, 'z3Rg1fVMt6EAYuZeIu1lTg==', '(810) 636-5001', NULL, NULL, NULL, 45),
(2, 'Nedra Lewis', 'Anna.E.Ogle91@example.com', NULL, 'xIb04bVuN5nSSa+TdE20Xg==', '(670) 844-7028', NULL, NULL, NULL, 15),
(3, 'Sade Tapia', 'Grubbs395@example.com', NULL, 'X+cl/1/ovenZ7Hnj601RvQ==', '(912) 818-6927', NULL, NULL, NULL, 34),
(4, 'Addie Ferguson', 'JakeNegron@example.com', NULL, 'amEv/Tz6E/+jZsVki4ADVg==', '(196) 694-9244', NULL, NULL, NULL, 1),
(5, 'Christel Abney', 'KelvinPeyton281@nowhere.com', NULL, 'G/zMiBMC0gD2kyjB0npRYQ==', '(224) 338-3524', NULL, NULL, NULL, 28),
(6, 'Barry Golden', 'Amaral378@example.com', NULL, 'qhP03qxvHcSAUjck9sszuA==', '(409) 791-7324', NULL, NULL, NULL, 25),
(7, 'Alethea Ma', 'LauraHawk736@example.com', NULL, 'vGwF/qV3oUwfbfJcJg7l8A==', '(313) 721-9488', NULL, NULL, NULL, 49),
(8, 'Paul Summers', 'AndrewKern@example.com', NULL, 'WC3M7lEDAw7UQKOuHaeZIg==', '(458) 721-4626', NULL, NULL, NULL, 32),
(9, 'Lesley Durden', 'Register@nowhere.com', NULL, 'Ax9D5R2j9OE3u84bT/E90w==', '(734) 250-1472', NULL, NULL, NULL, 35),
(10, 'Ashlyn Palma', 'AlfrediaVidal938@example.com', NULL, 'sSZTKQ3V4FKgZuNNt1EeRA==', '(696) 299-2856', NULL, NULL, NULL, 23),
(11, 'Deon Castle', 'AldenDougherty93@nowhere.com', NULL, '4LHYsNbFz6oyISRTdwPJcg==', '(558) 446-4105', NULL, NULL, NULL, 27),
(12, 'Abel Eddy', 'DonteDahl@example.com', NULL, 'Vs3mGgHX3fIuUoL2EA9mIg==', '(336) 978-4555', NULL, NULL, NULL, 14),
(13, 'Manie Wahl', 'BobbieMoriarty437@nowhere.com', NULL, 'pGw7VPLJhxzYHa96kySZwA==', '(238) 208-2407', NULL, NULL, NULL, 31),
(14, 'Kristel Duncan', 'ElayneSaldana7@example.com', NULL, 'Q/8s8n1uwmxVDEJtnQkV7w==', '(652) 073-5996', NULL, NULL, NULL, 48),
(15, 'Bernardine Edmonds', 'Crabtree456@nowhere.com', NULL, 'WJtt3eqh+cjZLyzQcogOzw==', '(376) 587-3111', NULL, NULL, NULL, 10),
(16, 'Abram Waller', 'Holmes@nowhere.com', NULL, 'xtBlSPlUpr59av2Nm0ZuVg==', '(838) 529-0541', NULL, NULL, NULL, 6),
(17, 'Aubrey Abernathy', 'znewgp8195@nowhere.com', NULL, 'dB+Lise3QMAy9+pws9hRaw==', '(141) 969-5565', NULL, NULL, NULL, 22),
(18, 'Ian Link', 'Vance.Key26@nowhere.com', NULL, '2SHZeTiuQ7j2gzaSKHW8Gw==', '(569) 150-2930', NULL, NULL, NULL, 14),
(19, 'Danille Willey', 'dpglpo5709@nowhere.com', NULL, '817VVMSRwbo3cBN1nD/9rA==', '(741) 261-0983', NULL, NULL, NULL, 26),
(20, 'Hugh Rubio', 'Acker@example.com', NULL, 'oIHpNQzlkYzKBG1J3jiLmA==', '(408) 449-2230', NULL, NULL, NULL, 13),
(21, 'Amanda Hawk', 'IoneLawler68@nowhere.com', NULL, 'nXz8PpIXTgP6YGzo3EhrZA==', '(262) 337-9667', NULL, NULL, NULL, 43),
(22, 'Dave Alston', 'CatharineHopper@nowhere.com', NULL, 'nzcos4tE5sUbYqeN3EhH+A==', '(579) 450-6682', NULL, NULL, NULL, 16),
(23, 'Junko Godwin', 'Jack.Lin@nowhere.com', NULL, 'EHDDJHWsFtf+k/mLX4ZdLA==', '(297) 957-6005', NULL, NULL, NULL, 29),
(24, 'Shantelle Abel', 'Freed5@example.com', NULL, 'zcgnmcRgsb/hJvFSv2P58A==', '(501) 678-3429', NULL, NULL, NULL, 26),
(25, 'Janette Turner', 'IrvingHarding@example.com', NULL, 'xLpPyEiXe9szZm9B7eHy5Q==', '(991) 431-8689', NULL, NULL, NULL, 32),
(26, 'Samuel Anders', 'Alexander@nowhere.com', NULL, 'wroU3qU6uLGijiO+KkHcVg==', '(834) 146-1492', NULL, NULL, NULL, 12),
(27, 'Narcisa Guillory', 'Jared_Guerrero99@nowhere.com', NULL, 'oIpyrc8ksoWv6tUxnruJLg==', '(499) 082-7604', NULL, NULL, NULL, 44),
(28, 'Neal Burris', 'VeolaSapp@example.com', NULL, 'vlXXXthsJ40qXJ96l1XbMQ==', '(851) 648-4060', NULL, NULL, NULL, 7),
(29, 'Edwina Bellamy', 'osdepdwn1202@example.com', NULL, 'pzTJTW4EbEZn/qV3WMW29g==', '(804) 113-0357', NULL, NULL, NULL, 46),
(30, 'Florence Ackerman', 'AdelaHuey@example.com', NULL, 'RO28ukoxX9L0li/rbY1nOQ==', '(311) 272-0511', NULL, NULL, NULL, 16),
(31, 'Alphonso Wyatt', 'Batten325@nowhere.com', NULL, '+WbXYZriq0WIolr9OEi2XA==', '(408) 949-7919', NULL, NULL, NULL, 17),
(32, 'Adam Cohen', 'Begay982@example.com', NULL, 'rbMjOv1qBuxQTZ2jbl/9RA==', '(394) 168-3957', NULL, NULL, NULL, 42),
(33, 'Harland Furr', 'RonniAhrens@example.com', NULL, 'X1i+OPtCdBjytXbw3GPcfw==', '(660) 841-9547', NULL, NULL, NULL, 40),
(34, 'Corey Jolley', 'Abraham6@nowhere.com', NULL, 'OuXtreH1fdvINx6NO7zvgQ==', '(371) 555-7281', NULL, NULL, NULL, 37),
(35, 'Lelia Valencia', 'Elias@nowhere.com', NULL, 'GsfqLH1sAkPsE2Fde76Zdw==', '(259) 003-7279', NULL, NULL, NULL, 27),
(36, 'Seymour Addison', 'Benedict@example.com', NULL, 'njd+rAMdFEDdJGErk20imQ==', '(402) 994-9105', NULL, NULL, NULL, 13),
(37, 'Kelsi Freitas', 'EvelinPfeifer8@nowhere.com', NULL, 'L6312zh6kIVgd9WIwTCmlg==', '(819) 934-1656', NULL, NULL, NULL, 45),
(38, 'Alejandro Rawls', 'Baca@example.com', NULL, 'Wbjv2C9nLKHzdS691Td0AA==', '(731) 266-3542', NULL, NULL, NULL, 45),
(39, 'Bernie Chun', 'Desmond.Conrad3@nowhere.com', NULL, '/DMCTfd/bYnzLI9aTLoLCQ==', '(635) 873-9552', NULL, NULL, NULL, 39),
(40, 'Issac Beers', 'Reynoso@example.com', NULL, 'K1DJ3cQ5aYWjSVQi6vRsxQ==', '(755) 532-1153', NULL, NULL, NULL, 13),
(41, 'Antone Maclean', 'Abbott@example.com', NULL, 'qhVMOOGUezfa1/6tW/moTQ==', '(731) 239-7395', NULL, NULL, NULL, 50),
(42, 'Karyl Adame', 'HarlandMichaud9@example.com', NULL, 'co/bFtymtn77w+K7/gGjQA==', '(852) 568-2880', NULL, NULL, NULL, 34),
(43, 'Doug Pollard', 'GaitherH@example.com', NULL, 'BtSWMsncm8tirq75lhK6aw==', '(498) 731-6340', NULL, NULL, NULL, 16),
(44, 'Garry Mahan', 'Abbott@nowhere.com', NULL, 'CSITqOiv5gOZJfzi9VPTmg==', '(543) 645-8634', NULL, NULL, NULL, 22),
(45, 'Beula Creech', 'Leida.Abraham893@nowhere.com', NULL, '/90Oy+0x39uO9uK0HW/dTg==', '(714) 286-7533', NULL, NULL, NULL, 9),
(46, 'Kimberely Wilde', 'Stuckey624@nowhere.com', NULL, 'EfGhxVa5I2WMNanaigYxVQ==', '(270) 492-0359', NULL, NULL, NULL, 19),
(47, 'Betsy Abney', 'nrmzqahm582@example.com', NULL, 'tsAKidBuon9NUi7dakTkuw==', '(823) 408-3845', NULL, NULL, NULL, 14),
(48, 'Aida Batson', 'Tena_Almeida45@nowhere.com', NULL, 'n48O0RL5f8u1fEBhOz8now==', '(214) 924-0372', NULL, NULL, NULL, 5),
(49, 'Leandro Amato', 'Benjamin_Mann5@example.com', NULL, '+avSeLqr/VwBUExVPb++bw==', '(246) 521-2959', NULL, NULL, NULL, 37),
(50, 'Taisha Brower', 'WaltonRoth368@nowhere.com', NULL, 'kuTS2j0VKLyfZmi7wm1jPg==', '(493) 834-0268', NULL, NULL, NULL, 38);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `country`
--
ALTER TABLE `country`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `country_phone_code_unique` (`phone_code`);

--
-- Индексы таблицы `doctype`
--
ALTER TABLE `doctype`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `doctype_docname_unique` (`docname`);

--
-- Индексы таблицы `documents`
--
ALTER TABLE `documents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `documents_user_id_foreign` (`user_id`),
  ADD KEY `documents_doctype_id_foreign` (`doctype_id`);

--
-- Индексы таблицы `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Индексы таблицы `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Индексы таблицы `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `users_country_id_foreign` (`country_id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `country`
--
ALTER TABLE `country`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT для таблицы `doctype`
--
ALTER TABLE `doctype`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT для таблицы `documents`
--
ALTER TABLE `documents`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT для таблицы `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT для таблицы `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `documents`
--
ALTER TABLE `documents`
  ADD CONSTRAINT `documents_doctype_id_foreign` FOREIGN KEY (`doctype_id`) REFERENCES `doctype` (`id`),
  ADD CONSTRAINT `documents_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Ограничения внешнего ключа таблицы `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_country_id_foreign` FOREIGN KEY (`country_id`) REFERENCES `country` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
