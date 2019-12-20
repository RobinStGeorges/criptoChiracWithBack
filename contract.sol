pragma solidity ^0.4.18;
import "github.com/Arachnid/solidity-stringutils/strings.sol";

contract criptoChirac {
    
    using strings for *;
    string public acc;
  
    //candidature
    struct Proposal {
        string name;   // nom 
        uint voteCount; // nombre de votes cumulés
    }
    

    //tableau de candidatures
     Proposal[] public proposals;
     
    struct poll {
        string name;
        string resultName; 
    }
    
    /// Creation d'un bulletin
    constructor() public {
        
        
    }
    
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


    function winnerName() public view
            returns (string winnerName_)
    {
        winnerName_ = proposals[winningProposal()].name;
    }
    
    function TESTAdd1ToName(string nameToIncrease) public{
        for (uint p = 0; p < proposals.length; p++) {
            if (keccak256(abi.encodePacked(proposals[p].name)) == keccak256(abi.encodePacked(nameToIncrease)) ) {
                proposals[p].voteCount += 1;
            }
        }
    }
    
    function addToProposals(string CandidateName) public{
        proposals.push(Proposal(CandidateName, 0));
    }
    
    function returnStringTest() public view
        returns(string test){
        test="test";
    }
    
    function _generateRandomUint(string memory _str) public view
    returns (uint) {
        uint rand = uint(keccak256(abi.encodePacked(_str)));
        return rand % 10;
    }
    
    
}