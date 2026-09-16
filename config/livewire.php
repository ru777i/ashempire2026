<?php

return [
    'layout' => 'layouts.app',

    'lazy_placeholder' => 'livewire.placeholder',

    'class_namespace' => 'App\\Livewire',
    'class_path' => app_path('\Livewire'),
    'view_path' => resource_path('views/livewire'),

    'smart_wire_keys' => false,

    'pagination_theme' => 'tailwind',
    'make_command' => [
        'type' => 'class',
        'emoji' => 'false',
    ],

    'inject_assets' => true,
    'legacy_model_binding' => false,
    'inject_morph_markers' => true,
    'render_on_redirect' => false,

    'navigate' => [
        'show_progress_bar' => true,
        'progress_bar_color' => '#2299dd',
    ],
];
