<?php
session_start();
require_once 'config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Get user data
$stmt = $pdo->prepare("SELECT username FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>3D Shape Visualizer</title>
    <link rel="stylesheet" href="styles.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
</head>
<body>
    <div class="container">
        <header>
            <h1>Welcome, <?php echo htmlspecialchars($user['username']); ?></h1>
            <a href="logout.php" class="logout-btn">Logout</a>
        </header>
        
        <div class="main-content">
            <div class="controls">
                <div class="dropdown-group">
                    <label for="colorSelect">Select Color:</label>
                    <select id="colorSelect">
                        <option value="red">Red</option>
                        <option value="green">Green</option>
                        <option value="blue">Blue</option>
                    </select>
                </div>
                
                <div class="dropdown-group">
                    <label for="shapeSelect">Select Shape:</label>
                    <select id="shapeSelect">
                        <option value="cylinder">Cylinder</option>
                        <option value="squarePrism">Square Prism</option>
                        <option value="hexagonalPrism">Hexagonal Prism</option>
                    </select>
                </div>
                
                <button id="findBtn" class="action-btn">Find</button>
                <button id="goLiveBtn" class="action-btn">Go Live</button>
            </div>
            
            <div id="canvasContainer" class="canvas-container"></div>
        </div>
        
        <div class="table-container">
            <table id="historyTable">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Time</th>
                        <th>User</th>
                        <th>Shape</th>
                        <th>Color</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $stmt = $pdo->query("SELECT * FROM selections ORDER BY created_at DESC");
                    while ($row = $stmt->fetch()) {
                        echo "<tr>";
                        echo "<td>" . date('Y-m-d', strtotime($row['created_at'])) . "</td>";
                        echo "<td>" . date('H:i:s', strtotime($row['created_at'])) . "</td>";
                        echo "<td>" . htmlspecialchars($row['username']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['shape']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['color']) . "</td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <script src="script.js"></script>
</body>
</html>