<?php
/**
 * REDIRECTION - SYSTÈME D'AUTHENTIFICATION MIGRÉ
 * 
 * Ce fichier a été remplacé par un système cliente localStorage
 * Les nouvelles pages d'authentification sont en HTML/CSS/JavaScript
 */

// Redirection vers la page d'administration HTML
header('Location: ../admin.html');
exit;
?>

            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="border border-white/10 p-6">
                <h2 class="text-xs uppercase tracking-widest text-gray-400 mb-4">Top clients</h2>
                <?php if (!$stats['top_clients']): ?>
                    <p class="text-sm text-gray-500">Pas encore de donnees.</p>
                <?php else: ?>
                    <ul class="space-y-2 text-sm">
                        <?php foreach ($stats['top_clients'] as $client): ?>
                            <li class="flex justify-between">
                                <span><?php echo htmlspecialchars($client['email'], ENT_QUOTES, 'UTF-8'); ?></span>
                                <span><?php echo number_format((float) $client['total_spent'], 2, ',', ' '); ?> EUR</span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
            <div class="border border-white/10 p-6">
                <h2 class="text-xs uppercase tracking-widest text-gray-400 mb-4">Ventes par produit</h2>
                <?php if (!$stats['top_products']): ?>
                    <p class="text-sm text-gray-500">Pas encore de donnees.</p>
                <?php else: ?>
                    <ul class="space-y-2 text-sm">
                        <?php foreach ($stats['top_products'] as $product): ?>
                            <li class="flex justify-between">
                                <span><?php echo htmlspecialchars($product['name'], ENT_QUOTES, 'UTF-8'); ?></span>
                                <span><?php echo (int) $product['total_sold']; ?></span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
            <div class="border border-white/10 p-6 md:col-span-2">
                <h2 class="text-xs uppercase tracking-widest text-gray-400 mb-4">Livraisons par statut</h2>
                <?php if (!$stats['delivery_status']): ?>
                    <p class="text-sm text-gray-500">Pas encore de donnees.</p>
                <?php else: ?>
                    <div class="flex flex-wrap gap-4 text-sm">
                        <?php foreach ($stats['delivery_status'] as $delivery): ?>
                            <div class="border border-white/10 px-4 py-2">
                                <p class="uppercase text-gray-400 text-xs tracking-widest"><?php echo htmlspecialchars($delivery['status'], ENT_QUOTES, 'UTF-8'); ?></p>
                                <p class="text-xl font-bold mt-1"><?php echo (int) $delivery['total']; ?></p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
