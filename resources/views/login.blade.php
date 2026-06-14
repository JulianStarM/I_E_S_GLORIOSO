<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>

<body>

<div class="login-container">

    <form class="login-box" method="POST" action="{{ route('login.attempt') }}">
        @csrf

        <h1>Iniciar Sesión</h1>

        <input type="email" name="email" placeholder="Correo" required>

        <input type="password" name="password" placeholder="Contraseña" required>

        <button type="submit">Ingresar</button>

        @if ($errors->any())
            <div style="color:red; margin-top:10px;">
                {{ $errors->first() }}
            </div>
        @endif

    </form>

</div>

</body>
</html>