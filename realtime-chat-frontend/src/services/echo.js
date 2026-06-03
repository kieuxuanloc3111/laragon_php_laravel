import Echo from 'laravel-echo'

import Pusher from 'pusher-js'

window.Pusher = Pusher

const echo = new Echo({


broadcaster: 'pusher',

key: 'cf7f5eea4abfbc14c529',

cluster: 'ap1',

forceTLS: true,

enabledTransports: ['ws', 'wss'],

authorizer: (channel) => {

    return {

        authorize: async (socketId, callback) => {

            try {

                const response = await fetch(
                    'http://realtime-chat-api.test/api/pusher/auth',
                    {
                        method: 'POST',

                        headers: {

                            'Content-Type': 'application/json',

                            'Accept': 'application/json',

                            Authorization:
                                `Bearer ${localStorage.getItem('token')}`,
                        },

                        body: JSON.stringify({

                            socket_id: socketId,

                            channel_name: channel.name,
                        }),
                    }
                )

                const data = await response.json()

                callback(null, data)

            } catch (error) {

                console.log(error)

                callback(error, null)
            }
        }
    }
}


})

export default echo
