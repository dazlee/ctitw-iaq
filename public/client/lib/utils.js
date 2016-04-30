define(function () {
    return {
        compose: function (funcs) {
            return function (x) {
                return funcs.reduce(function (v, func) {
                    return func(v);
                }, x);
            };
        }
    };
});
