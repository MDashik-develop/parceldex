(function ($) {
  "use strict";

  // Sticky Navbar
  $(window).scroll(function () {
    if ($(this).scrollTop() > 0) {
      $(".navbar-wrapper").addClass("nav-sticky");
    } else {
      $(".navbar-wrapper").removeClass("nav-sticky");
    }
  });

  //Mobile Nav Hide Show
  if ($(".mobile-menu").length) {
    var mobileMenuContent = $(
      ".navbar-wrapper .navbar-menu .navbar-nav"
    ).html();
    $(".mobile-menu .menu-box .menu-outer .nav_link").append(mobileMenuContent);

    //Menu Toggle Btn
    $(".mobile-nav-toggler").on("click", function () {
      $("body").addClass("mobile-menu-visible");
    });

    //Menu Toggle Btn
    $(".mobile-menu .menu-backdrop,.mobile-menu .close-btn").on(
      "click",
      function () {
        $("body").removeClass("mobile-menu-visible");
      }
    );
  }
})(jQuery);

// custom testimonial slider js code

const slider = document.querySelector(".slider");
const dots = document.querySelectorAll(".dot");

let slideIndex = 0;
const slidesPerPage = 3;
const slideInterval = 3000;
let slideTimer;

showSlides();

dots.forEach((dot, index) => {
  dot.addEventListener("click", () => {
    clearInterval(slideTimer);
    slideIndex = index * slidesPerPage;
    showSlides();
    startSlideTimer();
  });
});

function startSlideTimer() {
  slideTimer = setInterval(() => {
    slideIndex += slidesPerPage;
    showSlides();
  }, slideInterval);
}

function showSlides() {
  const slides = Array.from(document.querySelectorAll(".slider-item"));
  const maxIndex = slides.length - slidesPerPage;

  if (slideIndex < 0) {
    slideIndex = maxIndex;
  } else if (slideIndex > maxIndex) {
    slideIndex = 0;
  }
  if(slider){
    slider.style.transform = `translateX(-${
      slideIndex * (100 / slidesPerPage)
    }%)`;
  }
  

  dots.forEach((dot, index) => {
    dot.classList.toggle("active", index * slidesPerPage === slideIndex);
  });
}

startSlideTimer();
// custom testimonial slider js code
