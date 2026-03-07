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
