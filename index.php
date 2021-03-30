<?php

require_once 'connect.php';
$pdo = new PDO(DSN, USER, PASS); // Reference to my database


/////////////////////  Validation code to check if the data inserted are valid

if ('POST' === $_SERVER['REQUEST_METHOD']) {

    $firstname = trim($_POST['firstname']);
    $lastname = trim($_POST['lastname']);
    $errors = [];
    define('MAXLENGTH', 45);

    if (empty($firstname)) {
        $errors[] = "You must insert your firstname";
    };

    if (empty($lastname)) {
        $errors[] = "You must insert your lastname";
    };

    if (strlen($firstname) > MAXLENGTH) {
        $errors[] = "Your name should not exceed " . MAXLENGTH . " characters.";
    }

    if (strlen($lastname) > MAXLENGTH) {
        $errors[] = "Your lastname should not exceed " . MAXLENGTH . " characters.";
    };


///////////////////// Preparation of the input data before inserting data into database

    if (empty($errors)) {
        $query = "INSERT INTO friend (firstname, lastname) VALUES (:firstname, :lastname)";
        $statement = $pdo->prepare($query); // método para tratar os dados da solicitação acima

        $statement->bindValue(':firstname', $firstname, PDO::PARAM_STR);
        $statement->bindValue(':lastname', $lastname, PDO::PARAM_STR);

        $statement->execute();
        
//////////////////// Selection of all data from the table friend, in order to exhibit in HTML

        $query = "SELECT * FROM friend"; 
        $statement = $pdo->query($query);
        $friends = $statement->fetchAll(PDO::FETCH_ASSOC);
    };
};
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Friends list</title>
</head>

<body>

    <h1>Add a friend:</h1>
    
//////////////////// Loop to show errors, if there's any;
    <ul>
    <?php foreach ($errors as $error) { ?>
        <li><?= $error ?></li>
    <?php }; ?>
    </ul>

    <form method="POST">

        <label for="firstname">Name:</label>
        <input type="text" name="firstname" id="firstname" value="<?= $firstname ?? "" ?>"required>

        <label for="lastname">Lastname:</label>
        <input type="text" name="lastname" id="lastname"  value="<?= $lastname ?? ""?>"required>

        <button>Submit</button>

    </form>
    
//////////////////// Loop to show the content from friend table

    <h1>Friends list:</h1>

    <?php foreach ($friends as $friend) { ?>
        <li><?= $friend['firstname'] . " " . $friend['lastname'] ?></li>
    <?php }; ?>


</body>

</html>

