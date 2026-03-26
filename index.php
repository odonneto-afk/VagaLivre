<?php include("./topo.php"); ?>
<body>

    <div class="menu-btn"><i class="fas fa-bars" style="color:var(--primary-dark)"></i></div>

    <div id="map-container">
        <svg class="custom-map-svg" viewBox="0 0 200 200" preserveAspectRatio="xMidYMid slice">
            <rect width="200" height="200" fill="#e3e8ee"/>
            <path d="M80 -10 L120 210" stroke="white" stroke-width="14"/>
            <path d="M-10 80 L210 120" stroke="white" stroke-width="12"/>
            <path d="M30 -10 L70 210" stroke="white" stroke-width="6"/>
            <path d="M130 -10 L170 210" stroke="white" stroke-width="6"/>
            <path d="M-10 30 L210 70" stroke="white" stroke-width="5"/>
            <path d="M-10 130 L210 170" stroke="white" stroke-width="5"/>
            <rect x="125" y="95" width="20" height="20" fill="#d1d9e6" rx="2"/>
        </svg>

        <div class="map-label" style="top:40%; left:52%; transform:rotate(10deg); font-size: 10px; color:#5a6d7e;">R. Pedro Firmino</div>
        <div class="map-label" style="top:48%; left:20%; transform:rotate(10deg);">Av. Rio Branco</div>
        <div class="pin user" style="top:55%; left:55%;"></div>
    </div>

    <div class="sidebar collapsed" id="sidebar">
        <div class="sheet-header" id="drag-zone">
            <div class="drag-handle-container"><div class="drag-handle"></div></div>
            <div class="search-wrapper">
                <div class="search-container">
                    <i class="fas fa-search"></i>
                    <input type="text" id="searchInput" placeholder="Para onde vamos hoje?">
                </div>
            </div>
        </div>

        <div class="panel-content">
            <div id="list-view">
                <div class="quick-filters">
                    <div class="filter-chip active">Tudo</div>
                    <div class="filter-chip">Livres</div>
                    <div class="filter-chip">Sombra</div>
                </div>
                <div class="suggestion-item">
                    <div class="s-icon"><i class="fas fa-store"></i></div>
                    <div><div style="font-weight:600; font-size:14px; color:#333">Comércio Central</div><div style="font-size:12px; color:#888;">R. Pedro Firmino</div></div>
                </div>
            </div>

            <div id="details-view">
                <div class="spot-header">
                    <div><h2 id="spot-title" style="margin:0; font-size:22px; color:#333;">Vaga</h2><span style="font-size:12px; color:#666;">R. Pedro Firmino - Centro</span></div>
                    <div class="spot-tag">LIVRE</div>
                </div>
                
                <div class="camera-feed-container" id="cameraCarousel">
                    <div class="camera-overlay"><i class="fas fa-circle"></i> AO VIVO</div>
                    <img src="./image_0.png" class="camera-slide active" alt="Cam 1">
                    <img src="./image_1.png" class="camera-slide" alt="Cam 2">
                    <img src="./image_2.png" class="camera-slide" alt="Cam 3">
                    
                    <div style="position:absolute; z-index:-1; width:100%; height:100%; background:#333; display:flex; align-items:center; justify-content:center; color:#666;">
                        <i class="fas fa-video-slash"></i>
                    </div>
                </div>
                
                <div style="margin-bottom:20px; color:#555; font-size:14px;">
                    <p><i class="fas fa-info-circle"></i> Vaga paralela, rotativo.</p>
                    <p><i class="fas fa-clock"></i> Tempo máx: 2 horas</p>
                </div>
                <button class="btn-action">Navegar até aqui</button>
                <button class="btn-cancel" onclick="backToList()">Voltar para busca</button>
            </div>
        </div>
    </div>

    <script>

        // Fazemos aqui a ideia de bloquear Zoom de Pinça
        document.addEventListener('touchmove', function (event) {
            if (event.scale !== 1) { 
                event.preventDefault(); 
            }
        }, { passive: false });

        // Fazemos aqui a parte de bloquear Zoom de Duplo Toque
        let lastTouchEnd = 0;
        document.addEventListener('touchend', function (event) {
            const now = (new Date()).getTime();
            if (now - lastTouchEnd <= 300) {
                event.preventDefault();
            }
            lastTouchEnd = now;
        }, false);

        // Fazemo aqui a parte de bloquear Zoom por Gestos
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

        // Controle da parte do slideshow
        let slideInterval;
        let currentSlide = 0;

        function startCameraCarousel() {
            const slides = document.querySelectorAll('.camera-slide');
            if (slides.length === 0) return;

            // Garante que começa do zero
            currentSlide = 0;
            updateSlides(slides);

            // Inicia o intervalo de 2 segundos
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

        // Trabalha a geração de vagas
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
                pin.style.top = baseTop + '%'; pin.style.left = (baseLeft + (Math.random()*4 - 2)) + '%';
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

        // Gerencia os estados
        function setSidebarState(state) {
            sidebar.classList.remove('collapsed', 'half-expanded', 'full-expanded');
            if (state === 'collapsed') {
                sidebar.classList.add('collapsed');
                searchInput.blur();
                stopCameraCarousel(); // PARAR ANIMAÇÃO
                setTimeout(() => { if(sidebar.classList.contains('collapsed')) showListView(); }, 300);
            } else if (state === 'half') {
                sidebar.classList.add('half-expanded');
                stopCameraCarousel(); // PARAR ANIMAÇÃO
                showListView();
            } else if (state === 'full') {
                sidebar.classList.add('full-expanded');
                // Dispara para animação começar na função openSpotDetails
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
            
            // INICIAR ANIMAÇÃO
            startCameraCarousel();
        }

        function backToList() {
            setSidebarState('half');
        }

        // Parte dos eventos
        searchInput.addEventListener('focus', () => { setSidebarState('half'); });
        mapContainer.addEventListener('click', (e) => {
            if (!e.target.closest('.pin')) { setSidebarState('collapsed'); }
        });
        
        let startY = 0; let isDragging = false;
        dragZone.addEventListener('touchstart', (e) => { startY = e.touches[0].clientY; isDragging = true; });
        dragZone.addEventListener('touchend', (e) => {
            if (!isDragging) return; isDragging = false;
            const dist = startY - e.changedTouches[0].clientY;
            if (dist > 40) { if (sidebar.classList.contains('collapsed')) setSidebarState('half'); }
            else if (dist < -40) { 
                if (sidebar.classList.contains('full-expanded')) setSidebarState('half');
                else if (sidebar.classList.contains('half-expanded')) setSidebarState('collapsed');
            }
        });

        generateRandomSpots();
    </script>
</body>
</html>