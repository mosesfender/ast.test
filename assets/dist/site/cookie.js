var cookie;
(function (cookie) {
    function read(name) {
        var result = new RegExp('(?:^|; )' + encodeURIComponent(name) + '=([^;]*)').exec(document.cookie);
        return result ? result[1] : null;
    }
    cookie.read = read;
    function write(name, value, days) {
        if (!days) {
            days = 365 * 20;
        }
        var date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        var expires = "; expires=" + date.toUTCString();
        document.cookie = name + "=" + value + expires + "; path=/";
    }
    cookie.write = write;
    function remove(name) {
        write(name, "", -1);
    }
    cookie.remove = remove;
})(cookie || (cookie = {}));
