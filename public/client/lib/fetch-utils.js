define(["utils"], function (utils) {
    /**
     * check whether res status is between 200-299 or not
     * @param  {response} res response object return from fetch
     * @return {object}     if pass return response, otherwise throws error
     */
    function checkStatus (res) {
        if (res.status >= 200 && res.status < 300) {
            return res;
        } else {
            var error = new Error(res.statusText);
            error.res = res;
            throw error;
        }
    }

    /**
     * parse response data to json object
     * @param  {response} res response returned from fetch
     * @return {object}     json object
     */
    function parseJSON (res) {
        return res.json();
    }

    /**
     * wrapped function for fetching json object back from API
     * @param  {string} url     api path
     * @param  {object} headers options of request header
     * @return {fetch promise}         return fetch promise object to chain the actions
     */
    function fetchJSON (url, headers) {
        var statusCheckerJSONParser = utils.compose([checkStatus, parseJSON]);
        var _headers = headers || {};
        return fetch(url, {
            method: "GET",
            headers: _headers
        })
        .then(statusCheckerJSONParser);
    }

    function queryStringify(query) {
        var queryString = utils.reduceObject(query, function (reduced, value, key) {
            return reduced + (key + "=" + value + "&");
        }, "");
        if (queryString.length > 0) {
            queryString = queryString.slice(0, -1);
        }
        return queryString;
    }

    function formUrl (endpoint, queries) {
        var queryString = queryStringify(queries);
        return endpoint + "?" + queryString;
    }

    return {
        checkStatus: checkStatus,
        parseJSON: parseJSON,
        fetchJSON: fetchJSON,
        queryStringify: queryStringify,
        formUrl: formUrl,
    };
});
