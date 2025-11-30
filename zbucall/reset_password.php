<?php
/**
 * Script para resetar senha de um colaborador
 * Executar via linha de comando ou navegador
 */

require_once 'config/database.php';

// Nova senha que você quer definir
$novaSenha = 'senha123';

// Email do colaborador
$email = 'joao.silva@empresa.com';

// Gera o hash da senha
$senhaHash = password_hash($novaSenha, PASSWORD_DEFAULT);

// Atualiza no banco de dados
try {
    $sql = "UPDATE employees SET password = ? WHERE email = ?";
    $result = dbExecute($sql, [$senhaHash, $email]);
    
    if ($result) {
        echo "✅ Senha atualizada com sucesso!\n";
        echo "Email: $email\n";
        echo "Nova senha: $novaSenha\n";
        echo "\nAgora você pode fazer login com essas credenciais.\n";
    } else {
        echo "❌ Erro ao atualizar senha.\n";
        echo "Verifique se o email está correto e se o banco de dados foi importado.\n";
    }
} catch (Exception $e) {
    echo "❌ Erro: " . $e->getMessage() . "\n";
}

echo "\n";
echo "Você também pode usar este script para gerar hash de outras senhas:\n";
echo "Hash de 'senha123': " . password_hash('senha123', PASSWORD_DEFAULT) . "\n";
