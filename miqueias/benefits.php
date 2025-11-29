<?php
require_once 'config/session.php';
require_once 'components/breadcrumbs.php';
requireAuth();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Benefícios - Portal do Colaborador</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="assets/css/styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="bg-gray-50 dark:bg-gray-900">
    
    <?php include 'components/sidebar.php'; ?>
    
    <div class="lg:ml-64">
        <?php include 'components/header.php'; ?>
        
        <main class="p-6">
            <?php renderBreadcrumbs([['label' => 'Benefícios']]); ?>
            
            <div class="mb-6">
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Meus Benefícios</h1>
                <p class="text-gray-600 dark:text-gray-400">Consulte todos os seus benefícios ativos</p>
            </div>
            
            <div id="benefits-container" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Loading skeleton -->
                <div class="skeleton h-48"></div>
                <div class="skeleton h-48"></div>
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
                    const benefits = data.data.benefits || [];
                    const container = document.getElementById('benefits-container');
                    
                    if (benefits.length === 0) {
                        container.innerHTML = '<p class="col-span-full text-center text-gray-500 py-8">Nenhum benefício cadastrado</p>';
                        return;
                    }
                    
                    container.innerHTML = benefits.map((benefit, index) => {
                        const icon = benefitIcons[benefit.benefit_type] || 'gift';
                        const color = benefitColors[benefit.benefit_type] || 'blue';
                        const hasBalance = benefit.balance > 0;
                        
                        return `
                            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 hover:shadow-lg transition-shadow fade-in" style="animation-delay: ${index * 0.1}s">
                                <div class="flex items-start justify-between mb-4">
                                    <div class="bg-${color}-100 dark:bg-${color}-900/30 p-3 rounded-lg">
                                        <i data-lucide="${icon}" class="w-6 h-6 text-${color}-600 dark:text-${color}-400"></i>
                                    </div>
                                    <span class="badge badge-${benefit.status === 'active' ? 'success' : 'error'}">
                                        ${benefit.status === 'active' ? 'Ativo' : 'Inativo'}
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
                                    ${hasBalance ? `
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
                                    ${benefit.dependents > 0 ? `
                                        <div class="flex justify-between">
                                            <span class="text-gray-600 dark:text-gray-400">Dependentes</span>
                                            <span class="font-medium text-gray-900 dark:text-white">${benefit.dependents}</span>
                                        </div>
                                    ` : ''}
                                </div>
                            </div>
                        `;
                    }).join('');
                    
                    lucide.createIcons();
                }
            } catch (error) {
                console.error('Error loading benefits:', error);
                document.getElementById('benefits-container').innerHTML = 
                    '<p class="col-span-full text-center text-red-500 py-8">Erro ao carregar benefícios</p>';
            }
        }
        
        document.addEventListener('DOMContentLoaded', loadBenefits);
    </script>
</body>
</html>
