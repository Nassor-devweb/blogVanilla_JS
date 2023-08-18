import '../assets/styles/styles.scss'

const form = document.querySelector('form');
const cancelButton = document.querySelector('#cancel');
let dataUser;

console.log(location.href)

function setPhotoProfil(data){
    const nav = document.querySelector('.header__nav');
    const user_photo = document.createElement('img');
    user_photo.src = data.user_photo;
    user_photo.alt = 'photo de profil';
    user_photo.classList.add('user_photo');
    nav.insertAdjacentElement('afterbegin',user_photo);
}


function getDataUser() {
    fetch('http://localhost:3000/routes/user_info.php')
        .then((resp) => {
            if (resp.status < 299) {
                resp.json()
                    .then((data_user) => {
                        console.log(data_user)
                        dataUser = data_user;
                        const auteur = document.querySelector('#auteur_article');
                        auteur.value = data_user.user_name;
                        auteur.disabled = true;
                    })
                    .catch((err) => {
                        console.log(err)
                    })
            } else {
                location.assign('./login.html')
            }
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
    .then((resp) => {
        if(resp.status < 299 ){{
            resp.json()
        }}else{
            location.assign('./index.html')
        }
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
    .then((resp) => {
        if(resp.status < 299 ){{
            location.assign('./index.html')
        }}else{
            location.assign('./login.html')
        }
    })
}

form.addEventListener('submit',(e) => {
    e.preventDefault()
    const category_article = document.querySelector('#category_article').value
    const content_article = document.querySelector('#content_article').value;
    const data = {category_article,content_article};
    saveArticle(data)
});

cancelButton.addEventListener('click',(e) => {
    e.preventDefault()
    location.href = './index.html'
})
