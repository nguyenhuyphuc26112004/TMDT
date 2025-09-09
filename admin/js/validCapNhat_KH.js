var formCapNhatKH = document.getElementById('formCapNhatKH');


var hoVaTen = document.getElementById('name');
var soDienThoai = document.getElementById('tel');
var diaChi = document.getElementById('address');

var hoVaTenError = document.getElementById('hoVaTenError');
var soDienThoaiError = document.getElementById('soDienThoaiError');
var diaChiError = document.getElementById('diaChiError');

formCapNhatKH.addEventListener("submit",function (e) {
        var check = true;


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
        }
        else { // có dữ liệu rồi
            // ẩn lỗicheck = false ;
            soDienThoaiError.style.display = "none";
        }
        if (diaChi.value.trim() === "") { // chưa có dữ liệu
            // hiển thị lỗi
            check = false; // đánh dấu là có lỗi
            diaChiError.style.display = "block";
        }
        else { // có dữ liệu rồi
            // ẩn lỗicheck = false ;
            diaChiError.style.display = "none";
        }
        if (!check) {
            //ngăn chặn sự kiện submit trang khi có lỗi
            e.preventDefault();
        }
    }
)
