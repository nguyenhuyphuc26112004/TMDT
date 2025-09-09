var formDoiMatKhau = document.getElementById('formDoiMatKhau');
var tenDangNhap = document.getElementById('tenDangNhap');
var matKhauHienTai = document.getElementById('matKhauHienTai');
var matKhauMoi = document.getElementById('matKhauMoi');

var tenDangNhapError = document.getElementById('tenDangNhapError');
var matKhauHienTaiError = document.getElementById('matKhauHienTaiError');
var matKhauMoiError = document.getElementById('matKhauMoiError');

formDoiMatKhau.addEventListener('submit',
    function (event) {
        var isValid = true;
        if (tenDangNhap.value.trim() === '') {
            tenDangNhapError.style.display = 'block';
            tenDangNhap.classList.add('input-error');
            isValid = false;
        } else {
            tenDangNhapError.style.display = 'none';
            tenDangNhap.classList.remove('input-error');
        }

        if (matKhauHienTai.value.trim() === '') {
            matKhauHienTaiError.style.display = 'block';
            matKhauHienTai.classList.add('input-error');
            isValid = false;
        } else {
            matKhauHienTaiError.style.display = 'none';
            matKhauHienTai.classList.remove('input-error');
        }
        
        if (matKhauMoi.value.trim() === '') {
            matKhauMoiError.style.display = 'block';
            matKhauMoi.classList.add('input-error');
            isValid = false;
        } else {
            matKhauMoiError.style.display = 'none';
            matKhauMoi.classList.remove('input-error');
        }

        if (!isValid) {
            event.preventDefault();
        }
    }
);

