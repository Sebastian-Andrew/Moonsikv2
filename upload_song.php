<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['song'])) {
    $targetDir = "assets/music/";
    $targetFile = $targetDir . basename($_FILES['song']['name']);
    $uploadOk = 1;
    $fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    // Validate MP3 file type
    if ($fileType !== 'mp3') {
        echo "Only MP3 files are allowed.";
        $uploadOk = 0;
    }

    // Check if file already exists
    if (file_exists($targetFile)) {
        echo "Sorry, file already exists.";
        $uploadOk = 0;
    }

    // Check file size (optional)
    if ($_FILES['song']['size'] > 50000000) { // 50MB limit
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    // If no errors, try to upload the file
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
    } else {
        if (move_uploaded_file($_FILES['song']['tmp_name'], $targetFile)) {
            echo "The file " . htmlspecialchars(basename($_FILES['song']['name'])) . " has been uploaded.";

            // Include getID3 to extract duration
            require_once('getID3-master\getid3\getid3.php');  // Correct path to getID3

            $getID3 = new getID3;
            $fileInfo = $getID3->analyze($targetFile);
            $songDuration = isset($fileInfo['playtime_string']) ? $fileInfo['playtime_string'] : '00:00'; // Duration as hh:mm:ss or mm:ss

            // Database connection
            $conn = new mysqli("localhost", "root", "", "Moonsik");
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Prepare SQL Insert Query
            $stmt = $conn->prepare("INSERT INTO Songs (title, artist, album, genre, duration, path, albumOrder, plays) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $title = htmlspecialchars(basename($_FILES['song']['name']));  // Song title (filename without path)
            $artist_id = 1;  // Default artist_id, update as necessary
            $album_id = 1;   // Default album_id, update as necessary
            $genre_id = 1;   // Default genre_id, update as necessary
            $albumOrder = 1; // Default album order, update as needed
            $plays = 0;      // Default play count

            // Bind parameters to the prepared statement
            $stmt->bind_param("siiissii", $title, $artist_id, $album_id, $genre_id, $songDuration, $targetFile, $albumOrder, $plays);

            // Execute the query and check for success
            if ($stmt->execute()) {
                echo "Song metadata has been saved to the database!";
            } else {
                echo "Error: " . $stmt->error;
            }

            // Close statement and connection
            $stmt->close();
            $conn->close();
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
}

function getSongDuration($filePath) {
    require_once('getID3-master\getid3\getid3.php'); 
    $getID3 = new getID3;
    $fileInfo = $getID3->analyze($filePath);
    return isset($fileInfo['playtime_seconds']) ? $fileInfo['playtime_seconds'] : 0;
}
?>
