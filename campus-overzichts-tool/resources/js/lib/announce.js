announce();

function announce(string, type) {
    const annnounceDiv = document.getElementById("div");
    const announceType = document.getElementById("h3");
    const announceMessage = document.getElementById("p");

    announceType.textContent = type;
    announceMessage.textContent = string;

    switch (type) {
        case "success":
            
            break;
        case "warning":
            
            break;
        case "error":
            
            break;
        case "message":
            
            break;
    
        default:
            break;
    }

    setInterval(() => {
        annnounceDiv.remove();
    }, 3000);
}


export { announce };