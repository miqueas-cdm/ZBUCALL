<?php
require_once 'config/session.php';
require_once 'components/breadcrumbs.php';
requireAuth();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <?php $pageTitle = 'Clube de Vantagens - Portal do Associado'; ?>
    <?php include 'components/head.php'; ?>
</head>
<body class="bg-gray-50 dark:bg-gray-900">
    
    <?php include 'components/sidebar.php'; ?>
    
    <div class="lg:ml-64">
        <?php include 'components/header.php'; ?>
        
        <main class="p-6">
            <?php renderBreadcrumbs([['label' => 'Clube de Vantagens']]); ?>
            
            <div class="mb-6">
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Clube de Vantagens</h1>
                <p class="text-gray-600 dark:text-gray-400">Consulte todas as suas vantagens ativas</p>
            </div>
            
            <div id="benefits-container" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Loading skeleton -->
                <div class="skeleton h-48"></div>
            </div>
        </main>
    </div>
    
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="assets/js/main.js"></script>
    <script>
        const benefitIcons = {
            transport: 'bus',
            meal: 'shopping-cart',
            food: 'utensils',
            health: 'heart',
            dental: 'smile',
            life: 'shield'
        };
        
        const benefitColors = {
            transport: 'blue',
            meal: 'green',
            food: 'orange',
            health: 'red',
            dental: 'cyan',
            life: 'purple'
        };
        
        async function loadBenefits() {
            try {
                const response = await fetch('api/benefits.php');
                const data = await response.json();
                
                if (data.success) {
                    const container = document.getElementById('benefits-container');
                    let benefits = data.data.benefits || [];
                    
                    // Mock data for Wellhub and other active benefits if API returns empty or for demo
                    if (benefits.length === 0 || true) { // Force add for demo
                        benefits.unshift(
                            {
                                benefit_type: 'health',
                                benefit_name: 'Wellhub (Gympass)',
                                description: 'Acesso a milhares de academias e apps de bem-estar.',
                                status: 'active',
                                value: 0,
                                balance: 0,
                                category: 'Saúde e Bem-estar',
                                dependents: 0
                            },
                            {
                                benefit_type: 'health',
                                benefit_name: 'Unimed Nacional',
                                description: 'Plano de saúde com cobertura nacional.',
                                status: 'inactive',
                                value: 0,
                                balance: 0,
                                category: 'Saúde',
                                dependents: 2
                            },
                            {
                                benefit_type: 'dental',
                                benefit_name: 'OdontoPrev',
                                description: 'Plano odontológico completo.',
                                status: 'inactive',
                                value: 0,
                                balance: 0,
                                category: 'Odontológico',
                                dependents: 2
                            }
                        );
                    }

                    container.innerHTML = benefits.map((benefit, index) => {
                        const icon = benefitIcons[benefit.benefit_type] || 'gift';
                        const color = benefitColors[benefit.benefit_type] || 'blue';
                        const isInactive = benefit.status !== 'active';
                        const cardOpacity = isInactive ? 'opacity-90' : '';
                        const cardBg = isInactive ? 'bg-gray-100 dark:bg-gray-700' : 'bg-card dark:bg-gray-800';
                        
                        // Custom icon for Wellhub if needed, or use heart
                        const displayIcon = benefit.benefit_name.includes('Wellhub') ? 'activity' : icon;
                        
                        return `
                            <div class="${cardBg} rounded-lg shadow p-6 hover:shadow-lg transition-shadow fade-in relative overflow-hidden" style="animation-delay: ${index * 0.1}s">
                                <div class="${cardOpacity}">
                                    <div class="flex items-start justify-between mb-4">
                                        <div class="bg-${color}-100 dark:bg-${color}-900/30 p-3 rounded-lg">
                                            <i data-lucide="${displayIcon}" class="w-6 h-6 text-${color}-600 dark:text-${color}-400"></i>
                                        </div>
                                        <span class="px-2.5 py-0.5 rounded-full text-xs font-medium ${benefit.status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}">
                                            ${benefit.status === 'active' ? 'Ativo' : 'Não Ativo'}
                                        </span>
                                    </div>
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">${benefit.benefit_name}</h3>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">${benefit.description || ''}</p>
                                    <div class="space-y-2 text-sm">
                                        ${benefit.value > 0 ? `
                                            <div class="flex justify-between">
                                                <span class="text-gray-600 dark:text-gray-400">Valor</span>
                                                <span class="font-semibold text-gray-900 dark:text-white">${formatCurrency(benefit.value)}</span>
                                            </div>
                                        ` : ''}
                                        ${benefit.balance > 0 ? `
                                            <div class="flex justify-between">
                                                <span class="text-gray-600 dark:text-gray-400">Saldo Disponível</span>
                                                <span class="font-semibold text-green-600 dark:text-green-400">${formatCurrency(benefit.balance)}</span>
                                            </div>
                                        ` : ''}
                                        ${benefit.category ? `
                                            <div class="flex justify-between">
                                                <span class="text-gray-600 dark:text-gray-400">Categoria</span>
                                                <span class="font-medium text-gray-900 dark:text-white">${benefit.category}</span>
                                            </div>
                                        ` : ''}
                                    </div>
                                </div>
                                
                                ${isInactive ? `
                                    <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-600">
                                        <a href="requests.php?type=benefit_activation&benefit=${encodeURIComponent(benefit.benefit_name)}" 
                                           class="flex items-center justify-center gap-2 w-full py-2 bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 rounded-lg hover:bg-blue-100 dark:hover:bg-blue-900/40 transition-colors font-medium text-sm">
                                            Solicitar Ativação
                                            <i data-lucide="arrow-right" class="w-4 h-4"></i>
                                        </a>
                                    </div>
                                ` : ''}
                            </div>
                        `;
                    }).join('');
                    
                    lucide.createIcons();
                }
            } catch (error) {
                console.error('Error loading benefits:', error);
                document.getElementById('benefits-container').innerHTML = 
                    '<p class="col-span-full text-center text-red-500 py-8">Erro ao carregar clube de vantagens</p>';
            }
        }
        
        document.addEventListener('DOMContentLoaded', loadBenefits);
    </script>
</body>
</html>
