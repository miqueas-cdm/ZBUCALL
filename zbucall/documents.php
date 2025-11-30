<?php
require_once 'config/session.php';
require_once 'components/breadcrumbs.php';
requireAuth();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <?php $pageTitle = 'Documentos - Portal do Associado'; ?>
    <?php include 'components/head.php'; ?>
</head>
<body class="bg-gray-50 dark:bg-gray-900">
    
    <?php include 'components/sidebar.php'; ?>
    
    <div class="lg:ml-64">
        <?php include 'components/header.php'; ?>
        
        <main class="p-6">
            <?php renderBreadcrumbs([['label' => 'Documentos']]); ?>
            
            <div class="mb-6 flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Documentos</h1>
                    <p class="text-gray-600 dark:text-gray-400">Gerencie seus documentos e políticas da empresa</p>
                </div>
                <button onclick="showUploadModal()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors flex items-center gap-2">
                    <i data-lucide="upload" class="w-5 h-5"></i>
                    Enviar Documento
                </button>
            </div>
            
            <!-- Category Tabs -->
            <div class="mb-6">
                <div class="border-b border-gray-200 dark:border-gray-700 overflow-x-auto">
                    <nav class="-mb-px flex gap-6 min-w-max">
                        <button onclick="filterCategory('all')" class="category-tab active border-b-2 border-blue-600 dark:border-blue-400 text-blue-600 dark:text-blue-400 py-3 px-1 font-medium text-sm whitespace-nowrap">
                            Todos
                        </button>
                        <button onclick="filterCategory('policy')" class="category-tab border-b-2 border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600 py-3 px-1 font-medium text-sm whitespace-nowrap">
                            Políticas
                        </button>
                        <button onclick="filterCategory('manual')" class="category-tab border-b-2 border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600 py-3 px-1 font-medium text-sm whitespace-nowrap">
                            Manuais
                        </button>
                        <button onclick="filterCategory('certificate')" class="category-tab border-b-2 border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600 py-3 px-1 font-medium text-sm whitespace-nowrap">
                            Certificados
                        </button>
                        <button onclick="filterCategory('card')" class="category-tab border-b-2 border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600 py-3 px-1 font-medium text-sm whitespace-nowrap">
                            Carteirinhas
                        </button>
                        <button onclick="filterCategory('events')" class="category-tab border-b-2 border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600 py-3 px-1 font-medium text-sm whitespace-nowrap">
                            Eventos
                        </button>
                        <button onclick="filterCategory('transparency')" class="category-tab border-b-2 border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600 py-3 px-1 font-medium text-sm whitespace-nowrap">
                            Transparência
                        </button>
                    </nav>
                </div>
            </div>
            
            <!-- Documents Grid -->
            <div id="documents-container" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                <div class="skeleton h-32"></div>
                <div class="skeleton h-32"></div>
                <div class="skeleton h-32"></div>
                <div class="skeleton h-32"></div>
            </div>
        </main>
    </div>
    
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="assets/js/main.js"></script>
    <script src="assets/js/components.js"></script>
    <script>
        let allDocuments = [];
        let currentCategory = 'all';
        
        async function loadDocuments() {
            try {
                const response = await fetch('api/documents.php?action=list');
                const data = await response.json();
                
                if (data.success) {
                    allDocuments = data.documents || [];
                    
                    // Add mock data for Transparency and Events if not present
                    if (!allDocuments.some(d => d.category === 'transparency')) {
                        allDocuments.push(
                            { id: 101, title: 'Balancete Outubro/2025', description: 'Relatório financeiro mensal', category: 'transparency', created_at: '2025-11-15', file_size: 2457600, is_public: true },
                            { id: 102, title: 'Ata da Assembleia Geral', description: 'Registro da última assembleia', category: 'transparency', created_at: '2025-11-10', file_size: 1843200, is_public: true },
                            { id: 103, title: 'Prestação de Contas Trimestral', description: 'Detalhamento de despesas e receitas', category: 'transparency', created_at: '2025-11-01', file_size: 460800, is_public: true }
                        );
                    }
                    if (!allDocuments.some(d => d.category === 'events')) {
                        allDocuments.push(
                            { id: 201, title: 'Cronograma Festa de Fim de Ano', description: 'Detalhes do evento de encerramento', category: 'events', created_at: '2025-11-20', file_size: 1024000, is_public: true },
                            { id: 202, title: 'Convite Workshop de Saúde', description: 'Convite para associados', category: 'events', created_at: '2025-11-18', file_size: 512000, is_public: true }
                        );
                    }

                    renderDocuments();
                }
            } catch (error) {
                console.error('Error loading documents:', error);
            }
        }
        
        function renderDocuments() {
            const filtered = currentCategory === 'all' 
                ? allDocuments 
                : allDocuments.filter(doc => doc.category === currentCategory);
            
            const container = document.getElementById('documents-container');
            
            if (filtered.length === 0) {
                container.innerHTML = '<p class="col-span-full text-center text-gray-500 py-8">Nenhum documento encontrado</p>';
                return;
            }
            
            const categoryIcons = {
                policy: 'file-text',
                manual: 'book-open',
                certificate: 'award',
                card: 'credit-card',
                events: 'calendar',
                transparency: 'bar-chart-2',
                other: 'file'
            };
            
            container.innerHTML = filtered.map((doc, index) => `
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 hover:shadow-lg transition-shadow fade-in" style="animation-delay: ${index * 0.05}s">
                    <div class="flex items-start justify-between mb-3">
                        <div class="bg-blue-100 dark:bg-blue-900/30 p-2 rounded-lg">
                            <i data-lucide="${categoryIcons[doc.category] || 'file'}" class="w-5 h-5 text-blue-600 dark:text-blue-400"></i>
                        </div>
                        ${doc.is_public ? '<span class="badge badge-info text-xs">Público</span>' : ''}
                    </div>
                    <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-1 truncate">${doc.title}</h3>
                    <p class="text-xs text-gray-600 dark:text-gray-400 mb-3 line-clamp-2">${doc.description || 'Sem descrição'}</p>
                    <div class="flex items-center justify-between text-xs text-gray-500 dark:text-gray-400 mb-3">
                        <span>${formatDate(doc.created_at)}</span>
                        <span>${(doc.file_size / 1024).toFixed(1)} KB</span>
                    </div>
                    <div class="flex gap-2">
                        <button onclick="downloadDocument(${doc.id})" class="flex-1 px-3 py-2 bg-blue-600 text-white rounded text-xs font-medium hover:bg-blue-700">
                            <i data-lucide="download" class="w-3 h-3 inline mr-1"></i> Download
                        </button>
                        <button onclick="deleteDocument(${doc.id})" class="px-3 py-2 border border-red-300 dark:border-red-600 text-red-600 dark:text-red-400 rounded text-xs font-medium hover:bg-red-50 dark:hover:bg-red-900/20">
                            <i data-lucide="trash-2" class="w-3 h-3"></i>
                        </button>
                    </div>
                </div>
            `).join('');
            
            lucide.createIcons();
        }
        
        function filterCategory(category) {
            currentCategory = category;
            // Update active tab
            document.querySelectorAll('.category-tab').forEach(tab => {
                tab.classList.remove('active', 'border-blue-600', 'dark:border-blue-400', 'text-blue-600', 'dark:text-blue-400');
                tab.classList.add('border-transparent', 'text-gray-500', 'dark:text-gray-400');
            });
            event.target.classList.add('active', 'border-blue-600', 'dark:border-blue-400', 'text-blue-600', 'dark:text-blue-400');
            event.target.classList.remove('border-transparent', 'text-gray-500', 'dark:text-gray-400');
            
            renderDocuments();
        }
        
        function showUploadModal() {
            const content = `
                <form id="upload-form" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Título</label>
                        <input type="text" name="title" required class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Descrição</label>
                        <textarea name="description" rows="3" class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white"></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Categoria</label>
                        <select name="category" required class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                            <option value="other">Outro</option>
                            <option value="policy">Política</option>
                            <option value="manual">Manual</option>
                            <option value="certificate">Certificado</option>
                            <option value="card">Carteirinha</option>
                            <option value="events">Eventos</option>
                            <option value="transparency">Transparência</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Arquivo</label>
                        <input type="file" name="file" required class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                    </div>
                </form>
            `;
            
            const footer = `
                <div class="flex gap-3 justify-end">
                    <button onclick="Modal.close('upload-modal')" class="px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700">
                        Cancelar
                    </button>
                    <button onclick="submitUpload()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        Enviar
                    </button>
                </div>
            `;
            
            new Modal('upload-modal', 'Enviar Documento', content, { footer }).render().show();
        }
        
        async function submitUpload() {
            const form = document.getElementById('upload-form');
            const formData = new FormData(form);
            formData.append('action', 'upload');
            formData.append('document_type', 'general');
            
            try {
                const response = await fetch('api/documents.php', {
                    method: 'POST',
                    body: formData
                });
                
                const data = await response.json();
                if (data.success) {
                    Toast.show('Documento enviado com sucesso!', 'success');
                    Modal.close('upload-modal');
                    loadDocuments();
                } else {
                    Toast.show(data.message || 'Erro ao enviar documento', 'error');
                }
            } catch (error) {
                console.error('Upload error:', error);
                Toast.show('Erro ao enviar documento', 'error');
            }
        }
        
        function downloadDocument(id) {
            // In a real implementation, this would download the file
            Toast.show('Iniciando download...', 'info');
        }
        
        async function deleteDocument(id) {
            if (!confirm('Tem certeza que deseja excluir este documento?')) return;
            
            try {
                const formData = new FormData();
                formData.append('action', 'delete');
                formData.append('id', id);
                
                const response = await fetch('api/documents.php', {
                    method: 'POST',
                    body: formData
                });
                
                const data = await response.json();
                if (data.success) {
                    Toast.show('Documento excluído!', 'success');
                    loadDocuments();
                }
            } catch (error) {
                Toast.show('Erro ao excluir documento', 'error');
            }
        }
        
        document.addEventListener('DOMContentLoaded', loadDocuments);
    </script>
</body>
</html>
