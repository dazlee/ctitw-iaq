define(function () {
    return {
        compose: function (funcs) {
            return function (x) {
                return funcs.reduce(function (v, func) {
                    return func(v);
                }, x);
            };
        },
        mapObject: function (target, func) {
            var keys = Object.keys(target);
            var length = keys.length;
            var result = {};
            var key;

            for (var i = 0; i < length; i++) {
                key = keys[i];
                result[key] = func(target[key], key);
            }
            return result;
        },
        reduceObject: function (target, func, result) {
            var keys = Object.keys(target);
            var length = keys.length;
            var key;

            for (var i = 0; i < length; i++) {
                key = keys[i];
                result = func(result, target[key], key);
            }
            return result;
        },
    };
});
