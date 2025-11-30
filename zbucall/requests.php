<?php
require_once 'config/session.php';
require_once 'components/breadcrumbs.php';
requireAuth();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <?php $pageTitle = 'Solicitações - Portal do Associado'; ?>
    <?php include 'components/head.php'; ?>
</head>
<body class="bg-white dark:bg-gray-900">
    
    <?php include 'components/sidebar.php'; ?>
    
    <div class="lg:ml-64">
        <?php include 'components/header.php'; ?>
        
        <main class="p-6">
            <?php renderBreadcrumbs([['label' => 'Solicitações']]); ?>
            
            <div class="mb-6 flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Minhas Solicitações</h1>
                    <p class="text-gray-600 dark:text-gray-400">Envie e acompanhe suas solicitações</p>
                </div>
                <button onclick="showRequestModal()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors flex items-center gap-2">
                    <i data-lucide="plus" class="w-5 h-5"></i>
                    Nova Solicitação
                </button>
            </div>
            
            <!-- Status Tabs -->
            <div class="mb-6">
                <div class="border-b border-gray-200 dark:border-gray-700 overflow-x-auto">
                    <nav class="-mb-px flex gap-6 min-w-max">
                        <button onclick="filterStatus('all')" class="status-tab active border-b-2 border-blue-600 dark:border-blue-400 text-blue-600 dark:text-blue-400 py-3 px-1 font-medium text-sm whitespace-nowrap">
                            Todas
                        </button>
                        <button onclick="filterStatus('pending')" class="status-tab border-b-2 border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 py-3 px-1 font-medium text-sm whitespace-nowrap">
                            Pendentes
                        </button>
                        <button onclick="filterStatus('approved')" class="status-tab border-b-2 border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 py-3 px-1 font-medium text-sm whitespace-nowrap">
                            Aprovadas
                        </button>
                        <button onclick="filterStatus('rejected')" class="status-tab border-b-2 border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 py-3 px-1 font-medium text-sm whitespace-nowrap">
                            Rejeitadas
                        </button>
                    </nav>
                </div>
            </div>
            
            <div id="requests-container" class="space-y-4">
                <div class="skeleton h-32"></div>
                <div class="skeleton h-32"></div>
            </div>
        </main>
    </div>
    
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="assets/js/main.js"></script>
    <script src="assets/js/components.js"></script>
    <script>
        let allRequests = [];
        let currentStatus = 'all';
        
        async function loadRequests() {
            try {
                const response = await fetch('api/requests.php?action=list');
                const data = await response.json();
                
                if (data.success) {
                    allRequests = data.requests || [];
                    renderRequests();
                }
            } catch (error) {
                console.error('Error loading requests:', error);
            }
        }
        
        function renderRequests() {
            const filtered = currentStatus === 'all' 
                ? allRequests 
                : allRequests.filter(req => req.status === currentStatus);
            
            const container = document.getElementById('requests-container');
            
            if (filtered.length === 0) {
                container.innerHTML = '<p class="text-center text-gray-500 py-8">Nenhuma solicitação encontrada</p>';
                return;
            }
            
            const statusColors = {
                pending: 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-300',
                approved: 'bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300',
                rejected: 'bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300',
                cancelled: 'bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300'
            };
            
            const statusLabels = {
                pending: 'Pendente',
                approved: 'Aprovada',
                rejected: 'Rejeitada',
                cancelled: 'Cancelada'
            };
            
            const typeIcons = {
                vacation: 'calendar',
                loan: 'banknote',
                hr: 'users',
                cadastral: 'edit',
                declaration: 'file-text',
                other: 'help-circle'
            };
            
            container.innerHTML = filtered.map((req, index) => `
                <div class="bg-card dark:bg-gray-800 rounded-lg shadow p-6 fade-in" style="animation-delay: ${index * 0.1}s">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-center gap-3">
                            <div class="bg-blue-100 dark:bg-blue-900/30 p-2 rounded-lg">
                                <i data-lucide="${typeIcons[req.request_type] || 'file'}" class="w-5 h-5 text-blue-600 dark:text-blue-400"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">${req.title}</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400">${formatDate(req.created_at)}</p>
                            </div>
                        </div>
                        <span class="badge ${statusColors[req.status]} px-3 py-1">
                            ${statusLabels[req.status]}
                        </span>
                    </div>
                    
                    ${req.description ? `
                        <p class="text-gray-700 dark:text-gray-300 mb-4">${req.description}</p>
                    ` : ''}
                    
                    ${req.start_date ? `
                        <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
                            <strong>Período:</strong> ${formatDate(req.start_date)} ${req.end_date ? ' até ' + formatDate(req.end_date) : ''}
                        </div>
                    ` : ''}
                    
                    ${req.review_notes ? `
                        <div class="mt-4 p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <p class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Observações do Revisor:</p>
                            <p class="text-sm text-gray-600 dark:text-gray-400">${req.review_notes}</p>
                        </div>
                    ` : ''}
                    
                    ${req.status === 'pending' ? `
                        <div class="mt-4 flex gap-2">
                            <button onclick="cancelRequest(${req.id})" 
                                    class="px-4 py-2 border border-red-300 dark:border-red-600 text-red-600 dark:text-red-400 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20 text-sm">
                                Cancelar Solicitação
                            </button>
                        </div>
                    ` : ''}
                </div>
            `).join('');
            
            lucide.createIcons();
        }
        
        function filterStatus(status) {
            currentStatus = status;
            // Update active tab
            document.querySelectorAll('.status-tab').forEach(tab => {
                tab.classList.remove('active', 'border-blue-600', 'dark:border-blue-400', 'text-blue-600', 'dark:text-blue-400');
                tab.classList.add('border-transparent', 'text-gray-500', 'dark:text-gray-400');
            });
            event.target.classList.add('active', 'border-blue-600', 'dark:border-blue-400', 'text-blue-600', 'dark:text-blue-400');
            event.target.classList.remove('border-transparent', 'text-gray-500', 'dark:text-gray-400');
            
            renderRequests();
        }
        
        function showRequestModal() {
            const content = `
                <form id="request-form" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tipo de Solicitação</label>
                        <select name="request_type" id="request_type" required class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                            <option value="vacation">Férias</option>
                            <option value="loan">Empréstimo</option>
                            <option value="hr">Solicitação ao RH</option>
                            <option value="cadastral">Atualização Cadastral</option>
                            <option value="declaration">Declaração</option>
                            <option value="other">Outro</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Título</label>
                        <input type="text" name="title" required class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Descrição</label>
                        <textarea name="description" rows="4" required class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white"></textarea>
                    </div>
                    <div id="date-fields" class="grid grid-cols-2 gap-4 hidden">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Data Início</label>
                            <input type="date" name="start_date" class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Data Fim</label>
                            <input type="date" name="end_date" class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                        </div>
                    </div>
                </form>
            `;
            
            const footer = `
                <div class="flex gap-3 justify-end">
                    <button onclick="Modal.close('request-modal')" class="px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700">
                        Cancelar
                    </button>
                    <button onclick="submitRequest()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        Enviar Solicitação
                    </button>
                </div>
            `;
            
            new Modal('request-modal', 'Nova Solicitação', content, { footer }).render().show();
            
            // Show/hide date fields based on request type
            document.getElementById('request_type').addEventListener('change', (e) => {
                const dateFields = document.getElementById('date-fields');
                if (e.target.value === 'vacation') {
                    dateFields.classList.remove('hidden');
                } else {
                    dateFields.classList.add('hidden');
                }
            });
        }
        
        async function submitRequest() {
            const form = document.getElementById('request-form');
            const formData = new FormData(form);
            formData.append('action', 'create');
            
            try {
                const response = await fetch('api/requests.php', {
                    method: 'POST',
                    body: formData
                });
                
                const data = await response.json();
                if (data.success) {
                    Toast.show('Solicitação enviada com sucesso!', 'success');
                    Modal.close('request-modal');
                    loadRequests();
                } else {
                    Toast.show(data.message || 'Erro ao enviar solicitação', 'error');
                }
            } catch (error) {
                console.error('Request error:', error);
                Toast.show('Erro ao enviar solicitação', 'error');
            }
        }
        
        async function cancelRequest(id) {
            if (!confirm('Tem certeza que deseja cancelar esta solicitação?')) return;
            
            try {
                const formData = new FormData();
                formData.append('action', 'cancel');
                formData.append('id', id);
                
                const response = await fetch('api/requests.php', {
                    method: 'POST',
                    body: formData
                });
                
                const data = await response.json();
                if (data.success) {
                    Toast.show('Solicitação cancelada!', 'success');
                    loadRequests();
                } else {
                    Toast.show(data.message || 'Erro ao cancelar solicitação', 'error');
                }
            } catch (error) {
                Toast.show('Erro ao cancelar solicitação', 'error');
            }
        }
        
        document.addEventListener('DOMContentLoaded', loadRequests);
    </script>
</body>
</html>
