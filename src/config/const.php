<?php

return [
  // title
  'title' => [
    'web_title' => [
      'admin' => '管理' . config('app.name'),
      'user' => config('app.name'),
    ],
    'page_title' => [
      'admin' => [
        'admin' => '管理者管理',
        'user' => 'スタッフ管理',
      ],
      'user' => [],
    ],
    'sub_title' => [
      'index' => '一覧',
      'create' => '新規登録',
      'show' => '詳細',
      'edit' => '編集',
      'login' => 'ログイン',
    ],
  ]
];