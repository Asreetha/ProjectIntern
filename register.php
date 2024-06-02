<?php
$servername = "localhost";
$username = "root"; // Change to your database username
$password = "Asreetha@314"; // Change to your database password
$dbname = "movie_app";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $conn->real_escape_string($_POST['username']);
    $email = $conn->real_escape_string($_POST['email']);
    $password = $conn->real_escape_string($_POST['password']);
    $confirmPassword = $conn->real_escape_string($_POST['confirmPassword']);
    $phone = $conn->real_escape_string($_POST['phone']);
    $dob = $conn->real_escape_string($_POST['dob']);
    
    // Server-side validation
    $errors = [];
    
    if (!preg_match("/^[a-zA-Z0-9_]+$/", $username)) {
        $errors[] = "Username can only contain letters, numbers, and underscores.";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Please enter a valid email address.";
    }

    if (strlen($password) < 8) {
        $errors[] = "Password must be at least 8 characters long.";
    }

    if ($password !== $confirmPassword) {
        $errors[] = "Passwords do not match.";
    }

    if (!preg_match("/^[0-9]{10}$/", $phone)) {
        $errors[] = "Please enter a valid 10-digit phone number.";
    }

    $dobDate = new DateTime($dob);
    $today = new DateTime();
    if ($dobDate >= $today) {
        $errors[] = "Please enter a valid date of birth.";
    }

    if (empty($errors)) {
        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Insert data into the database
        $sql = "INSERT INTO users (username, email, password, phone, dob) VALUES ('$username', '$email', '$hashedPassword', '$phone', '$dob')";

        if ($conn->query($sql) === TRUE) {
            // Display success message
            echo "Registration successful. You can now <a href='login.html'>login</a>."; // You can customize this message as per your requirement
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } else {
        foreach ($errors as $error) {
            echo "<p>$error</p>";
        }
    }

    $conn->close();
}
?>
