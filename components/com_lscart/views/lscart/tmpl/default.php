<?php
defined('_JEXEC') or die();
JImport('b0.Cart.CartConfig');
$user = JFactory::getUser();
?>
<h1 class="uk-text-center-medium">Корзина покупок</h1>
<div id="cart">
    <nav class="uk-navbar uk-margin hidden-print" id="cart-controls-top">
        <div class="uk-navbar-flip" style="margin-right: 10px;">
            <ul class="uk-subnav uk-subnav-line" style="margin-top: auto;">
                <li>
                    <a href="/spareparts">
                        <i class="uk-icon-reply uk-icon-small uk-margin-right uk-icon-hover"></i>Продолжить выбор
                    </a>
                </li>
                <li>
                    <a href="#" v-on:click="deleteAllRows()" title="Очистить корзину">
                        Очистить корзину<i class="uk-icon-close uk-icon-small uk-margin-left uk-icon-hover"></i>
                    </a>
                </li>
            </ul>
        </div>
    </nav>

    <hr class="uk-article-divider">

    <div id="product-list">
        <table class="uk-table">
            <thead>
                <tr>
                    <td>Товар</td>
                    <td></td>
                    <td class="uk-text-center">Цена</td>
                    <td class="uk-text-center">Количество</td>
                    <td class="uk-text-center">Сумма</td>
                    <td></td>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(item, index) in items" :key="item.id">
                    <td><img :src="item.image" width="160" height="120" style="width: 160px; height: 120px;" :alt="item.title"></td>
                    <td>
                        <p><strong>Код товара: </strong>{{item.productCode}}</p>
                        <p class="b0-title-related">
                            <a :href="item.url" target="_blank">{{item.title}}</a>
                            <span v-if="item.isSpecial" class="uk-text-danger">- спецпредложение</span>
                        </p>
                        <p class="ls-sub-title">{{item.subTitle}}</p>
<!--                        <hr class="uk-article-divider">-->
<!--                        <p class="ls-sub-title">Всего доступно: {{item.availabilityTotal}}</p>-->
                        <p class="uk-text-danger uk-text-bold" v-show="item.quantity == item.availabilityTotal" >Всего доступно: {{item.availabilityTotal}}</p>
<!--                        <p class="ls-sub-title" v-if="item.availabilityTotal < 10" >Всего доступно: {{item.availabilityTotal}}</p>-->
<!--                        <p class="ls-sub-title" v-else >Всего доступно: много</p>-->
                    </td>
                    <td class="b0-price b0-price-related uk-text-center uk-table-middle">{{item.priceCurrent}}</td>
                    <td class="uk-text-center uk-table-middle">
                        <input v-model="item.quantity" v-on:change="calcItems" type="number" min="1" :max="item.availabilityTotal" title="Введите количество" style="width: 50px;">
                    </td>
                    <td class="b0-price b0-price-related uk-text-center uk-table-middle">
                        {{item.amountCurrent}}
                    </td>
                    <td class="uk-text-center uk-table-middle" v-on:click="deleteRow(index, item.id)"><i class="uk-icon-trash-o uk-icon-small uk-icon-hover"></i></td>
                </tr>
            </tbody>
        </table>
    </div>

    <div id="cart-total" class="uk-grid">
        <div class="uk-width-medium-1-2 uk-hidden-small">
        </div>
        <div class="uk-width-1-2">
            <p class="uk-h4 uk-text-right">
                Общая стоимость товаров: {{calcSumGoods}} руб.
            </p>
        </div>
    </div>

    <div class="uk-grid" data-uk-grid-margin>
        <div class="uk-width-medium-1-2">
            <div class="uk-panel uk-panel-box uk-panel-box-secondary uk-hidden-small hidden-print">
                <p class="uk-h4">Порядок оформления заказа при самовывозе.</p>
                <p>Для оформления заказа регистрация на сайте не требуется.</p>
                <p>После оформления заказа Вы получите СМС на указанный номер и письмо на электронную почту.</p>
                <p>В рабочее время с Вами свяжется менеджер для уточнения всех параметров заказа, согласования условий и времени забора товара с магазина.</p>
                <p><a href="/about-us#delivery" title="Подробный порядок оформления Заказа при Самовывозе" target="_blank">Прочитать подробнее...</a></p>
            </div>
        </div>
        <div class="uk-width-medium-1-2 uk-width-small-1-1">
            <div class="uk-panel uk-panel-box hidden-print">
                <form class="uk-form uk-form-horizontal" v-on:submit.prevent="submit">
                    <div class="uk-form-row">
                        <label class="uk-form-label" style="width: 150px" for="form-destination">Способ получения</label>
                        <div class="uk-form-controls uk-form-controls-text uk-text-danger uk-text-bold">Самовывоз</div>
<!--                        <input type="text" placeholder="Самовывоз" class="uk-form-danger uk-form-blank">-->
                    </div>
                    <div class="uk-form-row">
                        <label class="uk-form-label" style="width: 150px" for="form-shop">Магазин*</label>
                        <select v-model="shop" id="form-shop">
                            <option value="<?= CartConfig::CART_ID_GAGARINA ?>">ул. Кузнецовская, 52 к.13</option>
                            <option value="<?= CartConfig::CART_ID_KHIMIKOV ?>">Кудровский пр-д, 5</option>
                            <option value="<?= CartConfig::CART_ID_KULTURY ?>">1-й Верхний пер., 10</option>
                            <option value="<?= CartConfig::CART_ID_ZHUKOVA ?>">ул. Портовая, 15-Б</option>
                            <option value="<?= CartConfig::CART_ID_PLANERNAYA ?>">ул. Планерная, 15-Б</option>
                        </select>
                    </div>
                    <div class="uk-form-row">
                        <label class="uk-form-label" style="width: 150px" for="form-surname">Фамилия*</label>
                        <input type="text" v-model="customerSurname" v-bind:class="{'uk-form-danger':!validation.surname}"
                           id="form-name" name="name" placeholder="Ваша фамилия"/>
                    </div>
                    <div class="uk-form-row">
                        <label class="uk-form-label" style="width: 150px" for="form-name">Имя*</label>
                        <input type="text" v-model="customerName" v-bind:class="{'uk-form-danger':!validation.name}"
                           id="form-name" name="name" placeholder="Ваше имя"/>
                    </div>
                    <div class="uk-form-row">
                        <label class="uk-form-label" style="width: 150px" for="form-phone">Телефон*</label>
                        <span class="uk-text-bold">+7 </span><input type="tel" v-model="customerPhone" v-bind:class="{'uk-form-danger':!validation.phone}" v-phone
                               id="form-phone" name="phone" placeholder="(999)999-99-99"/>
                    </div>
                    <div class="uk-form-row">
                        <label class="uk-form-label" style="width: 150px" for="form-email">Email*</label>
                        <input type="email" v-model="customerEmail" v-bind:class="{'uk-form-danger':!validation.email}"
                               id="form-email" name="email" placeholder="Ваш Email" />
                    </div>
                    <div class="uk-margin-top">
                        <p>
                            <small>
                                Нажимая кнопку «Оформить заказ», я даю свое согласие на обработку моих персональных данных,
                                в соответствии с Федеральным законом от 27.07.2006 года №152-ФЗ «О персональных данных»,
                                на условиях и для целей, определенных в
                                <a href="/politika-konfidentsialnosti" target="_blank" title="Согласие на обработку персональных данных">
                                    Согласии на обработку персональных данных
                                </a>
                            </small>
                        </p>
                    </div>

                    <div class="uk-form-row">
                        <button type="submit" v-bind:disabled="!isValid"
                                id="form-cart-save" class="uk-button uk-button-large uk-button-success">
                            Оформить заказ
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Окно сообщений -->
    <div id="lscart-alert" class="uk-modal" style="overflow-y: scroll;">
        <div class="uk-modal-dialog">
            <button type="button" class="uk-modal-close uk-close"></button>
            <div class="uk-modal-header" id="alert-header"></div>
            <div  id="alert-body"></div>
            <div class="uk-modal-footer uk-text-right"></div>
        </div>
    </div>
</div>
<!--<script src="https://cdn.jsdelivr.net/npm/vue@2/dist/vue.js"></script>-->
<!--<script src="https://cdn.jsdelivr.net/npm/vue@2" defer></script>-->
<!--<script src="https://unpkg.com/axios/dist/axios.min.js"></script>-->
<script src="/templates/b0/js/axios-1-5.min.js"></script>
<script src="/templates/b0/js/vue-2-7-16.js"></script>

<script>
    const surnameRE = /^\D+$/i;
    const nameRE = /^\D+$/i;
    const emailRE = /^[\w-\.]+@[\w-\.]+\.[a-z]{2,4}$/i;
    const phoneRE = /^\(9[0-9]{2}\)[0-9]{3}-[0-9]{2}-[0-9]{2}$/;
    const cityRE = /^\D+$/i;
    let app = new Vue({
        el: '#cart',
        data: {
            items: [],
            sumGoods: 0,
            customerSurname: '',
            customerName: '',
            customerPhone: '',
            customerEmail: '',
            shop: 'ВВ-000001',
            showConfirm: false
        },
        computed: {
            calcSumGoods: function () {
                let calcSumGoods = 0;
                for (let item of this.items) {
                    calcSumGoods += item.amountCurrent;
                }
                this.sumGoods = calcSumGoods;
                return calcSumGoods;
            },
            validation: function () {
                return {
                    name: nameRE.test(this.customerName),
                    surname: surnameRE.test(this.customerSurname),
                    phone: phoneRE.test(this.customerPhone),
                    email: emailRE.test(this.customerEmail),
                };
            },
            isValid: function () {
                let validation = this.validation;
                return Object.keys(validation).every(function (key) {
                    return validation[key];
                });
            }
        },
        methods: {
            calcItems: function () {
                let sum = 0;
                for (let item of this.items) {
                    if (!item.isSpecial) {
                        if (item.isOriginal) {
                            item.priceCurrent = Math.round(item.priceGeneral * 1.00);
                            item.amountCurrent = Math.round(item.priceGeneral * item.quantity * 1.00);
                            sum += Math.round(item.priceGeneral * item.quantity * 1.00);
                        }
                        else {
                            item.priceCurrent = Math.round(item.priceGeneral * 1.00);
                            item.amountCurrent = Math.round(item.priceGeneral * item.quantity * 1.00);
                            sum += Math.round(item.priceGeneral * item.quantity * 1.00);
                        }
                    }
                    else {
                        item.priceCurrent = item.priceSpecial;
                        item.amountCurrent = item.priceSpecial * item.quantity;
                        sum += item.priceSpecial * item.quantity;
                    }
                }
            },
            deleteRow: function (index, id) {
                let vm = this;
                UIkit.modal.confirm('Удалить этот элемент?', function () {
                        axios.get('/index.php?option=com_lscart&task=cart.delete&item_id='+id)
                            .then(function(response) {
                                vm.items.splice(index, 1);
                            })
                            .catch(function(error) {
                                console.log(error);
                                confirm(error);
                            })
                    },
                    {
                        labels: {
                            Cancel: 'Отмена',
                            Ok: 'Удалить'
                        }
                    }
                );
            },
            deleteAllRows: function () {
                let vm = this;
                UIkit.modal.confirm('Очистить корзину?', function () {
                        axios.get('/index.php?option=com_lscart&task=cart.deleteAll')
                            .then(function (response) {
                                // this.items=[];
                                location.assign('/');
                            })
                            .catch(function (error) {
                                console.log(error);
                                confirm(error);
                            });
                    },
                    {
                        labels: {
                            Cancel: 'Отмена',
                            Ok: 'Очистить'
                        }
                    }
                );
            },
            submit: function () {
                if (this.isValid) {
                    let cartData = {
                        shop: this.shop,
                        customer: {
                            name: this.customerName,
                            surname: this.customerSurname,
                            phone: this.customerPhone,
                            email: this.customerEmail
                        },
                        items: this.items
                    };
                    axios.post('/index.php?option=com_lscart&task=cart.save', cartData)
                        .then((response) => {
                            // console.log(response);
                            if (typeof VK !== "undefined" && VK !== null) {
                                VK.Goal('purchase');
                            }

                            let alertBody = '';
                            alertBody = '<p class="uk-text-center">Уважаемый ' + cartData.customer.surname + ' ' + cartData.customer.name +'</p>';
                            alertBody += '<p class="uk-text-center">Мы перезвоним Вам в течение часа в рабочее время</p>';
                            alertBody += '<p class="uk-text-center">Также мы отправили Вам подтверждение на ' + cartData.customer.email + '</p>';
                            alertBody += '<p class="uk-text-center">Спасибо за обращение в Логан-Шоп</p>';

                            UIkit.modal.confirm(alertBody, function () {
                                    location.assign('/');
                                },
                                function () {
                                    location.assign('/cart');
                                },
                                {
                                    labels: {
                                        Cancel: 'Закрыть',
                                        Ok: 'На главную'
                                    }
                                });
                        })
                        .catch((error) => {
                            console.log(error);
                            confirm(error);
                        })
                }
                else {
                    UIkit.modal.alert('Проверьте введенные данные');
                }
            }
        },
        created: function () {
            this.items = <?= json_encode($this->cart->goods, JSON_THROW_ON_ERROR | JSON_INVALID_UTF8_IGNORE) ?>;
            this.calcItems();
        },
        directives: {
            phone: {
                bind(el) {
                    el.oninput = function (e) {
                        if (!e.isTrusted) {
                            return;
                        }
                        const x = this.value.replace(/\D/g, '').match(/(\d{0,3})(\d{0,3})(\d{0,2})(\d{0,2})/);
                        this.value = !x[2] ? x[1] : '(' + x[1] + ')' + x[2] + (x[3] ? '-' + x[3] : '') + (x[4] ? '-' + x[4] : '');
                        el.dispatchEvent(new Event('input'));
                    }
                }
            }
        }
    });
</script>
