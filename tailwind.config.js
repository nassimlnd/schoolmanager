/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        "./assets/**/*.js",
        "./templates/**/*.html.twig",
    ],
    theme: {
        extend: {
            textColor: {
                primary: '#101828',
                secondary: '#344054',
                tertiary: '#475467',
            },
            borderColor: {
                primary: '#D0D5DD'
            },
            placeholderColor: {
                placeholder: '#667085'
            }
        },
    },
    plugins: [],
}

