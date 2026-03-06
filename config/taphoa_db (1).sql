-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th3 06, 2026 lúc 03:46 PM
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
(8, 'Mỹ phẩm ', 'Trang Sức', '2026-01-27 16:59:59');

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
(4, 'PN-20260129092815', 'Công ti đồ hộp hạ long', NULL, 250000.00, NULL, '2026-01-29 08:28:23');

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
(3, 'KK-260127-181257', 2, '2026-01-27 17:13:14', 'Kiểm Kho Định Kì Tháng 1');

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
(3, 3, 5, 10000, 3000);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `order_code` varchar(50) NOT NULL,
  `customer_name` varchar(255) DEFAULT 'Khách vãng lai',
  `total_amount` decimal(15,2) DEFAULT 0.00,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(5, '011', 'gà rán', 'Mỹ phẩm ', 200000.00, 100000.00, 3000, 'thùng', NULL, '2026-01-27 17:01:07'),
(6, '012', 'Rau mùi', 'Thực phẩm', 10000.00, 500.00, 11123, 'Bó', NULL, '2026-01-27 17:16:25'),
(8, '0124', 'Rau mùi 5', 'Thực phẩm', 15000.00, 15000.00, 11124, 'Bó', NULL, '2026-01-27 17:53:03'),
(10, '123', '123', 'Thực phẩm', 100.00, 20000.00, 43333, 'thùng', NULL, '2026-03-06 13:45:35');

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
(6, 'minh313', 'nguyencongminh028@gmail.com07', '$2y$10$lnCqwVJa1BmdfuFSOi/MyO6W.vg0fMd2WzzAJdKAcYN..SIvg6FbW', '[\"UC2\"]', '2026-01-27 15:25:03');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT cho bảng `imports`
--
ALTER TABLE `imports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `import_details`
--
ALTER TABLE `import_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `inventory_checks`
--
ALTER TABLE `inventory_checks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `inventory_details`
--
ALTER TABLE `inventory_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `order_details`
--
ALTER TABLE `order_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT cho bảng `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

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
