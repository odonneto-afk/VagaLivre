<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>VagaLivre - Patos (Camera Ao Vivo)</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;800&display=swap');

        * { box-sizing: border-box; }

        :root {
            --primary-dark: #2b5876;
            --primary-light: #4e4376;
            --accent-green: #2ecc71;
            --accent-red: #e74c3c;
            --user-blue: #3498db;
            --white: #FFFFFF;
            --gray-bg: #f0f2f5;
            --map-road: #ffffff;
            --map-block: #e3e8ee;
            --map-bg: #ced6e0;
        }

        body {
            font-family: 'Montserrat', sans-serif;
            margin: 0; padding: 0;
            height: 100%; width: 100%;
            position: fixed; overflow: hidden;
            background-color: var(--gray-bg);
            display: flex; flex-direction: column;

            touch-action: manipulation; /* Impede zoom por duplo toque */
            overscroll-behavior: none;  /* Impede o efeito elástico */
            -webkit-user-select: none;  /* Impede selecionar texto */
            user-select: none;
            

            font-family: 'Montserrat', sans-serif;
            margin: 0; padding: 0;
            height: 100%; width: 100%;
            position: fixed; overflow: hidden;
            background-color: var(--gray-bg);
            display: flex; flex-direction: column;



            font-family: 'Montserrat', sans-serif;
            touch-action: pan-x pan-y; /* Deixa rolar listas, mas bloqueia zoom */
            -webkit-text-size-adjust: 100%; /* Evita aumento de fonte automático */
            overscroll-behavior: none; /* Remove efeito elástico */
        }


        #map-container {
            position: absolute; top: 0; left: 0; width: 100%; height: 100%;
            background-color: var(--map-bg); z-index: 1; overflow: hidden;
            touch-action: none; /* Bloqueia TUDO no mapa inclusive pinça */
        }
        .custom-map-svg { width: 100%; height: 100%; object-fit: cover; position: absolute; top: 0; left: 0; }
        .map-label { position: absolute; font-size: 8px; font-weight: 700; color: #8fa6b9; text-transform: uppercase; letter-spacing: 0.5px; pointer-events: none; text-align: center; }


        .pin {
            position: absolute; width: 28px; height: 28px;
            border-radius: 50% 50% 50% 0;
            transform: rotate(-45deg);
            display: flex; justify-content: center; align-items: center;
            box-shadow: 0 3px 6px rgba(0,0,0,0.2);
            cursor: pointer; transition: transform 0.2s; z-index: 10;
        }
        .pin i { transform: rotate(45deg); color: white; font-size: 12px; }
        .pin.free { background-color: var(--accent-green); border: 2px solid #fff; }
        .pin.occupied { background-color: var(--accent-red); border: 2px solid #fff; opacity: 0.8; }
        .pin.user {
            background-color: var(--user-blue); border: 2px solid #fff;
            width: 18px; height: 18px; border-radius: 50%; transform: none;
            box-shadow: 0 0 0 8px rgba(52, 152, 219, 0.2);
            animation: pulse 2s infinite; z-index: 5; pointer-events: none;
        }
        @keyframes pulse {
            0% { box-shadow: 0 0 0 0 rgba(52, 152, 219, 0.4); }
            100% { box-shadow: 0 0 0 12px rgba(52, 152, 219, 0); }
        }


        .sidebar {
            position: absolute; bottom: 0; left: 0; width: 100%;
            background: white; z-index: 100;
            border-radius: 25px 25px 0 0;
            box-shadow: 0 -5px 20px rgba(0,0,0,0.15);
            display: flex; flex-direction: column;
            transition: height 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
            will-change: height;
        }
        .sidebar.collapsed { height: 130px; }
        .sidebar.half-expanded { height: 45%; }
        .sidebar.full-expanded { height: 70%; }

        .sheet-header { padding: 0 20px; flex-shrink: 0; background: white; border-radius: 25px 25px 0 0; }
        .drag-handle-container { width: 100%; display: flex; justify-content: center; padding: 12px 0; cursor: grab; touch-action: none; }
        .drag-handle { width: 40px; height: 5px; background-color: #ddd; border-radius: 5px; }

        .search-wrapper { transition: all 0.3s ease; max-height: 80px; opacity: 1; margin-bottom: 15px; overflow: hidden; }
        .sidebar.full-expanded .search-wrapper { max-height: 0; opacity: 0; margin-bottom: 0; pointer-events: none; }

        .search-container { position: relative; }
        .search-container input {
            width: 100%; padding: 14px 15px 14px 45px;
            border-radius: 12px; border: 1px solid #eee; background-color: #f9f9f9;
            font-family: 'Montserrat', sans-serif; font-size: 16px;
        }
        .search-container input:focus { outline: none; background-color: #fff; border-color: var(--primary-light); }
        .search-container i { position: absolute; left: 15px; top: 50%; transform: translateY(-50%); color: #999; }

        .panel-content { flex-grow: 1; overflow-y: auto; padding: 0 20px 40px 20px; opacity: 0; transition: opacity 0.2s ease; pointer-events: none; }
        .sidebar.half-expanded .panel-content, .sidebar.full-expanded .panel-content { opacity: 1; pointer-events: auto; }

        #list-view { display: block; }
        .quick-filters { display: flex; gap: 10px; margin-bottom: 20px; overflow-x: auto; padding-bottom: 5px; }
        .quick-filters::-webkit-scrollbar { display: none; }
        .filter-chip { padding: 8px 16px; background-color: #f0f2f5; border-radius: 20px; font-size: 12px; font-weight: 600; color: #555; white-space: nowrap; cursor: pointer; }
        .filter-chip.active { background-color: var(--primary-dark); color: white; }
        .suggestion-item { display: flex; align-items: center; padding: 15px 0; border-bottom: 1px solid #f5f5f5; cursor: pointer; }
        .s-icon { width: 36px; height: 36px; background-color: #edf2f7; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-right: 15px; color: var(--primary-dark); }

        #details-view { display: none; padding-top: 10px; }
        .spot-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; }
        .spot-tag { background: #e8f5e9; color: var(--accent-green); padding: 4px 10px; border-radius: 8px; font-size: 11px; font-weight: bold; }


        .camera-feed-container {
            width: 100%; height: 200px; 
            background: #000; 
            border-radius: 12px;
            margin-bottom: 20px; 
            position: relative; 
            overflow: hidden; /* Garante que nada saia da borda redonda */
        }
        
        /* Modal do "Ao Vivo" fica sobreposto */
        .camera-overlay {
            position: absolute; top: 10px; left: 10px; z-index: 20;
            background: rgba(231, 76, 60, 0.9); color: white; 
            font-size: 10px; padding: 4px 8px; border-radius: 4px; font-weight: 700;
            display: flex; align-items: center; gap: 5px;
        }
        .camera-overlay i { font-size: 8px; animation: blink 1s infinite; }
        @keyframes blink { 50% { opacity: 0; } }

        /* Estilo das imagens do slide */
        .camera-slide {
            position: absolute;
            top: 0; left: 0;
            width: 100%; height: 100%;
            object-fit: cover;
            opacity: 0; /* Iniciando invisível */
            transition: opacity 0.8s ease-in-out; /* Transição suave */
        }
        
        /* Parete da classe que torna a imagem visível */
        .camera-slide.active { opacity: 1; }

        .btn-action { width: 100%; padding: 16px; border: none; background-color: var(--primary-light); color: white; font-weight: bold; border-radius: 12px; cursor: pointer; font-size: 14px; box-shadow: 0 4px 15px rgba(78, 67, 118, 0.3); }
        .btn-cancel { width: 100%; padding: 15px; background: transparent; border: none; color: #999; cursor: pointer; font-size: 13px; }
        .menu-btn { position: absolute; top: 20px; right: 20px; width: 45px; height: 45px; background: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 10px rgba(0,0,0,0.1); cursor: pointer; z-index: 200; }
    </style>
</head>
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