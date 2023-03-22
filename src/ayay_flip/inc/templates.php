<?php
  namespace iberezansky\fb3d;

  function init_local_templates() {
    global $fb3d;
    $fb3d['templates'] = [
      'short-white-book-view'=> [
        'styles'=> [
          ASSETS_CSS.'font-awesome.min.css',
          ASSETS_CSS.'short-white-book-view.css'
        ],
        'links'=> [],
        'html'=> ASSETS_TEMPLATES.'default-book-view.html',
        'script'=> ASSETS_JS.'default-book-view.js',
        'sounds'=> [
          'startFlip'=> ASSETS_SOUNDS.'start-flip.mp3',
          'endFlip'=> ASSETS_SOUNDS.'end-flip.mp3'
        ]
      ],
      'white-book-view'=> [
        'styles'=> [
          ASSETS_CSS.'font-awesome.min.css',
          ASSETS_CSS.'white-book-view.css'
        ],
        'links'=> [],
        'html'=> ASSETS_TEMPLATES.'default-book-view.html',
        'script'=> ASSETS_JS.'default-book-view.js',
        'sounds'=> [
          'startFlip'=> ASSETS_SOUNDS.'start-flip.mp3',
          'endFlip'=> ASSETS_SOUNDS.'end-flip.mp3'
        ]
      ],
      'short-black-book-view'=> [
        'styles'=> [
          ASSETS_CSS.'font-awesome.min.css',
          ASSETS_CSS.'short-black-book-view.css'
        ],
        'links'=> [],
        'html'=> ASSETS_TEMPLATES.'default-book-view.html',
        'script'=> ASSETS_JS.'default-book-view.js',
        'sounds'=> [
          'startFlip'=> ASSETS_SOUNDS.'start-flip.mp3',
          'endFlip'=> ASSETS_SOUNDS.'end-flip.mp3'
        ]
      ],
      'black-book-view'=> [
        'styles'=> [
          ASSETS_CSS.'font-awesome.min.css',
          ASSETS_CSS.'black-book-view.css'
        ],
        'links'=> [],
        'html'=> ASSETS_TEMPLATES.'default-book-view.html',
        'script'=> ASSETS_JS.'default-book-view.js',
        'sounds'=> [
          'startFlip'=> ASSETS_SOUNDS.'start-flip.mp3',
          'endFlip'=> ASSETS_SOUNDS.'end-flip.mp3'
        ]
      ],
      'search-book-view'=> [
        'styles'=> [
          ASSETS_CSS.'font-awesome.min.css',
          ASSETS_CSS.'search-book-view.css'
        ],
        'links'=> [],
        'html'=> ASSETS_TEMPLATES.'search-book-view.html',
        'script'=> ASSETS_JS.'search-book-view.js',
        'sounds'=> [
          'startFlip'=> ASSETS_SOUNDS.'start-flip.mp3',
          'endFlip'=> ASSETS_SOUNDS.'end-flip.mp3'
        ]
      ]
    ];
  }

  $fb3d['lightboxes'] = [
    'light' => [
      'caption'=> 'Light Glass Box'
    ],
    'dark' => [
      'caption'=> 'Dark Glass Box'
    ],
    'dark-shadow' => [
      'caption'=> 'Dark Glass Shadow'
    ],
    'light-shadow' => [
      'caption'=> 'Light Glass Shadow'
    ]
  ];

  function init_templates() {
    global $fb3d;
    $fb3d['templates'] = apply_filters('fb3d_templates', $fb3d['templates']);
  }

  add_action('init', 'iberezansky\fb3d\init_templates');

?>
