<?php

require_once 'vendor/autoload.php';
require_once 'model/UserModel.php';
require_once 'model/PromocodeModel.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Snippet 1: Read existing user from Db
//$u1 = new UserModel(1); // Loads user with id=1
//var_dump($u1->isExists()); // true (if row exists in db)
//var_dump($u1->fullname); // John Doe
//var_dump($u1->email); // johndoe@example.com
//var_dump($u1->password); // password hash
//var_dump($u1->passwordVerify('123123')); // true


// Snippet 2: Read unexisting user from Db
//$u123 = new UserModel(123);
//var_dump($u123->isExists()); // false
//var_dump($u123->fullname); // null
//var_dump($u123->email); // null
//var_dump($u123->password); // null


// Snippet 3: Create new user record
//$u = new UserModel();
//$u->fullname = 'Jane Doe';
//$u->email = 'janedoe@example.com';
//$u->password = UserModel::passwordHash('qwerty');
//$u->paid_to = date('2022-10-30 20:50:00');
//$u->save();


// Snippet 4: Load promocode and apply it
//$p = (new PromocodeModel())->getPromocodeByPromocode('FREE');
//$u = new UserModel(1);
//$result = $p->applyPromocode($u);
//var_dump($result); // true?
