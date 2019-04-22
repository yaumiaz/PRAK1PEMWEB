<?php
$hari = date("d");

var_dump($hari);

if ($hari < "10"){
    echo "awal bulan";
}

else if ($hari > "20"){
    echo "akhir bulan";
}

else {
    echo "pertengahan bulan";
}