import moment from 'moment';
import 'moment/locale/fr';
import Vue from 'vue';

moment.locale('fr');

Vue.prototype.moment = moment;