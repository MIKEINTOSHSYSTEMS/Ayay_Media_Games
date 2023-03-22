$().ready(function () {


  var setupGame = function () {
    resetImages()//reset the hangman images.
    $(".blank-spaces").empty(); //This empties out all the elements in the .blank-spaces section
    var word = pickWord();// Picks a word from secret list of possible words
    makeBlankSpaces(word); // This creates the blank spaces for the word.

    $(".letters button").show()
    $(".letters button").click(letterGuess)
    $(".game-messages p").text("There are " + possibleWords.length + " more words to play.")//uses secret possibleWords list to say how many words are left to play
    $(".hangman-image img").replaceWith("<img src='/src/games/ayay_hangman/img/ayay_hangman.png' alt='hangman image'>")//This replaces the img that is currently showing with another one.
  }

  var resetGame = function () {
    resetImages()
    resetPossibleWords()
    $(".blank-spaces").empty()

    fillInWelcomeWord()//Fill in Welcome Word

    $(".game-messages p").text("Welcome to AYAY Media Hangman Game. Click \"Play a Word\" to start playing!")

    $(".hangman-image img").replaceWith("<img src='/src/games/ayay_hangman/img/ayay_hangman.png' alt='hangman image'>")//This replaces the img that is currently showing with another one.

  }


var letterGuess = function (event) {
    var letter = $(this).text().toLowerCase();

    if(letterInWord(letter)){ // If the person picked the right letter
      fillInBlanks(letter) //fill In the blank spaces with the letter
    if(winner()){//Check if they've won
      $(".letters button").off("click")
      $(".game-messages p").text("You Won! Click Play a Word to play again")
    }
    }else{
      if(!gameOver()){//Check if they have more chances
        //Notice the !. It means "not".
        showNextImage()
      } else {//This means the player has lost
        showNextImage()
        revealWord()
        $(".game-messages p").text("Game Over. Click Play Word to play again")
        $(".letters button").off("click")

      }

    }
    $(this).hide(500)

  }











  $(".play").click(setupGame)

  $(".reset").click(resetGame)

})