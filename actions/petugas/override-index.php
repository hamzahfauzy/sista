<?php

$data = $db->all($table,[],[
    'id' => 'desc'
]);

return $data;