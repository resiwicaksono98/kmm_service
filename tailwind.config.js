/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
    ],
    theme: {
        extend: {
            colors: {
                primary: "#240750" , // navy
                secondary: "#7c85b3",
                teal: "#57A6A1",
                red: '#eb6f53'
            },
        },
    },
    plugins: [],
};
