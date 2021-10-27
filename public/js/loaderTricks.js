loader = document.getElementById('loadMoreTrick');
// let zoneTexte = document.getElementById('zoneLoader');
let zoneTexte = document.getElementById('zoneTricks');
// let texte = "salut alex";
let offset = 3;
let clickedButton= 0;


loader.addEventListener('click', function(event) {
    event.preventDefault();
    clickedButton++;

    // fetch(this.getAttribute("href")) // ici c est mon bouton href
    fetch('/loadTricks/'+offset*clickedButton) // ici c est mon bouton href
        .then(function(res) {
            if (res.ok) {
                return res.json();
            }
        })
        .then(function(value) {
            let dataResponseJson = value.dataResponse;	//on recupére nos données en objet json transmis via le controller HomeController function loadTricks
            const dataResponseJavascript = JSON.parse(dataResponseJson); // on parse notre objet json en un objet javascript
            console.log(dataResponseJavascript);

            for (let trick of dataResponseJavascript) {
                
                // on affiche nos donnée sur le site
                    //pour connaitre la picture a afficher
                    let picture;
                    if(trick.pictures[0] === undefined) {
                        picture = "/pictures/site/no_picture.jpg";
                    } else {
                        picture = "/pictures/contributions/"+trick.pictures[0].pictureFileName;
                    }
                    // on affiche nos donnée sur le site
                    let content = `
                                <div class="card">
                                    <img class="card-img-top" src="`+picture+`" alt="noPicture">
                                    <div class="card-body">
                                        <h5 class="card-title"><a href="/trick/`+trick.slug+`">`+trick.name+`</a></h5>
                                        <p class="card-text">`+trick.description+`</p>
                                    </div>
                                </div>
                    `;

                    // let test = "salut nico";
                    let newEltement = document.createElement("div" );
                    newEltement.className = 'col-sm-4';
                    newEltement.innerHTML = content; 
                    zoneTexte.appendChild(newEltement);

                    // zoneTexte.innerHTML = content;
            }
        })
        .catch(function(err) {
            console.log(err);
        });
});


// loader.addEventListener('click', function(event) {
//     event.preventDefault();

//     fetch(this.getAttribute("href")) // ici c est mon bouton href
//         .then(function(res) {
//             if (res.ok) {
//                 return res.json();
//             }
//         })
//         .then(function(value) {
//             let dataResponseJson = value.dataResponse;	//on recupére nos données en objet json transmis via le controller HomeController function loadTricks
//             const dataResponseJavascript = JSON.parse(dataResponseJson); // on parse notre objet json en un objet javascript
//             console.log(dataResponseJavascript);

//             // on affiche nos donnée sur le site
//                 //pour connaitre la picture a afficher
//                 let picture;
//                 if(dataResponseJavascript.pictures[0] === undefined) {
//                     picture = "/pictures/site/no_picture.jpg";
//                 } else {
//                     picture = "/pictures/contributions/"+dataResponseJavascript.pictures[0].pictureFileName;
//                 }
//                 // on affiche nos donnée sur le site
//                 let content = `
//                     <div class="col-sm-4">
//                         <div class="card">
//                             <img class="card-img-top" src="`+picture+`" alt="noPicture">
//                             <div class="card-body">
//                                 <h5 class="card-title"><a href="/trick/`+dataResponseJavascript.slug+`">`+dataResponseJavascript.name+`</a></h5>
//                                 <p class="card-text">`+dataResponseJavascript.description+`</p>
//                             </div>
//                         </div>
//                     </div>
//                 `;
//                 zoneTexte.innerHTML = content;
             
//         })
//         .catch(function(err) {
//             console.log(err);
//         });
// });


// loader = document.getElementById('loadMoreTrick');
// let zoneTexte = document.getElementById('zoneLoader');
// // let texte = "salut alex";

// loader.addEventListener('click', function(event) {
//     event.preventDefault();

//     fetch(this.getAttribute("href")) // ici c est notre bouton href
//         .then(function(res) {
//             if (res.ok) {
//                 return res.json();
//             }
//         })
//         .then(function(value) {
//             let dataResponse = value.dataResponse;	//on recupére nos données en objet javascript transmis en json via le controller HomeController function loadTricks
//             const newEltement = document.createElement("p");
//             newEltement.innerHTML = dataResponse; 
//             zoneTexte.appendChild(newEltement);
             
//         })
//         .catch(function(err) {
//             console.log(err);
//         });
// });


// let loader = document.getElementById('loadMoreTrick');
		
// loader.addEventListener('click', function(event) {          // On écoute l'événement click
//     event.preventDefault();                         
//     loader.style.backgroundColor = "yellow";              // On change le contenu de notre élément pour afficher "C'est cliqué !"
// });

// let loader = document.getElementById('loadMoreTrick');
// let zoneTexte = document.getElementById('zonetexte');
// let texte = "salut nicolas";
// zoneTexte.innerHTML = texte;

// let loader = document.getElementById('loadMoreTrick');
// let zoneTexte = document.getElementById('zoneLoader');
// let texte = "salut alex";

// loader.addEventListener('click', function(event) {
//     event.preventDefault();                         
//     const newEltement = document.createElement("p");
//     newEltement.innerHTML = texte; 
//     zoneTexte.appendChild(newEltement);
// });