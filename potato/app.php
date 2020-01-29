<?php


$response = array(); // сюда будем писать то что будем возвращать скрипту

$field1 = isset($_POST['entry1']) ? $_POST['entry1'] : false; // сунем каждое поле в отдельную переменную
$field2 = isset($_POST['entry2']) ? $_POST['entry2'] : false;
$field3 = isset($_POST['entry3']) ? $_POST['entry3'] : false;
$field4 = isset($_POST['entry4']) ? $_POST['entry4'] : false;


// сюда можно положить всякие проверки полей и капчу например
if (!$field1 || !$field2) { // в моем случае надо чтобы первые 2 поля не были пустыми
    $response['ok'] = 0; // пишем что все плохо
    $response['message'] = '<p class="error">Не заполнены первые два поля.</p>'; // пишем ответ
    die(json_encode($response)); //выводим массив в json формате и умираем
}

// теперь подготовим данные для отправки в гугл форму
$url = 'https://docs.google.com/forms/d/e/1FAIpQLSfRRWMJpvbMkjQZVlwAyjg1-JEn4oeu4RWwEglZ0d-d62Wafw/formResponse?embedded=true'; // куда слать, это атрибут action у гугл формы 
$data = array(); // массив для отправки в гугл форм
$data['entry.1413905019'] = $field1; // указываем соответствия полей, ключи массива это нэймы оригинальных полей гуглформы
$data['entry.659055744'] = $field2;
$data['entry.1899051778'] = $field3;
$data['entry.1696887288'] = $field4;
$data['entry.635644082'] = 'Собственный HTML+jQuery+PHP'; // это наше скрытое поле


$data = http_build_query($data); // теперь сериализуем массив данных в строку для отправки


$options = array( // задаем параметры запроса
    'http' => array(
        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
        'method'  => 'POST',
        'content' => $data,
    ),
);
$context  = stream_context_create($options); // создаем контекст отправки
$result = file_get_contents($url, false, $context); // отправляем

if (!$result) { // если что-то не так
    $response['ok'] = 0; // пишем что все плохо
    $response['message'] = '<p class="error">Что-то пошло не так, попробуйте отправить позже.</p>'; // пишем ответ
    die(json_encode($response)); //выводим массив в json формате и умираем
}

$response['ok'] = 1; // если дошло до сюда, значит все ок
$response['message'] = '<p class="">Все ок, отправилось.</p>'; // пишем ответ
die(json_encode($response)); //выводим массив в json формате и умираем

?>




<?php 

$sendto   = "Vkoska520@gmail.com"; // почта, на которую будет приходить письмо
$username = $_POST['entry.659055744'];   // сохраняем в переменную данные полученные из поля c именем
$usertel = $_POST['entry.1413905019']; // сохраняем в переменную данные полученные из поля c телефонным номером
$usermail = $_POST['entry.1899051778']; // сохраняем в переменную данные полученные из поля c адресом электронной почты
$usertext = $_POST['entry.1696887288'];

// Формирование заголовка письма
$subject  = "Новое сообщение";
$headers  = "From: " . strip_tags($usermail) . "\r\n";
$headers .= "Reply-To: ". strip_tags($usermail) . "\r\n";
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: text/html;charset=utf-8 \r\n";

// Формирование тела письма
$msg  = "<html><body style='font-family:Arial,sans-serif;'>";
$msg .= "<h2 style='font-weight:bold;border-bottom:1px dotted #ccc;'>Cообщение с сайта</h2>\r\n";
$msg .= "<p><strong>От кого:</strong> ".$username."</p>\r\n";
$msg .= "<p><strong>Почта:</strong> ".$usermail."</p>\r\n";
$msg .= "<p><strong>Телефон:</strong> ".$usertel."</p>\r\n";
$msg .= "<p><strong>Текст:</strong> ".$usertext."</p>\r\n";
$msg .= "</body></html>";

// отправка сообщения
@mail($sendto, $subject, $msg, $headers) ;

?>
