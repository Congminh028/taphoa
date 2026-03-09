-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th3 09, 2026 lúc 11:42 AM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `taphoa_db`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `categories`
--

INSERT INTO `categories` (`id`, `name`, `description`, `created_at`) VALUES
(1, 'Đồ uống', 'Nước ngọt, bia, nước giải khát', '2026-01-27 16:34:58'),
(2, 'Thực phẩm', 'Bánh kẹo, đồ ăn nhanh, gia vị', '2026-01-27 16:34:58'),
(3, 'Gia dụng', 'Xà phòng, bột giặt, đồ dùng gia đình', '2026-01-27 16:34:58'),
(4, 'Khác', 'Các mặt hàng chưa phân loại', '2026-01-27 16:34:58'),
(8, 'Mỹ phẩm ', 'Trang Sức', '2026-01-27 16:59:59'),
(9, 'Hàng Cấm', 'Danh Mục Hàng Cấm', '2026-03-08 10:04:51');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `customers`
--

CREATE TABLE `customers` (
  `id` int(11) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `name` varchar(255) NOT NULL,
  `address` text DEFAULT NULL,
  `points` int(11) DEFAULT 0,
  `tier` varchar(50) DEFAULT 'Đồng',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `customers`
--

INSERT INTO `customers` (`id`, `phone`, `name`, `address`, `points`, `tier`, `created_at`) VALUES
(1, '0329093961', 'Lò Thu Huyền', 'Bản Lò', 0, 'Đồng', '2026-03-07 13:40:18'),
(3, '0123654555', 'Nguyễn Công Minh ', 'Bản Cầy', 330, 'Vàng', '2026-03-07 13:49:54'),
(4, '123123332', 'Nguyễn Công Minh', 'Khu 1', 0, 'Đồng', '2026-03-07 15:00:34'),
(5, '0123323232', 'Nguyễn Trường An', 'Bản Ruồi', 19, 'Đồng', '2026-03-09 10:29:50'),
(6, '123123', 'Nguyễn Tuấn Thành', 'Hoàng mike', 0, 'Đồng', '2026-03-09 10:30:13'),
(7, '012336633', 'NGuyễn Tấn DŨng', 'Thank Hóa', 0, 'Đồng', '2026-03-09 10:31:13');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `imports`
--

CREATE TABLE `imports` (
  `id` int(11) NOT NULL,
  `import_code` varchar(50) NOT NULL,
  `supplier` varchar(255) DEFAULT NULL,
  `supplier_name` varchar(255) DEFAULT NULL,
  `total_amount` decimal(15,2) DEFAULT 0.00,
  `user_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `imports`
--

INSERT INTO `imports` (`id`, `import_code`, `supplier`, `supplier_name`, `total_amount`, `user_id`, `created_at`) VALUES
(1, 'PN-1769576147', 'an béo', NULL, 61500.00, NULL, '2026-01-28 04:55:47'),
(2, 'PN-1769576200', 'tu ti', NULL, 265000.00, NULL, '2026-01-28 04:56:40'),
(3, 'PN-20260128063530', 'Công Ty Căng Hải', NULL, 1000000.00, NULL, '2026-01-28 05:35:38'),
(4, 'PN-20260129092815', 'Công ti đồ hộp hạ long', NULL, 250000.00, NULL, '2026-01-29 08:28:23'),
(5, 'PN-20260308110911', 'Công ti đồ hộp hạ long', NULL, 100000000.00, NULL, '2026-03-08 10:09:56');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `import_details`
--

CREATE TABLE `import_details` (
  `id` int(11) NOT NULL,
  `import_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `import_price` decimal(15,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `inventory_checks`
--

CREATE TABLE `inventory_checks` (
  `id` int(11) NOT NULL,
  `code` varchar(50) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `check_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `note` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `inventory_checks`
--

INSERT INTO `inventory_checks` (`id`, `code`, `user_id`, `check_date`, `note`) VALUES
(1, 'KK-260127-181101', 2, '2026-01-27 17:11:22', ''),
(2, 'KK-260127-181129', 2, '2026-01-27 17:11:43', 'Kiểm Kho Định Kì Tháng 1'),
(3, 'KK-260127-181257', 2, '2026-01-27 17:13:14', 'Kiểm Kho Định Kì Tháng 1'),
(4, 'KK-260307-165444', 10, '2026-03-07 15:54:53', ''),
(5, 'KK-260307-165614', 11, '2026-03-07 15:56:19', ''),
(6, 'KK-260307-165632', 11, '2026-03-07 15:56:42', '');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `inventory_details`
--

CREATE TABLE `inventory_details` (
  `id` int(11) NOT NULL,
  `check_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `system_stock` int(11) DEFAULT NULL,
  `actual_stock` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `inventory_details`
--

INSERT INTO `inventory_details` (`id`, `check_id`, `product_id`, `system_stock`, `actual_stock`) VALUES
(1, 1, 5, 10000, 10000),
(2, 2, 5, 10000, 10000),
(3, 3, 5, 10000, 3000),
(4, 4, 10, 43262, 43265),
(5, 4, 8, 11111, 11111),
(6, 4, 6, 11115, 11115),
(7, 4, 5, 2991, 2991),
(8, 5, 10, 43265, 43268),
(9, 5, 8, 11111, 11111),
(10, 5, 6, 11115, 11115),
(11, 5, 5, 2991, 2991),
(12, 6, 10, 43268, 43272),
(13, 6, 8, 11111, 11111),
(14, 6, 6, 11115, 11115),
(15, 6, 5, 2991, 2991);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `sender_name` varchar(100) DEFAULT NULL,
  `role_label` varchar(50) DEFAULT NULL,
  `channel` varchar(50) DEFAULT 'GLOBAL',
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `messages`
--

INSERT INTO `messages` (`id`, `sender_id`, `sender_name`, `role_label`, `channel`, `message`, `created_at`) VALUES
(1, 2, 'minh2', 'Admin', 'GLOBAL', 'alooo', '2026-03-07 14:36:41'),
(2, 2, 'minh2', 'Admin', 'GLOBAL', 'tất cả chúng mày đã bị một mình tao bao vây', '2026-03-07 14:36:56'),
(3, 2, 'minh2', 'Admin', 'GLOBAL', 'aloo', '2026-03-07 14:42:41'),
(4, 2, 'minh2', 'Admin', 'GLOBAL', 'aloo', '2026-03-07 14:42:43'),
(5, 2, 'minh2', 'Admin', 'GLOBAL', 'aloo', '2026-03-07 14:42:43'),
(6, 2, 'minh2', 'Admin', 'GLOBAL', 'aloo', '2026-03-07 14:42:43'),
(7, 2, 'minh2', 'Admin', 'GLOBAL', 'aloo', '2026-03-07 14:43:53'),
(8, 2, 'minh2', 'Admin', 'GLOBAL', 'aloo', '2026-03-07 14:43:54'),
(9, 2, 'minh2', 'Admin', 'GLOBAL', 'aloo', '2026-03-07 14:43:54'),
(10, 2, 'minh2', 'Admin', 'ADMIN', 'aloo', '2026-03-07 14:43:57'),
(11, 2, 'minh2', 'Admin', 'ADMIN', 'aloo', '2026-03-07 14:43:57'),
(12, 2, 'minh2', 'Admin', 'KE_TOAN', 'aloo', '2026-03-07 14:46:35'),
(13, 2, 'minh2', 'Admin', 'KHO', 'thành ngu', '2026-03-07 15:07:19'),
(14, 2, 'minh2', 'Admin', 'KHO', 'thành ngu ơi', '2026-03-07 15:09:59');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `order_code` varchar(50) NOT NULL,
  `customer_name` varchar(255) DEFAULT 'Khách vãng lai',
  `total_amount` decimal(15,2) DEFAULT 0.00,
  `payment_method` varchar(50) DEFAULT 'Tiền mặt',
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `orders`
--

INSERT INTO `orders` (`id`, `customer_id`, `order_code`, `customer_name`, `total_amount`, `payment_method`, `created_at`) VALUES
(1, NULL, 'HD-20260307134708', 'Khách vãng lai', 10300.00, 'Tiền mặt', '2026-03-07 19:47:08'),
(2, NULL, 'HD-20260307135528', 'Khách vãng lai', 635400.00, 'Tiền mặt', '2026-03-07 19:55:28'),
(3, NULL, 'HD-20260307135731', 'Khách vãng lai', 600000.00, 'Tiền mặt', '2026-03-07 19:57:31'),
(4, NULL, 'HD-20260307135737', 'Khách vãng lai', 600000.00, 'Tiền mặt', '2026-03-07 19:57:37'),
(5, NULL, 'HD-20260307144619', 'Khách vãng lai', 65600.00, 'Tiền mặt', '2026-03-07 20:46:19'),
(6, NULL, 'HD-20260307144648', 'Khách vãng lai', 45000.00, 'Tiền mặt', '2026-03-07 20:46:48'),
(7, NULL, 'HD-20260307144656', 'Khách vãng lai', 45000.00, 'Tiền mặt', '2026-03-07 20:46:56'),
(8, NULL, 'HD-20260307144701', 'Khách vãng lai', 45000.00, 'Tiền mặt', '2026-03-07 20:47:01'),
(9, NULL, 'HD-20260307144709', 'Khách vãng lai', 30000.00, 'Tiền mặt', '2026-03-07 20:47:09'),
(10, NULL, 'HD-20260307152450', 'Khách vãng lai', 200.00, 'Tiền mặt', '2026-03-07 21:24:50'),
(11, NULL, 'HD-20260307152455', 'Khách vãng lai', 200.00, 'Tiền mặt', '2026-03-07 21:24:55'),
(12, NULL, 'HD-20260307152507', 'Khách vãng lai', 100.00, 'Tiền mặt', '2026-03-07 21:25:07'),
(13, NULL, 'HD-20260307152517', 'Khách vãng lai', 400.00, 'Tiền mặt', '2026-03-07 21:25:17'),
(14, NULL, 'HD-20260307152520', 'Khách vãng lai', 400.00, 'Tiền mặt', '2026-03-07 21:25:20'),
(15, NULL, 'HD-20260307160747', 'Khách vãng lai', 100.00, 'Tiền mặt', '2026-03-07 22:07:47'),
(16, 1, 'HD-20260307161040', 'Lò Thu Huyền', 450.00, 'Tiền mặt', '2026-03-07 22:10:40'),
(17, 3, 'HD-20260307161305', 'Nguyễn Công Minh ', 150.00, 'Tiền mặt', '2026-03-07 22:13:05'),
(18, 3, 'HD-20260307161734', 'Nguyễn Công Minh ', 300.00, 'Tiền mặt', '2026-03-07 22:17:34'),
(19, 1, 'HD-20260307161756', 'Lò Thu Huyền', 150.00, 'Tiền mặt', '2026-03-07 22:17:56'),
(20, 3, 'HD-20260307162307', 'Nguyễn Công Minh ', 250.00, 'Tiền mặt', '2026-03-07 22:23:07'),
(21, 3, 'HD-20260307162706', 'Nguyễn Công Minh ', 150.00, 'Tiền mặt', '2026-03-07 22:27:06'),
(22, NULL, 'HD-20260307162722', 'Khách vãng lai', 200.00, 'Tiền mặt', '2026-03-07 22:27:22'),
(23, NULL, 'HD-20260307162731', 'Khách vãng lai', 150.00, 'Tiền mặt', '2026-03-07 22:27:31'),
(24, 1, 'HD-20260307162851', 'Lò Thu Huyền', 200.00, 'Tiền mặt', '2026-03-07 22:28:51'),
(25, NULL, 'HD-20260307162912', 'Khách vãng lai', 150.00, 'Tiền mặt', '2026-03-07 22:29:12'),
(26, NULL, 'HD-20260308105711', 'Khách vãng lai', 600150.00, 'Tiền mặt', '2026-03-08 16:57:11'),
(27, 3, 'HD-20260308105732', 'Nguyễn Công Minh ', 300225.00, 'Tiền mặt', '2026-03-08 16:57:32'),
(28, 4, 'HD-20260308105752', 'Nguyễn Công Minh', 225.00, 'Tiền mặt', '2026-03-08 16:57:52'),
(29, 3, 'HD-20260308105852', 'Nguyễn Công Minh ', 1350450.00, 'Tiền mặt', '2026-03-08 16:58:52'),
(30, 5, 'HD-20260309113321', 'Nguyễn Trường An', 96000.00, 'Tiền mặt', '2026-03-09 17:33:21');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `order_details`
--

CREATE TABLE `order_details` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(15,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `order_details`
--

INSERT INTO `order_details` (`id`, `order_id`, `product_id`, `quantity`, `price`) VALUES
(1, 1, 6, 1, 10000.00),
(2, 1, 10, 3, 100.00),
(3, 2, 10, 4, 100.00),
(4, 2, 5, 3, 200000.00),
(5, 2, 6, 2, 10000.00),
(6, 2, 8, 1, 15000.00),
(7, 3, 5, 3, 200000.00),
(8, 4, 5, 3, 200000.00),
(9, 5, 10, 6, 100.00),
(10, 5, 6, 2, 10000.00),
(11, 5, 8, 3, 15000.00),
(12, 6, 8, 3, 15000.00),
(13, 7, 8, 3, 15000.00),
(14, 8, 8, 3, 15000.00),
(15, 9, 6, 3, 10000.00),
(16, 10, 10, 2, 100.00),
(17, 11, 10, 2, 100.00),
(18, 12, 10, 1, 100.00),
(19, 13, 10, 4, 100.00),
(20, 14, 10, 4, 100.00),
(21, 15, 10, 2, 100.00),
(22, 16, 10, 9, 100.00),
(23, 17, 10, 3, 100.00),
(24, 18, 10, 6, 100.00),
(25, 19, 10, 3, 100.00),
(26, 20, 10, 5, 100.00),
(27, 21, 10, 3, 100.00),
(28, 22, 10, 4, 100.00),
(29, 23, 10, 3, 100.00),
(30, 24, 10, 4, 100.00),
(31, 25, 10, 3, 100.00),
(32, 26, 5, 4, 200000.00),
(33, 26, 10, 2, 100.00),
(34, 27, 5, 2, 200000.00),
(35, 27, 10, 3, 100.00),
(36, 28, 10, 3, 100.00),
(37, 29, 10, 6, 100.00),
(38, 29, 5, 9, 200000.00),
(39, 30, 8, 2, 15000.00),
(40, 30, 6, 3, 10000.00),
(41, 30, 11, 4, 15000.00);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `code` varchar(50) NOT NULL,
  `name` varchar(255) NOT NULL,
  `category` varchar(100) DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `cost` decimal(10,2) NOT NULL,
  `stock` int(11) DEFAULT 0,
  `unit` varchar(50) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `products`
--

INSERT INTO `products` (`id`, `code`, `name`, `category`, `price`, `cost`, `stock`, `unit`, `image`, `created_at`) VALUES
(5, '011', 'gà rán', 'Mỹ phẩm ', 200000.00, 100000.00, 2976, 'thùng', NULL, '2026-01-27 17:01:07'),
(6, '012', 'Rau mùi', 'Thực phẩm', 10000.00, 500.00, 11112, 'Bó', NULL, '2026-01-27 17:16:25'),
(8, '0124', 'Rau mùi 5', 'Thực phẩm', 15000.00, 15000.00, 11109, 'Bó', NULL, '2026-01-27 17:53:03'),
(10, '123', 'Dưa cải ', 'Thực phẩm', 100000.00, 20000.00, 43258, 'thùng', NULL, '2026-03-06 13:45:35'),
(11, 'COCA001', 'Nước Tăng Lực', 'Đồ uống', 15000.00, 10000.00, 109996, 'Chai', NULL, '2026-03-08 10:03:45'),
(12, 'SP0011', 'Nem Chua', 'Thực phẩm', 5000.00, 500.00, 10000, 'Chai', NULL, '2026-03-08 10:06:27');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `suppliers`
--

CREATE TABLE `suppliers` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `suppliers`
--

INSERT INTO `suppliers` (`id`, `name`, `phone`, `address`, `created_at`) VALUES
(1, 'Công ti đồ hộp hạ long', '032858687', 'Quảng Ninh', '2026-01-28 12:09:42'),
(2, 'Công Ty Căng Hải', '0912121213', 'QL1A', '2026-01-28 12:35:02');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `system_settings`
--

CREATE TABLE `system_settings` (
  `setting_key` varchar(50) NOT NULL,
  `setting_value` varchar(255) NOT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `system_settings`
--

INSERT INTO `system_settings` (`setting_key`, `setting_value`, `description`) VALUES
('discount_gold', '10', 'Phần trăm giảm giá cho hạng Vàng'),
('discount_silver', '5', 'Phần trăm giảm giá cho hạng Bạc'),
('global_discount_percent', '20', 'Phần trăm giảm giá toàn hệ thống tại POS'),
('points_conversion_rate', '5000', 'Số tiền VNĐ tương ứng 1 điểm'),
('points_gold', '200', 'Số điểm tối thiểu để đạt hạng Vàng'),
('points_silver', '50', 'Số điểm tối thiểu để đạt hạng Bạc');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `permissions` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `permissions`, `created_at`) VALUES
(1, 'minh', 'nguyencongminh028@gmail.com03', '$2y$10$kGyPKfHAjDHeBhYpdWRox.sBzQPMOiZzJVEOjdW9FImHO1Out/Or2', '[\"UC3\"]', '2026-01-27 14:07:06'),
(2, 'minh2', 'nguyencongminh028@gmail.com04', '$2y$10$K9Vyhb9dHsOrtb2mHxmPG.mcRzl.iNjXCLs1IdH98ijtXvC54.WRq', '[\"UC1\",\"UC2\",\"UC3\",\"UC4\",\"UC5\",\"ADMIN\"]', '2026-01-27 14:25:39'),
(4, 'minh44', 'nguyencongminh028@gmail.com05', '$2y$10$divdebgHZYy/nhGNiXZ7U.OZr44bPgQZDm8mD8b/xOaccJrsLG8yC', '[\"UC1\"]', '2026-01-27 14:58:33'),
(5, 'minh444', 'nguyencongminh028@gmail.com06', '$2y$10$uM94c5B8cOLdb/Dho6RWCOk74HpHJuQrUA5xZzH8RHkhMSkkpCGoi', '[\"UC4\"]', '2026-01-27 15:05:51'),
(6, 'minh313', 'nguyencongminh028@gmail.com07', '$2y$10$lnCqwVJa1BmdfuFSOi/MyO6W.vg0fMd2WzzAJdKAcYN..SIvg6FbW', '[\"UC2\"]', '2026-01-27 15:25:03'),
(7, 'thanhngu', 'thanhngu@gmail.com', '$2y$10$v1U6Q2zjxa/Y4rdLOcNd9e8oEBaGG7d9GurUa5mmZS3UnQ5BJnnOu', '[\"UC1\"]', '2026-03-07 11:56:07'),
(9, 'admin', 'adminh@gmail.com', '$2y$10$FCqjPrGpIyhEQxA.3yaXVul4yKWfQdyLxoZa8NXhygbg3FvZwVVRC', '[\"UC1\",\"UC2\",\"UC3\",\"UC4\",\"UC5\",\"ADMIN\"]', '2026-03-07 12:00:42'),
(10, 'thanhngu1', 'thanhngu1@gmail.com', '$2y$10$4WBM2y4e6xl28EhdQ/67Tev2j.ZtZwEjKjPY6An0voJJFoKuwKTVm', '[\"UC1\"]', '2026-03-07 15:06:40'),
(11, 'thanh1', 'thanh1@gmail.com', '$2y$10$poPZbQI34B7ChtNtfQY/EuYSNA5xXyVZ5cH18UirjLouqAhN5M3d2', '[\"UC1\"]', '2026-03-07 15:56:07');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Chỉ mục cho bảng `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `phone` (`phone`);

--
-- Chỉ mục cho bảng `imports`
--
ALTER TABLE `imports`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `import_code` (`import_code`);

--
-- Chỉ mục cho bảng `import_details`
--
ALTER TABLE `import_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `import_id` (`import_id`);

--
-- Chỉ mục cho bảng `inventory_checks`
--
ALTER TABLE `inventory_checks`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Chỉ mục cho bảng `inventory_details`
--
ALTER TABLE `inventory_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `check_id` (`check_id`);

--
-- Chỉ mục cho bảng `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `order_details`
--
ALTER TABLE `order_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Chỉ mục cho bảng `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Chỉ mục cho bảng `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `system_settings`
--
ALTER TABLE `system_settings`
  ADD PRIMARY KEY (`setting_key`);

--
-- Chỉ mục cho bảng `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT cho bảng `customers`
--
ALTER TABLE `customers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT cho bảng `imports`
--
ALTER TABLE `imports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT cho bảng `import_details`
--
ALTER TABLE `import_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `inventory_checks`
--
ALTER TABLE `inventory_checks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT cho bảng `inventory_details`
--
ALTER TABLE `inventory_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT cho bảng `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT cho bảng `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT cho bảng `order_details`
--
ALTER TABLE `order_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT cho bảng `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT cho bảng `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `import_details`
--
ALTER TABLE `import_details`
  ADD CONSTRAINT `import_details_ibfk_1` FOREIGN KEY (`import_id`) REFERENCES `imports` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `inventory_details`
--
ALTER TABLE `inventory_details`
  ADD CONSTRAINT `inventory_details_ibfk_1` FOREIGN KEY (`check_id`) REFERENCES `inventory_checks` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `order_details`
--
ALTER TABLE `order_details`
  ADD CONSTRAINT `order_details_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`),
  ADD CONSTRAINT `order_details_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
