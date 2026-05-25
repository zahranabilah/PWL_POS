<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$view = $app->make(Illuminate\Contracts\View\Factory::class);
echo $view->make('stok.index', [
    'page' => (object)['title' => 'test'],
    'breadcrumb' => (object)['title' => 'test', 'list' => ['Home']],
    'activeMenu' => 'stok'
])->render();
