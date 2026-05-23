class Pizza {
    static BASE_PIZZAS = {
        margarita: { name: 'Маргарита', price: 500, calories: 300 },
        pepperoni: { name: 'Пепперони', price: 800, calories: 400 },
        bavarian:  { name: 'Баварская', price: 700, calories: 450 }
    };

    static SIZES = {
        small:  { name: 'Маленькая', extraPrice: 100, extraCalories: 100 },
        large:  { name: 'Большая',   extraPrice: 200, extraCalories: 200 }
    };

    static TOPPINGS_SIZE_DEPENDENT = {
        cheeseCrust: {
            name: 'Сырный борт',
            small: { price: 150, calories: 50 },
            large: { price: 300, calories: 50 }
        },
        cheddarParmesan: {
            name: 'Чедер и пармезан',
            small: { price: 150, calories: 50 },
            large: { price: 300, calories: 50 }
        }
    };

    static TOPPINGS_SIMPLE = {
        creamyMozzarella: {
            name: 'Сливочная моцарелла',
            price: 50,
            calories: 20
        }
    };

    constructor(baseKey, sizeKey) {
        const base = Pizza.BASE_PIZZAS[baseKey];
        const size = Pizza.SIZES[sizeKey];

        if (!base) throw new Error(`Неверный тип пиццы. Доступны: ${Object.keys(Pizza.BASE_PIZZAS).join(', ')}`);
        if (!size) throw new Error(`Неверный размер. Доступны: ${Object.keys(Pizza.SIZES).join(', ')}`);

        this._base = base;
        this._size = size;
        this._toppings = [];
    }

    addTopping(key) {
        const topping = this._findTopping(key);
        if (!topping) return;
        if (!this._toppings.includes(key)) {
            this._toppings.push(key);
        }
    }

    removeTopping(key) {
        this._toppings = this._toppings.filter(t => t !== key);
    }

    getToppings() {
        return this._toppings.map(key => this._findTopping(key).name);
    }

    getBase() {
        return this._base.name;
    }

    getSize() {
        return this._size.name;
    }

    calculatePrice() {
        let total = this._base.price + this._size.extraPrice;
        for (const key of this._toppings) {
            const topping = this._findTopping(key);
            if ('price' in topping) {
                total += topping.price;
            } else {
                total += topping[this._getSizeKey()].price;
            }
        }
        return total;
    }

    calculateCalories() {
        let total = this._base.calories + this._size.extraCalories;
        for (const key of this._toppings) {
            const topping = this._findTopping(key);
            if ('calories' in topping) {
                total += topping.calories;
            } else {
                total += topping[this._getSizeKey()].calories;
            }
        }
        return total;
    }

    _findTopping(key) {
        return Pizza.TOPPINGS_SIMPLE[key] || Pizza.TOPPINGS_SIZE_DEPENDENT[key] || null;
    }

    _getSizeKey() {
        return this._size === Pizza.SIZES.small ? 'small' : 'large';
    }
}

// связь русских названий из HTML с ключами класса
const PIZZA_MAP = {
    'Маргарита': 'margarita',
    'Пепперони': 'pepperoni',
    'Баварская': 'bavarian'
};

const SIZE_MAP = {
    'маленькая': 'small',
    'большая': 'large'
};

const TOPPING_MAP = {
    'Сырный борт': 'cheeseCrust',
    'Сливочная моцарелла': 'creamyMozzarella',
    'Чедер и пармезан': 'cheddarParmesan'
};

let pizza = null;

function updateCart() {
    const btn = document.getElementById('cartButton');
    if (!pizza) {
        btn.textContent = 'Выберите пиццу';
        return;
    }
    btn.textContent = `Добавить в корзину за ${pizza.calculatePrice()}₽ (${pizza.calculateCalories()} Ккал)`;
}

function createPizza() {
    const baseEl = document.querySelector('.pizza-option.selected');
    const sizeEl = document.querySelector('input[name="size"]:checked');
    if (!baseEl || !sizeEl) return null;

    const baseKey = PIZZA_MAP[baseEl.dataset.type];
    const sizeKey = SIZE_MAP[sizeEl.value];
    return new Pizza(baseKey, sizeKey);
}

document.querySelectorAll('.pizza-option').forEach(option => {
    option.addEventListener('click', () => {
        document.querySelectorAll('.pizza-option').forEach(o => o.classList.remove('selected'));
        option.classList.add('selected');

        pizza = createPizza();
        if (pizza) {
            document.querySelectorAll('.addon.selected').forEach(addon => {
                const key = TOPPING_MAP[addon.dataset.name];
                if (key) pizza.addTopping(key);
            });
        }
        updateCart();
    });
});

document.querySelectorAll('input[name="size"]').forEach(input => {
    input.addEventListener('change', () => {
        pizza = createPizza();
        if (pizza) {
            document.querySelectorAll('.addon.selected').forEach(addon => {
                const key = TOPPING_MAP[addon.dataset.name];
                if (key) pizza.addTopping(key);
            });
        }
        updateCart();
    });
});

document.querySelectorAll('.addon').forEach(addon => {
    addon.addEventListener('click', () => {
        addon.classList.toggle('selected');
        if (!pizza) return;

        const key = TOPPING_MAP[addon.dataset.name];
        if (!key) return;

        if (addon.classList.contains('selected')) {
            pizza.addTopping(key);
        } else {
            pizza.removeTopping(key);
        }
        updateCart();
    });
});