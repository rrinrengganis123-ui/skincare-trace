<?php

function cek_admin_akses()
{
    $session = session();

    
    if (!$session->get('isLoggedIn')) {
        header('Location: /login');
        exit;
    }

    if ($session->get('role') !== 'admin') {
        header('Location: /access-denied');
        exit;
    }
}