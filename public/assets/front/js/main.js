$(function () {
    // Initialize AOS (disable on small devices to reduce heavy effects)
    AOS.init({
        duration: 700,
        once: true,
        mirror: false,
        disable: function () {
            return window.innerWidth < 768;
        },
    });

    $(".nice-select").niceSelect();

    // Smooth scroll for anchor links
    $('a[href^="#"]').on("click", function (e) {
        var target = $(this.getAttribute("href"));
        if (target.length) {
            e.preventDefault();
            $("html, body")
                .stop()
                .animate({ scrollTop: target.offset().top - 70 }, 700, "swing");
        }
    });

    // Hide scroll-down chevrons once user scrolls past hero
    // function toggleScrollChev() {
    //   var heroBottom = $('#home').offset().top + $('#home').outerHeight();
    //   if ($(window).scrollTop() > heroBottom - 80) {
    //     $('.scroll-down-wrap').fadeOut(200);
    //   } else {
    //     $('.scroll-down-wrap').fadeIn(200);
    //   }
    // }
    // $(window).on('scroll resize', toggleScrollChev);
    // toggleScrollChev();

    // Navbar shadow when scrolled
    function toggleNavbarShadow() {
        if ($(window).scrollTop() > 12) {
            $("#siteNavbar").addClass("navbar-scrolled");
        } else {
            $("#siteNavbar").removeClass("navbar-scrolled");
        }
    }
    $(window).on("scroll resize", toggleNavbarShadow);
    toggleNavbarShadow();

    // Animated counters
    var ideasCounter = new countUp.CountUp("counter-ideas", 12450, {
        duration: 2.2,
        separator: ",",
    });
    var predCounter = new countUp.CountUp("counter-predictions", 3240, {
        duration: 2.2,
        separator: ",",
    });
    // Start counters when hero visible
    function startCounters() {
        var el = document.getElementById("counter-ideas");
        if (el && el.getBoundingClientRect().top < window.innerHeight) {
            if (!ideasCounter.error) ideasCounter.start();
            if (!predCounter.error) predCounter.start();
            $(window).off("scroll", startCounters);
        }
    }
    $(window).on("scroll", startCounters);
    startCounters();

    // Pricing toggle
    $("#billingToggle").on("change", function () {
        var yearly = $(this).is(":checked");
        $(".pricing-card .amount").each(function () {
            var $t = $(this);
            var monthly = Number($t.data("monthly"));
            var yearlyVal = Number($t.data("yearly"));
            var display = yearly ? yearlyVal : monthly;
            $t.text(display);
        });
    });

    // Subscribe form (footer) - simple front-end feedback
    $("#subscribeForm").on("submit", function (e) {
        e.preventDefault();
        var email = $("#subscribeEmail").val();
        if (!email || email.indexOf("@") === -1) {
            $("#subscribeMessage")
                .text("Please enter a valid email")
                .css("color", "#ffb4c6");
            return;
        }
        $("#subscribeMessage")
            .text("Thanks! You are subscribed.")
            .css("color", "#bde4ff");
        $("#subscribeEmail").val("");
    });

    // Create a sample Validation Score chart for demonstration in a modal or future section
    // We'll append a small canvas to the body hidden and show when needed
    var scoreChartCanvas = $(
        '<canvas id="scoreChart" width="220" height="220" style="display:none"></canvas>'
    );
    $("body").append(scoreChartCanvas);
    var ctx = document.getElementById("scoreChart").getContext("2d");
    var scoreChart = new Chart(ctx, {
        type: "doughnut",
        data: {
            labels: ["Validation", "Risk"],
            datasets: [
                {
                    data: [72, 28],
                    backgroundColor: ["#7f00ff", "#ff5a7d"],
                },
            ],
        },
        options: {
            cutout: "75%",
            plugins: { legend: { display: false }, tooltip: { enabled: false } },
        },
    });

    // Animated pulse on hero CTAs (subtle)
    setInterval(function () {
        $(".hero-cta").each(function (i, el) {
            $(el).animate({ opacity: 0.92 }, 120).animate({ opacity: 1 }, 120);
        });
    }, 2500);

    // Accessibility: keyboard focus for feature cards
    $(".feature-card .stretched-link")
        .on("focus", function () {
            $(this).closest(".feature-card").addClass("focus");
        })
        .on("blur", function () {
            $(this).closest(".feature-card").removeClass("focus");
        });


    /**=====================================================
     * Login form submission
     *=====================================================*/
    $("#loginForm").on('submit', function (e) {
        e.preventDefault();
        const submitBtn = $('#loginBtn');
        const originalText = submitBtn.html();

        submitBtn.prop('disabled', true)
            .html('<i class="fas fa-spinner fa-spin me-2"></i>Signing in...');

        loginSubmitForm(submitBtn, originalText);
    });

    function loginSubmitForm(submitBtn, originalText) {
        let loginForm = document.getElementById('loginForm');
        let fd = new FormData(loginForm);
        let url = $("#loginForm").attr('action');
        let method = $("#loginForm").attr('method');

        // Clear previous errors and borders
        $('.em').each(function () {
            $(this).html('');
        });
        $('.is-invalid').removeClass('is-invalid');
        $('.form-control').css('border-color', '');

        $.ajax({
            url: url,
            method: method,
            data: fd,
            contentType: false,
            processData: false,
            success: function (data) {
                submitBtn.prop('disabled', false).html(originalText);

                if (data.success) {
                    $.toast({
                        heading: 'Success',
                        text: data.message,
                        showHideTransition: 'plain',
                        icon: 'success',
                        allowToastClose: true,
                        position: 'top-right',
                        hideAfter: 2000,
                    });

                    // Redirect on success
                    setTimeout(function () {
                        window.location.href = data.redirect_url;
                    }, 1500);
                }
            },
            error: function (error) {
                submitBtn.prop('disabled', false).html(originalText);

                if (error.responseJSON) {
                    // Show error message
                    $.toast({
                        heading: 'Error',
                        text: error.responseJSON.message || 'An error occurred',
                        showHideTransition: 'plain',
                        icon: 'error',
                        allowToastClose: true,
                        position: 'top-right',
                        hideAfter: 4000,
                    });

                    // Handle validation errors
                    if (error.status === 422 && error.responseJSON.errors) {
                        const errors = error.responseJSON.errors;
                        for (let key in errors) {
                            const errorElement = document.getElementById('err_' + key);
                            if (errorElement) {
                                errorElement.innerHTML = errors[key][0];
                            }
                            $(`[name="${key}"]`).addClass('is-invalid');
                        }
                    }

                    // Handle redirect for specific errors (if needed)
                    if (error.responseJSON.redirect_url) {
                        setTimeout(function () {
                            window.location.href = error.responseJSON.redirect_url;
                        }, 2000);
                    }
                } else {
                    // Generic error handling
                    $.toast({
                        heading: 'Error',
                        text: 'Network error or server unavailable',
                        showHideTransition: 'plain',
                        icon: 'error',
                        allowToastClose: true,
                        position: 'top-right',
                        hideAfter: 4000,
                    });
                }
            }
        });
    }

    // Password toggle functionality
    $('.password-toggle').on('click', function () {
        const passwordInput = $('#passwordInput');
        const icon = $(this).find('i');

        if (passwordInput.attr('type') === 'password') {
            passwordInput.attr('type', 'text');
            icon.removeClass('fa-eye').addClass('fa-eye-slash');
        } else {
            passwordInput.attr('type', 'password');
            icon.removeClass('fa-eye-slash').addClass('fa-eye');
        }
    });


    //testimonial slider start
    $("#testimonialCarousel .carousel-item").each(function () {
        var $this = $(this);
        var $next = $this.next();
        if (!$next.length) $next = $this.siblings(":first");
        $next.children().first().clone().appendTo($this);
    });

    $("#testimonialCarousel").carousel({
        interval: 6000,
        pause: "hover",
        ride: "carousel",
    });
    //testimonial slider end

    // Blog search functionality
    $(".blog-search input").on("keyup", function () {
        const searchTerm = $(this).val().toLowerCase();

        $(".blog-card").each(function () {
            const title = $(this).find("h3").text().toLowerCase();
            const content = $(this).find("p").text().toLowerCase();

            if (title.includes(searchTerm) || content.includes(searchTerm)) {
                $(this).parent().fadeIn();
            } else {
                $(this).parent().fadeOut();
            }
        });
    });

    // Blog category filters
    $(".category-filter").on("click", function () {
        const category = $(this).text().toLowerCase();

        // Toggle active state
        $(".category-filter").removeClass("active");
        $(this).addClass("active");

        if (category === "all") {
            $(".blog-card").parent().fadeIn();
        } else {
            $(".blog-card").each(function () {
                const cardCategory = $(this).data("category").toLowerCase();
                if (cardCategory === category) {
                    $(this).parent().fadeIn();
                } else {
                    $(this).parent().fadeOut();
                }
            });
        }
    });

    /**
     * Real-time subdomain preview
     */
    $("#username").on("input", function () {
        const username = $(this)
            .val()
            .toLowerCase()
            .replace(/[^a-z0-9]/g, "");
        $(".subdomain").text(username || "yourname");
    });

    // Password strength checker
    $("#password").on("input", function () {
        const password = $(this).val();

        // Check requirements
        const requirements = {
            length: password.length >= 8,
            uppercase: /[A-Z]/.test(password),
            lowercase: /[a-z]/.test(password),
            number: /[0-9]/.test(password),
            special: /[!@#$%^&*]/.test(password),
        };

        // Update requirement indicators
        Object.keys(requirements).forEach((req) => {
            const $requirement = $(`.requirement-item[data-requirement="${req}"]`);
            if (requirements[req]) {
                $requirement.addClass("valid").removeClass("invalid");
                $requirement.find("i").removeClass("fa-circle").addClass("fa-check");
            } else {
                $requirement.addClass("invalid").removeClass("valid");
                $requirement.find("i").removeClass("fa-check").addClass("fa-circle");
            }
        });
    });

    // dropdown for mobile menu

    let deviceWidth = window.innerWidth;

    if (deviceWidth < 992) {
        $(".dropdown").on("click", function () {
            $(this).find(".dropdown-menu").first().stop(true, true).slideToggle();
        });
    }
});
