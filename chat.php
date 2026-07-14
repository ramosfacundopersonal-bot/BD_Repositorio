<?php
include 'conexion.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['rol'] !== 'estudiante') {
    header("Location: login.php");
    exit();
}

// Procesar envío de mensaje
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['mensaje'])) {
    $mensaje = $_POST['mensaje'];
    $id_us = $_SESSION['user_id'];
    
    // El moderador ID 1 audita este chat por defecto
    $id_agbd = 1; 
    $miembros = 2;

    $stmt = $conn->prepare("INSERT INTO Chats (ID_Us, ID_AGBD, Mensajes, Miembros) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iisi", $id_us, $id_agbd, $mensaje, $miembros);
    $stmt->execute();
}

// Obtener mensajes de la base de datos
$query = "SELECT c.Mensajes, c.ID_Us, u.Nombre_y_Apellido FROM Chats c JOIN Usuario u ON c.ID_Us = u.ID_Us ORDER BY c.ID_Chat ASC";
$result = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Aula Virtual - Chat Anónimo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        body { background-color: #f0f2f5; }
        .chat-room { height: 60vh; overflow-y: auto; background: white; border-radius: 8px; padding: 15px; }
        .msg-box { margin-bottom: 12px; }
        .msg-anon { background-color: #e9ecef; border-radius: 15px; padding: 8px 14px; display: inline-block; max-width: 75%; }
        .msg-me { background-color: #0d6efd; color: white; border-radius: 15px; padding: 8px 14px; display: inline-block; max-width: 75%; }
    </style>
</head>
<body>
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h4 class="fw-bold mb-0">Sala de Consultas Activa</h4>
                <span class="badge bg-success">Registrado como: <?php echo $_SESSION['nombre']; ?> (Tu identidad es Anónima para tus pares)</span>
            </div>
            <a href="login.php" class="btn btn-outline-danger btn-sm">Cerrar Sesión</a>
        </div>

        <div class="row">
            <div class="col-md-3 mb-3">
                <div class="card bg-light border-0 shadow-sm">
                    <div class="card-body">
                        <h6 class="fw-bold"><i class="bi bi-shield-check"></i> Código de Conducta</h6>
                        <p class="text-muted small">Este espacio promueve la participación inclusiva y el debate respetuoso. Se sanciona el cyberbullying.</p>
                    </div>
                </div>
            </div>

            <div class="col-md-9">
                <div class="chat-room shadow-sm border mb-3 p-3">
                    <div class="messages">
                        <?php while($row = $result->fetch_assoc()): ?>
                            <?php $es_propio = ($row['ID_Us'] == $_SESSION['user_id']); ?>
                            <div class="msg-box <?php echo $es_propio ? 'text-end' : 'text-start'; ?>">
                                <span class="d-block small text-muted mb-1">
                                    <?php echo $es_propio ? 'Vos (Anónimo)' : 'Estudiante Anónimo'; ?>
                                </span>
                                <div class="<?php echo $es_propio ? 'msg-me' : 'msg-anon'; ?>">
                                    <?php echo htmlspecialchars($row['Mensajes']); ?>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>
                </div>

                <form action="chat.php" method="POST" class="input-group">
                    <input type="text" name="mensaje" class="form-control" placeholder="Escribí una duda o comentario..." required>
                    <button class="btn btn-primary" type="submit"><i class="bi bi-send"></i> Enviar</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>