// Check if id is number, otherwise replace anything that isn't number
let theInput = document.getElementById("cred");
theInput.onkeydown = function(event) {
    // Only allow if the e.key value is a number or if it's 'Backspace'
    if (isNaN(event.key) && event.key !== 'Backspace') {
        event.preventDefault();
    }
};
