define(function () {
    return {
        parseData: function (dataList) {
            var parsedData = {
                co2: [],
                temp: [],
                rh: [],
            };
            return dataList.reduce(function (reduced, data) {
                // [TODO] should change to record_at
                var timestamp = new Date(data.created_at).getTime();
                reduced.co2.push([timestamp, data.co2]);
                reduced.temp.push([timestamp, data.temp]);
                reduced.rh.push([timestamp, data.rh]);
                return reduced;
            }, parsedData);
        },
    };
});
