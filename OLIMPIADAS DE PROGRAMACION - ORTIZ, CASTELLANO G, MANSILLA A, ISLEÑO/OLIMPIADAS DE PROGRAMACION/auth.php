<?php
include 'db.php';
include 'functions.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['login'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];

        $sql = "SELECT * FROM users WHERE username='$username' AND password='$password'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $_SESSION['username'] = $username;
            header("Location: index.php");
            exit();
        } else {
            $error = "Usuario o contraseña incorrectos";
        }
    } elseif (isset($_POST['register'])) {
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];

        // Verificar si las contraseñas coinciden
        if ($password !== $confirm_password) {
            $error = "Las contraseñas no coinciden";
        } else {
            // Verificar si el usuario ya existe
            $sql = "SELECT * FROM users WHERE username='$username' OR email='$email'";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                $error = "El usuario o correo electrónico ya están registrados";
            } else {
                // Insertar nuevo usuario
                $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$password')";
                if ($conn->query($sql) === TRUE) {
                    $success = "Registro exitoso. Por favor, inicie sesión.";
                } else {
                    $error = "Error al registrar el usuario";
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login/Registro</title>
    <link rel="stylesheet" href="styles/styles_log.css">
</head>
<body>
    <nav>
        <a href="index.php" class="logo">Tienda Web</a>
        <a href="index.php">Inicio</a>
        <a href="auth.php" class="active right">Login/Registro</a>
    </nav>
<div class="container login-register">
    <div class="login">
    <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
    <form method="POST" action="">
        <h2>Iniciar Sesión</h2>
        <input type="text" name="username" placeholder="Usuario" required>
        <input type="password" name="password" placeholder="Contraseña" required>
        <button type="submit" name="login">Login</button>
    </form>
    </div>
    <div>
    <?php if (isset($success)) echo "<p style='color:green;'>$success</p>"; ?>
    <form method="POST" action="">
        <h2>Registrarse</h2>
        <input type="text" name="username" placeholder="Usuario" required>
        <input type="email" name="email" placeholder="Correo electrónico" required>
        <input type="password" name="password" placeholder="Contraseña" required>
        <input type="password" name="confirm_password" placeholder="Repetir contraseña" required>
        <button type="submit" name="register">Registrarse</button>
    </form>
    </div>
</div>
</body>
</html>
