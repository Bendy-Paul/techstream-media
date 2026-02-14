    <!-- Footer -->
    <footer id="contact" class="no-print">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-5">
                    <h4 class="text-dark mb-3 d-flex align-items-center gap-2">
                        <i class="bi bi-hexagon-fill text-primary-accent"></i>
                        TECH<span>MEDIA</span>
                    </h4>
                    <p class="text-muted pe-4">The number one digital ecosystem for Nigerian tech companies, startups, and innovators. Connecting the future, today.</p>
                    <div class="d-flex gap-2">
                        <a href="#" class="social-btn"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="social-btn"><i class="fab fa-linkedin-in"></i></a>
                        <!-- <a href="#" class="social-btn"><i class="fab fa-instagram"></i></a> -->
                        <a href="#" class="social-btn"><i class="fab fa-facebook-f"></i></a>
                    </div>
                </div>
                <div class="col-lg-2 col-6 mb-4">
                    <h6 class="text-dark mb-3 text-uppercase fw-bold small ls-1">Company</h6>
                    <a href="#" class="footer-link">About Us</a>
                    <!-- <a href="#" class="footer-link">Careers</a> -->
                    <a href="#" class="footer-link">Privacy Policy</a>
                    <a href="#" class="footer-link">Terms of Service</a>
                </div>
                <div class="col-lg-2 col-6 mb-4">
                    <h6 class="text-dark mb-3 text-uppercase fw-bold small ls-1">Resources</h6>
                    <a href="#" class="footer-link">Blog</a>
                    <a href="#" class="footer-link">Contact Us</a>
                    <a href="#" class="footer-link">Media Kit</a>
                    <!-- <a href="#" class="footer-link">API Access</a> -->
                </div>
                <div class="col-lg-4 mb-4">
                    <h6 class="text-dark mb-3 text-uppercase fw-bold small ls-1">Newsletter</h6>
                    <p class="text-muted small">Get the latest tech events and funding news delivered to your inbox.</p>
                    <div class="input-group">
                        <input type="email" class="form-control bg-white border-secondary" placeholder="Enter email">
                        <button class="btn btn-primary" style="background-color: var(--primary-accent); border:none;">Subscribe</button>
                    </div>
                </div>
            </div>
            <div class="border-top border-secondary border-opacity-25 pt-4 mt-4 text-center text-muted small">
                &copy; 2025 Tech Media Directory. All Rights Reserved. Built for Nigeria.
            </div>
        </div>
    </footer>



        <script>
        $(document).ready(function() {

            // 2. Ticker Logic (Fade transition every 12s)
            const tickerItems = $('.ticker-item');
            let currentTicker = 0;
            
            function updateTicker() {
                $(tickerItems[currentTicker]).removeClass('active');
                currentTicker = (currentTicker + 1) % tickerItems.length;
                $(tickerItems[currentTicker]).addClass('active');
                
                // Reset Countdown for demo purposes in this specific view
                startCountdown($(tickerItems[currentTicker]));
            }

            // Initial countdown start
            startCountdown($(tickerItems[0]));
            setInterval(updateTicker, 12000); // 12 seconds switch

            function startCountdown(element) {
                // Placeholder logic
                let display = element.find('.countdown-timer');
            }

            // // 3. Number Counter Animation (for Stats)
            // let counted = false;
            // $(window).scroll(function() {
            //     var oTop = $('#about').offset().top - window.innerHeight;
            //     if (counted == false && $(window).scrollTop() > oTop) {
            //         $('.stats-number').each(function() {
            //             var $this = $(this),
            //             countTo = $this.attr('data-target');
            //             $({ countNum: $this.text() }).animate({
            //                     countNum: countTo
            //                 },
            //                 {
            //                     duration: 2500,
            //                     easing: 'swing',
            //                     step: function() {
            //                         $this.text(Math.floor(this.countNum));
            //                     },
            //                     complete: function() {
            //                         $this.text(this.countNum);
            //                         if(countTo > 1000) $this.text(Math.floor(this.countNum/1000) + 'K+');
            //                     }
            //                 });
            //         });
            //         counted = true;
            //     }
            // });

            // 4. Map Tooltip Logic
            $('.state-path').hover(function(e) {
                const name = $(this).data('name');
                const count = $(this).data('count');
                const tooltip = $('#map-tooltip');
                
                tooltip.html(`<strong>${name}</strong><br><span class="text-primary-accent">${count}</span>`).css('opacity', 1);
            }, function() {
                $('#map-tooltip').css('opacity', 0);
            });

            $('.state-path').mousemove(function(e) {
                $('#map-tooltip').css({
                    left: e.pageX + 15,
                    top: e.pageY + 15
                });
            });


            // 6. Mobile Menu Toggle
            $('.mobile-menu-toggle').click(function() {
                $('#mobileMenu').addClass('active');
            });

            $('.mobile-menu-close').click(function() {
                $('#mobileMenu').removeClass('active');
            });

            // 7. Mobile Dropdown Toggle
            $('.mobile-dropdown > .mobile-nav-link').click(function(e) {
                e.preventDefault();
                $(this).parent().toggleClass('active');
            });
        });
    </script>