<?php
require_once '../config/session.php';
requireAdmin();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Transparência - Admin</title>
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
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Gerenciar Transparência</h1>
                    <p class="text-gray-600 dark:text-gray-400">Publique documentos e atualize dados financeiros</p>
                </div>
                <button onclick="openModal('upload-modal')" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors flex items-center gap-2">
                    <i data-lucide="upload" class="w-5 h-5"></i>
                    Novo Documento
                </button>
            </div>
            
            <!-- Financial Summary Edit -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 mb-8">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Resumo Financeiro (Mês Atual)</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Receitas</label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500">R$</span>
                            <input type="text" value="45.280,00" class="w-full pl-10 pr-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Despesas</label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500">R$</span>
                            <input type="text" value="32.150,00" class="w-full pl-10 pr-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Saldo</label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500">R$</span>
                            <input type="text" value="128.450,00" class="w-full pl-10 pr-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white">
                        </div>
                    </div>
                </div>
                <div class="mt-4 flex justify-end">
                    <button class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">Salvar Alterações</button>
                </div>
            </div>
            
            <!-- Documents List -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Documentos Publicados</h3>
                </div>
                <table class="w-full text-left text-sm">
                    <thead class="bg-gray-50 dark:bg-gray-700/50 text-gray-500 dark:text-gray-400">
                        <tr>
                            <th class="px-6 py-4 font-medium">Título</th>
                            <th class="px-6 py-4 font-medium">Categoria</th>
                            <th class="px-6 py-4 font-medium">Tamanho</th>
                            <th class="px-6 py-4 font-medium">Data</th>
                            <th class="px-6 py-4 font-medium text-right">Ações</th>
                        </tr>
                    </thead>
                    <tbody id="documents-list" class="divide-y divide-gray-200 dark:divide-gray-700">
                        <!-- Loaded via JS -->
                    </tbody>
                </table>
            </div>
        </main>
    </div>

    <!-- Upload Modal -->
    <div id="upload-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl w-full max-w-md">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Novo Documento</h3>
                <button onclick="closeModal('upload-modal')" class="text-gray-500 hover:text-gray-700 dark:hover:text-gray-300">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>
            <form id="upload-form" class="p-6 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Título</label>
                    <input type="text" name="title" required class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Categoria</label>
                    <select name="category" class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                        <option value="transparency">Transparência</option>
                        <option value="policy">Política</option>
                        <option value="manual">Manual</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Arquivo</label>
                    <input type="file" name="file" required class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                </div>
                <div class="flex justify-end gap-3 pt-4">
                    <button type="button" onclick="closeModal('upload-modal')" class="px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700">Cancelar</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Enviar</button>
                </div>
            </form>
        </div>
    </div>
    
    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        lucide.createIcons();

        function openModal(id) {
            document.getElementById(id).classList.remove('hidden');
        }

        function closeModal(id) {
            document.getElementById(id).classList.add('hidden');
        }

        // Load Documents
        async function loadDocuments() {
            // Mock data
            const docs = [
                { id: 1, title: 'Balancete Outubro/2025', category: 'transparency', size: '2.4 MB', created_at: '2025-11-15' },
                { id: 2, title: 'Ata da Assembleia Geral', category: 'transparency', size: '1.8 MB', created_at: '2025-11-10' }
            ];
            
            const tbody = document.getElementById('documents-list');
            tbody.innerHTML = docs.map(doc => `
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                    <td class="px-6 py-4 text-gray-900 dark:text-white font-medium">${doc.title}</td>
                    <td class="px-6 py-4 text-gray-500 capitalize">${doc.category}</td>
                    <td class="px-6 py-4 text-gray-500">${doc.size}</td>
                    <td class="px-6 py-4 text-gray-500">${doc.created_at}</td>
                    <td class="px-6 py-4 text-right">
                        <button class="text-red-600 hover:text-red-800"><i data-lucide="trash-2" class="w-4 h-4"></i></button>
                    </td>
                </tr>
            `).join('');
            lucide.createIcons();
        }

        loadDocuments();
    </script>
</body>
</html>
