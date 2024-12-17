'use strict';
let elem = document.getElementsByClassName('cart-add');
for (let i = 0; i < elem.length; i++) {
    elem[i].addEventListener('click' , cartAdd);
}

function cartAdd(event) {
    let target = event.target;
    //Получаем значения ID элемента и количества
    let itemId = target.getAttribute("data-item-id");
    let formQuantity = document.getElementById('cart-quantity'+itemId);
    let itemQuantity = formQuantity.value;
    let inCart = target.getAttribute("data-in-cart");

    target.innerHTML = '<img src="<?= JUri::root(TRUE);?>/components/com_cobalt/images/load.gif"/>';
    let xhr = new XMLHttpRequest();
    let body = '';
    let task = '';
    if (itemQuantity === '0') { // Удаляем
        body = 'item_id='+itemId;
        task = 'index.php?option=com_lscart&task=cart.delete';
    }
    else {  // Изменяем
        body = 'item_id='+itemId+'&item_quantity='+itemQuantity;
        task = 'index.php?option=com_lscart&task=cart.add';
    }

    xhr.open("POST", task, true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.send(body);

    xhr.onreadystatechange = function() {
        if (this.readyState != 4) {
            return;
        }

        if (xhr.status != 200) {
            alert(xhr.status + ': ' + xhr.statusText);
        }
        else {
            //Кнопка - target
            target.setAttribute("data-in-cart", itemQuantity);
            if (itemQuantity === '0') { //Удаляем
                target.innerHTML = 'Добавить в корзину';
            }
            else {  // Изменяем
                //if (inCart === '0') {
                target.innerHTML = 'Изменить';
                //}
            }

            //Уже в корзине- cartAlready
            let cartAlready = document.getElementById('cart-already'+itemId);
            if (itemQuantity === '0') { // Удаляем
                cartAlready.hidden = true;

            }
            else {  // Изменяем
                cartAlready.lastChild.textContent = formQuantity.value;
                //if (inCart === '0') {
                cartAlready.hidden = false;
                //}
            }

            //Модуль корзины
            let elemCartCount = document.getElementById('cart-count');
            let elemCartCountSmall = document.getElementById('cart-count-small');
            //let cartCount = Number(document.getElementById('cart-count').textContent);

            if (itemQuantity === '0') {
                //document.getElementById('cart-count').textContent = String(cartCount - 1);
                elemCartCount.textContent = String(Number(elemCartCount.textContent) - 1);
                elemCartCountSmall.textContent = String(Number(elemCartCountSmall.textContent) - 1);
            }
            else {
                if (inCart === '0') {   // Это новый элемент в корзине
                    //document.getElementById('cart-count').textContent = String(cartCount + 1);
                    elemCartCount.textContent = String(Number(elemCartCount.textContent) + 1);
                    elemCartCountSmall.textContent = String(Number(elemCartCountSmall.textContent) + 1);
                }
            }

            //Количество- itemQuantity, определено выше
            if (itemQuantity === '0') { // Удаляем
                document.getElementById('cart-quantity'+itemId).value = 1;
                document.getElementById('cart-quantity'+itemId).setAttribute('min', 1);
            }
            else {  // Изменяем
                document.getElementById('cart-quantity'+itemId).value = 0;
                document.getElementById('cart-quantity'+itemId).setAttribute('min', 0);
            }
        }
    }
}
