
<?php 
session_start();

 if(isset($_POST['login'])){
        $user=$_POST['username'];
        $pass=$_POST['password'];
}

 $servername = "localhost";
    
//Database name
$dbname = "id13163525_esp_data";
//Database user
$username = "root";
//Database user password
$password = "";

$link = mysqli_connect("$servername","$username","$password") or die ("khong the ket noi den CSDL MYSQL");
    mysqli_select_db($link,"$dbname");
	$sql = "select * from admin";
	
	$result = mysqli_query($link,$sql);
	
	$d = 0;
	while ($row = mysqli_fetch_array($result)) {
		$id = $row["id"];
		$username = $row['username'];
		$password = $row['password'];
		echo md5($pass) .'</br>';
        echo 'MD5: <b>' . $password . '</b><br/>';
		if ( ($user == $username) && (md5($pass) == $password) ) {	      		
		echo "<tr> <td>$id</td> <td>$username</td> <td>$password</td> </tr> dung roi ";
		$d++;	
		break;			
		}
	}
	if ($d==0 ) {
		echo "wrong username or password,please try again";
	    header("Location: login.php");
	 } else {
		$_SESSION['name'] = $_POST['username'];
        echo  $_SESSION['name'];
	 	header("Location: trangchu.php");
	 }      
?>	
