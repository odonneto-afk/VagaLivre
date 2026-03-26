<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>VagaLivre - Patos (Camera Ao Vivo)</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        
    </style>
</head>
<body>

    <div class="menu-btn" onclick="irPerfil()">
        <i class="fas fa-bars" style="color:var(--primary-dark)"></i>
    </div>

    <div id="map-container">
       
    </div>

    <div class="sidebar collapsed" id="sidebar">
       
    </div>

    <script>

        function irPerfil(){
            window.location.href = "perfil.php";
        }

    

        document.addEventListener('touchmove', function (event) {
            if (event.scale !== 1) { 
                event.preventDefault(); 
            }
        }, { passive: false });

        let lastTouchEnd = 0;
        document.addEventListener('touchend', function (event) {
            const now = (new Date()).getTime();
            if (now - lastTouchEnd <= 300) {
                event.preventDefault();
            }
            lastTouchEnd = now;
        }, false);

        document.addEventListener('gesturestart', function (e) {
            e.preventDefault();
        });

        const mapContainer = document.getElementById('map-container');
        const sidebar = document.getElementById('sidebar');
        const dragZone = document.getElementById('drag-zone');
        const searchInput = document.getElementById('searchInput');
        const listView = document.getElementById('list-view');
        const detailsView = document.getElementById('details-view');
        const spotTitle = document.getElementById('spot-title');

        let slideInterval;
        let currentSlide = 0;

        function startCameraCarousel() {
            const slides = document.querySelectorAll('.camera-slide');
            if (slides.length === 0) return;

            currentSlide = 0;
            updateSlides(slides);

            slideInterval = setInterval(() => {
                currentSlide = (currentSlide + 1) % slides.length;
                updateSlides(slides);
            }, 2000);
        }

        function stopCameraCarousel() {
            clearInterval(slideInterval);
        }

        function updateSlides(slides) {
            slides.forEach((slide, index) => {
                if (index === currentSlide) {
                    slide.classList.add('active');
                } else {
                    slide.classList.remove('active');
                }
            });
        }

        function generateRandomSpots() {
            const existingPins = document.querySelectorAll('.pin:not(.user)');
            existingPins.forEach(pin => pin.remove());
            
            const numberOfSpots = 7;
            for (let i = 0; i < numberOfSpots; i++) {
                const pin = document.createElement('div');
                const isFree = Math.random() > 0.4;
                const progress = i / (numberOfSpots - 1);
                const baseLeft = 40 + (progress * 20);
                const baseTop = 10 + (progress * 80);
                
                pin.className = `pin ${isFree ? 'free' : 'occupied'}`;
                pin.style.top = baseTop + '%';
                pin.style.left = (baseLeft + (Math.random()*4 - 2)) + '%';
                pin.innerHTML = `<i class="fas fa-${isFree ? 'check' : 'times'}"></i>`;

                if (isFree) {
                    pin.addEventListener('click', (e) => {
                        e.stopPropagation();
                        openSpotDetails(`Vaga #${i + 200}`);
                    });
                }
                mapContainer.appendChild(pin);
            }
        }

        function setSidebarState(state) {
            sidebar.classList.remove('collapsed', 'half-expanded', 'full-expanded');
            if (state === 'collapsed') {
                sidebar.classList.add('collapsed');
                searchInput.blur();
                stopCameraCarousel();
                setTimeout(() => { 
                    if(sidebar.classList.contains('collapsed')) showListView(); 
                }, 300);
            } else if (state === 'half') {
                sidebar.classList.add('half-expanded');
                stopCameraCarousel();
                showListView();
            } else if (state === 'full') {
                sidebar.classList.add('full-expanded');
            }
        }

        function showListView() {
            detailsView.style.display = 'none';
            listView.style.display = 'block';
        }

        function openSpotDetails(title) {
            spotTitle.innerText = title;
            listView.style.display = 'none';
            detailsView.style.display = 'block';
            setSidebarState('full');
            startCameraCarousel();
        }

        function backToList() {
            setSidebarState('half');
        }

        searchInput.addEventListener('focus', () => { setSidebarState('half'); });

        mapContainer.addEventListener('click', (e) => {
            if (!e.target.closest('.pin')) { 
                setSidebarState('collapsed'); 
            }
        });
        
        let startY = 0;
        let isDragging = false;

        dragZone.addEventListener('touchstart', (e) => {
            startY = e.touches[0].clientY;
            isDragging = true;
        });

        dragZone.addEventListener('touchend', (e) => {
            if (!isDragging) return;
            isDragging = false;

            const dist = startY - e.changedTouches[0].clientY;

            if (dist > 40) {
                if (sidebar.classList.contains('collapsed')) setSidebarState('half');
            } else if (dist < -40) {
                if (sidebar.classList.contains('full-expanded')) setSidebarState('half');
                else if (sidebar.classList.contains('half-expanded')) setSidebarState('collapsed');
            }
        });

        generateRandomSpots();

    </script>
</body>
</html>