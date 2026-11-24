{{-- Shared seller head: design tokens, typography, layout helpers, sidebar behaviour --}}
<script>
    tailwind.config = {
        theme: {
            extend: {
                colors: {
                    primary: '#B5733A',
                    'primary-dark': '#8A5229',
                    secondary: '#E3DCD6',
                },
                fontFamily: {
                    sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    heading: ['"Poppins"', 'sans-serif'],
                },
                letterSpacing: {
                    tight: '-0.02em',
                },
            },
        },
    };
</script>
@include('partials.typography')
<style>
    body { background-color: #F8F6F4; overflow-x: hidden; color: #1F2937; }
    .bg-primary { background-color: #B5733A; }
    .text-primary { color: #B5733A; }
    .border-primary { border-color: #B5733A; }
    .bg-secondary { background-color: #E3DCD6; }
    .active-link { background-color: rgba(181, 115, 58, 0.1); color: #B5733A !important; border-right: 4px solid #B5733A; }
    .sidebar-transition { transition: transform 0.3s ease-in-out, margin 0.3s ease-in-out; }
    .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    .hide-scrollbar::-webkit-scrollbar { display: none; }

    @media (max-width: 767px) {
        #sidebar { transform: translateX(-100%); }
        #sidebar.sidebar-open { transform: translateX(0); box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.2); }
        #main-content { margin-left: 0 !important; }
        #fixed-bottom-bar { left: 0 !important; right: 0 !important; padding-left: 1rem; padding-right: 1rem; }
        #fixed-bottom-bar .bar-hint { display: none; }
    }

    @media (min-width: 768px) {
        #sidebar.sidebar-hidden { transform: translateX(-100%); }
        #main-content.main-expanded { margin-left: 0 !important; }
    }
</style>
<script>
    /* Single sidebar controller for every seller page (overrides legacy per-page handlers). */
    document.addEventListener('DOMContentLoaded', function () {
        var sidebar = document.getElementById('sidebar');
        var main = document.getElementById('main-content');
        var backdrop = document.getElementById('sidebar-backdrop');

        if (!backdrop) {
            backdrop = document.createElement('div');
            backdrop.id = 'sidebar-backdrop';
            backdrop.className = 'fixed inset-0 bg-black/40 z-40 hidden md:hidden';
            document.body.appendChild(backdrop);
        }

        function isMobile() {
            return window.matchMedia('(max-width: 767.98px)').matches;
        }

        function openMobile() {
            sidebar.classList.add('sidebar-open');
            backdrop.classList.remove('hidden');
        }

        function closeMobile() {
            sidebar.classList.remove('sidebar-open');
            backdrop.classList.add('hidden');
        }

        document.addEventListener('click', function (event) {
            var button = event.target.closest ? event.target.closest('#toggle-sidebar') : null;

            if (button) {
                event.preventDefault();
                event.stopPropagation();

                if (!sidebar) {
                    return;
                }

                if (isMobile()) {
                    sidebar.classList.contains('sidebar-open') ? closeMobile() : openMobile();
                } else {
                    var collapsed = sidebar.classList.toggle('sidebar-hidden');
                    if (main) {
                        main.classList.toggle('main-expanded', collapsed);
                    }
                }
                return;
            }

            if (event.target === backdrop) {
                closeMobile();
            }
        }, true);

        if (sidebar) {
            sidebar.querySelectorAll('nav a').forEach(function (link) {
                link.addEventListener('click', function () {
                    if (isMobile()) {
                        closeMobile();
                    }
                });
            });
        }

        window.addEventListener('resize', function () {
            if (!isMobile()) {
                closeMobile();
            }
        });
    });
</script>