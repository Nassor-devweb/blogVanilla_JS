import './assets/styles/styles.scss'

const confirm_delete = document.querySelector('.invisible');
const deleteButton = document.querySelector('#delete');
let deleteId;
let id_user;


function getDataUser() {
    fetch('http://localhost:3000/routes/user_info.php')
        .then((resp) => {
            if (resp.status < 299) {
                resp.json()
                    .then((data_user) => {
                        console.log(data_user)
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

deleteButton.addEventListener('click', (e) => {
    const id_article = { 'id_article': deleteId }
    fetch('http://localhost:3000/routes/index.php', {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(id_article)
    })
        .then((resp) => {
            deleteId = null;
            confirm_delete.setAttribute('id', 'confirm-delete')
            getAllArticle()
        })

})

getDataUser()

function affichage(data_article) {
    const allArticle = data_article.map((curr) => {
        const li = document.createElement('li')
        li.classList.add('article')

        const photo_user = document.createElement('img')
        photo_user.src = `${curr.user_photo}`;
        photo_user.alt = 'photo de profil';

        const title = document.createElement('h2');
        title.textContent = `${curr.category_article}`;
        console.log(curr.date_created)
        const span = document.createElement('span');
        const date = curr.date_created;
        span.textContent = date.toLocaleString('fr-FR');

        const content = document.createElement('p')
        content.textContent = `${curr.content_article}`

        const buttonContenaire = document.createElement('div')
        buttonContenaire.classList.add('btn-content')

        const button_primary = document.createElement('button')
        button_primary.classList.add('btn')
        button_primary.classList.add('btn-primary')
        button_primary.textContent = 'Supprimer';
        button_primary.addEventListener('click', (e) => {
            e.preventDefault()
            confirm_delete.removeAttribute('id')
            deleteId = curr.id_article;
        })

        const button_secondary = document.createElement('button')
        button_secondary.classList.add('btn')
        button_secondary.classList.add('btn-secondary')
        button_secondary.textContent = 'Modifier'

        buttonContenaire.append(button_secondary, button_primary)

        li.append(photo_user, title, span, content, buttonContenaire);
        return li
    })

    const ul = document.createElement('ul');
    ul.classList.add('article_contenaire');
    ul.append(...allArticle);
    const section = document.querySelector('.content')
    section.innerHTML = '';
    section.append(ul)
}

function getAllArticle() {
    fetch('http://localhost:3000/routes/index.php')
        .then((resp) => {
            if (resp.status < 299) {
                resp.json()
                    .then((data) => {
                        console.log(data)
                        affichage(data)
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

getAllArticle()