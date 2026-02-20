moment.locale('es');

let axios_headers = {
  "Accept":         "application/json, text/javascript, */*; q=0.01",
  "Content-Type":   "application/json; charset=UTF-8",
};

let root = window.location.protocol == 'https:' ? 'https://portalgibanibb.com' : 'http://localhost/cotizacion';

const Cotizador = axios.create({
  baseURL: root,
  headers: axios_headers,
});

const Axios = axios.create({
  baseURL: root,
  headers: axios_headers,
});

let formData_header = {
  headers: {
    'Content-Type': 'multipart/form-data'
  }
};
