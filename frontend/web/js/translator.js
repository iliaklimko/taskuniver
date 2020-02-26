function Translator(source) {
    // $.extend({}, out)
    var extend = function(out) {
        out = out || {};
        for (var i = 1; i < arguments.length; i++) {
            if (!arguments[i])
                continue;
            for (var key in arguments[i]) {
                if (arguments[i].hasOwnProperty(key))
                    out[key] = arguments[i][key];
            }
        }
        return out;
    };
    this.source = extend({}, source);
    this.get = function(key) {
        for (var k in this.source) {
            if (k === key) {
                return this.source[k];
            }
        }
        return key;
    };
}
