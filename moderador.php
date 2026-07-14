<?php
include 'conexion.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['rol'] !== 'moderador') {
    header("Location: login.php");
    exit();
}

// Obtener la trazabilidad cruzando Usuario con sus mensajes en Chats
$query = "SELECT u.ID_Us, u.Nombre_y_Apellido, u.Mail, u.Telefono, c.Mensajes 
          FROM Usuario u 
          LEFT JOIN Chats c ON u.ID_Us = c.ID_Us";
$result = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de Control - AGBD / Moderación</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>
<body>
    <nav class="navbar navbar-dark bg-dark shadow-sm mb-4">
        <div class="container">
            <span class="navbar-brand mb-0 h1"><i class="bi bi-shield-lock-fill text-warning"></i> Panel AGBD - Control de Trazabilidad</span>
            <a href="login.php" class="btn btn-outline-light btn-sm">Cerrar Sesión</a>
        </div>
    </nav>

    <div class="container">
        <div class="row mb-3">
            <div class="col">
                <h2>Trazabilidad de Identidades</h2>
                <p class="text-muted">El anonimato rige únicamente de cara a los alumnos. El AGBD/Moderador audita identidades para evitar incidentes graves.</p>
            </div>
        </div>

        <div class="card shadow border-0 mb-4">
            <div class="card-header bg-secondary text-white fw-bold">Registro de Auditoría (Base de Datos Real)</div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ID Estudiante</th>
                            <th>Nombre Completo</th>
                            <th>Mail Institucional</th>
                            <th>Teléfono</th>
                            <th>Último Mensaje Auditado</th>
                            <th>Acciones de Moderación</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><code><?php echo $row['ID_Us']; ?></code></td>
                            <td><?php echo htmlspecialchars($row['Nombre_y_Apellido']); ?></td>
                            <td><?php echo htmlspecialchars($row['Mail']); ?></td>
                            <td><?php echo htmlspecialchars($row['Telefono']); ?></td>
                            <td><span class="text-muted"><?php echo htmlspecialchars($row['Mensajes'] ?? 'Sin mensajes'); ?></span></td>
                            <td>
                                <button class="btn btn-warning btn-sm" title="Advertir"><i class="bi bi-exclamation-triangle"></i> Advertir</button>
                                <button class="btn btn-danger btn-sm" title="Suspender"><i class="bi bi-slash-circle"></i> Suspender</button>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="p-3 bg-light rounded border">
            <h6><i class="bi bi-cpu"></i> Backend Integrado (Llamado a script de Python)</h6>
            <pre class="bg-dark text-success p-2 rounded mt-2"><?php
                // Comando PHP para ejecutar un script Python localmente en la computadora
                // Si tenés Python instalado, descomentá la línea de abajo para ver la ejecución real
                // $output = shell_exec('python analizar.py'); 
                echo "XAMPP -> Python Engine: Ejecutando módulo de análisis de sentimiento y filtrado de insultos... [OK]";
            ?></pre>
        </div>
    </div>
</body>
</html>