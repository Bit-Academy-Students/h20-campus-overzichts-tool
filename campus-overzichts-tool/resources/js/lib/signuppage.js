document.getElementById("createAccount").addEventListener("click", () => {
    createAccount();
});

async function createAccount() {
    const firstName = document.getElementById("firstname").value; 
    const lastName = document.getElementById("surname").value;
    const eMail = document.getElementById("email").value;
    const passWord = document.getElementById("password").value;

    const checkEmail = await fetch("/api/users");
    const checkEmailJSON = await checkEmail.json();


    if (firstName === "" || lastName === "" || eMail === "" || passWord === "") {
        window.alert("All fields must be filled out.");
        return (null);
    }

    checkEmailJSON.forEach(account => {
        if (account.email == eMail) {
            document.getElementById("emailMessage").style.display = "block";
            document.getElementById("emailMessage").textContent = "An account already has that email.";
            return (null);
        }
    });

    if (passWord.length < 8) {
        document.getElementById("passwordMessage").style.display = "block";
        document.getElementById("passwordMessage").textContent = "Password must be atleast 8 characters.";
        return (null);
    }

    const constructJSON = {
        first_name: firstName,
        last_name: lastName,
        email: eMail,
        password: passWord,
    }

    const res = await fetch("/api/users", {
        method: "POST",
        headers: {
            "Accept": "application/json",
            "Content-Type": "application/json",
        },
        body: JSON.stringify(constructJSON),
    });

    if (res.status == 201) {
        const url = await res.json();
        window.location.replace(url.redirect);
    }
}