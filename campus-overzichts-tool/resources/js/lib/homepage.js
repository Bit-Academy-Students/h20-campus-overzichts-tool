searchArticles();

document.getElementById("articleinput").addEventListener("input", () => {
    const sortByName = document.getElementById("articlesort").textContent === "Name";
    const search = document.getElementById("articleinput").value.toLowerCase();
    searchArticles(search, sortByName);
});

document.getElementById("articlesort").addEventListener("click", () => {
    setArticleSort();
});

async function searchArticles(search = "", sortByName = true) {
    //const res = await fetch("");
    //const data = res.json();

    const data = [{ title: "TESTTESTTEST", tags: ["Test", "Test", "Test"] }];

    const maindiv = document.getElementById("articlelist");
    maindiv.innerHTML = "";
    const filteredData = data.filter((article) => {
        if (sortByName) {
            return article.title.toLowerCase().includes(search.toLowerCase());
        }

        return article.tags.some((tag) => tag.toLowerCase().includes(search.toLowerCase()));
    });
    console.log(filteredData);
    
    for (let i = 0; i < filteredData.length; i++) {
        const article = filteredData[i];
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
        title.textContent = article.title;
        tagslabel.setAttribute("class", "tagslabel");
        tagslabel.textContent = "Tags:";
        tags.setAttribute("class", "articleTags");
        tags.textContent = article.tags.join(", ");
        favButton.setAttribute("class", "favButton");
        favButton.setAttribute("type", "checkbox");

        favButton.addEventListener("click", () => {
            if (favButton.checked) {
                setFavorites(i, article.title, true);
            } else {
                setFavorites(i, article.title, false);
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

function setArticleSort() {
    const sortbutton = document.getElementById("articlesort");
    const search = document.getElementById("articleinput").value.toLowerCase();
    console.log(search);

    if (sortbutton.textContent === "Name") {
        sortbutton.textContent = "Tags";
        searchArticles(search, false);
    } else {
        sortbutton.textContent = "Name";
        searchArticles(search, true);
    }
}
