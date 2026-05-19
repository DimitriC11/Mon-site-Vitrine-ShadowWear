/**
 * SYSTÈME DE GESTION DES COMMANDES
 * Gère les commandes, les paniers et les statistiques
 */

const OrdersManager = {
    /**
     * Initialise les données de commandes
     */
    init() {
        if (!localStorage.getItem('orders')) {
            localStorage.setItem('orders', JSON.stringify([]));
        }
        if (!localStorage.getItem('carts')) {
            localStorage.setItem('carts', JSON.stringify({}));
        }
    },

    /**
     * Crée une nouvelle commande
     */
    createOrder(userId, email, items = [], totalAmount = 0, status = 'pending') {
        this.init();
        const orders = JSON.parse(localStorage.getItem('orders') || '[]');
        
        const order = {
            id: Date.now(),
            userId: userId,
            email: email,
            items: items, // [{productId, productName, quantity, price}]
            totalAmount: totalAmount,
            status: status, // pending, paid, shipped, delivered, canceled
            createdAt: new Date().toISOString(),
            updatedAt: new Date().toISOString(),
            notes: ''
        };

        orders.push(order);
        localStorage.setItem('orders', JSON.stringify(orders));
        return order;
    },

    /**
     * Récupère toutes les commandes
     */
    getAllOrders() {
        this.init();
        return JSON.parse(localStorage.getItem('orders') || '[]');
    },

    /**
     * Récupère les commandes d'un utilisateur
     */
    getUserOrders(userId) {
        return this.getAllOrders().filter(order => order.userId === userId);
    },

    /**
     * Récupère une commande par ID
     */
    getOrderById(orderId) {
        return this.getAllOrders().find(order => order.id === orderId);
    },

    /**
     * Met à jour le statut d'une commande
     */
    updateOrderStatus(orderId, newStatus) {
        const orders = this.getAllOrders();
        const order = orders.find(o => o.id === orderId);
        
        if (order) {
            order.status = newStatus;
            order.updatedAt = new Date().toISOString();
            localStorage.setItem('orders', JSON.stringify(orders));
            return order;
        }
        return null;
    },

    /**
     * Supprime une commande
     */
    deleteOrder(orderId) {
        const orders = this.getAllOrders();
        const filtered = orders.filter(o => o.id !== orderId);
        localStorage.setItem('orders', JSON.stringify(filtered));
    },

    /**
     * STATISTIQUES DES COMMANDES
     */

    /**
     * Obtient les statistiques globales
     */
    getGlobalStats() {
        const orders = this.getAllOrders();
        const users = JSON.parse(localStorage.getItem('users') || '[]');

        return {
            totalOrders: orders.length,
            totalUsers: users.length,
            totalRevenue: orders.reduce((sum, o) => sum + (o.totalAmount || 0), 0),
            averageOrderValue: orders.length > 0 ? orders.reduce((sum, o) => sum + (o.totalAmount || 0), 0) / orders.length : 0,
            
            ordersByStatus: {
                pending: orders.filter(o => o.status === 'pending').length,
                paid: orders.filter(o => o.status === 'paid').length,
                shipped: orders.filter(o => o.status === 'shipped').length,
                delivered: orders.filter(o => o.status === 'delivered').length,
                canceled: orders.filter(o => o.status === 'canceled').length
            }
        };
    },

    /**
     * Obtient les top clients
     */
    getTopClients(limit = 5) {
        const orders = this.getAllOrders();
        const clientStats = {};

        orders.forEach(order => {
            if (!clientStats[order.email]) {
                clientStats[order.email] = { email: order.email, userId: order.userId, totalSpent: 0, orderCount: 0 };
            }
            clientStats[order.email].totalSpent += order.totalAmount || 0;
            clientStats[order.email].orderCount += 1;
        });

        return Object.values(clientStats)
            .sort((a, b) => b.totalSpent - a.totalSpent)
            .slice(0, limit);
    },

    /**
     * Obtient les produits les plus vendus
     */
    getTopProducts(limit = 5) {
        const orders = this.getAllOrders();
        const productStats = {};

        orders.forEach(order => {
            (order.items || []).forEach(item => {
                const key = `${item.productId}_${item.productName}`;
                if (!productStats[key]) {
                    productStats[key] = {
                        productId: item.productId,
                        productName: item.productName,
                        totalSold: 0,
                        totalRevenue: 0
                    };
                }
                productStats[key].totalSold += item.quantity || 1;
                productStats[key].totalRevenue += (item.price || 0) * (item.quantity || 1);
            });
        });

        return Object.values(productStats)
            .sort((a, b) => b.totalSold - a.totalSold)
            .slice(0, limit);
    },

    /**
     * Obtient les statistiques par date
     */
    getOrdersByDate(days = 7) {
        const orders = this.getAllOrders();
        const stats = {};
        const now = new Date();

        // Initialiser les dates
        for (let i = days - 1; i >= 0; i--) {
            const date = new Date(now);
            date.setDate(date.getDate() - i);
            const dateStr = date.toISOString().split('T')[0];
            stats[dateStr] = { date: dateStr, count: 0, revenue: 0 };
        }

        // Compter les commandes par date
        orders.forEach(order => {
            const dateStr = order.createdAt.split('T')[0];
            if (stats[dateStr]) {
                stats[dateStr].count += 1;
                stats[dateStr].revenue += order.totalAmount || 0;
            }
        });

        return Object.values(stats);
    },

    /**
     * Obtient le résumé par statut
     */
    getStatusSummary() {
        const orders = this.getAllOrders();
        const statuses = {
            pending: { label: 'En attente', count: 0, revenue: 0, color: '#f59e0b' },
            paid: { label: 'Payée', count: 0, revenue: 0, color: '#10b981' },
            shipped: { label: 'Expédiée', count: 0, revenue: 0, color: '#3b82f6' },
            delivered: { label: 'Livrée', count: 0, revenue: 0, color: '#8b5cf6' },
            canceled: { label: 'Annulée', count: 0, revenue: 0, color: '#ef4444' }
        };

        orders.forEach(order => {
            if (statuses[order.status]) {
                statuses[order.status].count += 1;
                statuses[order.status].revenue += order.totalAmount || 0;
            }
        });

        return statuses;
    },

    /**
     * Ajoute au panier (temporaire avant commande)
     */
    addToCart(userId, product) {
        this.init();
        const carts = JSON.parse(localStorage.getItem('carts') || '{}');
        
        if (!carts[userId]) {
            carts[userId] = [];
        }

        const existingItem = carts[userId].find(item => item.productId === product.productId);
        
        if (existingItem) {
            existingItem.quantity = (existingItem.quantity || 1) + (product.quantity || 1);
        } else {
            carts[userId].push({
                productId: product.productId,
                productName: product.productName,
                price: product.price,
                quantity: product.quantity || 1
            });
        }

        localStorage.setItem('carts', JSON.stringify(carts));
        return carts[userId];
    },

    /**
     * Récupère le panier d'un utilisateur
     */
    getCart(userId) {
        this.init();
        const carts = JSON.parse(localStorage.getItem('carts') || '{}');
        return carts[userId] || [];
    },

    /**
     * Vide le panier d'un utilisateur
     */
    clearCart(userId) {
        this.init();
        const carts = JSON.parse(localStorage.getItem('carts') || '{}');
        delete carts[userId];
        localStorage.setItem('carts', JSON.stringify(carts));
    },

    /**
     * Convertit le panier en commande
     */
    cartToOrder(userId, email) {
        const cart = this.getCart(userId);
        if (cart.length === 0) return null;

        const totalAmount = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
        const order = this.createOrder(userId, email, cart, totalAmount, 'pending');
        
        this.clearCart(userId);
        return order;
    }
};

// Initialiser au chargement
OrdersManager.init();
