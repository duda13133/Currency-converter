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


$query="SELECT * FROM kursy LIMIT 13";
$result=$conn->query($query);
if ($result->num_rows > 0) {
    $options=mysqli_fetch_all($result,MYSQLI_ASSOC) ;
}
    ?>
<form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
    Wybierz walutę źródłową:
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
    <br>
    Podaj ilość waluty źrodłowej <input type="number" name="fname" min="0" step=".01" >
    <br>
</body>
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $currency = $_POST['currencyName'];
    $sql = "Select * from kursy order by id";
    foreach ($conn->query($sql) as $row) {
        if ($currency == $row['currency']) {
            $liczba1 = $_POST['fname'];
            $liczba2=$row['ask'];
            $wynik1=$liczba1*$liczba2;
        }
    }
}
echo "<tr><th> Wybierz walutę docelową: </th></tr>";

?>
    <form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
            <select name="currencyName1">
<?php
                foreach ($GLOBALS['options'] as $option1) {
                    ?>

                    <option><?php echo $GLOBALS['option1']['currency']; ?> </option>
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
$currency2=$_POST['currencyName1'];
$currency=$GLOBALS['currency'];
$sql= "Select * from kursy order by id";
foreach ($conn->query($sql) as $row){
    if($currency2==$row['currency']) {
        $liczba3=$GLOBALS['liczba1'];
        $liczba4 =$row['bid'];
        $wynik=$GLOBALS['wynik1']/$liczba4;
        $code=$row['code'];
        echo $wynik;
        echo ' ';
        echo $row['code'];
        $sql1="Insert into `historia` (`source_currency`,`target_currency`,`liczba`,`wynik`,`code`)values ('$currency','$currency2','$liczba3','$wynik','$code')";
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
echo "<tr><th> source currency </th> <th> target currency </th> <th> amount </th> <th> Result </th></tr>";
$sql1= "Select * from historia LIMIT 50";
foreach ($conn->query($sql1) as $row){
        echo '<tr>';
        echo '<td>'.$row['source_currency'].'</td>';
        echo '<td>'.$row['target_currency'].'</td>';
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


