-- 1. Bảng Vai Trò
CREATE TABLE vai_tro (
    id_vai_tro INT IDENTITY(1,1) PRIMARY KEY,
    ten NVARCHAR(50) DEFAULT NULL
);

-- 2. Bảng Người Dùng
CREATE TABLE nguoi_dung (
    id_nguoi_dung BIGINT IDENTITY(1,1) PRIMARY KEY,
    id_vai_tro INT DEFAULT NULL,
    ho_ten NVARCHAR(50) DEFAULT NULL,
    gioi_tinh NVARCHAR(50) DEFAULT NULL,
    email VARCHAR(100) DEFAULT NULL, -- Đã gộp từ phần ALTER
    ten_dang_nhap VARCHAR(50) DEFAULT NULL,
    mat_khau VARCHAR(255) DEFAULT NULL,
    so_lan_sai INT DEFAULT 0, -- Đã gộp từ phần ALTER
    thoi_gian_khoa DATETIME DEFAULT NULL, -- Đã gộp từ phần ALTER
    ma_otp VARCHAR(10) DEFAULT NULL, -- Đã gộp từ phần ALTER
    het_han_otp DATETIME DEFAULT NULL, -- Đã gộp từ phần ALTER
    CONSTRAINT fk_nguoi_dung_vai_tro FOREIGN KEY (id_vai_tro) REFERENCES vai_tro(id_vai_tro)
);

-- 3. Bảng Sản Phẩm
CREATE TABLE san_pham (
    id_san_pham BIGINT IDENTITY(1,1) PRIMARY KEY,
    ten NVARCHAR(255) DEFAULT NULL,
    loai NVARCHAR(50) DEFAULT NULL,
    don_vi NVARCHAR(50) DEFAULT NULL,
    so_luong INT DEFAULT NULL,
    gia FLOAT DEFAULT NULL,
    anh VARCHAR(255) DEFAULT NULL,
    trang_thai BIT DEFAULT 1 -- Dùng BIT thay cho TINYINT(1)
);

-- 4. Bảng Đơn Hàng
CREATE TABLE don_hang (
    id_don_hang BIGINT IDENTITY(1,1) PRIMARY KEY,
    id_nguoi_dung BIGINT DEFAULT NULL,
    tong_tien FLOAT DEFAULT NULL,
    dia_chi NVARCHAR(255) DEFAULT NULL,
    email VARCHAR(255) DEFAULT NULL,
    sdt VARCHAR(20) DEFAULT NULL,
    ten NVARCHAR(255) DEFAULT NULL,
    trang_thai NVARCHAR(255) DEFAULT NULL,
    trang_thai_thanh_toan NVARCHAR(50) DEFAULT N'Chưa thanh toán', -- Đã gộp
    ma_chuyen_khoan VARCHAR(100) DEFAULT NULL, -- Đã gộp
    pt_thanh_toan NVARCHAR(255) DEFAULT NULL, -- Đã gộp
    ngay_dat DATETIME DEFAULT GETDATE(), -- Đã gộp (Sử dụng GETDATE())
    CONSTRAINT fk_don_hang_nguoi_dung FOREIGN KEY (id_nguoi_dung) REFERENCES nguoi_dung(id_nguoi_dung)
);

-- 5. Bảng Chi Tiết Đơn Hàng
CREATE TABLE ct_don_hang (
    id_ctdh BIGINT IDENTITY(1,1) PRIMARY KEY,
    id_don_hang BIGINT DEFAULT NULL,
    id_san_pham BIGINT DEFAULT NULL,
    so_luong INT DEFAULT NULL,
    gia FLOAT DEFAULT NULL,
    CONSTRAINT fk_ct_don_hang_don_hang FOREIGN KEY (id_don_hang) REFERENCES don_hang(id_don_hang),
    CONSTRAINT fk_ct_don_hang_san_pham FOREIGN KEY (id_san_pham) REFERENCES san_pham(id_san_pham)
);

-- 6. Bảng Giỏ Hàng
CREATE TABLE gio_hang (
    id_gio_hang BIGINT IDENTITY(1,1) PRIMARY KEY,
    id_nguoi_dung BIGINT DEFAULT NULL,
    so_luong_sp INT DEFAULT NULL,
    CONSTRAINT fk_gio_hang_nguoi_dung FOREIGN KEY (id_nguoi_dung) REFERENCES nguoi_dung(id_nguoi_dung)
);

-- 7. Bảng Chi Tiết Giỏ Hàng
CREATE TABLE ct_gio_hang (
    id_ctgh BIGINT IDENTITY(1,1) PRIMARY KEY,
    id_gio_hang BIGINT DEFAULT NULL,
    id_san_pham BIGINT DEFAULT NULL,
    so_luong BIGINT DEFAULT NULL,
    gia FLOAT DEFAULT NULL,
    CONSTRAINT fk_ct_gio_hang_gio_hang FOREIGN KEY (id_gio_hang) REFERENCES gio_hang(id_gio_hang),
    CONSTRAINT fk_ct_gio_hang_san_pham FOREIGN KEY (id_san_pham) REFERENCES san_pham(id_san_pham)
);

