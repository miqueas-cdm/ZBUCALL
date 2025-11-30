<?php
require_once '../config/session.php';
requireAdmin();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Portal do Associado</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    colors: {
                        primary: {
                            50: '#f0f9ff',
                            100: '#e0f2fe',
                            500: '#0ea5e9',
                            600: '#0284c7',
                            700: '#0369a1',
                            900: '#0c4a6e',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        .fade-in { animation: fadeIn 0.5s ease-out forwards; opacity: 0; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
    </style>
</head>
<body class="bg-gray-50 dark:bg-gray-900">
    
    <?php include 'components/sidebar.php'; ?>
    
    <div class="lg:ml-64">
        <?php include 'components/header.php'; ?>
        
        <main class="p-6 mt-16">
            <div class="mb-6">
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Painel Administrativo</h1>
                <p class="text-gray-600 dark:text-gray-400">Visão geral do sistema e atividades recentes</p>
            </div>
            
            <!-- Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow hover:shadow-lg transition-shadow fade-in" style="animation-delay: 0.1s">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 dark:text-gray-400 text-sm font-medium">Usuários Ativos</p>
                            <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">1,234</p>
                        </div>
                        <div class="bg-blue-100 dark:bg-blue-900/30 p-3 rounded-lg">
                            <i data-lucide="users" class="w-6 h-6 text-blue-600 dark:text-blue-400"></i>
                        </div>
                    </div>
                    <p class="text-xs text-green-600 mt-2 flex items-center gap-1">
                        <i data-lucide="trending-up" class="w-3 h-3"></i> +12% este mês
                    </p>
                </div>
                
                <div class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow hover:shadow-lg transition-shadow fade-in" style="animation-delay: 0.2s">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 dark:text-gray-400 text-sm font-medium">Solicitações Pendentes</p>
                            <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">28</p>
                        </div>
                        <div class="bg-yellow-100 dark:bg-yellow-900/30 p-3 rounded-lg">
                            <i data-lucide="clock" class="w-6 h-6 text-yellow-600 dark:text-yellow-400"></i>
                        </div>
                    </div>
                    <p class="text-xs text-gray-500 mt-2">5 urgentes</p>
                </div>
                
                <div class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow hover:shadow-lg transition-shadow fade-in" style="animation-delay: 0.3s">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 dark:text-gray-400 text-sm font-medium">Comunicados Ativos</p>
                            <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">8</p>
                        </div>
                        <div class="bg-green-100 dark:bg-green-900/30 p-3 rounded-lg">
                            <i data-lucide="megaphone" class="w-6 h-6 text-green-600 dark:text-green-400"></i>
                        </div>
                    </div>
                    <p class="text-xs text-gray-500 mt-2">2 novos esta semana</p>
                </div>
                
                <div class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow hover:shadow-lg transition-shadow fade-in" style="animation-delay: 0.4s">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 dark:text-gray-400 text-sm font-medium">Saúde do Sistema</p>
                            <p class="text-3xl font-bold text-green-600 dark:text-green-400 mt-2">98%</p>
                        </div>
                        <div class="bg-purple-100 dark:bg-purple-900/30 p-3 rounded-lg">
                            <i data-lucide="activity" class="w-6 h-6 text-purple-600 dark:text-purple-400"></i>
                        </div>
                    </div>
                    <p class="text-xs text-gray-500 mt-2">Todos os serviços online</p>
                </div>
            </div>
            
            <!-- Recent Activity & Quick Actions -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Recent Requests -->
                <div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-lg shadow p-6 fade-in" style="animation-delay: 0.5s">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Solicitações Recentes</h2>
                        <a href="requests.php" class="text-sm text-blue-600 hover:text-blue-700">Ver todas</a>
                    </div>
                    
                    <div class="space-y-4">
                        <!-- Mock Data -->
                        <div class="flex items-center justify-between p-4 border border-gray-100 dark:border-gray-700 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold">JS</div>
                                <div>
                                    <h4 class="font-medium text-gray-900 dark:text-white">João Silva</h4>
                                    <p class="text-sm text-gray-500">Solicitação de Férias</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Pendente</span>
                                <button class="text-gray-400 hover:text-blue-600"><i data-lucide="chevron-right" class="w-5 h-5"></i></button>
                            </div>
                        </div>
                        
                        <div class="flex items-center justify-between p-4 border border-gray-100 dark:border-gray-700 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-full bg-purple-100 flex items-center justify-center text-purple-600 font-bold">MO</div>
                                <div>
                                    <h4 class="font-medium text-gray-900 dark:text-white">Maria Oliveira</h4>
                                    <p class="text-sm text-gray-500">Atualização Cadastral</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Aprovado</span>
                                <button class="text-gray-400 hover:text-blue-600"><i data-lucide="chevron-right" class="w-5 h-5"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Quick Actions -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 fade-in" style="animation-delay: 0.6s">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">Ações Rápidas</h2>
                    
                    <div class="space-y-3">
                        <a href="communications.php?action=new" class="flex items-center gap-3 p-3 rounded-lg border border-gray-200 dark:border-gray-700 hover:bg-blue-50 dark:hover:bg-blue-900/20 hover:border-blue-200 transition-all group">
                            <div class="bg-blue-100 dark:bg-blue-900/30 p-2 rounded-lg group-hover:bg-blue-200 dark:group-hover:bg-blue-800/30 transition-colors">
                                <i data-lucide="plus" class="w-5 h-5 text-blue-600 dark:text-blue-400"></i>
                            </div>
                            <span class="font-medium text-gray-700 dark:text-gray-200 group-hover:text-blue-700 dark:group-hover:text-blue-300">Novo Comunicado</span>
                        </a>
                        
                        <a href="transparency.php?action=upload" class="flex items-center gap-3 p-3 rounded-lg border border-gray-200 dark:border-gray-700 hover:bg-green-50 dark:hover:bg-green-900/20 hover:border-green-200 transition-all group">
                            <div class="bg-green-100 dark:bg-green-900/30 p-2 rounded-lg group-hover:bg-green-200 dark:group-hover:bg-green-800/30 transition-colors">
                                <i data-lucide="upload" class="w-5 h-5 text-green-600 dark:text-green-400"></i>
                            </div>
                            <span class="font-medium text-gray-700 dark:text-gray-200 group-hover:text-green-700 dark:group-hover:text-green-300">Upload Documento</span>
                        </a>
                        
                        <a href="users.php" class="flex items-center gap-3 p-3 rounded-lg border border-gray-200 dark:border-gray-700 hover:bg-purple-50 dark:hover:bg-purple-900/20 hover:border-purple-200 transition-all group">
                            <div class="bg-purple-100 dark:bg-purple-900/30 p-2 rounded-lg group-hover:bg-purple-200 dark:group-hover:bg-purple-800/30 transition-colors">
                                <i data-lucide="user-plus" class="w-5 h-5 text-purple-600 dark:text-purple-400"></i>
                            </div>
                            <span class="font-medium text-gray-700 dark:text-gray-200 group-hover:text-purple-700 dark:group-hover:text-purple-300">Gerenciar Usuários</span>
                        </a>
                    </div>
                </div>
            </div>
        </main>
    </div>
    
    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        lucide.createIcons();
        
        // Theme toggle logic (simplified)
        const themeToggleBtn = document.getElementById('theme-toggle');
        const html = document.documentElement;
        
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            html.classList.add('dark');
        } else {
            html.classList.remove('dark');
        }
        
        themeToggleBtn.addEventListener('click', () => {
            html.classList.toggle('dark');
            localStorage.theme = html.classList.contains('dark') ? 'dark' : 'light';
        });
        
        // Mobile menu toggle
        const mobileMenuBtn = document.getElementById('mobile-menu-btn');
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebar-overlay');
        
        mobileMenuBtn.addEventListener('click', () => {
            sidebar.classList.remove('-translate-x-full');
            overlay.classList.remove('hidden');
        });
        
        overlay.addEventListener('click', () => {
            sidebar.classList.add('-translate-x-full');
            overlay.classList.add('hidden');
        });

        // Profile dropdown
        const profileBtn = document.getElementById('profile-btn');
        const profileDropdown = document.getElementById('profile-dropdown');
        
        profileBtn.addEventListener('click', () => {
            profileDropdown.classList.toggle('hidden');
        });
        
        document.addEventListener('click', (e) => {
            if (!profileBtn.contains(e.target) && !profileDropdown.contains(e.target)) {
                profileDropdown.classList.add('hidden');
            }
        });
    </script>
</body>
</html>
