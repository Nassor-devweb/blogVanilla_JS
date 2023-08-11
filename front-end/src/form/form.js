import '../assets/styles/styles.scss'

const form = document.querySelector('form');
const cancelButton = document.querySelector('#cancel');

console.log(location.href)

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
    fetch('http://localhost:3000/form.php',{
        method : 'POST',
        headers : {
            'Content-Type' : 'application/json'
        },
        body : JSON.stringify(data)
    })
}

form.addEventListener('submit',(e) => {
    e.preventDefault()
    const category_article = document.querySelector('#category_article')
    const content_article = document.querySelector('#content_article');
    const data = {category_article,content_article};
    saveArticle(data)
});

cancelButton.addEventListener('click',(e) => {
    e.preventDefault()
    location.href = './index.html'
})
