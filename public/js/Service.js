class Service {
	/**
	 *  Service.setLoading();
   *  Service.show('.loading');
   *  Service.show('.backdrop-mobile-menu');
	 */

	static loader = () => {
		return '<div class="p-6 w-full flex justify-center"><span class="loader"></span></div>';
	}

	static empty = (message) => {
		return `<div class="p-2 w-full text-center text-gray">${message}</div>`;
	}

	static setLoading = () => {
    const backdrop = document.createElement("div");
    backdrop.className = "backdrop-mobile-menu";

    const loadingWrapper = document.createElement("div");
    loadingWrapper.className = "loading";
    const loadingInner = document.createElement("div");
    loadingWrapper.appendChild(loadingInner);

    document.body.prepend(backdrop);
    document.body.prepend(loadingWrapper);
	}

	static stopSubmit = (form, status) => {
		let btn = form.querySelector('button[type="submit"]');
    if (btn) btn.disabled = status;
	}

	static show = (selector) => {
	  const element = document.querySelector(selector);
	  element.style.display = 'block';
	}

	static hide = (selector) => {
	  const element = document.querySelector(selector);
	  element.style.display = 'none';
	}

	// Axios methods (like get and delete) only take two arguments: (url, config),
	// while others (like post, put, and patch) take three: (url, data, config).
	static exec = async (http_method, endpoint, config = {}, data = null) => {
	  try {
	    let method = http_method.toLowerCase();

	    let _config = {
	      ...config, // Spread existing config first
	      headers: { ...axios_headers, ...(config.headers || {}) },
	    };

	    let response;
	    if (["get", "delete"].includes(method)) {
	      response = await Axios[method](endpoint, _config); // No data for GET/DELETE
	    } else {
	      response = await Axios[method](endpoint, data, _config); // Data needed for POST/PUT/PATCH
	    }

			console.log(response.data);
      return response.data;

	  } catch (error) {
	    console.table([
      	{ Error_message: error.message },
      	{ Error_message: error.response.data.message }
    	]);
	    // throw error;
	  }
	};

}
