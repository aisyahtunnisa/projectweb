<?php
$servername = "localhost";
$username = "root"; // Adjust as necessary
$password = ""; // Adjust as necessary
$dbname = "bon_appetit";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM tutorials ORDER BY created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Tutorials</title>
    <style>
        /* Styling from previous example */
    </style>
</head>
<body>
    <div class="container">
        <h1>Bon Appetit Tutorials</h1>
        <?php if ($result->num_rows > 0): ?>
            <ul>
                <?php while($row = $result->fetch_assoc()): ?>
                    <li>
                        <h2><?php echo htmlspecialchars($row['title']); ?></h2>
                        <p><?php echo nl2br(htmlspecialchars($row['content'])); ?></p>

                        <?php if ($row['media_type'] == 'image/jpeg' || $row['media_type'] == 'image/png' || $row['media_type'] == 'image/gif'): ?>
                            <img src="<?php echo $row['media_url']; ?>" alt="Tutorial Image" style="width:100%; max-width:600px; height:auto; border-radius: 10px;">
                        <?php elseif ($row['media_type'] == 'video/mp4' || $row['media_type'] == 'video/webm' || $row['media_type'] == 'video/ogg'): ?>
                            <video controls style="width:100%; max-width:600px; border-radius: 10px;">
                                <source src="<?php echo $row['media_url']; ?>" type="<?php echo $row['media_type']; ?>">
                                Your browser does not support the video tag.
                            </video>
                        <?php endif; ?>

                        <small>Created at: <?php echo $row['created_at']; ?></small>
                    </li>
                <?php endwhile; ?>
            </ul>
        <?php else: ?>
            <p class="no-tutorials">No tutorials found. Be the first to add one!</p>
        <?php endif; ?>

        <a href="insert_tutorial.php" class="back-button">Add New Tutorial</a>
        
    </div>

    <?php $conn->close(); ?>
</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Tutorials</title>
    <style>
        /* General Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            background-color: #f9f9f9;
            color: #333;
            padding: 20px;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            background: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
            font-size: 2.2em;
            color: #444;
        }

        ul {
            list-style-type: none;
        }

        li {
            margin-bottom: 20px;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background: #fafafa;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        li:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        h2 {
            font-size: 1.5em;
            margin-bottom: 10px;
            color: #007bff;
        }

        p {
            margin-bottom: 10px;
            font-size: 1em;
            line-height: 1.5;
            color: #555;
        }

        small {
            font-size: 0.9em;
            color: #888;
        }

        .no-tutorials {
            text-align: center;
            font-size: 1.2em;
            color: #999;
            margin-top: 50px;
        }

        .back-button {
            display: block;
            width: fit-content;
            margin: 20px auto;
            padding: 10px 20px;
            background: #007bff;
            color: white;
            text-align: center;
            text-decoration: none;
            border-radius: 5px;
            transition: background 0.3s;
        }

        .back-button:hover {
            background: #0056b3;
        }
    </style>
</head>
</html>
