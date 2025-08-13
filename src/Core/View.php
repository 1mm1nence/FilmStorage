<?php

namespace App\Core;

class View {
    public static function render(string $templateFile, array $data = []): void
    {
        $config = require __DIR__ . '/../../config/config.php';
        $templatePath = $config['templates_path'] . $templateFile;

        if (!file_exists($templatePath)) {
            http_response_code(500);
            echo "View not found: $templateFile";
            return;
        }

        extract($data);

        ob_start();
        require $templatePath;
        $content = ob_get_clean();

        require $config['templates_path'] . 'base.php';
    }
}
