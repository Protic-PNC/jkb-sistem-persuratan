(function ($) {
    "use strict";

    // Spinner
    var spinner = function () {
        setTimeout(function () {
            if ($("#spinner").length > 0) {
                $("#spinner").removeClass("show");
            }
        }, 1);
    };
    spinner();

    // Initiate the wowjs
    new WOW().init();

    // Sticky Navbar
    $(window).scroll(function () {
        if ($(this).scrollTop() > 300) {
            $(".sticky-top").addClass("shadow-sm").css("top", "0px");
        } else {
            $(".sticky-top").removeClass("shadow-sm").css("top", "-100px");
        }
    });

    // Back to top button
    $(window).scroll(function () {
        if ($(this).scrollTop() > 300) {
            $(".back-to-top").fadeIn("slow");
        } else {
            $(".back-to-top").fadeOut("slow");
        }
    });
    $(".back-to-top").click(function () {
        $("html, body").animate({ scrollTop: 0 }, 1500, "easeInOutExpo");
        return false;
    });

    // Facts counter
    $('[data-toggle="counter-up"]').counterUp({
        delay: 10,
        time: 2000,
    });

    // Roadmap carousel
    $(".roadmap-carousel").owlCarousel({
        autoplay: true,
        smartSpeed: 1000,
        margin: 25,
        loop: true,
        dots: false,
        nav: true,
        navText: [
            '<i class="bi bi-chevron-left"></i>',
            '<i class="bi bi-chevron-right"></i>',
        ],
        responsive: {
            0: {
                items: 1,
            },
            576: {
                items: 2,
            },
            768: {
                items: 3,
            },
            992: {
                items: 4,
            },
            1200: {
                items: 5,
            },
        },
    });

    // Testimonials carousel
    $(".testimonial-carousel").owlCarousel({
        autoplay: true,
        smartSpeed: 1000,
        margin: 25,
        loop: true,
        center: true,
        dots: false,
        nav: true,
        navText: [
            '<i class="bi bi-chevron-left"></i>',
            '<i class="bi bi-chevron-right"></i>',
        ],
        responsive: {
            0: {
                items: 1,
            },
            768: {
                items: 2,
            },
            992: {
                items: 3,
            },
        },
    });
})(jQuery);

// Service cards animation
document.addEventListener("DOMContentLoaded", function () {
    const cards = document.querySelectorAll(".service-card");
    const observer = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    const index = Array.from(cards).indexOf(entry.target);
                    setTimeout(() => {
                        entry.target.style.opacity = "1";
                        entry.target.style.transform = "translateY(0)";
                    }, index * 150);
                    observer.unobserve(entry.target);
                }
            });
        },
        {
            threshold: 0.2,
        }
    );
    cards.forEach((card) => {
        observer.observe(card);
    });

    cards.forEach((card) => {
        const icon = card.querySelector(".icon-container i");
        const iconContainer = card.querySelector(".icon-container");
        const title = card.querySelector("h4");

        card.addEventListener("mouseenter", () => {
            icon.style.transform = "scale(1.15) rotate(5deg)";
            iconContainer.style.boxShadow = "0 6px 16px rgba(0,0,0,0.15)";

            if (icon.style.color) {
                title.style.color = icon.style.color;
            } else if (icon.classList.contains("text-primary")) {
                title.style.color = "#4e73df";
            }
        });

        card.addEventListener("mouseleave", () => {
            icon.style.transform = "scale(1) rotate(0)";
            iconContainer.style.boxShadow = "none";
            title.style.color = "#333";
        });
    });
});

// Edit account card
document.addEventListener("DOMContentLoaded", function () {
    const toggleButtons = document.querySelectorAll(".toggle-password");
    toggleButtons.forEach((button) => {
        button.addEventListener("click", function () {
            const targetId = this.getAttribute("data-target");
            const passwordInput = document.getElementById(targetId);
            const icon = this.querySelector("i");

            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                icon.classList.remove("bi-eye");
                icon.classList.add("bi-eye-slash");
            } else {
                passwordInput.type = "password";
                icon.classList.remove("bi-eye-slash");
                icon.classList.add("bi-eye");
            }
        });
    });

    const profilePictureInput = document.getElementById("profile_picture");
    if (profilePictureInput) {
        profilePictureInput.addEventListener("change", function () {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    const imageContainer =
                        document.querySelector(
                            ".rounded-circle.overflow-hidden"
                        ) || document.querySelector(".rounded-circle.bg-light");

                    if (imageContainer) {
                        imageContainer.innerHTML = "";
                        imageContainer.classList.remove(
                            "bg-light",
                            "d-flex",
                            "align-items-center",
                            "justify-content-center"
                        );
                        imageContainer.classList.add("overflow-hidden");

                        const img = document.createElement("img");
                        img.src = e.target.result;
                        img.classList.add("w-100", "h-100");
                        img.style.objectFit = "cover";
                        imageContainer.appendChild(img);
                    }
                };
                reader.readAsDataURL(this.files[0]);
            }
        });
    }
});
