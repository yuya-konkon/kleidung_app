<?php

session_start();
$no = $_REQUEST["no"];
$ID =$_SESSION["USERID"];
$sql = "DELETE FROM `favorite` WHERE `fav_user`='.$ID.' AND `id`='.$no.'";