import api from './api'

export const getToken = () => {

    return localStorage.getItem('token')
}

export const getUser = () => {


const user = localStorage.getItem('user')

if (
    !user ||
    user === 'undefined'
) {
    return null
}

try {

    return JSON.parse(user)

} catch {

    return null
}


}


export const setAuth = (token, user) => {

    localStorage.setItem('token', token)

    localStorage.setItem(
        'user',
        JSON.stringify(user)
    )
}

export const logout = () => {

    localStorage.removeItem('token')

    localStorage.removeItem('user')
}

export const fetchUser = async () => {

    const token = getToken()

    if (!token) return null

    try {

        const response = await api.get('/user', {

            headers: {
                Authorization: `Bearer ${token}`
            }
        })

        localStorage.setItem(
            'user',
            JSON.stringify(response.data)
        )

        return response.data

    } catch {

        logout()

        return null
    }
}