define(function () {
    return {
        compose: function (funcs) {
            return function (x) {
                return funcs.reduce(function (v, func) {
                    return func(v);
                }, x);
            };
        },
        reduceObject: function (target, func) {
            var keys = Object.keys(target);
            var length = keys.length;
            var result = {};

            for (var i = 0; i < length; i++) {
                result[keys[i]] = func(target[keys[i]]);
            }
            return result;
        },
    };
});
