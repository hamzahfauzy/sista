<?php

if(request() == 'POST')
{
    $conn  = conn();
    $db    = new Database($conn);

    // save application installation
    $db->insert('application',$_POST['app']);

    // create user login
    $_POST['users']['name'] = "Admin ".$_POST['app']['name'];
    $_POST['users']['password'] = md5($_POST['users']['password']);
    $user = $db->insert('users',$_POST['users']);

    // create roles
    $role = $db->insert('roles',[
        'name' => 'administrator'
    ]);

    // assign role to user
    $db->insert('user_roles',[
        'user_id' => $user->id,
        'role_id' => $role->id
    ]);

    // create roles route
    $role = $db->insert('role_routes',[
        'role_id' => $role->id,
        'route_path' => '*'
    ]);

    $roles = [
        'pembina kecamatan' => [
            'default/index',
            'default/profile',
            'default/kecamatan',
            'default/kelurahan',
            'default/lingkungan',
            'survey/index',
            'survey/view',
            'crud/index?table=kelurahan',
            'crud/edit?table=kelurahan',
            'crud/delete?table=kelurahan',
            'crud/index?table=penduduk',
            'crud/index?table=indikator',
        ],
        'admin puskesmas' => [
            'default/index',
            'default/profile',
            'default/kecamatan',
            'default/kelurahan',
            'default/lingkungan',
            'survey/index',
            'survey/view',
            'crud/index?table=kelurahan',
            'crud/index?table=lingkungan',
            'crud/index?table=penduduk',
            'crud/index?table=indikator',
        ],
        'pembina kelurahan' => [
            'default/index',
            'default/profile',
            'default/kelurahan',
            'default/lingkungan',
            'survey/index',
            'survey/view',
            'crud/index?table=lingkungan',
            'crud/edit?table=lingkungan',
            'crud/delete?table=lingkungan',
            'crud/index?table=penduduk',
            'crud/edit?table=penduduk',
            'crud/delete?table=penduduk',
            'crud/index?table=indikator',
        ],
        'petugas lapangan' => [
            'default/index',
            'default/profile',
            'default/kecamatan',
            'default/kelurahan',
            'default/lingkungan',
            'crud/index?table=kelurahan',
            'crud/index?table=lingkungan',
            'crud/index?table=penduduk',
            'survey/*',
        ],
        'pembina kabupaten' => [
            'default/*',
            'survey/index',
            'survey/view',
        ]
    ];

    foreach($roles as $role_name => $routes)
    {
        $role = $db->insert('roles',[
            'name' => $role_name
        ]);
        foreach($routes as $route)
        {
            $db->insert('role_routes',[
                'role_id' => $role->id,
                'route_path' => $route
            ]);
        }
    }

    set_flash_msg(['success'=>'Instalasi Berhasil']);
    header('location:'.routeTo('auth/login'));
    die();

}