<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <title>Komentarze na temat portfolio</title>
</head>
<body>
<div id="top">
    <form method="post">
    <h5>Dodaj komentarz na temat portfolio</h5><br>
    <p class="info">Firma/Imię: <input type="text" name="author"></p>
    <p class="info">Jak oceniasz portfolio w skali 1-10: <input type="number" name="rate"></p>
    <p class="info">Wpisz wady portfolio (nad czym powinnam popracować): <input type="text" name="flaws" size=28></p>
    <p class="info">Wpisz zalety portfolio (co Ci się spodobało): <input type="text" name="adv" size=28></p>
    <button name="submit" class="btn btn-light">Zatwierdź</button>
    </form><br>
    <p id="err">
    <?php
    if (isset($_POST["submit"])){
        $author = $_POST["author"];
        $author = trim($author);
        $author = htmlspecialchars($author);
        $rate = $_POST["rate"];
        $rate = trim($rate);
        $rate = intval($rate);
        $flaws = $_POST["flaws"];
        $flaws = trim($flaws);
        $flaws = htmlspecialchars($flaws);
        $adv = $_POST["adv"];
        $adv = trim($adv);
        $adv = htmlspecialchars($adv);

        if (strlen($author) === 0){
            echo "Wpisz firmę/imię!";
        }
        else if (strlen($author)>50){
            echo "Za długa nazwa!";
        }
        else if (!is_int($rate)){
            echo "Wpisz liczbę od 1 do 10!";
        }
        else if ($rate<1 || $rate>10){
            echo "Wpisz liczbę od 1 do 10!";
        }

        else if (strlen($flaws) === 0){
            echo "Wpisz wady portfolio!";
        }
        else if (strlen($flaws)>140){
            echo "Za długi tekst skróć do 140 znaków!";
        }

        else if (strlen($adv) === 0){
            echo "Wpisz zalety portfolio!";
        }
        else if (strlen($adv)>140){
            echo "Za długi tekst skróć do 140 znaków!";
        }

        else {
        $conn = new mysqli("localhost", "root", "", "ocena");
		$conn->set_charset("utf8");
		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}
		$sql ="INSERT INTO portfolio (author, rate, faults, pros) VALUES (?,?,?,?)";
		$stmt = $conn->prepare($sql);
		$stmt->bind_param("siss", $author, $rate, $flaws, $adv);
		$stmt->execute();
		$stmt->close();
		$conn->close();
        }
    }
    ?>
    
    </p>
<?php
?>
    </div>
    <div id="comments">
    <h5 class="rate">Średnia ocena portfolio:
    <?php
    $conn2 = new mysqli("localhost", "root", "", "ocena");
    $conn2->set_charset("utf8");
    if ($conn2->connect_error){
    die("Connection failed: " . $conn2->connect_error);
    }
    $stmt2 = $conn2->prepare("SELECT AVG(rate) as average FROM portfolio");
    $stmt2->execute();
    $result2 = $stmt2->get_result();
    if ($result2->num_rows === 0){
      echo "";
      die;
    }
    while($row2 = $result2->fetch_assoc()) {
    echo round(($row2["average"]),2);
    }
    $stmt2->close();
    ?>
    </h5>
    <h5 class="rate">Liczba wyświetleń tej strony:
    <?php 
    $conn3 = new mysqli("localhost", "root", "", "ocena");
    $conn3->set_charset("utf8");
    if ($conn3->connect_error){
    die("Connection failed: " . $conn3->connect_error);
    }
    $stmt3 = $conn3->prepare("SELECT count FROM count");
    $stmt3->execute();
    $result3 = $stmt3->get_result();
    if ($result3->num_rows === 0){
      echo "";
      die;
    }
   
    while ($row3 = $result3->fetch_assoc()){
        $count = $row3['count'];
        $count++;
    }
    echo $count;
    $sql2="UPDATE count SET count =$count";
		$stmt3 = $conn3->prepare($sql2);
		$stmt3->execute();
		$stmt3->close();
		$conn3->close();
    ?>

    </h5>
    <h5 class="com">Komentarze</h5>
    <?php
    $conn = new mysqli("localhost", "root", "", "ocena");
    $conn->set_charset("utf8");
            if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
            }
            $stmt = $conn->prepare("SELECT * FROM portfolio");
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows === 0){
      echo "";
      die;
    }
   
    while ($row = $result->fetch_assoc()) {
      $author = $row['author'];
      $rate = $row['rate'];
      $faults = $row['faults'];
      $pros = $row['pros'];
      $reasons = $row['reasons'];
      echo "<div class='comment'>";
      echo "<p><span class='w'>Firma/Imię: </span>".$author."</p>";
      echo "<p><span class='w'>Ocena portfolio: </span>".$rate."</p>";
      echo "<p><span class='w'>Wady portfolio: </span>".$faults."</p>";
      echo "<p><span class='w'>Zalety portfolio: </span>".$pros."</p>";
      echo "</div>";
    }
    $stmt->close();
    $conn->close();
    ?>
    </div>
</body>
</html>