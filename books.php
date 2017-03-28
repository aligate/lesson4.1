<?php
header("Content-Type: text/html; charset=utf-8");
try{
	$pdo = new PDO('mysql:host=localhost;dbname=global', 'root', '');
}
catch (PDOException $e){
	
	echo "Невозможно подключиться к Базе данных";
}
	
	$array = [];
	if(empty( $_GET ))
	{
		
		$query = "SELECT * FROM books";
		$stmt = $pdo->query($query);
		$array = $stmt->fetchAll();
		
	}
	else
	{
	$executeArray = [];
	$query = "SELECT * FROM books WHERE ";
	foreach ( $_GET as $key => $item )
	{
		if ( $_GET[$key] === '')  unset ( $_GET[$key] );
		else 
		{
			$item = trim ( addslashes($item) );
			$executeArray [$key] = "%$item%";
			$query .= "$key LIKE :$key OR ";
		}
	}
	$query = substr($query, 0, -4);
	
	$stmt = $pdo->prepare ( $query );
	$stmt ->execute ( $executeArray );
	if($stmt->rowCount () === 0) header('Location:'.$_SERVER['PHP_SELF']);
	$array = $stmt->fetchAll();
	}
	
?>

<!doctype html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Библиотека</title>
<style>
    table { 
        border-spacing: 0;
        border-collapse: collapse;
    }

    table td, table th {
        border: 1px solid #ccc;
        padding: 5px;
    }
    
    table th {
        background: #eee;
    }
</style>
</head>
<body>
<h1>Библиотека успешного человека</h1>

<form method="GET">
    <input type="text" name="isbn" placeholder="ISBN" value="<?= isset($_GET['isbn']) ? $_GET['isbn']: null; ?>" />
    <input type="text" name="name" placeholder="Название книги" value="<?= isset($_GET['isbn']) ? $_GET['isbn']: null; ?>" />
    <input type="text" name="author" placeholder="Автор книги" value="<?= isset($_GET['isbn']) ? $_GET['isbn']: null; ?>" />
    <input type="submit" value="Поиск" />
</form>

<table>
    <tr>
        <th>Название</th>
        <th>Автор</th>
        <th>Год выпуска</th>
        <th>Жанр</th>
        <th>ISBN</th>
    </tr>
	<?php foreach($array as $arr =>$item):?>
<tr>

  <td><?= htmlspecialchars($item['name']); ?></td>
  <td><?= htmlspecialchars($item['author']); ?></td>
  <td><?= htmlspecialchars($item['year']); ?></td>
  <td><?= htmlspecialchars($item['genre']); ?></td>
  <td><?= htmlspecialchars($item['isbn']); ?></td>
	
</tr>
	<?php endforeach; ?>
	
</table>
</body>
</html>
