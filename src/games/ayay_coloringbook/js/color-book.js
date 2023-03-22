// window.addEventListener("load", () => {
// Note: Commented out to allow onClick to work

    const canvas = document.querySelector("#canvas");
    const context = canvas.getContext("2d");


    canvas.height = 500;
    canvas.width = 500;

    let start_background_color = "#B7B7B7CF";
    context.fillStyle = start_background_color;
    context.fillRect(0, 0, canvas.width, canvas.height);


    // Variables
    let draw_color = "black";
    let draw_width = "10";
    let is_drawing = false;

    let slider = document.getElementById("penRange");
    let output = document.getElementById("pen-width");
    output.textContent = slider.value;

    let imageValue;
    
    //Array

    let store_array = [];
    let index = -1;

    let removed_array = [];
    let indexRemoved = -1;

    // EventListeners

    // For mobile and tablets
    canvas.addEventListener("touchstart", start, false);
    canvas.addEventListener("touchmove", draw, false);
    canvas.addEventListener("touchend", stop, false);


    // For computer
    canvas.addEventListener("mousedown", start);
    canvas.addEventListener("mousemove", draw);

    canvas.addEventListener("mouseup", stop, false);
    canvas.addEventListener("mouseout", stop, false);


    function start(event) {
        is_drawing = true;
        draw(event); //needed to make dots
        
        event.preventDefault(); //To prevent default changes to appear
    }

    function draw(event) {
        if(is_drawing) {
            context.lineTo(event.clientX-canvas.offsetLeft, event.clientY-canvas.offsetLeft);

            context.strokeStyle = draw_color;
            context.lineWidth = draw_width;
            context.lineCap = "round";
            context.lineJoin = "round"; //To get a full line with less interruptions
            
            context.stroke();

            //To make path smoother
            context.beginPath();
            context.moveTo(event.clientX-canvas.offsetLeft, event.clientY-canvas.offsetLeft);

            event.preventDefault();
        }
    }

    function stop(event) {
        if (is_drawing) {
            context.stroke();
            context.closePath();
            is_drawing = false;

            store_array.push(context.getImageData(0, 0, canvas.width, canvas.height));

            index += 1;
        
        }
        context.beginPath(); //to close the path if pen goes off the canvas

        event.preventDefault();

    }

    // Pen Width
    slider.oninput = function () {
        draw_width = this.value;
        output.textContent = this.value;
    }

    // UNDO 
    function undo_last() {
        if (index === 0) {
            context.clearRect(0, 0, canvas.width, canvas.height);
            context.fillStyle = start_background_color;
            context.fillRect(0, 0, canvas.width, canvas.height);
        } else if (index < 0) {
            reset_canvas();
        } else {
            
            removed_array.push(context.getImageData(0, 0, canvas.width, canvas.height));
            indexRemoved += 1;
            
            store_array.pop();
            index -= 1;

            context.putImageData(store_array[index], 0, 0);

            document.querySelector(".redo").disabled = false;
        }
    }

    // REDO 
    function redo_last() {
        if (index === -1)  {
            context.putImageData(removedLast[0], 0, 0);
            store_array.push(context.getImageData(0, 0, canvas.width, canvas.height));
            index += 1;
        } else {
            context.putImageData(removed_array[indexRemoved], 0, 0);
            removed_array.pop();
            indexRemoved -= 1;

            //to put the returned imaged back to the restore_array

            store_array.push(context.getImageData(0, 0, canvas.width, canvas.height));
            index += 1;

            if (removed_array.indexOf(removed_array[indexRemoved]) === -1) {
                document.querySelector(".redo").disabled = true; //Needed to prevent error
            }
        }
    }

    // ERASE
    function erase_color() {
        //To prevent the color of the main background
        draw_color = "white";
    
        // context.globalCompositeOperation = 'destination-out'
    }

    // RESET CANVAS
    function reset_canvas() {
        //declaring the fill's color 
        context.clearRect(0, 0, canvas.width, canvas.height); //clears the background
        context.fillStyle = "#f000c3";
        context.fillRect(0, 0, canvas.width, canvas.height); //refills with white again

                    // start rendering ayay icon to color-book.js

        drawing_image("./images/bird.png")

        //reset array and index
        store_array = [];
        index = -1;
    
        removed_array = [];
        indexRemoved = -1;

        document.querySelector(".redo").disabled = true;
    }

    // LOADING IMAGE
    function load_pic(element) {
        imageValue = element.src;
        drawing_image(imageValue);
    }

    function drawing_image(image) {
        let newImage = new Image();

        newImage.onload = function() {
            context.drawImage(newImage, 0, 0, canvas.width, canvas.height);
            store_array.push(context.getImageData(0, 0, canvas.width, canvas.height));
            
            index += 1; 
        }
        if (image !== undefined) {
            newImage.src = image;
        }
    }

    // SAVE CANVAS
    function saveCanvas() {
        //Note: "window.navigator" alone does not work
    
        if (canvas.msToBlob) { // IE/Edge (PNG only)
            let blob = canvas.msToBlob();
            window.navigator.msSaveBlob(blob, 'ayaymedia_colorbook.png');
        } else { // Chrome, Internet Explorer    
            const a = document.createElement("a");
            document.body.appendChild(a);
            a.href = canvas.toDataURL();
            a.download = "ayaymedia_colorbook.png";
            a.click();
        }
    }



    

// });