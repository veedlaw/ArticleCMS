function trySendForm()
{
    let nameInput = document.getElementById("nameInput");
    if (nameInput.value === "")
    {
        alert("Article name may not be empty!");
        return;
    }
    else
    {
        window.location.href='../articles/';
    }
}

window.addEventListener('DOMContentLoaded', (event) => {
    let saveButton = document.getElementById("saveButton");
    saveButton.onclick = trySendForm;
})