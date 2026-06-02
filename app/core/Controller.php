<?php
declare(strict_types=1);

abstract class Controller {
    protected function view(string $view, array $data = []): void {
        extract($data, EXTR_SKIP);
        $viewFile = __DIR__ . '/../views/' . $view . '.php';
        if (!is_file($viewFile)) { http_response_code(500); exit('Vista no encontrada: ' . e($view)); }
        require __DIR__ . '/../views/layouts/header.php';
        require $viewFile;
        require __DIR__ . '/../views/layouts/footer.php';
    }

    protected function authView(string $view, array $data = []): void {
        extract($data, EXTR_SKIP);
        require __DIR__ . '/../views/' . $view . '.php';
    }
}
