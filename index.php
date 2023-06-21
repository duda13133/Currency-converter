<html>
<head>
</head>
<?php
$sql = "SELECT * FROM kursy LIMIT 13";
$dbhost = "localhost";
$dbuser = "root";
$dbpass = "";
$dbname = "NBP";
$conn = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$query2="DELETE FROM kursy";
$result=$conn->query($query2);
$nbp = file_get_contents('https://api.nbp.pl/api/exchangerates/tables/C?format=json/');
$dane = json_decode($nbp,true);
$date=$dane[0]['effectiveDate'];
    for ($x = 0; $x < 12; $x++) {
        $id=$x;
        $code = $dane[0]['rates'][$x]['code'];
        $currency = $dane[0]['rates'][$x]['currency'];
        $bid = $dane[0]['rates'][$x]['bid'];
        $ask = $dane[0]['rates'][$x]['ask'];
$sql1 = "INSERT INTO `kursy`(`id`,`code`,`currency`,`effective_date`,`bid`,`ask`) VALUES ('$id','$code', '$currency', '$date', '$bid', '$ask')";
        if ($conn->query($sql1) === TRUE) {
            //echo "New record created successfully";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }

    }

    $sql2 = "Insert into `kursy`(`id`,`code`,`currency`,`effective_date`,`bid`,`ask`) values ('12','PLN', 'polski złoty','$date','1','1')";
    if ($conn->query($sql2) === TRUE) {
        //echo "New record created successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }


    ?>
</select>
<body>
<form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
    Podaj ilość waluty docelowej <input type="number" name="fname" step=".01" >
    <br>
</body>
<?php

echo "<tr><th> Wybierz walutę docelową: </th></tr>";
$query="SELECT * FROM kursy LIMIT 13";
$result=$conn->query($query);
if ($result->num_rows > 0) {
    $options=mysqli_fetch_all($result,MYSQLI_ASSOC) ;

}
?>
    <form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
            <select name="currencyName">
<?php
                foreach ($GLOBALS['options'] as $option) {
                    ?>

                    <option><?php echo $GLOBALS['option']['currency']; ?> </option>
                    <?php

                    }

                ?>
            </select>



<body>
<form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
<input type="submit" name="button" value="Przewalutuj">
<br>Wynik:
</body>
    </html>
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $liczba1 = $_POST['fname'];
$currency=$_POST['currencyName'];
$sql= "Select * from kursy order by id";
    foreach ($conn->query($sql) as $row){
if($currency==$row['currency']) {
    $liczba2 =$row['ask'];
    $wynik=$liczba1*$liczba2;
    echo $wynik;
    echo 'zł';
    $sql1="Insert into `historia` (`currency`,`ask`,`liczba`,`wynik`)values ('$currency','$liczba2','$liczba1','$wynik')";
    if ($conn->query($sql1) === TRUE) {
        //echo "New record created successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
}
}


echo "</select>";
echo'<table>';
echo "<tr><th> currency </th> <th> ask </th> <th> liczba(zł) </th> <th> Wynik </th></tr>";
$sql1= "Select * from historia";
foreach ($conn->query($sql1) as $row){
        echo '<tr>';
        echo '<td>'.$row['currency'].'</td>';
        echo '<td>'.$row['ask'].'</td>';
        echo '<td>'.$row['liczba'].'</td>';
        echo '<td>'.$row['wynik'].'</td>';
        echo '</tr>';
    echo '</echo>';
    }


echo "</select>";
echo'<table>';
echo "<tr> <th> id </th> <th> code </th> <th> currency </th> <th> effective_date </th><th> bid </th><th> ask </th> </tr>";
$sql= "Select * from kursy order by id";
foreach ($conn->query($sql) as $row){

    echo '<tr>';
    echo '<td>'. $row['id'] .'</td>';
    echo '<td>'. $row['code'] . '</td>';
    echo '<td>'. $row['currency'] . '</td>';
    echo '<td>'. $row['effective_date'] . '</td>';
    echo '<td>'. $row['bid'] . '</td>';
    echo '<td>'. $row['ask'] . '</td>';
    echo '</tr>';
    echo '</echo>';
}


$conn->close();


