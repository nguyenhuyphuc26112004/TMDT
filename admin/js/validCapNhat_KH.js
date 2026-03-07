// Lấy form và các trường nhập liệu
var formCapNhatKH = document.getElementById('formCapNhatKH');
var hoVaTen = document.getElementById('name');
var soDienThoai = document.getElementById('tel');

// Lấy các thẻ hiển thị lỗi
var hoVaTenError = document.getElementById('hoVaTenError');
var soDienThoaiError = document.getElementById('soDienThoaiError');

formCapNhatKH.addEventListener("submit", function (e) {
    var check = true;

    // 1. Kiểm tra Họ và Tên
    if (hoVaTen.value.trim() === "") {
        check = false;
        hoVaTenError.innerText = "Họ và tên không được để trống";
        hoVaTenError.style.display = "block";
        hoVaTen.style.borderColor = "#e74c3c"; // Đổi màu viền ô nhập thành đỏ
    } else {
        hoVaTenError.style.display = "none";
        hoVaTen.style.borderColor = "#ccc";
    }

    // 2. Kiểm tra Số điện thoại
    var sdtValue = soDienThoai.value.trim();
    // Biểu thức chính quy kiểm tra SĐT (chỉ chứa số, từ 10-11 ký tự)
    var vnf_regex = /((09|03|07|08|05)+([0-9]{8})\b)/g;

    if (sdtValue === "") {
        check = false;
        soDienThoaiError.innerText = "Số điện thoại không được để trống";
        soDienThoaiError.style.display = "block";
        soDienThoai.style.borderColor = "#e74c3c";
    } else if (!vnf_regex.test(sdtValue)) {
        check = false;
        soDienThoaiError.innerText = "Số điện thoại không đúng định dạng (VD: 0987654321)";
        soDienThoaiError.style.display = "block";
        soDienThoai.style.borderColor = "#e74c3c";
    } else {
        soDienThoaiError.style.display = "none";
        soDienThoai.style.borderColor = "#ccc";
    }

    // Nếu có bất kỳ lỗi nào (check == false) thì dừng submit
    if (!check) {
        e.preventDefault();
        // Cuộn lên đầu form để người dùng thấy lỗi (nếu form dài)
        window.scrollTo({ top: 100, behavior: 'smooth' });
    }
});