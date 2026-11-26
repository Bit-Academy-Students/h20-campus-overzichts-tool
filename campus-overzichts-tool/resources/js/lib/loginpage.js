document.getElementById("loginToAccount").addEventListener("click", () => {
    logInToAccount();
});

async function logInToAccount() {
    const res = await fetch("/api/users");
    const data = await res.json();

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

    if (res.status !== 200) {
        document.getElementById("passwordMessage").style.display = "block";
        document.getElementById("passwordMessage").textContent = "Given credentials are incorrect.";
    } else {
        const url = await res.json();
        window.location.replace(url.redirect);
    }
};