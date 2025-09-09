var formCapNhatSP = document.getElementById('formCapNhatSP');
var tenSanPham = document.getElementById('name');
var soLuong = document.getElementById('quantify');
var gia = document.getElementById('price');
var moTaSanPham = document.getElementById('moTaSanPham');

var tenSanPhamError = document.getElementById('tenError');
var soLuongError = document.getElementById('soLuongError');
var soLuongAmError = document.getElementById('soLuongAmError');
var giaError = document.getElementById('giaError');
var giaAmError = document.getElementById('giaAmError');
var moTaSanPhamError = document.getElementById('moTaSanPhamError');

formCapNhatSP.addEventListener("submit",
    function (e) {
        var check = true;


        if (tenSanPham.value.trim() === "") { // chưa có dữ liệu
            // hiển thị lỗi
            check = false; // đánh dấu là có lỗi
            tenSanPhamError.style.display = "block";
        }
        else { // có dữ liệu rồi
            // ẩn lỗicheck = false ;
            tenSanPhamError.style.display = "none";
        }


        if (soLuong.value.trim() === "") { // kiểm tra chuỗi rỗng
            check = false; // đánh dấu là có lỗi
            soLuongError.style.display = "block";
            soLuongAmError.style.display = "none";
        } else if (isNaN(soLuong.value.trim()) || Number(soLuong.value.trim()) <= 0) {
            // kiểm tra không phải là số hoặc nhỏ hơn hoặc bằng 0
            check = false; // đánh dấu là có lỗi
            soLuongError.style.display = "none";
            soLuongAmError.style.display = "block";
        } else {
            // Dữ liệu hợp lệ
            soLuongError.style.display = "none";
            soLuongAmError.style.display = "none";
        }


        if (gia.value.trim() === "") { // kiểm tra chuỗi rỗng
            check = false; // đánh dấu là có lỗi
            giaError.style.display = "block";
            giaAmError.style.display = "none";
        } else if (isNaN(gia.value.trim()) || Number(gia.value.trim()) <= 0) {
            // kiểm tra không phải là số hoặc nhỏ hơn hoặc bằng 0
            check = false; // đánh dấu là có lỗi
            giaError.style.display = "none";
            giaAmError.style.display = "block";
        } else {
            // Dữ liệu hợp lệ
            giaError.style.display = "none";
            giaAmError.style.display = "none";
        }


        if (moTaSanPham.value.trim() === "") { // chưa có dữ liệu
            // hiển thị lỗi
            check = false; // đánh dấu là có lỗi
            moTaSanPhamError.style.display = "block";
        } else { // có dữ liệu rồi
            // ẩn lỗicheck = false ;
            moTaSanPhamError.style.display = "none";
        }

        if (!check) {
            //ngăn chặn sự kiện submit trang khi có lỗi
            e.preventDefault();
        }
    }
)
