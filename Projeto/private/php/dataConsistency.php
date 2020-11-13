<?php require($_SERVER['DOCUMENT_ROOT'] . '/Mega-Triunfo/projeto/public/html/partials/privateHead.php') ?>

<?php
$name = $_POST['userForm'] ?? null;
$email = $_POST['emailForm'] ?? null;
$passwordForm = $_POST['passwordForm'] ?? null;
$passwordConf = $_POST['passwordConfForm'] ?? null;

// Evita espaço antes ou depois da senha
$passwordForm = trim($passwordForm);

// E-mail sempre minúsculo
$email = strToLower($email);

// Array com os errros possíveis
$erros = [];

// Chamando Banco
require_once "database/db.php";

// Função de validação do email
function existingEmail(string $emailForm): bool
{

  if (empty($emailForm)) return false;

  global $database;

  $stmt = $database->prepare('SELECT userId FROM userLogin WHERE userEmail = :email');

  $stmt->bindParam(':email', $emailForm);

  $stmt->execute();

  $register = $stmt->fetch();

  return is_numeric($register['userId']) ? true : false;
}


// Verifica se o nome tem mais que dois ou caracteres
if (strlen($name) < 2) {
  $erros[] = "O nome deve contém pelo menos 2 caracteres";
}

// Verifica se o e-mail é válido
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
  $erros[] = 'E-mail inválido';
} else if (existingEmail($email)) {
  $erros[] = 'E-mail já cadastrado';
}

// Verifica se a senha possui mais que 8 caracteres
if (strlen($passwordForm) < 8) {
  $erros[] = "A senha deve contém pelo menos 8 caracteres";
} else if ($passwordForm != $passwordConf) {
  $erros[] = "As senhas não são iguais";
}

//var_dump($erros == NULL);

if ($erros == NULL) {
  include $_SERVER['DOCUMENT_ROOT'] . '/Mega-Triunfo/projeto/private/php/createUserSucsess.php';
}