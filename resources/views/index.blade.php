@extends($extends_containt)

@push('custom_style')
    <style>
        .js-carousel {
            position: relative;
            overflow: hidden;
        }

        .js-carousel-inner {
            display: flex;
            transition: transform 0.5s ease-in-out;
        }

        .js-carousel-item {
            box-sizing: border-box;
            flex: 0 0 100%;
            /* Un élément par ligne pour mobile */
        }

        .js-carousel-item>div {
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .js-carousel-item img {
            width: 100%;
            object-fit: cover;
            /* Ajuste l'image pour qu'elle remplisse le conteneur */
        }

        @media (min-width: 768px) {
            .js-carousel-item {
                flex: 0 0 33.333%;
                /* Trois éléments par ligne pour écran moyen et plus grand */
            }
        }

        .js-carousel-arrow {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background-color: rgba(0, 0, 0, 0.5);
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            padding: 0.5rem;
            z-index: 10;
            transition: background-color 0.3s ease;
            width: 40px;
        }

        .js-carousel-arrow.disabled {
            background-color: #64696a3b;
            color: rgba(255, 255, 255, 0.788);
            cursor: not-allowed;
        }
    </style>
@endpush

@section('content')
    <div class="h-full overflow-y-scroll">
        @include('layouts.landing')
    </div>
@endsection

@section('script')
    <script>
        // Burger menus
        document.addEventListener('DOMContentLoaded', function() {
            // open
            const burger = document.querySelectorAll('.navbar-burger');
            const menu = document.querySelectorAll('.navbar-menu');

            if (burger.length && menu.length) {
                for (var i = 0; i < burger.length; i++) {
                    burger[i].addEventListener('click', function() {
                        for (var j = 0; j < menu.length; j++) {
                            menu[j].classList.toggle('hidden');
                        }
                    });
                }
            }

            // close
            const close = document.querySelectorAll('.navbar-close');
            const backdrop = document.querySelectorAll('.navbar-backdrop');

            if (close.length) {
                for (var i = 0; i < close.length; i++) {
                    close[i].addEventListener('click', function() {
                        for (var j = 0; j < menu.length; j++) {
                            menu[j].classList.toggle('hidden');
                        }
                    });
                }
            }

            if (backdrop.length) {
                for (var i = 0; i < backdrop.length; i++) {
                    backdrop[i].addEventListener('click', function() {
                        for (var j = 0; j < menu.length; j++) {
                            menu[j].classList.toggle('hidden');
                        }
                    });
                }
            }
        });

        $('#menu-button').on('click', function() {
            const dropdownMenu = $('#dropdown-menu');
            dropdownMenu.toggleClass('hidden');
            dropdownMenu.toggleClass('block');
        });

        window.addEventListener('click', function(event) {
            if (!event.target.closest('#menu-button') && !event.target.closest('#dropdown-menu')) {
                $('#dropdown-menu').addClass('hidden')
                $('#dropdown-menu').removeClass('block')
            }
        });

        document.addEventListener('DOMContentLoaded', function() {

            const initCarousel = (prevId, nextId, innerClass) => {
                const prevButton = document.getElementById(prevId);
                const nextButton = document.getElementById(nextId);
                const carouselInner = document.querySelector(innerClass);
                const items = Array.from(carouselInner.querySelectorAll('.js-carousel-item'));
                let index = 0;

                if (items.length > 0) {
                    const updateCarousel = () => {
                        const itemWidth = items[0].offsetWidth;
                        const visibleItems = window.innerWidth < 768 ? 1 : 3;
                        const totalItems = items.length;
                        const maxIndex = Math.ceil(totalItems / visibleItems) - 1;

                        carouselInner.style.transform = `translateX(-${index * itemWidth}px)`;

                        prevButton.classList.toggle('disabled', index === 0);
                        nextButton.classList.toggle('disabled', index >= maxIndex);
                    };

                    prevButton.addEventListener('click', () => {
                        const visibleItems = window.innerWidth < 768 ? 1 : 3;
                        index = Math.max(0, index - visibleItems);
                        updateCarousel();
                    });

                    nextButton.addEventListener('click', () => {
                        const visibleItems = window.innerWidth < 768 ? 1 : 3;
                        const totalItems = items.length;
                        const maxIndex = Math.ceil(totalItems / visibleItems) - 1;
                        index = Math.min(maxIndex * visibleItems, index + visibleItems);
                        updateCarousel();
                    });

                    window.addEventListener('resize', updateCarousel);
                    updateCarousel();
                } else {
                    $('.js-carousel-arrow').addClass('hidden');
                }

            };

            initCarousel('prev1', 'next1', '.js-carousel-inner-1');
            initCarousel('prev2', 'next2', '.js-carousel-inner-2');

        });
    </script>
@endsection
