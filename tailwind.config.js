const { variants } = require('tailwindcss/defaultConfig')
const { fontFamily, spacing } = require('tailwindcss/defaultTheme')

module.exports = {
  theme: {
    extend: {
      colors: {
        inherit: 'inherit',
      },
      fontFamily: {
        mono: ['Roboto Mono', ...fontFamily.mono],
      },
      spacing: {
        '2px': '2px',
      },
      borderWidth: {
        3: '3px',
      }
    }
  },
  plugins: [
    require('tailwindcss-spaced-items')({ values: spacing }),
    require('tailwindcss-visuallyhidden')(),
    require('tailwindcss-skip-link')(),
    require('tailwindcss-vuejs')()
  ],
  variants: {
    borderStyle: [...variants.borderStyle, 'hover', 'focus'],
    borderWidth: [...variants.borderStyle, 'hover', 'focus']
  }
}
