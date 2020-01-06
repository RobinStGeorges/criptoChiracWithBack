pragma solidity ^0.4.18;
import "github.com/Arachnid/solidity-stringutils/strings.sol";

contract criptoChirac {
    
    using strings for *;
    string public acc;
    string public sep = " ; ";
  
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
    **/
    function shuffle (uint slot, uint max) public view returns(uint256) {
        
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

    
    
}
