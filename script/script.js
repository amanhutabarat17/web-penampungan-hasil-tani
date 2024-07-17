
        document.addEventListener('DOMContentLoaded', function () {
            var carousel = document.querySelector('#carouselExampleControls');
            var carouselInstance = new bootstrap.Carousel(carousel, {
                interval: 3000, // Ganti interval sesuai kebutuhan (ms)
                wrap: true // Mengaktifkan fitur wrap atau tidak
            });
        });
    