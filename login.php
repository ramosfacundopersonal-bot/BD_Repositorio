<?php
include 'conexion.php';
session_start();

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $mail = $_POST['mail'];
    $password = $_POST['contrasena'];

    // 1. Buscamos en la tabla Usuario (Estudiantes)
    $stmt = $conn->prepare("SELECT ID_Us, Nombre_y_Apellido FROM Usuario WHERE Mail = ? AND Contrasena = ?");
    $stmt->bind_param("ss", $mail, $password);
    $stmt->execute();
    $res_us = $stmt->get_result();

    if ($res_us->num_rows > 0) {
        $user = $res_us->fetch_assoc();
        $_SESSION['user_id'] = $user['ID_Us'];
        $_SESSION['nombre'] = $user['Nombre_y_Apellido'];
        $_SESSION['rol'] = 'estudiante';
        header("Location: chat.php");
        exit();
    }

    // 2. Si no es estudiante, buscamos en Moderador
    $stmt2 = $conn->prepare("SELECT ID_AGBD, Nombre_y_Apellido FROM Moderador WHERE Mail = ? AND Contrasena = ?");
    $stmt2->bind_param("ss", $mail, $password);
    $stmt2->execute();
    $res_mod = $stmt2->get_result();

    if ($res_mod->num_rows > 0) {
        $mod = $res_mod->fetch_assoc();
        $_SESSION['user_id'] = $mod['ID_AGBD'];
        $_SESSION['nombre'] = $mod['Nombre_y_Apellido'];
        $_SESSION['rol'] = 'moderador';
        header("Location: moderador.php");
        exit();
    }

    $error = "Credenciales incorrectas. Verificá los datos de inicio de sesión.";
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Sistema de Chat - Ingreso</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .login-container { max-width: 400px; margin-top: 10%; }
    </style>
</head>
<body>
    <div class="container login-container">
        <div class="card shadow p-4">
            <div class="text-center mb-4">
                <h3 class="text-primary fw-bold">Aula Virtual Chat</h3>
                <p class="text-muted small">Conectá y colaborá de manera constructiva</p>
            </div>
            
            <?php if ($error != ""): ?>
                <div class="alert alert-danger py-2 small"><?php echo $error; ?></div>
            <?php endif; ?>

            <form action="login.php" method="POST">
                <div class="mb-3">
                    <label class="form-label">Correo Institucional</label>
                    <input type="email" class="form-control" name="mail" placeholder="ejemplo@institucion.edu" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Contraseña</label>
                    <input type="password" class="form-control" name="contrasena" placeholder="••••••••" required>
                </div>
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary">Ingresar al Sistema</button>
                </div>
            </form>
            <div class="text-center mt-3">
                <span class="text-muted small">Prueba: framos@institucion.edu (alumno123) o econtreras@institucion.edu (admin123)</span>
            </div>
        </div>
    </div>
</body>
</html>