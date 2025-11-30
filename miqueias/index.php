<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <?php $pageTitle = 'Login - Portal do Associado'; ?>
    <?php include 'components/head.php'; ?>
    <style>
        :root {
            --primary-color: #0A55A1;
        }
        .text-primary { color: var(--primary-color); }
        .bg-primary { background-color: var(--primary-color); }
        .border-primary { border-color: var(--primary-color); }
        .ring-primary { --tw-ring-color: var(--primary-color); }
        .hover-bg-primary:hover { background-color: #084482; } /* Darker shade */
        .btn-primary {
            background-color: var(--primary-color);
        }
        .btn-primary:hover {
            background-color: #084482;
        }
        
        /* Opening Animation */
        #opening-animation {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: #ffffff;
            z-index: 50;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: opacity 0.5s ease-out;
        }
        .dark #opening-animation {
            background-color: #111827;
        }
        #opening-logo {
            width: 150px;
            opacity: 0;
            transform: scale(0.5);
            animation: logoEntrance 1s ease-out forwards;
        }
        @keyframes logoEntrance {
            0% { opacity: 0; transform: scale(0.5); }
            50% { opacity: 1; transform: scale(1.2); }
            100% { opacity: 1; transform: scale(1); }
        }
        .content-hidden {
            opacity: 0;
            transition: opacity 0.5s ease-in;
        }
        .content-visible {
            opacity: 1;
        }
    </style>
</head>
<body class="bg-gray-50 dark:bg-gray-900 min-h-screen flex items-center justify-center p-4 overflow-hidden">
    
    <!-- Opening Animation Overlay -->
    <div id="opening-animation">
        <img id="opening-logo" src="assets/asfa.png" alt="Logo">
    </div>

    <div id="main-content" class="w-full max-w-md content-hidden">
        <!-- Logo and Title -->
        <div class="text-center mb-8 fade-in">
            <div class="flex items-center justify-center w-96 max-w-full h-auto mb-6 mx-auto">
                <img src="assets/asfa.png" alt="ABCZ Logo" class="w-full h-full object-contain">
            </div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-1">Portal do Associado</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">Acesse sua conta</p>
        </div>
        
        <!-- Login Card -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl p-8 fade-in" style="animation-delay: 0.1s">
            <form id="login-form" class="space-y-6">
                <!-- Step 1: CPF -->
                <div id="step-cpf">
                    <label for="cpf" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        CPF
                    </label>
                    <input type="text" 
                           id="cpf" 
                           name="cpf" 
                           required
                           maxlength="14"
                           class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                           placeholder="000.000.000-00">
                    
                    <button type="button" 
                            id="btn-check-cpf"
                            class="mt-6 w-full btn-primary text-white font-semibold py-3 px-6 rounded-lg hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 ring-primary transition-all transform hover:scale-105">
                        Continuar
                    </button>
                </div>
                
                <!-- Step 2: Registration Number (Hidden initially) -->
                <div id="step-auth" class="hidden">
                    <div class="mb-4">
                        <label for="registration_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Número de Identificação (Matrícula)
                        </label>
                        <input type="text" 
                               id="registration_number" 
                               name="registration_number" 
                               required
                               class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 ring-primary focus:border-transparent transition-all"
                               placeholder="Digite sua matrícula">
                    </div>

                    <button type="submit" 
                            class="w-full btn-primary text-white font-semibold py-3 px-6 rounded-lg hover:opacity-90 focus:outline-none focus:ring-2 ring-primary focus:ring-offset-2 transition-all transform hover:scale-105">
                        Entrar
                    </button>
                    
                    <button type="button" 
                            id="btn-back"
                            class="mt-4 w-full bg-transparent border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 font-semibold py-2 px-6 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 transition-all">
                        Voltar
                    </button>
                </div>
            </form>
            
            <!-- Demo Credentials 
            <div class="mt-6 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-200 dark:border-blue-800">
                <p class="text-sm text-blue-800 dark:text-blue-300 font-medium mb-2">📌 Credenciais de Teste:</p>
                <p class="text-xs text-blue-700 dark:text-blue-400">CPF: 123.456.789-00</p>
                <p class="text-xs text-blue-700 dark:text-blue-400">Matrícula: EMP001</p>
            </div> Info -->
        </div>
        
        <!-- Footer -->
        <p class="text-center text-sm text-gray-600 dark:text-gray-400 mt-8">
            © 2025 Portal do Associado. Todos os direitos reservados.
        </p>
    </div>
    
    <script>
        // Ensure body is visible (Page Transitions)
        document.addEventListener('DOMContentLoaded', () => {
            document.body.classList.add('loaded');
        });

        // Opening Animation
        window.addEventListener('load', () => {
            setTimeout(() => {
                const overlay = document.getElementById('opening-animation');
                const content = document.getElementById('main-content');
                
                overlay.style.opacity = '0';
                content.classList.remove('content-hidden');
                content.classList.add('content-visible');
                
                setTimeout(() => {
                    overlay.remove();
                    document.body.classList.remove('overflow-hidden');
                }, 500);
            }, 1500);
        });

        // Simple theme toggle on login page
        const savedTheme = localStorage.getItem('theme') || 'light';
        document.documentElement.setAttribute('data-theme', savedTheme);
        if (savedTheme === 'dark') {
            document.documentElement.classList.add('dark');
        }
        
        // Handle login form submission
        // CPF Mask
        const cpfInput = document.getElementById('cpf');
        cpfInput.addEventListener('input', (e) => {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 11) value = value.slice(0, 11);
            
            if (value.length > 9) {
                value = value.replace(/^(\d{3})(\d{3})(\d{3})(\d{2}).*/, '$1.$2.$3-$4');
            } else if (value.length > 6) {
                value = value.replace(/^(\d{3})(\d{3})(\d{3}).*/, '$1.$2.$3');
            } else if (value.length > 3) {
                value = value.replace(/^(\d{3})(\d{3}).*/, '$1.$2');
            }
            
            e.target.value = value;
        });

        // Elements
        const stepCpf = document.getElementById('step-cpf');
        const stepAuth = document.getElementById('step-auth');
        const btnCheckCpf = document.getElementById('btn-check-cpf');
        const btnBack = document.getElementById('btn-back');

        // Check CPF Handler
        async function checkCpf() {
            const cpf = cpfInput.value;
            if (cpf.length < 14) {
                alert('Por favor, digite um CPF válido.');
                return;
            }

            const originalText = btnCheckCpf.innerText;
            btnCheckCpf.innerText = 'Verificando...';
            btnCheckCpf.disabled = true;

            const formData = new FormData();
            formData.append('action', 'check_cpf');
            formData.append('cpf', cpf);

            try {
                const response = await fetch('api/auth.php', {
                    method: 'POST',
                    body: formData
                });
                
                const data = await response.json();
                
                if (data.success) {
                    // Show Auth step
                    stepCpf.classList.add('hidden');
                    stepAuth.classList.remove('hidden');
                } else {
                    alert(data.message || 'CPF não encontrado');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Erro ao conectar com o servidor');
            } finally {
                btnCheckCpf.innerText = originalText;
                btnCheckCpf.disabled = false;
            }
        }

        btnCheckCpf.addEventListener('click', checkCpf);

        // Back Button
        btnBack.addEventListener('click', () => {
            stepAuth.classList.add('hidden');
            stepCpf.classList.remove('hidden');
        });

        // Handle login form submission (Registration Number verification)
        document.getElementById('login-form').addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const formData = new FormData(e.target);
            formData.append('action', 'login_registration');
            
            try {
                const response = await fetch('api/auth.php', {
                    method: 'POST',
                    body: formData
                });
                
                const data = await response.json();
                
                if (data.success) {
                    window.location.href = data.redirect || 'dashboard.php';
                } else {
                    alert(data.message || 'Erro ao fazer login');
                }
            } catch (error) {
                console.error('Login error:', error);
                alert('Erro ao conectar com o servidor');
            }
        });
    </script>
</body>
</html>
