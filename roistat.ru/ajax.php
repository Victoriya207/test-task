<link rel="stylesheet" href="style.css?11">
<body style = "background-color: #e7d7c1;">
<div class = "ajax">
<?php
require 'vendor/autoload.php';

use AmoCRM\Client\AmoCRMApiClient;
use AmoCRM\Client\LongLivedAccessToken;
use AmoCRM\Models\ContactModel;
use AmoCRM\Models\CustomFieldsValues\MultitextCustomFieldValuesModel;
use AmoCRM\Models\CustomFieldsValues\ValueCollections\MultitextCustomFieldValueCollection;
use AmoCRM\Models\CustomFieldsValues\ValueModels\MultitextCustomFieldValueModel;
use AmoCRM\Models\CustomFieldsValues\TextCustomFieldValuesModel;
use AmoCRM\Models\CustomFieldsValues\ValueCollections\TextCustomFieldValueCollection;
use AmoCRM\Models\CustomFieldsValues\ValueModels\TextCustomFieldValueModel;
use AmoCRM\Collections\ContactsCollection;
use AmoCRM\Collections\CustomFieldsValuesCollection;
use AmoCRM\Models\LeadModel;

$name = $_POST['name'];
$mail = $_POST['mail'];
$phone = $_POST['phone'];
$price = $_POST['price'];
$timeSpent = $_POST['timeSpent'];

$errors = [];

if(empty($name)) {
    $errors[] = "<h4>Поле 'Имя' обязательно для заполнения.</h4>";
}
if(empty($mail)) {
    $errors[] = "<h4>Поле 'Email' обязательно для заполнения.</h4>";
}
if(empty($phone)) {
    $errors[] = "<h4>Поле 'Телефон' обязательно для заполнения.</h4>";
}
if(empty($price)) {
    $errors[] = "<h4>Поле 'Цена' обязательно для заполнения.</h4>";
}


if(!empty($errors)) {
    echo "<h2>Вы ввели не все данные, попробуйте <a href='javascript:history.back(1);'> ещё раз! </a></h2>";
    foreach($errors as $error) {
        echo $error;
    }
    die;
}

$apiClient = new AmoCRMApiClient();
$access_token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6ImMxYzVhODIzNmI0NjkzNzYwNDNkZWU1YThlMDkxYTJkMzQ1ZGRiMzRkMTg1MWIwZDQ3OWZhYmExYmQ3NzE2MjRjNTVmMjk4Y2RmMWU3OTJlIn0.eyJhdWQiOiI4M2NlOGVlNi0yODZmLTQ3ZmEtYjUwZC0zYzk2OWUyNWY4MTEiLCJqdGkiOiJjMWM1YTgyMzZiNDY5Mzc2MDQzZGVlNWE4ZTA5MWEyZDM0NWRkYjM0ZDE4NTFiMGQ0NzlmYWJhMWJkNzcxNjI0YzU1ZjI5OGNkZjFlNzkyZSIsImlhdCI6MTcyNDc1MDUxNywibmJmIjoxNzI0NzUwNTE3LCJleHAiOjE3MzU2ODk2MDAsInN1YiI6IjExNDM5ODUwIiwiZ3JhbnRfdHlwZSI6IiIsImFjY291bnRfaWQiOjMxOTE2NTE4LCJiYXNlX2RvbWFpbiI6ImFtb2NybS5ydSIsInZlcnNpb24iOjIsInNjb3BlcyI6WyJjcm0iLCJmaWxlcyIsImZpbGVzX2RlbGV0ZSIsIm5vdGlmaWNhdGlvbnMiLCJwdXNoX25vdGlmaWNhdGlvbnMiXSwiaGFzaF91dWlkIjoiYTg3Yzk3MTYtNGMzOC00NThmLWFlZGMtNGQ3NjNjMmM5N2I2IiwiYXBpX2RvbWFpbiI6ImFwaS1iLmFtb2NybS5ydSJ9.JZFF7r4HcbeJH0BU7K_FSMR_T45Sz5uE4U4MgK33cjeU0ElBQtJqMJmybyymc8W1GV6kdsQRT__1e_nJEOV73nJO-De0Sc3yGgnvyZ21et1mtVV1Lq5eDgrzsdPnaUWOmxcIh2mYjoPatUOGewE5LPbkt2K9sYR1Pv8Lxi0UZF56XZtQnD_nMCEgBVFQfZ5InLnpzNUxtcnsjNvjqsuBU3Ru669cjdGfWzHN2WfTnsdgEGhKYHuzf4p4CHgDVve-yX4LuS6AdfHkb3JEmDa5xderuRcDgT7FKR-70unglBwaDdhxOSpsUpXfjRWCq1dwa81XjHk8NFDFDWsI_HzXpg';
$longLivedAccessToken = new LongLivedAccessToken($access_token);

$apiClient->setAccessToken($longLivedAccessToken)
    ->setAccountBaseDomain('vika20072003.amocrm.ru');

try {
    $contact = new ContactModel(); // создание контакта
    $contact->setName($name);
    
    $customFieldsValuesCollection = new CustomFieldsValuesCollection(); // создание коллекции для кастомных полей

    $phoneField = new MultitextCustomFieldValuesModel(); // поле для телефона
    $phoneField->setFieldId(668805);
    $phoneField->setFieldCode('PHONE'); 
    $phoneField->setFieldName('Телефон');

    $phoneValues = new MultitextCustomFieldValueCollection();
    $phoneValue = new MultitextCustomFieldValueModel();
    $phoneValue->setValue($phone);
    $phoneValue->setEnumId(656279);
    $phoneValue->setEnum('WORK');

    $phoneValues->add($phoneValue);
    $phoneField->setValues($phoneValues);

    $customFieldsValuesCollection->add($phoneField);

    $emailField = new MultitextCustomFieldValuesModel(); // поле для email
    $emailField->setFieldId(668807); 
    $emailField->setFieldCode('EMAIL'); 
    $emailField->setFieldName('Email'); 
    
    $emailValues = new MultitextCustomFieldValueCollection();
    $emailValue = new MultitextCustomFieldValueModel();
    $emailValue->setValue($mail);
    $emailValue->setEnumId(656291); 
    $emailValue->setEnum('WORK'); 

    $emailValues->add($emailValue);
    $emailField->setValues($emailValues);
    
    $customFieldsValuesCollection->add($emailField);

    $contact->setCustomFieldsValues($customFieldsValuesCollection);

    $contact = $apiClient->contacts()->addOne($contact); // добавление контакта в amoCRM

    $contactId = $contact->getId(); // id только что созданного контакта
    
    
    $deal = new LeadModel();

    $deal->setName("Заявка от {$name}")
    ->setPrice($price)
    ->setContacts(
        (new ContactsCollection())
            ->add(
                (new ContactModel())
                    ->setId($contactId)
            )
    );

    $customFieldsValuesCollection1 = new CustomFieldsValuesCollection(); // создание коллекции для кастомных полей

    $timer = new TextCustomFieldValuesModel(); // настройка кастомного поля "Таймер"
    $timer->setFieldId(679211);
    $timer->setFieldName('Таймер'); 

    $timerValues = new TextCustomFieldValueCollection();
    $newTimerValue = new TextCustomFieldValueModel();
    $newTimerValue->setValue($timeSpent);

    $timerValues->add($newTimerValue);
    $timer->setValues($timerValues);

    $customFieldsValuesCollection1->add($timer);

    $deal->setCustomFieldsValues($customFieldsValuesCollection1);

    $deal = $apiClient->leads()->addOne($deal); // сохранение сделки с привязанным контактом

    echo "<h2>Заявка успешно создана!</h2>"
        . "<h4><a href='javascript:history.back(1);'> Назад </a></h4>";

    
} 
catch (Exception $e) {
    echo 'Ошибка: ' . $e->getMessage();
}

?>
</div>
</body>