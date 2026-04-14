<?php
require_once __DIR__ . '/../layout/header.php';
?>

<div id="usuarios" class="content-section">
    <h2>👥 Gestión de Usuarios</h2>
    
    <!-- Formulario para agregar usuario -->
    <div class="report-section">
        <h3>➕ Agregar Nuevo Usuario</h3>
        <form method="POST" class="form-grid">
            <input type="hidden" name="accion" value="agregar_usuario">
            <input type="hidden" name="seccion_activa" value="usuarios">
            <div class="form-group">
                <label>Usuario:</label>
                <input type="text" name="usuario" required placeholder="Ej: mesero2">
            </div>
            <div class="form-group">
                <label>Contraseña:</label>
                <input type="password" name="password" required placeholder="Contraseña">
            </div>
            <div class="form-group">
                <label>Nombre Completo:</label>
                <input type="text" name="nombre_completo" required placeholder="Ej: Juan Pérez">
            </div>
            <div class="form-group">
                <label>Rol:</label>
                <select name="rol" required>
                    <option value="mesero">Mesero</option>
                    <option value="cocina">Cocina</option>
                    <option value="caja">Caja</option>
                    <option value="admin">Administrador</option>
                </select>
            </div>
            <div class="form-group">
                <button type="submit" class="btn">Agregar Usuario</button>
            </div>
        </form>
    </div>

    <!-- Lista de usuarios -->
    <div class="report-section">
        <h3>📋 Usuarios del Sistema</h3>
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Usuario</th>
                        <th>Nombre</th>
                        <th>Rol</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($data['usuarios'] as $row): ?>
                        <?php $estado = $row['activo'] ? '🟢 Activo' : '🔴 Inactivo'; ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><strong><?php echo $row['usuario']; ?></strong></td>
                            <td><?php echo $row['nombre']; ?></td>
                            <td><?php echo $row['rol']; ?></td>
                            <td><?php echo $estado; ?></td>
                            <td>
                                <form method="POST" style="display:inline;">
                                    <input type="hidden" name="accion" value="eliminar_usuario">
                                    <input type="hidden" name="usuario_id" value="<?php echo $row['id']; ?>">
                                    <input type="hidden" name="seccion_activa" value="usuarios">
                                    <button type="submit" class="btn-sm btn-danger" onclick="return confirm('¿Está seguro de eliminar este usuario?')">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>