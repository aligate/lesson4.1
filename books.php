<?php
header ("Content-Type: text/html; charset=utf-8");
try 
{
	$pdo = new PDO('mysql:host=localhost;dbname=global', 'root', '');
}
catch (PDOException $e) 
{
	echo "Невозможно подключиться к Базе данных";
}
	$array = [];
	if( strpos( $_SERVER['REQUEST_URI'], '?') === false )
	{
		$query = "SELECT * FROM books";
		$stmt = $pdo->query($query);
		$array = $stmt->fetchAll();
	}
	else
	{
	$request = $_GET;
	$query = "SELECT * FROM books WHERE name = :name OR isbn= :isbn OR author= :author";
	$stmt = $pdo -> prepare ( $query );
	$stmt -> execute ( ['name' => $request['name'], 'isbn' => $request['isbn'], 'author' => $request['author'] ] );
	if( $stmt -> rowCount () === 0 ) header ('Location:'.$_SERVER['PHP_SELF']);
	$array = $stmt -> fetchAll();
	
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
<form>
    <input type="text" name="isbn" placeholder="ISBN" value="<?= isset($request['isbn'])? $request['isbn']: null ; ?>" />
    <input type="text" name="name" placeholder="Название книги" value="<?= isset($request['name'])? $request['name']: null ; ?>" />
    <input type="text" name="author" placeholder="Автор книги" value="<?= isset($request['author'])? $request['author']: null ; ?>" />
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
	<?php foreach ( $array as $arr =>$item ) : ?>
<tr>
  <td><?= $item['name']?></td>
  <td><?= $item['author']?></td>
  <td><?= $item['year']?></td>
  <td><?= $item['genre']?></td>
  <td><?= $item['isbn']?></td>
</tr>
	<?php endforeach; ?>
	
</table>
</body>
</html>
