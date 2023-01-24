class Like {
    
    constructor() {
        this.wrapper = document.querySelectorAll('[data-js="like-wrapper"]');
    }
    
    init() {
        
        Array.from(this.wrapper).forEach((el, i) => {
            const counter    = el.querySelector('[data-js="counter"]'),
                  likeButton = el.querySelectorAll('[data-btn]'),
                  id         = counter.dataset.id;
            
            this.clickLikesBtn(likeButton, id, counter);
        });
    }
    
    ajax(id, likeValues, counter) {
        const xhr = new XMLHttpRequest(),
              url = ajaxUrl.url;
        
        const data = 'action=set_like&id=' + id + '&like=' + likeValues['like'] + '&dislike=' + likeValues['dislike'];
        
        xhr.responseType = 'json';
        xhr.open('POST', url, true);
        
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    
        xhr.send(data);
        
        xhr.addEventListener('readystatechange', () => {
            
            if (xhr.readyState === 4 && xhr.status === 200) {
                
                if (xhr.response.result) {
                    let elementValue = counter.innerHTML,
                        increment = 2;
                    console.log(xhr.response.is_new_user)
                    if (xhr.response.is_new_user) {
                        increment = 1;
                    }
                    
                    if(likeValues['like']) {
                        counter.innerHTML = +elementValue + increment;
                        return;
                    }
    
                    counter.innerHTML = +elementValue - increment;
                    return;
                }
            }
        });
    }
    
    clickLikesBtn(selector, id, counter) {
        Array.from(selector).forEach((el) => {
            el.addEventListener('click', (event) => {
                const likeProp   = event.currentTarget.dataset.btn;
                const likeValues = {
                    like: false,
                    dislike: false,
                };
                
                likeValues[likeProp] = true;
                
                this.ajax(id, likeValues, counter);
            });
        });
    }
}

const like = new Like();

like.init();
