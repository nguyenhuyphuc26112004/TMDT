var banner = 0;
    const slides = document.querySelectorAll(".main-picture img");
    function showSlide(index){
        slides.forEach((slide,i) =>{
            slide.classList.toggle('active', i === index);
        });
    }
    function showNext(){
        banner = (banner + 1 ) % slides.length;
        showSlide(banner);
    }
    function showPrev(){
        banner = (banner - 1 + slides.length) % slides.length;
        showSlide(banner);
    }
// let currentIndex = 0;
//         const slides = document.querySelectorAll('.slide');

//         function showSlide(index) {
//             slides.forEach((slide, i) => {
//                 slide.classList.remove('active');
//                 if (i === index) {
//                     slide.classList.add('active');
//                 }
//             });
//         }

//         function nextSlide() {
//             currentIndex = (currentIndex + 1) % slides.length;
//             showSlide(currentIndex);
//         }

//         setInterval(nextSlide, 3000); 
    