<?php

$route = get_route();

if(startWith($route,'default/landing')) return true;

// if(startWith($route,'default/riwayat')) return true;

// if(startWith($route,'default/download')) return true;

if(startWith($route,'auth/register')) return true;

if(startWith($route,'default/rekapitulasi')) return true;

if(startWith($route,'app/db-')) return true;

if(startWith($route,'api'))
{

    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Headers: *");
    header("Access-Control-Allow-Methods: *");
    if(!startWith($route,'api/rekapitulasi'))
    {
        header("Content-Type: application/json");
    }
    return true;
}

// check if installation is exists
$conn  = conn();
$db    = new Database($conn);

$installation = $db->single('application');
if(!$installation && $route != "installation")
{
    header("location:".routeTo('installation'));
    die();
}

$auth = auth();
if(!isset($auth->user) && !in_array($route, ['auth/login','installation']))
{
    header("location:".routeTo('auth/login'));
    die();
}

if(isset($auth->user) && !isset($auth->user->id) && $route != 'auth/logout')
{
    header("location:".routeTo('auth/logout'));
    die();
}

if(isset($auth->user) && $route == 'auth/login')
{
    if(get_role($auth->user->id)->name == 'penduduk')
    {
        header("location:".routeTo('default/riwayat',['nik'=>$auth->user->username]));
    }
    else
    {
        header("location:".routeTo('default/index'));
    }
    die();
}

// check if route is allowed
if(isset($auth->user) && isset($auth->user->id) && !is_allowed($route, $auth->user->id) && $route != 'auth/logout')
{
    return false;
}

return true;