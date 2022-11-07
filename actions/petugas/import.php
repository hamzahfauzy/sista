<?php

if(request() == 'POST')
{
    $conn = conn();
    $db   = new Database($conn);
    // Open uploaded CSV file with read-only mode
    $csvFile = fopen($_FILES['file']['tmp_name'], 'r');

    // Skip the first line
    fgetcsv($csvFile);

    // Parse data from CSV file line by line
    while(($line = fgetcsv($csvFile)) !== FALSE){
        $name = ucwords(strtolower($line[0]));
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
            'role_id' => 6,
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



        // Get row data
        // $name   = $line[0];
        // $email  = $line[1];
        // $phone  = $line[2];
        // $status = $line[3];
        
        // // Check whether member already exists in the database with the same email
        // $prevQuery = "SELECT id FROM members WHERE email = '".$line[1]."'";
        // $prevResult = $db->query($prevQuery);
        
        // if($prevResult->num_rows > 0){
        //     // Update member data in the database
        //     $db->query("UPDATE members SET name = '".$name."', phone = '".$phone."', status = '".$status."', modified = NOW() WHERE email = '".$email."'");
        // }else{
        //     // Insert member data in the database
        //     $db->query("INSERT INTO members (name, email, phone, created, modified, status) VALUES ('".$name."', '".$email."', '".$phone."', NOW(), NOW(), '".$status."')");
        // }
    }

    // Close opened CSV file
    fclose($csvFile);

    set_flash_msg(['success'=>'Petugas berhasil di Import']);
    header('location:'.routeTo('crud/index',['table'=>'petugas']));
    die();
}