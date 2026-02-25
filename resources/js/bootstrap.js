import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

import Swal from "sweetalert2/dist/sweetalert2.all.js";
window.Swal = Swal;