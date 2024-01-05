<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transfer Novca</title>
    <link rel="stylesheet" href="../public/css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;600&display=swap" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="form-box">
    <h1>Transfer Novca</h1>

    <form method="post" action="obrada_transfera.php">
        <label for="iznos">Iznos:</label><br>
        <input type="number" id="iznos" name="iznos" required><br>

        <label for="racun_primaoca">Broj Računa Primaoca:</label><br>
        <input type="text" id="racun_primaoca" name="racun_primaoca" required><br>

        <label for="svrha_uplate">Svrha uplate:</label><br>
        <input type="text" id="svrha_uplate" name="svrha_uplate" required><br>
        <div class="btn-field">
        <button type="submit">Izvrši Transfer</button>
        </div>
    </form>
    </div>
    </div>
</body>
</html>