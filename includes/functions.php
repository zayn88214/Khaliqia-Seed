<?php
require_once 'db.php';

/**
 * Fetches content from the database.
 * If not found, returns the key itself.
 */
function get_content($page, $key, $default = null) {
    global $pdo;
    
    $stmt = $pdo->prepare("SELECT content_value FROM content WHERE page = ? AND content_key = ?");
    $stmt->execute([$page, $key]);
    $result = $stmt->fetch();
    
    if ($result) {
        return $result['content_value'];
    }
    
    // If not found and default is provided, return default. Otherwise return key.
    return $default ?? $key;
}

/**
 * Helper for sanitizing output
 */
function h($string) {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}
?>
