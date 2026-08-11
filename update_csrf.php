<?php
$dir = new RecursiveDirectoryIterator(__DIR__ . '/views/admin');
$iterator = new RecursiveIteratorIterator($dir);
foreach ($iterator as $file) {
    if ($file->isFile() && $file->getExtension() === 'php' && $file->getFilename() !== 'login.php' && $file->getFilename() !== 'logout.php') {
        $path = $file->getPathname();
        $content = file_get_contents($path);
        
        $modified = false;

        // Thêm hidden input vào form POST
        $patternForm = '/(<form\s+[^>]*method=["\']POST["\'][^>]*>)/i';
        if (preg_match($patternForm, $content) && strpos($content, 'name="csrf_token"') === false) {
            $replacement = "$1\n<input type=\"hidden\" name=\"csrf_token\" value=\"<?= htmlspecialchars(\$_SESSION['csrf_token'] ?? '') ?>\">";
            $content = preg_replace($patternForm, $replacement, $content);
            $modified = true;
        }

        // Thêm CsrfMiddleware::verify() vào xử lý POST
        $patternPost = '/(if\s*\(\s*\$_SERVER\["REQUEST_METHOD"\]\s*==\s*"POST".*?\{)/is';
        if (preg_match($patternPost, $content) && strpos($content, 'CsrfMiddleware::verify();') === false) {
            $replacementPost = "$1\n    CsrfMiddleware::verify();";
            $content = preg_replace($patternPost, $replacementPost, $content);
            $modified = true;
        }
        
        if ($modified) {
            file_put_contents($path, $content);
            echo "Updated: $path\n";
        }
    }
}
echo "Done.\n";
