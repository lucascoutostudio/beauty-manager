<?php
// app/bootstrap/autoloader.php

// A função spl_autoload_register tenta carregar uma classe quando ela é referenciada.
spl_autoload_register(function ($className) {
    
    // 1. Converte o namespace completo para um caminho de arquivo.
    // Ex: "app\Models\Entity\Conta"
    //     se torna "app/Models/Entity/Conta"
    $file = str_replace('\\', DIRECTORY_SEPARATOR, $className);
    
    // 2. Adiciona a extensão do arquivo
    $file .= '.php';

    // 3. Verifica e inclui o arquivo
    // 🚨 ATENÇÃO: Você pode precisar ajustar o caminho base aqui.
    // Se o seu ponto de entrada (index.php) estiver na raiz, use $file.
    // Se estiver em outro lugar, use um caminho absoluto.
    
    // Exemplo: Assumindo que o namespace 'app\' corresponde ao diretório 'app/'
    if (file_exists($file)) {
        require_once $file;
        return;
    }
    
    // Tenta caminhos alternativos se a classe não for encontrada no primeiro
    // Exemplo para um ambiente que usa 'app/src' como base:
    // $srcFile = 'src/' . $file;
    // if (file_exists($srcFile)) {
    //     require_once $srcFile;
    // }
});