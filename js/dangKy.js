var hoVaTen = document.getElementById('hoVaTen');
var soDienThoai = document.getElementById('soDienThoai');
var tenDangNhap = document.getElementById('tenDangNhap');
var matKhau = document.getElementById('matKhau');
var nhapLaiMatKhau = document.getElementById('nhapLaiMatKhau');

var formDangKy = document.getElementById('formDangKy');

var hoVaTenError = document.getElementById('hoVaTenError');
var soDienThoaiError = document.getElementById('soDienThoaiError');
var tenDangNhapError = document.getElementById('tenDangNhapError');
var matKhauError = document.getElementById('matKhauError');
var nhapLaiMatKhauError = document.getElementById('nhapLaiMatKhauError');

formDangKy.addEventListener("submit", function (e) { 
    var check = true;
    if (hoVaTen.value.trim() === "") { 
        check = false;
        hoVaTenError.style.display = "block";
    }
    else { 
        hoVaTenError.style.display = "none";
    }

    if (soDienThoai.value.trim() === "") { 
        soDienThoaiError.style.display = "block";
    } else {
        soDienThoaiError.style.display = "none";
    }

    if (tenDangNhap.value.trim() === "") {
        check = false;
        tenDangNhapError.style.display = "block";
    } else {
        tenDangNhapError.style.display = "none";
    }

    if (matKhau.value.trim() === "") {
        check = false;
        matKhauError.style.display = "block";
    } else {
        matKhauError.style.display = "none";
    }

    if (nhapLaiMatKhau.value.trim() === "") {
        check = false;
        nhapLaiMatKhauError.style.display = "block";
    } else {
        nhapLaiMatKhauError.style.display = "none";
    }

    if (matKhau.value !== nhapLaiMatKhau.value) {
        check = false;
        nhapLaiMatKhauError.style.display = "block";
        nhapLaiMatKhauError.innerHTML = "Mật khẩu nhập lại không khớp với mật khẩu ";
    }


    if (!check) {
        //ngăn chặn sự kiện submit trang khi có lỗi
        e.preventDefault();
    }
    // nếu không có lỗi -> check = true -> form vẫn sẽ được submit 
})
function togglePassword(inputId, eyeElement) {
    const inputField = document.getElementById(inputId);
    const icon = eyeElement.querySelector('i');

    if (inputField.type === 'password') {
        inputField.type = 'text';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    } else {
        inputField.type = 'password';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    }
}
