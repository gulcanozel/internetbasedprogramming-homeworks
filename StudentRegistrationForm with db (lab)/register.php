<?php

// Validate and sanitize the form data
$full_name = $_POST['full_name'];
$email = $_POST['email'];
$gender = $_POST['gender'];

// Validate the form data (add more validation if required)
$errors = array();

if (empty($full_name)) {
    $errors[] = "Full Name is required.";
}

if (empty($email)) {
    $errors[] = "Email Address is required.";
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Invalid Email Address.";
}

if (empty($gender)) {
    $errors[] = "Gender is required.";
}

// If there are validation errors, display them
if (!empty($errors)) {
    foreach ($errors as $error) {
        echo "<p>Error: $error</p>";
    }
} else {
    // Connect to the MySQL database
    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "studentregistration";

    $conn = new mysqli($servername, $username, $password, $database);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Insert the student's information into the database
    $sql = "INSERT INTO students (full_name, email, gender) VALUES ('$full_name', '$email', '$gender')";

    if ($conn->query($sql) === TRUE) {
        echo "<p>Registration Successful!</p>";

        // Retrieve the list of registered students from the database
        $listQuery = "SELECT * FROM students";
        $result = $conn->query($listQuery);

        // Display the list of registered students
        if ($result->num_rows > 0) {
            echo "<h2>Registered Students:</h2>";
            while ($row = $result->fetch_assoc()) {
                echo "<p>Name: " . $row['full_name'] . "</p>";
                echo "<p>Email: " . $row['email'] . "</p>";
                echo "<p>Gender: " . $row['gender'] . "</p>";
                echo "<hr>";
            }
        } else {
            echo "<p>No students registered yet.</p>";
        }
    } else {
        echo "<p>Error: " . $conn->error . "</p>";
    }

    $conn->close();
}

?>

