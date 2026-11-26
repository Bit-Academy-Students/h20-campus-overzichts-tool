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

    attemptLogin(emailValue, passwordValue);
}

async function attemptLogin(email, password) {
    const token = document.querySelector('meta[name="csrf-token"]').content;
    const stringifyJSON = {
        email: email,
        password: password,
    }

    const res = await fetch("/login", {
        method: "POST",
        headers: {
            "Accept": "application/json",
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": token,
        },
        body: JSON.stringify(stringifyJSON),
    });

    const url = await res.json();
    console.log(url);
};