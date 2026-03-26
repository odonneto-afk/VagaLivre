<?php include("./topo.php"); ?>
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