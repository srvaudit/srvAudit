
/*
 |--------------------------------------------------------------------------
 | Laravel Spark Bootstrap
 |--------------------------------------------------------------------------
 |
 | First, we will load all of the "core" dependencies for Spark which are
 | libraries such as Vue and jQuery. This also loads the Spark helpers
 | for things such as HTTP calls, forms, and form validation errors.
 |
 | Next, we'll create the root Vue application for Spark. This will start
 | the entire application and attach it to the DOM. Of course, you may
 | customize this script as you desire and load your own components.
 |
 */
require('./bootstrap');
require('spark-bootstrap');

require('./components/bootstrap');
require('./timeago');

import VueAxios from 'vue-axios';
import axios from 'axios';

Vue.use(VueAxios, axios);

require('./components/command-list');
require('./components/chart');

var app = new Vue({
    mixins: [require('spark')]
});
