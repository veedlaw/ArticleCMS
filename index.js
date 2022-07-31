function showArticlePrompt()
{
    const dialog = document.getElementById("createDialog");
    dialog.open=true;
}
function hideArticlePrompt()
{
    const dialog = document.getElementById("createDialog");
    const articleNameInput = document.getElementById("articleNameInput");
    let createArticleButton = document.getElementById("createArticleButton");
    createArticleButton.disabled = true;
    dialog.open=false;
    articleNameInput.value="";
}

function deleteArticle(id)
{
    const articleRow = document.getElementById(id);
    articleRow.remove();
    updatePagination();
    fetch(`../article/${id}`, {method:'DELETE'});
}

class Paginator
{
    constructor(maxItemsPerPage, currentPage)
    {
        this.maxItemsPerPage = maxItemsPerPage;
        this.currentPage = currentPage;

        this.incrementPage = this.incrementPage.bind(this);
        this.decrementPage = this.decrementPage.bind(this);
        this.paginate = this.paginate.bind(this);
    }

    incrementPage()
    {
        this.currentPage++;
    }

    decrementPage()
    {
        this.currentPage--;
    }

    paginate(table)
    {
        this.prevButton = document.getElementById("prevButton");
        this.nextButton = document.getElementById("nextButton");   

        
        const lastPage = Math.ceil(table.rows.length / this.maxItemsPerPage);
        if (this.currentPage > lastPage)
        {
            this.currentPage = lastPage;
        }
        
        document.getElementById("pageCount").innerHTML = `Page count: ${this.currentPage}/${lastPage}`;

        // hide the prev/next button if appropriate
        if (this.currentPage === 1)
        {
            this.prevButton.style.display = 'none';
            this.nextButton.style.display = 'initial';
            if (this.currentPage === lastPage) this.nextButton.style.display = 'none';

        }
        else if (this.currentPage === lastPage)
        {
            this.prevButton.style.display = 'initial'; 
            this.nextButton.style.display = 'none';
        }
        else
        {
            this.prevButton.style.display = 'initial';
            this.nextButton.style.display = 'initial';
        }

         // handles updating the pages
         for(let i=0; i < table.rows.length; i++) 
         {    
             let row = table.rows[i];
             if ((i >= (this.currentPage-1) * this.maxItemsPerPage) && 
                  i < this.currentPage * this.maxItemsPerPage)
             {
                 row.style.display = 'initial';
             }
             else
             {
                 row.style.display = 'none';
             }
         }
    }
}

var paginator = new Paginator(10, 1);

window.addEventListener('DOMContentLoaded', (event) => {

    updatePagination();
    
    let createButton = document.getElementById("createButton");
    createButton.onclick = showArticlePrompt;

    let nextButton = document.getElementById("nextButton");
    nextButton.addEventListener('click', function() {
        paginator.incrementPage();
        updatePagination();
    })

    let prevButton = document.getElementById("prevButton");
    prevButton.addEventListener('click', function() {
        paginator.decrementPage();
        updatePagination();
    })

    let createArticleButton = document.getElementById("createArticleButton");
    let articleNameInput = document.getElementById("articleNameInput");
    articleNameInput.addEventListener("input", function() {
        createArticleButton.disabled = articleNameInput.value==="";
    })
})

function updatePagination()
{
    paginator.paginate(document.getElementById("articlesTable"));
}