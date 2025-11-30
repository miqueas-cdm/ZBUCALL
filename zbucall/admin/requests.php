<?php
require_once '../config/session.php';
requireAdmin();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Solicitações - Admin</title>
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
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Gerenciar Solicitações</h1>
                <p class="text-gray-600 dark:text-gray-400">Analise e responda às solicitações dos associados</p>
            </div>
            
            <!-- Requests List -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
                <table class="w-full text-left text-sm">
                    <thead class="bg-gray-50 dark:bg-gray-700/50 text-gray-500 dark:text-gray-400">
                        <tr>
                            <th class="px-6 py-4 font-medium">Solicitante</th>
                            <th class="px-6 py-4 font-medium">Tipo</th>
                            <th class="px-6 py-4 font-medium">Assunto</th>
                            <th class="px-6 py-4 font-medium">Data</th>
                            <th class="px-6 py-4 font-medium">Status</th>
                            <th class="px-6 py-4 font-medium text-right">Ações</th>
                        </tr>
                    </thead>
                    <tbody id="requests-list" class="divide-y divide-gray-200 dark:divide-gray-700">
                        <!-- Loaded via JS -->
                    </tbody>
                </table>
            </div>
        </main>
    </div>
    
    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        lucide.createIcons();

        // Load Requests
        async function loadRequests() {
            // Mock data
            const requests = [
                { id: 1, user: 'João Silva', type: 'Férias', subject: 'Férias Janeiro', created_at: '2025-11-28', status: 'pending' },
                { id: 2, user: 'Maria Oliveira', type: 'Cadastro', subject: 'Atualização Endereço', created_at: '2025-11-27', status: 'approved' }
            ];
            
            const tbody = document.getElementById('requests-list');
            tbody.innerHTML = requests.map(req => `
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                    <td class="px-6 py-4 text-gray-900 dark:text-white font-medium">${req.user}</td>
                    <td class="px-6 py-4 text-gray-500">${req.type}</td>
                    <td class="px-6 py-4 text-gray-500">${req.subject}</td>
                    <td class="px-6 py-4 text-gray-500">${req.created_at}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 rounded-full text-xs font-medium ${req.status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800'}">
                            ${req.status === 'pending' ? 'Pendente' : 'Aprovado'}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <button class="text-green-600 hover:text-green-800 mr-2" title="Aprovar"><i data-lucide="check" class="w-4 h-4"></i></button>
                        <button class="text-red-600 hover:text-red-800" title="Rejeitar"><i data-lucide="x" class="w-4 h-4"></i></button>
                    </td>
                </tr>
            `).join('');
            lucide.createIcons();
        }

        loadRequests();
    </script>
</body>
</html>
