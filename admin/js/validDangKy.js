// console.log("ok");

// lấy data từ from về
var hoVaTen = document.getElementById('hoVaTen');
var soDienThoai = document.getElementById('soDienThoai');
var tenDangNhap = document.getElementById('tenDangNhap');
var matKhau = document.getElementById('matKhau');
var nhapLaiMatKhau = document.getElementById('nhapLaiMatKhau');

var formDangKyAdmin = document.getElementById('formDangKyAdmin');

// lấy thẻ hiển thị lỗi
var hoVaTenError = document.getElementById('hoVaTenError');
var soDienThoaiError = document.getElementById('soDienThoaiError');
var tenDangNhapError = document.getElementById('tenDangNhapError');
var matKhauError = document.getElementById('matKhauError');
var nhapLaiMatKhauError = document.getElementById('nhapLaiMatKhauError');


// lắng nghe sự kiện form
formDangKyAdmin.addEventListener("submit", function (e) { // onsubmit -> trang sẽ load như bthg 
    // Biến kiểm tra xem có lỗi hay không
    var check = true;

    // Validate dữ liệu đầu vào
    // hoVaTen.value.trim() : loại bỏ khoảng trắng đầu cuối
    if (hoVaTen.value.trim() === "") { // chưa có dữ liệu
        // hiển thị lỗi
        check = false; // đánh dấu là có lỗi
        hoVaTenError.style.display = "block";
    }
    else { // có dữ liệu rồi
        // ẩn lỗicheck = false ;
        hoVaTenError.style.display = "none";
    }

    if (soDienThoai.value.trim() === "") { // chưa có dữ liệu
        // hiển thị lỗi
        check = false; // đánh dấu là có lỗi
        soDienThoaiError.style.display = "block";
    } else { // có dữ liệu rồi
        // ẩn lỗicheck = false ;
        soDienThoaiError.style.display = "none";
    }

    if (tenDangNhap.value.trim() === "") { // chưa có dữ liệu
        // hiển thị lỗi
        check = false; // đánh dấu là có lỗi
        tenDangNhapError.style.display = "block";
    } else { // có dữ liệu rồi
        // ẩn lỗicheck = false ;
        tenDangNhapError.style.display = "none";
    }

    if (matKhau.value.trim() === "") { // chưa có dữ liệu
        // hiển thị lỗi
        check = false; // đánh dấu là có lỗi
        matKhauError.style.display = "block";
    } else { // có dữ liệu rồi
        // ẩn lỗicheck = false ;
        matKhauError.style.display = "none";
    }

    if (nhapLaiMatKhau.value.trim() === "") { // chưa có dữ liệu
        // hiển thị lỗi
        check = false; // đánh dấu là có lỗi
        nhapLaiMatKhauError.style.display = "block";
    } else { // có dữ liệu rồi
        // ẩn lỗicheck = false ;
        nhapLaiMatKhauError.style.display = "none";
    }

    // kiểm tra mật khẩu và nhập lại mật khẩu
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
// var hoVaTen = document.getElementById('hoVaTen').value;
// var soDienThoai = document.getElementById('soDienThoai').value;
// var tenDangNhap = document.getElementById('tenDangNhap').value;
// var matKhau = document.getElementById('matKhau').value;
// var nhapLaiMatKhau = document.getElementById('nhapLaiMatKhau').value;
// validate = function() {
//     if( hoVaTen == ""){
//         document.getElementById('hoVaTenError')
//     }
// }
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