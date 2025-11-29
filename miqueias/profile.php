<?php
require_once 'config/session.php';
require_once 'config/database.php';
require_once 'components/breadcrumbs.php';
requireAuth();

$employee = getCurrentEmployee();
$employeeId = getCurrentEmployeeId();

// Get full employee data
$employeeData = dbGetRow("SELECT * FROM employees WHERE id = ?", [$employeeId]);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meu Perfil - Portal do Colaborador</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="assets/css/styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="bg-gray-50 dark:bg-gray-900">
    
    <?php include 'components/sidebar.php'; ?>
    
    <div class="lg:ml-64">
        <?php include 'components/header.php'; ?>
        
        <main class="p-6">
            <?php renderBreadcrumbs([['label' => 'Meu Perfil']]); ?>
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Profile Card -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 text-center fade-in">
                    <img src="<?= htmlspecialchars($employeeData['photo_url']) ?>" 
                         alt="Foto de Perfil" 
                         class="w-32 h-32 rounded-full mx-auto mb-4 object-cover border-4 border-blue-500">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-1"><?= htmlspecialchars($employeeData['full_name']) ?></h2>
                    <p class="text-gray-600 dark:text-gray-400 mb-4"><?= htmlspecialchars($employeeData['position']) ?></p>
                    <div class="space-y-2 text-sm">
                        <div class="flex items-center justify-center gap-2 text-gray-700 dark:text-gray-300">
                            <i data-lucide="building" class="w-4 h-4"></i>
                            <?= htmlspecialchars($employeeData['department']) ?>
                        </div>
                        <div class="flex items-center justify-center gap-2 text-gray-700 dark:text-gray-300">
                            <i data-lucide="id-card" class="w-4 h-4"></i>
                            Matrícula: <?= htmlspecialchars($employeeData['registration_number']) ?>
                        </div>
                        <div class="flex items-center justify-center gap-2 text-gray-700 dark:text-gray-300">
                            <i data-lucide="calendar" class="w-4 h-4"></i>
                            <?= getTimeInCompany($employeeData['hire_date']) ?> na empresa
                        </div>
                    </div>
                    <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300">
                            <span class="status-dot status-active"></span>
                            Ativo
                        </span>
                    </div>
                </div>
                
                <!-- Professional Info -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Professional Data -->
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 fade-in" style="animation-delay: 0.1s">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                            <i data-lucide="briefcase" class="w-5 h-5 text-blue-600"></i>
                            Informações Profissionais
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Cargo</label>
                                <p class="text-gray-900 dark:text-white font-medium"><?= htmlspecialchars($employeeData['position']) ?></p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Departamento</label>
                                <p class="text-gray-900 dark:text-white font-medium"><?= htmlspecialchars($employeeData['department']) ?></p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Data de Admissão</label>
                                <p class="text-gray-900 dark:text-white font-medium"><?= formatDate($employeeData['hire_date']) ?></p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Matrícula</label>
                                <p class="text-gray-900 dark:text-white font-medium"><?= htmlspecialchars($employeeData['registration_number']) ?></p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Personal Data -->
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 fade-in" style="animation-delay: 0.2s">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                            <i data-lucide="user" class="w-5 h-5 text-blue-600"></i>
                            Dados Pessoais
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="text-sm font-medium text-gray-500 dark:text-gray-400">E-mail</label>
                                <p class="text-gray-900 dark:text-white font-medium"><?= htmlspecialchars($employeeData['email']) ?></p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Telefone</label>
                                <p class="text-gray-900 dark:text-white font-medium"><?= htmlspecialchars($employeeData['phone'] ?: '-') ?></p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Celular</label>
                                <p class="text-gray-900 dark:text-white font-medium"><?= htmlspecialchars($employeeData['mobile'] ?: '-') ?></p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Data de Nascimento</label>
                                <p class="text-gray-900 dark:text-white font-medium"><?= $employeeData['birth_date'] ? formatDate($employeeData['birth_date']) : '-' ?></p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Address -->
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 fade-in" style="animation-delay: 0.3s">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                            <i data-lucide="map-pin" class="w-5 h-5 text-blue-600"></i>
                            Endereço
                        </h3>
                        <div class="grid grid-cols-1 gap-4">
                            <div class="grid grid-cols-3 gap-4">
                                <div class="col-span-2">
                                    <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Rua</label>
                                    <p class="text-gray-900 dark:text-white font-medium"><?= htmlspecialchars($employeeData['address_street'] ?: '-') ?></p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Número</label>
                                    <p class="text-gray-900 dark:text-white font-medium"><?= htmlspecialchars($employeeData['address_number'] ?: '-') ?></p>
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Bairro</label>
                                    <p class="text-gray-900 dark:text-white font-medium"><?= htmlspecialchars($employeeData['address_neighborhood'] ?: '-') ?></p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Complemento</label>
                                    <p class="text-gray-900 dark:text-white font-medium"><?= htmlspecialchars($employeeData['address_complement'] ?: '-') ?></p>
                                </div>
                            </div>
                            <div class="grid grid-cols-3 gap-4">
                                <div>
                                    <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Cidade</label>
                                    <p class="text-gray-900 dark:text-white font-medium"><?= htmlspecialchars($employeeData['address_city'] ?: '-') ?></p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-500 dark:text-gray-400">UF</label>
                                    <p class="text-gray-900 dark:text-white font-medium"><?= htmlspecialchars($employeeData['address_state'] ?: '-') ?></p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-500 dark:text-gray-400">CEP</label>
                                    <p class="text-gray-900 dark:text-white font-medium"><?= htmlspecialchars($employeeData['address_zipcode'] ?: '-') ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
    
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="assets/js/main.js"></script>
    <script>lucide.createIcons();</script>
</body>
</html>
