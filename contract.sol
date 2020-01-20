pragma solidity ^0.4.18;
import "github.com/Arachnid/solidity-stringutils/strings.sol";

contract criptoChirac {
    
    //lib for strings
    using strings for *;

    //var to parse strings
    string public sep = " ; ";

    // if vote is open : 1 else 0
    uint isVoteOpen = 1;

    //acc var checking how many voters has voted
    uint aPaye =0;

    //candidature
    struct Proposal {
        string name;
        uint voteCount; // cumulated votes
    }

    struct User {
        bool voted;// check if user has already voted for this election
        bool set; // This boolean is used to differentiate between unset and zero struct values
    }

    mapping(address => User) public users; // map user with address


    //tableau de candidatures
     Proposal[] public proposals;

    /// Creation d'un bulletin
    constructor() public {

    }

    function createUser(address _userAddress) public {
        User storage user = users[_userAddress];
        // Check that the user did not already exist:
        require(!user.set);
        //Store the user
        users[_userAddress] = User({
            voted : false,
            set: true
        });
    }

    //get the winning index from proposal
    function winningProposal() public view
            returns (uint winningProposal_)
    {
        uint winningVoteCount = 0;
        for (uint p = 0; p < proposals.length; p++) {
            if (proposals[p].voteCount > winningVoteCount) {
                winningVoteCount = proposals[p].voteCount;
                winningProposal_ = p;
            }
        }
    }

    //increment acc when user request a candidate name to vote to
    function addAPaye() public payable{
        aPaye++;
    }

    //check if user has already vote
    function hasUserVoted() public view returns(bool){
        User storage sender = users[msg.sender];
        return sender.voted;
    }

    //check is the election is close
    function isClosed() public view returns(bool){
        if(isVoteOpen == 0){
            return true;
        }
    }

    //return the winner's name by is index in proposal
    function winnerName() public view returns (string winnerName_)
    {
        winnerName_ = proposals[winningProposal()].name;
    }

    //increase the candidat's name by one, if the user has not already voted
    function Add1ToName(string nameToIncrease) public returns(bool){
        createUser(msg.sender);
        User storage sender = users[msg.sender];
        if (sender.voted == false){
            for (uint p = 0; p < proposals.length; p++) {
                if (keccak256(abi.encodePacked(proposals[p].name)) == keccak256(abi.encodePacked(nameToIncrease)) ) {
                    proposals[p].voteCount += 1;
                    sender.voted = true;
                    return(sender.voted);
                }
            }
        }
        return(sender.voted);
    }

    //increase by one a candidat's score in proposals using is index
    function add1ToIndex(uint input) public{
        if(isVoteOpen == 1){
            proposals[shuffle(input)].voteCount += 1;
        }
    }

    // add a candidat name in proposals [admin]
    function addToProposals(string CandidateName) public{
        uint isAlreadyHere = 0;
        //check if the name already exist
        for (uint itt =0; itt<proposals.length; itt++){
            if(  keccak256(abi.encodePacked(proposals[itt].name)) == keccak256(abi.encodePacked(CandidateName))){
                isAlreadyHere = 1;
            }
        }
        if(isAlreadyHere == 0){
            proposals.push(Proposal(CandidateName, 0));
        }

    }

    //well, that's just a test function, ¯\_(ツ)_/¯
    function returnStringTest() public pure
        returns(string test){
        test="test";
    }

    //testing random in solidity this one does not always works
    function _generateRandomUint(string memory _str) public pure
    returns (uint) {
        uint rand = uint(keccak256(abi.encodePacked(_str)));
        return rand % 10;
    }

    //return a string composed of proposals names separated by a sepparator (no way)
    function getProposalNames() public view returns (string acc) {
        acc="";
        for (uint p = 0; p < proposals.length; p++) {
            acc = acc.toSlice().concat(sep.toSlice());
            acc = acc.toSlice().concat(proposals[p].name.toSlice());
        }
    return acc;
   }

    /**
    * slot : chosen candidate's number
    * max : the maximum slots + 1
    *
    * returns the new chosen candidate
    *
    * update : does not works sometime, replaced by a function in back
    **/
    function shuffle (uint slot) public view returns(uint256) {

        uint max = proposals.length;
        int height = 11;

        while (height > 0) {
            if (height % 2 == 0) {  // étage intermédiaire
                slot += now % 2;
            } else {    // étage de départ / arrivée
                slot -= now % 2;
            }
            height --;

            if(slot == 0) { // if the ball got out of bounds
                slot++;
            } else if(slot == max) {
                slot--;
            }
        }

        return slot;

    }

    //close the election and return the winner's name
    function closeVote() public payable returns(string resultat) {
        setIsVoteOpenToFalse();
        resultat = winnerName();
        return resultat;
    }

    //well, has you've guessed, it close the election
    function setIsVoteOpenToFalse() public payable{
        isVoteOpen = 0;
    }

    //return a candidat's score using his name
    function getNumberVoteByName(string name)public view returns (uint){
        for(uint itter=0 ; itter<proposals.length;itter++){
            if( keccak256(abi.encodePacked(proposals[itter].name)) == keccak256(abi.encodePacked(name)) ){
                return proposals[itter].voteCount;
            }
        }
    }

    
}