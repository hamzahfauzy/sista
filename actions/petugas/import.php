<?php

if(request() == 'POST')
{
    $conn = conn();
    $db   = new Database($conn);

    $role = $db->single('roles',['name' => $_POST['sebagai']]);

    // Open uploaded CSV file with read-only mode
    $csvFile = fopen($_FILES['file']['tmp_name'], 'r');

    // Skip the first line
    fgetcsv($csvFile);

    // Parse data from CSV file line by line
    while(($line = fgetcsv($csvFile)) !== FALSE){
        // 0 -> no, 1 -> Instansi, 2 -> username
        if($_POST['sebagai'] == 'pembina kabupaten')
        {
            $name = ucwords(strtolower($line[1]));
            $arrName = explode(' ',$name);
            $frontName = $arrName[0];
    
            unset($arrName[0]);
            $backName = implode('',$arrName);
            $username = $frontName.'_'.strtolower($backName);
    
            $user = $db->insert('users', [
                'name' => $name,
                'username' => $username,
                'password' => md5(123456)
            ]);
    
            $db->insert('user_roles',[
                'user_id' => $user->id,
                'role_id' => $role->id,
            ]);
    
            $db->insert('petugas',[
                'user_id' => $user->id,
                'kecamatan_id' => 0,
                'kelurahan_id' => 0,
                'NIK' => strtotime('now') . $user->id,
                'nama' => $name,
                'alamat' => '-',
                'jenis_kelamin' => 'Laki-laki',
                'no_hp' => strtotime('now') . $user->id,
                'email' => strtotime('now') . $user->id.'@pasta.com'
            ]);
        }

        if($_POST['sebagai'] == 'pembina kecamatan')
        {
            $kecamatan = $db->single('kecamatan',['nama' => ['LIKE','%'.$line[1].'%']]);
            
            if($kecamatan)
            {
                $username = $line[2];
                $user = $db->insert('users', [
                    'name' => $username,
                    'username' => $username,
                    'password' => md5(123456)
                ]);
    
                $db->insert('user_roles',[
                    'user_id' => $user->id,
                    'role_id' => $role->id,
                ]);
        
                $db->insert('petugas',[
                    'user_id' => $user->id,
                    'kecamatan_id' => $kecamatan->id,
                    'kelurahan_id' => 0,
                    'NIK' => strtotime('now') . $user->id,
                    'nama' => $username,
                    'alamat' => '-',
                    'jenis_kelamin' => 'Laki-laki',
                    'no_hp' => strtotime('now') . $user->id,
                    'email' => strtotime('now') . $user->id.'@pasta.com'
                ]);
            }

        }
    }

    // Close opened CSV file
    fclose($csvFile);

    set_flash_msg(['success'=>'Petugas berhasil di Import']);
    header('location:'.routeTo('crud/index',['table'=>'petugas']));
    die();
}