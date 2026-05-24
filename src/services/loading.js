const loading = {
    start () {
        const loader = document.querySelector('.loader-wrapper')
        if (loader) {
            loader.classList.remove('loader-wrapper_stopped')
        }
    },

    stop () {
        const loader = document.querySelector('.loader-wrapper')
        if (loader) {
            loader.classList.add('loader-wrapper_stopped')
        }
    }
}

export default loading