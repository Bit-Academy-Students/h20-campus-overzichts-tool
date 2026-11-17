document.getElementById("articleinput").addEventListener("input", () => {
    searchArticles();
});

async function searchArticles() {
    //const res = await fetch("");
    //const data = res.json();

    const data = [{title: "TESTTESTTEST", tags: ["Test", "Test", "Test"]}];

    const maindiv = document.getElementById("articlelist");

    for (let i = 0; i < data.length; i++) {
        const articleDiv = document.createElement("div");
        const titleContainer = document.createElement("div");
        const favContainer = document.createElement("div"); 
        const tagsContainer = document.createElement("div");
        const title = document.createElement("h4");
        const tagslabel = document.createElement("p");
        const tags = document.createElement("p");
        const favButton = document.createElement("input");

        articleDiv.setAttribute("class", "articleDiv");
        titleContainer.setAttribute("class", "titleContainer")
        favContainer.setAttribute("class", "favContainer");
        tagsContainer.setAttribute("class", "tagsContainer")
        title.setAttribute("class", "articleTitle");
        title.textContent = data[i].title;
        tagslabel.setAttribute("class", "tagslabel");
        tagslabel.textContent = "Tags:";
        tags.setAttribute("class", "articleTags");
        tags.textContent = JSON.stringify(data[i].tags).replaceAll("[", "").replaceAll("]", "").replaceAll("\"","");
        favButton.setAttribute("class", "favButton");
        favButton.setAttribute("type", "checkbox");

        favButton.addEventListener("click", () => {
            if (favButton.checked) {
                setFavorites(i, data[i].title, true);
            } else {
                setFavorites(i, data[i].title, false);
            }
        });

        maindiv.appendChild(articleDiv);
        articleDiv.appendChild(titleContainer);
        articleDiv.appendChild(favContainer);
        titleContainer.appendChild(title);
        titleContainer.appendChild(tagsContainer);
        favContainer.appendChild(favButton);
        tagsContainer.appendChild(tagslabel);
        tagsContainer.appendChild(tags);
    }
}

//async function searchCompanies() {
//    const res = await fetch("");
//    const data = res.json();
//
//}
//
//function displayContent() {
//
//}

function setFavorites(id, title, add) {
    if (add) {
        console.log(`${id} ${title} added`);
    } else {
        console.log(`${id} ${title} removed`)
    }
}