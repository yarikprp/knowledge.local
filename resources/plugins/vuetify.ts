import { createVuetify } from 'vuetify';
import { md3 } from 'vuetify/blueprints';
import 'vuetify/styles';

export const vuetify = createVuetify({
  blueprint: md3,
  theme: {
    defaultTheme: 'light',
    themes: {
      light: {
        dark: false,
        colors: {
          background: '#FFFFFF',
          surface: '#FFFFFF',
          primary: '#1976D2',
          // ... другие цвета
        },
      },
      dark: {
        dark: true,
        colors: {
          background: '#121212',
          surface: '#1E1E1E',
          primary: '#BB86FC',
          // ... другие цвета
        },
      },
    },
  },
});
