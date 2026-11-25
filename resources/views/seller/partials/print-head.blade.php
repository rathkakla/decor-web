{{-- Shared head for standalone print documents (invoice, shipping label). No sidebar JS. --}}
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
    body { background-color: #F8F6F4; color: #1F2937; }
    .bg-primary { background-color: #B5733A; }
    .text-primary { color: #B5733A; }
    .border-primary { border-color: #B5733A; }
    .bg-secondary { background-color: #E3DCD6; }
</style>
