import Auth from "../services/auth.js";
import location from "../services/location.js";
import loading from "../services/loading.js";
import TodosRepository from "../repository/todos.js";

const init = async () => {
    const { ok: isLogged } = await Auth.me();

    if (!isLogged) {
        loading.stop();
        return location.login();
    } else {
        loading.stop();
    }

    const mainEl = document.querySelector('.main');

    mainEl.innerHTML = `
        <form id="add-todo-form" class="form" style="margin-bottom: 20px;">
            <label class="text-field">
                <input
                    type="text"
                    class="text-field__input"
                    name="description"
                    placeholder="Новая задача"
                >
                <span class="text-field__error"></span>
            </label>
            <button type="submit" class="button">Добавить</button>
        </form>
        <div id="todos-list"></div>
    `;

    const formEl = document.getElementById('add-todo-form');
    const listEl = document.getElementById('todos-list');

    const renderTodos = (todos) => {
        if (!todos || todos.length === 0) {
            listEl.innerHTML = '<p>Задач пока нет</p>';
            return;
        }

        listEl.innerHTML = todos.map(todo => `
            <div class="todo-item" data-id="${todo.id}" style="display: flex; align-items: center; gap: 10px; margin-bottom: 10px;">
                <input 
                    type="checkbox" 
                    class="todo-checkbox" 
                    ${todo.completed ? 'checked' : ''}
                >
                <span class="todo-description" style="${todo.completed ? 'text-decoration: line-through;' : ''}">
                    ${todo.description}
                </span>
                <button class="todo-delete button" style="margin-left: auto;">Удалить</button>
            </div>
        `).join('');
    };

    const loadTodos = async () => {
        try {
            loading.start();
            const response = await TodosRepository.getAll();
            if (response.ok) {
                renderTodos(response.data);
            }
        } catch (error) {
            console.error(error);
        } finally {
            loading.stop();
        }
    };

    formEl.addEventListener('submit', async (event) => {
        event.preventDefault();

        const input = formEl.querySelector('input[name="description"]');
        const errorEl = formEl.querySelector('.text-field__error');
        const description = input.value.trim();

        if (!description) {
            errorEl.textContent = 'Поле обязательно';
            return;
        }

        errorEl.textContent = '';

        try {
            loading.start();
            const response = await TodosRepository.create({ description });
            if (response.ok) {
                input.value = '';
                await loadTodos();
            }
        } catch (error) {
            console.error(error);
        } finally {
            loading.stop();
        }
    });

    listEl.addEventListener('change', async (event) => {
        const target = event.target;
        if (!target.classList.contains('todo-checkbox')) return;

        const todoItem = target.closest('.todo-item');
        const id = Number(todoItem.dataset.id);
        const completed = target.checked;

        try {
            loading.start();
            const response = await TodosRepository.update(id, { completed });

            if (response.ok) {
                const descEl = todoItem.querySelector('.todo-description');
                descEl.style.textDecoration = completed ? 'line-through' : '';
            } else {
                target.checked = !completed;
            }
        } catch (error) {
            target.checked = !completed;
            console.error(error);
        } finally {
            loading.stop();
        }
    });

    listEl.addEventListener('click', async (event) => {
        const target = event.target;

        if (target.classList.contains('todo-delete')) {
            const todoItem = target.closest('.todo-item');
            const id = Number(todoItem.dataset.id);

            try {
                loading.start();
                const response = await TodosRepository.delete(id);
                if (response.ok) {
                    todoItem.remove();
                    if (listEl.children.length === 0) {
                        listEl.innerHTML = '<p>Задач пока нет</p>';
                    }
                }
            } catch (error) {
                console.error(error);
            } finally {
                loading.stop();
            }
        }
    });

    await loadTodos();
};

if (document.readyState === 'loading') {
    document.addEventListener("DOMContentLoaded", init);
} else {
    init();
}