import api from "../services/api.js";

const TodosRepository = {
    async getAll() {
        return await api('/todo');
    },

    async create(data) {
        return await api('/todo', {
            method: 'POST',
            body: JSON.stringify(data)
        });
    },

    async update(id, data) {
        return await api(`/todo/${id}`, {
            method: 'PUT',
            body: JSON.stringify(data)
        });
    },

    async delete(id) {
        return await api(`/todo/${id}`, {
            method: 'DELETE'
        });
    }
};

export default TodosRepository;