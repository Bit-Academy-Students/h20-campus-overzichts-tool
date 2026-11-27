announce();

function announce(string, type) {
    const annnounceDiv = document.getElementById("div");
    const announceType = document.getElementById("h3");
    const announceMessage = document.getElementById("p");

    announceType.textContent = type;
    announceMessage.textContent = string;

    
    

    setInterval(() => {
        annnounceDiv.remove();
    }, 5000);
}


export { announce };