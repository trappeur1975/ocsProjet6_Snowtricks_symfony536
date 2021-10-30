
let zoneTricks = document.getElementById('zoneTricks');
// let allCardTrick = document.querySelectorAll('#zoneTricks div.col-sm-4');
// let lastCardTrick = allCardTrick[allCardTrick.length - 1];

let cardTick = document.querySelector('#zoneTricks div.col-sm-4');
// console.log(cardTick);

//---------------

loader = document.getElementById('loadMoreTrick');
// let zoneTexte = document.getElementById('zoneLoader');
let zoneTexte = document.getElementById('zoneTricks');

let offset = 3;
let clickedButton= 0;

// console.log(lastCardTrick);

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
            // on recupere les donnee en json puis on les transforme en javascript
            let dataJsonTricks = value.dataJsonTricks;
            let dataJavascriptTricks = JSON.parse(dataJsonTricks);
            // console.log(dataJavascriptTricks);
            // console.log(dataJavascriptTricks[1].user.email);

            let dataJsonUserConnected = value.dataJsonUserConnected;
            let dataJavascriptUserConnected = JSON.parse(dataJsonUserConnected);
            // console.log(dataJavascriptUserConnected.email);

            // let dataJsonTemplateDeleteTrick = value.dataJsonTemplateDeleteTrick;
            // let dataJavascriptTemplateDeleteTrick = JSON.parse(dataJsonTemplateDeleteTrick);

        for (let trick of dataJavascriptTricks) {
            // on affiche nos données sur le site
                //pour connaitre la picture a afficher
                    let picture;
                    if(trick.pictures[0] === undefined) {
                        picture = "/pictures/site/no_picture.jpg";
                    } else {
                        picture = "/pictures/contributions/"+trick.pictures[0].pictureFileName;
                    }

                // pour savoir si on affiche les icone edit, delete // a l utilisateur connecter
                let iconesUser = '<br>';
                if( dataJavascriptUserConnected !== null){
                    if(trick.user.email === dataJavascriptUserConnected.email) {
                        // console.log(dataJavascriptUserConnected.email);
                        iconesUser = `
                            <div class="btn-group" role="group" aria-label="gestion du trick">
                                <a href="/trick/`+trick.slug+`"><i class="btn btn-success btn-sm bi bi-eye-fill"></i></a>
                                <a href="/trick/`+trick.id+`/edit"><i class="btn btn-warning btn-sm bi bi-pencil-fill"></i></a>
                                <a href="/trick/delete/`+trick.id+`"><i class="btn btn-danger btn-sm bi bi-trash"></i></a>
                            </div>
                        `;
                    }
                }

                // on affiche nos donnée sur le site
                let content = `
                            <div class="card">
                                <img class="card-img-top" src="`+picture+`" alt="noPicture">
                                <div class="card-body">
                                    <h5 class="card-title"><a href="/trick/`+trick.slug+`">`+trick.name+`</a></h5>
                                    
                                    `+iconesUser+`
                                    
                                    <p class="card-text">`+trick.description+`</p>
                                    
                                </div>
                            </div>
                `;
            
                let newEltement = document.createElement("div" );
                newEltement.className = 'col-sm-4';
                newEltement.innerHTML = content; 
                zoneTexte.appendChild(newEltement);


        // ----------------------ancien code 1---------------------

            // let dataResponseJson = value.dataResponse;	//on recupére nos données en objet json transmis via le controller HomeController function loadTricks
            // const dataResponseJavascript = JSON.parse(dataResponseJson); // on parse notre objet json en un objet javascript
            // console.log(dataResponseJavascript);

            // for (let trick of dataResponseJavascript) {
                
            //     // on affiche nos donnée sur le site
            //         //pour connaitre la picture a afficher
            //         let picture;
            //         if(trick.pictures[0] === undefined) {
            //             picture = "/pictures/site/no_picture.jpg";
            //         } else {
            //             picture = "/pictures/contributions/"+trick.pictures[0].pictureFileName;
            //         }
            //         // on affiche nos donnée sur le site
            //         let content = `
            //                     <div class="card">
            //                         <img class="card-img-top" src="`+picture+`" alt="noPicture">
            //                         <div class="card-body">
            //                             <h5 class="card-title"><a href="/trick/`+trick.slug+`">`+trick.name+`</a></h5>
            //                             <p class="card-text">`+trick.description+`</p>
            //                         </div>
            //                     </div>
            //         `;
              
            //         let newEltement = document.createElement("div" );
            //         newEltement.className = 'col-sm-4';
            //         newEltement.innerHTML = content; 
            //         zoneTexte.appendChild(newEltement);


        //     // ----------------------ancien code 2---------------------
        //         // //  on recupere une card trick
        //         // let cloneCardTrick = cardTick.cloneNode(true);

        //         // //definit les champs a modifier
        //         // let ZonePicture  = cloneCardTrick.querySelector('.card-img-top');
        //         // let zoneDescription = cloneCardTrick.querySelector('.card-text');
        //         // let zoneTitle = cloneCardTrick.querySelector('.card-title a');
        //         // // console.log(zoneTitleHreft);
            
        //         // //  pour connaitre la picture a afficher
        //         // let srcPicture;
        //         // if(trick.pictures[0] === undefined) {
        //         //     srcPicture = "/pictures/site/no_picture.jpg";
        //         // } else {
        //         //     srcPicture = "/pictures/contributions/"+trick.pictures[0].pictureFileName;
        //         // }
                    
        //         // //  on modifier le contenu des card trick
                
        //         // ZonePicture.setAttribute("src", srcPicture);
        //         // zoneTitle.innerHTML = trick.name;
        //         // zoneTitle.setAttribute("href", "/trick/"+trick.slug);
        //         // zoneDescription.innerHTML = trick.description;
                    
            
        //         // //on affiche la card trick modifier
        //         // zoneTricks.appendChild(cloneCardTrick);


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