/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        "./assets/**/*.js",
        "./templates/**/*.html.twig",
    ],
    theme: {
        extend: {
            textColor: {
                primary: "#101828",
                secondary: "#344054",
                tertiary: "#475467",
                quarternary: "#667085",
                senary: "#D0D5DD",
                button: {
                    secondary: {
                        fg: "#344054"
                    }
                },
                nav: '#667085'
            },
            borderColor: {
                primary: "#D0D5DD",
                secondary: "#E4E7EC",
                button: {
                    secondary: "#D0D5DD",
                }
            },
            backgroundColor: {
                secondary: "#F9FAFB"
            },
            placeholderColor: {
                DEFAULT: "#667085"
            }
        },
    },
    plugins: [],
}

