function $_GET(k)
{
    var vars = {};

    window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m, key, value) {
        vars[key] = value;
    });

    if (typeof k == "undefined") {
        return vars;
    }

    return vars[k];
}