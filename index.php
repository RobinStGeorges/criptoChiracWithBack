<?php

session_start();

require 'database.php';

if( isset($_SESSION['user_id']) ){

	$records = $conn->prepare('SELECT id,email,password, admin FROM users WHERE id = :id');
	$records->bindParam(':id', $_SESSION['user_id']);
	$records->execute();
	$results = $records->fetch(PDO::FETCH_ASSOC);

	$user = NULL;

	if( count($results) > 0){
		$user = $results;
	}

}

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>CriptoChirac</title>

    <link rel="stylesheet" type="text/css" href="main.css">

    <script src="node_modules/web3/dist/web3.min.js"></script>
	<link rel="stylesheet" type="text/css" href="style.css">
	<link href='http://fonts.googleapis.com/css?family=Roboto' rel='stylesheet' type='text/css'>
</head>
<body>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>

	<div class="header">
		<h1>CriptoChirac</h1>
	</div>

	<?php if( !empty($user) ): ?>

		<br />Welcome <?= $user['email']; ?> 
		<br /><br />You are successfully logged in as
            <?php if($user['admin']):?>
                an admin !
                <!-- Mettre formulaire creation poll ici-->
            <div class="container">
                <h2><u>Les candidats</u></h2>
                <h2 id="candidats"></h2>
                <h1>Ajouter :</h1>
                <label for="add" class="col-lg-2 control-label">Ajouter un candidats :</label>
                <input id="add" type="text">
                <button id="addButton">Ajouter</button>
            </div>


            <?php else:?>
                a voter!
                <!-- Mettre formulaire lancer un vote ici-->
            <div class="container">
                <h2><u>Les candidats</u></h2>
                <h2 id="candidats"></h2>
                <h1>Vote :</h1>
                <label for="vote" class="col-lg-2 control-label">Donner  votre vote :</label>
                <h3 id="slotsNumbers"></h3>
                <input id="vote" type="number">
                <button id="button">Vote !</button>
            </div>


            <?php endif; ?>
		<br /><br />
		<a href="logout.php">Logout?</a>

	<?php else: ?>

		<h1>Please Login or Register</h1>
		<a href="login.php">Login</a> or
		<a href="register.php">Register</a>

	<?php endif; ?>
    <script>

        if (typeof web3 !== 'undefined') {
            web3 = new Web3(web3.currentProvider);
        } else {
            // set the provider you want from Web3.providers
            web3 = new Web3(new Web3.providers.HttpProvider("http://localhost:8545"));
        }

        console.log(web3);
        //get the first account from ganache (or other)
        web3.eth.defaultAccount = web3.eth.accounts[0];
        var VoteContract = web3.eth.contract([
            {
                "constant": true,
                "inputs": [
                    {
                        "name": "",
                        "type": "uint256"
                    }
                ],
                "name": "proposals",
                "outputs": [
                    {
                        "name": "name",
                        "type": "string"
                    },
                    {
                        "name": "voteCount",
                        "type": "uint256"
                    }
                ],
                "payable": false,
                "stateMutability": "view",
                "type": "function"
            },
            {
                "constant": false,
                "inputs": [
                    {
                        "name": "CandidateName",
                        "type": "string"
                    }
                ],
                "name": "addToProposals",
                "outputs": [],
                "payable": false,
                "stateMutability": "nonpayable",
                "type": "function"
            },
            {
                "constant": true,
                "inputs": [],
                "name": "sep",
                "outputs": [
                    {
                        "name": "",
                        "type": "string"
                    }
                ],
                "payable": false,
                "stateMutability": "view",
                "type": "function"
            },
            {
                "constant": false,
                "inputs": [
                    {
                        "name": "nameToIncrease",
                        "type": "string"
                    }
                ],
                "name": "TESTAdd1ToName",
                "outputs": [],
                "payable": false,
                "stateMutability": "nonpayable",
                "type": "function"
            },
            {
                "constant": true,
                "inputs": [],
                "name": "winningProposal",
                "outputs": [
                    {
                        "name": "winningProposal_",
                        "type": "uint256"
                    }
                ],
                "payable": false,
                "stateMutability": "view",
                "type": "function"
            },
            {
                "constant": true,
                "inputs": [],
                "name": "getProposalNames",
                "outputs": [
                    {
                        "name": "acc",
                        "type": "string"
                    }
                ],
                "payable": false,
                "stateMutability": "view",
                "type": "function"
            },
            {
                "constant": true,
                "inputs": [],
                "name": "returnStringTest",
                "outputs": [
                    {
                        "name": "test",
                        "type": "string"
                    }
                ],
                "payable": false,
                "stateMutability": "pure",
                "type": "function"
            },
            {
                "constant": true,
                "inputs": [
                    {
                        "name": "_str",
                        "type": "string"
                    }
                ],
                "name": "_generateRandomUint",
                "outputs": [
                    {
                        "name": "",
                        "type": "uint256"
                    }
                ],
                "payable": false,
                "stateMutability": "pure",
                "type": "function"
            },
            {
                "constant": false,
                "inputs": [
                    {
                        "name": "input",
                        "type": "uint256"
                    }
                ],
                "name": "add1ToIndex",
                "outputs": [],
                "payable": false,
                "stateMutability": "nonpayable",
                "type": "function"
            },
            {
                "constant": true,
                "inputs": [],
                "name": "winnerName",
                "outputs": [
                    {
                        "name": "winnerName_",
                        "type": "string"
                    }
                ],
                "payable": false,
                "stateMutability": "view",
                "type": "function"
            },
            {
                "constant": true,
                "inputs": [
                    {
                        "name": "slot",
                        "type": "uint256"
                    }
                ],
                "name": "shuffle",
                "outputs": [
                    {
                        "name": "",
                        "type": "uint256"
                    }
                ],
                "payable": false,
                "stateMutability": "view",
                "type": "function"
            },
            {
                "inputs": [],
                "payable": false,
                "stateMutability": "nonpayable",
                "type": "constructor"
            }
        ]);
        var criptoChirac = VoteContract.at('0xc93F934e28578BD89C19A008E4f5aC0dfa5Fc0a3');
        console.log(criptoChirac);

        //VOTE POUR UN CANDIDAT
        $("#button").click(function() {
            criptoChirac.add1ToIndex.sendTransaction($("#vote").val(),{
                from:  web3.eth.defaultAccount
            },function(error , result){
                if(!error)
                    console.log(result);
                else
                    console.log(error.code)
            })
        });

        $("#addButton").click(function() {
            ethereum.enable()
            criptoChirac.addToProposals.sendTransaction($("#add").val(),{
                from:  web3.eth.defaultAccount
            },function(error , result){
                if(!error)
                    console.log(result);
                else
                    console.log(error.code)
            })
        });

        criptoChirac.getProposalNames(function(error, result){
            if(!error)
            {
                $("#candidats").html(result.slice(3));
                var number = result.split(";");
                number = number.length-2;
                $("#slotsNumbers").html("Entre 0 et "+number);
                console.log(result);
            }
            else
                console.error(error);
        });
        </script>
</body>
</html>