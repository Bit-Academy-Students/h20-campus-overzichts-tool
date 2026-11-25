logAccounts();

document.getElementById("createAccount").addEventListener("click", () =>{
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
        window.alert("All areas must be filled out");
        return(null);
    }

    checkEmailJSON.forEach(account => {
        if (account.email === eMail) {
            window.alert("Account with email already exists");
        }
    });

    const constructJSON = {
        first_Name: firstName,
        last_Name: lastName,
        email: eMail,
        password: passWord,
    }
    
    fetch("/api/users", {
        method: "POST",
        headers: {
            "Accept": "application/json",
            "Content-Type": "application/json",
        },
        body: JSON.stringify(constructJSON),
    });
}

async function logAccounts() {
    const checkEmail = await fetch("/api/users");
    const checkEmailJSON = await checkEmail.json();
    console.log(checkEmailJSON);
    if (document.getElementById("firstname").value === "") {
        console.log("null");
    }    
}