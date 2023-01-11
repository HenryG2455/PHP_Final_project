window.onload = function () {
//password checking on creating account
    document.querySelector("#CreateAccountButton").addEventListener("click", validatePasswords);
    document.querySelector("#ChangePasswordButton").addEventListener("click", validatePasswordsWhenChanging);
    document.querySelector("#logout").addEventListener("click",logout)
};


function validatePasswords(event) {
    let password = document.querySelector("#password").value;
    let passwordConfirmation = document.querySelector("#passwordConfirmation").value;

    if (password !== passwordConfirmation){
        event.preventDefault();
        alert("Password confirmation doesn't match password");
    }
}
function validatePasswordsWhenChanging(event) {
    let password = document.querySelector("#newPassword").value;
    let passwordConfirmation = document.querySelector("#newPasswordConfirmation").value;

    if (password !== passwordConfirmation){
        event.preventDefault();
        alert("Password confirmation doesn't match password");
    }
}
function logout(){
    let url = "account/user"; // file name or server-side process name
    let xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function () {
        if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
            let resp = xmlhttp.responseText;
            console.log("-->"+resp);
            if (resp.search("ERROR") >= 0) {
                alert("Something is wrong with the Logout.");
            } else {
                window.location.replace("http://localhost/Projects/Final_Project/index.php");
            }

        }
    };
    console.log(url);
    xmlhttp.open("DELETE", url, true);
    xmlhttp.send();

}