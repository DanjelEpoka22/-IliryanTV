// Modern JavaScript with GSAP animations and interactive features
class IllyrianTV {
    constructor() {
        this.init();
    }

    init() {
        this.hideLoadingSpinner();
        this.initNavigation();
        this.initAnimations();
        this.initScrollEffects();
        this.initLiveUpdates();
        this.initCountdowns();
        this.initImageLazyLoading();
        this.initBackToTop();
    }

    // Hide loading spinner when page is ready
    hideLoadingSpinner() {
        const hideSpinner = () => {
            const spinner = document.getElementById('loading-spinner');
            if (spinner) {
                spinner.style.display = 'none !important';
            }
        };

        // Hide immediately
        hideSpinner();

        // Also hide on load event as fallback
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', hideSpinner);
        }
    }

    // Navigation with smooth scroll
    initNavigation() {
        // Navbar scroll effect
        window.addEventListener('scroll', () => {
            const navbar = document.querySelector('.navbar');
            if (navbar) {
                if (window.scrollY > 100) {
                    navbar.classList.add('scrolled');
                } else {
                    navbar.classList.remove('scrolled');
                }
            }
        }, { passive: true });

        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    e.preventDefault();
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    }

    // GSAP Animations
    initAnimations() {
        // Check if GSAP is available
        if (typeof gsap === 'undefined') {
            return;
        }

        // Animate hero content
        const heroSection = document.querySelector('.hero-section');
        if (heroSection) {
            gsap.from('.hero-title', {
                duration: 0.8,
                y: 50,
                opacity: 0,
                ease: 'power2.out'
            });

            gsap.from('.hero-subtitle', {
                duration: 0.8,
                y: 30,
                opacity: 0,
                delay: 0.2,
                ease: 'power2.out'
            });

            gsap.from('.hero-buttons .btn', {
                duration: 0.8,
                y: 20,
                opacity: 0,
                delay: 0.4,
                stagger: 0.1,
                ease: 'power2.out'
            });
        }

        // Animate news cards
        const newsCards = document.querySelectorAll('.news-card');
        newsCards.forEach((card, index) => {
            gsap.from(card, {
                duration: 0.6,
                y: 30,
                opacity: 0,
                delay: index * 0.05,
                ease: 'power2.out'
            });
        });
    }

    // Scroll effects (reduced for performance)
    initScrollEffects() {
        // Light parallax for hero
        window.addEventListener('scroll', () => {
            const scrolled = window.pageYOffset;
            const parallax = document.querySelector('.hero-section');
            if (parallax && scrolled < 800) {
                parallax.style.transform = `translateY(${scrolled * 0.3}px)`;
            }
        }, { passive: true });
    }

    // Live updates simulation
    initLiveUpdates() {
        // Real-time clock
        this.updateClock();
        setInterval(() => {
            this.updateClock();
        }, 1000);
    }

    updateClock() {
        const clockElement = document.getElementById('live-clock');
        if (clockElement) {
            const now = new Date();
            clockElement.textContent = now.toLocaleTimeString('sq-AL');
        }
    }

    // Countdown timers for sports events
    initCountdowns() {
        document.querySelectorAll('.countdown').forEach(countdown => {
            const targetDate = new Date(countdown.getAttribute('data-date')).getTime();
            
            const updateCountdown = () => {
                const now = new Date().getTime();
                const distance = targetDate - now;

                if (distance < 0) {
                    countdown.innerHTML = '<span class="live-badge"><i class="fas fa-circle me-1"></i> LIVE</span>';
                    return;
                }

                const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                countdown.innerHTML = `
                    <div class="countdown-item">
                        <span class="countdown-number">${days}</span>
                        <span class="countdown-label">Ditë</span>
                    </div>
                    <div class="countdown-item">
                        <span class="countdown-number">${hours}</span>
                        <span class="countdown-label">Orë</span>
                    </div>
                    <div class="countdown-item">
                        <span class="countdown-number">${minutes}</span>
                        <span class="countdown-label">Minuta</span>
                    </div>
                    <div class="countdown-item">
                        <span class="countdown-number">${seconds}</span>
                        <span class="countdown-label">Sekonda</span>
                    </div>
                `;
            };

            updateCountdown();
            setInterval(updateCountdown, 1000);
        });
    }

    // Lazy loading for images
    initImageLazyLoading() {
        if ('IntersectionObserver' in window) {
            const lazyImages = document.querySelectorAll('img[data-src]');
            
            const imageObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        img.src = img.dataset.src;
                        img.classList.remove('lazy');
                        imageObserver.unobserve(img);
                    }
                });
            });

            lazyImages.forEach(img => imageObserver.observe(img));
        }
    }

    // Back to top button
    initBackToTop() {
        const backToTop = document.createElement('button');
        backToTop.className = 'back-to-top';
        backToTop.innerHTML = '<i class="fas fa-chevron-up"></i>';
        backToTop.setAttribute('aria-label', 'Back to top');
        backToTop.style.cssText = `
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 50px;
            height: 50px;
            background: var(--primary-color, #e41e26);
            color: white;
            border: none;
            border-radius: 50%;
            cursor: pointer;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
            z-index: 999;
            font-size: 1.2rem;
        `;
        document.body.appendChild(backToTop);

        backToTop.addEventListener('click', () => {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });

        window.addEventListener('scroll', () => {
            if (window.pageYOffset > 300) {
                backToTop.style.opacity = '1';
                backToTop.style.visibility = 'visible';
            } else {
                backToTop.style.opacity = '0';
                backToTop.style.visibility = 'hidden';
            }
        }, { passive: true });
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    // Hide loading spinner immediately
    const spinner = document.getElementById('loading-spinner');
    if (spinner) {
        spinner.style.display = 'none';
    }

    // Initialize main functionality
    try {
        window.illyrianTV = new IllyrianTV();
    } catch (error) {
        console.error('Error initializing IllyrianTV:', error);
    }
});

// Emergency fallback
window.addEventListener('load', () => {
    const spinner = document.getElementById('loading-spinner');
    if (spinner) {
        spinner.style.display = 'none';
    }
});