<?php

namespace App\Core;

class View {
    public static function render(string $templateFile, array $data = []): void
    {
        $config = require __DIR__ . '/../../config/config.php';
        $templatePath = $config['view_path'] . $templateFile;

        if (!file_exists($templatePath)) {
            http_response_code(500);
            echo "View not found: $templateFile";
            return;
        }

        extract($data); // allows using $error, $username, etc.
        require $templatePath;
    }
}
