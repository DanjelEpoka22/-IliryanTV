</main>

    <!-- Footer -->
    <footer class="bg-dark text-light pt-5 mt-5">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-4 col-md-6">
                    <h4 class="text-danger mb-4">
                        <i class="fas fa-tv me-2"></i>IliryanTV
                    </h4>
                    <p class="text-light">Televizioni patriotik shqiptar - Lajmet më të fundit në kohë reale</p>
                    <div class="social-links mt-4">
                        <a href="#" class="btn btn-outline-light btn-sm me-2"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="btn btn-outline-light btn-sm me-2"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="btn btn-outline-light btn-sm me-2"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="btn btn-outline-light btn-sm"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
                
                <div class="col-lg-2 col-md-6">
                    <h5 class="text-danger mb-4">Lidhje të Shpejta</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="<?php echo SITE_URL; ?>/index.php" class="text-light text-decoration-none">Kryefaqja</a></li>
                        <li class="mb-2"><a href="<?php echo SITE_URL; ?>/news.php" class="text-light text-decoration-none">Lajmet</a></li>
                        <li class="mb-2"><a href="<?php echo SITE_URL; ?>/sports.php" class="text-light text-decoration-none">Sporti</a></li>
                        <li class="mb-2"><a href="<?php echo SITE_URL; ?>/shows.php" class="text-light text-decoration-none">Programet TV</a></li>
                    </ul>
                </div>
                
                <div class="col-lg-3 col-md-6">
                    <h5 class="text-danger mb-4">Na Kontaktoni</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><i class="fas fa-map-marker-alt text-danger me-2"></i> Rruga Iliria, Tiranë</li>
                        <li class="mb-2"><i class="fas fa-phone text-danger me-2"></i> +355 4 123 4567</li>
                        <li class="mb-2"><i class="fas fa-envelope text-danger me-2"></i> info@iliryantv.com</li>
                    </ul>
                </div>
                
                <div class="col-lg-3 col-md-6">
                    <h5 class="text-danger mb-4">Newsletter</h5>
                    <p>Regjistrohu për lajmet më të fundit</p>
                    <div class="input-group">
                        <input type="email" class="form-control" placeholder="Email juaj">
                        <button class="btn btn-danger" type="button">Abonohu</button>
                    </div>
                </div>
            </div>
            
            <hr class="my-4 bg-secondary">
            
            <div class="text-center py-3">
                <p class="mb-0">&copy; <?php echo date('Y'); ?> IliryanTV. Të gjitha të drejtat e rezervuara.</p>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="https://cdn.jsdelivr.net/gh/mcstudios/glightbox/dist/js/glightbox.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script src="<?php echo SITE_URL; ?>/assets/js/main.js"></script>
    
    <!-- Initialize Libraries -->
    <script>
        // Hide spinner immediately before anything else
        const spinner = document.getElementById('loading-spinner');
        if (spinner) {
            spinner.style.display = 'none';
        }

        // Initialize AOS
        if (typeof AOS !== 'undefined') {
            AOS.init({
                duration: 1000,
                once: true,
                offset: 100
            });
        }

        // Initialize GLightbox
        if (typeof GLightbox !== 'undefined') {
            const lightbox = GLightbox({
                touchNavigation: true,
                loop: true,
                autoplayVideos: true
            });
        }
    </script>
</body>
</html>