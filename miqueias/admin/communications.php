<?php
require_once '../config/session.php';
requireAdmin();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Comunicados - Admin</title>
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
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Gerenciar Comunicados</h1>
                    <p class="text-gray-600 dark:text-gray-400">Crie e gerencie notícias e avisos para os associados</p>
                </div>
                <button onclick="openModal('new-comm-modal')" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors flex items-center gap-2">
                    <i data-lucide="plus" class="w-5 h-5"></i>
                    Novo Comunicado
                </button>
            </div>
            
            <!-- Communications List -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
                <table class="w-full text-left text-sm">
                    <thead class="bg-gray-50 dark:bg-gray-700/50 text-gray-500 dark:text-gray-400">
                        <tr>
                            <th class="px-6 py-4 font-medium">Título</th>
                            <th class="px-6 py-4 font-medium">Categoria</th>
                            <th class="px-6 py-4 font-medium">Prioridade</th>
                            <th class="px-6 py-4 font-medium">Data</th>
                            <th class="px-6 py-4 font-medium">Status</th>
                            <th class="px-6 py-4 font-medium text-right">Ações</th>
                        </tr>
                    </thead>
                    <tbody id="communications-list" class="divide-y divide-gray-200 dark:divide-gray-700">
                        <!-- Loaded via JS -->
                    </tbody>
                </table>
            </div>
        </main>
    </div>

    <!-- New Communication Modal -->
    <div id="new-comm-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl w-full max-w-2xl">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Novo Comunicado</h3>
                <button onclick="closeModal('new-comm-modal')" class="text-gray-500 hover:text-gray-700 dark:hover:text-gray-300">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>
            <form id="new-comm-form" class="p-6 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Título</label>
                    <input type="text" name="title" required class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Conteúdo</label>
                    <textarea name="content" rows="4" required class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white"></textarea>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Categoria</label>
                        <select name="category" class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                            <option value="news">Notícia</option>
                            <option value="notice">Aviso</option>
                            <option value="campaign">Campanha</option>
                            <option value="announcement">Anúncio</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Prioridade</label>
                        <select name="priority" class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                            <option value="normal">Normal</option>
                            <option value="high">Alta</option>
                            <option value="urgent">Urgente</option>
                        </select>
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Imagem de Capa</label>
                    <input type="file" name="image" accept="image/*" class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                </div>

                <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
                    <div class="flex items-center gap-2 mb-4">
                        <input type="checkbox" id="has-survey" name="has_survey" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <label for="has-survey" class="text-sm font-medium text-gray-700 dark:text-gray-300">Adicionar Pesquisa de Satisfação</label>
                    </div>
                    
                    <div id="survey-fields" class="hidden space-y-4 pl-6 border-l-2 border-gray-200 dark:border-gray-700">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Pergunta da Pesquisa</label>
                            <input type="text" name="survey_question" class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white" placeholder="Ex: O que achou desta novidade?">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Opções (separadas por vírgula)</label>
                            <textarea name="survey_options" rows="2" class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white" placeholder="Ex: Ótimo, Bom, Regular, Ruim"></textarea>
                        </div>
                    </div>
                </div>
                <div class="flex justify-end gap-3 pt-4">
                    <button type="button" onclick="closeModal('new-comm-modal')" class="px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700">Cancelar</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Publicar</button>
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

        // Load Communications
        async function loadCommunications() {
            // Mock data for now, replace with API call
            const comms = [
                { id: 1, title: 'Bem-vindo ao Portal', category: 'announcement', priority: 'high', created_at: '2025-11-29', status: 'published' },
                { id: 2, title: 'Campanha de Vacinação', category: 'campaign', priority: 'normal', created_at: '2025-11-28', status: 'published' }
            ];
            
            const tbody = document.getElementById('communications-list');
            tbody.innerHTML = comms.map(comm => `
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                    <td class="px-6 py-4 text-gray-900 dark:text-white font-medium">${comm.title}</td>
                    <td class="px-6 py-4 text-gray-500 capitalize">${comm.category}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 rounded-full text-xs font-medium ${comm.priority === 'high' ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800'}">${comm.priority}</span>
                    </td>
                    <td class="px-6 py-4 text-gray-500">${comm.created_at}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">${comm.status}</span>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <button class="text-blue-600 hover:text-blue-800 mr-2"><i data-lucide="edit" class="w-4 h-4"></i></button>
                        <button class="text-red-600 hover:text-red-800"><i data-lucide="trash-2" class="w-4 h-4"></i></button>
                    </td>
                </tr>
            `).join('');
            lucide.createIcons();
        }

        loadCommunications();

        // Survey Toggle
        const hasSurveyCheckbox = document.getElementById('has-survey');
        const surveyFields = document.getElementById('survey-fields');
        
        hasSurveyCheckbox.addEventListener('change', (e) => {
            if (e.target.checked) {
                surveyFields.classList.remove('hidden');
            } else {
                surveyFields.classList.add('hidden');
            }
        });

        // Form Submission
        document.getElementById('new-comm-form').addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const formData = new FormData(e.target);
            formData.append('action', 'create');
            
            try {
                const response = await fetch('../api/admin_communications.php', {
                    method: 'POST',
                    body: formData
                });
                
                const data = await response.json();
                
                if (data.success) {
                    alert('Comunicado criado com sucesso!');
                    closeModal('new-comm-modal');
                    e.target.reset();
                    surveyFields.classList.add('hidden');
                    loadCommunications(); // Reload list
                } else {
                    alert(data.message || 'Erro ao criar comunicado');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Erro ao conectar com o servidor');
            }
        });
    </script>
</body>
</html>
