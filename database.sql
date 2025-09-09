CREATE TABLE `vai_tro` (
  `id` int NOT NULL AUTO_INCREMENT,
  `ten` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `nguoi_dung` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `id_vai_tro` int DEFAULT NULL,
  `ho_ten` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `gioi_tinh` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `so_dien_thoai` varchar(50) DEFAULT NULL,
  `ten_dang_nhap` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `mat_khau` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_nguoi_dung_vai_tro` (`id_vai_tro`),
  CONSTRAINT `fk_nguoi_dung_vai_tro` FOREIGN KEY (`id_vai_tro`) REFERENCES `vai_tro` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `don_hang` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `id_nguoi_dung` bigint DEFAULT NULL,
  `tong_tien` float DEFAULT NULL,
  `dia_chi` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `sdt` varchar(255) DEFAULT NULL,
  `ten` varchar(255) DEFAULT NULL,
  `trang_thai` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_don_hang_nguoi_dung` (`id_nguoi_dung`),
  CONSTRAINT `fk_don_hang_nguoi_dung` FOREIGN KEY (`id_nguoi_dung`) REFERENCES `nguoi_dung` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `san_pham` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `ten` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `loai` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `don_vi` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `so_luong` int DEFAULT NULL,
  `gia` float DEFAULT NULL,
  `anh` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `trang_thai` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `gio_hang` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `id_nguoi_dung` bigint DEFAULT NULL,
  `so_luong_sp` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_gio_hang_nguoi_dung` (`id_nguoi_dung`),
  CONSTRAINT `fk_gio_hang_nguoi_dung` FOREIGN KEY (`id_nguoi_dung`) REFERENCES `nguoi_dung` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `ct_gio_hang` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `id_gio_hang` bigint DEFAULT NULL,
  `id_san_pham` bigint DEFAULT NULL,
  `so_luong` bigint DEFAULT NULL,
  `gia` float DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_ct_gio_hang_san_pham` (`id_san_pham`),
  KEY `fk_ct_gio_hang_gio_hang` (`id_gio_hang`),
  CONSTRAINT `fk_ct_gio_hang_gio_hang` FOREIGN KEY (`id_gio_hang`) REFERENCES `gio_hang` (`id`),
  CONSTRAINT `fk_ct_gio_hang_san_pham` FOREIGN KEY (`id_san_pham`) REFERENCES `san_pham` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `ct_don_hang` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `id_don_hang` bigint DEFAULT NULL,
  `id_san_pham` bigint DEFAULT NULL,
  `so_luong` int DEFAULT NULL,
  `gia` float DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_ct_don_hang_don_hang` (`id_don_hang`),
  KEY `fk_ct_don_hang_san_pham` (`id_san_pham`),
  CONSTRAINT `fk_ct_don_hang_don_hang` FOREIGN KEY (`id_don_hang`) REFERENCES `don_hang` (`id`),
  CONSTRAINT `fk_ct_don_hang_san_pham` FOREIGN KEY (`id_san_pham`) REFERENCES `san_pham` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `vai_tro` VALUES (1,'USER'),(2,'ADMIN');
