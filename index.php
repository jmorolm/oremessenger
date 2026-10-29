<?php
session_start();
$host="127.0.0.1";
$port="33060";
$dbname="oremessenger";
$root="root";
$pass="ejemplo_pass";
$username=$_POST['user']??" ";
$contrasena=$_POST['pass']?? " ";
$messages=[];


try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname;charset=utf8", $root, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
}catch (PDOException $e){
    die("Error conexion BD: " . $e->getMessage());
}
$stmt = $pdo->prepare("SELECT * FROM person WHERE username=? AND password=?");
$stmt->execute([$username, $contrasena]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
$stmt=$pdo->prepare("SELECT * FROM message WHERE username=? ORDER BY created_at DESC");
$stmt->execute([$username]);
$messages=$stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Document</title>
</head>
<body>
<?php if($_SERVER['REQUEST_METHOD']=='GET'){

echo'
<form method="post">
    <label for="user">Nombre de usuario</label>
    <input type="text" name="user"><br>
    <label for="user">Contraseña</label>
    <input type="password" name="pass"><br>
    <button type="submit" name="enviar" value="ok">Enviar</button>
</form>
';
}else if ($_SERVER['REQUEST_METHOD']=='POST'){
    if(count($messages)==0){
        echo '<p>No hay mensajes</p>';
    }else{
        echo '<table border="1">';
        foreach($messages as $message){
            echo '<tr>';
            echo '<td>'.$message['username'].'</td>';
            echo '<td>'.$message['body'].'</td>';
            echo '<td>'.$message['created_at'].'</td>';
            echo '</tr>';

        }
        echo '</table>';
    }
}

?>

</body>
</html>
