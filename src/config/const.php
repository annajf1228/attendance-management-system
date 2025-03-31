<?php

return [
  // タイトル
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
      'user' => [
        'staff' => [
          'index' => '月間勤怠一覧',
          'edit' => '日間勤怠編集',
        ]
      ],
    ],
    'sub_title' => [
      'index' => '一覧',
      'create' => '新規登録',
      'show' => '詳細',
      'edit' => '編集',
      'login' => 'ログイン',
    ],
  ],
  // 祝日番号
  'holiday_num' => [
    'saturday' => 1,
    'sunday' => 2,
    'public_holiday' => 3,
  ],
  // 出勤ステータス
  'work_status' => [
    'clocked_in'  => 1,  // 出勤
    'clocked_out' => 2,  // 退勤
    'not_worked'  => 99, // 未出勤
  ],
  // 休憩時間のリスト
  'break_time_list' => [
    0 => '00:00',
    15 => '00:15',
    30 => '00:30',
    45 => '00:45',
    60 => '01:00',
    75 => '01:15',
    90 => '01:30',
    105 => '01:45',
    120 => '02:00',
  ],
];
