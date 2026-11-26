document.getElementById("loginToAccount").addEventListener("click", () => {
    logInToAccount();
});

async function logInToAccount() {
    const res = await fetch("/api/users");
    const data = await res.json();
    console.log(data);

    const emailValue = document.getElementById("email").value;
    const passwordValue = document.getElementById("password").value;

    if (emailValue === "" || passwordValue === "") {
        window.alert("All fields must be filled out.");
        return (null);
    }
    
    data.forEach(account => {
        if (account.email == emailValue) {
            if (account.password == passwordValue) {
                window.location.replace("http://localhost:8000/dashboard");
            } else {
                document.getElementById("passwordMessage").style.display = "block";
                document.getElementById("passwordMessage").textContent = "Password is incorrect.";
            }
        } 
    });
}