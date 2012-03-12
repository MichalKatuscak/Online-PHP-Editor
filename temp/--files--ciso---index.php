<?php
include_once ("./header.php");

if (!$_POST["submit"] OR $_POST["old"] == ""){
	echo <<<END
<form action="#" method="post">
Číslo <input name="old" size="30"> v
<select name="old_sous">
END;
for($i=2;$i<=16;$i++){
	echo "<option value='$i'";
	if ($i == 10) echo " SELECTED";
	echo ">$i</option>";
}
echo <<<END
</select> soustavě převést na 
<select name="sous">
END;
for($i=2;$i<=16;$i++){
	echo "<option value='$i'>$i</option>";
}
echo <<<END
</select>
soustavu.
 <input type="submit" name="submit" value="Start">
</form>
END;
} else {
$des = from_CiSo($_POST["old"],$_POST["old_sous"]);
$new = to_CiSo($des,$_POST["sous"]);
echo "$_POST[old] bylo převedeno z $_POST[old_sous] soustavy na $_POST[sous] soustavu : <big><b>$new</b></big><br>
<a href='./'>Nový převod</a>
";
}

include_once ("./footer.php");
?>
