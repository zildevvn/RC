import Swiper from 'swiper';
import { Pagination, Navigation, Autoplay, EffectFade, Keyboard, Thumbs } from 'swiper/modules';
import { CountUp } from 'countup.js';
import AOS from 'aos';


(function ($) {
    "use strict";

    const modalSearch = () => {
        const $modal = $('#search-modal');
        const $trigger = $('#btn_search');

        if (!$modal.length || !$trigger.length || $modal.data('rcModalSearchInit')) {
            return;
        }

        $modal.data('rcModalSearchInit', true);

        const $closeBtn = $modal.find('.search-modal__close');
        const $overlay = $modal.find('.search-modal__overlay');
        const $input = $modal.find('#search-modal-input');
        let focusTimer = null;

        const openModal = () => {
            if ($modal.hasClass('is-active')) {
                return;
            }

            $modal.addClass('is-active').attr('aria-hidden', 'false');
            $trigger.attr('aria-expanded', 'true');
            $('body').addClass('search-modal-open');

            if ($input.length) {
                $input.trigger('focus');
                clearTimeout(focusTimer);
                focusTimer = setTimeout(() => {
                    if ($modal.hasClass('is-active')) {
                        $input.trigger('focus');
                    }
                }, 50);
            }
        };

        const closeModal = () => {
            if (!$modal.hasClass('is-active')) {
                return;
            }

            clearTimeout(focusTimer);
            $modal.removeClass('is-active').attr('aria-hidden', 'true');
            $trigger.attr('aria-expanded', 'false');
            $('body').removeClass('search-modal-open');
            $trigger.trigger('focus');
        };

        $trigger.on('click.rcModalSearch', function (e) {
            e.preventDefault();
            openModal();
        });

        $closeBtn.on('click.rcModalSearch', function (e) {
            e.preventDefault();
            closeModal();
        });

        $overlay.on('click.rcModalSearch', function () {
            closeModal();
        });

        $modal.on('click.rcModalSearch', function (e) {
            if ($(e.target).is($modal)) {
                closeModal();
            }
        });

        $(document).on('keydown.rcModalSearch', function (e) {
            if (!$modal.hasClass('is-active')) {
                return;
            }

            if (e.key === 'Escape') {
                e.preventDefault();
                closeModal();
                return;
            }

            if (e.key === 'Tab') {
                const $focusable = $modal
                    .find('button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])')
                    .filter(':visible:not(:disabled)');

                if (!$focusable.length) {
                    return;
                }

                const firstEl = $focusable[0];
                const lastEl = $focusable[$focusable.length - 1];

                if (e.shiftKey && document.activeElement === firstEl) {
                    e.preventDefault();
                    lastEl.focus();
                } else if (!e.shiftKey && document.activeElement === lastEl) {
                    e.preventDefault();
                    firstEl.focus();
                }
            }
        });
    };

    const testimonialsSwiper = () => {
        const sections = document.querySelectorAll('.testimonials-section');
        if (!sections.length) {
            return;
        }

        sections.forEach((section) => {
            const sliderEl = section.querySelector('.testimonials-section__grid.swiper');
            if (!sliderEl || sliderEl.swiper) {
                return;
            }

            const prevEl = section.querySelector('.swiper-prev');
            const nextEl = section.querySelector('.swiper-next');
            const paginationEl = section.querySelector('.swiper-pagination');

            new Swiper(sliderEl, {
                modules: [Navigation, Pagination],
                slidesPerView: 1,
                spaceBetween: 20,
                watchOverflow: true,
                navigation: {
                    prevEl: prevEl,
                    nextEl: nextEl,
                },
                pagination: {
                    el: paginationEl,
                    clickable: true,
                },
                breakpoints: {
                    768: {
                        slidesPerView: 2,
                        spaceBetween: 24,
                    },
                    992: {
                        slidesPerView: 3,
                        spaceBetween: 32,
                    },
                },
            });
        });
    };

    const rcRelatedCarousel = () => {
        const carousels = document.querySelectorAll('.rc-related-carousel.swiper, .related-tour-section__list.swiper, .related-post-section__list.swiper');
        if (!carousels.length) {
            return;
        }

        carousels.forEach((carousel) => {
            if (carousel.swiper || carousel.dataset.rcRelatedCarouselInit) {
                return;
            }

            carousel.dataset.rcRelatedCarouselInit = 'true';

            // Find controls within the closest section or parent container
            const section = carousel.closest('section') || carousel.parentElement;
            const prevEl = section ? section.querySelector('.rc-swiper-prev, .swiper-prev') : null;
            const nextEl = section ? section.querySelector('.rc-swiper-next, .swiper-next') : null;
            const paginationEl = carousel.querySelector('.swiper-pagination') || (section ? section.querySelector('.rc-related-pagination, .swiper-pagination') : null);

            new Swiper(carousel, {
                modules: [Navigation, Pagination, Autoplay],
                slidesPerView: 1.15,
                spaceBetween: 16,
                watchOverflow: true,
                grabCursor: true,
                navigation: {
                    prevEl: prevEl,
                    nextEl: nextEl,
                },
                pagination: {
                    el: paginationEl,
                    clickable: true,
                },
                breakpoints: {
                    576: {
                        slidesPerView: 1.5,
                        spaceBetween: 20,
                    },
                    768: {
                        slidesPerView: 2,
                        spaceBetween: 24,
                    },
                    1024: {
                        slidesPerView: 3,
                        spaceBetween: 30,
                    },
                },
            });
        });
    };

    const rcFaqs = () => {
        const $containers = $('.rc-faqs, .faqs-list');
        if (!$containers.length) {
            return;
        }

        $containers.each(function () {
            const $container = $(this);
            if ($container.data('rcFaqsInit')) {
                return;
            }

            $container.data('rcFaqsInit', true);

            $container.on('click.rcFaqs', '.rc-faqs__question, .faqs-list__question, .faq-item__question', function (e) {
                e.preventDefault();
                const $btn = $(this);
                const $item = $btn.closest('.rc-faqs__item, .faqs-list__item, .faq-item');
                if (!$item.length) {
                    return;
                }

                const isActive = $item.hasClass('is-active') || $item.hasClass('rc-faqs__item--active') || $item.hasClass('faqs-list__item--active');

                if (isActive) {
                    $item.removeClass('is-active rc-faqs__item--active faqs-list__item--active');
                    $btn.attr('aria-expanded', 'false');
                } else {
                    const $siblings = $container.find('.rc-faqs__item, .faqs-list__item, .faq-item').filter('.is-active, .rc-faqs__item--active, .faqs-list__item--active').not($item);
                    $siblings.removeClass('is-active rc-faqs__item--active faqs-list__item--active');
                    $siblings.find('.rc-faqs__question, .faqs-list__question, .faq-item__question').attr('aria-expanded', 'false');

                    $item.addClass('is-active');
                    $btn.attr('aria-expanded', 'true');
                }
            });
        });
    };

    const modalBookCar = () => {
        const $modal = $('#book-car-modal');
        if (!$modal.length || $modal.data('rcModalBookCarInit')) {
            return;
        }

        $modal.data('rcModalBookCarInit', true);

        const $closeBtn = $modal.find('.book-car-modal__close');
        const $overlay = $modal.find('.book-car-modal__overlay');
        let $activeTrigger = null;
        let focusTimer = null;

        const openModal = ($trigger) => {
            if ($modal.hasClass('is-active')) {
                return;
            }

            $activeTrigger = $trigger && $trigger.length ? $trigger : null;
            $modal.addClass('is-active').attr('aria-hidden', 'false');
            $('.rc-book-car-trigger').attr('aria-expanded', 'true');
            $('body').addClass('book-car-modal-open');

            const $firstInput = $modal
                .find('.book-car-modal__body')
                .find('input:not([type="hidden"]), select, textarea')
                .filter(':visible:not(:disabled)')
                .first();
            const $focusTarget = $firstInput.length ? $firstInput : $closeBtn;

            if ($focusTarget.length) {
                $focusTarget.trigger('focus');
                clearTimeout(focusTimer);
                focusTimer = setTimeout(() => {
                    if ($modal.hasClass('is-active')) {
                        $focusTarget.trigger('focus');
                    }
                }, 50);
            }
        };

        const closeModal = () => {
            if (!$modal.hasClass('is-active')) {
                return;
            }

            clearTimeout(focusTimer);
            $modal.removeClass('is-active').attr('aria-hidden', 'true');
            $('.rc-book-car-trigger').attr('aria-expanded', 'false');
            $('body').removeClass('book-car-modal-open');

            if ($activeTrigger && $activeTrigger.is(':visible')) {
                $activeTrigger.trigger('focus');
            }
            $activeTrigger = null;
        };

        $(document).on('click.rcModalBookCar', '.rc-book-car-trigger', function (e) {
            e.preventDefault();
            openModal($(this));
        });

        $closeBtn.on('click.rcModalBookCar', function (e) {
            e.preventDefault();
            closeModal();
        });

        $overlay.on('click.rcModalBookCar', function () {
            closeModal();
        });

        $modal.on('click.rcModalBookCar', function (e) {
            if ($(e.target).is($modal)) {
                closeModal();
            }
        });

        $(document).on('keydown.rcModalBookCar', function (e) {
            if (!$modal.hasClass('is-active')) {
                return;
            }

            if (e.key === 'Escape') {
                e.preventDefault();
                closeModal();
                return;
            }

            if (e.key === 'Tab') {
                const $focusable = $modal
                    .find('button, [href], input:not([type="hidden"]), select, textarea, [tabindex]:not([tabindex="-1"])')
                    .filter(':visible:not(:disabled)');

                if (!$focusable.length) {
                    return;
                }

                const firstEl = $focusable[0];
                const lastEl = $focusable[$focusable.length - 1];

                if (e.shiftKey && document.activeElement === firstEl) {
                    e.preventDefault();
                    lastEl.focus();
                } else if (!e.shiftKey && document.activeElement === lastEl) {
                    e.preventDefault();
                    firstEl.focus();
                }
            }
        });
    };

    const initAOS = () => {
        if (typeof AOS === 'undefined') {
            return;
        }

        AOS.init({
            duration: 700,
            easing: 'ease-out-cubic',
            once: true,
            offset: 60,
            disable: function () {
                return window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
            }
        });
    };

    const backToTop = () => {
        const $btn = $('#rc-back-to-top');

        if (!$btn.length || $btn.data('rcBackToTopInit')) {
            return;
        }

        $btn.data('rcBackToTopInit', true);

        const scrollThreshold = 300;
        let ticking = false;

        const checkScroll = () => {
            const currentScroll = window.pageYOffset || document.documentElement.scrollTop || 0;

            if (currentScroll > scrollThreshold) {
                if (!$btn.hasClass('is-visible')) {
                    $btn.addClass('is-visible');
                }
            } else {
                if ($btn.hasClass('is-visible')) {
                    $btn.removeClass('is-visible');
                }
            }
            ticking = false;
        };

        const onScroll = () => {
            if (!ticking) {
                window.requestAnimationFrame(checkScroll);
                ticking = true;
            }
        };

        // Passive event listener for high scroll performance
        window.addEventListener('scroll', onScroll, { passive: true });

        // Initial check in case page is loaded already scrolled
        checkScroll();

        $btn.on('click.rcBackToTop', function (e) {
            e.preventDefault();

            const prefersReducedMotion = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

            if (typeof window.scrollTo === 'function') {
                window.scrollTo({
                    top: 0,
                    behavior: prefersReducedMotion ? 'auto' : 'smooth'
                });
            } else {
                $('html, body').animate({ scrollTop: 0 }, prefersReducedMotion ? 0 : 500);
            }
        });
    };

    const tourGallery = () => {
        const $galleries = $('.tour-gallery');
        const $lightbox = $('#tourGalleryLightbox');

        if (!$galleries.length || !$lightbox.length) {
            return;
        }

        const $lightboxImg = $lightbox.find('#tourGalleryLightboxImg');
        const $lightboxCounter = $lightbox.find('#tourGalleryLightboxCounter');
        const $lightboxCaption = $lightbox.find('#tourGalleryLightboxCaption');
        const $closeBtn = $lightbox.find('.tour-gallery__lightbox-close');
        const $prevBtn = $lightbox.find('.tour-gallery__lightbox-prev');
        const $nextBtn = $lightbox.find('.tour-gallery__lightbox-next');
        const $overlay = $lightbox.find('.tour-gallery__lightbox-overlay');

        $galleries.each(function () {
            const $gallery = $(this);
            if ($gallery.data('rcTourGalleryInit')) {
                return;
            }
            $gallery.data('rcTourGalleryInit', true);

            const $items = $gallery.find('.tour-gallery__item');

            // Collect all gallery items data
            const images = [];
            $items.each(function (idx) {
                const $item = $(this);
                const fullSrc = $item.data('full') || $item.find('img').attr('src');
                if (fullSrc) {
                    images.push({
                        index: idx,
                        full: fullSrc,
                        alt: $item.data('alt') || $item.find('img').attr('alt') || '',
                        title: $item.data('title') || ''
                    });
                }
            });

            if (!images.length) {
                return;
            }

            let currentIndex = 0;
            let $lastFocusedElement = null;

            // Preload image
            const preloadImage = (url) => {
                if (url) {
                    const img = new Image();
                    img.src = url;
                }
            };

            const updateLightboxImage = (index) => {
                if (index < 0 || index >= images.length) {
                    return;
                }
                currentIndex = index;
                const imgData = images[currentIndex];

                $lightbox.addClass('is-loading');
                $lightboxImg.attr('src', imgData.full).attr('alt', imgData.alt);

                $lightboxImg.off('load.rcTourGallery error.rcTourGallery').on('load.rcTourGallery error.rcTourGallery', function () {
                    $lightbox.removeClass('is-loading');
                });

                if ($lightboxCounter.length) {
                    $lightboxCounter.text((currentIndex + 1) + ' / ' + images.length);
                }

                if ($lightboxCaption.length) {
                    const captionText = imgData.title || imgData.alt;
                    $lightboxCaption.text(captionText ? captionText : '');
                }

                // Preload adjacent images
                if (images.length > 1) {
                    const nextIdx = (currentIndex + 1) % images.length;
                    const prevIdx = (currentIndex - 1 + images.length) % images.length;
                    preloadImage(images[nextIdx].full);
                    preloadImage(images[prevIdx].full);
                }

                // Hide prev/next if only 1 image
                if (images.length <= 1) {
                    $prevBtn.hide();
                    $nextBtn.hide();
                } else {
                    $prevBtn.show();
                    $nextBtn.show();
                }
            };

            const openLightbox = (index, $trigger) => {
                $lastFocusedElement = $trigger || $(document.activeElement);
                updateLightboxImage(index);

                $lightbox.addClass('is-active').attr('aria-hidden', 'false');
                $('body').addClass('tour-gallery-lightbox-open');

                $closeBtn.trigger('focus');
            };

            const closeLightbox = () => {
                if (!$lightbox.hasClass('is-active')) {
                    return;
                }
                $lightbox.removeClass('is-active is-loading').attr('aria-hidden', 'true');
                $('body').removeClass('tour-gallery-lightbox-open');

                if ($lastFocusedElement && $lastFocusedElement.length) {
                    $lastFocusedElement.trigger('focus');
                }
            };

            const showNext = () => {
                if (images.length <= 1) return;
                const next = (currentIndex + 1) % images.length;
                updateLightboxImage(next);
            };

            const showPrev = () => {
                if (images.length <= 1) return;
                const prev = (currentIndex - 1 + images.length) % images.length;
                updateLightboxImage(prev);
            };

            // Event bindings
            $gallery.on('click.rcTourGallery', '.tour-gallery__item, .tour-gallery__view-all-btn', function (e) {
                e.preventDefault();
                const $target = $(this);
                const idx = parseInt($target.data('index'), 10) || 0;
                openLightbox(idx, $target);
            });

            $closeBtn.off('click.rcTourGallery').on('click.rcTourGallery', function (e) {
                e.preventDefault();
                closeLightbox();
            });

            $overlay.off('click.rcTourGallery').on('click.rcTourGallery', function () {
                closeLightbox();
            });

            $nextBtn.off('click.rcTourGallery').on('click.rcTourGallery', function (e) {
                e.preventDefault();
                showNext();
            });

            $prevBtn.off('click.rcTourGallery').on('click.rcTourGallery', function (e) {
                e.preventDefault();
                showPrev();
            });

            $(document).off('keydown.rcTourGallery').on('keydown.rcTourGallery', function (e) {
                if (!$lightbox.hasClass('is-active')) {
                    return;
                }

                if (e.key === 'Escape') {
                    e.preventDefault();
                    closeLightbox();
                } else if (e.key === 'ArrowRight') {
                    e.preventDefault();
                    showNext();
                } else if (e.key === 'ArrowLeft') {
                    e.preventDefault();
                    showPrev();
                }
            });

            // Touch swipe support for mobile
            let touchStartX = 0;
            let touchEndX = 0;

            $lightbox.find('.tour-gallery__lightbox-image-container')
                .off('touchstart.rcTourGallery touchend.rcTourGallery')
                .on('touchstart.rcTourGallery', function (e) {
                    if (e.originalEvent && e.originalEvent.changedTouches) {
                        touchStartX = e.originalEvent.changedTouches[0].screenX;
                    }
                })
                .on('touchend.rcTourGallery', function (e) {
                    if (e.originalEvent && e.originalEvent.changedTouches) {
                        touchEndX = e.originalEvent.changedTouches[0].screenX;
                        const diffX = touchEndX - touchStartX;
                        if (Math.abs(diffX) > 40) {
                            if (diffX < 0) {
                                showNext();
                            } else {
                                showPrev();
                            }
                        }
                    }
                });
        });
    };

    $(document).ready(function () {
        modalSearch();
        modalBookCar();
        testimonialsSwiper();
        rcRelatedCarousel();
        rcFaqs();
        initAOS();
        backToTop();
        tourGallery();
    });

    $(window).on('load', function () {
        if (typeof AOS !== 'undefined') {
            AOS.refresh();
        }
    });
})(jQuery);
