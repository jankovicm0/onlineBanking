<?php
session_start();

require_once '../controllers/baza.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $lozinka = $_POST['lozinka'];

    $upit = "SELECT id, email, lozinka FROM korisnici WHERE email = ?";
    
    if ($stmt = $conn->prepare($upit)) {
        $stmt->bind_param('s', $email);
    
        $stmt->execute();

        $stmt->bind_result($korisnikId, $korisnikEmail, $hashedPassword);

        if ($stmt->fetch()) {
            if (password_verify($lozinka, $hashedPassword)) {
                $_SESSION['user_id'] = $korisnikId;
                $_SESSION['user_email'] = $korisnikEmail;

                header('Location: ../models/dashboard.php');
                exit();
            }
        }

        $stmt->close();
    }

    $error = 'Pogrešni podaci za prijavu!';
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../public/css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;600&display=swap" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="form-box">
            <h1>Login</h1>
        <form method="post" action="login.php">
        <label for="email">E-mail:</label><br>
        <input type="text" id="email" name="email" required><br>

        <label for="lozinka">Lozinka:</label><br>
        <input type="password" id="lozinka" name="lozinka" required><br>

        <div class="btn-field">
        <button type="submit">Prijavi se</button>
        </div>
        <p>Zaboravljena sifra? <a href="#">Klikni ovde!</a></p>
    </form>
</body>
</html>