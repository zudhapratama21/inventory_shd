<?php
$birthDate = new DateTime($tanggal);
$today = new DateTime("today");
$umur = $today->diff($birthDate)->y;
?>

<td>{{$umur}}</td>