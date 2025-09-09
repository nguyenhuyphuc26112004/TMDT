// console.log("ok");

// lấy data từ from về
var tenSanPham = document.getElementById('tenSanPham');
var soLuong = document.getElementById('soLuong');
var gia = document.getElementById('gia');
var moTaSanPham = document.getElementById('moTaSanPham');
var anhSanPham = document.getElementById('anhSanPham');

var formTaoMoiSP = document.getElementById('formTaoMoiSP');

// lấy thẻ hiển thị lỗi
var tenSanPhamError = document.getElementById('tenSanPhamError');
var soLuongError = document.getElementById('soLuongError');
var soLuongAmError = document.getElementById('soLuongAmError');
var giaError = document.getElementById('giaError');
var giaAmError = document.getElementById('giaAmError');
var moTaSanPhamError = document.getElementById('moTaSanPhamError');
var anhSanPhamError = document.getElementById('anhSanPhamError');
var anhKhongHopLeError = document.getElementById('anhKhongHopLeError');


// lắng nghe sự kiện form
formTaoMoiSP.addEventListener("submit", function (e) { // onsubmit -> trang sẽ load như bthg 
    // Biến kiểm tra xem có lỗi hay không
    var check = true;

    // Validate dữ liệu đầu vào
    // tenSanPham.value.trim() : loại bỏ khoảng trắng đầu cuối
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

    if (anhSanPham.value.trim() === "") { // chưa có dữ liệu
        // hiển thị lỗi
        check = false; // đánh dấu là có lỗi
        anhSanPhamError.style.display = "block";
        anhKhongHopLeError.style.display = "none";
    }else if(!anhSanPham.value.match(/\.(jpg|jpeg|png)$/)) { // kiểm tra file
        check = false;
        anhSanPhamError.style.display = "none";
        anhKhongHopLeError.style.display = "block";
    } else { // có dữ liệu rồi
        // ẩn lỗicheck = false ;
        anhSanPhamError.style.display = "none";
        anhKhongHopLeError.style.display = "none";
    }
    // if (!anhSanPham.value.match(/\.(jpg|jpeg|png)$/)) {
    //     check = false;
    //     anhSanPhamError.style.display = "block";
    //     anhSanPhamError.innerText = "File phải là ảnh (.jpg, .jpeg, .png)";
    // }
    

    if (!check) {
        //ngăn chặn sự kiện submit trang khi có lỗi
        e.preventDefault();
    }
    // nếu không có lỗi -> check = true -> form vẫn sẽ được submit 
})
