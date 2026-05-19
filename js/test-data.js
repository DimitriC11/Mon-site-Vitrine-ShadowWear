/**
 * DONNÉES DE TEST
 * Crée des commandes d'exemple pour tester le système de statistiques
 */

function createTestOrders() {
    // Créer des utilisateurs de test si nécessaire
    let users = JSON.parse(localStorage.getItem('users') || '[]');
    
    const testUsers = [
        { id: 1, email: 'client1@example.com', name: 'Client 1' },
        { id: 2, email: 'client2@example.com', name: 'Client 2' },
        { id: 3, email: 'client3@example.com', name: 'Client 3' },
        { id: 4, email: 'client4@example.com', name: 'Client 4' },
        { id: 5, email: 'client5@example.com', name: 'Client 5' }
    ];

    // Données productsexemples
    const testProducts = [
        { id: 1, name: 'T-Shirt ShadowWear', price: 29.99 },
        { id: 2, name: 'Hoodie Noir', price: 59.99 },
        { id: 3, name: 'Pantalon Cargo', price: 79.99 },
        { id: 4, name: 'Casquette', price: 19.99 },
        { id: 5, name: 'Sweatshirt', price: 49.99 }
    ];

    // Génère les commandes
    const orders = [];
    const statuses = ['pending', 'paid', 'shipped', 'delivered', 'canceled'];

    // Créer 30 commandes avec des dates variées
    for (let i = 0; i < 30; i++) {
        const user = testUsers[Math.floor(Math.random() * testUsers.length)];
        const status = statuses[Math.floor(Math.random() * statuses.length)];
        
        // Articles aléatoires
        const itemCount = Math.floor(Math.random() * 3) + 1;
        const items = [];
        let totalAmount = 0;

        for (let j = 0; j < itemCount; j++) {
            const product = testProducts[Math.floor(Math.random() * testProducts.length)];
            const quantity = Math.floor(Math.random() * 3) + 1;
            const itemTotal = product.price * quantity;

            items.push({
                productId: product.id,
                productName: product.name,
                price: product.price,
                quantity: quantity
            });

            totalAmount += itemTotal;
        }

        // Date aléatoire dans les 30 derniers jours
        const daysAgo = Math.floor(Math.random() * 30);
        const createdAt = new Date();
        createdAt.setDate(createdAt.getDate() - daysAgo);

        orders.push({
            id: Date.now() + i,
            userId: user.id,
            email: user.email,
            items: items,
            totalAmount: totalAmount,
            status: status,
            createdAt: createdAt.toISOString(),
            updatedAt: createdAt.toISOString(),
            notes: `Commande test #${i + 1}`
        });
    }

    localStorage.setItem('orders', JSON.stringify(orders));
    console.log('✅ 30 commandes de test créées');
    console.log(orders);
}

// Exporte la fonction globalement
window.createTestOrders = createTestOrders;
