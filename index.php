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
    <link rel="stylesheet" type="text/css" href="style.css">
    <script src="node_modules/web3/dist/web3.min.js"></script>

	<link href='http://fonts.googleapis.com/css?family=Roboto' rel='stylesheet' type='text/css'>
</head>
<body>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>

	<div class="header">
        <h1>CriptoChirac</h1>
        <center>
            <div id='flash' style="display: none;color: red">
                Mauvaise valeur !
            </div>
        </center>

        <br /><br />You are successfully logged in as

    </div>

	<?php if( !empty($user) ): ?>


        <?php if($user['admin']):?>
                an admin !
                <!-- Mettre formulaire creation poll ici-->

            <div class="container">

                <fieldset>
                    <legend>
                        <b><u>Les candidats</u></b>
                    </legend>

                    <h2 id="candidats"></h2>
                </fieldset>
                <div id="isOpen" style="display: block;">
                <button id ="addForm" style = "">
                    <b>Menu ajouter ▼</b>
                </button>
                    <div id="showAddForm" style="display:none;">
                        <fieldset>
                        <label for="add" class="col-lg-2 control-label">Ajouter un candidats :</label>
                        <input id="add" type="text">
                        <button id="addButton">Valider</button>
                        </fieldset>
                    </div>
                    <b>Clôturer les élections et récuperer le résultat</b>
                    <button id="close" style="color:red;">
                        Clôture
                    </button>
                </div>
                <div id="resultVote"></div>

            </div>


            <?php else:?>
                a voter!
                <!-- Mettre formulaire lancer un vote ici-->
            <div class="container">
                <fieldset>
                    <legend>
                        <b><u>Les candidats</u></b>
                    </legend>
                    <h2 id="candidats"></h2>
                </fieldset>
                <div id="canVote" style="display: block;">
                    <p><label for="vote" class="col-lg-2 control-label"><b>Donner  votre vote :</b></label></p>
                    <h4 id="slotsNumbers"></h4>
                    <button id="button">Vote !</button>
                    <div id="aVote" style="display: none">
                        Voulez-vous valider le vote pour
                        <div id="tempResult" style="font-weight: bold"></div>
                        <button id="validateVote">Valider</button>
                    </div>
                </div>
                <div id="cantVote" style="display: none;">
                </div>
            </div>


            <?php endif; ?>
		<br /><br />
		<button>
            <a href="logout.php">Logout?</a>
        </button>

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
            web3 = new Web3(new Web3.providers.HttpProvider("http://localhost:7545"));
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
                "inputs": [],
                "name": "closeVote",
                "outputs": [
                    {
                        "name": "resultat",
                        "type": "string"
                    }
                ],
                "payable": true,
                "stateMutability": "payable",
                "type": "function"
            },
            {
                "constant": false,
                "inputs": [],
                "name": "addAPaye",
                "outputs": [],
                "payable": true,
                "stateMutability": "payable",
                "type": "function"
            },
            {
                "constant": false,
                "inputs": [],
                "name": "setIsVoteOpenToFalse",
                "outputs": [],
                "payable": true,
                "stateMutability": "payable",
                "type": "function"
            },
            {
                "constant": true,
                "inputs": [
                    {
                        "name": "name",
                        "type": "string"
                    }
                ],
                "name": "getNumberVoteByName",
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
                "constant": false,
                "inputs": [
                    {
                        "name": "nameToIncrease",
                        "type": "string"
                    }
                ],
                "name": "Add1ToName",
                "outputs": [
                    {
                        "name": "",
                        "type": "bool"
                    }
                ],
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
                "name": "getAVote",
                "outputs": [
                    {
                        "name": "",
                        "type": "bool"
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
                        "name": "",
                        "type": "address"
                    }
                ],
                "name": "users",
                "outputs": [
                    {
                        "name": "voted",
                        "type": "bool"
                    },
                    {
                        "name": "set",
                        "type": "bool"
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
                "constant": true,
                "inputs": [],
                "name": "isClosed",
                "outputs": [
                    {
                        "name": "",
                        "type": "bool"
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
                        "name": "_userAddress",
                        "type": "address"
                    }
                ],
                "name": "createUser",
                "outputs": [],
                "payable": false,
                "stateMutability": "nonpayable",
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
        var criptoChirac = VoteContract.at('0x697Cbb7E1B3f799BF2DCad58dDC55510A7e73F57');
        console.log(criptoChirac);

        criptoChirac.getAVote(function (error, result) {
            if(result === true){
                console.log("can vote ?");
                console.log(result);
                document.getElementById("canVote").style.display='none';
                document.getElementById("canVote").innerHTML="Vous avez déjà voté !";
                document.getElementById("canVote").style.display='block';
            }
        });

        criptoChirac.isClosed(function (error, result) {
            if(result === true){
                criptoChirac.winnerName({
                    from:  web3.eth.defaultAccount
                },function(error , result){
                    if(!error) {
                        document.getElementById("isOpen").style.display='none';
                        document.getElementById("resultVote").innerHTML="Le.a président.e est : <b>"+result+"</b>";
                        console.log(result);
                    }else{
                        console.log("could not close");
                        console.log(error.code);
                    }
                })
            }
        });

        function setDisplayFlashToNone() {
            document.getElementById("flash").style.display = 'none';
        }

        //Avoir UN CANDIDAT
        $("#button").click(function() {
            ethereum.enable();

                criptoChirac.addAPaye({
                    from:  web3.eth.defaultAccount
                },function(error , result){
                    if(!error){
                        console.log("Vous avez un nouveau candidat possible!");
                        var min = 0;
                        var max = window.listeCandidats.length;
                        window.tempCandIndex = Math.floor(Math.random() * (+max - +min)) + +min;
                        document.getElementById("aVote").style.display="block";
                        document.getElementById("tempResult").innerHTML=window.listeCandidats[window.tempCandIndex];
                    }
                    else{
                        console.log("votre tentative de vote n'a pas aboutie");
                    }
                });


        });

        // valider le vote
        $("#validateVote").click(function() {
            ethereum.enable();
            var valToSend = document.getElementById("tempResult").innerHTML.trim();
            console.log(document.getElementById("tempResult").innerHTML);
            criptoChirac.Add1ToName.sendTransaction(valToSend,{
                from:  web3.eth.defaultAccount
            },function(error , result){
                console.log("result :");
                console.log(result);
                if(!error){
                    var urlLogout= "http://localhost/quickBack/login.php";
                    window.location = urlLogout;
                }
                else{
                    console.log("votre tentative de vote n'a pas aboutie");
                }
            });
        });

        //ajoute un candidat (call to clockchain)
        $("#addButton").click(function() {
            ethereum.enable();
            criptoChirac.addToProposals.sendTransaction($("#add").val(),{
                from:  web3.eth.defaultAccount
            },function(error , result){
                if(!error)
                    console.log(result);
                else
                    console.log(error.code)
            })
        });


        //termine une election
        $("#close").click(function() {
            ethereum.enable();
            criptoChirac.closeVote({
                from:  web3.eth.defaultAccount
            },function(error , result){
                if(!error) {

                    console.log(result);
                }else{
                    console.log("could not close");
                    console.log(error.code);
                }
            })
        });




        //recupère la liste des candidats
        criptoChirac.getProposalNames(function(error, result){
            if(!error)
            {
                window.listeCandidats = result.slice(3).split(";");
                var listeStringCandidats = " ";
                if(listeCandidats[0] !== "") {
                    for (var i = 0; i < listeCandidats.length; i++) {
                        listeStringCandidats += "[ " + listeCandidats[i] + " ]";
                    }
                }
                $("#candidats").html(listeStringCandidats);
                /*
                var listParticipants = result.split(";");
                number = listParticipants.length-2;
                $("#slotsNumbers").html("Entre 0 et "+number);
                console.log(result);
                */
            }
            else
                console.error(error);
        });

        //affiche le formulaire
        $("#addForm").click(function() {
            if(document.getElementById("showAddForm").style.display === "none") {
                document.getElementById("showAddForm").style.display = 'block';
            }else{
                document.getElementById("showAddForm").style.display = 'none';
            }
        })

        </script>
</body>
</html>