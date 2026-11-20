document.getElementById("createAccount").addEventListener("click", () =>{
    createAccount();
});

function createAccount() {
    const firstName = document.getElementById("firstname").value;
    const lastName = document.getElementById("surname").value;
    const eMail = document.getElementById("email").value;
    const passWord = document.getElementById("password").value;

    const constructJSON = {
        first_Name: firstName,
        last_Name: lastName,
        email: eMail,
        password: passWord,
        role: "user",
    }

    console.log(constructJSON);
    
}