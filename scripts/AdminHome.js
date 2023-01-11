window.onload = function () {
    queryAllUsers();
    getAllTags();
    document.querySelector("#selectUserButton").addEventListener("click", getResultsByUser);
    document.querySelector("#selectRangeButton").addEventListener("click", getResultsByRange);
    document.querySelector("#selectDateRangeButton").addEventListener("click", getResultsByDateRange);
    document.querySelectorAll(".tagsSelector").forEach(element => { element.addEventListener("change", addAnotherDropdown) });
    document.querySelector("#selectTagsButton").addEventListener("click", getAllSelectedTags);
    document.querySelector("#tagsFloatingSelect").addEventListener("change", buildTagsSelectors);
    document.querySelector("#tagsFloatingSelectAggregate").addEventListener("change", buildTagsSelectorsForAggregatedQuiz);
    document.querySelector("#selectQuestionTagsButton").addEventListener("click", getAllSelectedTagsForQuestion);
    document.querySelector("#searchForAggregateQuizResultsByTagsContainer .selectQuestionTagsButton").addEventListener("click", getAllSelectedTagsForAggregatedQuiz);

};

function queryAllUsers() {
    let url = "account/users"; // file name or server-side process name
    let xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function () {
        if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
            let resp = xmlhttp.responseText;
            if (resp.search("ERROR") >= 0) {
                alert("Something is wrong with the queryAllUsers.");
            } else {
                populateUsersSelector(resp);
            }
            //showMainPanel();
        }
    };
    xmlhttp.open("GET", url, true);
    xmlhttp.send();
}

function populateUsersSelector(text) {
    let data = JSON.parse(text);
    let userSelector = document.querySelector("#selectUser");
    let html = "";

    for (let user of data) {
        html += `<option value="${user.username}">${user.username}</option>`;
    }

    userSelector.innerHTML += html;
}

function getResultsByUser() {
    //e.preventDefault();
    let username = document.querySelector("#selectUser").value;

    let url = "quizResults/search:user=" + username;
    // let title = "Quiz Results for " + username;
    let table = document.querySelector("#searchForQuizResultsByUserContainer #quizTable");
    buildQuizResultsSection(url, table);
}

function buildQuizResultsSection(url, element) {
    let xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            let resp = xhr.responseText;
            let data = JSON.parse(resp);
            let html = `<thead> <tr> <th scope="col">User</th> <th scope="col">Quiz ID</th> <th scope="col">Quiz Title</th> <th scope="col">Started</th> <th scope="col">Submitted</th> <th scope="col">Score</th> <th scope="col">Percent</th> </tr></thead>`;

            html += "<tbody>";
            for (let quizResult of data) {
                let startTime = quizResult.startTime.split(" ");
                let endTime = quizResult.endTime.split(" ");
                let percent = quizResult.scoreNumerator / quizResult.scoreDenominator * 100;

                html += "<tr>";
                    html += `<th scope="row">${quizResult.user.username}</th>`;
                    html += `<td>${quizResult.quiz.quizID}</td>`;
                    html += `<td>${quizResult.quiz.quizTitle}</td>`;
                    html += `<td>${startTime[0]} at ${startTime[1]}</td>`;
                    html += `<td>${endTime[0]} at ${endTime[1]}</td>`;
                    html += `<td>${quizResult.scoreNumerator} / ${quizResult.scoreDenominator}</td>`;
                    html += `<td>${percent.toFixed(1)}%</td>`;
                html += "</tr>";
            }
            html += "</tbody>";

            element.innerHTML = html;
        }
    };
    xhr.open("GET", url, true);
    xhr.send();
}

function getResultsByRange() {
    let min = document.querySelector("#minRange").value;
    let max = document.querySelector("#maxRange").value;
    let url = "quizResults/search:scoremin=" + min + "&scoremax=" + max;
    let element = document.querySelector("#searchForQuizResultsByRangeContainer #quizTableByRange");
    buildQuizResultsSection(url, element);
}

function getResultsByDateRange() {
    let min = document.querySelector("#minDate").value;
    let max = document.querySelector("#maxDate").value;

    let url = "quizResults/search:mindate=" + min + "&maxdate=" + max;
    let element = document.querySelector("#searchForQuizResultsByDateRangeContainer #quizTableByDateRange");
    buildQuizResultsSection(url, element);
}


/////
let allTags = [];
function getAllTags() {
    let url="quizapp/catergory";
    let xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function () {
        if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
            let resp = xmlhttp.responseText;
            if (resp.search("ERROR") >= 0) {
                alert("oh no, something is wrong with the populateTagsSelector");
            } else {
                allTags = JSON.parse(resp);
                addAnotherDropdown();
            }
        }
    };
    xmlhttp.open("GET", url, true);
    xmlhttp.send();
}

function addAnotherDropdown() {
    let element = document.querySelector("#tagsSelectorContainer");

    let html = `<select class="form-select w-25 m-2 tagsSelector" aria-label="Select Tag"><option value="0" selected>Pick a tag</option>`;
        for (let tag of allTags) {
            html += `<option value="${tag.tagID}">${tag.tagName.split("::")[1]}</option>`;
        }
    html += `</select>`;
    element.innerHTML += html;

    addAnotherEventListener();
}

function addAnotherEventListener() {
    document.querySelectorAll(".tagsSelector:last-child").forEach(element => { element.addEventListener("change", addAnotherDropdown) })
}

function getAllSelectedTags() {
    let tags = [];
    document.querySelectorAll(".tagsSelector").forEach(tag => tags.push(tag.value));
    let filteredTags = tags.filter(value => value !== "0").join("-");
    let url = "quizResults/search:tags=" + filteredTags;
    let element = document.querySelector("#searchForQuizResultsByTagsContainer #quizTableByTags");
    buildQuizResultsSection(url, element);
}

function buildTagsSelectors() {
    let numberOfTags = Number(document.querySelector("#tagsFloatingSelect").value);
    let element = document.querySelector("#questionsTagsSelectorContainer");

    element.innerHTML = "";
    for (let i = 0; i < numberOfTags; i++) {
        let html = `<select class="form-select w-25 m-2 tagsSelector" aria-label="Select Tag"><option value="0" selected>Pick a tag</option>`;
        for (let tag of allTags) {
            html += `<option value="${tag.tagID}">${tag.tagName.split("::")[1]}</option>`;
        }
        html += `</select>`;
        element.innerHTML += html;
    }
}
function getAllSelectedTagsForQuestion() {
    let tags = [];
    document.querySelectorAll("#questionsTagsSelectorContainer .tagsSelector").forEach(tag => tags.push(tag.value));
    let filteredTags = tags.filter(value => value !== "0").join("-");
    let url = "quizResults/search:questionTags=" + filteredTags;
    let element = document.querySelector("#searchForQuestionsByTagsContainer #quizQuestionTableByTags");

    buildQuestionSection(url, element);
}


function buildTagsSelectorsForAggregatedQuiz() {
    let numberOfTags = Number(document.querySelector("#tagsFloatingSelectAggregate").value);
    let element = document.querySelector("#searchForAggregateQuizResultsByTagsContainer .questionsTagsSelectorContainer");

    element.innerHTML = "";
    for (let i = 0; i < numberOfTags; i++) {
        let html = `<select class="form-select w-25 m-2 tagsSelector" aria-label="Select Tag"><option value="0" selected>Pick a tag</option>`;
        for (let tag of allTags) {
            html += `<option value="${tag.tagID}">${tag.tagName.split("::")[1]}</option>`;
        }
        html += `</select>`;
        element.innerHTML += html;
    }
}
function getAllSelectedTagsForAggregatedQuiz() {
    let tags = [];
    document.querySelectorAll("#searchForAggregateQuizResultsByTagsContainer .tagsSelector").forEach(tag => tags.push(tag.value));
    let filteredTags = tags.filter(value => value !== "0").join("-");
    let url = "quizResults/search:aggregatedQuiz=" + filteredTags;
    let element = document.querySelector("#searchForAggregateQuizResultsByTagsContainer .quizQuestionTableByTags");

    buildAggregatedQuizSection(url, element);
}

function buildQuestionSection(url, element) {
    let xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            let resp = xhr.responseText;
            let data = JSON.parse(resp);
            console.log(data);
            let html = `<thead> <tr> <th scope="col">Question ID</th> <th scope="col">Question Text</th> <th scope="col">Choices</th> <th scope="col">Answer</th>  </tr></thead>`;

            html += "<tbody>";
            for (let question of data) {

                html += "<tr>";
                    html += `<th scope="row">${question.questionID}</th>`;
                    html += `<td>${question.questionText}</td>`;
                    html += `<td>${question.choices}</td>`;
                    html += `<td>${question.answer}</td>`;
                html += "</tr>";
            }
            html += "</tbody>";

            element.innerHTML = html;
        }
    };
    xhr.open("GET", url, true);
    xhr.send();
}
function buildAggregatedQuizSection(url, element) {
    let xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            let resp = xhr.responseText;
            console.log(resp);
            let data = JSON.parse(resp);
            let html = `<thead> <tr> <th scope="col">Quiz ID</th> <th scope="col">Quiz Title</th> <th scope="col">Highest Score</th> <th scope="col">Avg Score</th>  <th scope="col">Lowest Score</th></tr></thead>`;

            html += "<tbody>";
            for (let quiz of data) {

                html += "<tr>";
                html += `<th scope="row">${quiz.quizID}</th>`;
                html += `<td>${quiz.quizTitle}</td>`;
                html += `<td>${quiz.Max}</td>`;
                html += `<td>${quiz.AVG}</td>`;
                html += `<td>${quiz.Min}</td>`;
                html += "</tr>";
            }
            html += "</tbody>";

            element.innerHTML = html;
        }
    };
    xhr.open("GET", url, true);
    xhr.send();
}