<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <!-- Link para o Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEJgXcz2bRZ2S47w1XoAA7Ra6d5Tt9BlTkYZdNdIWTbcFvFkeV3ElZL24ffHp" crossorigin="anonymous">

    <!-- Estilo personalizado -->
    <style>
        /* Fundo com gradiente suave */
        body {
            background: linear-gradient(135deg, #6e7dff, #3f56e7);
            font-family: 'Arial', sans-serif;
            height: 100vh;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        /* Centralizando o conteúdo */
        .login-container {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
        }

        /* Estilizando o card */
        .card {
            width: 100%;
            max-width: 450px;
            padding: 30px;
            border-radius: 20px;
            background-color: #fff;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            border: none;
            transition: all 0.3s ease-in-out;
        }

        .card:hover {
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);  /* Sombra mais intensa ao passar o mouse */
        }

        /* Estilo do título */
        .card-title {
            font-size: 32px;
            font-weight: 700;
            color: #3f56e7;
            margin-bottom: 40px;
            text-align: center;
        }

        /* Estilo dos campos de input */
        .form-control {
            border-radius: 10px;
            box-shadow: none;
            padding: 15px;
            font-size: 16px;
            margin-bottom: 20px;
            background-color: #f7f8fa;
            border: 2px solid #dcdfe5;
            transition: all 0.3s ease-in-out;
        }

        /* Efeito de foco nos campos */
        .form-control:focus {
            border-color: #3f56e7;
            background-color: #fff;
            box-shadow: 0 0 5px rgba(63, 86, 231, 0.5);
            outline: none;
            transform: scale(1.02);
        }

        /* Placeholder estilizado */
        .form-control::placeholder {
            color: #bbb;
            font-style: italic;
        }

        /* Estilizando o botão */
        .btn-custom {
            background-color: #3f56e7;
            color: white;
            font-weight: 600;
            border-radius: 30px;
            padding: 15px;
            font-size: 18px;
            border: none;
            width: 100%;
            transition: all 0.3s ease-in-out;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .btn-custom:hover {
            background-color: #2d47b4;
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
            transform: translateY(-3px);
        }

        /* Link para o "Esqueci minha senha" */
        .forgot-password {
            text-align: center;
            margin-top: 20px;
        }

        .forgot-password a {
            color: #3f56e7;
            text-decoration: none;
            font-weight: 600;
        }

        .forgot-password a:hover {
            text-decoration: underline;
        }

        /* Animação no card */
        @keyframes slideIn {
            0% { transform: translateY(-50px); opacity: 0; }
            100% { transform: translateY(0); opacity: 1; }
        }

        .card {
            animation: slideIn 0.5s ease-out;
        }

    </style>
</head>
<body>
    <div class="login-container">
        <div class="card shadow-lg">
            <h4 class="card-title">Bem-vindo de volta</h4>
            <form action="/login" method="POST">
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" required placeholder="Digite seu email">
                </div>
                <div class="mb-3">
                    <label for="senha" class="form-label">Senha</label>
                    <input type="password" class="form-control" id="senha" name="senha" required placeholder="Digite sua senha">
                </div>
                <!-- Botão centralizado -->
                <div class="d-flex justify-content-center">
                    <button type="submit" class="btn btn-custom">Entrar</button>
                </div>
            </form>
            <div class="forgot-password">
                <a href="#">Esqueci minha senha</a>
            </div>
        </div>
    </div>

    <!-- Link para o Bootstrap JS e dependências -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz4fnFO9gyb2ZRgP9L5v7XzRO4PSYwzSbiK1Te1D/hgfR0Rz7yUbKH/c6w" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js" integrity="sha384-pzjw8f+ua7Kw1TIq0v8FqfJqa9oZJv9p3U+qF9OFeGVJxj5mhlPaJrFhzzzz+MyJ" crossorigin="anonymous"></script>
</body>
</html>
