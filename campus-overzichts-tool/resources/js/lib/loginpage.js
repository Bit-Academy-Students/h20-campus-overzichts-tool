async function logInToAccount() {
    const res = await fetch("/api/users");
    const data = await res.json();
    const filterdata = data.filter();

}