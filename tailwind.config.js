import defaultTheme from "tailwindcss/defaultTheme";
import forms from "@tailwindcss/forms";

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php",
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ["Figtree", ...defaultTheme.fontFamily.sans],
                poppins: ["Poppins", ...defaultTheme.fontFamily.sans],
                comfortaa: ["Comfortaa", ...defaultTheme.fontFamily.sans],
                mulish: ["Mulish", ...defaultTheme.fontFamily.sans],
            },
            colors: {
                mine: {
                    100: "#EEF9FD",
                    200: "#00AFEF",
                    300: "#354B9C",
                    400: "#F58634",
                },
            },
            boxShadow: {
                "mine": "4px 4px 10px 4px rgba(0, 0, 0, 0.1)",
            },
        },
    },

    plugins: [forms],
};
