window.onload = () => {
    // delete link management 
    let links = document.querySelectorAll("[data-delete]")
    
    for(link of links){
        link.addEventListener("click", function(e){
            e.preventDefault() // we cancel the initial action of the click 
            if(confirm("voulez vous supprimer cette image?")){
                //we send an Ajax request to the href of the link with the DELETE method
                fetch(this.getAttribute("href"), {
                    method: "DELETE",
                    headers: {
                        'X-Requested-With': "XMLHttpRequest",
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({"_token": this.dataset.token})
                }).then(
                    // we get the answer in json 
                    response => response.json()
                ).then(data =>{
                    if(data.success)
                        this.parentElement.remove()
                    else
                        alert(data.error)
                }).catch(e => alert(e))
            }
        })
    }
}