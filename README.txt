BlockChain soutenance
Kevin Marion Robin

-node_modules : nécessaire pour web3
-contract.sol : contrat solidity
-*.php & *.css: web app

HOW TO :

Copier le dossier dans www/ pour wampp et htdocs pour xampp.
Copier le contenu de contract.sol dans remix, compiler, et déployer sur web3 port 7545
Lancer ganache sur le port 7545
Connecter metamask rpc personnalisé à l'adresse : http://127.0.0.1:7545/
Vérifier dans index.php que l'objet web3 se s'initialise bien sur 7545
creer la base de donnée selon les paramètres de database.php

Un compte admin peux :
  -ajouter des candidats à une élection
  -clore une élection
  
Un compte user peux :
  -obtenir un nom de candidat
  -voter pour un candidat
  
  Le résultat des élection est affiché sur chaque compte une fois l'élection close
