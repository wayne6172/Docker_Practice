<?php
    function ymdot_write($key, $value) {
        $mysql = getSqlConnection();
        $rs = mysqli_fetch_all(mysqli_query($mysql, "SELECT * FROM `ymdot` WHERE `key` = '$key'"), MYSQLI_ASSOC);
        if($rs) {
            mysqli_query($mysql, "UPDATE `ymdot` SET `value` = '$value' WHERE `key` = '$key'");
        }
        else {
            mysqli_query($mysql, "INSERT INTO `ymdot` (`key`, `value`) VALUES ('$key', '$value')");
        }
        mysqli_close($mysql);
    }

    function ymdot_sync() {
        $mysql = getSqlConnection();
        $rs = mysqli_fetch_all(mysqli_query($mysql, "SELECT * FROM `ymdot`"), MYSQLI_ASSOC);
    
        mysqli_close($mysql);

        $memcache = memcache_connect('ymdot-memcached', 11211);
        memcache_flush($memcache);

        foreach($rs as $row) {
            memcache_set($memcache, $row['key'], $row['value']);
        }
        memcache_close($memcache);
    }

    function ymdot_read($key) {
        $mysql = getSqlConnection();
        $rs = mysqli_fetch_all(mysqli_query($mysql, "SELECT * FROM `ymdot` WHERE `key` = '$key'"), MYSQLI_ASSOC);
        
        $row = reset($rs);
        $dbVal = isset($row['value']) ? $row['value'] : '';

        $memcache = memcache_connect('ymdot-memcached', 11211);
        $memVal = memcache_get($memcache, $key);
        memcache_close($memcache);

        echo '資料庫中的值:'.$dbVal.'<br/>'.'快取中的值:'.$memVal;
    }

    function getSqlConnection() {
        return mysqli_connect('ymdot_mysql', 'root', 'screencast', 'web');
    }

    $action = $_POST['action'];

    switch($action) {
        case 'write':
            ymdot_write($_POST['key'], $_POST['value']);
            break;
        case 'sync':
            ymdot_sync();
            break;
        case 'read':
            ymdot_read($_POST['key']);
            break;
    }
?>