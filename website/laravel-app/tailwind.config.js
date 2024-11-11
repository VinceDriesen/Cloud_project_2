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
                background: 'var(--background)',
                foreground: 'var(--foreground)',
                bordercolor: 'var(--border)',
                component: 'var(--component)',
                'active-component': 'var(--active-component)',
                'primary-text-color': 'var(--primary-text-color)',
                'navigation-dropdown': 'var(--navigation-dropdown)',
                'text-shadow': 'var(--text-shadow)',
                'secondary-text-color': 'var(--secondary-text-color)',
                action: 'var(--action)',
                'action-hover': 'var(--action-hover)',
                'header-footer': 'var(--HeaderAndFooter)',
                'active-footer-header': 'var(--active-footer-header)',
                'link-color': 'var(--link-color)'
            },
            borderColor: (theme) => ({
                ...theme('colors'),
                'component-border': 'var(--component_border)'
            }),

            screens: {
                desktop: '1080px',
                mobile: '320px',
                tablet: '768px'
            },

            keyframes: {

                slidedown: {
                    '0%': { transform: 'translateY(-10px)', opacity: '0' },
                    '10%': { opacity: '0.05' },
                    '30%': { opacity: '0.1' },
                    '50%': { opacity: '0.3' },
                    '70%': { opacity: '0.6' },
                    '100%': { transform: 'translateY(0)', opacity: '1', visibility: 'visible' }
                }
            },

            animation: {
                'ease-in-down': 'slidedown 1s both ease-in-out'
            },
        }
    },
    plugins: [require('daisyui')]
}
