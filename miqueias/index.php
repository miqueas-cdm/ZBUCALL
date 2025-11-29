<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Portal do Colaborador</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="bg-gradient-to-br from-blue-50 to-indigo-100 dark:from-gray-900 dark:to-gray-800 min-h-screen flex items-center justify-center p-4">
    
    <div class="w-full max-w-md">
        <!-- Logo and Title -->
        <div class="text-center mb-8 fade-in">
            <div class="inline-flex items-center justify-center w-24 h-24 mb-4">
                <img src="assets/logo.png" alt="Logo" class="w-full h-full object-contain">
            </div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">Portal do Colaborador</h1>
            <p class="text-gray-600 dark:text-gray-400">Acesse sua conta para continuar</p>
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
                            id="btn-send-code"
                            class="mt-6 w-full bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-semibold py-3 px-6 rounded-lg hover:from-blue-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all transform hover:scale-105">
                        Enviar Código via SMS
                    </button>
                </div>
                
                <!-- Step 2: OTP (Hidden initially) -->
                <div id="step-otp" class="hidden">
                    <div class="mb-4">
                        <label for="otp" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Código SMS
                        </label>
                        <input type="text" 
                               id="otp" 
                               name="otp" 
                               maxlength="6"
                               class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all text-center tracking-widest text-xl"
                               placeholder="000000">
                        <p class="mt-2 text-xs text-gray-500 dark:text-gray-400 text-right">
                            <button type="button" id="btn-resend" class="text-blue-600 hover:text-blue-700 dark:text-blue-400">Reenviar código</button>
                        </p>
                    </div>

                    <button type="submit" 
                            class="w-full bg-gradient-to-r from-green-600 to-teal-600 text-white font-semibold py-3 px-6 rounded-lg hover:from-green-700 hover:to-teal-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-all transform hover:scale-105">
                        Entrar
                    </button>
                    
                    <button type="button" 
                            id="btn-back"
                            class="mt-4 w-full bg-transparent border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 font-semibold py-2 px-6 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 transition-all">
                        Voltar
                    </button>
                </div>
            </form>
            
            <!-- Demo Credentials Info -->
            <div class="mt-6 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-200 dark:border-blue-800">
                <p class="text-sm text-blue-800 dark:text-blue-300 font-medium mb-2">📌 Credenciais de Teste:</p>
                <p class="text-xs text-blue-700 dark:text-blue-400">CPF: 123.456.789-00</p>
                <p class="text-xs text-blue-700 dark:text-blue-400">O código será exibido no alerta (Ambiente Dev)</p>
            </div>
        </div>
        
        <!-- Footer -->
        <p class="text-center text-sm text-gray-600 dark:text-gray-400 mt-8">
            © 2025 Portal do Colaborador. Todos os direitos reservados.
        </p>
    </div>
    
    <script>
        // Simple theme toggle on login page
        const savedTheme = localStorage.getItem('theme') || 'light';
        document.documentElement.setAttribute('data-theme', savedTheme);
        
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
        const stepOtp = document.getElementById('step-otp');
        const btnSendCode = document.getElementById('btn-send-code');
        const btnBack = document.getElementById('btn-back');
        const btnResend = document.getElementById('btn-resend');

        // Send Code Handler
        async function sendCode() {
            const cpf = cpfInput.value;
            if (cpf.length < 14) {
                alert('Por favor, digite um CPF válido.');
                return;
            }

            const originalText = btnSendCode.innerText;
            btnSendCode.innerText = 'Enviando...';
            btnSendCode.disabled = true;

            const formData = new FormData();
            formData.append('action', 'send_otp');
            formData.append('cpf', cpf);

            try {
                const response = await fetch('api/auth.php', {
                    method: 'POST',
                    body: formData
                });
                
                const data = await response.json();
                
                if (data.success) {
                    // Show OTP step
                    stepCpf.classList.add('hidden');
                    stepOtp.classList.remove('hidden');
                    
                    // Show dev OTP
                    if (data.dev_otp) {
                        alert(`[DEV] Seu código de acesso é: ${data.dev_otp}`);
                        console.log('OTP:', data.dev_otp);
                    }
                } else {
                    alert(data.message || 'Erro ao enviar código');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Erro ao conectar com o servidor');
            } finally {
                btnSendCode.innerText = originalText;
                btnSendCode.disabled = false;
            }
        }

        btnSendCode.addEventListener('click', sendCode);
        btnResend.addEventListener('click', sendCode);

        // Back Button
        btnBack.addEventListener('click', () => {
            stepOtp.classList.add('hidden');
            stepCpf.classList.remove('hidden');
        });

        // Handle login form submission (OTP verification)
        document.getElementById('login-form').addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const formData = new FormData(e.target);
            formData.append('action', 'login_otp');
            
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
