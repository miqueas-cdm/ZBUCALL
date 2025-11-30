<?php
require_once '../config/session.php';
requireAdmin();

// Mock server stats
$cpuUsage = rand(10, 40);
$ramUsage = rand(30, 60);
$diskUsage = 45;
$uptime = "15 dias, 4 horas";
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monitoramento - Admin</title>
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
</head>
<body class="bg-gray-50 dark:bg-gray-900">
    
    <?php include 'components/sidebar.php'; ?>
    
    <div class="lg:ml-64">
        <?php include 'components/header.php'; ?>
        
        <main class="p-6 mt-16">
            <div class="mb-6">
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Monitoramento do Servidor</h1>
                <p class="text-gray-600 dark:text-gray-400">Status em tempo real da infraestrutura</p>
            </div>
            
            <!-- Server Stats -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-gray-500 dark:text-gray-400 font-medium">Uso de CPU</h3>
                        <i data-lucide="cpu" class="w-6 h-6 text-blue-500"></i>
                    </div>
                    <div class="text-3xl font-bold text-gray-900 dark:text-white mb-2"><?= $cpuUsage ?>%</div>
                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2.5">
                        <div class="bg-blue-600 h-2.5 rounded-full" style="width: <?= $cpuUsage ?>%"></div>
                    </div>
                </div>
                
                <div class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-gray-500 dark:text-gray-400 font-medium">Memória RAM</h3>
                        <i data-lucide="hard-drive" class="w-6 h-6 text-purple-500"></i>
                    </div>
                    <div class="text-3xl font-bold text-gray-900 dark:text-white mb-2"><?= $ramUsage ?>%</div>
                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2.5">
                        <div class="bg-purple-600 h-2.5 rounded-full" style="width: <?= $ramUsage ?>%"></div>
                    </div>
                </div>
                
                <div class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-gray-500 dark:text-gray-400 font-medium">Disco</h3>
                        <i data-lucide="database" class="w-6 h-6 text-green-500"></i>
                    </div>
                    <div class="text-3xl font-bold text-gray-900 dark:text-white mb-2"><?= $diskUsage ?>%</div>
                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2.5">
                        <div class="bg-green-600 h-2.5 rounded-full" style="width: <?= $diskUsage ?>%"></div>
                    </div>
                </div>
            </div>
            
            <!-- System Info -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Informações do Sistema</h3>
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Sistema Operacional</p>
                        <p class="font-medium text-gray-900 dark:text-white"><?= php_uname('s') . ' ' . php_uname('r') ?></p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Versão do PHP</p>
                        <p class="font-medium text-gray-900 dark:text-white"><?= phpversion() ?></p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Servidor Web</p>
                        <p class="font-medium text-gray-900 dark:text-white"><?= $_SERVER['SERVER_SOFTWARE'] ?></p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Tempo de Atividade</p>
                        <p class="font-medium text-gray-900 dark:text-white"><?= $uptime ?></p>
                    </div>
                </div>
            </div>
        </main>
    </div>
    
    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        lucide.createIcons();
    </script>
</body>
</html>
