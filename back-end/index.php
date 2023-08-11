<?php

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if ($_COOKIE) {
        $msg = ['msg' => 'cookie ok'];
        echo json_encode($msg);
    } else {
        $msg = ['msg' => 'pas de cookie'];
        echo json_encode($msg);
    }
}
