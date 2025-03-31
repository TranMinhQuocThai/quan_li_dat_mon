<?php

return [
    'required' => ':attribute không được để trống.',
    'string' => ':attribute phải là một chuỗi ký tự.',
    'min' => [
        'string' => ':attribute phải có ít nhất :min ký tự.',
        'numeric' => ':attribute phải lớn hơn hoặc bằng :min.',
        'array' => ':attribute phải có ít nhất :min mục.',
        'file' => ':attribute phải có dung lượng tối thiểu :min KB.',
    ],
    'max' => [
        'string' => ':attribute không được vượt quá :max ký tự.',
        'numeric' => ':attribute không được vượt quá :max.',
        'array' => ':attribute không được vượt quá :max mục.',
        'file' => ':attribute không được vượt quá :max KB.',
    ],
    'email' => ':attribute không hợp lệ.',
    'unique' => ':attribute đã tồn tại.',
    'confirmed' => ':attribute xác nhận không khớp.',
    'regex' => ':attribute không đúng định dạng.',
    // 'password' => [
    //     'letters' => 'Mật khẩu phải chứa ít nhất một chữ cái.',
    //     'mixed' => 'Mật khẩu phải có cả chữ hoa và chữ thường.',
    //     'numbers' => 'Mật khẩu phải chứa ít nhất một số.',
    //     'symbols' => 'Mật khẩu phải chứa ít nhất một ký tự đặc biệt.',
    // ],

    'attributes' => require __DIR__ . '/attributes.php',
];

