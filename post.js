const getPostId = () => {
    const url = new URL(window.location.href)
    return url.searchParams.get('id')
}

const loadPost = async (id) => {
    try {
        const res = await fetch(`https://jsonplaceholder.typicode.com/posts/${id}`)
        if (!res.ok) throw new Error(`Ошибка загрузки поста: ${res.status}`)
        return await res.json()
    } catch (error) {
        console.error(error)
        return null
    }
}

const loadComments = async (id) => {
    try {
        const res = await fetch(`https://jsonplaceholder.typicode.com/posts/${id}/comments`)
        if (!res.ok) throw new Error(`Ошибка загрузки комментариев: ${res.status}`)
        return await res.json()
    } catch (error) {
        console.error(error)
        return []
    }
}

const renderComments = (comments) => {
    if (!comments.length) return '<p>Комментариев нет</p>'
    return comments.map(c => `
        <div class="comment">
            <strong>${c.name}</strong> (${c.email})
            <p>${c.body}</p>
        </div>
    `).join('')
}

const init = async () => {
    const id = getPostId()
    if (!id) {
        document.getElementById('postTitle').textContent = 'Пост не найден'
        return
    }

    const post = await loadPost(id)
    if (!post) {
        document.getElementById('postTitle').textContent = 'Пост не найден'
        return
    }

    document.getElementById('postTitle').textContent = post.title
    document.getElementById('postBody').textContent = post.body

    const comments = await loadComments(id)
    document.getElementById('commentsList').innerHTML = renderComments(comments)
}

init()