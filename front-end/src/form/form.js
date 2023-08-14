import '../assets/styles/styles.scss'

const form = document.querySelector('form');
const cancelButton = document.querySelector('#cancel');

console.log(location.href)

function setPhotoProfil(data){
    const nav = document.querySelector('.header__nav');
    const user_photo = document.createElement('img');
    user_photo.src = data.user_photo;
    user_photo.alt = 'photo de profil';
    user_photo.classList.add('user_photo');
    nav.insertAdjacentElement('afterbegin',user_photo);
}


function getDataUser(){
    fetch('http://localhost:3000/routes/form.php')
    .then((resp) => {
        resp.json()
        .then((data) => {
            console.log(data)
            const auteur = document.querySelector('#auteur_article');
            auteur.value = data.user_name;
            auteur.disabled = true;
            setPhotoProfil(data)
        })
    })
    .catch((err) => {
        console.log(err)
    })
}

getDataUser()


function udapteArticle(data){
    fetch('http://localhost:3000/form.php',{
        method : 'PATCH',
        headers : {
            'Content-Type' : 'application/json'
        },
        body : JSON.stringify(data)
    })
}

function saveArticle(data){
    fetch('http://localhost:3000/routes/form.php',{
        method : 'POST',
        headers : {
            'Content-Type' : 'application/json'
        },
        body : JSON.stringify(data)
    })
}

form.addEventListener('submit',(e) => {
    e.preventDefault()
    const category_article = document.querySelector('#category_article').value
    const content_article = document.querySelector('#content_article').value;
    const data = {category_article,content_article};
    saveArticle(data)
    location.href = './index.html'
});

cancelButton.addEventListener('click',(e) => {
    e.preventDefault()
    location.href = './index.html'
})
