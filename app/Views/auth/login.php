<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <title>MANAGER - Karol Couto Studio</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <link rel="stylesheet" href="/assets/css/style.css">
        <style>
            html, body{ height: 100%; }
            .form-container{ max-width: 600px; padding: 1rem; }
        </style>
    </head>
    <body class="d-flex align-items-center py-4 bg-body-tertiary">
        <main class="w-100 m-auto form-container">
             <?php if (isset($error) && $error): ?>
                <p style="color: red; border: 1px solid red; padding: 10px;"><?= htmlspecialchars($error); ?></p>
            <?php endif; ?>
            <form action="/login/auth" method="post"> 
                <div class="form-floating m-3">               
                    <h1 class="h3 mb-3 px-2 fw-normal"><?= htmlspecialchars($titulo); ?></h1>                
                </div>
                <div class="form-floating m-3">
                    <input type="text" class="form-control" id="userInput" name="usuario_ou_email" placeholder="Login">
                    <label form="userInput">Usuário ou e-mail</label>
                </div>
                <div class="form-floating m-3">
                    <input type="password" class="form-control" id="passInput" name="senha" placeholder="Password">
                    <label form="passInput">Senha</label>
                </div>
                <div class="form-floating m-3">
                    <button class="w-100 btn btn-primary py-2" id="submitbtn" type="submit">Entrar</button>
                </div>
            </form>
        </main>

    </body>
</html>