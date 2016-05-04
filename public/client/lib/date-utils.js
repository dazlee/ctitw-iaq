define(['moment', 'curry'], function (moment, curry) {

    var format = curry(function (format, date) {
        return moment(date).format(format);
    });

    var formatYMD = format('YYYY-MM-DD');

    return {
        formatYMD: formatYMD,
    };
});
