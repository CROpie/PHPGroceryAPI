<?php

function handleLogout() {
    session_start();
    session_unset();
    session_destroy();
}