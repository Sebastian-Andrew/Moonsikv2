<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "Moonsik";


$conn = new mysqli($servername, $username, $password, $database);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


if (isset($_POST['create'])) {
    $title = $_POST['title'];
    $artist = $_POST['artist'];

    $stmt = $conn->prepare("INSERT INTO songs (title, artist) VALUES (?, ?)");
    $stmt->bind_param("ss", $title, $artist);
    if ($stmt->execute()) {
        echo "Song added successfully.";
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}

if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $title = $_POST['title'];
    $artist = $_POST['artist'];
    $album_order = $_POST['albumOrder'];

    $stmt = $conn->prepare("UPDATE songs SET title = ?, artist = ?, albumOrder = ? WHERE id = ?");
    $stmt->bind_param("ssii", $title, $artist, $album_order, $id);

    if ($stmt->execute()) {
         "Song updated successfully.";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}



$sql = "SELECT * FROM songs";
$result = $conn->query($sql);


$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Page</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="navigation">
        <a href="browse.php">Browse</a>
        <a href="yourMusic.php">Your Music</a>
        <a href="profile.php">Profile</a>
        <a href="admin.php">Admin</a>
    </div>

    <h1>Admin Page - Song Management</h1>

    <!-- CREATE Form -->
    <h2>Add a New Song</h2>
    <form id="addSongForm">
        <input type="text" id="songTitle" name="title" placeholder="Enter Song Title" required>
        <input type="text" id="songArtist" name="artist" placeholder="Enter Artist Name" required>
        <input type="text" id="albumNumber" name="album" placeholder="Enter Album Number" required>
        <button type="submit">Add Song</button>
    </form>

    <form action="upload_song.php" method="post" enctype="multipart/form-data">
    <label for="song">Choose Song:</label>
    <input type="file" name="song" id="song" accept="audio/mp3" required><br><br>
    
    <button type="submit">Upload Song</button>
</form>

    <!-- READ Data -->
    <h2>Song List</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Artist</th>
            <th>Actions</th>
        </tr>
        <?php if ($result->num_rows > 0): ?>
            <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['title']; ?></td>
                    <td><?php echo $row['artist']; ?></td>
                    <td>
                        <!-- Update Form -->
                        <form method="POST" style="display:inline-block;">
    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
    <input type="text" name="title" placeholder="New Title" required>
    <input type="text" name="artist" placeholder="New Artist" required>
    <input type="number" name="albumOrder" placeholder="Order in Album" required>
    <button type="submit" name="update">Update</button>
</form>

                        <!-- Delete Form -->
                        <form method="POST" style="display:inline-block;">
                            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                            <button type="submit" name="delete">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="4">No songs found.</td>
            </tr>
        <?php endif; ?>
    </table>

    <div id="responseMessage"></div>

    <script>
        $(document).ready(function() {
            $('#addSongForm').submit(function(e) {
                e.preventDefault();

                var title = $('#songTitle').val();
                var artist = $('#songArtist').val();
                var album = $('#songArtist').val();

                
                $.ajax({
                    url: '', 
                    type: 'POST',
                    data: {
                        create: true,  
                        title: title,
                        artist: artist
                    },
                    success: function(response) {
                        $('#responseMessage').html(response); 
                        $('#songTitle').val(''); 
                        $('#songArtist').val('');
                        $('#albumNumber').val('');
                        location.reload(); 
                    },
                    error: function(xhr, status, error) {
                        $('#responseMessage').html('Error: ' + error);
                    }
                });
            });
        });
    </script>
 <style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    background-color: #212121; 
    font-family: Arial, sans-serif;
    color: #e0e0e0; 
    line-height: 1.6;
}

.navigation {
    background-color: #45a049;
    display: flex;
    justify-content: space-around;
    padding: 15px;
    margin-bottom: 20px;
}

.navigation a {
    color: #ccc;
    text-decoration: none;
    font-size: 18px;
    padding: 10px;
    transition: background-color 0.3s ease;
}

.navigation a:hover {
    background-color: #45a049; 
}

h1, h2 {
    text-align: center;
    color: #45a049;
    margin: 20px 0;
}

h1 {
    font-size: 2.5rem;
    margin-top: 30px;
}

.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
    background-color: #333; 
    border-radius: 8px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

form {
    background-color: #fff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    margin-bottom: 30px;
}

input[type="text"], input[type="file"], button {
    width: 100%;
    padding: 10px;
    margin: 10px 0;
    font-size: 16px;
    border: 1px solid #ccc;
    border-radius: 5px;
}

button {
    background-color: #333;
    color: white;
    border: none;
    cursor: pointer;
}

button:hover {
    background-color: #555;
}


table {
    width: 100%;
    margin-top: 20px;
    border-collapse: collapse;
}

table, th, td {
    border: 1px solid #ddd;
}

th, td {
    padding: 12px;
    text-align: left;
}

th {
    background-color: #212121;
    color: white;
}

td {
    background-color: #555;
}

td form {
    display: inline-block;
    margin-right: 10px;
}

td button {
    padding: 5px 10px;
    background-color: #ccc;
    color: white;
    border: none;
    cursor: pointer;
    border-radius: 5px;
}

td button:hover {
    background-color: #45a049;
}

@media (max-width: 768px) {
    .navigation {
        flex-direction: column;
        align-items: center;
    }

    .container {
        padding: 10px;
    }

    input[type="text"], input[type="file"], button {
        width: 100%;
    }

    table {
        font-size: 14px;
    }
}
 </style>
</body>
</html>