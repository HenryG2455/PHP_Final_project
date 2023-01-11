let allQuizzes =[];
let userobj;
let quizBeingTaken;
window.onload = function () {
    // will only work in the index page
    // if (window.location.href.indexOf("index") > -1)
    //     document.querySelector("#logInButton").addEventListener("click", login);
    //password checking on sign up
    if (window.location.href.indexOf("signup") > -1 || window.location.href.indexOf("homepage") > -1)
        document.querySelector("#SignInButton").addEventListener("click", validatePasswords);
    else if(window.location.href.indexOf("index")> -1){
        document.querySelector("#searchByTagButton").addEventListener("click", getQuizzesByTag);
        document.querySelector("#quizTagsSelect").addEventListener("change", addToTags);
        document.querySelector("#refresh").addEventListener("click", getAllQuizes);
        getUser();
        getCatergories();
    }else if(window.location.href.indexOf("quiz")> -1){

    }


};
function getUser(){
    let url = "account/user"; // file name or server-side process name
    let xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function () {
        if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
            let resp = xmlhttp.responseText;
            console.log("-->"+resp);
            if (resp.search("ERROR") >= 0) {
                alert("Something is wrong with the GET.");
            } else {
                userobj=resp;
                //pagePermission(userobj);
                //console.log(typeof userobj)
                getAllQuizes();
                adjustNavbar();
            }

        }
    };
    xmlhttp.open("GET", url, true);
    xmlhttp.send();
}
function adjustNavbar(){
    let thenav = document.querySelector("#changer");
    let html = thenav.innerHTML;
    let user = JSON.parse(userobj);
    if(user.permissionLevel === "GUEST"){
        html += "<li class=\"nav-item\">\n" +
                    "<a class=\"nav-link active\" aria-current=\"page\" href=\"loginPage.html\">Log In</a>\n" +
                "</li>\n" +
                "<li class=\"nav-item\">\n" +
                    "<a class=\"nav-link\" href=\"signupPage.php\">Sign Up</a>\n" +
                "</li>";
    }else{
        html += "<li class=\"nav-item\">\n" +
                    "<a class=\"nav-link active\" aria-current=\"page\" href=\"quizResults.html\">Quiz Results</a>\n" +
                "</li>\n" +
                "<li class=\"nav-item\">\n" +
                    "<a class=\"nav-link active\" href='' aria-current=\"page\" onclick='logout()'>Logout</a>\n" +
                "</li>\n";
                if(user.permissionLevel === "ADMIN")
                {
                    html +="<li class=\"nav-item\">\n" +
                        "<a class=\"nav-link active\" aria-current=\"page\" href=\"HomePages/AdminHomePage.php\">Admin Homepage</a>\n" +
                        "</li>\n";
                }
                if(user.permissionLevel === "SUPER")
                {
                    html +="<li class=\"nav-item\">\n" +
                        "<a class=\"nav-link active\" aria-current=\"page\" href=\"HomePages/SystemHomePage.php\">System Homepage</a>\n" +
                        "</li>\n";
                }
        html +="<li class=\"nav-item\">\n" +
                    "<a class=\"nav-link\" aria-current=\"page\">"+user.username.toLocaleUpperCase()+"</a>\n" +
                "</li>";

    }
    thenav.innerHTML=html;
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

function validatePasswords(event) {
    let password = document.querySelector("#password").value;
    let passwordConfirmation = document.querySelector("#passwordConfirmation").value;

    if (password !== passwordConfirmation){
        event.preventDefault();
        alert("Password confirmation doesn't match password");
    }
}

//TODO: filter on the backend

function getCatergories(){
    let url="quizapp/catergory";
    let xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function () {
        if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
            let resp = xmlhttp.responseText;
            //console.log(resp);
            if (resp.search("ERROR") >= 0) {
                alert("oh no, something is wrong with the GET ...");
            } else {
                //buildTable(resp);
                let select = document.getElementById("quizTagsSelect");
                let html = select.innerHTML;
                let arr = JSON.parse(resp);
                html+="<option>Choose a tag</option>";
                for(let i = 0; i < arr.length; i++){
                    let row = arr[i];
                    html+="<option value='"+row.tagName+"'>"+row.tagName.split('::')[1]+"</option>";
                    //console.log(row);
                }
                select.innerHTML =html;
            }
        }
    };
    console.log(url);
    xmlhttp.open("GET", url, true);
    xmlhttp.send();
}

function getAllQuizes(){
    let url = "quizapp/quizzes"; // file name or server-side process name
    let xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function () {
        if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
            let resp = xmlhttp.responseText;
            //console.log(resp);
            if (resp.search("ERROR") >= 0) {
                alert("oh no, something is wrong with the GET ...");
            } else {
                buildTable(resp);
            }
        }
    };
    //console.log(url);
    xmlhttp.open("GET", url, true);
    xmlhttp.send();
}
function clearTable(){
    let theTable = document.querySelector("table");
    let html = theTable.querySelector("tr").innerHTML;
    theTable.innerHTML = html;
}
function buildTable(text) {
    clearTable();
    //console.log(text);
    let arr = JSON.parse(text);
    //console.log(arr);
    // get JS Objects
    let theTable = document.querySelector("table");
    let html = theTable.querySelector("tr").innerHTML;
    let user = JSON.parse(userobj);
    for (let i = 0; i < arr.length; i++) {
        let row = arr[i];
        allQuizzes.push(arr[i]);
        html += "<tr id = '"+row.quizID+"'>";
        html += "<td>" + row.quizID + "</td>";
        html += "<td>" + row.quizTitle + "</td>";
        html += "<td>" + row.questions.length + "</td>";
        if(user.permissionLevel === "GUEST"){
            html += "<td>Login To Use</td>";
        }else{
            html += "<td><form action='quiz/takeQuiz.php'><input type='hidden' name='permissionlevel' value='"+user.permissionLevel+"'><input type='hidden' name='username' value='"+user.username+"'><input type='hidden' name='quizID' value='"+row.quizID+"'><button class='btn btn-outline-success takeQuiz'>Take quiz</button></form></td>";
        }
        html += "</tr>";
    }
    //console.log(allQuizzes);

    theTable.innerHTML = html;
    if(user.permissionLevel === "USER" || user.permissionLevel === "ADMIN"){
        document.querySelector(".takeQuiz").addEventListener("click",takeQuiz)
    }
}

function addToTags(){
    let tag = document.querySelector("#quizTagsSelect").value;
    let tagBar = document.getElementById("tagSearch");
    let p = tagBar.querySelector('#tags');
    let innerHTML = p.innerHTML;
    let temp = innerHTML.split(",");
    if(tag ==="Choose a tag" ){
        
    }
    else if(temp.length === 2){
        alert("You can only have two Tags");
    }else{
        console.log(tag);
        let tagBar = document.getElementById("tagSearch");
        let p = tagBar.querySelector('#tags');
        let html = p.innerHTML;
        //console.log(tag);
        let arr = tag.split("::");
        console.log(arr);
        if(html===""){
            html+= ""+tag+"";
        }else{
            html+= ","+tag+"";
        }
        p.innerHTML = html;
    }
}

function buildFilteredTable() {
    let tag = document.querySelector("#quizTagsSelect").value;
    alert(tag);
}
function getQuizzesByTag(){

    let tagBar = document.getElementById("tagSearch");
    let p = tagBar.querySelector('#tags');
    let html = p.innerHTML;
    p.innerHTML= "";
    //console.log(html);
    let arr = html.split(',');
    console.log(arr.length);
    console.log(arr);
    let url = "quizapp/quizzes/search:tags=";
    
    //This if else checks if Tags have been selected 
    //And Then checks if there are mutiple tags 
    //And makes a differ query based on the amout of tags
    if(arr.length === 1 && arr[0]===""){
        alert("Please select Some Tags");
    }else if(arr.length>1){
        for(let i=0; i<arr.length;i++){
            url+=arr[i]+",";
        }
        let xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function () {
            if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
                let resp = xmlhttp.responseText;
                console.log(resp);
                if (resp.search("ERROR") >= 0) {
                    alert("oh no, something is wrong with the GET ...");
                } else {
                    buildTable(resp);
                }
            }
        };
        console.log(url);
        xmlhttp.open("GET", url, true);
        xmlhttp.send();
    }else {
        url+=arr[0]+"";
        let xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function () {
            if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
                let resp = xmlhttp.responseText;
                console.log(resp);
                if (resp.search("ERROR") >= 0) {
                    alert("oh no, something is wrong with the GET ...");
                } else {
                    
                    buildTable(resp);
                }
            }
        };
        console.log(url);
        xmlhttp.open("GET", url, true);
        xmlhttp.send();
    }
    
}


function takeQuiz(e){
   let parent =  e.target.parentElement;
   let input = parent.firstChild.valueOf();
   //console.log(input.value);
   //console.log(allQuizzes);
   for(let i = 0; i<allQuizzes.length; i++){
       if(allQuizzes[i].quizID === input.value){
           console.log(allQuizzes[i].quizID);
           quizBeingTaken = allQuizzes[i];

       }
   }
    console.log(quizBeingTaken);
}

function buildQuiz(){

}