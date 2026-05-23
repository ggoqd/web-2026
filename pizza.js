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

    // цена зависит от размера
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

    // фиксированная цена
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

    // ищет добавку в обоих списках
    _findTopping(key) {
        return Pizza.TOPPINGS_SIMPLE[key] || Pizza.TOPPINGS_SIZE_DEPENDENT[key] || null;
    }

    _getSizeKey() {
        return this._size === Pizza.SIZES.small ? 'small' : 'large';
    }
}

const pizza = new Pizza('margarita', 'large');
pizza.addTopping('creamyMozzarella');
pizza.addTopping('cheeseCrust');
pizza.addTopping('cheddarParmesan');

console.log('Основа:', pizza.getBase());
console.log('Размер:', pizza.getSize());
console.log('Добавки:', pizza.getToppings().join(', '));
console.log('Цена:', pizza.calculatePrice(), 'руб.');
console.log('Калории:', pizza.calculateCalories(), 'Ккал.');

pizza.removeTopping('cheeseCrust');
console.log('\nПосле удаления сырного борта:');
console.log('Добавки:', pizza.getToppings().join(', '));
console.log('Цена:', pizza.calculatePrice(), 'руб.');
console.log('Калории:', pizza.calculateCalories(), 'Ккал.');