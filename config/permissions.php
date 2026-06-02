<?php

return [
    // Map menu keys to allowed level_kode values
    // Edit this mapping to change which roles can see which menu
    'dashboard' => ['ADM', 'MNG', 'STF'],
    'level'     => ['ADM'],
    'user'      => ['ADM'],
    'kategori'  => ['ADM', 'MNG'],
    'barang'    => ['ADM', 'MNG'],
    'supplier'  => ['ADM', 'MNG'],
    'stok'      => ['ADM', 'MNG', 'STF'],
    'penjualan' => ['ADM', 'MNG', 'STF'],
];
