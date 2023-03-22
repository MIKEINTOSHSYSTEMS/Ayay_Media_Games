const animationButton = document.querySelector(".animationButton");
const paintingText = document.querySelector(".painting");

// pause animation
animationButton.addEventListener("click", function () {

    switch (animationButton.textContent) {
        case "Pause Animations":
            // Blinking Painting Text
            paintingText.style.animationPlayState = 'paused';
            animationButton.classList.add("restart");
            animationButton.textContent = "Restart Animations";
            break;
        case "Restart Animations":

            animationButton.classList.remove("restart");
            // Blinking Painting Text
            // remove animation
            paintingText.style.animation = 'none';

            // trigger reflow
            paintingText.offsetWidth;
        
            // add animation again
            paintingText.style.animation = "blinker 3s linear infinite";
            animationButton.textContent = "Pause Animations";
            break;
        default:
            break;
    }


});
