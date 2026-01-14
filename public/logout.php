<?php
require __DIR__ . '/../app/auth.php';
require __DIR__ . '/../app/helpers.php';

logout_user();
redirect('/index.php');
