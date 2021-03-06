import Vue from 'vue';
import Vuetify, {
    VApp,
    VBtn,
    VCard,
    VCardText,
    VCardTitle,
    VContainer,
    VDataTable,
    VSelect,
    VTextarea,
    VTextField
} from 'vuetify/lib';
// Helpers
import de from 'vuetify/es5/locale/de';
import en from 'vuetify/es5/locale/en';
import 'vuetify/src/stylus/app.styl';

Vue.use(Vuetify, {
    iconfont: 'md',
    lang: {
        locales: {de, en},
        current: 'de'
    },
    components: {
        VApp,
        VBtn,
        VDataTable,
        VTextField,
        VTextarea,
        VSelect,
        VCard,
        VCardText,
        VCardTitle,
        VContainer
    }
});
