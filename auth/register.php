<?php
/**
 * REDIRECTION - SYSTÈME D'AUTHENTIFICATION MIGRÉ
 * 
 * Ce fichier a été remplacé par un système cliente localStorage
 * Les nouvelles pages d'authentification sont en HTML/CSS/JavaScript
 */

// Redirection vers la page d'inscription HTML
header('Location: ../register.html');
exit;
?>
    'email' => '',
    'full_name' => '',
    'address' => '',
    'phone' => '',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $values['email'] = trim((string) ($_POST['email'] ?? ''));
    $password = (string) ($_POST['password'] ?? '');
    $password_confirm = (string) ($_POST['password_confirm'] ?? '');
    $values['full_name'] = trim((string) ($_POST['full_name'] ?? ''));
    $values['address'] = trim((string) ($_POST['address'] ?? ''));
    $values['phone'] = trim((string) ($_POST['phone'] ?? ''));

    if (!filter_var($values['email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Email invalide.';
    }

    if (strlen($password) < 8) {
        $errors[] = 'Mot de passe trop court (8 caracteres minimum).';
    }

    if ($password !== $password_confirm) {
        $errors[] = 'Les mots de passe ne correspondent pas.';
    }

    if (!$errors) {
        $stmt = $pdo->prepare('SELECT id FROM users WHERE email = ?');
        $stmt->execute([$values['email']]);
        if ($stmt->fetch()) {
            $errors[] = 'Email deja utilise.';
        } else {
            $stmt = $pdo->prepare('INSERT INTO users (email, password_hash, role, full_name, address, phone) VALUES (?, ?, ?, ?, ?, ?)');
            $stmt->execute([
                $values['email'],
                password_hash($password, PASSWORD_DEFAULT),
                'client',
                $values['full_name'],
                $values['address'],
                $values['phone'],
            ]);

            $user_id = (int) $pdo->lastInsertId();
            session_regenerate_id(true);
            $_SESSION['user'] = [
                'id' => $user_id,
                'email' => $values['email'],
                'role' => 'client',
            ];

            redirect_to(BASE_PATH . '/auth/account.php');
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ShadowWear | Inscription</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="bg-black text-white min-h-screen flex items-center justify-center px-6">
    <div class="w-full max-w-xl border border-white/10 p-8 bg-black/70">
        <h1 class="text-2xl font-bold uppercase tracking-widest mb-6">Inscription</h1>

        <?php if ($errors): ?>
            <div class="mb-4 border border-red-500/40 bg-red-500/10 p-3 text-sm">
                <?php foreach ($errors as $error): ?>
                    <p><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form method="post" class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="md:col-span-2">
                <label class="text-xs uppercase tracking-widest text-gray-400">Email</label>
                <input type="email" name="email" required value="<?php echo htmlspecialchars($values['email'], ENT_QUOTES, 'UTF-8'); ?>"
                    class="mt-2 w-full bg-black border border-white/20 px-3 py-2 text-sm">
            </div>
            <div>
                <label class="text-xs uppercase tracking-widest text-gray-400">Mot de passe</label>
                <input type="password" name="password" required
                    class="mt-2 w-full bg-black border border-white/20 px-3 py-2 text-sm">
            </div>
            <div>
                <label class="text-xs uppercase tracking-widest text-gray-400">Confirmer</label>
                <input type="password" name="password_confirm" required
                    class="mt-2 w-full bg-black border border-white/20 px-3 py-2 text-sm">
            </div>
            <div>
                <label class="text-xs uppercase tracking-widest text-gray-400">Nom complet</label>
                <input type="text" name="full_name" value="<?php echo htmlspecialchars($values['full_name'], ENT_QUOTES, 'UTF-8'); ?>"
                    class="mt-2 w-full bg-black border border-white/20 px-3 py-2 text-sm">
            </div>
            <div>
                <label class="text-xs uppercase tracking-widest text-gray-400">Telephone</label>
                <input type="text" name="phone" value="<?php echo htmlspecialchars($values['phone'], ENT_QUOTES, 'UTF-8'); ?>"
                    class="mt-2 w-full bg-black border border-white/20 px-3 py-2 text-sm">
            </div>
            <div class="md:col-span-2">
                <label class="text-xs uppercase tracking-widest text-gray-400">Adresse</label>
                <input type="text" name="address" value="<?php echo htmlspecialchars($values['address'], ENT_QUOTES, 'UTF-8'); ?>"
                    class="mt-2 w-full bg-black border border-white/20 px-3 py-2 text-sm">
            </div>
            <div class="md:col-span-2">
                <button type="submit" class="w-full bg-white text-black py-2 text-xs font-bold uppercase tracking-widest">Creer mon compte</button>
            </div>
        </form>

        <div class="mt-6 text-xs text-gray-400">
            <a href="<?php echo BASE_PATH; ?>/auth/login.php" class="hover:text-white">Deja un compte ? Se connecter</a>
        </div>
    </div>
</body>
</html>
