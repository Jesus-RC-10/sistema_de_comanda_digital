<?php
require_once __DIR__ . '/../layout/header.php';
?>

<div id="inventario" class="content-section">
    <h2>📦 Control de Inventario</h2>
    
    <!-- Inventario de ingredientes -->
    <div class="report-section">
        <h3>➕ Agregar Nuevo Insumo al Inventario</h3>
        <form method="POST" action="<?php echo BASE_URL; ?>index.php?action=admin&seccion=inventario" style="margin-bottom: 20px; display: flex; gap: 10px; flex-wrap: wrap; align-items: flex-end;">
            <input type="hidden" name="accion" value="agregar_ingrediente">
            <input type="hidden" name="seccion_activa" value="inventario">
            
            <div class="form-group" style="margin: 0;">
                <label>Nombre:</label>
                <input type="text" name="nombre" required placeholder="Ej: Tortillas" style="padding: 6px;">
            </div>
            <div class="form-group" style="margin: 0;">
                <label>Categoría:</label>
                <select name="categoria" required style="padding: 6px;">
                    <option value="vegetales">Vegetales</option>
                    <option value="carnes">Carnes</option>
                    <option value="lacteos">Lácteos</option>
                    <option value="granos">Granos</option>
                    <option value="especias">Especias</option>
                    <option value="bebidas">Bebidas</option>
                    <option value="otros">Otros</option>
                </select>
            </div>
            <div class="form-group" style="margin: 0;">
                <label>Unidad:</label>
                <select name="unidad_medida" required style="padding: 6px;">
                    <option value="kg">Kilogramos (kg)</option>
                    <option value="gr">Gramos (gr)</option>
                    <option value="lt">Litros (lt)</option>
                    <option value="ml">Mililitros (ml)</option>
                    <option value="unidad">Unidad</option>
                    <option value="paquete">Paquete</option>
                </select>
            </div>
            <div class="form-group" style="margin: 0;">
                <label>Stock Inicial:</label>
                <input type="number" name="cantidad_actual" step="0.01" required value="0" style="padding: 6px; width: 80px;">
            </div>
            <div class="form-group" style="margin: 0;">
                <label>Minimo Alerta:</label>
                <input type="number" name="cantidad_minima" step="0.01" required value="10" style="padding: 6px; width: 80px;">
            </div>
            <button type="submit" class="btn">Guardar Insumo</button>
        </form>

        <h3>🥕 Ingredientes e Insumos Listados</h3>
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Ingrediente</th>
                        <th>Categoría</th>
                        <th>Cantidad Actual</th>
                        <th>Mínimo</th>
                        <th>Unidad</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($data['ingredientes'] as $ingrediente): ?>
                        <?php
                        $estado = $ingrediente['cantidad_actual'] <= $ingrediente['cantidad_minima'] ? '🔴 Stock Bajo' : '🟢 Normal';
                        $color_class = $ingrediente['cantidad_actual'] <= $ingrediente['cantidad_minima'] ? 'stock-bajo' : '';
                        ?>
                        <tr class="<?php echo $color_class; ?>">
                            <td><?php echo $ingrediente['id']; ?></td>
                            <td><strong><?php echo $ingrediente['nombre']; ?></strong></td>
                            <td><?php echo $ingrediente['categoria']; ?></td>
                            <td>
                                <form method="POST" action="<?php echo BASE_URL; ?>index.php?action=admin&seccion=inventario" style="display:inline;">
                                    <input type="hidden" name="accion" value="actualizar_inventario">
                                    <input type="hidden" name="ingrediente_id" value="<?php echo $ingrediente['id']; ?>">
                                    <input type="hidden" name="seccion_activa" value="inventario">
                                    <input type="number" name="cantidad_actual" value="<?php echo $ingrediente['cantidad_actual']; ?>" step="0.001" style="width: 80px; padding: 4px;">
                                    <button type="submit" class="btn-sm">Actualizar</button>
                                </form>
                            </td>
                            <td><?php echo $ingrediente['cantidad_minima']; ?></td>
                            <td><?php echo $ingrediente['unidad_medida']; ?></td>
                            <td><?php echo $estado; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Alertas de stock bajo -->
    <div class="report-section">
        <h3>⚠️ Alertas de Stock Bajo</h3>
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Ingrediente</th>
                        <th>Cantidad Actual</th>
                        <th>Mínimo Requerido</th>
                        <th>Diferencia</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($data['alertas_stock'])): ?>
                        <?php foreach ($data['alertas_stock'] as $row): ?>
                            <?php $diferencia = $row['cantidad_minima'] - $row['cantidad_actual']; ?>
                            <tr style="background-color: #ffe6e6;">
                                <td><strong><?php echo $row['nombre']; ?></strong></td>
                                <td><?php echo $row['cantidad_actual']; ?> <?php echo $row['unidad_medida']; ?></td>
                                <td><?php echo $row['cantidad_minima']; ?> <?php echo $row['unidad_medida']; ?></td>
                                <td>Faltan <?php echo $diferencia; ?> <?php echo $row['unidad_medida']; ?></td>
                                <td>
                                    <button class="btn-sm btn-danger" onclick="alert('Contactar a proveedor: <?php echo $row['proveedor']; ?>')">Pedir</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="5">✅ Todo el stock está en niveles normales</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>