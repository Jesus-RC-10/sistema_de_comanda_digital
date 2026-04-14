<?php
require_once __DIR__ . '/../layout/header.php';
?>

<div id="menu" class="content-section">
    <h2>🍽️ Gestión de Menú con Recetas</h2>
    
    <!-- Formulario para agregar producto -->
    <div class="report-section">
        <h3>➕ Agregar Nuevo Producto y su Receta</h3>
        <form method="POST" class="form-grid" enctype="multipart/form-data">
            <input type="hidden" name="accion" value="agregar_producto">
            <input type="hidden" name="seccion_activa" value="menu">
            
            <div class="form-group">
                <label>Nombre del Producto:</label>
                <input type="text" name="nombre" required placeholder="Ej: Lomo Saltado">
            </div>
            <div class="form-group">
                <label>Descripción:</label>
                <textarea name="descripcion" required placeholder="Descripción del producto..."></textarea>
            </div>
            <div class="form-group">
                <label>Precio:</label>
                <input type="number" name="precio" step="0.01" required placeholder="Ej: 35.00">
            </div>
            <div class="form-group">
                <label>Categoría:</label>
                <select name="categoria_id" required>
                    <option value="">Seleccionar categoría</option>
                    <?php foreach ($data['categorias'] as $categoria): ?>
                        <option value="<?php echo $categoria['id']; ?>"><?php echo $categoria['nombre']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Stock Inicial Predeterminado:</label>
                <input type="number" name="stock" required min="0" placeholder="Ej: 10" value="0">
            </div>
            <div class="form-group">
                <label>Imagen del Platillo:</label>
                <input type="file" name="imagen" accept="image/*">
            </div>
            
            <div class="form-group" style="grid-column: 1 / -1;">
                <hr>
                <h4>📋 Configurar Receta (Insumos que descontará)</h4>
                <div id="receta-container">
                    <!-- Filas dinámicas irán aquí -->
                </div>
                <button type="button" class="btn btn-sm" onclick="agregarIngrediente()">+ Añadir Insumo a la Receta</button>
            </div>

            <div class="form-group" style="grid-column: 1 / -1; margin-top: 15px;">
                <button type="submit" class="btn">Guardar Producto Completo</button>
            </div>
        </form>
    </div>

    <!-- Lista de productos -->
    <div class="report-section">
        <h3>📋 Productos del Menú</h3>
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Imagen</th>
                        <th>Producto</th>
                        <th>Descripción</th>
                        <th>Precio</th>
                        <th>Categoría</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($data['productos'] as $row): ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td>
                                <?php if(!empty($row['imagen'])): ?>
                                    <img src="<?php echo ASSETS_URL . '../public/images/platillos/' . $row['imagen']; ?>" width="50" style="border-radius: 5px;">
                                <?php else: ?>
                                    No imagen
                                <?php endif; ?>
                            </td>
                            <td><strong><?php echo $row['nombre']; ?></strong></td>
                            <td><?php echo $row['descripcion']; ?></td>
                            <td>$<?php echo number_format($row['precio'], 2); ?></td>
                            <td><?php echo $row['categoria']; ?></td>
                            <td>
                                <form method="POST" style="display:inline;">
                                    <input type="hidden" name="accion" value="eliminar_producto">
                                    <input type="hidden" name="producto_id" value="<?php echo $row['id']; ?>">
                                    <button type="submit" class="btn-sm btn-danger" onclick="return confirm('¿Está seguro de eliminar este producto?')">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<template id="ingrediente-template">
    <div class="receta-fila" style="display: flex; gap: 10px; margin-bottom: 10px; align-items: center;">
        <select name="ingredientes_id[]" required style="flex: 2;">
            <option value="">Seleccionar Insumo del Inventario</option>
            <?php foreach ($data['ingredientes'] as $ingrediente): ?>
                <option value="<?php echo $ingrediente['id']; ?>">
                    <?php echo $ingrediente['nombre'] . ' (Disponibles: ' . $ingrediente['cantidad_actual'] . ' ' . $ingrediente['unidad_medida'] . ')'; ?>
                </option>
            <?php endforeach; ?>
        </select>
        <input type="number" name="ingredientes_cantidad[]" step="0.01" required placeholder="Ej: 2.5" style="flex: 1;">
        <button type="button" class="btn-sm btn-danger" onclick="this.parentElement.remove()">X</button>
    </div>
</template>

<script>
    function agregarIngrediente() {
        const container = document.getElementById('receta-container');
        const template = document.getElementById('ingrediente-template');
        const clone = template.content.cloneNode(true);
        container.appendChild(clone);
    }
    // Agregar un ingrediente vacio por defecto
    document.addEventListener('DOMContentLoaded', function() {
        // agregarIngrediente(); // Opcional
    });
</script>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>