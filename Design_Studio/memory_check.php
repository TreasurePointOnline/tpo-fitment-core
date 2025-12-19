<?php
        require_once('wp-load.php');
        echo "<h1>Memory Check</h1>";
        echo "<p>PHP Memory Limit: " . ini_get('memory_limit') . "</p>";
        echo "<p>WP Memory Limit: " . (defined('WP_MEMORY_LIMIT') ? WP_MEMORY_LIMIT : 'Not Defined') . "</p>";
        echo "<p>WP Max Memory Limit: " . (defined('WP_MAX_MEMORY_LIMIT') ? WP_MAX_MEMORY_LIMIT : 'Not Defined') . "</p>";
        ?>