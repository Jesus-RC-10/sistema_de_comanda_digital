<?php
require_once __DIR__ . '/../layout/header.php';
?>

<div id="dashboard" class="content-section">
    <h2>📊 Información General</h2>
    <div class="dashboard-cards">
        <div class="card">
            <h3>💰 Ventas Hoy</h3>
            <div class="card-content">
                <p class="metric-value">$<?php echo number_format($data['ventas_hoy'], 2); ?></p>
                <p class="metric-label">Total de ventas del día de hoy</p>
            </div>
        </div>
        
        <div class="card">
            <h3>📦 Pedidos Activos</h3>
            <div class="card-content">
                <p class="metric-value"><?php echo $data['pedidos_activos']; ?></p>
                <p class="metric-label">Pedidos en proceso</p>
            </div>
        </div>
        
        <div class="card">
            <h3>🪑 Mesas Ocupadas</h3>
            <div class="card-content">
                <p class="metric-value"><?php echo $data['mesas_ocupadas']; ?>/<?php echo $data['total_mesas']; ?></p>
                <p class="metric-label">Mesas en uso actualmente</p>
            </div>
        </div>
    </div>
    
    <!-- Productos desde la Base de Datos -->
    <div class="report-section">
        <h3>🍽️ Productos del Menú</h3>
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Producto</th>
                        <th>Descripción</th>
                        <th>Precio</th>
                        <th>Stock</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($data['productos'] as $row): ?>
                        <?php
                        $estado = $row['stock'] > 0 ? '🟢 Disponible' : '🔴 Sin stock';
                        ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><strong><?php echo $row['nombre']; ?></strong></td>
                            <td><?php echo $row['descripcion']; ?></td>
                            <td>$<?php echo number_format($row['precio'], 2); ?></td>
                            <td><?php echo $row['stock']; ?></td>
                            <td><?php echo $estado; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>