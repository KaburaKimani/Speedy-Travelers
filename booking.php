<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $from = $_POST['from'];
    $to = $_POST['to'];
    $date = $_POST['date'];
    $time = $_POST['time'];

    // Here you would typically query your database to find available routes
    // For demonstration purposes, we'll use a static array of routes
    $routes = [
        ["from" => "City A", "to" => "City B", "departure" => "2024-11-22 08:00"],
        ["from" => "City A", "to" => "City C", "departure" => "2024-11-22 09:00"],
        ["from" => "City B", "to" => "City C", "departure" => "2024-11-22 10:00"]
    ];

    $availableRoutes = array_filter($routes, function($route) use ($from, $to, $date, $time) {
        return $route['from'] == $from && $route['to'] == $to && strpos($route['departure'], $date) !== false;
    });
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Available Routes</title>
    <link rel="stylesheet" href="booking.css">
</head>
<body>
    <div class="booking-container">
        <h1>Available Routes</h1>
        <?php if (!empty($availableRoutes)): ?>
            <ul>
                <?php foreach ($availableRoutes as $route): ?>
                    <li><?php echo "From: " . $route['from'] . " To: " . $route['to'] . " Departure: " . $route['departure']; ?></li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>No available routes found for your search criteria.</p>
        <?php endif; ?>
    </div>
</body>
</html>
