/**
 * Nosy Luxury — Alpine.js Application
 */

function app() {
    return {
        scrolled: false,

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
            paymentMethod: 'bank_transfer',
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
 * Trip builder component — with step validation
 */
function tripBuilder() {
    return {
        step: 1,
        errors: {},
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
            // Sync hidden checkboxes
            this.$nextTick(() => {
                this.$el.querySelectorAll('input[name="destinations[]"]').forEach(cb => {
                    cb.checked = this.formData.destinations.includes(cb.value);
                });
            });
            // Clear error on change
            delete this.errors.destinations;
        },

        toggleInterest(interest) {
            const idx = this.formData.interests.indexOf(interest);
            if (idx > -1) {
                this.formData.interests.splice(idx, 1);
            } else {
                this.formData.interests.push(interest);
            }
            this.$nextTick(() => {
                this.$el.querySelectorAll('input[name="interests[]"]').forEach(cb => {
                    cb.checked = this.formData.interests.includes(cb.value);
                });
            });
            delete this.errors.interests;
        },

        get estimatedPrice() {
            const basePrices = { budget: 100, mid: 200, luxury: 350, ultra: 600 };
            const base = basePrices[this.formData.budgetRange] || 200;
            return base * this.formData.duration * this.formData.groupSize;
        },

        validate(step) {
            this.errors = {};
            if (step === 1) {
                if (this.formData.destinations.length === 0) {
                    this.errors.destinations = 'Please select at least one destination.';
                }
            } else if (step === 2) {
                if (!this.formData.travelDates) {
                    this.errors.travelDates = 'Travel date is required.';
                }
                if (this.formData.duration < 3) {
                    this.errors.duration = 'Minimum 3 days.';
                }
                if (this.formData.groupSize < 1) {
                    this.errors.groupSize = 'At least 1 guest.';
                }
            } else if (step === 3) {
                if (this.formData.interests.length === 0) {
                    this.errors.interests = 'Please select at least one interest.';
                }
            } else if (step === 4) {
                if (!this.formData.name.trim()) {
                    this.errors.name = 'Name is required.';
                }
                if (!this.formData.email.trim() || !this.formData.email.includes('@')) {
                    this.errors.email = 'Valid email is required.';
                }
            }
            return Object.keys(this.errors).length === 0;
        },

        get canProceed() {
            if (this.step === 1) return this.formData.destinations.length > 0;
            if (this.step === 2) return this.formData.travelDates && this.formData.duration >= 3 && this.formData.groupSize >= 1;
            if (this.step === 3) return this.formData.interests.length > 0;
            if (this.step === 4) return this.formData.name.trim() && this.formData.email.includes('@');
            return true;
        },

        nextStep() {
            if (this.validate(this.step) && this.step < 4) {
                this.step++;
            }
        },
        prevStep() {
            if (this.step > 1) {
                this.errors = {};
                this.step--;
            }
        },
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

/**
 * Dark / Light mode toggle
 */
function toggleTheme() {
    const html = document.documentElement;
    const current = html.getAttribute('data-theme') || 'dark';
    const next = current === 'dark' ? 'light' : 'dark';

    html.setAttribute('data-theme', next);
    localStorage.setItem('theme', next);
    updateThemeIcons(next);
}

function updateThemeIcons(theme) {
    const lightIcon = document.getElementById('theme-icon-light');
    const darkIcon = document.getElementById('theme-icon-dark');
    if (!lightIcon || !darkIcon) return;

    if (theme === 'light') {
        // In light mode, show moon icon (to switch to dark)
        lightIcon.style.display = 'none';
        darkIcon.style.display = 'inline';
    } else {
        // In dark mode, show sun icon (to switch to light)
        lightIcon.style.display = 'inline';
        darkIcon.style.display = 'none';
    }
}

// Initialize icons on page load
document.addEventListener('DOMContentLoaded', function () {
    const theme = document.documentElement.getAttribute('data-theme') || 'dark';
    updateThemeIcons(theme);

    // Listen for system preference changes (only if user hasn't manually set)
    if (window.matchMedia) {
        window.matchMedia('(prefers-color-scheme: light)').addEventListener('change', function (e) {
            if (!localStorage.getItem('theme')) {
                const newTheme = e.matches ? 'light' : 'dark';
                document.documentElement.setAttribute('data-theme', newTheme);
                updateThemeIcons(newTheme);
            }
        });
    }
});

