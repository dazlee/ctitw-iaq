define(function () {
    return {
        checkStatus: function (res) {
            if (res.status >= 200 && res.status < 300) {
                return res;
            } else {
                var error = new Error(res.statusText);
                error.res = res;
                throw error;
            }
        },
        parseJSON: function (res) {
            return res.json();
        },
    };
});
