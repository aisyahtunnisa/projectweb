<?php
$servername = "localhost";
$username = "root"; // Adjust as necessary
$password = ""; // Adjust as necessary
$dbname = "bon_appetit";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = "";

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];
    
    // Handle file upload
    $media_url = '';
    $media_type = '';

    // Check if a file has been uploaded
    if (isset($_FILES['media']['name']) && $_FILES['media']['error'] == 0) {
        // Directory to store uploaded files
        $target_dir = "uploads/";

        // Ensure the 'uploads' directory exists, if not, create it
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true); // Create 'uploads' directory with full permissions
        }

        // Generate a unique file name to prevent overwriting
        $file_name = basename($_FILES["media"]["name"]);
        $target_file = $target_dir . uniqid() . "_" . $file_name; // Unique file name

        // Check file type (image or video)
        $media_type = mime_content_type($_FILES["media"]["tmp_name"]);

        // Move uploaded file to the 'uploads' directory
        if (move_uploaded_file($_FILES["media"]["tmp_name"], $target_file)) {
            $media_url = $target_file; // Save file path
        } else {
            $message = "Error: Unable to upload file.";
        }
    }

    // Insert tutorial into the database
    $sql = "INSERT INTO tutorials (title, content, media_url, media_type) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $title, $content, $media_url, $media_type);

    if ($stmt->execute()) {
        $message = "Tutorial added successfully!";
    } else {
        $message = "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Insert Tutorial</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 50px auto;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            color: #444;
        }
        form {
            display: flex;
            flex-direction: column;
        }
        label {
            margin: 10px 0 5px;
        }
        input, textarea, button {
            font-size: 16px;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            background-color: #007bff;
            color: white;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        button:hover {
            background-color: #0056b3;
        }
        .message {
            color: green;
            text-align: center;
            margin-bottom: 15px;
        }
        .button-group {
            display: flex;
            justify-content: space-between;
        }
        .button-group button {
            flex: 1;
            margin: 0 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Insert New Tutorial</h1>

        <!-- Display success or error message -->
        <?php if ($message): ?>
            <p class="<?php echo ($message === "Tutorial added successfully!") ? 'message' : 'error'; ?>"><?php echo htmlspecialchars($message); ?></p>
        <?php endif; ?>

        <form method="POST" action="" enctype="multipart/form-data">
            <label for="title">Title:</label>
            <input type="text" id="title" name="title" required>

            <label for="content">Content:</label>
            <textarea id="content" name="content" rows="5" required></textarea>

            <label for="media">Upload Image/Video:</label>
            <input type="file" id="media" name="media" accept="image/*,video/*">

            <button type="submit">Submit Tutorial</button>
            <button type="button" onclick="window.location.href='view_tutorial.php'">View Tutorials</button>
        </form>
    </div>
</body>
</html>
