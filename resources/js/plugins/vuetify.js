// plugins/vuetify.js
import 'vuetify/styles'
import { createVuetify } from 'vuetify'

const customDarkTheme = {
  dark: true,
  colors: {
    primary: '#323499',
    secondary: '#DC3938',
    accent: '#FBD034',
    error: '#b71c1c',
    background: "#fff",
    header: "#272727",
    button: "292929",
    universal: '#1e85f1',
    nav: '#0f1e3d',
    excel: '#1D6F42',
    pdf: "#f40f02",
    primaryText: '#212B5C',
    secondaryText: '#758599',
    tableHeader: '#121212',

  },
};

const customLightTheme = {
  dark: false,
  colors: {
    primary: '#323499',
    secondary: '#DC3938',
    accent: '#FBD034',
    error: '#b71c1c',
    background: "#fff",
    header: "#fff",
    button: "#EEEEEE",
    universal: '#1e85f1',
    nav: '#1e85f1',
    excel: '#1D6F42',
    pdf: "#f40f02",
    primaryText: '#62728C',
    secondaryText: '#758599',
    tableHeader: '#212B5C',
  },
};

export default createVuetify({
  theme: {
    defaultTheme: "light",
    themes: {
      customDarkTheme,
      customLightTheme,
    },
  },
});