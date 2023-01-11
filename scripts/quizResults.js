let allResults =[];
let refinedResults = [];
let userobj;
let aggAverage=0;
let aggMin = 0;
let aggMax = 0;

window.onload = function () {
        loadingElement();
        document.querySelector("#searchByTagButton").addEventListener("click", getQuizzesByTag);
        document.querySelector('#viewStats').addEventListener('click',viewStats)
        document.querySelector("#quizTagsSelect").addEventListener("change", addToTags);
        document.querySelector("table").addEventListener("click", handleRowClick);
        document.querySelector("#refresh").addEventListener("click", refreshTable);
        //document.querySelector("#selectQuestionTagsButton").addEventListener("click", getAllSelectedTagsForQuestion);
        //document.querySelector("#tagsFloatingSelect").addEventListener("change", buildTagsSelectors);
        //document.querySelector("#selectTagsButton").addEventListener("click", getAllSelectedTags);
        getUser();
        getCatergories();

        //getAllResults();
};

function loadingElement(){
    let theTable = document.querySelector("table");
    let html = theTable.querySelector("tr").innerHTML;
    html += "<tr id = 'loading'>";
    html += "<td colspan=\"42\" class=\"mx-auto\"><h2>Loading Table</h2></td>";
    html += "</tr>";
    theTable.innerHTML = html;
}

function handleRowClick(e) {
    //add style to parent of clicked cell
    clearSelections();
    //e.target.parentElement.classList.add("highlighted");
    console.log(e.target.parentElement.id);
    rowSelected = e.target.parentElement.id;

}
function clearSelections() {
    let trs = document.querySelectorAll("tr");
    for (let i = 0; i < trs.length; i++) {
        trs[i].classList.remove("highlighted");
    }
}




function viewResult(e){
    let parent =  e.target.parentElement;
    let input = parent.firstChild.valueOf();
    //console.log(input.value);
    //console.log(allQuizzes);
    for(let i = 0; i<allResults.length; i++){
        if(allResults[i].quizResultID === input.value){
            console.log(allResults[i].quizResultID);
            quizBeingTaken = allResults[i];

        }
    }
    console.log(quizBeingTaken);
}
function getUser(){
    let url = "account/user"; // file name or server-side process name
    let xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function () {
        if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
            let resp = xmlhttp.responseText;
            //console.log("-->"+resp);
            if (resp.search("ERROR") >= 0) {
                alert("Something is wrong with the GET.");
            } else {
                userobj=resp;
                adjustNavbar();
                let user = JSON.parse(userobj);
                if(user.permissionLevel === "USER"){
                    getResultsByUser();
                }else if(user.permissionLevel === "GUEST"){
                    window.location.replace("http://localhost/Projects/Final_Project/index.php");
                }else if(user.permissionLevel === "ADMIN" || user.permissionLevel === "SUPER"){
                    getAllResults();
                }


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
    html += "<li class=\"nav-item\">\n" +
        "<a class=\"nav-link active\" aria-current=\"page\" href=\"index.php\">Quizzes</a>\n" +
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
    html +="<li class=\"nav-item\">\n" +
        "<a class=\"nav-link\" aria-current=\"page\">"+user.username.toLocaleUpperCase()+"</a>\n" +
        "</li>";
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
    //console.log(url);
    xmlhttp.open("GET", url, true);
    xmlhttp.send();
}

function getAllResults(){

    let url = "quizapp/quizresults"; // file name or server-side process name
    let xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function () {
        if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
            let resp = xmlhttp.responseText;
            //console.log(resp);
            if (resp.search("ERROR") >= 0) {
                alert("oh no, something is wrong with the GET ...");
            } else {
                //console.log(resp);
                buildTable(resp);
            }
        }
    };
    //console.log(url);
    xmlhttp.open("GET", url, true);
    xmlhttp.send();
}

function getResultsByUser(){
    let user = JSON.parse(userobj);
    let url = "quizapp/quizresults/search:username="+user.username; // file name or server-side process name
    let xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function () {
        if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
            let resp = xmlhttp.responseText;
            //console.log(resp);
            if (resp.search("ERROR") >= 0) {
                alert("oh no, something is wrong with the GET ...");
            } else {
                //console.log(resp);
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

function getQuizzesByTag(){
    clearStats();
    let tagBar = document.getElementById("tagSearch");
    let p = tagBar.querySelector('#tags');
    let tagChecker=[];
    let unrefinedResult=[];
    let html = p.innerHTML;
    let tag = html.split(",");
    p.innerHTML= "";
    console.log(tag);
    //console.log(allResults);
    for(let i=0;i<allResults.length;i++){
        let temp = allResults[i]['quiz'].questions;
        for(let j=0;j<temp.length;j++){
            let temp2 = temp[j]['tags'];
            for(let k=0;k<temp2.length;k++){
                let temp3 = temp2[k]['tagName'];
                if(temp3 === tag[0]){
                    tagChecker.push(temp3);
                }else if(temp3 === tag[1]){
                    tagChecker.push(temp3);
                }
                //unrefinedResult.push(allResults[i]);
            }
            if(tag.length>1){
                if(tagChecker.includes(tag[0]) && tagChecker.includes(tag[1])){
                    unrefinedResult.push(allResults[i]);
                    tagChecker=[];
                }
            }else{
                if(tagChecker.includes(tag[0])){
                    unrefinedResult.push(allResults[i]);
                    tagChecker=[];
                }
            }
        }
    }
    let refinedResult = unrefinedResult.filter((c, index) => {
        return unrefinedResult.indexOf(c) === index;
    });
    buildRefinedTable(refinedResult);
    refinedResults = refinedResult;
    console.log(refinedResult);
    //console.log(tagChecker);

}
function clearStats(){
    let thenav = document.querySelector("#stats");
    thenav.innerHTML="";
}
function viewStats(){
    clearStats();
    let thenav = document.querySelector("#stats");
    let html = thenav.innerHTML;
    html += "<li class=\"nav-item\">\n" +
                "<a class=\"nav-link active\" aria-current=\"page\">Average: "+aggAverage+"</a>\n" +
            "</li>\n"+
            "<li class=\"nav-item\">\n" +
                "<a class=\"nav-link active\" aria-current=\"page\">Max: "+aggMax+"</a>\n" +
            "</li>\n"+
            "<li class=\"nav-item\">\n" +
                "<a class=\"nav-link active\" aria-current=\"page\">Min: "+aggMin+"</a>\n" +
            "</li>\n";

    thenav.innerHTML=html;
}

function refreshTable(){
    clearStats();
    buildRefinedTable(allResults);
}

function aggregateData(text){
    //console.log(text);
    let  sum = 0;
    let  max = 0;
    let  min = 1000;
    let tempVar = 0;
    for (let i = 0; i < text.length; i++){
        let score = (text[i].scoreNumerator / text[i].scoreDenominator)*100;
        tempVar=score;
        if(tempVar>max){
            max = tempVar;
        }if(tempVar<min){
            min = tempVar;
        }
        sum +=score;
    }
    aggAverage = Math.round(sum / text.length);
    aggMin = Math.round(min);
    aggMax = Math.round(max);
}

function buildTable(text) {
    clearTable();
    let arr = JSON.parse(text);
    aggregateData(arr);
    // get JS Objects
    let theTable = document.querySelector("table");
    let html = theTable.querySelector("tr").innerHTML;
    let user = JSON.parse(userobj);
    for (let i = 0; i < arr.length; i++) {
        let row = arr[i];
        //console.log(row.quiz);
        allResults.push(arr[i]);
        let score =  (row.scoreNumerator / row.scoreDenominator) *100;
        html += "<tr id = '"+row.resultID+"'>";
        html += "<td>" + row.resultID + "</td>";
        html += "<td>" + row.quiz.quizTitle  + "</td>";
        html += "<td>" + row.user.username + "</td>";
        html += "<td>" + Math.round(score) + "%</td>";
        html += "<td><form action='quiz/viewResult.php' ><input type='hidden' name='quizID' value='"+row.quiz.quizID+"'><input type='hidden' name='username' value='"+user.username+"'><input type='hidden' name='quizResultID' value='"+row.resultID+"'><button id='viewButton' class='btn btn-outline-success'>View</button></form></td>";
        html += "</tr>";
    }
    //console.log(allQuizzes);
    theTable.innerHTML = html;
    document.querySelector("#viewButton").addEventListener("click", viewResult);
}

function buildRefinedTable(text) {
    clearTable();
    let arr = text;
    aggregateData(text);
    //console.log(text);
    // get JS Objects
    let theTable = document.querySelector("table");
    let html = theTable.querySelector("tr").innerHTML;
    let user = JSON.parse(userobj);
    for (let i = 0; i < arr.length; i++) {
        let row = arr[i];
        let score =  (row.scoreNumerator / row.scoreDenominator) *100;
        html += "<tr id = '"+row.resultID+"'>";
        html += "<td>" + row.resultID + "</td>";
        html += "<td>" + row.quiz.quizTitle  + "</td>";
        html += "<td>" + row.user.username + "</td>";
        html += "<td>" + Math.round(score) + "%</td>";
        html += "<td><form action='quiz/viewResult.php' ><input type='hidden' name='quizID' value='"+row.quiz.quizID+"'><input type='hidden' name='username' value='"+user.username+"'><input type='hidden' name='quizResultID' value='"+row.resultID+"'><button id='viewButton' class='btn btn-outline-success'>View</button></form></td>";
        html += "</tr>";
    }
    //console.log(allQuizzes);
    theTable.innerHTML = html;
    document.querySelector("#viewButton").addEventListener("click", viewResult);
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
        //console.log(tag);
        let tagBar = document.getElementById("tagSearch");
        let p = tagBar.querySelector('#tags');
        let html = p.innerHTML;
        //console.log(tag);
        let arr = tag.split("::");
        //console.log(arr);
        if(html===""){
            html+= ""+tag+"";
        }else{
            html+= ","+tag+"";
        }
        p.innerHTML = html;
    }
}

