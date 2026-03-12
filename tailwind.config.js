/** @type {import('tailwindcss').Config} */
export default {
    content: ["./resources/**/*.blade.php", "./resources/**/*.js"],
    theme: {
        extend: {
            colors: {
                "unimed-green": "#006633", // Hijau Unimed
                "unimed-light": "#f0fdf4",
            },
        },
    },
    plugins: [],
};
