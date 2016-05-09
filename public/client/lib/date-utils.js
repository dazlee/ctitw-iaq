define(['moment', 'ramda'], function (moment, R) {

    var format = R.curry(function (format, date) {
        return moment(date).format(format);
    });

    var formatYMD = format('YYYY-MM-DD');

    return {
        formatYMD: formatYMD,
    };
});
