// Добавляем элемент <meta itemprop="itemReviewed"> в разметку каждого комментария (для соблюдения правил микроразметки schema.org)

document.addEventListener('DOMContentLoaded', () => {
    let comments = document.querySelectorAll('.comment-post'); // Находим все комментарии
    let productForItemReviewed = document.querySelector('h1'); // Получаем название товара
    if (comments && productForItemReviewed) {
        let elemItemReviewed = document.createElement("meta"); // Создаем элемент <meta>
        elemItemReviewed.setAttribute('itemprop', 'itemReviewed'); // Доавляем к нему атрибут itemprop
        elemItemReviewed.setAttribute('content', productForItemReviewed.outerText); // Добавляем к нему атрибут content
        for (let comment of comments) {
            // Вставляем в разметку каждого комментария <meta itemprop="itemReviewed" content="Название товара">
            comment.insertAdjacentHTML('afterBegin', elemItemReviewed.outerHTML);
        }
    }
});