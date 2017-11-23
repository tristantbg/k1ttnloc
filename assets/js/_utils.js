function addSourceToVideo(element, src, type) {
    var source = document.createElement('source');

    source.src = src;
    source.type = type;

    element.appendChild(source);
}

function isInt(value) {
  return !isNaN(value) && 
         parseInt(Number(value)) == value && 
         !isNaN(parseInt(value, 10));
}

function doSearch(url, query, container, callback) {
    query = query.replace(/\s\s+/g, '+');
    query = encodeURIComponent(query).replace(/%20/g, "+").replace(/%2B/g, "+");
    $.ajax({
        url: url+'?q='+query,
        type: 'GET',
        dataType: 'html',
        success: function(data, statut) {
            response = $(data).find(container).html();
            $(container).html(response);
            callback();
        },
        error: function(resultat, statut, erreur) {},
        complete: function(resultat, statut) {}
    });
}

function debounce(fn, delay) {
    var timer = null;
    return function() {
        var context = this,
            args = arguments;
        clearTimeout(timer);
        timer = setTimeout(function() {
            fn.apply(context, args);
        }, delay);
    };
}

function isInStr(needle, str) {
    return str.indexOf(needle) !== -1;
}

function getAllUrlParams(url) {
    // get query string from url (optional) or window
    var queryString = url ? url.split('?')[1] : window.location.search.slice(1);
    // we'll store the parameters here
    var obj = {};
    // if query string exists
    if (queryString) {
        // stuff after # is not part of query string, so get rid of it
        queryString = queryString.split('#')[0];
        // split our query string into its component parts
        var arr = queryString.split('&');
        for (var i = 0; i < arr.length; i++) {
            // separate the keys and the values
            var a = arr[i].split('=');
            // in case params look like: list[]=thing1&list[]=thing2
            var paramNum = undefined;
            var paramName = a[0].replace(/\[\d*\]/, function(v) {
                paramNum = v.slice(1, -1);
                return '';
            });
            // set parameter value (use 'true' if empty)
            var paramValue = typeof(a[1]) === 'undefined' ? true : a[1];
            // (optional) keep case consistent
            paramName = paramName.toLowerCase();
            paramValue = paramValue.toLowerCase();
            // if parameter name already exists
            if (obj[paramName]) {
                // convert value to array (if still string)
                if (typeof obj[paramName] === 'string') {
                    obj[paramName] = [obj[paramName]];
                }
                // if no array index number specified...
                if (typeof paramNum === 'undefined') {
                    // put the value on the end of the array
                    obj[paramName].push(paramValue);
                }
                // if array index number specified...
                else {
                    // put the value at that index number
                    obj[paramName][paramNum] = paramValue;
                }
            }
            // if param name doesn't exist yet, set it
            else {
                obj[paramName] = paramValue;
            }
        }
    }
    return obj;
}
// Vanilla scrolling
function easeInOutQuad(t, b, c, d) {
    t /= d / 2;
    if (t < 1) return c / 2 * t * t + b;
    t--;
    return -c / 2 * (t * (t - 2) - 1) + b;
}

function easeInOutCubic(t, b, c, d) {
    if ((t /= d / 2) < 1) return c / 2 * t * t * t + b;
    return c / 2 * ((t -= 2) * t * t + 2) + b;
}

function jump(target, options) {
    var start = window.pageYOffset;
    console.log(start);
    var opt = {
        duration: options.duration,
        offset: options.offset || 0,
        callback: options.callback,
        easing: options.easing || easeInOutCubic
    };
    var distance = typeof target === 'string' ? opt.offset + document.querySelector(target).getBoundingClientRect().top : target;
    var duration = typeof opt.duration === 'function' ? opt.duration(distance) : opt.duration;
    var timeStart = null,
        timeElapsed;
    requestAnimationFrame(function(time) {
        timeStart = time;
        loop(time);
    });

    function loop(time) {
        timeElapsed = time - timeStart;
        window.scrollTo(0, opt.easing(timeElapsed, start, distance - start, duration));
        if (timeElapsed < duration) requestAnimationFrame(loop)
        else end();
    }

    function end() {
        window.scrollTo(0, distance);
        typeof opt.callback === 'function' && opt.callback();
        timeStart = null;
    }
}