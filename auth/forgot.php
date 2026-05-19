<?php

declare(strict_types=1);

require __DIR__ . '/../config/db.php';
require __DIR__ . '/../config/auth.php';

$message = '';
$link = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim((string) ($_POST['email'] ?? ''));

    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $stmt = $pdo->prepare('SELECT id FROM users WHERE email = ?');
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user) {
            $token = bin2hex(random_bytes(32));
            $token_hash = hash('sha256', $token);
            $expires_at = (new DateTime('+1 hour'))->format('Y-m-d H:i:s');

            $stmt = $pdo->prepare('INSERT INTO password_resets (user_id, token_hash, expires_at) VALUES (?, ?, ?)');
            $stmt->execute([(int) $user['id'], $token_hash, $expires_at]);

            $link = BASE_PATH . '/auth/reset.php?token=' . $token;
            $message = 'Lien de reinitialisation genere (mode dev).';
        } else {
            $message = 'Si ce compte existe, un lien sera envoye.';
        }
    } else {
        $message = 'Email invalide.';
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ShadowWear | Mot de passe oublie</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="bg-black text-white min-h-screen flex items-center justify-center px-6">
    <div class="w-full max-w-md border border-white/10 p-8 bg-black/70">
        <h1 class="text-2xl font-bold uppercase tracking-widest mb-6">Mot de passe oublie</h1>

        <?php if ($message): ?>
            <div class="mb-4 border border-white/10 bg-white/5 p-3 text-sm">
                <?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?>
                <?php if ($link): ?>
                    <div class="mt-2 break-words">
                        <a href="<?php echo htmlspecialchars($link, ENT_QUOTES, 'UTF-8'); ?>" class="text-white underline"><?php echo htmlspecialchars($link, ENT_QUOTES, 'UTF-8'); ?></a>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <form method="post" class="space-y-4">
            <div>
                <label class="text-xs uppercase tracking-widest text-gray-400">Email</label>
                <input type="email" name="email" required
                    class="mt-2 w-full bg-black border border-white/20 px-3 py-2 text-sm">
            </div>
            <button type="submit" class="w-full bg-white text-black py-2 text-xs font-bold uppercase tracking-widest">Envoyer</button>
        </form>

        <div class="mt-6 text-xs text-gray-400">
            <a href="<?php echo BASE_PATH; ?>/auth/login.php" class="hover:text-white">Retour connexion</a>
        </div>
    </div>
</body>
</html>
