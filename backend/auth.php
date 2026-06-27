<?php
// Start the session to store user data
session_start();

// Import the database connection
require_once 'db.php';

// Check if the request method is GET or POST
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Check the current session status
    if (isset($_SESSION['user_id'])) {
        // User is logged in, return the user data
        $userData = array(
            'user_id' => $_SESSION['user_id'],
            'username' => $_SESSION['username'],
            'logged_in' => true
        );
        echo json_encode($userData);
    } else {
        // User is not logged in, return a message
        echo json_encode(array('logged_in' => false));
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check the action type (login or register)
    if (isset($_POST['action'])) {
        $action = $_POST['action'];

        // Check if the action is login
        if ($action === 'login') {
            // Check if the username and password fields are set
            if (isset($_POST['username']) && isset($_POST['password'])) {
                // Securely check the input fields
                $username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
                $password = $_POST['password'];

                // Prepare a statement to select the user data
                $stmt = $conn->prepare('SELECT user_id, username, password FROM users WHERE username = ?');
                $stmt->bind_param('s', $username);
                $stmt->execute();
                $result = $stmt->get_result();

                // Check if the user exists
                if ($result->num_rows > 0) {
                    // Fetch the user data
                    $userData = $result->fetch_assoc();

                    // Verify the password using password_verify()
                    if (password_verify($password, $userData['password'])) {
                        // Password is correct, store the user data in the session
                        $_SESSION['user_id'] = $userData['user_id'];
                        $_SESSION['username'] = $userData['username'];

                        // Return a success message
                        echo json_encode(array('logged_in' => true, 'message' => 'Login successful'));
                    } else {
                        // Password is incorrect, return an error message
                        echo json_encode(array('logged_in' => false, 'message' => 'Invalid password'));
                    }
                } else {
                    // User does not exist, return an error message
                    echo json_encode(array('logged_in' => false, 'message' => 'User not found'));
                }
            } else {
                // Username or password field is missing, return an error message
                echo json_encode(array('logged_in' => false, 'message' => 'Please fill in all fields'));
            }
        } elseif ($action === 'register') {
            // Check if the username, email, and password fields are set
            if (isset($_POST['username']) && isset($_POST['email']) && isset($_POST['password'])) {
                // Securely check the input fields
                $username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
                $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
                $password = $_POST['password'];

                // Check if the username and email are valid
                if (strlen($username) < 3 || strlen($username) > 20) {
                    echo json_encode(array('registered' => false, 'message' => 'Username must be between 3 and 20 characters'));
                } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    echo json_encode(array('registered' => false, 'message' => 'Invalid email address'));
                } else {
                    // Prepare a statement to check if the username or email already exists
                    $stmt = $conn->prepare('SELECT user_id FROM users WHERE username = ? OR email = ?');
                    $stmt->bind_param('ss', $username, $email);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    // Check if the username or email already exists
                    if ($result->num_rows > 0) {
                        // Username or email already exists, return an error message
                        echo json_encode(array('registered' => false, 'message' => 'Username or email already taken'));
                    } else {
                        // Hash the password using password_hash()
                        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                        // Prepare a statement to insert the new user data
                        $stmt = $conn->prepare('INSERT INTO users (username, email, password) VALUES (?, ?, ?)');
                        $stmt->bind_param('sss', $username, $email, $hashedPassword);
                        $stmt->execute();

                        // Check if the user was inserted successfully
                        if ($stmt->affected_rows > 0) {
                            // User was inserted successfully, return a success message
                            echo json_encode(array('registered' => true, 'message' => 'Registration successful'));
                        } else {
                            // User was not inserted successfully, return an error message
                            echo json_encode(array('registered' => false, 'message' => 'Registration failed'));
                        }
                    }
                }
            } else {
                // Username, email, or password field is missing, return an error message
                echo json_encode(array('registered' => false, 'message' => 'Please fill in all fields'));
            }
        } elseif ($action === 'logout') {
            // Unset the user data from the session
            unset($_SESSION['user_id']);
            unset($_SESSION['username']);

            // Destroy the session
            session_destroy();

            // Return a success message
            echo json_encode(array('logged_out' => true, 'message' => 'Logout successful'));
        }
    }
}