<?php
require __DIR__.'/vendor/autoload.php';

echo "Testando inicialização do Laravel...\n";

try {
    $app = require_once __DIR__.'/bootstrap/app.php';
    echo "✅ bootstrap/app.php carregado\n";

    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    echo "✅ Kernel criado\n";

    $kernel->bootstrap();
    echo "✅ Laravel inicializado com sucesso!\n";

} catch (Exception $e) {
    echo "❌ Erro: " . $e->getMessage() . "\n";
    echo "Arquivo: " . $e->getFile() . " Linha: " . $e->getLine() . "\n";
}
