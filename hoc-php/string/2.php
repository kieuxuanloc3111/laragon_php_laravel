<?php
echo implode(' ', ['hello', 'xin', 'chào', 'bạn']);
echo strlen('hello.net');
echo htmlentities('<b>hello.net</b>');
$arr = ['name' => 'Cường', 'age' => 29];
echo json_encode($arr);
$json = '{"name":"Cường","age":29}';
$data = json_decode($json, true);
print_r($data);
?>
