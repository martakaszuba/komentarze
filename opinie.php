<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
    <title>Komentarze na temat portfolio</title>
</head>
<body>
<div id="top">
    <form method="post">
    <h5>Dodaj komentarz na temat portfolio</h5><br>
    <p>Firma/Imię: <input type="text" name="author"></p>
    <p>Jak oceniasz portfolio w skali 0-10: <input type="number" name="rate"></p>
    <p>Wpisz wady portfolio (nad czym powinnam popracować): <input type="text" name="flaws" size=28></p>
    <p>Wpisz zalety portfolio (co Ci się spodobało): <input type="text" name="adv" size=28></p>
    <button name="submit" class="btn btn-primary">Zatwierdź</button>
    </form><br>
    <p id="err">
    <?php
    if (isset($_POST["submit"])){
        $author = $_POST["author"];
        $author = trim($author);
        $rate = $_POST["rate"];
        $rate = trim($rate);
        $rate = intval($rate);
        $flaws = $_POST["flaws"];
        $flaws = trim($flaws);
        $adv = $_POST["adv"];
        $adv = trim($adv);
        if (strlen($author) === 0){
            echo "Wpisz firmę/imię";
        }
        else if (strlen($author)>50){
            echo "Za długa nazwa!";
        }
        else if (!is_int($rate)){
            echo "Wpisz liczbę całkowitą!";
        }
        else if ($rate<0 || $rate>10){
            echo "Wpisz lczbę od 0 do 10!";
        }

        else if (strlen($flaws) === 0){
            echo "Wpisz wady portfolio!";
        }
        else if (strlen($flaws)>120){
            echo "Za długi tekst!";
        }

        else if (strlen($adv) === 0){
            echo "Wpisz zalety portfolio!";
        }
        else if (strlen($adv)>120){
            echo "Za długi tekst!";
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
    <h5 class="rate">Średnia ocena:
    <?php
    $conn2 = new mysqli("localhost", "root", "", "ocena");
    $conn2->set_charset("utf8");
            if ($conn2->connect_error) {
            die("Connection failed: " . $conn2->connect_error);
            }
            $stmt2 = $conn2->prepare("SELECT AVG(rate) as average FROM portfolio");
    $stmt2->execute();
    $result2 = $stmt2->get_result();
    if ($result2->num_rows === 0) {
      echo "";
      die;
    }
    while($row2 = $result2->fetch_assoc()) {
    echo round(($row2["average"]),2);
    }
    $stmt2->close();
    ?>
    </h5>
    <h5 class="rate">Komentarze</h5>
    <?php
    $conn = new mysqli("localhost", "root", "", "ocena");
    $conn->set_charset("utf8");
            if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
            }
            $stmt = $conn->prepare("SELECT * FROM portfolio");
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows === 0) {
      echo "";
      die;
    }
   
    while($row = $result->fetch_assoc()) {
      $author = $row['author'];
      $rate = $row['rate'];
      $faults = $row['faults'];
      $pros = $row['pros'];
      $reasons = $row['reasons'];
      echo "<div class='comment'>";
      echo "<p><span class='w'>Firma/autor: </span>".$author."</p>";
      echo "<p><span class='w'>Ocena portfolio: </span>".$rate."</p>";
      echo "<p><span class='w'>Wady portfolio: </span>".$faults."</p>";
      echo "<p><span class='w'>Zalety portfolio: </span>".$pros."</p>";
      echo "</div>";
    }
    $stmt->close();
    ?>
    </div>
</body>
</html>