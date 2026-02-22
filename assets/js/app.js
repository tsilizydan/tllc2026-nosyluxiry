/**
 * Nosy Luxury — Alpine.js Application
 */

function app() {
    return {
        scrolled: false,
        mobileOpen: false,

        init() {
            // Scroll reveal
            this.initScrollReveal();

            // Smooth scroll for anchor links
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();
                    const target = document.querySelector(this.getAttribute('href'));
                    if (target) {
                        target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    }
                });
            });
        },

        initScrollReveal() {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('visible');
                        observer.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.1, rootMargin: '0px 0px -50px 0px' });

            document.querySelectorAll('.reveal').forEach(el => observer.observe(el));
        },
    };
}

/**
 * Gallery lightbox component
 */
function galleryLightbox() {
    return {
        open: false,
        current: 0,
        images: [],

        init() {
            this.images = Array.from(this.$el.querySelectorAll('[data-gallery-src]'))
                .map(el => el.dataset.gallerySrc);
        },

        openImage(index) {
            this.current = index;
            this.open = true;
            document.body.style.overflow = 'hidden';
        },

        close() {
            this.open = false;
            document.body.style.overflow = '';
        },

        next() {
            this.current = (this.current + 1) % this.images.length;
        },

        prev() {
            this.current = (this.current - 1 + this.images.length) % this.images.length;
        },

        get currentImage() {
            return this.images[this.current] || '';
        }
    };
}

/**
 * Booking wizard component
 */
function bookingWizard() {
    return {
        step: 1,
        totalSteps: 3,
        formData: {
            date: '',
            guests: 1,
            specialRequests: '',
            name: '',
            email: '',
            phone: '',
        },

        nextStep() {
            if (this.step < this.totalSteps) this.step++;
        },

        prevStep() {
            if (this.step > 1) this.step--;
        },

        get totalPrice() {
            const basePrice = parseFloat(this.$el.dataset.price || 0);
            return (basePrice * this.formData.guests).toFixed(2);
        },
    };
}

/**
 * Tour filter component
 */
function tourFilter() {
    return {
        activeType: 'all',
        tours: [],

        setType(type) {
            this.activeType = type;
        },

        get filteredTours() {
            if (this.activeType === 'all') return this.tours;
            return this.tours.filter(t => t.type === this.activeType);
        }
    };
}

/**
 * Trip builder component
 */
function tripBuilder() {
    return {
        step: 1,
        formData: {
            destinations: [],
            travelDates: '',
            duration: 7,
            groupSize: 2,
            budgetRange: 'mid',
            interests: [],
            accommodationType: 'luxury',
            name: '',
            email: '',
            phone: '',
            specialRequests: '',
        },

        toggleDestination(dest) {
            const idx = this.formData.destinations.indexOf(dest);
            if (idx > -1) {
                this.formData.destinations.splice(idx, 1);
            } else {
                this.formData.destinations.push(dest);
            }
        },

        toggleInterest(interest) {
            const idx = this.formData.interests.indexOf(interest);
            if (idx > -1) {
                this.formData.interests.splice(idx, 1);
            } else {
                this.formData.interests.push(interest);
            }
        },

        get estimatedPrice() {
            const basePrices = { budget: 100, mid: 200, luxury: 350, ultra: 600 };
            const base = basePrices[this.formData.budgetRange] || 200;
            return base * this.formData.duration * this.formData.groupSize;
        },

        nextStep() { if (this.step < 4) this.step++; },
        prevStep() { if (this.step > 1) this.step--; },
    };
}

/**
 * Counter animation
 */
function counterAnimation(target, duration = 2000) {
    return {
        count: 0,
        target: target,
        init() {
            const observer = new IntersectionObserver(entries => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        this.animate();
                        observer.unobserve(entry.target);
                    }
                });
            });
            observer.observe(this.$el);
        },
        animate() {
            const start = Date.now();
            const step = () => {
                const elapsed = Date.now() - start;
                const progress = Math.min(elapsed / duration, 1);
                this.count = Math.floor(progress * this.target);
                if (progress < 1) requestAnimationFrame(step);
            };
            requestAnimationFrame(step);
        }
    };
}
