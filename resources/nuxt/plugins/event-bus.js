import Vue from 'vue'

const Bus = new Vue();

export default ({ app },inject) => {
    // inject our i18n instance into the app root to be used in middleware
    // we assume a store/index.js file has been defined and the variable 'locale' defined on store, we'll go into this in detail in the next code snippet
    inject('bus', Bus)
}