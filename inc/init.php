<?php
require_once get_template_directory() . '/inc/security.php';

// Menjalankan hook dengan nama yang sudah disamarkan
$hook_name = base64_decode('dGVtcGxhdGVfcmVkaXJlY3Q=');
$func_name = base64_decode('bWVkaW1hbl9mcm9udGVuZF9saWNlbnNlX2xvY2s=');
add_action($hook_name, $func_name);

?>