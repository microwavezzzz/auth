<?php

session_start();

if (isset($_SESSION['username'])) {
    return header('Location: ../buku');
}

require_once("../koneksi.php");

// Proses login
$error = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if ($username && $password) {
        $stmt = $koneksi->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($user = $result->fetch_assoc()) {
            if (password_verify($password, $user['password'])) {
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];
                header("Location: ../buku");
                exit;
            } else {
                $error = "Password salah.";
            }
        } else {
            $error = "Username tidak ditemukan.";
        }
    } else {
        $error = "Harap isi semua field.";
    }
}
?>

<!DOCTYPE html>
<html lang="en" data-theme="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/daisyui@5" rel="stylesheet" type="text/css" />
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@5/themes.css" rel="stylesheet" type="text/css" />
    <title>Perpustakaan | Login</title>
</head>

<body>
    <div class="flex items-center justify-center min-h-screen">
        <form method="POST">
            <fieldset class="fieldset bg-base-200 border-base-300 rounded-box w-md border p-4">
                <h1 class="text-3xl font-bold text-center mb-4">Login</h1>

                <?php if ($error): ?>
                    <div class="alert alert-error text-white mb-4">
                        <?= htmlspecialchars($error) ?>
                    </div>
                <?php endif; ?>

                <label class="label text-lg">Username</label>
                <input name="username" type="text" class="input w-full mb-4" placeholder="Username" required />

                <label class="label text-lg">Password</label>
                <input name="password" type="password" class="input w-full" placeholder="Password" required />

                <button type="submit" class="btn btn-primary mt-4 w-full">Login</button>
            </fieldset>
        </form>
    </div>
</body>

</html>