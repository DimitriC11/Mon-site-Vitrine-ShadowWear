<?php
/**
 * REDIRECTION - SYSTÈME D'AUTHENTIFICATION MIGRÉ
 * 
 * Ce fichier a été remplacé par un système cliente localStorage
 * Les nouvelles pages d'authentification sont en HTML/CSS/JavaScript
 */

// Redirection vers la page du compte HTML
header('Location: ../account.html');
exit;
?>
$errors = [];
$success = '';

$stmt = $pdo->prepare('SELECT email, full_name, address, phone FROM users WHERE id = ?');
$stmt->execute([$user_id]);
$profile = $stmt->fetch();

if (!$profile) {
    redirect_to(BASE_PATH . '/auth/login.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $profile['full_name'] = trim((string) ($_POST['full_name'] ?? ''));
    $profile['address'] = trim((string) ($_POST['address'] ?? ''));
    $profile['phone'] = trim((string) ($_POST['phone'] ?? ''));

    $stmt = $pdo->prepare('UPDATE users SET full_name = ?, address = ?, phone = ? WHERE id = ?');
    $stmt->execute([
        $profile['full_name'],
        $profile['address'],
        $profile['phone'],
        $user_id,
    ]);

    $success = 'Profil mis a jour.';
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ShadowWear | Mon compte</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="bg-black text-white min-h-screen px-6 py-10">
    <div class="max-w-xl mx-auto border border-white/10 p-8 bg-black/70">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold uppercase tracking-widest">Mon compte</h1>
            <a href="<?php echo BASE_PATH; ?>/auth/logout.php" class="text-xs uppercase tracking-widest text-gray-400 hover:text-white">Deconnexion</a>
        </div>

        <?php if ($success): ?>
            <div class="mb-4 border border-green-500/40 bg-green-500/10 p-3 text-sm">
                <?php echo htmlspecialchars($success, ENT_QUOTES, 'UTF-8'); ?>
            </div>
        <?php endif; ?>

        <form method="post" class="space-y-4">
            <div>
                <label class="text-xs uppercase tracking-widest text-gray-400">Email</label>
                <input type="text" value="<?php echo htmlspecialchars($profile['email'], ENT_QUOTES, 'UTF-8'); ?>" disabled
                    class="mt-2 w-full bg-black border border-white/20 px-3 py-2 text-sm text-gray-500">
            </div>
            <div>
                <label class="text-xs uppercase tracking-widest text-gray-400">Nom complet</label>
                <input type="text" name="full_name" value="<?php echo htmlspecialchars($profile['full_name'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                    class="mt-2 w-full bg-black border border-white/20 px-3 py-2 text-sm">
            </div>
            <div>
                <label class="text-xs uppercase tracking-widest text-gray-400">Telephone</label>
                <input type="text" name="phone" value="<?php echo htmlspecialchars($profile['phone'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                    class="mt-2 w-full bg-black border border-white/20 px-3 py-2 text-sm">
            </div>
            <div>
                <label class="text-xs uppercase tracking-widest text-gray-400">Adresse</label>
                <input type="text" name="address" value="<?php echo htmlspecialchars($profile['address'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                    class="mt-2 w-full bg-black border border-white/20 px-3 py-2 text-sm">
            </div>
            <button type="submit" class="w-full bg-white text-black py-2 text-xs font-bold uppercase tracking-widest">Mettre a jour</button>
        </form>
    </div>
</body>
</html>
