<?php

declare(strict_types=1);

require __DIR__ . '/../config/db.php';
require __DIR__ . '/../config/auth.php';

$token = (string) ($_GET['token'] ?? '');
$token_hash = $token ? hash('sha256', $token) : '';
$errors = [];
$success = '';

if (!$token_hash) {
    $errors[] = 'Token manquant.';
}

$reset_row = null;
if (!$errors) {
    $stmt = $pdo->prepare('SELECT id, user_id, expires_at FROM password_resets WHERE token_hash = ? ORDER BY id DESC LIMIT 1');
    $stmt->execute([$token_hash]);
    $reset_row = $stmt->fetch();

    if (!$reset_row || strtotime($reset_row['expires_at']) < time()) {
        $errors[] = 'Token invalide ou expire.';
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$errors && $reset_row) {
    $password = (string) ($_POST['password'] ?? '');
    $password_confirm = (string) ($_POST['password_confirm'] ?? '');

    if (strlen($password) < 8) {
        $errors[] = 'Mot de passe trop court (8 caracteres minimum).';
    }

    if ($password !== $password_confirm) {
        $errors[] = 'Les mots de passe ne correspondent pas.';
    }

    if (!$errors) {
        $stmt = $pdo->prepare('UPDATE users SET password_hash = ? WHERE id = ?');
        $stmt->execute([
            password_hash($password, PASSWORD_DEFAULT),
            (int) $reset_row['user_id'],
        ]);

        $stmt = $pdo->prepare('DELETE FROM password_resets WHERE id = ?');
        $stmt->execute([(int) $reset_row['id']]);

        $success = 'Mot de passe mis a jour.';
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ShadowWear | Nouveau mot de passe</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="bg-black text-white min-h-screen flex items-center justify-center px-6">
    <div class="w-full max-w-md border border-white/10 p-8 bg-black/70">
        <h1 class="text-2xl font-bold uppercase tracking-widest mb-6">Nouveau mot de passe</h1>

        <?php if ($errors): ?>
            <div class="mb-4 border border-red-500/40 bg-red-500/10 p-3 text-sm">
                <?php foreach ($errors as $error): ?>
                    <p><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="mb-4 border border-green-500/40 bg-green-500/10 p-3 text-sm">
                <?php echo htmlspecialchars($success, ENT_QUOTES, 'UTF-8'); ?>
            </div>
        <?php endif; ?>

        <?php if (!$success && !$errors): ?>
            <form method="post" class="space-y-4">
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
                <button type="submit" class="w-full bg-white text-black py-2 text-xs font-bold uppercase tracking-widest">Mettre a jour</button>
            </form>
        <?php endif; ?>

        <div class="mt-6 text-xs text-gray-400">
            <a href="<?php echo BASE_PATH; ?>auth/login.php" class="hover:text-white">Retour connexion</a>
        </div>
    </div>
</body>
</html>
