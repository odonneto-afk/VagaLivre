<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VagaLivre - Acesso</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;800&display=swap');

        * {
            box-sizing: border-box;
        }

        :root {
            --primary-dark: #2b5876;
            --primary-light: #4e4376;
            --accent-green: #2ecc71;
            --white: #FFFFFF;
        }

        body {
            background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary-light) 100%);
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            font-family: 'Montserrat', sans-serif;
            height: 100vh;
            margin: 0;
            overflow: hidden; /* Evitamos assim o  scroll indesejado na animação */
        }


        .logo-container {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 15px;
        }
        .logo-icon {
            font-size: 24px;
            color: var(--primary-dark);
            margin-right: 8px;
            position: relative;
        }
        .logo-icon::after {
            content: '';
            position: absolute;
            top: 0; right: -2px; width: 6px; height: 6px;
            background-color: var(--accent-green);
            border-radius: 50%; border: 2px solid var(--white);
        }
        .logo-text {
            font-size: 22px; font-weight: 800; color: var(--primary-dark); letter-spacing: -1px;
        }
        .logo-text span { color: var(--accent-green); font-weight: 600; }


        .overlay .logo-icon { color: var(--white); }
        .overlay .logo-icon::after { border-color: var(--primary-light); }
        .overlay .logo-text { color: var(--white); }


        .container {
            background-color: #fff;
            border-radius: 20px;
            box-shadow: 0 14px 28px rgba(0,0,0,0.25), 0 10px 10px rgba(0,0,0,0.22);
            position: relative;
            overflow: hidden;
            width: 800px;
            max-width: 100%;
            min-height: 500px; /* Aqui fica a altura quando for desktop */
        }

        .form-container {
            position: absolute;
            top: 0;
            height: 100%;
            transition: all 0.6s ease-in-out;
        }


        form {
            background-color: #FFFFFF;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            padding: 0 50px;
            height: 100%;
            text-align: center;
        }
        
        h1 { margin: 0; font-size: 24px; }
        p { font-size: 14px; font-weight: 100; line-height: 20px; letter-spacing: 0.5px; margin: 20px 0 30px; }
        span.sub-text { font-size: 12px; margin-bottom: 10px; }
        
        input {
            background-color: #eee;
            border: none;
            padding: 12px 15px;
            margin: 8px 0;
            width: 100%;
            border-radius: 8px;
        }
        .plate-input {
            text-transform: uppercase;
            font-family: monospace;
            letter-spacing: 2px;
            font-weight: bold;
            border-left: 5px solid var(--accent-green);
        }

        button {
            border-radius: 20px;
            border: 1px solid var(--primary-light);
            background-color: var(--primary-light);
            color: #FFFFFF;
            font-size: 12px;
            font-weight: bold;
            padding: 12px 45px;
            letter-spacing: 1px;
            text-transform: uppercase;
            transition: transform 80ms ease-in;
            cursor: pointer;
            margin-top: 10px;
        }
        button:active { transform: scale(0.95); }
        button:focus { outline: none; }
        button.ghost { background-color: transparent; border-color: #FFFFFF; }


        .sign-in-container {
            left: 0; width: 50%; z-index: 2;
        }
        .container.right-panel-active .sign-in-container {
            transform: translateX(100%);
        }
        .sign-up-container {
            left: 0; width: 50%; opacity: 0; z-index: 1;
        }
        .container.right-panel-active .sign-up-container {
            transform: translateX(100%); opacity: 1; z-index: 5; animation: show 0.6s;
        }
        @keyframes show {
            0%, 49.99% { opacity: 0; z-index: 1; }
            50%, 100% { opacity: 1; z-index: 5; }
        }


        .overlay-container {
            position: absolute; top: 0; left: 50%; width: 50%; height: 100%;
            overflow: hidden; transition: transform 0.6s ease-in-out; z-index: 100;
        }
        .container.right-panel-active .overlay-container { transform: translateX(-100%); }
        
        .overlay {
            background: linear-gradient(to right, var(--primary-dark), var(--primary-light));
            background-repeat: no-repeat; background-size: cover; background-position: 0 0;
            color: #FFFFFF; position: relative; left: -100%; height: 100%; width: 200%;
            transform: translateX(0); transition: transform 0.6s ease-in-out;
        }
        .container.right-panel-active .overlay { transform: translateX(50%); }

        .overlay-panel {
            position: absolute; display: flex; align-items: center; justify-content: center;
            flex-direction: column; padding: 0 40px; text-align: center; top: 0; height: 100%; width: 50%;
            transform: translateX(0); transition: transform 0.6s ease-in-out;
        }
        .overlay-left { transform: translateX(-20%); }
        .container.right-panel-active .overlay-left { transform: translateX(0); }
        .overlay-right { right: 0; transform: translateX(0); }
        .container.right-panel-active .overlay-right { transform: translateX(20%); }


        /* Parte do mobile */
        @media (max-width: 768px) {
            
            .container {
                width: 90vw; /* Largura boa no mobile */
                height: 85vh; /* Altura quase que total */
                min-height: auto;
                max-width: 400px; /* Limite para tablets */
                border-radius: 15px;
            }

            form { padding: 0 30px; }
            h1 { font-size: 20px; }

            .sign-in-container, .sign-up-container {
                width: 100%;
                height: 70%;
                top: 0;
                left: 0;
            }


            .container.right-panel-active .sign-in-container { transform: translateY(100%); }
            .container.right-panel-active .sign-up-container { transform: translateY(0); }
            

            .overlay-container {
                width: 100%;
                height: 30%;
                top: 70%;
                left: 0;
                right: 0;
            }
            
            /* Quando ta ativo, o overlay corre e sobe para o TOPO */
            .container.right-panel-active .overlay-container {
                transform: translateY(-233%);
                transform: translateY(-233%); 
            }
            
            .container.right-panel-active .overlay-container {
                transform: translateY(-233%); 
            }

            .overlay {
                width: 100%;
                height: 200%;
                left: 0;
                top: -100%; 
                flex-direction: column;
                transform: translateY(0);
            }
            
            .container.right-panel-active .overlay {
                transform: translateY(50%);
            }

            /* Painéis de texto dentro do Overlay */
            .overlay-panel {
                width: 100%;
                height: 50%; /* Ajusta para cada painel ocupar metade da altura do overlay */
                padding: 0 20px;
            }

            .overlay-left { 
                top: 0; 
                transform: translateY(-20%); 
            }
            .overlay-right { 
                top: auto; 
                bottom: 0; 
                right: auto;
                transform: translateY(0); 
            }

            /* Animações de texto quando for mobile */
            .container.right-panel-active .overlay-left { transform: translateY(0); }
            .container.right-panel-active .overlay-right { transform: translateY(20%); }

            /* Ajuste de correção da posição dos forms na animação */
            .sign-up-container {
                top: auto;
                bottom: 0; /* Começa na parte de baixo quando for mobile */
                transform: translateY(0);
            }
            
            /* Estado inicial do login com form em cima e overlay em baixo */
            .sign-in-container { top: 0; height: 70%; }
            .sign-up-container { top: 30%; height: 70%; opacity: 0; z-index: 0;}
            
            /* Estado ativo de cadastro  com overlay em cima e form em baixo */
            .container.right-panel-active .sign-in-container {
                transform: translateY(100%);
                opacity: 0;
            }
            .container.right-panel-active .sign-up-container {
                transform: translateY(0); 
                top: 30%; 
                opacity: 1;
                z-index: 5;
                animation: none;
            }


            /* Quando for login o overlay está em top: 70% */
            /* Qjando for cadastro o overlay deve ir para top: 0% */
            
            .container.right-panel-active .overlay-container {
                transform: translateY(-233%); 
            }
        }

        /* Ajuste para telas muito pequenas */
        @media (max-height: 600px) and (max-width: 768px) {
            .container { height: 95vh; }
            h1 { font-size: 18px; margin-bottom: 5px;}
            p { margin: 10px 0; font-size: 12px; }
            .logo-container { margin-bottom: 5px; }
            input { padding: 8px 15px; margin: 4px 0; }
        }
    </style>
</head>
<body>

<div class="container" id="container">
    
    <div class="form-container sign-up-container">
        <form action="#" method="post">
            <div class="logo-container">
                <i class="fas fa-car-side logo-icon"></i>
                <div class="logo-text">Vaga<span>Livre</span></div>
            </div>
            <h1>Criar Conta</h1>
            <input type="text" name="nome" placeholder="Nome" />
            <input type="email" name="whatsapp" placeholder="Whatsapp" />
            <input type="email" name="email" placeholder="Email" />
            <input type="password" name="senha" placeholder="Senha" />
            <input type="password" name="confsenha" placeholder="Confirmação de Senha" />
            <button>Cadastrar</button>
        </form>
    </div>

    <div class="form-container sign-in-container">
        <form action="#" method="post">
            <div class="logo-container">
                <i class="fas fa-car-side logo-icon"></i>
                <div class="logo-text">Vaga<span>Livre</span></div>
            </div>
            <h1>Login</h1>
            <input type="email" name="email" placeholder="Email" />
            <input type="password" name="senha" placeholder="Senha" />
            <a href="#">Esqueceu a senha?</a>
            <button>Entrar</button>
        </form>
    </div>

    <div class="overlay-container">
        <div class="overlay">
            <div class="overlay-panel overlay-left">
                <h1>Já tem conta?</h1>
                <p>Faça login para ver suas vagas.</p>
                <button class="ghost" id="signIn">Entrar</button>
            </div>
            <div class="overlay-panel overlay-right">
                <h1>Novo aqui?</h1>
                <p>Cadastre-se e estacione fácil.</p>
                <button class="ghost" id="signUp">Cadastrar</button>
            </div>
        </div>
    </div>
</div>

<script>
    const signUpButton = document.getElementById('signUp');
    const signInButton = document.getElementById('signIn');
    const container = document.getElementById('container');

    signUpButton.addEventListener('click', () => {
        container.classList.add("right-panel-active");
    });

    signInButton.addEventListener('click', () => {
        container.classList.remove("right-panel-active");
    });
</script>

</body>
</html>