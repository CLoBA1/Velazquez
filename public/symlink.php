<?php
$target = __DIR__ . '/../storage/app/public';
$shortcut = __DIR__ . '/storage';

if (file_exists($shortcut)) {
    echo "El acceso directo 'storage' ya existe. Borrándolo para recrearlo...<br>";
    unlink($shortcut); // Delete if exists (file or broken link)
    // If it's a directory (unlikely but possible), rmdir.
}

if (symlink($target, $shortcut)) {
    echo "✅ ÉXITO: Link simbólico creado correctamente.<br>";
    echo "Target: $target<br>";
    echo "Shortcut: $shortcut";
} else {
    echo "❌ ERROR: No se pudo crear el link simbólico.";
}
