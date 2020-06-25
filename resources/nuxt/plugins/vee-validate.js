import Vue from 'vue'
import VeeValidate from 'vee-validate'

Vue.use(VeeValidate)

export default ({ app }) => {
    // inject our i18n instance into the app root to be used in middleware
    // we assume a store/index.js file has been defined and the variable 'locale' defined on store, we'll go into this in detail in the next code snippet
    app.validator = new VeeValidate({
        inject: true,
        i18nRootKey: 'validations',
        i18n:app.i18n
    })
}